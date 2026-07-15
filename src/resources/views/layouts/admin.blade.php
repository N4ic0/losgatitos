<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') - Motel Los Gatitos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/img/icono.png">
</head>
<body x-data="{ sidebarOpen: false }" class="bg-[#0a0a0a] text-white font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="hidden lg:flex lg:flex-col bg-black border-r border-white/5 overflow-y-auto transition-all duration-300">
            <div class="p-6 border-b border-white/5">
                <button @click="sidebarOpen = !sidebarOpen" class="flex items-center space-x-3 w-full text-left">
                    <img :src="sidebarOpen ? '/img/logo.png' : '/img/logosmall.png'" alt="Logo" class="h-8 w-auto shrink-0">
                    <span x-show="sidebarOpen" class="text-white font-bold text-lg">Los Gatitos</span>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Dashboard' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>
                <a href="{{ route('admin.habitaciones.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.habitaciones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Habitaciones' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span x-show="sidebarOpen">Habitaciones</span>
                </a>
                <a href="{{ route('admin.reservas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.reservas.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Reservas' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span x-show="sidebarOpen">Reservas</span>
                </a>
                <a href="{{ route('admin.tarifas.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.tarifas.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Tarifas' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-show="sidebarOpen">Tarifas</span>
                </a>
                <a href="{{ route('admin.promociones.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.promociones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Promociones' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    <span x-show="sidebarOpen">Promociones</span>
                </a>
                <a href="{{ route('admin.productos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.productos.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Productos' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span x-show="sidebarOpen">Productos</span>
                </a>
                <a href="{{ route('admin.promocion-productos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.promocion-productos.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Paquetes' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span x-show="sidebarOpen">Paquetes</span>
                </a>
                <a href="{{ route('admin.ocupaciones.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.ocupaciones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Ocupaciones' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span x-show="sidebarOpen">Ocupaciones</span>
                </a>
                <a href="{{ route('admin.feriados.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.feriados.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Feriados' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span x-show="sidebarOpen">Feriados</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Roles' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span x-show="sidebarOpen">Roles</span>
                </a>
                <a href="{{ route('admin.usuarios.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.usuarios.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" :title="!sidebarOpen ? 'Usuarios' : ''">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    <span x-show="sidebarOpen">Usuarios</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-red-400 hover:bg-red-500/5 transition-all duration-200 w-full" :title="!sidebarOpen ? 'Cerrar Sesión' : ''">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span x-show="sidebarOpen">Cerrar Sesión</span>
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
                <button @click="$refs.mobileSidebar.classList.toggle('-translate-x-full')" class="text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Sidebar --}}
        <div x-ref="mobileSidebar" class="fixed inset-0 z-50 -translate-x-full transition-transform duration-300 lg:hidden">
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
                    <a href="{{ route('admin.productos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Productos</a>
                    <a href="{{ route('admin.promocion-productos.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Paquetes</a>
                    <a href="{{ route('admin.ocupaciones.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Ocupaciones</a>
                    <a href="{{ route('admin.roles.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Roles</a>
                    <a href="{{ route('admin.usuarios.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5">Usuarios</a>
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
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-4 flex items-center justify-between px-4 py-3 rounded-xl bg-green-900/50 border border-green-700 text-green-200">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-300 hover:text-green-100">&times;</button>
                </div>
                @endif
                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" class="mb-4 flex items-center justify-between px-4 py-3 rounded-xl bg-red-900/50 border border-red-700 text-red-200">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-300 hover:text-red-100">&times;</button>
                </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
