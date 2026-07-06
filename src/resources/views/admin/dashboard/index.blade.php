@extends('layouts.admin')

@section('title', 'Dashboard - Administración')

@section('content')
<div class="space-y-8">
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Disponibles</p>
            <p class="text-3xl font-bold text-green-400 mt-2">{{ $disponibles }}</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Ocupadas</p>
            <p class="text-3xl font-bold text-red-400 mt-2">{{ $ocupadas }}</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Reservadas</p>
            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $reservadas }}</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Limpieza</p>
            <p class="text-3xl font-bold text-blue-400 mt-2">{{ $limpieza }}</p>
        </div>
    </div>

    {{-- Room Grid --}}
    <div>
        <h2 class="text-xl font-bold text-white mb-4">Estado de Habitaciones</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($habitaciones as $habitacion)
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-5 border border-white/5 hover:border-[#D4AF37]/30 transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-white font-bold text-lg">{{ $habitacion->numero }}</span>
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        @if($habitacion->estado === 'Disponible') bg-green-500/20 text-green-400
                        @elseif($habitacion->estado === 'Ocupada') bg-red-500/20 text-red-400
                        @elseif($habitacion->estado === 'Reservada') bg-yellow-500/20 text-yellow-400
                        @elseif($habitacion->estado === 'Limpieza') bg-blue-500/20 text-blue-400
                        @else bg-gray-500/20 text-gray-400 @endif">
                        {{ $habitacion->estado }}
                    </span>
                </div>
                <p class="text-gray-500 text-xs">{{ $habitacion->categoria }}</p>
                @if($habitacion->estado === 'Ocupada' && $habitacion->reservaActiva)
                <div class="mt-3" x-data="{ tiempo: '00:00:00', iniciado: false }" x-init="iniciado = true; setInterval(() => {
                    if(!iniciado) return;
                    const inicio = new Date('{{ $habitacion->reservaActiva->hora_ingreso ? $habitacion->reservaActiva->hora_ingreso->format('Y-m-d H:i:s') : '' }}').getTime();
                    const ahora = new Date().getTime();
                    const diff = Math.max(0, ahora - inicio);
                    const h = Math.floor(diff / 3600000).toString().padStart(2,'0');
                    const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2,'0');
                    const s = Math.floor((diff % 60000) / 1000).toString().padStart(2,'0');
                    tiempo = h + ':' + m + ':' + s;
                }, 1000)">
                    <p class="text-[#D4AF37] text-xs font-mono" x-text="tiempo"></p>
                    <p class="text-gray-500 text-xs mt-1">Tiempo transcurrido</p>
                </div>
                @endif
                @if($habitacion->estado === 'Reservada' && $habitacion->reservaActiva)
                    <p class="text-[#D4AF37] text-xs mt-2">{{ \Carbon\Carbon::parse($habitacion->reservaActiva->hora)->format('H:i') }} hrs</p>
                    <a href="{{ route('admin.reservas.show', $habitacion->reservaActiva) }}" class="text-xs text-[#D4AF37] hover:underline mt-1 inline-block">Ver reserva</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Quick actions + Today's reservations --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <h2 class="text-xl font-bold text-white mb-4">Acciones Rápidas</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.reservas.create') }}" class="bg-white/5 hover:bg-white/10 rounded-2xl p-4 border border-white/5 text-center transition-all">
                    <svg class="w-6 h-6 text-[#D4AF37] mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="text-white text-sm">Crear Reserva</span>
                </a>
                <a href="{{ route('admin.habitaciones.index') }}" class="bg-white/5 hover:bg-white/10 rounded-2xl p-4 border border-white/5 text-center transition-all">
                    <svg class="w-6 h-6 text-[#D4AF37] mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span class="text-white text-sm">Gestionar Habitaciones</span>
                </a>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold text-white mb-4">Reservas de Hoy</h2>
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 divide-y divide-white/5">
                @forelse($reservasHoy as $reserva)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="text-white font-medium">{{ $reserva->nombre ?? 'Sin nombre' }}</p>
                        <p class="text-gray-400 text-sm">{{ $reserva->rut }} - Hab. {{ $reserva->habitacion?->numero ?? 'Sin asignar' }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full font-medium
                        @if($reserva->estado === 'Reservada') bg-yellow-500/20 text-yellow-400
                        @elseif($reserva->estado === 'Ingresada') bg-green-500/20 text-green-400
                        @else bg-gray-500/20 text-gray-400 @endif">
                        {{ $reserva->estado }}
                    </span>
                </div>
                @empty
                <p class="p-6 text-gray-500 text-sm text-center">No hay reservas para hoy.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
