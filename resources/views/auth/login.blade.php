<x-layouts.app :title="'Login'">
    <div class="mx-auto mt-10 max-w-md rounded bg-white p-6 shadow">
        <h2 class="mb-4 text-lg font-semibold">Entrar</h2>
        <form method="POST" action="{{ route('login.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="mb-1 block text-sm">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded border border-slate-300 px-3 py-2"/>
            </div>
            <div>
                <label class="mb-1 block text-sm">Senha</label>
                <input type="password" name="password" required class="w-full rounded border border-slate-300 px-3 py-2"/>
            </div>
            <button class="w-full rounded bg-slate-900 px-4 py-2 text-white">Entrar</button>
        </form>
        <p class="mt-3 text-sm">Ainda não possui conta? <a href="{{ route('register') }}" class="text-blue-700 underline">Cadastre-se</a></p>
    </div>
</x-layouts.app>
