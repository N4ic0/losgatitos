@extends('layouts.admin')

@section('title', 'Nuevo Paquete')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Nuevo Paquete</h1>
    <form action="{{ route('admin.promocion-productos.store') }}" method="POST" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Promoción</label>
                <select name="promocion_id" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    <option value="" class="bg-gray-900">Seleccionar...</option>
                    @foreach($promociones as $p)
                    <option value="{{ $p->id }}" {{ old('promocion_id') == $p->id ? 'selected' : '' }} class="bg-gray-900">{{ $p->titulo }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Producto</label>
                <select name="producto_id" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    <option value="" class="bg-gray-900">Seleccionar...</option>
                    @foreach($productos as $prod)
                    <option value="{{ $prod->id }}" {{ old('producto_id') == $prod->id ? 'selected' : '' }} class="bg-gray-900">{{ $prod->nombre }} ({{ $prod->categoria }}) - ${{ number_format($prod->precio, 0, '', '.') }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Cantidad</label>
                <input type="number" name="cantidad" value="{{ old('cantidad', 1) }}" required min="1" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.promocion-productos.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Crear Paquete</button>
        </div>
    </form>
</div>
@endsection
