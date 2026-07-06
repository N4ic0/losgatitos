@extends('layouts.admin')

@section('title', 'Nueva Promoción')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Nueva Promoción</h1>
    <form action="{{ route('admin.promociones.store') }}" method="POST" enctype="multipart/form-data" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Título</label>
            <input type="text" name="titulo" value="{{ old('titulo') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
            <textarea name="descripcion" rows="4" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none">{{ old('descripcion') }}</textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Orden</label>
                <input type="number" name="orden" value="{{ old('orden', 0) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Imagen</label>
                <input type="file" name="imagen" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white file:bg-[#D4AF37] file:text-black file:font-semibold file:px-4 file:py-2 file:rounded-xl file:border-0 file:cursor-pointer">
            </div>
        </div>
        <div>
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="activo" value="1" checked class="w-5 h-5 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                <span class="text-gray-300 text-sm">Activo</span>
            </label>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.promociones.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Crear Promoción</button>
        </div>
    </form>
</div>
@endsection
