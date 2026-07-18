@extends('layouts.admin')

@section('title', 'Ocupaciones')

@push('styles')
<style>
.btn-xs { padding: 0.15rem 0.4rem; font-size: 0.7rem; line-height: 1.2; border-radius: 0.25rem; }
.btn-outline-warning { color: #D4AF37; border-color: #D4AF37; }
.btn-outline-warning:hover { color: #000; background-color: #D4AF37; border-color: #D4AF37; }
.btn-outline-info { color: #3b82f6; border-color: #3b82f6; }
.btn-outline-info:hover { color: #fff; background-color: #3b82f6; border-color: #3b82f6; }
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
</style>
@endpush

@section('content')
<div class="space-y-6">
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Ocupaciones</h1>
</div>

<div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 p-4">
    <table id="ocupaciones-table" class="display responsive nowrap w-full" style="width:100%">
        <thead>
            <tr>
                <th>Habitación</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Tarifa</th>
                <th>Clientes</th>
                <th>Vehículo</th>
                <th>Patente</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
</div>
@endsection

@push('scripts')
<script>
const OcupacionesManager = {
    table: null,

    init: function() {
        this.initDataTable();
        this.bindEvents();
    },

    initDataTable: function() {
        var self = this;
        this.table = $('#ocupaciones-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: { url: '/admin/ocupaciones-json', dataSrc: function(json) { return json; } },
            responsive: true,
            autoWidth: false,
            order: [],
            columns: [
                { data: 'habitacion' },
                { data: 'fecha_inicio' },
                { data: 'fecha_fin' },
                { data: 'tarifa' },
                { data: 'clientes', className: 'text-center' },
                { data: 'vehiculo',
                    render: function(data) {
                        var cls = data ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400';
                        return '<span class="inline-block text-xs px-3 py-1 rounded-full font-medium ' + cls + '">' + (data ? 'Vehículo' : 'Peatón') + '</span>';
                    }
                },
                { data: 'patente', className: 'font-mono uppercase' },
                { data: 'total' },
                { data: 'activa',
                    render: function(data) {
                        var cls = data ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400';
                        return '<span class="inline-block text-xs px-3 py-1 rounded-full font-medium ' + cls + '">' + (data ? 'Activa' : 'Finalizada') + '</span>';
                    }
                },
                { data: null,
                    render: function(row) {
                        return '<a href="/admin/ocupaciones/' + row.id + '" class="btn btn-xs btn-outline-info me-1" title="Ver"><i class="fas fa-eye"></i></a>' +
                               '<button class="accion-eliminar btn btn-xs btn-outline-danger" data-id="' + row.id + '" title="Eliminar"><i class="fas fa-trash-alt"></i></button>';
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

        $(document).on('click', '.accion-eliminar', function() {
            var id = parseInt($(this).data('id'));
            Swal.fire({
                title: '¿Eliminar ocupación?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then(function(result) {
                if (!result.isConfirmed) return;
                fetch('/admin/ocupaciones/' + id, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new URLSearchParams({ _method: 'DELETE' }),
                }).then(function() {
                    self.table.ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Ocupación eliminada', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
                }).catch(function() { location.reload(); });
            });
        });
    },
};

document.addEventListener('DOMContentLoaded', function() { OcupacionesManager.init(); });
</script>
@endpush