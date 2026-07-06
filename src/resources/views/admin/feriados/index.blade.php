@extends('layouts.admin')

@section('title', 'Feriados')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div>
        <h1 class="text-2xl font-bold text-white mb-6">Feriados</h1>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                        <th class="text-left px-6 py-4">Fecha</th>
                        <th class="text-left px-6 py-4">Descripción</th>
                        <th class="text-right px-6 py-4">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($feriados as $feriado)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 text-white">{{ $feriado->fecha->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $feriado->descripcion }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.feriados.destroy', $feriado) }}" method="POST" onsubmit="return confirm('¿Eliminar feriado?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-gray-500 text-center">No hay feriados registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white mb-6">Agregar Feriado</h1>
        <form action="{{ route('admin.feriados.store') }}" method="POST" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
            @csrf
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Fecha</label>
                <input type="date" name="fecha" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
                <input type="text" name="descripcion" required placeholder="Ej: Año Nuevo" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Agregar Feriado</button>
        </form>
    </div>
</div>
@endsection
