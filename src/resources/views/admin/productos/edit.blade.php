@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Producto</h1>
    <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data" class="bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/5 space-y-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                <div class="relative">
                    <select name="categoria" id="categoriaSelect" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none appearance-none">
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->nombre }}" {{ old('categoria', $producto->categoria) === $cat->nombre ? 'selected' : '' }} class="bg-gray-900">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="btnGestionCategorias" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#D4AF37] transition-colors p-1" title="Gestionar categorías">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Precio</label>
                <input type="number" name="precio" value="{{ old('precio', $producto->precio) }}" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Factor</label>
                <select name="factor" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    <option value="unidad" {{ old('factor', $producto->factor ?? 'unidad') === 'unidad' ? 'selected' : '' }} class="bg-gray-900">Unidad</option>
                    <option value="cc" {{ old('factor', $producto->factor) === 'cc' ? 'selected' : '' }} class="bg-gray-900">CC</option>
                    <option value="kgs" {{ old('factor', $producto->factor) === 'kgs' ? 'selected' : '' }} class="bg-gray-900">KGS</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Stock Mínimo</label>
                <input type="text" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Stock Máximo</label>
                <input type="text" name="stock_maximo" value="{{ old('stock_maximo', $producto->stock_maximo) }}" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Stock Actual</label>
                <input type="text" name="stock_actual" value="{{ old('stock_actual', $producto->stock_actual) }}" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Imagen</label>
                @if($producto->imagen)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-16 w-16 object-cover rounded-lg">
                </div>
                @endif
                <input type="file" name="imagen" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white file:bg-[#D4AF37] file:text-black file:font-semibold file:px-4 file:py-2 file:rounded-xl file:border-0 file:cursor-pointer">
            </div>
        </div>
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>
        <div class="flex space-x-8">
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="activo" value="1" {{ old('activo', $producto->activo) ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                <span class="text-gray-300 text-sm">Activo</span>
            </label>
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="cortesia" value="1" {{ old('cortesia', $producto->cortesia) ? 'checked' : '' }} class="w-5 h-5 rounded bg-white/5 border-white/10 text-purple-500 focus:ring-purple-500">
                <span class="text-gray-300 text-sm">Cortesía</span>
            </label>
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.productos.index') }}" class="px-6 py-3 text-gray-400 hover:text-white transition-colors">Cancelar</a>
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-xl transition-all">Actualizar Producto</button>
        </div>
    </form>

    {{-- Modal Gestionar Categorías --}}
    <div class="modal fade" id="categoriasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content bg-[#1a1a1a] rounded-2xl p-6 border border-white/10 shadow-2xl">
                <div class="modal-header border-0 pb-0">
                    <h3 class="text-xl font-bold text-white">Gestionar Categorías</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="categoriasLista" class="space-y-3 mb-6"></div>

                    <div class="border-t border-white/10 pt-4">
                        <label class="block text-gray-300 text-sm font-medium mb-2">Nueva Categoría</label>
                        <div class="flex space-x-3">
                            <input type="text" id="nuevaCategoriaInput" placeholder="Nombre" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none text-sm">
                            <button type="button" id="btnAgregarCategoria" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-4 py-3 rounded-xl transition-all text-sm">Agregar</button>
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
class CategoriaManager {
    constructor() {
        this.categorias = @json($categorias);
        this.editandoId = null;
        this.editandoNombre = '';

        this.selectEl = document.getElementById('categoriaSelect');
        this.listaEl = document.getElementById('categoriasLista');
        this.nuevaInput = document.getElementById('nuevaCategoriaInput');
        this.btnAgregar = document.getElementById('btnAgregarCategoria');
        this.btnGestion = document.getElementById('btnGestionCategorias');

        this.modalEl = document.getElementById('categoriasModal');
        this.bsModal = null;

        this.init();
    }

    getModal() {
        if (!this.bsModal) {
            this.bsModal = new bootstrap.Modal(this.modalEl, { keyboard: true });
        }
        return this.bsModal;
    }

    init() {
        this.btnGestion.addEventListener('click', () => this.abrirGestion());
        this.selectEl.addEventListener('dblclick', () => this.abrirGestion());
        this.btnAgregar.addEventListener('click', () => this.agregarCategoria());
        this.nuevaInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') this.agregarCategoria();
        });
        this.modalEl.addEventListener('hidden.bs.modal', () => {
            this.editandoId = null;
            this.editandoNombre = '';
            this.nuevaInput.value = '';
        });
    }

    async abrirGestion() {
        await this.cargarCategorias();
        this.renderLista();
        this.getModal().show();
    }

    async cargarCategorias() {
        try {
            const res = await fetch('{{ route('admin.categorias.index') }}');
            this.categorias = await res.json();
        } catch(e) { console.error(e); }
    }

    renderLista() {
        this.listaEl.innerHTML = '';
        this.categorias.forEach(cat => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between bg-white/5 rounded-xl px-4 py-3';

            if (this.editandoId === cat.id) {
                const input = document.createElement('input');
                input.type = 'text';
                input.value = this.editandoNombre;
                input.className = 'flex-1 bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm focus:border-[#D4AF37] outline-none mr-2';
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') this.guardarCategoria(cat.id);
                    if (e.key === 'Escape') this.cancelarEdicion();
                });
                input.addEventListener('input', (e) => { this.editandoNombre = e.target.value; });
                div.appendChild(input);

                const btnDiv = document.createElement('div');
                btnDiv.className = 'flex space-x-2';
                const btnGuardar = document.createElement('button');
                btnGuardar.type = 'button';
                btnGuardar.className = 'text-green-400 hover:text-green-300 transition-colors text-xs';
                btnGuardar.textContent = 'Guardar';
                btnGuardar.addEventListener('click', () => this.guardarCategoria(cat.id));
                btnDiv.appendChild(btnGuardar);
                div.appendChild(btnDiv);
            } else {
                const span = document.createElement('span');
                span.className = 'text-white text-sm';
                span.textContent = cat.nombre;
                div.appendChild(span);

                const btnDiv = document.createElement('div');
                btnDiv.className = 'flex space-x-2';
                const btnEditar = document.createElement('button');
                btnEditar.type = 'button';
                btnEditar.className = 'text-gray-400 hover:text-[#D4AF37] transition-colors text-xs';
                btnEditar.textContent = 'Editar';
                btnEditar.addEventListener('click', () => {
                    this.editandoId = cat.id;
                    this.editandoNombre = cat.nombre;
                    this.renderLista();
                });
                btnDiv.appendChild(btnEditar);
                div.appendChild(btnDiv);
            }

            this.listaEl.appendChild(div);
        });
    }

    cancelarEdicion() {
        this.editandoId = null;
        this.editandoNombre = '';
        this.renderLista();
    }

    async agregarCategoria() {
        const nombre = this.nuevaInput.value.trim();
        if (!nombre) return;
        try {
            const res = await fetch('{{ route('admin.categorias.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ nombre }),
            });
            if (!res.ok) { const err = await res.json(); alert(err.message || 'Error'); return; }
            const cat = await res.json();
            this.categorias.push(cat);
            this.nuevaInput.value = '';
            this.selectEl.value = cat.nombre;
            this.renderLista();
        } catch(e) { console.error(e); }
    }

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
            this.renderLista();
        } catch(e) { console.error(e); }
    }
}

document.addEventListener('DOMContentLoaded', () => { new CategoriaManager(); });
</script>
@endpush
