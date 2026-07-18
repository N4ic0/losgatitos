<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Panel Admin') - Motel Los Gatitos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <link rel="icon" href="/img/icono.png">
    <style>
        @media (max-width: 768px) {
            body { -webkit-overflow-scrolling: touch; }
            #promocionModal.modal { margin: 0; padding: 0; height: 100vh; overflow: hidden; }
            #promocionModal .modal-dialog { margin: 0; max-width: 100%; height: 100vh; }
            #promocionModal .modal-content { border-radius: 0; height: 100%; }
            #promocionModal .modal-body { overflow-y: auto; -webkit-overflow-scrolling: touch; padding-bottom: 80px; }
            #promocionModal .nav-tabs { flex-wrap: nowrap; overflow-x: auto; white-space: nowrap; padding-bottom: 0.5rem; }
            #promocionModal .nav-tabs .nav-link { padding: 0.4rem 0.6rem; font-size: 0.75rem; white-space: nowrap; }
            #promocionModal .modal-header, #promocionModal .modal-footer { padding: 0.75rem; }
            #promocionModal .row, #promocionModal .col { margin-bottom: 0.5rem; }
            #promocionModal input[type="number"], #promocionModal input[type="date"], #promocionModal input[type="time"], #promocionModal select { font-size: 16px !important; }
            #promocionModal .btn { min-height: 44px; }
        }

        /* ===== DataTables Responsive: flecha dtr-control en modo oscuro ===== */
        /* El plugin usa border-trick (triángulo CSS). El color por defecto es negro semi-transparente
           y solo se sobreescribe si html tiene data-bs-theme=dark. Como nuestro html no lo tiene,
           lo forzamos aquí directamente. */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control::before,
        table.dataTable.dtr-column > tbody > tr > td.dtr-control::before,
        table.dataTable.dtr-column > tbody > tr > th.dtr-control::before {
            border-left-color: #D4AF37 !important;
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control.arrow-right::before,
        table.dataTable.dtr-column > tbody > tr > td.dtr-control.arrow-right::before {
            border-right-color: #D4AF37 !important;
            border-left-color: transparent !important;
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr.dtr-expanded > td.dtr-control::before,
        table.dataTable.dtr-inline.collapsed > tbody > tr.dtr-expanded > th.dtr-control::before,
        table.dataTable.dtr-column > tbody > tr.dtr-expanded > td.dtr-control::before,
        table.dataTable.dtr-column > tbody > tr.dtr-expanded > th.dtr-control::before {
            border-top-color: #D4AF37 !important;
            border-left-color: transparent !important;
            border-right-color: transparent !important;
        }
    </style>
</head>
<body class="bg-[#0a0a0a] text-white font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside id="sidebar" class="hidden lg:flex lg:flex-col bg-black border-r border-white/5 overflow-y-auto transition-all duration-300 shrink-0" style="width: 5rem; min-width: 5rem;" data-expanded="false">
            <div class="h-14 border-b border-white/5 flex items-center shrink-0">
                <button id="sidebarToggle" class="flex items-center justify-center w-full h-full text-gray-400 hover:text-white transition-colors" type="button">
                    <svg id="sidebarIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span id="sidebarTitle" class="hidden text-white font-bold text-sm ml-3 tracking-widest">GATITOS</span>
                </button>
            </div>
            <nav class="flex-1 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Dashboard">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Dashboard</span>
                </a>
                <a href="{{ route('admin.habitaciones.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.habitaciones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Habitaciones">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Habitaciones</span>
                </a>
                <a href="{{ route('admin.reservas.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.reservas.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Reservas">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Reservas</span>
                </a>
                <a href="{{ route('admin.tarifas.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.tarifas.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Tarifas">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Tarifas</span>
                </a>
                <a href="{{ route('admin.promociones.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.promociones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Promociones">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Promociones</span>
                </a>
                <a href="{{ route('admin.productos.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.productos.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Productos">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Productos</span>
                </a>
                <a href="{{ route('admin.promocion-productos.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.promocion-productos.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Paquetes">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Paquetes</span>
                </a>
                <a href="{{ route('admin.ocupaciones.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.ocupaciones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Ocupaciones">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Ocupaciones</span>
                </a>
                <a href="{{ route('admin.feriados.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.feriados.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Feriados">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Feriados</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Roles">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Roles</span>
                </a>
                <a href="{{ route('admin.usuarios.index') }}" class="nav-link-dash flex items-center h-10 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-200 {{ request()->routeIs('admin.usuarios.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}" title="Usuarios">
                    <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    </div>
                    <span class="label-dash hidden text-sm whitespace-nowrap">Usuarios</span>
                </a>
            </nav>
            <div class="p-2 border-t border-white/5 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link-dash flex items-center h-10 w-full rounded-xl text-gray-400 hover:text-red-400 hover:bg-red-500/5 transition-all duration-200" title="Cerrar Sesión">
                        <div class="icon-wrap-dash flex items-center justify-center w-10 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                        <span class="label-dash hidden text-sm whitespace-nowrap">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile Header --}}
        <div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-black/90 backdrop-blur-md border-b border-white/5">
            <div class="flex items-center justify-between px-4 h-14">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <img src="/img/logo.png" alt="Logo" class="h-7 w-auto">
                    <span class="text-white font-bold text-sm">Los Gatitos</span>
                </a>
                <button class="text-white p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Sidebar (Offcanvas) --}}
        <div id="mobileSidebar" class="offcanvas offcanvas-start bg-black border-r border-white/5 text-white" tabindex="-1" data-bs-backdrop="true" data-bs-scroll="true">
            <div class="offcanvas-header border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <img src="/img/logo.png" alt="Logo" class="h-7 w-auto">
                    <span class="text-white font-bold">Los Gatitos</span>
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-4">
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.dashboard') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.habitaciones.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.habitaciones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Habitaciones</a>
                    <a href="{{ route('admin.reservas.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.reservas.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Reservas</a>
                    <a href="{{ route('admin.tarifas.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.tarifas.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Tarifas</a>
                    <a href="{{ route('admin.promociones.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.promociones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Promociones</a>
                    <a href="{{ route('admin.productos.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.productos.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Productos</a>
                    <a href="{{ route('admin.promocion-productos.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.promocion-productos.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Paquetes</a>
                    <a href="{{ route('admin.ocupaciones.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.ocupaciones.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Ocupaciones</a>
                    <a href="{{ route('admin.roles.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.roles.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Roles</a>
                    <a href="{{ route('admin.usuarios.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.usuarios.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Usuarios</a>
                    <a href="{{ route('admin.feriados.index') }}" class="d-block px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 {{ request()->routeIs('admin.feriados.*') ? 'bg-white/5 text-[#D4AF37]' : '' }}">Feriados</a>
                    <hr class="border-white/5 my-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 rounded-xl text-gray-400 hover:text-red-400">Cerrar Sesión</button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- Main Content --}}
        <main id="mainContent" class="flex-1 overflow-y-auto pt-14 lg:pt-0">
            <div class="p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                <div class="alert alert-dismissible fade show mb-4 px-4 py-3 rounded-xl bg-green-900/50 border border-green-700 text-green-200" role="alert">
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-dismissible fade show mb-4 px-4 py-3 rounded-xl bg-red-900/50 border border-red-700 text-red-200" role="alert">
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')

