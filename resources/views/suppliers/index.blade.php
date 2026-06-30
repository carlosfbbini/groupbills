<x-layouts.app :title="'Fornecedores'">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Fornecedores</h2>
        <a href="{{ route('suppliers.create') }}" class="rounded bg-slate-900 px-4 py-2 text-white">Novo fornecedor</a>
    </div>
    <div class="rounded bg-white p-4 shadow">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b">
                    <th class="py-2">Nome</th>
                    <th>CNPJ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($suppliers as $supplier)
                <tr class="border-b">
                    <td class="py-2">{{ $supplier->name }}</td>
                    <td class="py-2">{{ $supplier->cnpj }}</td>
                    <td class="py-2 text-right">
                        <a class="mr-2 text-blue-700" href="{{ route('suppliers.edit', $supplier) }}">Editar</a>
                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-700">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="py-4" colspan="3">Nenhum fornecedor cadastrado.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $suppliers->links() }}</div>
    </div>
</x-layouts.app>
