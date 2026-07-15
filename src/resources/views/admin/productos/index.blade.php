@extends('layouts.admin')

@section('title', 'Productos')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
table.dataTable { color: #fff; }
table.dataTable td, table.dataTable th { border-color: rgba(255,255,255,0.1); }
table.dataTable thead th { background: rgba(255,255,255,0.05); color: #D4AF37; }
table.dataTable tbody tr:hover { background: rgba(212,175,55,0.05); }
.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: #fff;
    border-radius: 0.75rem;
    padding: 0.5rem 1rem;
    outline: none;
}
.dataTables_wrapper .dataTables_filter input:focus,
.dataTables_wrapper .dataTables_length select:focus {
    border-color: #D4AF37;
}
.dataTables_wrapper .dataTables_filter label,
.dataTables_wrapper .dataTables_length label { color: #9ca3af; }
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate { color: #9ca3af; }
.dataTables_wrapper .dataTables_paginate .paginate_button { color: #fff !important; border-radius: 0.5rem; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #D4AF37 !important;
    border-color: #D4AF37 !important;
    color: #000 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: rgba(212,175,55,0.2) !important;
    border-color: rgba(212,175,55,0.3) !important;
    color: #fff !important;
}
table.dataTable tbody td { padding: 0.75rem 0.5rem; }
.badge-categoria { display: inline-block; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; }
.badge-stock { display: inline-block; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; }
.badge-estado { cursor: pointer; display: inline-block; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; }
</style>
@endpush

@section('content')
<div class="space-y-6">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Productos</h1>
    <div class="flex gap-2">
    <a href="{{ route('admin.productos.catalogo') }}" target="_blank" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Catálogo PDF</a>
    <a href="{{ route('admin.productos.create') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nuevo Producto</a>
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
<div class="modal fade" id="ingresoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content bg-[#1a1a1a] rounded-xl p-5 border border-white/10 shadow-2xl">
            <h3 class="text-base font-bold text-white mb-1">Ingreso de Stock</h3>
            <p id="ingresoProductoNombre" class="text-gray-400 text-xs mb-3"></p>
            <form id="ingresoForm" action="{{ route('admin.ingresos.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="producto_id" id="ingresoProductoId">

                <div>
                    <label class="block text-gray-300 text-xs font-medium mb-1.5">Tipo Documento</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="tipo_documento" value="Boleta" checked class="text-[#D4AF37] focus:ring-[#D4AF37] bg-white/5 border-white/10">
                            <span class="text-gray-300 text-sm">Boleta</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="tipo_documento" value="Factura" class="text-[#D4AF37] focus:ring-[#D4AF37] bg-white/5 border-white/10">
                            <span class="text-gray-300 text-sm">Factura</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-300 text-xs font-medium mb-1.5">N° Documento</label>
                    <input type="text" id="numero_documento" name="numero_documento" maxlength="50" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
                </div>
                <div>
                    <label class="block text-gray-300 text-xs font-medium mb-1.5">RUT Proveedor</label>
                    <input type="text" name="rut_proveedor" required maxlength="20" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
                </div>
                <div>
                    <label class="block text-gray-300 text-xs font-medium mb-1.5">Nombre Proveedor</label>
                    <input type="text" name="nombre_proveedor" required maxlength="255" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
                </div>
                <div>
                    <label class="block text-gray-300 text-xs font-medium mb-1.5">Fecha</label>
                    <input type="date" name="fecha" required value="{{ date('Y-m-d') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
                </div>
                <div>
                    <label class="block text-gray-300 text-xs font-medium mb-1.5">Costo Neto</label>
                    <input type="number" name="costo_neto" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
                </div>
                <div>
                    <label class="block text-gray-300 text-xs font-medium mb-1.5">Cantidad</label>
                    <input type="number" name="cantidad" required min="1" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
                </div>

                <div class="flex justify-end space-x-2 pt-1">
                    <button type="button" data-bs-dismiss="modal" class="px-3 py-2 text-gray-400 hover:text-white transition-colors text-xs">Cancelar</button>
                    <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-3 py-2 rounded-xl transition-all text-xs">Registrar Ingreso</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
const ProductosManager = {
    ingresoModal: null,

    init: function() {
        this.ingresoModal = new bootstrap.Modal(document.getElementById('ingresoModal'));
        this.initDataTable();
        this.bindEvents();
    },

    initDataTable: function() {
        const self = this;
        $('#productos-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '/admin/productos-json',
                dataSrc: function(json) { return json; }
            },
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
                        return '<button class="accion-ingreso text-blue-400 hover:text-blue-300 text-xs font-medium mr-2" data-id="' + row.id + '" data-nombre="' + row.nombre + '">Ingreso</button>' +
                               '<a href="/admin/productos/' + row.id + '/edit" class="text-[#D4AF37] hover:text-white text-xs font-medium mr-2">Editar</a>' +
                               '<button class="accion-eliminar text-red-400 hover:text-red-300 text-xs font-medium" data-id="' + row.id + '">Eliminar</button>';
                    },
                    orderable: false
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    },

    bindEvents: function() {
        var self = this;

        $(document).on('click', '.badge-estado', function() {
            var id = parseInt($(this).data('id'));
            var field = $(this).data('field');
            self.toggleField(id, field, $(this));
        });

        $(document).on('click', '.accion-ingreso', function() {
            var id = parseInt($(this).data('id'));
            var nombre = $(this).data('nombre');
            self.abrirIngreso(id, nombre);
        });

        $(document).on('click', '.accion-eliminar', function() {
            if (!confirm('¿Eliminar este producto?')) return;
            var id = parseInt($(this).data('id'));
            fetch('/admin/productos/' + id, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new URLSearchParams({ _method: 'DELETE' }),
            }).then(function() { location.reload(); });
        });

        $('input[name="tipo_documento"]').on('change', function() {
            var req = $(this).val() === 'Factura';
            $('#numero_documento').prop('required', req);
        });
    },

    async toggleField: function(id, field, $el) {
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

    abrirIngreso: function(id, nombre) {
        document.getElementById('ingresoProductoId').value = id;
        document.getElementById('ingresoProductoNombre').textContent = 'Producto: ' + nombre;
        document.querySelector('input[name="tipo_documento"][value="Boleta"]').checked = true;
        document.getElementById('numero_documento').required = false;
        document.getElementById('numero_documento').value = '';
        document.getElementById('ingresoForm').reset();
        this.ingresoModal.show();
    },
};

$(document).ready(function() { ProductosManager.init(); });
</script>
@endpush
