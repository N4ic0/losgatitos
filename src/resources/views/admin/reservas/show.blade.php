@extends('layouts.admin')

@section('title', 'Reserva #' . $reserva->id)

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">Reserva #{{ $reserva->id }}</h1>
        <a href="{{ route('admin.reservas.index') }}" class="text-gray-400 hover:text-white transition-colors text-sm">← Volver</a>
    </div>

    <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider">RUT</p>
                <p class="text-white font-medium">{{ $reserva->rut }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider">Nombre</p>
                <p class="text-white font-medium">{{ $reserva->nombre ?? 'Anónimo' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider">Fecha</p>
                <p class="text-white font-medium">{{ $reserva->fecha->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider">Hora</p>
                <p class="text-white font-medium">{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }} hrs</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider">Habitación</p>
                <p class="text-white font-medium">{{ $reserva->habitacion?->numero ?? 'Sin asignar' }} - {{ $reserva->habitacion?->categoria ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider">Personas</p>
                <p class="text-white font-medium">{{ $reserva->personas }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider">Estado</p>
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    @if($reserva->estado === 'Reservada') bg-yellow-500/20 text-yellow-400
                    @elseif($reserva->estado === 'Ingresada') bg-green-500/20 text-green-400
                    @else bg-blue-500/20 text-blue-400 @endif">
                    {{ $reserva->estado }}
                </span>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider">Total</p>
                <p class="text-[#D4AF37] font-bold text-xl">${{ number_format($reserva->total, 0, '', '.') }}</p>
            </div>
        </div>

        @if($reserva->observaciones)
        <div>
            <p class="text-gray-400 text-xs uppercase tracking-wider mb-2">Observaciones</p>
            <p class="text-gray-300">{{ $reserva->observaciones }}</p>
        </div>
        @endif

        <div class="border-t border-white/5 pt-6 flex flex-wrap gap-3">
            @if($reserva->estado === 'Reservada')
            <a href="{{ route('admin.reservas.edit', $reserva) }}" class="bg-white/10 hover:bg-white/20 text-white px-5 py-2.5 rounded-xl transition-all text-sm">Editar Reserva</a>
            <form action="{{ route('admin.reservas.asignar', $reserva) }}" method="POST" class="inline">
                @csrf
                <select name="habitacion_id" required class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm outline-none">
                    <option value="" class="bg-gray-900">Seleccionar habitación...</option>
                    @foreach(\App\Models\Habitacion::where('estado', 'Disponible')->get() as $h)
                    <option value="{{ $h->id }}" class="bg-gray-900">{{ $h->numero }} - {{ $h->categoria }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-green-500/20 hover:bg-green-500/30 text-green-400 px-5 py-2.5 rounded-xl transition-all text-sm font-medium">Asignar e Ingresar</button>
            </form>
            @endif
            @if($reserva->estado === 'Ingresada')
            <form action="{{ route('admin.reservas.liberar', $reserva) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 px-5 py-2.5 rounded-xl transition-all text-sm font-medium">Liberar Habitación</button>
            </form>
            <form action="{{ route('admin.reservas.cobrar-horas', $reserva) }}" method="POST" class="inline flex items-center space-x-2">
                @csrf
                <input type="number" name="horas" min="1" max="24" placeholder="Horas" class="w-20 bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-sm outline-none">
                <button type="submit" class="bg-[#D4AF37]/20 hover:bg-[#D4AF37]/30 text-[#D4AF37] px-5 py-2.5 rounded-xl transition-all text-sm font-medium">Cobrar Horas</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
