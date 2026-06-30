<x-layouts.app :title="'Nova Empresa'">
    <h2 class="mb-4 text-xl font-semibold">Nova empresa</h2>
    <form method="POST" action="{{ route('companies.store') }}" class="max-w-lg space-y-3 rounded bg-white p-4 shadow">
        @csrf
        <div>
            <label class="mb-1 block text-sm">Grupo</label>
            <select name="group_id" required class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="">Selecione</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" @selected(old('group_id') == $group->id)>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm">Nome</label>
            <input name="name" value="{{ old('name') }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <button class="rounded bg-slate-900 px-4 py-2 text-white">Salvar</button>
    </form>
</x-layouts.app>
