@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Usuarios</h1>
    <a href="{{ route('admin.usuarios.create') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nuevo Usuario</a>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-gray-400 uppercase text-xs tracking-wider">
                    <th class="text-left px-6 py-4">Nombre</th>
                    <th class="text-left px-6 py-4">Email</th>
                    <th class="text-left px-6 py-4">RUT</th>
                    <th class="text-left px-6 py-4">Teléfono</th>
                    <th class="text-left px-6 py-4">Rol</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($usuarios as $usuario)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 text-white font-medium">{{ $usuario->name }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $usuario->email }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $usuario->rut ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $usuario->telefono ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-3 py-1 rounded-full font-medium {{ $usuario->userRole?->slug === 'administrador' ? 'bg-purple-500/20 text-purple-400' : 'bg-blue-500/20 text-blue-400' }}">
                            {{ $usuario->userRole?->name ?? $usuario->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-[#D4AF37] hover:text-white transition-colors text-sm font-medium">Editar</a>
                        @if($usuario->id !== auth()->id())
                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este usuario?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-sm font-medium">Eliminar</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
