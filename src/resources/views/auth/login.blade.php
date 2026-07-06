<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-white">Iniciar Sesión</h1>
        <p class="text-gray-400 text-sm mt-2">Accede al panel de administración</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-gray-300 text-sm font-medium mb-2">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-gray-300 text-sm font-medium mb-2">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                <span class="ml-2 text-sm text-gray-400">Recordarme</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-bold py-3 rounded-xl transition-all duration-300 shadow-lg shadow-[#D4AF37]/25 hover:shadow-[#D4AF37]/40">
            Ingresar
        </button>
    </form>
</x-guest-layout>
