@extends('layouts.admin')

@section('title', 'Productos')

@push('styles')
<style>
.btn-xs { padding: 0.15rem 0.4rem; font-size: 0.7rem; line-height: 1.2; border-radius: 0.25rem; }
.btn-outline-warning { color: #D4AF37; border-color: #D4AF37; }
.btn-outline-warning:hover { color: #000; background-color: #D4AF37; border-color: #D4AF37; }
.btn-outline-danger { color: #ef4444; border-color: #ef4444; }
.btn-outline-danger:hover { color: #fff; background-color: #ef4444; border-color: #ef4444; }
table.dataTable { color: #fff; }
table.dataTable td, table.dataTable th { border-color: rgba(255,255,255,0.1); }
table.dataTable thead th { background: rgba(255,255,255,0.05); color: #D4AF37; }
table.dataTable tbody tr:hover { background: rgba(212,175,55,0.05); }
table.dataTable tbody td { padding: 0.75rem 0.5rem; }
div.dt-container div.dt-search input {
    padding: 0.5rem 1rem;
    outline: none;
}
div.dt-container div.dt-search label,
div.dt-container div.dt-length label { color: #9ca3af; }
div.dt-container div.dt-info { color: #9ca3af; }
.badge-categoria { display: inline-block; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; }
.badge-stock { display: inline-block; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; }
.badge-estado { cursor: pointer; display: inline-block; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; }
[data-bs-theme="dark"] .form-control { background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff; }
[data-bs-theme="dark"] .form-control:focus { border-color: #D4AF37; box-shadow: 0 0 0 2px rgba(212,175,55,0.2); }
[data-bs-theme="dark"] .form-check-input { background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); }
[data-bs-theme="dark"] .form-check-input:checked { background-color: #D4AF37; border-color: #D4AF37; }
</style>
@endpush

@section('content')
<div class="space-y-6">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Productos</h1>
    <div class="flex gap-2">
    <a href="{{ route('admin.productos.catalogo') }}" target="_blank" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Catálogo PDF</a>
    <button type="button" class="accion-nuevo-producto bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nuevo Producto</button>
    </div>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-4">
    <table id="productos-table" class="display responsive nowrap w-full" style="width:100%">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Factor</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Cortesía</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

{{-- Modal Ingreso --}}
<div class="modal fade" id="ingresoModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-black border border-white/10 shadow-2xl">
            <div class="modal-header border-white/10">
                <h5 class="modal-title text-white font-bold">Ingreso de Stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p id="ingresoProductoNombre" class="text-gray-400 text-xs mb-3"></p>
                <form id="ingresoForm" action="{{ route('admin.ingresos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="producto_id" id="ingresoProductoId">

                    <div class="mb-3">
                        <label class="form-label text-gray-400">Tipo Documento</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="tipo_documento" value="Boleta" checked id="docBoleta">
                                <label class="form-check-label text-gray-300" for="docBoleta">Boleta</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="tipo_documento" value="Factura" id="docFactura">
                                <label class="form-check-label text-gray-300" for="docFactura">Factura</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-gray-400">N° Documento</label>
                        <input type="text" id="numero_documento" name="numero_documento" maxlength="50" class="form-control bg-white/5 border-white/10 text-white">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-gray-400">RUT Proveedor</label>
                        <input type="text" name="rut_proveedor" required maxlength="20" class="form-control bg-white/5 border-white/10 text-white">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-gray-400">Nombre Proveedor</label>
                        <input type="text" name="nombre_proveedor" required maxlength="255" class="form-control bg-white/5 border-white/10 text-white">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-gray-400">Fecha</label>
                        <input type="date" name="fecha" required value="{{ date('Y-m-d') }}" class="form-control bg-white/5 border-white/10 text-white">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-gray-400">Costo Neto</label>
                        <input type="number" name="costo_neto" required min="0" class="form-control bg-white/5 border-white/10 text-white">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-gray-400">Cantidad</label>
                        <input type="number" name="cantidad" required min="1" class="form-control bg-white/5 border-white/10 text-white">
                    </div>

                    <div class="d-flex justify-end gap-2 pt-2">
                        <button type="button" class="btn btn-sm text-gray-400" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-gold">Registrar Ingreso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Producto (Crear/Editar) --}}
<div class="modal fade" id="productoModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-black border border-white/10 shadow-2xl">
            <div class="modal-header border-white/10">
                <h5 class="modal-title text-white font-bold" id="productoModalTitle">Nuevo Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="productoForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="productoMethod" value="">
                    <input type="hidden" name="producto_id" id="productoId" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Nombre</label>
                            <input type="text" name="nombre" id="prodNombre" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                            <div class="relative">
                                <select name="categoria" id="categoriaSelect" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none appearance-none">
                                    @foreach($categorias as $cat)
                                    <option value="{{ $cat->nombre }}" class="bg-gray-900">{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="btnGestionCategorias" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#D4AF37] transition-colors p-1" title="Gestionar categorías">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Precio</label>
                            <input type="number" name="precio" id="prodPrecio" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Factor</label>
                            <select name="factor" id="prodFactor" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                <option value="unidad" class="bg-gray-900">Unidad</option>
                                <option value="cc" class="bg-gray-900">CC</option>
                                <option value="kgs" class="bg-gray-900">KGS</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Stock Mínimo</label>
                            <input type="number" name="stock_minimo" id="prodStockMin" value="0" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Stock Máximo</label>
                            <input type="number" name="stock_maximo" id="prodStockMax" value="0" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Stock Actual</label>
                            <input type="number" name="stock_actual" id="prodStockActual" value="0" min="0" step="0.001" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Imagen</label>
                            <div id="prodImagenPreview" class="mb-2 hidden">
                                <img id="prodImagenImg" class="h-16 w-16 object-cover rounded-lg">
                            </div>
                            <input type="file" name="imagen" id="prodImagen" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white file:bg-[#D4AF37] file:text-black file:font-semibold file:px-4 file:py-2 file:rounded-xl file:border-0 file:cursor-pointer">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
                        <textarea name="descripcion" id="prodDescripcion" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none"></textarea>
                    </div>
                    <div class="flex space-x-8 mt-4">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="activo" id="prodActivo" value="1" checked class="w-5 h-5 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                            <span class="text-gray-300 text-sm">Activo</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="cortesia" id="prodCortesia" value="1" class="w-5 h-5 rounded bg-white/5 border-white/10 text-purple-500 focus:ring-purple-500">
                            <span class="text-gray-300 text-sm">Cortesía</span>
                        </label>
                    </div>
                    <div class="d-flex justify-end gap-3 mt-6">
                        <button type="button" class="btn btn-sm text-gray-400" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-gold" id="productoFormSubmit">Crear Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                        <input type="text" id="nuevaCategoriaNombre" placeholder="Nombre" class="form-control bg-white/5 border-white/10 text-white">
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
const ProductosManager = {
    table: null,
    editandoId: null,

    init: function() {
        this.initDataTable();
        this.bindEvents();
    },

    initDataTable: function() {
        var self = this;
        this.table = $('#productos-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: { url: '/admin/productos-json', dataSrc: function(json) { return json; } },
            responsive: true,
            autoWidth: false,
            order: [],
            columns: [
                { data: 'nombre' },
                { data: 'categoria',
                    render: function(data) {
                        var cls = data === 'Colacion' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30';
                        return '<span class="badge-categoria ' + cls + '">' + data + '</span>';
                    }
                },
                { data: 'precio' },
                { data: 'factor', className: 'text-center' },
                { data: null,
                    render: function(row) {
                        var v = row.stock_actual;
                        var cls = 'bg-green-500/20 text-green-400';
                        if (row.sin_stock) cls = 'bg-red-500/20 text-red-400';
                        else if (row.bajo_stock) cls = 'bg-yellow-500/20 text-yellow-400';
                        var max = row.stock_maximo > 0 ? ' / ' + row.stock_minimo.toFixed(3) + ' - ' + row.stock_maximo.toFixed(3) : '';
                        return '<span class="badge-stock ' + cls + '">' + v.toFixed(3) + max + '</span>';
                    }
                },
                { data: 'activo',
                    render: function(data, type, row) {
                        var cls = data ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30';
                        return '<span class="badge-estado ' + cls + '" data-id="' + row.id + '" data-field="activo">' + (data ? 'Activo' : 'Inactivo') + '</span>';
                    }
                },
                { data: 'cortesia',
                    render: function(data, type, row) {
                        var cls = data ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-gray-500/20 text-gray-300 border border-gray-500/30';
                        return '<span class="badge-estado ' + cls + '" data-id="' + row.id + '" data-field="cortesia">' + (data ? 'Sí' : 'No') + '</span>';
                    }
                },
                { data: null,
                    render: function(row) {
                        return '<button class="accion-editar btn btn-xs btn-outline-warning me-1" data-id="' + row.id + '"><i class="fas fa-pencil-alt"></i></button>' +
                               '<button class="accion-eliminar btn btn-xs btn-outline-danger" data-id="' + row.id + '"><i class="fas fa-trash-alt"></i></button>';
                    },
                    orderable: false
                }
            ],
            language: {
                decimal: ',',
                thousands: '.',
                lengthMenu: 'Mostrar _MENU_ registros',
                zeroRecords: 'No se encontraron resultados',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                infoThousands: '.',
                loadingRecords: 'Cargando...',
                processing: 'Procesando...',
                search: 'Buscar:',
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                },
            }
        });
    },

    bindEvents: function() {
        var self = this;

        $(document).on('click', '.badge-estado', function() {
            self.toggleField(parseInt($(this).data('id')), $(this).data('field'), $(this));
        });

        $(document).on('click', '.accion-nuevo-producto', function() {
            self.abrirProductoModal('create');
        });

        $(document).on('click', '.accion-editar', function() {
            self.abrirProductoModal('edit', parseInt($(this).data('id')));
        });

        $(document).on('click', '.accion-eliminar', function() {
            var id = parseInt($(this).data('id'));
            Swal.fire({
                title: '¿Eliminar producto?',
                text: 'Esta acción no se puede deshacer. También puedes desactivar el producto desde la columna Estado.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#1a1a2e',
                color: '#e5e7eb',
            }).then(function(result) {
                if (!result.isConfirmed) return;
                fetch('/admin/productos/' + id, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new URLSearchParams({ _method: 'DELETE' }),
                }).then(function() {
                    self.table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Producto eliminado', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
                }).catch(function() { location.reload(); });
            });
        });

        $('#productoForm').on('submit', function(e) {
            e.preventDefault();
            self.guardarProducto();
        });
    },

    toggleField: async function(id, field, $el) {
        try {
            var csrfToken = '{{ csrf_token() }}';
            var res = await fetch('/admin/productos/' + id + '/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ field: field }),
            });
            if (!res.ok) {
                var msg = 'Error del servidor (código ' + res.status + ')';
                try { var err = await res.json(); if (err.message) msg = err.message; } catch(e) {}
                Swal.fire('Error ' + res.status, msg, 'error');
                return;
            }
            var json = await res.json();
            if (json.success) {
                var text, cls;
                if (field === 'activo') {
                    text = json.value ? 'Activo' : 'Inactivo';
                    cls = json.value ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30';
                } else {
                    text = json.value ? 'Sí' : 'No';
                    cls = json.value ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-gray-500/20 text-gray-300 border border-gray-500/30';
                }
                $el.text(text).attr('class', 'badge-estado ' + cls);
            }
        } catch(e) {
            console.error(e);
            Swal.fire('Error', 'Error de conexión al servidor', 'error');
        }
    },

    abrirProductoModal: function(modo, id) {
        var modal = document.getElementById('productoModal');
        var title = document.getElementById('productoModalTitle');
        var submitBtn = document.getElementById('productoFormSubmit');
        var form = document.getElementById('productoForm');
        var methodInput = document.getElementById('productoMethod');
        var idInput = document.getElementById('productoId');

        form.reset();
        document.getElementById('prodActivo').checked = true;
        document.getElementById('prodCortesia').checked = false;
        document.getElementById('prodImagenPreview').classList.add('hidden');
        document.getElementById('prodImagenImg').src = '';

        if (modo === 'create') {
            title.textContent = 'Nuevo Producto';
            submitBtn.textContent = 'Crear Producto';
            methodInput.value = '';
            idInput.value = '';
            this.editandoId = null;
            this.mostrarModal(modal);
        } else if (modo === 'edit' && id) {
            title.textContent = 'Editar Producto';
            submitBtn.textContent = 'Guardar Cambios';
            methodInput.value = 'PUT';
            idInput.value = id;
            this.editandoId = id;
            this.cargarProducto(id, modal);
        }
    },

    cargarProducto: function(id, modal) {
        var self = this;
        var token = '{{ csrf_token() }}';
        fetch('/admin/productos/' + id + '/data', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
        }).then(function(res) {
            if (!res.ok) throw new Error('Error al cargar producto');
            return res.json();
        }).then(function(p) {
            document.getElementById('prodNombre').value = p.nombre || '';
            document.getElementById('categoriaSelect').value = p.categoria || '';
            document.getElementById('prodPrecio').value = p.precio || '';
            document.getElementById('prodFactor').value = p.factor || 'unidad';
            document.getElementById('prodStockMin').value = p.stock_minimo ?? 0;
            document.getElementById('prodStockMax').value = p.stock_maximo ?? 0;
            document.getElementById('prodStockActual').value = p.stock_actual ?? 0;
            document.getElementById('prodDescripcion').value = p.descripcion || '';
            document.getElementById('prodActivo').checked = !!p.activo;
            document.getElementById('prodCortesia').checked = !!p.cortesia;
            if (p.imagen) {
                document.getElementById('prodImagenImg').src = '/storage/' + p.imagen;
                document.getElementById('prodImagenPreview').classList.remove('hidden');
            }
            self.mostrarModal(modal);
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        });
    },

    guardarProducto: function() {
        var self = this;
        var form = document.getElementById('productoForm');
        var formData = new FormData(form);
        var token = '{{ csrf_token() }}';
        formData.set('_token', token);
        var id = parseInt(document.getElementById('productoId').value);

        var url, method;
        if (id) {
            url = '/admin/productos/' + id;
            formData.set('_method', 'PUT');
        } else {
            url = '/admin/productos';
            formData.delete('_method');
        }

        fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: formData,
        }).then(function(res) {
            if (!res.ok) {
                return res.json().then(function(err) {
                    var msg = err.message || err.errors ? Object.values(err.errors).flat().join(', ') : 'Error del servidor';
                    throw new Error(msg);
                }).catch(function(e) {
                    if (e instanceof SyntaxError) throw new Error('Error del servidor (código ' + res.status + ')');
                    throw e;
                });
            }
            return res.json();
        }).then(function(json) {
            var me = document.getElementById('productoModal');
            bootstrap.Modal.getInstance(me)?.hide();
            form.reset();
            Swal.fire({ icon: 'success', title: id ? 'Producto actualizado' : 'Producto creado', text: json.message || 'Operación exitosa', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
            self.table.ajax.reload();
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
        });
    },

    mostrarModal: function(el) {
        bootstrap.Modal.getOrCreateInstance(el).show();
    },
};

{{-- CategoriaManager inline --}}
const CategoriaManager = {
    categorias: @json($categorias),
    editandoId: null,
    editandoNombre: '',

    init: function() {
        document.getElementById('btnGestionCategorias').addEventListener('click', function() { CategoriaManager.abrirGestion(); });
        document.getElementById('categoriaSelect').addEventListener('dblclick', function() { CategoriaManager.abrirGestion(); });
        document.getElementById('btnAgregarCategoria').addEventListener('click', function() { CategoriaManager.agregarCategoria(); });
        document.getElementById('nuevaCategoriaNombre').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); CategoriaManager.agregarCategoria(); }
        });
        document.getElementById('categoriasModal').addEventListener('hidden.bs.modal', function() {
            CategoriaManager.editandoId = null;
            CategoriaManager.editandoNombre = '';
            document.getElementById('nuevaCategoriaNombre').value = '';
        });
    },

    abrirGestion: async function() {
        await CategoriaManager.cargarCategorias();
        CategoriaManager.renderCategorias();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('categoriasModal')).show();
    },

    renderCategorias: function() {
        var list = document.getElementById('categoriasList');
        var html = '';
        var self = CategoriaManager;
        self.categorias.forEach(function(cat) {
            if (self.editandoId === cat.id) {
                html += '<div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3">';
                html += '<input type="text" id="editCatInput" value="' + self.editandoNombre + '" class="flex-1 bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm focus:border-[#D4AF37] outline-none mr-2">';
                html += '<button class="btn-guardar-cat text-green-400 hover:text-green-300 transition-colors text-xs" data-id="' + cat.id + '">Guardar</button>';
                html += '</div>';
            } else {
                html += '<div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3">';
                html += '<span class="text-white text-sm">' + cat.nombre + '</span>';
                html += '<button class="btn-editar-cat text-gray-400 hover:text-[#D4AF37] transition-colors text-xs" data-id="' + cat.id + '" data-nombre="' + cat.nombre + '">Editar</button>';
                html += '</div>';
            }
        });
        list.innerHTML = html;

        list.querySelectorAll('.btn-editar-cat').forEach(function(btn) {
            btn.addEventListener('click', function() {
                CategoriaManager.editandoId = parseInt(this.dataset.id);
                CategoriaManager.editandoNombre = this.dataset.nombre;
                CategoriaManager.renderCategorias();
            });
        });
        list.querySelectorAll('.btn-guardar-cat').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var input = document.getElementById('editCatInput');
                CategoriaManager.editandoNombre = input.value;
                CategoriaManager.guardarCategoria(parseInt(this.dataset.id));
            });
        });
        var ei = document.getElementById('editCatInput');
        if (ei) {
            ei.focus();
            ei.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { CategoriaManager.editandoNombre = this.value; CategoriaManager.guardarCategoria(CategoriaManager.editandoId); }
                if (e.key === 'Escape') { CategoriaManager.editandoId = null; CategoriaManager.editandoNombre = ''; CategoriaManager.renderCategorias(); }
            });
        }
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
            if (!res.ok) { try { var err = await res.json(); alert(err.message || 'Error'); } catch(e) { alert('Error'); } return; }
            var cat = await res.json();
            this.categorias.push(cat);
            input.value = '';
            var sel = document.getElementById('categoriaSelect');
            var opt = document.createElement('option');
            opt.value = cat.nombre; opt.textContent = cat.nombre; opt.className = 'bg-gray-900';
            sel.appendChild(opt);
            sel.value = cat.nombre;
            this.renderCategorias();
        } catch(e) { console.error(e); }
    },

    async guardarCategoria(id) {
        if (!this.editandoNombre.trim() || id === 0) return;
        try {
            var res = await fetch('/admin/categorias/' + id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ nombre: this.editandoNombre.trim() }),
            });
            if (!res.ok) { try { var err = await res.json(); alert(err.message || 'Error'); } catch(e) { alert('Error'); } return; }
            await this.cargarCategorias();
            this.editandoId = null;
            this.editandoNombre = '';
        } catch(e) { console.error(e); }
    },
};

document.addEventListener('DOMContentLoaded', function() { ProductosManager.init(); CategoriaManager.init(); });
</script>
@endpush