<style>
#sidebar[data-expanded="false"] .nav-link-dash {
    justify-content: center;
    margin-left: auto;
    margin-right: auto;
    width: 2.5rem;
}
#sidebar[data-expanded="true"] .nav-link-dash {
    justify-content: flex-start;
    padding-left: 0.75rem;
    width: 100%;
}
#sidebar[data-expanded="false"] .nav-link-dash .icon-wrap-dash {
    width: auto;
}
.nav-link-dash {
    position: relative;
}
.nav-link-dash.text-\[\#D4AF37\]::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 1.25rem;
    background-color: #D4AF37;
    border-radius: 0 2px 2px 0;
}
#sidebar[data-expanded="false"] .nav-link-dash.text-\[\#D4AF37\]::before {
    left: -2px;
    width: 2px;
    height: 1rem;
}
.nav-link-dash:hover {
    transform: scale(1.02);
}
#sidebar[data-expanded="false"] .nav-link-dash:hover {
    transform: scale(1.1);
}
#sidebar {
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.05) transparent;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('sidebar');
    var toggle = document.getElementById('sidebarToggle');
    var title = document.getElementById('sidebarTitle');
    var icon = document.getElementById('sidebarIcon');
    var labels = document.querySelectorAll('.label-dash');

    if (toggle) {
        toggle.addEventListener('click', function() {
            var expanded = sidebar.getAttribute('data-expanded') === 'true';

            if (expanded) {
                sidebar.setAttribute('data-expanded', 'false');
                sidebar.style.width = '5rem';
                if (title) title.classList.add('hidden');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
                labels.forEach(function(l) { l.classList.add('hidden'); });
            } else {
                sidebar.setAttribute('data-expanded', 'true');
                sidebar.style.width = '14rem';
                if (title) title.classList.remove('hidden');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                labels.forEach(function(l) { l.classList.remove('hidden'); });
            }
        });
    }
});
</script>

</body>
</html>
