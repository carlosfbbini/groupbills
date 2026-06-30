<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        return view('companies.index', [
            'companies' => Company::query()->with('group')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('companies.create', [
            'groups' => Group::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:15'],
        ]);

        Company::query()->create($validated);

        return redirect()->route('companies.index')->with('status', 'Empresa criada com sucesso.');
    }

    public function show(Company $company): RedirectResponse
    {
        return redirect()->route('companies.edit', $company);
    }

    public function edit(Company $company): View
    {
        return view('companies.edit', [
            'company' => $company,
            'groups' => Group::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:15'],
        ]);

        $company->update($validated);

        return redirect()->route('companies.index')->with('status', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('companies.index')->with('status', 'Empresa removida com sucesso.');
    }
}
