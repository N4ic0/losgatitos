@extends('layouts.admin')

@section('title', 'Habitaciones')

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
[data-bs-theme="dark"] .form-control { background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff; }
[data-bs-theme="dark"] .form-control:focus { border-color: #D4AF37; box-shadow: 0 0 0 2px rgba(212,175,55,0.2); }
</style>
@endpush

@section('content')
<div class="space-y-6">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Habitaciones</h1>
    <button type="button" class="accion-nueva-habitacion bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Nueva Habitación</button>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-4">
    <table id="habitaciones-table" class="display responsive nowrap w-full" style="width:100%">
        <thead>
            <tr>
                <th>N°</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

{{-- Modal Habitación (Crear/Editar) --}}
<div class="modal fade" id="habitacionModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-black border border-white/10 shadow-2xl">
            <div class="modal-header border-white/10">
                <h5 class="modal-title text-white font-bold" id="habitacionModalTitle">Nueva Habitación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="habitacionForm">
                    @csrf
                    <input type="hidden" name="_method" id="habitacionMethod" value="">
                    <input type="hidden" name="habitacion_id" id="habitacionId" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Número</label>
                            <input type="text" name="numero" id="habNumero" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                            <select name="categoria" id="habCategoria" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                <option value="Suite" class="bg-gray-900">Suite</option>
                                <option value="Departamento" class="bg-gray-900">Departamento</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Estado</label>
                            <select name="estado" id="habEstado" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                                <option value="Disponible" class="bg-gray-900">Disponible</option>
                                <option value="Reservada" class="bg-gray-900">Reservada</option>
                                <option value="Ocupada" class="bg-gray-900">Ocupada</option>
                                <option value="Limpieza" class="bg-gray-900">Limpieza</option>
                                <option value="Mantenimiento" class="bg-gray-900">Mantenimiento</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-300 text-sm font-medium mb-2">Observaciones</label>
                        <textarea name="observaciones" id="habObservaciones" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none resize-none"></textarea>
                    </div>
                    <div class="d-flex justify-end gap-3 mt-6">
                        <button type="button" class="btn btn-sm text-gray-400" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-gold" id="habitacionFormSubmit">Crear Habitación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
const HabitacionesManager = {
    table: null,
    editandoId: null,

    init: function() {
        this.initDataTable();
        this.bindEvents();
    },

    initDataTable: function() {
        var self = this;
        this.table = $('#habitaciones-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: { url: '/admin/habitaciones-json', dataSrc: function(json) { return json; } },
            responsive: true,
            autoWidth: false,
            order: [],
            columns: [
                { data: 'numero' },
                { data: 'categoria' },
                { data: 'estado',
                    render: function(data) {
                        var cls = 'bg-gray-500/20 text-gray-400';
                        if (data === 'Disponible') cls = 'bg-green-500/20 text-green-400';
                        else if (data === 'Ocupada') cls = 'bg-red-500/20 text-red-400';
                        else if (data === 'Reservada') cls = 'bg-yellow-500/20 text-yellow-400';
                        else if (data === 'Limpieza') cls = 'bg-blue-500/20 text-blue-400';
                        return '<span class="inline-block text-xs px-3 py-1 rounded-full font-medium ' + cls + '">' + data + '</span>';
                    }
                },
                { data: 'observaciones' },
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

        $(document).on('click', '.accion-nueva-habitacion', function() {
            self.abrirModal('create');
        });

        $(document).on('click', '.accion-editar', function() {
            self.abrirModal('edit', parseInt($(this).data('id')));
        });

        $(document).on('click', '.accion-eliminar', function() {
            var id = parseInt($(this).data('id'));
            Swal.fire({
                title: '¿Eliminar habitación?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then(function(result) {
                if (!result.isConfirmed) return;
                fetch('/admin/habitaciones/' + id, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new URLSearchParams({ _method: 'DELETE' }),
                }).then(function() {
                    self.table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Habitación eliminada', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
                }).catch(function() { location.reload(); });
            });
        });

        $('#habitacionForm').on('submit', function(e) {
            e.preventDefault();
            self.guardar();
        });
    },

    abrirModal: function(modo, id) {
        var modal = document.getElementById('habitacionModal');
        var title = document.getElementById('habitacionModalTitle');
        var submitBtn = document.getElementById('habitacionFormSubmit');
        var form = document.getElementById('habitacionForm');
        var methodInput = document.getElementById('habitacionMethod');
        var idInput = document.getElementById('habitacionId');

        form.reset();

        if (modo === 'create') {
            title.textContent = 'Nueva Habitación';
            submitBtn.textContent = 'Crear Habitación';
            methodInput.value = '';
            idInput.value = '';
            this.editandoId = null;
            this.mostrarModal(modal);
        } else if (modo === 'edit' && id) {
            title.textContent = 'Editar Habitación';
            submitBtn.textContent = 'Guardar Cambios';
            methodInput.value = 'PUT';
            idInput.value = id;
            this.editandoId = id;
            this.cargarHabitacion(id, modal);
        }
    },

    cargarHabitacion: function(id, modal) {
        var self = this;
        var token = '{{ csrf_token() }}';
        fetch('/admin/habitaciones/' + id + '/data', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
        }).then(function(res) {
            if (!res.ok) throw new Error('Error al cargar habitación');
            return res.json();
        }).then(function(h) {
            document.getElementById('habNumero').value = h.numero || '';
            document.getElementById('habCategoria').value = h.categoria || '';
            document.getElementById('habEstado').value = h.estado || '';
            document.getElementById('habObservaciones').value = h.observaciones || '';
            self.mostrarModal(modal);
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        });
    },

    guardar: function() {
        var self = this;
        var form = document.getElementById('habitacionForm');
        var formData = new FormData(form);
        var token = '{{ csrf_token() }}';
        formData.set('_token', token);
        var id = parseInt(document.getElementById('habitacionId').value);

        var url, method;
        if (id) {
            url = '/admin/habitaciones/' + id;
            formData.set('_method', 'PUT');
        } else {
            url = '/admin/habitaciones';
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
            var me = document.getElementById('habitacionModal');
            bootstrap.Modal.getInstance(me)?.hide();
            form.reset();
            Swal.fire({ icon: 'success', title: id ? 'Habitación actualizada' : 'Habitación creada', text: json.message || 'Operación exitosa', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
            self.table.ajax.reload();
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
        });
    },

    mostrarModal: function(el) {
        bootstrap.Modal.getOrCreateInstance(el).show();
    },
};

document.addEventListener('DOMContentLoaded', function() { HabitacionesManager.init(); });
</script>
@endpush