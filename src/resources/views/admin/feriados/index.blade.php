@extends('layouts.admin')

@section('title', 'Feriados')

@push('styles')
<style>
.btn-xs { padding: 0.15rem 0.4rem; font-size: 0.7rem; line-height: 1.2; border-radius: 0.25rem; }
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
    <h1 class="text-2xl font-bold text-white">Feriados</h1>
    <div class="flex gap-2">
        <form action="{{ route('admin.feriados.importar') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">
                Importar
            </button>
        </form>
        <button type="button" class="accion-nuevo-feriado bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-5 py-2.5 rounded-xl transition-all text-sm">Agregar Feriado</button>
    </div>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-4">
    <table id="feriados-table" class="display responsive nowrap w-full" style="width:100%">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

{{-- Modal Feriado --}}
<div class="modal fade" id="feriadoModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-black border border-white/10 shadow-2xl">
            <div class="modal-header border-white/10">
                <h5 class="modal-title text-white font-bold">Agregar Feriado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="feriadoForm">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-gray-300 text-sm font-medium mb-2">Fecha</label>
                        <input type="date" name="fecha" id="ferFecha" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Descripción</label>
                        <input type="text" name="descripcion" id="ferDescripcion" required placeholder="Ej: Año Nuevo" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] outline-none">
                    </div>
                    <div class="d-flex justify-end gap-3 mt-6">
                        <button type="button" class="btn btn-sm text-gray-400" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-gold">Agregar Feriado</button>
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
const FeriadosManager = {
    table: null,

    init: function() {
        this.initDataTable();
        this.bindEvents();
    },

    initDataTable: function() {
        var self = this;
        this.table = $('#feriados-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: { url: '/admin/feriados-json', dataSrc: function(json) { return json; } },
            responsive: true,
            autoWidth: false,
            order: [[0, 'desc']],
            columns: [
                { data: 'fecha' },
                { data: 'descripcion' },
                { data: null,
                    render: function(row) {
                        return '<button class="accion-eliminar btn btn-xs btn-outline-danger" data-id="' + row.id + '" title="Eliminar"><i class="fas fa-trash-alt"></i></button>';
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

        $(document).on('click', '.accion-nuevo-feriado', function() {
            document.getElementById('feriadoForm').reset();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('feriadoModal')).show();
        });

        $(document).on('click', '.accion-eliminar', function() {
            var id = parseInt($(this).data('id'));
            Swal.fire({
                title: '¿Eliminar feriado?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then(function(result) {
                if (!result.isConfirmed) return;
                fetch('/admin/feriados/' + id, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new URLSearchParams({ _method: 'DELETE' }),
                }).then(function() {
                    self.table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Feriado eliminado', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
                }).catch(function() { location.reload(); });
            });
        });

        $('#feriadoForm').on('submit', function(e) {
            e.preventDefault();
            self.guardar();
        });
    },

    guardar: function() {
        var self = this;
        var form = document.getElementById('feriadoForm');
        var formData = new FormData(form);
        var token = '{{ csrf_token() }}';
        formData.set('_token', token);

        fetch('/admin/feriados', {
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
            bootstrap.Modal.getInstance(document.getElementById('feriadoModal'))?.hide();
            form.reset();
            Swal.fire({ icon: 'success', title: 'Feriado registrado', text: json.message, timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
            self.table.ajax.reload();
        }).catch(function(err) {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
        });
    },
};

document.addEventListener('DOMContentLoaded', function() { FeriadosManager.init(); });
</script>
@endpush