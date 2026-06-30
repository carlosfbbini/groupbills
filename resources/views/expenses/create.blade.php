<x-layouts.app :title="'Nova Despesa'">
    <h2 class="mb-4 text-xl font-semibold">Nova despesa</h2>
    <form method="POST" action="{{ route('expenses.store') }}" class="grid max-w-3xl grid-cols-1 gap-3 rounded bg-white p-4 shadow md:grid-cols-2">
        @csrf
        <div>
            <label class="mb-1 block text-sm">Empresa</label>
            <select name="company_id" required class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="">Selecione</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }} ({{ $company->group->name }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm">Fatura</label>
            <input name="invoice" value="{{ old('invoice') }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <div>
            <label class="mb-1 block text-sm">Parcela</label>
            <input type="number" min="1" name="installment" value="{{ old('installment', 1) }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <div>
            <label class="mb-1 block text-sm">Valor</label>
            <input type="number" min="0.01" step="0.01" name="amount" value="{{ old('amount') }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <div>
            <label class="mb-1 block text-sm">Vencimento</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <div>
            <label class="mb-1 block text-sm">Status</label>
            <select name="status" required class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="pending" @selected(old('status', 'pending') === 'pending')>Pendente</option>
                <option value="paid" @selected(old('status') === 'paid')>Pago</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm">Data de pagamento (opcional)</label>
            <input type="date" name="paid_at" value="{{ old('paid_at') }}" class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <div class="md:col-span-2">
            <button class="rounded bg-slate-900 px-4 py-2 text-white">Salvar</button>
        </div>
    </form>
</x-layouts.app>
