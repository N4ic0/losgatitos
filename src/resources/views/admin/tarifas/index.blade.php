@extends('layouts.admin')

@section('title', 'Tarifas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Tarifas</h1>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-6 py-4">Categoría</th>
                    <th class="text-left px-6 py-4">Tipo</th>
                    <th class="text-left px-6 py-4">D-J</th>
                    <th class="text-left px-6 py-4">Viernes</th>
                    <th class="text-left px-6 py-4">Sábado</th>
                    <th class="text-left px-6 py-4">Víspera</th>
                    <th class="text-left px-6 py-4">Estado</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($tarifas as $tarifa)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 text-white font-medium">{{ $tarifa->categoria }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $tarifa->tipo_tiempo }}</td>
                    <td class="px-6 py-4 text-gray-300">${{ number_format($tarifa->precio_dj, 0, '', '.') }}</td>
                    <td class="px-6 py-4 text-gray-300">${{ number_format($tarifa->precio_viernes, 0, '', '.') }}</td>
                    <td class="px-6 py-4 text-gray-300">${{ number_format($tarifa->precio_sabado, 0, '', '.') }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $tarifa->precio_vispera ? '$'.number_format($tarifa->precio_vispera, 0, '', '.') : '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-3 py-1 rounded-full font-medium {{ $tarifa->activo ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $tarifa->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.tarifas.edit', $tarifa) }}" class="text-[#D4AF37] hover:text-white transition-colors text-sm font-medium">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
