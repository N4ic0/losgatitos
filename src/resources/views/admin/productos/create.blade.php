@extends('layouts.admin')

@section('title', 'Nuevo Producto')

@section('content')
<div x-data="categoriaManager()" class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Nuevo Producto</h1>
    <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                <div class="relative">
                    <select name="categoria" x-ref="categoriaSelect" @dblclick.prevent="abrirGestionCategorias()" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none appearance-none">
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->nombre }}" {{ old('categoria') === $cat->nombre ? 'selected' : '' }} class="bg-gray-900">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" @click="abrirGestionCategorias()" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#D4AF37] transition-colors p-1" title="Gestionar categorías (doble clic)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Precio</label>
                <input type="number" name="precio" value="{{ old('precio') }}" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Factor</label>
                <select name="factor" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    <option value="unidad" {{ old('factor', 'unidad') === 'unidad' ? 'selected' : '' }} class="bg-gray-900">Unidad</option>
                    <option value="cc" {{ old('factor') === 'cc' ? 'selected' : '' }} class="bg-gray-900">CC</option>
                    <option value="kgs" {{ old('factor') === 'kgs' ? 'selected' : '' }} class="bg-gray-900">KGS</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Stock Mínimo</label>
                <input type="text" name="stock_minimo" value="{{ old('stock_minimo', '0') }}" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Stock Máximo</label>
                <input type="text" name="stock_maximo" value="{{ old('stock_maximo', '0') }}" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Stock Actual</label>
                <input type="text" name="stock_actual" value="{{ old('stock_actual', '0') }}" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Imagen</label>
                <input type="file" name="imagen" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white file:bg-[#D4AF37] file:text-black file:font-semibold file:px-4 file:py-2 file:rounded-xl file:border-0 file:cursor-pointer">
            </div>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none">{{ old('descripcion') }}</textarea>
        </div>
        <div class="flex space-x-8">
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="activo" value="1" checked class="w-5 h-5 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                <span class="text-gray-300 text-sm">Activo</span>
            </label>
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="cortesia" value="1" class="w-5 h-5 rounded bg-white/5 border-white/10 text-purple-500 focus:ring-purple-500">
                <span class="text-gray-300 text-sm">Cortesía</span>
            </label>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.productos.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Crear Producto</button>
        </div>
    </form>

    {{-- Modal Gestionar Categorías --}}
    <div x-show="categoriasModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="cerrarGestionCategorias()">
        <div class="absolute inset-0 bg-black/60" @click="cerrarGestionCategorias()"></div>
        <div class="relative bg-[#1a1a1a] rounded-2xl p-8 w-full max-w-md border border-white/10 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6">Gestionar Categorías</h3>

            <div class="space-y-3 mb-6">
                <template x-for="(cat, index) in categorias" :key="cat.id">
                    <div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3">
                        <template x-if="editandoId !== cat.id">
                            <span class="text-white text-sm" x-text="cat.nombre"></span>
                        </template>
                        <template x-if="editandoId === cat.id">
                            <input type="text" x-model="editandoNombre" @keydown.enter="guardarCategoria(cat.id)" @keydown.escape="cancelarEdicion()" class="flex-1 bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm focus:border-[#D4AF37] outline-none mr-2">
                        </template>
                        <div class="flex space-x-2">
                            <template x-if="editandoId !== cat.id">
                                <button @click="editarCategoria(cat)" class="text-gray-400 hover:text-[#D4AF37] transition-colors text-xs">Editar</button>
                            </template>
                            <template x-if="editandoId === cat.id">
                                <button @click="guardarCategoria(cat.id)" class="text-green-400 hover:text-green-300 transition-colors text-xs">Guardar</button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div class="border-t border-white/10 pt-4">
                <label class="block text-gray-300 text-sm font-medium mb-2">Nueva Categoría</label>
                <div class="flex space-x-3">
                    <input type="text" x-model="nuevaCategoriaNombre" @keydown.enter="agregarCategoria()" placeholder="Nombre" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none text-sm">
                    <button @click="agregarCategoria()" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-4 py-3 rounded-xl transition-all text-sm">Agregar</button>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="button" @click="cerrarGestionCategorias()" class="px-6 py-3 text-gray-400 hover:text-white transition-colors text-sm">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function categoriaManager() {
    return {
        categoriasModalOpen: false,
        categorias: @json($categorias),
        nuevaCategoriaNombre: '',
        editandoId: null,
        editandoNombre: '',

        async abrirGestionCategorias() {
            await this.cargarCategorias();
            this.categoriasModalOpen = true;
        },

        cerrarGestionCategorias() {
            this.categoriasModalOpen = false;
            this.nuevaCategoriaNombre = '';
            this.editandoId = null;
            this.editandoNombre = '';
        },

        async cargarCategorias() {
            try {
                const res = await fetch('{{ route('admin.categorias.index') }}');
                this.categorias = await res.json();
            } catch(e) { console.error(e); }
        },

        async agregarCategoria() {
            if (!this.nuevaCategoriaNombre.trim()) return;
            try {
                const res = await fetch('{{ route('admin.categorias.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ nombre: this.nuevaCategoriaNombre.trim() }),
                });
                if (!res.ok) { const err = await res.json(); alert(err.message || 'Error'); return; }
                const cat = await res.json();
                this.categorias.push(cat);
                this.nuevaCategoriaNombre = '';
                // Select the new category
                this.$refs.categoriaSelect.value = cat.nombre;
            } catch(e) { console.error(e); }
        },

        editarCategoria(cat) {
            this.editandoId = cat.id;
            this.editandoNombre = cat.nombre;
        },

        cancelarEdicion() {
            this.editandoId = null;
            this.editandoNombre = '';
        },

        async guardarCategoria(id) {
            if (!this.editandoNombre.trim() || id === 0) return;
            try {
                const res = await fetch('/admin/categorias/' + id, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ nombre: this.editandoNombre.trim() }),
                });
                if (!res.ok) { const err = await res.json(); alert(err.message || 'Error'); return; }
                await this.cargarCategorias();
                this.editandoId = null;
                this.editandoNombre = '';
            } catch(e) { console.error(e); }
        },
    };
}
</script>
@endpush
