@extends('layouts.admin')

@section('title', 'Roles y Permisos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Roles y Permisos</h1>
    <a href="{{ route('admin.roles.create') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nuevo Rol</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($roles as $rol)
    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-white">{{ $rol->name }}</h3>
                <p class="text-gray-400 text-xs mt-1">{{ $rol->slug }}</p>
            </div>
            @if(!$rol->editable)
            <span class="text-xs px-2 py-1 rounded-full bg-gray-500/20 text-gray-400">Sistema</span>
            @endif
        </div>
        @if($rol->description)
        <p class="text-gray-400 text-sm mb-4">{{ $rol->description }}</p>
        @endif
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-500">{{ $rol->users_count }} usuario(s)</span>
            <span class="text-gray-500">{{ count($rol->permissions ?? []) }} permiso(s)</span>
        </div>
        <div class="flex justify-end space-x-3 mt-4 pt-4 border-t border-white/5">
            <a href="{{ route('admin.roles.edit', $rol) }}" class="text-[#D4AF37] hover:text-white transition-colors text-sm font-medium">Editar Permisos</a>
            @if($rol->editable)
            <form action="{{ route('admin.roles.destroy', $rol) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este rol?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-sm font-medium">Eliminar</button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
