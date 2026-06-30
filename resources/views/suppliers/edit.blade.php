<x-layouts.app :title="'Editar Fornecedor'">
    <h2 class="mb-4 text-xl font-semibold">Editar fornecedor</h2>
    <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="max-w-lg space-y-3 rounded bg-white p-4 shadow">
        @csrf @method('PUT')
        <div>
            <label class="mb-1 block text-sm">Nome</label>
            <input name="name" value="{{ old('name', $supplier->name) }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
        </div>
        <div>
            <label class="mb-1 block text-sm">CNPJ</label>
            <input name="cnpj" value="{{ old('cnpj', $supplier->cnpj) }}" required maxlength="18" class="w-full rounded border border-slate-300 px-3 py-2" placeholder="00.000.000/0000-00"/>
        </div>
        <button class="rounded bg-slate-900 px-4 py-2 text-white">Atualizar</button>
    </form>
</x-layouts.app>
