@extends('layouts.admin')

@section('title', 'Promociones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Promociones</h1>
    <a href="{{ route('admin.promociones.create') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nueva Promoción</a>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-6 py-4">Título</th>
                    <th class="text-left px-6 py-4">Horario</th>
                    <th class="text-left px-6 py-4">Valor / Hrs Beneficio</th>
                    <th class="text-left px-6 py-4">Tarifas</th>
                    <th class="text-left px-6 py-4">Inicio</th>
                    <th class="text-left px-6 py-4">Fin</th>
                    <th class="text-left px-6 py-4">Estado</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($promociones as $promocion)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 text-white font-medium">{{ $promocion->titulo }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $promocion->desde ? $promocion->desde.' - '.$promocion->hasta : '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">
                        {{ $promocion->valor ? '$'.number_format($promocion->valor, 0, '', '.') : '-' }}
                        @if($promocion->horas_beneficio)
                            <span class="text-xs text-gray-400 ml-1">/ {{ $promocion->horas_beneficio }}h beneficio</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ $promocion->tarifas ? implode(', ', array_map(fn($t) => str_replace('_', ' ', $t), $promocion->tarifas)) : '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $promocion->fecha_inicio->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $promocion->fecha_fin->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-3 py-1 rounded-full font-medium {{ $promocion->activo ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $promocion->activo ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.promociones.edit', $promocion) }}" class="text-[#D4AF37] hover:text-white transition-colors text-sm font-medium">Editar</a>
                        <form action="{{ route('admin.promociones.destroy', $promocion) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta promoción?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-sm font-medium">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
