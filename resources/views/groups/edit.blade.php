<x-layouts.app :title="'Editar Grupo'">
    <h2 class="mb-4 text-xl font-semibold">Editar grupo</h2>
    <form method="POST" action="{{ route('groups.update', $group) }}" class="max-w-lg space-y-3 rounded bg-white p-4 shadow">
        @csrf @method('PUT')
        <div>
            <label class="mb-1 block text-sm">Nome</label>
            <input name="name" value="{{ old('name', $group->name) }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <button class="rounded bg-slate-900 px-4 py-2 text-white">Atualizar</button>
    </form>
</x-layouts.app>
