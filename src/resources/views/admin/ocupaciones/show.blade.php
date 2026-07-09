@extends('layouts.admin')

@section('title', 'Ocupación #' . $ocupacion->id)

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Ocupación #{{ $ocupacion->id }}</h1>
    <a href="{{ route('admin.ocupaciones.index') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Volver</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Info general --}}
    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Información General</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-400">Habitación</dt>
                <dd class="text-white font-medium">{{ $ocupacion->habitacion->numero ?? '-' }} ({{ $ocupacion->habitacion->categoria ?? '-' }})</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Tarifa</dt>
                <dd class="text-white font-medium">{{ $ocupacion->tarifa?->tipo_tiempo ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Promoción</dt>
                <dd class="text-white font-medium">{{ $ocupacion->promocion?->titulo ?? 'Sin promoción' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Inicio</dt>
                <dd class="text-white font-medium">{{ $ocupacion->fecha_inicio->format('d/m/Y H:i') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Fin</dt>
                <dd class="text-white font-medium">{{ $ocupacion->fecha_fin?->format('d/m/Y H:i') ?? 'En curso' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Horas beneficio</dt>
                <dd class="text-white font-medium">{{ $ocupacion->horas_beneficio }}h</dd>
            </div>
            <div class="flex justify-between pt-3 border-t border-white/5">
                <dt class="text-gray-400">Precio Base</dt>
                <dd class="text-white font-medium">${{ number_format($ocupacion->precio_base, 0, '', '.') }}</dd>
            </div>
        </dl>
    </div>

    {{-- Clientes --}}
    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Clientes ({{ $ocupacion->clientes->count() }})</h2>
        @if($ocupacion->clientes->count() > 0)
        <div class="space-y-3">
            @foreach($ocupacion->clientes as $cliente)
            <div class="bg-white/5 rounded-xl p-4">
                <p class="text-white font-medium">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                <p class="text-gray-400 text-xs">{{ $cliente->rut ?? 'Sin RUT' }}</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-sm">Sin clientes registrados</p>
        @endif
    </div>

    {{-- Resumen financiero --}}
    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Resumen Financiero</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-400">Precio Base</dt>
                <dd class="text-white font-medium">${{ number_format($ocupacion->precio_base, 0, '', '.') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Consumos</dt>
                <dd class="text-white font-medium">${{ number_format($ocupacion->total_consumos, 0, '', '.') }}</dd>
            </div>
            <div class="flex justify-between pt-3 border-t border-white/5">
                <dt class="text-gray-300 font-semibold">Total</dt>
                <dd class="text-white font-bold text-lg">${{ number_format($ocupacion->total, 0, '', '.') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Pagado</dt>
                <dd class="text-green-400 font-medium">${{ number_format($ocupacion->total_pagado, 0, '', '.') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Saldo</dt>
                <dd class="text-{{ $ocupacion->saldo > 0 ? 'red-400' : 'green-400' }} font-medium">${{ number_format($ocupacion->saldo, 0, '', '.') }}</dd>
            </div>
        </dl>
    </div>
</div>

{{-- Consumos --}}
@if($ocupacion->consumos->count() > 0)
<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-6 mt-6">
    <h2 class="text-lg font-semibold text-white mb-4">Consumos ({{ $ocupacion->consumos->count() }})</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-4 py-3">Producto</th>
                    <th class="text-left px-4 py-3">Cantidad</th>
                    <th class="text-left px-4 py-3">Precio</th>
                    <th class="text-left px-4 py-3">Total</th>
                    <th class="text-left px-4 py-3">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($ocupacion->consumos as $consumo)
                <tr>
                    <td class="px-4 py-3 text-white">{{ $consumo->producto?->nombre ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $consumo->cantidad }}</td>
                    <td class="px-4 py-3 text-gray-300">${{ number_format($consumo->precio_unitario, 0, '', '.') }}</td>
                    <td class="px-4 py-3 text-gray-300">${{ number_format($consumo->total, 0, '', '.') }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $consumo->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Pagos --}}
@if($ocupacion->pagos->count() > 0)
<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-6 mt-6">
    <h2 class="text-lg font-semibold text-white mb-4">Pagos ({{ $ocupacion->pagos->count() }})</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-4 py-3">Monto</th>
                    <th class="text-left px-4 py-3">Método</th>
                    <th class="text-left px-4 py-3">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($ocupacion->pagos as $pago)
                <tr>
                    <td class="px-4 py-3 text-white font-medium">${{ number_format($pago->monto, 0, '', '.') }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $pago->metodo_pago ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-300">{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Observaciones --}}
@if($ocupacion->observaciones->count() > 0)
<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-6 mt-6">
    <h2 class="text-lg font-semibold text-white mb-4">Observaciones</h2>
    <div class="space-y-3">
        @foreach($ocupacion->observaciones as $obs)
        <div class="bg-white/5 rounded-xl p-4">
            <p class="text-gray-300 text-sm">{{ $obs->contenido }}</p>
            <p class="text-gray-500 text-xs mt-1">{{ $obs->created_at->format('d/m/Y H:i') }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
