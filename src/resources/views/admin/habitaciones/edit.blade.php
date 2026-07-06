@extends('layouts.admin')

@section('title', 'Editar Habitación')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Habitación {{ $habitacion->numero }}</h1>
    <form action="{{ route('admin.habitaciones.update', $habitacion) }}" method="POST" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Número</label>
                <input type="text" name="numero" value="{{ old('numero', $habitacion->numero) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                <select name="categoria" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    <option value="Suite" {{ $habitacion->categoria === 'Suite' ? 'selected' : '' }} class="bg-gray-900">Suite</option>
                    <option value="Departamento" {{ $habitacion->categoria === 'Departamento' ? 'selected' : '' }} class="bg-gray-900">Departamento</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Estado</label>
                <select name="estado" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    @foreach(['Disponible','Reservada','Ocupada','Limpieza','Mantenimiento'] as $estado)
                    <option value="{{ $estado }}" {{ $habitacion->estado === $estado ? 'selected' : '' }} class="bg-gray-900">{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none">{{ old('observaciones', $habitacion->observaciones) }}</textarea>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.habitaciones.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Actualizar</button>
        </div>
    </form>
</div>
@endsection
