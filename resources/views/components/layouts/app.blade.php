<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'GroupBills' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-slate-100 text-slate-900">
<div class="min-h-screen">
    <header class="bg-white shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center justify-between p-4">
            <h1 class="text-xl font-semibold">GroupBills</h1>
            @auth
                <nav class="flex items-center gap-4 text-sm">
                    <a href="{{ route('reports.index') }}" class="hover:underline">Relatórios</a>
                    <a href="{{ route('groups.index') }}" class="hover:underline">Grupos</a>
                    <a href="{{ route('companies.index') }}" class="hover:underline">Empresas</a>
                    <a href="{{ route('expenses.index') }}" class="hover:underline">Despesas</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded bg-slate-800 px-3 py-1 text-white">Sair</button>
                    </form>
                </nav>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-7xl p-4">
        @if (session('status'))
            <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded border border-red-300 bg-red-50 px-3 py-2 text-sm text-red-700">
                <ul class="list-disc pl-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>
</div>
</body>
</html>
