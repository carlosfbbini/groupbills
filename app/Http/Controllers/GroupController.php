<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(): View
    {
        return view('groups.index', [
            'groups' => Group::query()->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Group::query()->create($validated);

        return redirect()->route('groups.index')->with('status', 'Grupo criado com sucesso.');
    }

    public function show(Group $group): RedirectResponse
    {
        return redirect()->route('groups.edit', $group);
    }

    public function edit(Group $group): View
    {
        return view('groups.edit', ['group' => $group]);
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $group->update($validated);

        return redirect()->route('groups.index')->with('status', 'Grupo atualizado com sucesso.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        $group->delete();

        return redirect()->route('groups.index')->with('status', 'Grupo removido com sucesso.');
    }
}
