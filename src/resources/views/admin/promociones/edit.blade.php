@extends('layouts.admin')

@section('title', 'Editar Promoción')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Promoción</h1>
    <form action="{{ route('admin.promociones.update', $promocion) }}" method="POST" enctype="multipart/form-data" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf @method('PUT')
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Título</label>
            <input type="text" name="titulo" value="{{ old('titulo', $promocion->titulo) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
            <textarea name="descripcion" rows="4" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none">{{ old('descripcion', $promocion->descripcion) }}</textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $promocion->fecha_inicio->format('Y-m-d')) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $promocion->fecha_fin->format('Y-m-d')) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Orden</label>
                <input type="number" name="orden" value="{{ old('orden', $promocion->orden) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Imagen</label>
                <input type="file" name="imagen" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white file:bg-[#D4AF37] file:text-black file:font-semibold file:px-4 file:py-2 file:rounded-xl file:border-0 file:cursor-pointer">
            </div>
        </div>
        <div>
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="activo" value="1" {{ old('activo', $promocion->activo) ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                <span class="text-gray-300 text-sm">Activo</span>
            </label>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-3">Productos incluidos</label>
            <div class="space-y-2 max-h-48 overflow-y-auto">
                @foreach($productos as $producto)
                @php
                    $pivot = $promocion->productos->find($producto->id);
                @endphp
                <label class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="productos[]" value="{{ $producto->id }}" {{ $pivot ? 'checked' : '' }} class="w-4 h-4 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                        <span class="text-gray-300 text-sm">{{ $producto->nombre }}</span>
                        <span class="text-gray-500 text-xs">(${{ number_format($producto->precio, 0, '', '.') }})</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-500 text-xs">Cant:</span>
                        <input type="number" name="cantidades[{{ $producto->id }}]" value="{{ $pivot->pivot->cantidad ?? 1 }}" min="1" class="w-16 bg-white/5 border border-white/10 rounded-lg px-2 py-1 text-white text-sm focus:border-[#D4AF37] outline-none">
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.promociones.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Actualizar</button>
        </div>
    </form>
</div>
@endsection
