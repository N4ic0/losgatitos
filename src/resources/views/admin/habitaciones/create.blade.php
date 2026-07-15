@extends('layouts.admin')

@section('title', 'Nueva Habitación')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Nueva Habitación</h1>
    <form action="{{ route('admin.habitaciones.store') }}" method="POST" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Número</label>
                <input type="text" name="numero" value="{{ old('numero') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none @error('numero') border-red-500 @enderror">
                @error('numero') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                <select name="categoria" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    <option value="Suite" class="bg-gray-900">Suite</option>
                    <option value="Departamento" class="bg-gray-900">Departamento</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Estado</label>
                <select name="estado" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    <option value="Disponible" class="bg-gray-900">Disponible</option>
                    <option value="Reservada" class="bg-gray-900">Reservada</option>
                    <option value="Ocupada" class="bg-gray-900">Ocupada</option>
                    <option value="Limpieza" class="bg-gray-900">Limpieza</option>
                    <option value="Mantenimiento" class="bg-gray-900">Mantenimiento</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none">{{ old('observaciones') }}</textarea>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.habitaciones.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Crear Habitación</button>
        </div>
    </form>
</div>
@endsection
