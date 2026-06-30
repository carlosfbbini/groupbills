<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Expense;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

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
