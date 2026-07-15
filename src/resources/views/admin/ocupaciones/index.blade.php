@extends('layouts.admin')

@section('title', 'Ocupaciones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Ocupaciones</h1>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-400 text-xs font-medium mb-1">Habitación</label>
            <select name="habitacion_id" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
                <option value="" class="bg-gray-900">Todas</option>
                @foreach($habitaciones as $h)
                <option value="{{ $h->id }}" {{ request('habitacion_id') == $h->id ? 'selected' : '' }} class="bg-gray-900">{{ $h->numero }} - {{ $h->categoria }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-400 text-xs font-medium mb-1">Estado</label>
            <select name="estado" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
                <option value="" class="bg-gray-900">Todas</option>
                <option value="activa" {{ request('estado') === 'activa' ? 'selected' : '' }} class="bg-gray-900">Activas</option>
                <option value="finalizada" {{ request('estado') === 'finalizada' ? 'selected' : '' }} class="bg-gray-900">Finalizadas</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-400 text-xs font-medium mb-1">Desde</label>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
        </div>
        <div>
            <label class="block text-gray-400 text-xs font-medium mb-1">Hasta</label>
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
        </div>
        <div class="flex items-end space-x-2 lg:col-span-4">
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Filtrar</button>
            <a href="{{ route('admin.ocupaciones.index') }}" class="px-5 py-2.5 text-gray-400 hover:text-white transition-colors text-sm">Limpiar</a>
        </div>
    </form>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-6 py-4">Habitación</th>
                    <th class="text-left px-6 py-4">Inicio</th>
                    <th class="text-left px-6 py-4">Fin</th>
                    <th class="text-left px-6 py-4">Tarifa</th>
                    <th class="text-left px-6 py-4">Clientes</th>
                    <th class="text-left px-6 py-4">Vehículo</th>
                    <th class="text-left px-6 py-4">Patente</th>
                    <th class="text-left px-6 py-4">Total</th>
                    <th class="text-left px-6 py-4">Estado</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($ocupaciones as $ocupacion)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 text-white font-medium">{{ $ocupacion->habitacion->numero ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $ocupacion->fecha_inicio->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $ocupacion->fecha_fin?->format('d/m/Y H:i') ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $ocupacion->tarifa?->tipo_tiempo ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $ocupacion->clientes->count() }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-3 py-1 rounded-full font-medium {{ $ocupacion->vehiculo ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $ocupacion->vehiculo ? 'Vehículo' : 'Peatón' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-300 font-mono uppercase">{{ $ocupacion->patente ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">${{ number_format($ocupacion->total, 0, '', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-3 py-1 rounded-full font-medium {{ $ocupacion->fecha_fin ? 'bg-gray-500/20 text-gray-400' : 'bg-green-500/20 text-green-400' }}">
                            {{ $ocupacion->fecha_fin ? 'Finalizada' : 'Activa' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.ocupaciones.show', $ocupacion) }}" class="text-[#D4AF37] hover:text-white transition-colors text-sm font-medium">Ver</a>
                        <form action="{{ route('admin.ocupaciones.destroy', $ocupacion) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta ocupación?')">
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

<div class="mt-6">
    {{ $ocupaciones->links() }}
</div>
@endsection
