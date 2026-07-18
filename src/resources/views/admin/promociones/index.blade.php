@extends('layouts.admin')

@section('title', 'Promociones')

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
div.dt-container div.dt-search input { padding: 0.5rem 1rem; outline: none; }
div.dt-container div.dt-search label,
div.dt-container div.dt-length label { color: #9ca3af; }
div.dt-container div.dt-info { color: #9ca3af; }
.badge-estado { cursor: pointer; display: inline-block; font-size: 0.75rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 500; }

/* dt-control (botón responsive expand) visible en modo oscuro */
table.dataTable tbody td.dt-control { cursor: pointer; text-align: center; vertical-align: middle; }
table.dataTable tbody td.dt-control::before {
    content: '';
    display: inline-block;
    width: 1rem; height: 1rem;
    border: 2px solid #D4AF37;
    border-radius: 50%;
    background-color: transparent;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23D4AF37' d='M12 5v14M5 12h14'/%3E%3C/svg%3E");
    background-size: 0.7rem;
    background-repeat: no-repeat;
    background-position: center;
    vertical-align: middle;
}
table.dataTable tbody tr.dt-hasChild td.dt-control::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23D4AF37' d='M5 12h14'/%3E%3C/svg%3E");
}
table.dataTable tbody tr.dt-hasChild { background: rgba(212,175,55,0.05); }

.nav-tabs .nav-link { color: #9ca3af; border: none; padding: 0.75rem 1.25rem; font-size: 0.875rem; border-radius: 0.75rem 0.75rem 0 0; transition: all 0.2s; }
.nav-tabs .nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
.nav-tabs .nav-link.active { color: #D4AF37; background: rgba(212,175,55,0.1); border-bottom: 2px solid #D4AF37; }

#productosPromocionTable tbody td { vertical-align: middle; }
#productosPromocionTable .cantidad-input,
#productosPromocionTable .valor-input { width: 70px; padding: 0.2rem 0.4rem; font-size: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.375rem; color: #fff; outline: none; text-align: center; }
#productosPromocionTable .cantidad-input:focus,
#productosPromocionTable .valor-input:focus { border-color: #D4AF37; }

@media (max-width: 768px) {
    /* Modal full-screen en móvil */
    #promocionModal .modal-dialog { margin: 0; max-width: 100%; width: 100%; }
    #promocionModal .modal-content { border-radius: 0; min-height: 100dvh; display: flex; flex-direction: column; }
    #promocionModal .modal-body { flex: 1; overflow-y: auto; padding: 0.75rem; }
    #promocionModal .modal-header { padding: 0.75rem 1rem; }

    /* Tabs más compactos */
    #promocionModal .nav-tabs .nav-link { padding: 0.4rem 0.55rem; font-size: 0.7rem; }
    #promocionModal .nav-tabs { flex-wrap: nowrap; overflow-x: auto; }

    /* Grid de campos en 1 columna */
    #promocionModal .grid { grid-template-columns: 1fr !important; }

    /* Tabla principal: scroll horizontal */
    #promociones-table_wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .dt-scroll-body { overflow-x: auto !important; }

    /* Tabla de productos dentro del modal: scroll horizontal */
    #tab-productos-pane { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    #productosPromocionTable_wrapper { min-width: 0; }
    #productosPromocionTable { min-width: 480px; }

    /* Botones de acción más pequeños en móvil */
    .accion-editar, .accion-eliminar { padding: 0.1rem 0.3rem; font-size: 0.65rem; }

    /* Footer del formulario */
    #promocionModal .d-flex.justify-end { flex-direction: row; gap: 0.5rem; }
}
</style>
@endpush

@section('content')
<div class="space-y-6">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Promociones</h1>
    <button type="button" class="accion-nueva-promocion bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nueva Promoción</button>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-4">
        <table id="promociones-table" class="display responsive w-full" style="width:100%;">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Horario</th>
                    <th>Valor / Hrs Beneficio</th>
                    <th>Tarifas</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
</div>

{{-- Modal Promoción --}}
<div class="modal fade" id="promocionModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-black border border-white/10 shadow-2xl">
            <div class="modal-header border-white/10">
                <h5 class="modal-title text-white font-bold" id="promocionModalTitle">Nueva Promoción</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs flex-nowrap gap-2 mb-4" id="promoTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-info" data-bs-toggle="tab" data-bs-target="#tab-info-pane" type="button" role="tab">Información</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-tarifas" data-bs-toggle="tab" data-bs-target="#tab-tarifas-pane" type="button" role="tab">Tarifas Aplicables</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-productos" data-bs-toggle="tab" data-bs-target="#tab-productos-pane" type="button" role="tab">Productos</button>
                    </li>
                </ul>

                <form id="promocionForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="promocionMethod" value="">
                    <input type="hidden" name="promocion_id" id="promocionId" value="">

                    <div class="tab-content">
                        {{-- Tab 1: Información --}}
                        <div class="tab-pane fade show active" id="tab-info-pane" role="tabpanel">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Título</label>
                                    <input type="text" name="titulo" id="promoTitulo" required maxlength="255" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Valor Promoción</label>
                                    <input type="number" name="valor" id="promoValor" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="promoFechaInicio" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="promoFechaFin" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Desde (hora)</label>
                                    <input type="time" name="desde" id="promoDesde" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Hasta (hora)</label>
                                    <input type="time" name="hasta" id="promoHasta" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Horas Beneficio</label>
                                    <input type="number" name="horas_beneficio" id="promoHorasBeneficio" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Imagen</label>
                                    <div id="promoImagenPreview" class="mb-2 hidden">
                                        <img id="promoImagenImg" class="h-16 w-16 object-cover rounded-lg">
                                    </div>
                                    <input type="file" name="imagen" id="promoImagen" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white file:bg-[#D4AF37] file:text-black file:font-semibold file:px-4 file:py-2 file:rounded-xl file:border-0 file:cursor-pointer">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
                                <textarea name="descripcion" id="promoDescripcion" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none"></textarea>
                            </div>
                            <div class="flex items-center gap-8 mt-4">
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" name="activo" id="promoActivo" value="1" checked class="w-5 h-5 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                                    <span class="text-gray-300 text-sm">Activo</span>
                                </label>
                            </div>
                        </div>

                        {{-- Tab 2: Tarifas --}}
                        <div class="tab-pane fade" id="tab-tarifas-pane" role="tabpanel">
                            <label class="block text-gray-300 text-sm font-medium mb-2">Selecciona las tarifas aplicables</label>
                            <div id="chipPickerTarifas" class="flex flex-wrap gap-2">
                                <button type="button" class="chip-option" data-value="D-J_3h">D-J 3h</button>
                                <button type="button" class="chip-option" data-value="D-J_8h">D-J 8h</button>
                                <button type="button" class="chip-option" data-value="Viernes_3h">Viernes 3h</button>
                                <button type="button" class="chip-option" data-value="Viernes_8h">Viernes 8h</button>
                                <button type="button" class="chip-option" data-value="Sábado_3h">Sábado 3h</button>
                                <button type="button" class="chip-option" data-value="Sábado_8h">Sábado 8h</button>
                                <button type="button" class="chip-option" data-value="Víspera_3h">Víspera 3h</button>
                                <button type="button" class="chip-option" data-value="Víspera_8h">Víspera 8h</button>
                            </div>
                            <div id="tarifasHiddenContainer"></div>
                        </div>

                        {{-- Tab 3: Productos --}}
                        <div class="tab-pane fade" id="tab-productos-pane" role="tabpanel">
                            <label class="block text-gray-300 text-sm font-medium mb-2">Selecciona los productos incluidos</label>
                            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                            <table id="productosPromocionTable" class="display w-full" style="width:100%; min-width: 480px;">
                                <thead>
                                    <tr>
                                        <th style="width:40px"><input type="checkbox" id="checkAllProductos"></th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th style="width:90px">Precio</th>
                                        <th style="width:85px">Cantidad</th>
                                        <th style="width:110px">Valor Prom.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productos as $producto)
                                    <tr>
                                        <td><input type="checkbox" name="productos[]" value="{{ $producto->id }}" class="check-producto"></td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->categoria }}</td>
                                        <td>${{ number_format($producto->precio, 0, '', '.') }}</td>
                                        <td><input type="number" name="cantidades[{{ $producto->id }}]" class="cantidad-input" value="1" min="1"></td>
                                        <td><input type="number" name="valores_promocion[{{ $producto->id }}]" class="valor-input" placeholder="$0" min="0"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-end gap-3 mt-6 pt-4 border-t border-white/10">
                        <button type="button" class="btn btn-sm text-gray-400" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-gold" id="promocionFormSubmit">Crear Promoción</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<style>
/* ===== Chip Picker darkmode ===== */
.chip-option {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 9999px;
    padding: 0.4rem 1rem;
    color: #9ca3af;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
    user-select: none;
}
.chip-option:hover {
    border-color: rgba(212,175,55,0.4);
    color: #d1d5db;
    background: rgba(255,255,255,0.08);
}
.chip-option.selected {
    background: #2d2d1a;
    border-color: #D4AF37;
    color: #D4AF37;
    box-shadow: 0 0 8px rgba(212,175,55,0.15);
}
.chip-option.selected:hover {
    background: rgba(45,45,26,0.9);
    border-color: #D4AF37;
}
#chipPickerTarifas {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
</style>
<script>
// ===== Chip Picker helpers =====
function chipPickerInit() {
    document.querySelectorAll('#chipPickerTarifas .chip-option').forEach(function(chip) {
        chip.addEventListener('click', function() {
            this.classList.toggle('selected');
            chipPickerSyncHidden();
        });
    });
}
function chipPickerSyncHidden() {
    var container = document.getElementById('tarifasHiddenContainer');
    if (!container) return;
    container.innerHTML = '';
    document.querySelectorAll('#chipPickerTarifas .chip-option.selected').forEach(function(chip) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tarifas[]';
        input.value = chip.dataset.value;
        container.appendChild(input);
    });
}
function chipPickerSetValues(values) {
    if (!values) return;
    var arr = Array.isArray(values) ? values : values.split(',');
    document.querySelectorAll('#chipPickerTarifas .chip-option').forEach(function(chip) {
        chip.classList.toggle('selected', arr.indexOf(chip.dataset.value) !== -1);
    });
    chipPickerSyncHidden();
}
function chipPickerClear() {
    document.querySelectorAll('#chipPickerTarifas .chip-option.selected').forEach(function(chip) {
        chip.classList.remove('selected');
    });
    chipPickerSyncHidden();
}

