<x-layouts.app :title="'Grupos'">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Grupos</h2>
        <a href="{{ route('groups.create') }}" class="rounded bg-slate-900 px-4 py-2 text-white">Novo grupo</a>
    </div>
    <div class="rounded bg-white p-4 shadow">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b"><th class="py-2">Nome</th><th></th></tr></thead>
            <tbody>
            @forelse($groups as $group)
                <tr class="border-b">
                    <td class="py-2">{{ $group->name }}</td>
                    <td class="py-2 text-right">
                        <a class="mr-2 text-blue-700" href="{{ route('groups.edit', $group) }}">Editar</a>
                        <form method="POST" action="{{ route('groups.destroy', $group) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-700">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="py-4" colspan="2">Nenhum grupo cadastrado.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $groups->links() }}</div>
    </div>
</x-layouts.app>
