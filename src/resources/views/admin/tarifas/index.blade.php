@extends('layouts.admin')

@section('title', 'Tarifas')

@section('content')
<div class="space-y-6">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Tarifas</h1>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-4">
    <table id="tarifas-table" class="display responsive nowrap w-full" style="width:100%">
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Tipo</th>
                <th>D-J</th>
                <th>Viernes</th>
                <th>Sábado</th>
                <th>Víspera</th>
                <th>Hora Inicio</th>
                <th>Hora Término</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

{{-- Modal Editar Tarifa --}}
<div class="modal fade" id="tarifaModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-black border border-white/10 shadow-2xl">
            <div class="modal-header border-white/10">
                <h5 class="modal-title text-white font-bold" id="tarifaModalTitle">Editar Tarifa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="tarifaForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="tarifa_id" id="tarifaId" value="">
                    <input type="hidden" name="categoria" id="tarifaCategoria" value="">
                    <input type="hidden" name="tipo_tiempo" id="tarifaTipo" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                            <p id="tarifaCategoriaText" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-400"></p>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Tipo</label>
                            <p id="tarifaTipoText" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-400"></p>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Precio D-J</label>
                            <input type="number" name="precio_dj" id="tarifaPrecioDj" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Precio Viernes</label>
                            <input type="number" name="precio_viernes" id="tarifaPrecioViernes" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Precio Sábado</label>
                            <input type="number" name="precio_sabado" id="tarifaPrecioSabado" required min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Precio Víspera</label>
                            <input type="number" name="precio_vispera" id="tarifaPrecioVispera" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Hora Inicio</label>
                            <input type="time" name="hora_inicio" id="tarifaHoraInicio" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Hora Término</label>
                            <input type="time" name="hora_termino" id="tarifaHoraTermino" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                        </div>
                    </div>
                    <div class="flex space-x-8 mt-4">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="activo" id="tarifaActivo" value="1" class="w-5 h-5 rounded bg-white/5 border-white/10 text-[#D4AF37] focus:ring-[#D4AF37]">
                            <span class="text-gray-300 text-sm">Activo</span>
                        </label>
                    </div>
                    <div class="d-flex justify-end gap-3 mt-6">
                        <button type="button" class="btn btn-sm text-gray-400" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-gold">Guardar Cambios</button>
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
const TarifasManager = {
    table: null,

    init: function() {
        this.initDataTable();
        this.bindEvents();
    },

    initDataTable: function() {
        var self = this;
        this.table = $('#tarifas-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: { url: '/admin/tarifas-json', dataSrc: function(json) { return json; } },
            responsive: true,
            autoWidth: false,
            order: [],
            columns: [
                { data: 'categoria' },
                { data: 'tipo_tiempo' },
                { data: 'precio_dj' },
                { data: 'precio_viernes' },
                { data: 'precio_sabado' },
                { data: 'precio_vispera' },
                { data: 'hora_inicio' },
                { data: 'hora_termino' },
                { data: 'activo',
                    render: function(data, type, row) {
                        var cls = data ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30';
                        return '<span class="badge-estado ' + cls + '" data-id="' + row.id + '" data-field="activo">' + (data ? 'Activo' : 'Inactivo') + '</span>';
                    }
                },
                { data: null,
                    render: function(row) {
                        return '<button class="accion-editar btn btn-xs btn-outline-warning" data-id="' + row.id + '"><i class="fas fa-pencil-alt"></i></button>';
                    },
                    orderable: false,
                    className: 'text-center'
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

        $(document).on('click', '.badge-estado', function() {
            var $badge = $(this);
            var id = parseInt($badge.data('id'));
            var token = '{{ csrf_token() }}';
            fetch('/admin/tarifas/' + id + '/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({ field: 'activo' }),
            }).then(function(res) {
                if (!res.ok) throw new Error('Error del servidor');
                return res.json();
            }).then(function(json) {
                if (json.success) {
                    var cls = json.value ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30';
                    $badge.text(json.value ? 'Activo' : 'Inactivo').attr('class', 'badge-estado ' + cls);
                }
            }).catch(function(err) {
                Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            });
        });

        $(document).on('click', '.accion-editar', function() {
            self.abrirModal(parseInt($(this).data('id')));
        });

        $('#tarifaForm').on('submit', function(e) {
            e.preventDefault();
            self.guardar();
        });
    },

    abrirModal: function(id) {
        var token = '{{ csrf_token() }}';
        var self = this;
        fetch('/admin/tarifas/' + id + '/data', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
        }).then(function(res) {
            if (!res.ok) throw new Error('Error al cargar tarifa');
            return res.json();
        }).then(function(t) {
            document.getElementById('tarifaId').value = t.id;
            document.getElementById('tarifaCategoria').value = t.categoria;
            document.getElementById('tarifaTipo').value = t.tipo_tiempo;
            document.getElementById('tarifaCategoriaText').textContent = t.categoria;
            document.getElementById('tarifaTipoText').textContent = t.tipo_tiempo;
            document.getElementById('tarifaPrecioDj').value = t.precio_dj;
            document.getElementById('tarifaPrecioViernes').value = t.precio_viernes;
            document.getElementById('tarifaPrecioSabado').value = t.precio_sabado;
            document.getElementById('tarifaPrecioVispera').value = t.precio_vispera || '';
            document.getElementById('tarifaHoraInicio').value = t.hora_inicio ? t.hora_inicio.substring(0,5) : '';
            document.getElementById('tarifaHoraTermino').value = t.hora_termino ? t.hora_termino.substring(0,5) : '';
            document.getElementById('tarifaActivo').checked = !!t.activo;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('tarifaModal')).show();
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        });
    },

    guardar: function() {
        var self = this;
        var form = document.getElementById('tarifaForm');
        var formData = new FormData(form);
        var token = '{{ csrf_token() }}';
        formData.set('_token', token);
        formData.set('_method', 'PUT');
        formData.set('activo', document.getElementById('tarifaActivo').checked ? '1' : '0');

        var id = parseInt(document.getElementById('tarifaId').value);
        fetch('/admin/tarifas/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: formData,
        }).then(function(res) {
            if (!res.ok) {
                return res.json().then(function(err) {
                    var msg = err.errors ? Object.values(err.errors).flat().join(', ') : (err.message || 'Error del servidor');
                    throw new Error(msg);
                }).catch(function(e) {
                    if (e instanceof SyntaxError) throw new Error('Error del servidor (código ' + res.status + ')');
                    throw e;
                });
            }
            return res.json();
        }).then(function(json) {
            bootstrap.Modal.getInstance(document.getElementById('tarifaModal'))?.hide();
            form.reset();
            Swal.fire({ icon: 'success', title: 'Tarifa actualizada', text: json.message, timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
            self.table.ajax.reload();
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
        });
    },
};

document.addEventListener('DOMContentLoaded', function() { TarifasManager.init(); });
</script>
@endpush