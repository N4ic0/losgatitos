@extends('layouts.admin')

@section('title', 'Reservas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Reservas</h1>
    <a href="{{ route('admin.reservas.create') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nueva Reserva</a>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-6 py-4">RUT</th>
                    <th class="text-left px-6 py-4">Nombre</th>
                    <th class="text-left px-6 py-4">Fecha</th>
                    <th class="text-left px-6 py-4">Hora</th>
                    <th class="text-left px-6 py-4">Hab.</th>
                    <th class="text-left px-6 py-4">Estado</th>
                    <th class="text-left px-6 py-4">Total</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($reservas as $reserva)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 text-gray-300 font-mono text-xs">{{ $reserva->rut }}</td>
                    <td class="px-6 py-4 text-white">{{ $reserva->nombre ?? 'Anónimo' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $reserva->fecha->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $reserva->habitacion?->numero ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-3 py-1 rounded-full font-medium
                            @if($reserva->estado === 'Reservada') bg-yellow-500/20 text-yellow-400
                            @elseif($reserva->estado === 'Ingresada') bg-green-500/20 text-green-400
                            @elseif($reserva->estado === 'Finalizada') bg-blue-500/20 text-blue-400
                            @else bg-gray-500/20 text-gray-400 @endif">
                            {{ $reserva->estado }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-300">${{ number_format($reserva->total, 0, '', '.') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.reservas.show', $reserva) }}" class="text-[#D4AF37] hover:text-white transition-colors text-sm font-medium">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