const PromocionesManager = {
    table: null,
    productosTable: null,
    editandoId: null,
    promocionData: null,

    init: function() {
        this.initDataTable();
        this.bindEvents();
        chipPickerInit();
    },

    initDataTable: function() {
        var self = this;
        this.table = $('#promociones-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: { url: '/admin/promociones-json', dataSrc: function(json) { return json; } },
            responsive: true,
            autoWidth: false,
            order: [],
            columns: [
                { data: 'titulo' },
                { data: 'horario' },
                { data: null, render: function(row) {
                    var html = row.valor;
                    if (row.horas_beneficio) html += ' <span class="text-xs text-gray-400">/ ' + row.horas_beneficio + 'h beneficio</span>';
                    return html;
                }},
                { data: 'tarifas' },
                { data: 'fecha_inicio' },
                { data: 'fecha_fin' },
                { data: 'productos_count', className: 'text-center' },
                { data: 'activo',
                    render: function(data, type, row) {
                        var cls = data ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30';
                        return '<span class="badge-estado ' + cls + '" data-id="' + row.id + '">' + (data ? 'Activa' : 'Inactiva') + '</span>';
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
                paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' },
            }
        });
    },

    bindEvents: function() {
        var self = this;

        $(document).on('click', '.accion-nueva-promocion', function() {
            self.abrirModal('create');
        });

        $(document).on('click', '.accion-editar', function() {
            self.abrirModal('edit', parseInt($(this).data('id')));
        });

        $(document).on('click', '.accion-eliminar', function() {
            var id = parseInt($(this).data('id'));
            Swal.fire({
                title: '¿Eliminar promoción?',
                text: 'Esta acción no se puede deshacer.',
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
                fetch('/admin/promociones/' + id, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new URLSearchParams({ _method: 'DELETE' }),
                }).then(function() {
                    self.table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Promoción eliminada', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
                }).catch(function() { location.reload(); });
            });
        });

        $('#promocionForm').on('submit', function(e) {
            e.preventDefault();
            self.guardar();
        });

        $('#promocionModal').on('hidden.bs.modal', function() {
            if (self.productosTable) {
                var rows = self.productosTable.rows().nodes().to$();
                rows.find('.check-producto').prop('checked', false);
                rows.find('.cantidad-input').val('1');
                rows.find('.valor-input').val('');
            }
            $('#checkAllProductos').prop('checked', false);
            chipPickerClear();
        });

        $('#tab-productos').on('shown.bs.tab', function() {
            if (!self.productosTable) {
                self.productosTable = $('#productosPromocionTable').DataTable({
                    paging: true,
                    pageLength: 12,
                    info: false,
                    searching: true,
                    ordering: true,
                    autoWidth: false,
                    order: [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 4, 5] },
                        { width: '40px', targets: 0 },
                        { width: '100px', targets: [4, 5] },
                    ],
                    language: { search: 'Buscar producto:', zeroRecords: 'No se encontraron productos' },
                    dom: '<"mb-2"f>t<"mt-2"p>',
                });
                $('#checkAllProductos').on('click', function() {
                    var checked = this.checked;
                    self.productosTable.rows().every(function() {
                        $(this.node()).find('.check-producto').prop('checked', checked);
                    });
                });
                $(document).on('click', '.check-producto', function() {
                    var allChecked = true;
                    self.productosTable.rows().every(function() {
                        if (!$(this.node()).find('.check-producto').prop('checked')) { allChecked = false; }
                    });
                    $('#checkAllProductos').prop('checked', allChecked);
                });
            }
            if (self.promocionData && self.promocionData.productos && self.promocionData.productos.length) {
                var p = self.promocionData;
                var productoIds = p.productos.map(function(pr) { return pr.id; });
                self.productosTable.rows().every(function() {
                    var row = this.node();
                    var checkbox = $(row).find('.check-producto');
                    var id = parseInt(checkbox.val());
                    if (productoIds.indexOf(id) !== -1) {
                        checkbox.prop('checked', true);
                        var prod = p.productos.find(function(pr) { return pr.id === id; });
                        if (prod && prod.pivot) {
                            if (prod.pivot.cantidad) $(row).find('.cantidad-input').val(prod.pivot.cantidad);
                            if (prod.pivot.valor_promocion) $(row).find('.valor-input').val(prod.pivot.valor_promocion);
                        }
                    }
                });
                var tbody = self.productosTable.table().body();
                var trs = $(tbody).find('tr').detach();
                var checked = trs.filter(function() { return $(this).find('.check-producto').prop('checked'); });
                var unchecked = trs.filter(function() { return !$(this).find('.check-producto').prop('checked'); });
                $(tbody).append(checked).append(unchecked);
                self.promocionData = null;
            }
        });
    },

    abrirModal: function(modo, id) {
        var modal = document.getElementById('promocionModal');
        var title = document.getElementById('promocionModalTitle');
        var submitBtn = document.getElementById('promocionFormSubmit');
        var form = document.getElementById('promocionForm');
        var methodInput = document.getElementById('promocionMethod');
        var idInput = document.getElementById('promocionId');

        form.reset();
        document.getElementById('promoActivo').checked = true;
        document.getElementById('promoImagenPreview').classList.add('hidden');

        if (modo === 'create') {
            title.textContent = 'Nueva Promoción';
            submitBtn.textContent = 'Crear Promoción';
            methodInput.value = '';
            idInput.value = '';
            this.editandoId = null;
            this.limpiarTabProductos();
            this.mostrarModal(modal);
        } else if (modo === 'edit' && id) {
            title.textContent = 'Editar Promoción';
            submitBtn.textContent = 'Guardar Cambios';
            methodInput.value = 'PUT';
            idInput.value = id;
            this.editandoId = id;
            this.cargarPromocion(id, modal);
        }
    },

    cargarPromocion: function(id, modal) {
        var self = this;
        fetch('/admin/promociones/' + id + '/data', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        }).then(function(res) {
            if (!res.ok) throw new Error('Error al cargar promoción');
            return res.json();
        }).then(function(p) {
            document.getElementById('promoTitulo').value = p.titulo || '';
            document.getElementById('promoDescripcion').value = p.descripcion || '';
            document.getElementById('promoValor').value = p.valor || '';
            document.getElementById('promoFechaInicio').value = p.fecha_inicio ? p.fecha_inicio.substring(0,10) : '';
            document.getElementById('promoFechaFin').value = p.fecha_fin ? p.fecha_fin.substring(0,10) : '';
            document.getElementById('promoDesde').value = p.desde ? p.desde.substring(0,5) : '';
            document.getElementById('promoHasta').value = p.hasta ? p.hasta.substring(0,5) : '';
            document.getElementById('promoHorasBeneficio').value = p.horas_beneficio || '';
            document.getElementById('promoActivo').checked = !!p.activo;
            if (p.imagen) {
                document.getElementById('promoImagenImg').src = '/storage/' + p.imagen;
                document.getElementById('promoImagenPreview').classList.remove('hidden');
            }

            chipPickerSetValues(p.tarifas);

            self.promocionData = p;
            self.mostrarModal(modal);
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        });
    },

    guardar: function() {
        var self = this;
        var form = document.getElementById('promocionForm');
        var formData = new FormData(form);
        var token = '{{ csrf_token() }}';
        formData.set('_token', token);
        var id = parseInt(document.getElementById('promocionId').value);

        var url, method;
        if (id) {
            url = '/admin/promociones/' + id;
            formData.set('_method', 'PUT');
        } else {
            url = '/admin/promociones';
            formData.delete('_method');
        }

        if (self.productosTable) {
            var anyChecked = false;
            self.productosTable.rows().every(function() {
                var row = this.node();
                var checkbox = $(row).find('.check-producto');
                if (checkbox.prop('checked')) {
                    anyChecked = true;
                } else {
                    var val = checkbox.val();
                    formData.delete('productos[]', val);
                    formData.delete('valores_promocion[' + val + ']');
                }
            });
            if (!anyChecked) {
                formData.set('productos[]', '');
            }
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
            var me = document.getElementById('promocionModal');
            bootstrap.Modal.getInstance(me)?.hide();
            form.reset();
            Swal.fire({ icon: 'success', title: id ? 'Promoción actualizada' : 'Promoción creada', text: json.message, timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
            self.table.ajax.reload();
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
        });
    },

    mostrarModal: function(el) {
        bootstrap.Modal.getOrCreateInstance(el).show();
    },

    limpiarTabProductos: function() {
        if (this.productosTable) {
            var rows = this.productosTable.rows().nodes().to$();
            rows.find('.check-producto').prop('checked', false);
            rows.find('.cantidad-input').val('1');
            rows.find('.valor-input').val('');
        }
        $('#checkAllProductos').prop('checked', false);
        chipPickerClear();
    },
};

document.addEventListener('DOMContentLoaded', function() { PromocionesManager.init(); });
</script>
@endpush
