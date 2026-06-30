<x-layouts.app :title="'Despesas'">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Despesas</h2>
        <div class="flex gap-2">
            <a href="{{ route('expenses.import') }}" class="rounded border border-slate-900 px-4 py-2 text-sm text-slate-900">Importar planilha</a>
            <a href="{{ route('expenses.create') }}" class="rounded bg-slate-900 px-4 py-2 text-white">Nova despesa</a>
        </div>
    </div>
    <div class="rounded bg-white p-4 shadow">
        <table class="w-full text-left text-sm">
            <thead>
            <tr class="border-b">
                <th class="py-2">Empresa</th><th>Fatura</th><th>Parcela</th><th>Valor</th><th>Vencimento</th><th>Status</th><th>Pago em</th><th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($expenses as $expense)
                <tr class="border-b">
                    <td class="py-2">{{ $expense->company->name }}</td>
                    <td>{{ $expense->invoice }}</td>
                    <td>{{ $expense->installment }}</td>
                    <td>R$ {{ number_format((float) $expense->amount, 2, ',', '.') }}</td>
                    <td>{{ $expense->due_date->format('d/m/Y') }}</td>
                    <td>{{ $expense->status === 'paid' ? 'Pago' : 'Pendente' }}</td>
                    <td>{{ $expense->paid_at?->format('d/m/Y') ?? '-' }}</td>
                    <td class="text-right">
                        <a class="mr-2 text-blue-700" href="{{ route('expenses.edit', $expense) }}">Editar</a>
                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-700">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="py-4" colspan="8">Nenhuma despesa cadastrada.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $expenses->links() }}</div>
    </div>
</x-layouts.app>
