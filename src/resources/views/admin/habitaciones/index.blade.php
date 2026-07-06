@extends('layouts.admin')

@section('title', 'Habitaciones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Habitaciones</h1>
    <a href="{{ route('admin.habitaciones.create') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nueva Habitación</a>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-6 py-4">N°</th>
                    <th class="text-left px-6 py-4">Categoría</th>
                    <th class="text-left px-6 py-4">Estado</th>
                    <th class="text-left px-6 py-4">Observaciones</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($habitaciones as $habitacion)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 text-white font-medium">{{ $habitacion->numero }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $habitacion->categoria }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-3 py-1 rounded-full font-medium
                            @if($habitacion->estado === 'Disponible') bg-green-500/20 text-green-400
                            @elseif($habitacion->estado === 'Ocupada') bg-red-500/20 text-red-400
                            @elseif($habitacion->estado === 'Reservada') bg-yellow-500/20 text-yellow-400
                            @elseif($habitacion->estado === 'Limpieza') bg-blue-500/20 text-blue-400
                            @else bg-gray-500/20 text-gray-400 @endif">
                            {{ $habitacion->estado }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 max-w-xs truncate">{{ $habitacion->observaciones ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.habitaciones.edit', $habitacion) }}" class="text-[#D4AF37] hover:text-white transition-colors text-sm font-medium">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
