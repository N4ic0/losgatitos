<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') - Motel Los Gatitos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/img/icono.png">
</head>
<body class="bg-[#0a0a0a] text-white font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col w-64 bg-black border-r border-white/5 overflow-y-auto">
            <div class="p-6 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="/img/logo.png" alt="Logo" class="h-8 w-auto">
                    <span class="text-white font-bold text-lg">Los Gatitos</span>
                </a>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/5 text-[#D4AF37]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.habitaciones.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.habitaciones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Habitaciones</span>
                </a>
                <a href="{{ route('admin.reservas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.reservas.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Reservas</span>
                </a>
                <a href="{{ route('admin.tarifas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.tarifas.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Tarifas</span>
                </a>
                <a href="{{ route('admin.promociones.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.promociones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    <span>Promociones</span>
                </a>
                <a href="{{ route('admin.feriados.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.feriados.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Feriados</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-red-400 hover:bg-red-500/5 transition-all duration-200 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile Header --}}
        <div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-black/90 backdrop-blur-md border-b border-white/5">
            <div class="flex items-center justify-between px-4 h-14">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <img src="/img/logo.png" alt="Logo" class="h-7 w-auto">
                    <span class="text-white font-bold">Los Gatitos</span>
                </a>
                <button x-data @click="$refs.mobileSidebar.classList.toggle('-translate-x-full')" class="text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Sidebar --}}
        <div x-data x-ref="mobileSidebar" class="fixed inset-0 z-50 -translate-x-full transition-transform duration-300 lg:hidden">
            <div @click="$refs.mobileSidebar.classList.add('-translate-x-full')" class="absolute inset-0 bg-black/50"></div>
            <div class="relative w-64 h-full bg-black border-r border-white/5 p-6 overflow-y-auto">
                <div class="flex items-center justify-between mb-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                        <img src="/img/logo.png" alt="Logo" class="h-7 w-auto">
                        <span class="text-white font-bold">Los Gatitos</span>
                    </a>
                    <button @click="$refs.mobileSidebar.classList.add('-translate-x-full')" class="text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Dashboard</a>
                    <a href="{{ route('admin.habitaciones.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Habitaciones</a>
                    <a href="{{ route('admin.reservas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Reservas</a>
                    <a href="{{ route('admin.tarifas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Tarifas</a>
                    <a href="{{ route('admin.promociones.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Promociones</a>
                    <a href="{{ route('admin.feriados.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Feriados</a>
                    <hr class="border-white/5 my-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-red-400 w-full">Cerrar Sesión</button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto lg:ml-0 pt-14 lg:pt-0">
            <div class="p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ session('success') }}',
                            timer: 2000,
                            showConfirmButton: true,
                            confirmButtonColor: '#D4AF37',
                            confirmButtonText: 'Aceptar',
                            timerProgressBar: true,
                        });
                    });
                </script>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
