@extends('layouts.admin')

@section('title', 'Nuevo Rol')

@php
$permissionGroups = [
    'Dashboard' => ['dashboard.view' => 'Ver Dashboard'],
    'Habitaciones' => [
        'habitaciones.view' => 'Ver habitaciones',
        'habitaciones.create' => 'Crear habitaciones',
        'habitaciones.edit' => 'Editar habitaciones',
        'habitaciones.delete' => 'Eliminar habitaciones',
    ],
    'Reservas' => [
        'reservas.view' => 'Ver reservas',
        'reservas.create' => 'Crear reservas',
        'reservas.edit' => 'Editar reservas',
        'reservas.delete' => 'Eliminar reservas',
    ],
    'Tarifas' => [
        'tarifas.view' => 'Ver tarifas',
        'tarifas.edit' => 'Editar tarifas',
    ],
    'Promociones' => [
        'promociones.view' => 'Ver promociones',
        'promociones.create' => 'Crear promociones',
        'promociones.edit' => 'Editar promociones',
        'promociones.delete' => 'Eliminar promociones',
    ],
    'Productos' => [
        'productos.view' => 'Ver productos',
        'productos.create' => 'Crear productos',
        'productos.edit' => 'Editar productos',
        'productos.delete' => 'Eliminar productos',
    ],
    'Paquetes' => [
        'paquetes.view' => 'Ver paquetes',
        'paquetes.create' => 'Crear paquetes',
        'paquetes.edit' => 'Editar paquetes',
        'paquetes.delete' => 'Eliminar paquetes',
    ],
    'Ocupaciones' => [
        'ocupaciones.view' => 'Ver ocupaciones',
        'ocupaciones.delete' => 'Eliminar ocupaciones',
    ],
    'Feriados' => [
        'feriados.view' => 'Ver feriados',
        'feriados.create' => 'Crear feriados',
        'feriados.delete' => 'Eliminar feriados',
    ],
    'Usuarios' => [
        'usuarios.view' => 'Ver usuarios',
        'usuarios.create' => 'Crear usuarios',
        'usuarios.edit' => 'Editar usuarios',
        'usuarios.delete' => 'Eliminar usuarios',
    ],
    'Roles' => [
        'roles.view' => 'Ver roles',
        'roles.create' => 'Crear roles',
        'roles.edit' => 'Editar roles',
        'roles.delete' => 'Eliminar roles',
    ],
];
@endphp

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-white mb-6">Nuevo Rol</h1>
    <form action="{{ route('admin.roles.store') }}" method="POST" class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-8 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Nombre del Rol</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Identificador (slug)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                @error('slug') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
            <textarea name="description" rows="2" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none">{{ old('description') }}</textarea>
        </div>

        <div>
            <div class="flex items-center justify-between mb-4">
                <label class="block text-gray-300 text-sm font-medium">Permisos</label>
                <button type="button" onclick="toggleAll(true)" class="text-xs text-[#D4AF37] hover:text-white mr-3">Seleccionar todo</button>
                <button type="button" onclick="toggleAll(false)" class="text-xs text-gray-400 hover:text-white">Deseleccionar todo</button>
            </div>
            <div class="space-y-4">
                @foreach($permissionGroups as $group => $perms)
                <div class="bg-white/5 rounded-xl p-4">
                    <label class="flex items-center space-x-3 mb-3">
                        <input type="checkbox" onchange="toggleGroup('{{ Str::slug($group) }}', this.checked)" class="w-4 h-4 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                        <span class="text-white font-medium text-sm">{{ $group }}</span>
                    </label>
                    <div id="group-{{ Str::slug($group) }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 ml-6">
                        @foreach($perms as $key => $label)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="permissions[]" value="{{ $key }}" class="perm-checkbox group-{{ Str::slug($group) }} w-4 h-4 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                            <span class="text-gray-300 text-sm">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @error('permissions') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.roles.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Crear Rol</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleAll(checked) {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = checked);
}
function toggleGroup(group, checked) {
    document.querySelectorAll('.group-' + group).forEach(cb => cb.checked = checked);
}
</script>
@endpush
