<x-layouts.app :title="'Importar Despesas'">
    <h2 class="mb-4 text-xl font-semibold">Importar despesas via planilha</h2>

    <form method="POST" action="{{ route('expenses.import.store') }}" enctype="multipart/form-data"
          class="max-w-xl space-y-4 rounded bg-white p-4 shadow">
        @csrf

        <div>
            <label class="mb-1 block text-sm font-medium">Arquivo</label>
            <input type="file" name="file" required accept=".csv,.xls,.xlsx,.ods"
                   class="w-full rounded border border-slate-300 px-3 py-2 text-sm"/>
            <p class="mt-1 text-xs text-slate-500">Formatos aceitos: CSV, XLS, XLSX, ODS. A planilha deve conter as
                colunas <strong>CNPJ, Fatura, Parcela, Vencimento, Info, Valor</strong>. Todas as abas são lidas; abas
                sem coluna CNPJ são ignoradas.</p>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Fornecedor (opcional)</label>
            <select name="supplier_id" class="w-full rounded border border-slate-300 px-3 py-2">
                <option value="">Nenhum</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                        {{ $supplier->name }} — {{ $supplier->cnpj }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500">Todas as despesas importadas serão vinculadas a este fornecedor.</p>
        </div>

        <div class="flex gap-3">
            <button class="rounded bg-slate-900 px-4 py-2 text-white">Importar</button>
            <a href="{{ route('expenses.index') }}" class="rounded border border-slate-300 px-4 py-2 text-sm">Cancelar</a>
        </div>
    </form>

    @if(session('import_errors'))
        <div class="mt-6 max-w-xl rounded border border-amber-300 bg-amber-50 p-4">
            <p class="mb-2 text-sm font-medium text-amber-800">Linhas ignoradas durante a importação:</p>
            <ul class="list-disc pl-5 text-sm text-amber-700">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</x-layouts.app>
