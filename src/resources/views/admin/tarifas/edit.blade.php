@extends('layouts.admin')

@section('title', 'Editar Tarifa')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Tarifa</h1>
    <form action="{{ route('admin.tarifas.update', $tarifa) }}" method="POST" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                <input type="hidden" name="categoria" value="{{ $tarifa->categoria }}">
                <p class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-400">{{ $tarifa->categoria }}</p>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Tipo de Tiempo</label>
                <input type="hidden" name="tipo_tiempo" value="{{ $tarifa->tipo_tiempo }}">
                <p class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-400">{{ $tarifa->tipo_tiempo }}</p>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Precio D-J</label>
                <input type="number" name="precio_dj" value="{{ old('precio_dj', $tarifa->precio_dj) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Precio Viernes</label>
                <input type="number" name="precio_viernes" value="{{ old('precio_viernes', $tarifa->precio_viernes) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Precio Sábado</label>
                <input type="number" name="precio_sabado" value="{{ old('precio_sabado', $tarifa->precio_sabado) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Precio Víspera</label>
                <input type="number" name="precio_vispera" value="{{ old('precio_vispera', $tarifa->precio_vispera) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', $tarifa->activo) ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                    <span class="text-gray-300 text-sm">Activo</span>
                </label>
            </div>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.tarifas.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Actualizar</button>
        </div>
    </form>
</div>
@endsection
