<?php

namespace App\Http\Controllers;

use App\Imports\ExpensesImport;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index(): View
    {
        return view('expenses.index', [
            'expenses' => Expense::query()
                ->with(['company.group'])
                ->orderBy('due_date')
                ->orderByDesc('id')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('expenses.create', [
            'companies' => Company::query()->with('group')->orderBy('name')->get(),
            'suppliers' => Supplier::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        Expense::query()->create($validated);

        return redirect()->route('expenses.index')->with('status', 'Despesa criada com sucesso.');
    }

    public function show(Expense $expense): RedirectResponse
    {
        return redirect()->route('expenses.edit', $expense);
    }

    public function edit(Expense $expense): View
    {
        return view('expenses.edit', [
            'expense' => $expense,
            'companies' => Company::query()->with('group')->orderBy('name')->get(),
            'suppliers' => Supplier::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $this->validatePayload($request, $expense);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('status', 'Despesa atualizada com sucesso.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('status', 'Despesa removida com sucesso.');
    }

    public function importForm(): View
    {
        return view('expenses.import', [
            'suppliers' => Supplier::query()->orderBy('name')->get(),
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file'        => ['required', 'file', 'mimes:csv,txt,xls,xlsx,ods'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
        ]);

        // Build a normalized CNPJ → Company lookup map (digits only)
        $companiesByCnpj = Company::all()->mapWithKeys(
            fn ($c) => [preg_replace('/[^0-9]/', '', $c->cnpj ?? '') => $c]
        );

        $sheets   = Excel::toCollection(new ExpensesImport, $request->file('file'));
        $imported = 0;
        $errors   = [];

        foreach ($sheets as $sheet) {
            // WithHeadingRow consumes row 1 as keys; first() is the first data row.
            // If the sheet has no data or no 'cnpj' column, skip it.
            $firstRow = $sheet->first();
            if (!$firstRow || !$firstRow->has('cnpj')) {
                continue;
            }

            foreach ($sheet as $rowIndex => $row) {
                $rawCnpj = $row->get('cnpj');
                if (empty($rawCnpj)) {
                    continue;
                }

                $cnpj    = preg_replace('/[^0-9]/', '', (string) $rawCnpj);
                $company = $companiesByCnpj->get($cnpj);

                if (!$company) {
                    $errors[] = "Linha " . ($rowIndex + 2) . ": CNPJ {$rawCnpj} não encontrado no sistema.";
                    continue;
                }

                $dueDate = $this->parseDateValue($row->get('vencimento'));
                if (!$dueDate) {
                    $errors[] = "Linha " . ($rowIndex + 2) . ": data de vencimento invalida ({$row->get('vencimento')}).";
                    continue;
                }

                $invoice     = (string) ($row->get('fatura') ?? '');
                $installment = max(1, (int) ($row->get('parcela') ?? 1));
                $amount      = $this->parseAmountValue($row->get('valor'));

                if (Expense::where('company_id', $company->id)
                    ->where('invoice', $invoice)
                    ->where('installment', $installment)
                    ->exists()) {
                    $errors[] = "Linha " . ($rowIndex + 2) . ": duplicada (Fatura: {$invoice}, Parcela: {$installment}).";
                    continue;
                }

                Expense::create([
                    'company_id'  => $company->id,
                    'supplier_id' => $request->input('supplier_id') ?: null,
                    'invoice'     => $invoice,
                    'description' => $row->get('info'),
                    'installment' => $installment,
                    'amount'      => $amount,
                    'due_date'    => $dueDate,
                    'status'      => 'pending',
                ]);

                $imported++;
            }
        }

        $message = "{$imported} despesa(s) importada(s) com sucesso.";

        if (!empty($errors)) {
            return redirect()->route('expenses.import')
                ->with('status', $message)
                ->with('import_errors', $errors);
        }

        return redirect()->route('expenses.index')->with('status', $message);
    }

    private function parseDateValue(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof \Carbon\Carbon) {
            return $value->toDateString();
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->toDateString();
        }

        if (is_numeric($value)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value);
                return Carbon::instance($date)->toDateString();
            } catch (\Throwable) {
                return null;
            }
        }

        try {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', (string) $value)) {
                return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
            }
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function parseAmountValue(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = preg_replace('/[^0-9,.]/', '', (string) $value);

        // Brazilian format: "1.234,56" → remove thousands sep, swap decimal comma
        if (preg_match('/,\d{1,2}$/', $value)) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        return (float) $value;
    }

    private function validatePayload(Request $request, ?Expense $expense = null): array
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'invoice' => ['required', 'string', 'max:255'],
            'installment' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('expenses')->where(fn ($q) => $q
                    ->where('company_id', $request->input('company_id'))
                    ->where('invoice', $request->input('invoice'))
                )->ignore($expense?->id),
            ],
            'amount' => ['required', 'numeric', 'gt:0'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'in:paid,pending'],
            'paid_at' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'pending') {
            $validated['paid_at'] = null;
        } elseif (empty($validated['paid_at'])) {
            $validated['paid_at'] = Carbon::now()->toDateString();
        }

        return $validated;
    }
}
