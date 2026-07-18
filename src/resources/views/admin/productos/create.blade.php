@extends('layouts.admin')

@section('title', 'Nuevo Producto')

@section('content')
<div class="max-w-2xl">
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
                    <select id="categoriaSelect" name="categoria" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none appearance-none">
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->nombre }}" {{ old('categoria') === $cat->nombre ? 'selected' : '' }} class="bg-gray-900">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="btnGestionCategorias" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#D4AF37] transition-colors p-1" title="Gestionar categorías (doble clic)">
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
    <div class="modal fade" id="categoriasModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-black border border-white/10 shadow-2xl">
                <div class="modal-header border-white/10">
                    <h5 class="modal-title text-white font-bold">Gestionar Categorías</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="categoriasList" class="space-y-3 mb-4"></div>
                    <div class="border-t border-white/10 pt-4">
                        <label class="block text-gray-400 text-sm font-medium mb-2">Nueva Categoría</label>
                        <div class="flex gap-3">
                            <input type="text" id="nuevaCategoriaNombre" placeholder="Nombre" class="form-control form-control-lg bg-white/5 border-white/10 text-white">
                            <button type="button" id="btnAgregarCategoria" class="btn btn-gold px-4 flex-shrink-0">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const CategoriaManager = {
    categorias: @json($categorias),
    categoriasModal: null,
    editandoId: null,
    editandoNombre: '',

    init: function() {
        this.categoriasModal = new bootstrap.Modal(document.getElementById('categoriasModal'));

        document.getElementById('btnGestionCategorias').addEventListener('click', () => this.abrirGestionCategorias());
        document.getElementById('categoriaSelect').addEventListener('dblclick', () => this.abrirGestionCategorias());
        document.getElementById('btnAgregarCategoria').addEventListener('click', () => this.agregarCategoria());
        document.getElementById('nuevaCategoriaNombre').addEventListener('keydown', (e) => {
            if (e.key === 'Enter') { e.preventDefault(); this.agregarCategoria(); }
        });
    },

    renderCategorias: function() {
        var list = document.getElementById('categoriasList');
        var html = '';
        var self = this;
        this.categorias.forEach(function(cat) {
            if (self.editandoId === cat.id) {
                html += '<div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3">';
                html += '<input type="text" id="editInput" value="' + self.editandoNombre + '" class="flex-1 bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm focus:border-[#D4AF37] outline-none mr-2">';
                html += '<button class="btn-guardar text-green-400 hover:text-green-300 transition-colors text-xs" data-id="' + cat.id + '">Guardar</button>';
                html += '</div>';
            } else {
                html += '<div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3">';
                html += '<span class="text-white text-sm">' + cat.nombre + '</span>';
                html += '<button class="btn-editar text-gray-400 hover:text-[#D4AF37] transition-colors text-xs" data-id="' + cat.id + '" data-nombre="' + cat.nombre + '">Editar</button>';
                html += '</div>';
            }
        });
        list.innerHTML = html;

        list.querySelectorAll('.btn-editar').forEach(function(btn) {
            btn.addEventListener('click', function() {
                self.editarCategoria(parseInt(this.dataset.id), this.dataset.nombre);
            });
        });

        list.querySelectorAll('.btn-guardar').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var input = document.getElementById('editInput');
                self.editandoNombre = input.value;
                self.guardarCategoria(parseInt(this.dataset.id));
            });
        });

        var editInput = document.getElementById('editInput');
        if (editInput) {
            editInput.focus();
            editInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    self.editandoNombre = this.value;
                    self.guardarCategoria(self.editandoId);
                }
                if (e.key === 'Escape') {
                    self.cancelarEdicion();
                }
            });
        }
    },

    async abrirGestionCategorias() {
        await this.cargarCategorias();
        this.categoriasModal.show();
    },

    cancelarEdicion() {
        this.editandoId = null;
        this.editandoNombre = '';
        this.renderCategorias();
    },

    async cargarCategorias() {
        try {
            var res = await fetch('{{ route('admin.categorias.index') }}');
            this.categorias = await res.json();
            this.renderCategorias();
        } catch(e) { console.error(e); }
    },

    async agregarCategoria() {
        var input = document.getElementById('nuevaCategoriaNombre');
        var nombre = input.value.trim();
        if (!nombre) return;
        try {
            var res = await fetch('{{ route('admin.categorias.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ nombre: nombre }),
            });
            if (!res.ok) { var err = await res.json(); alert(err.message || 'Error'); return; }
            var cat = await res.json();
            this.categorias.push(cat);
            input.value = '';
            document.getElementById('categoriaSelect').value = cat.nombre;
            this.renderCategorias();
        } catch(e) { console.error(e); }
    },

    editarCategoria: function(id, nombre) {
        this.editandoId = id;
        this.editandoNombre = nombre;
        this.renderCategorias();
    },

    async guardarCategoria(id) {
        if (!this.editandoNombre.trim() || id === 0) return;
        try {
            var res = await fetch('/admin/categorias/' + id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ nombre: this.editandoNombre.trim() }),
            });
            if (!res.ok) { var err = await res.json(); alert(err.message || 'Error'); return; }
            await this.cargarCategorias();
            this.editandoId = null;
            this.editandoNombre = '';
        } catch(e) { console.error(e); }
    },
};

document.addEventListener('DOMContentLoaded', function() { CategoriaManager.init(); });
</script>
@endpush
