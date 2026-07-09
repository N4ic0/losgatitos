@extends('layouts.admin')

@section('title', 'Paquetes de Productos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Paquetes de Productos</h1>
    <a href="{{ route('admin.promocion-productos.create') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nuevo Paquete</a>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-6 py-4">Nombre</th>
                    <th class="text-left px-6 py-4">Promoción</th>
                    <th class="text-left px-6 py-4">Producto</th>
                    <th class="text-left px-6 py-4">Cantidad</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($items as $item)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 text-white font-medium">{{ $item->nombre ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $item->promocion?->titulo ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $item->producto?->nombre ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $item->cantidad }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.promocion-productos.edit', $item) }}" class="text-[#D4AF37] hover:text-white transition-colors text-sm font-medium">Editar</a>
                        <form action="{{ route('admin.promocion-productos.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este paquete?')">
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
