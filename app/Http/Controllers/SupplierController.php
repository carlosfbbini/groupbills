<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        return view('suppliers.index', [
            'suppliers' => Supplier::query()->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:suppliers,cnpj'],
        ]);

        Supplier::query()->create($validated);

        return redirect()->route('suppliers.index')->with('status', 'Fornecedor criado com sucesso.');
    }

    public function show(Supplier $supplier): RedirectResponse
    {
        return redirect()->route('suppliers.edit', $supplier);
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', ['supplier' => $supplier]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:suppliers,cnpj,' . $supplier->id],
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('status', 'Fornecedor atualizado com sucesso.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('status', 'Fornecedor removido com sucesso.');
    }
}
