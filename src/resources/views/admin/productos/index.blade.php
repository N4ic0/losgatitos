@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
<div x-data="productosManager()" class="space-y-6">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Productos</h1>
    <div class="flex gap-2">
    <a href="{{ route('admin.productos.catalogo') }}" target="_blank" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Catálogo PDF</a>
    <a href="{{ route('admin.productos.create') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nuevo Producto</a>
    </div>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-4">
    <div id="productos-table"></div>
</div>

{{-- Modal Ingreso --}}
<div x-show="ingresoModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="cerrarIngreso()">
    <div class="absolute inset-0 bg-black/90" @click="cerrarIngreso()"></div>
    <div class="relative bg-[#1a1a1a] rounded-xl p-5 w-full max-w-xs border border-white/10 shadow-2xl">
        <h3 class="text-base font-bold text-white mb-1">Ingreso de Stock</h3>
        <p class="text-gray-400 text-xs mb-3" x-text="'Producto: ' + ingresoProductoNombre"></p>
        <form action="{{ route('admin.ingresos.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="producto_id" x-model="ingresoProductoId">

            <div>
                <label class="block text-gray-300 text-xs font-medium mb-1.5">Tipo Documento</label>
                <div class="flex space-x-4">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="tipo_documento" value="Boleta" checked @click="tipoDoc = 'Boleta'" class="text-[#D4AF37] focus:ring-[#D4AF37] bg-white/5 border-white/10">
                        <span class="text-gray-300 text-sm">Boleta</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="tipo_documento" value="Factura" @click="tipoDoc = 'Factura'" class="text-[#D4AF37] focus:ring-[#D4AF37] bg-white/5 border-white/10">
                        <span class="text-gray-300 text-sm">Factura</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-gray-300 text-xs font-medium mb-1.5">N° Documento</label>
                <input type="text" name="numero_documento" :required="tipoDoc === 'Factura'" maxlength="50" class="w-full bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-white text-sm focus:border-[#D4AF37] outline-none">
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
                <button type="button" @click="cerrarIngreso()" class="px-3 py-2 text-gray-400 hover:text-white transition-colors text-xs">Cancelar</button>
                <button type="submit" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-3 py-2 rounded-xl transition-all text-xs">Registrar Ingreso</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function productosManager() {
    return {
        ingresoModalOpen: false,
        ingresoProductoId: null,
        ingresoProductoNombre: '',
        tipoDoc: 'Boleta',
        table: null,

        init() {
            this.$nextTick(() => this.initTabulator());
        },

        initTabulator() {
            const self = this;
            this.table = new Tabulator('#productos-table', {
                ajaxURL: '/admin/productos-json',
                ajaxResponse: (url, params, response) => response,
                layout: 'fitColumns',
                responsiveLayout: 'collapse',
                placeholder: 'No hay productos',
                height: '500px',
                index: 'id',
                columns: [
                    { title: 'Nombre', field: 'nombre', headerFilter: 'input', headerFilterPlaceholder: 'Buscar...', minWidth: 150 },
                    { title: 'Categoría', field: 'categoria', headerFilter: 'input', headerFilterPlaceholder: 'Filtrar...', width: 120,
                        formatter: (cell) => {
                            const v = cell.getValue();
                            const cls = v === 'Colacion' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30';
                            return '<span class="text-xs px-3 py-1 rounded-full font-medium ' + cls + '">' + v + '</span>';
                        }
                    },
                    { title: 'Precio', field: 'precio', headerFilter: false, width: 110, hozAlign: 'right' },
                    { title: 'Factor', field: 'factor', headerFilter: false, width: 90, hozAlign: 'center' },
                    { title: 'Stock', field: 'stock_actual', headerFilter: false, width: 130, hozAlign: 'center',
                        formatter: (cell) => {
                            const v = cell.getValue();
                            const row = cell.getRow().getData();
                            let cls = 'bg-green-500/20 text-green-400';
                            if (row.sin_stock) cls = 'bg-red-500/20 text-red-400';
                            else if (row.bajo_stock) cls = 'bg-yellow-500/20 text-yellow-400';
                            const max = row.stock_maximo > 0 ? ' / ' + row.stock_minimo.toFixed(3) + ' - ' + row.stock_maximo.toFixed(3) : '';
                            return '<span class="text-xs px-3 py-1 rounded-full font-medium ' + cls + '">' + v.toFixed(3) + max + '</span>';
                        }
                    },
                    { title: 'Estado', field: 'activo', headerFilter: false, width: 100, hozAlign: 'center',
                        formatter: (cell) => {
                            const v = cell.getValue();
                            return '<span class="cursor-pointer text-xs px-3 py-1 rounded-full font-medium ' + (v ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30') + '">' + (v ? 'Activo' : 'Inactivo') + '</span>';
                        },
                        cellClick: (e, cell) => {
                            const row = cell.getRow().getData();
                            self.toggleField(row.id, 'activo', cell);
                        }
                    },
                    { title: 'Cortesía', field: 'cortesia', headerFilter: false, width: 100, hozAlign: 'center',
                        formatter: (cell) => {
                            const v = cell.getValue();
                            return '<span class="cursor-pointer text-xs px-3 py-1 rounded-full font-medium ' + (v ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-gray-500/20 text-gray-300 border border-gray-500/30') + '">' + (v ? 'Sí' : 'No') + '</span>';
                        },
                        cellClick: (e, cell) => {
                            const row = cell.getRow().getData();
                            self.toggleField(row.id, 'cortesia', cell);
                        }
                    },
                    { title: 'Acciones', field: 'id', headerFilter: false, width: 200, hozAlign: 'center', frozen: true,
                        formatter: (cell) => {
                            const id = cell.getValue();
                            const nombre = cell.getRow().getData().nombre;
                            return '<button class="accion-ingreso text-blue-400 hover:text-blue-300 text-xs font-medium mr-2" data-id="' + id + '" data-nombre="' + nombre + '">Ingreso</button>' +
                                   '<a href="/admin/productos/' + id + '/edit" class="text-[#D4AF37] hover:text-white text-xs font-medium mr-2">Editar</a>' +
                                   '<button class="accion-eliminar text-red-400 hover:text-red-300 text-xs font-medium" data-id="' + id + '">Eliminar</button>';
                        },
                        cellClick: (e, cell) => {
                            const ingresoBtn = e.target.closest('.accion-ingreso');
                            if (ingresoBtn) {
                                self.abrirIngreso(parseInt(ingresoBtn.dataset.id), ingresoBtn.dataset.nombre);
                                return;
                            }
                            const eliminarBtn = e.target.closest('.accion-eliminar');
                            if (eliminarBtn && confirm('¿Eliminar este producto?')) {
                                const id = parseInt(eliminarBtn.dataset.id);
                                fetch('/admin/productos/' + id, {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: new URLSearchParams({ _method: 'DELETE' }),
                                }).then(() => location.reload());
                            }
                        }
                    },
                ],
                renderComplete: () => {
                    document.querySelectorAll('.tabulator').forEach(el => {
                        el.style.backgroundColor = 'transparent';
                    });
                }
            });
        },

        async toggleField(id, field, cell) {
            try {
                const csrfToken = '{{ csrf_token() }}';
                const res = await fetch('/admin/productos/' + id + '/toggle', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ field }),
                });
                if (!res.ok) {
                    let msg = 'Error del servidor (código ' + res.status + ')';
                    try {
                        const err = await res.json();
                        if (err.message) msg = err.message;
                    } catch(e) {}
                    Swal.fire('Error ' + res.status, msg, 'error');
                    return;
                }
                const json = await res.json();
                if (json.success) {
                    cell.setValue(json.value);
                }
            } catch(e) {
                console.error(e);
                Swal.fire('Error', 'Error de conexión al servidor', 'error');
            }
        },

        abrirIngreso(id, nombre) {
            this.ingresoProductoId = id;
            this.ingresoProductoNombre = nombre;
            this.tipoDoc = 'Boleta';
            this.ingresoModalOpen = true;
        },

        cerrarIngreso() {
            this.ingresoModalOpen = false;
            this.ingresoProductoId = null;
            this.ingresoProductoNombre = '';
        },
    };
}
</script>
@endpush
