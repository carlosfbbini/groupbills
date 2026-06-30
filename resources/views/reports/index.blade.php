<x-layouts.app :title="'Relatórios'">
    <h2 class="mb-4 text-xl font-semibold">Relatórios de despesas</h2>

    <div class="mb-6 rounded bg-white p-4 shadow">
        <h3 class="mb-2 font-semibold">Boletos a vencer hoje</h3>
        <ul class="space-y-1 text-sm">
            @forelse($dueToday as $expense)
                <li>{{ $expense->company->name }} - {{ $expense->invoice }} | R$ {{ number_format((float) $expense->amount, 2, ',', '.') }}</li>
            @empty
                <li>Nenhum boleto vence hoje.</li>
            @endforelse
        </ul>
    </div>

    <div class="mb-6 rounded bg-white p-4 shadow">
        <h3 class="mb-2 font-semibold">Boletos vencidos</h3>
        <ul class="space-y-1 text-sm">
            @forelse($overdue as $expense)
                <li>{{ $expense->company->name }} - {{ $expense->invoice }} | Venc.: {{ $expense->due_date->format('d/m/Y') }}</li>
            @empty
                <li>Nenhum boleto vencido pendente.</li>
            @endforelse
        </ul>
    </div>

    <div class="rounded bg-white p-4 shadow">
        <h3 class="mb-2 font-semibold">Histórico de pagamentos</h3>
        <ul class="space-y-1 text-sm">
            @forelse($paymentHistory as $expense)
                <li>{{ $expense->company->name }} - {{ $expense->invoice }} | Pago em: {{ $expense->paid_at?->format('d/m/Y') }}</li>
            @empty
                <li>Nenhum pagamento registrado.</li>
            @endforelse
        </ul>
    </div>
</x-layouts.app>
