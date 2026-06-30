<x-layouts.app :title="'Empresas'">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Empresas</h2>
        <a href="{{ route('companies.create') }}" class="rounded bg-slate-900 px-4 py-2 text-white">Nova empresa</a>
    </div>
    <div class="rounded bg-white p-4 shadow">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b"><th class="py-2">Empresa</th><th>Grupo</th><th></th></tr></thead>
            <tbody>
            @forelse($companies as $company)
                <tr class="border-b">
                    <td class="py-2">{{ $company->name }}</td>
                    <td class="py-2">{{ $company->group->name }}</td>
                    <td class="py-2 text-right">
                        <a class="mr-2 text-blue-700" href="{{ route('companies.edit', $company) }}">Editar</a>
                        <form method="POST" action="{{ route('companies.destroy', $company) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-700">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="py-4" colspan="3">Nenhuma empresa cadastrada.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $companies->links() }}</div>
    </div>
</x-layouts.app>
