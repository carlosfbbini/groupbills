<x-layouts.app :title="'Editar Empresa'">
    <h2 class="mb-4 text-xl font-semibold">Editar empresa</h2>
    <form method="POST" action="{{ route('companies.update', $company) }}" class="max-w-lg space-y-3 rounded bg-white p-4 shadow">
        @csrf @method('PUT')
        <div>
            <label class="mb-1 block text-sm">Grupo</label>
            <select name="group_id" required class="w-full rounded border border-slate-300 px-3 py-2">
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" @selected(old('group_id', $company->group_id) == $group->id)>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm">Nome</label>
            <input name="name" value="{{ old('name', $company->name) }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <button class="rounded bg-slate-900 px-4 py-2 text-white">Atualizar</button>
    </form>
</x-layouts.app>
