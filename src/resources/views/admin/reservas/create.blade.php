@extends('layouts.admin')

@section('title', 'Nueva Reserva')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Nueva Reserva</h1>
    <form action="{{ route('admin.reservas.store') }}" method="POST" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">RUT</label>
                <input type="text" name="rut" value="{{ old('rut') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Fecha</label>
                <input type="date" name="fecha" value="{{ old('fecha') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Hora</label>
                <input type="time" name="hora" value="{{ old('hora') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Personas</label>
                <select name="personas" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('personas', 2) == $i ? 'selected' : '' }} class="bg-gray-900">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Habitación</label>
                <select name="habitacion_id" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    <option value="" class="bg-gray-900">Seleccionar...</option>
                    @foreach($habitaciones as $h)
                    <option value="{{ $h->id }}" {{ old('habitacion_id') == $h->id ? 'selected' : '' }} class="bg-gray-900">{{ $h->numero }} - {{ $h->categoria }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none">{{ old('observaciones') }}</textarea>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.reservas.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Crear Reserva</button>
        </div>
    </form>
</div>
@endsection
