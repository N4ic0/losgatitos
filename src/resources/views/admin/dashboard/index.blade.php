@extends('layouts.admin')

@section('title', 'Dashboard - Administración')

@section('content')
<div class="space-y-8" x-data="dashboardManager()">
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Disponibles</p>
            <p class="text-3xl font-bold text-green-400 mt-2">{{ $disponibles }}</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Ocupadas</p>
            <p class="text-3xl font-bold text-red-400 mt-2">{{ $ocupadas }}</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Reservadas</p>
            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $reservadas }}</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Limpieza</p>
            <p class="text-3xl font-bold text-blue-400 mt-2">{{ $limpieza }}</p>
        </div>
    </div>

    {{-- Room Grid --}}
    <div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($habitaciones as $habitacion)
            <div @click="abrirModal({{ $habitacion->id }})"
                 class="cursor-pointer bg-white/5 backdrop-blur-xl rounded-2xl p-5 border border-white/5 hover:border-[#D4AF37]/30 hover:bg-white/[0.07] transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-white font-bold text-lg">{{ $habitacion->numero }}</span>
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        @if($habitacion->estado === 'Disponible') bg-green-500/20 text-green-400
                        @elseif($habitacion->estado === 'Ocupada') bg-red-500/20 text-red-400
                        @elseif($habitacion->estado === 'Reservada') bg-yellow-500/20 text-yellow-400
                        @elseif($habitacion->estado === 'Limpieza') bg-blue-500/20 text-blue-400
                        @else bg-gray-500/20 text-gray-400 @endif">
                        {{ $habitacion->estado }}
                    </span>
                </div>
                <p class="text-gray-500 text-xs mb-2">{{ $habitacion->categoria }}</p>

                {{-- Timer for any state --}}
                @if($habitacion->ultimoEstado)
                <div class="timer-{{ $habitacion->id }} text-[#D4AF37] text-xs font-mono"
                     data-inicio="{{ $habitacion->ultimoEstado->fecha_inicio->format('Y-m-d H:i:s') }}">
                    <span class="inline-block tiempo-valor">00:00:00</span>
                    <span class="text-gray-500 text-[10px] ml-1">transcurrido</span>
                </div>
                @endif

                @if($habitacion->estado === 'Ocupada' && $habitacion->ocupacionActiva)
                    @php
                        $oc = $habitacion->ocupacionActiva;
                        $tieneConsumos = $oc->consumos->where('origen', 'Consumo')->count() > 0;
                        $saldoPendiente = ($oc->precio_base + $oc->total_consumos) - $oc->pagos->sum('monto');
                    @endphp
                    <div class="mt-2 space-y-1">
                        <p class="text-gray-400 text-[10px]">
                            ${{ number_format($oc->precio_base, 0, '', '.') }}
                            @if($oc->promocion)
                            <span class="text-[#D4AF37] ml-1">({{ $oc->promocion->titulo }})</span>
                            @endif
                        </p>
                        @if($tieneConsumos)
                        <div class="flex items-center text-[10px] text-orange-400">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                            Consumos: ${{ number_format($oc->total_consumos, 0, '', '.') }}
                        </div>
                        @endif
                        @if($saldoPendiente > 0)
                        <div class="flex items-center text-[10px] text-red-400">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            Pago pendiente: ${{ number_format($saldoPendiente, 0, '', '.') }}
                        </div>
                        @endif
                    </div>
                @elseif($habitacion->estado === 'Reservada' && $habitacion->reservaActiva)
                    <p class="text-[#D4AF37] text-xs mt-2">{{ \Carbon\Carbon::parse($habitacion->reservaActiva->hora)->format('H:i') }} hrs</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Quick actions + Today's reservations --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <h2 class="text-xl font-bold text-white mb-4">Acciones Rápidas</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.reservas.create') }}" class="bg-white/5 hover:bg-white/10 rounded-2xl p-4 border border-white/5 text-center transition-all">
                    <svg class="w-6 h-6 text-[#D4AF37] mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="text-white text-sm">Crear Reserva</span>
                </a>
                <a href="{{ route('admin.habitaciones.index') }}" class="bg-white/5 hover:bg-white/10 rounded-2xl p-4 border border-white/5 text-center transition-all">
                    <svg class="w-6 h-6 text-[#D4AF37] mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span class="text-white text-sm">Gestionar Habitaciones</span>
                </a>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold text-white mb-4">Reservas de Hoy</h2>
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/5 divide-y divide-white/5">
                @forelse($reservasHoy as $reserva)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="text-white font-medium">{{ $reserva->nombre ?? 'Sin nombre' }}</p>
                        <p class="text-gray-400 text-sm">{{ $reserva->rut }} - Hab. {{ $reserva->habitacion?->numero ?? 'Sin asignar' }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full font-medium
                        @if($reserva->estado === 'Reservada') bg-yellow-500/20 text-yellow-400
                        @elseif($reserva->estado === 'Ingresada') bg-green-500/20 text-green-400
                        @else bg-gray-500/20 text-gray-400 @endif">
                        {{ $reserva->estado }}
                    </span>
                </div>
                @empty
                <p class="p-6 text-gray-500 text-sm text-center">No hay reservas para hoy.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Management Modal --}}
    @include('admin.dashboard.partials._modal')
</div>
@endsection

@push('scripts')
<script>
function dashboardManager() {
    return {
        modalOpen: false,
        loading: false,
        habitacionId: null,
        habitacion: null,
        ocupacion: null,
        activeTab: 'estado',
        productos: [],
        promociones: [],

        async init() {
            this.iniciarTimers();
            try {
                const res = await fetch('/admin/dashboard/promociones');
                this.promociones = await res.json();
                const res2 = await fetch('/admin/dashboard/productos');
                this.productos = await res2.json();
            } catch(e) { console.error(e); }
        },

        iniciarTimers() {
            document.querySelectorAll('[data-inicio]').forEach(el => {
                const inicio = new Date(el.dataset.inicio).getTime();
                const span = el.querySelector('.tiempo-valor');
                setInterval(() => {
                    const diff = Math.max(0, Date.now() - inicio);
                    const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
                    const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
                    const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
                    span.textContent = h + ':' + m + ':' + s;
                }, 1000);
            });
        },

        async abrirModal(id) {
            this.habitacionId = id;
            this.loading = true;
            this.modalOpen = true;
            this.activeTab = 'estado';
            try {
                const res = await fetch('/admin/dashboard/habitacion/' + id);
                this.habitacion = await res.json();
                this.ocupacion = this.habitacion.ocupacion_activa || null;
                if (this.ocupacion) {
                    const res2 = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id);
                    const data = await res2.json();
                    this.ocupacion = data.ocupacion;
                }
                this.$nextTick(() => this.iniciarTimers());
            } catch(e) { console.error(e); }
            this.loading = false;
        },

        cerrarModal() {
            this.modalOpen = false;
            this.habitacion = null;
            this.ocupacion = null;
        },

        async cambiarEstado(estado) {
            if (this.habitacion.estado === 'Ocupada' && estado !== 'Disponible') {
                Swal.fire({ icon: 'error', title: 'Debe finalizar la ocupación primero.', confirmButtonColor: '#D4AF37' });
                return;
            }
            try {
                const res = await fetch('/admin/dashboard/habitacion/' + this.habitacion.id + '/cambiar-estado', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ estado }),
                });
                const data = await res.json();
                if (data.success) {
                    this.habitacion.estado = estado;
                    Swal.fire({ icon: 'success', title: 'Estado actualizado a ' + estado, timer: 1500, showConfirmButton: false });
                }
            } catch(e) { console.error(e); }
        },

        async iniciarOcupacion() {
            const promocionId = this.$refs.promocionSelect?.value || null;
            try {
                const res = await fetch('/admin/dashboard/habitacion/' + this.habitacion.id + '/iniciar-ocupacion', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ promocion_id: promocionId }),
                });
                const data = await res.json();
                if (data.success) {
                    this.habitacion.estado = 'Ocupada';
                    this.ocupacion = data.ocupacion;
                    this.activeTab = 'clientes';
                    Swal.fire({ icon: 'success', title: 'Ocupación iniciada', timer: 1500, showConfirmButton: false });
                }
            } catch(e) { console.error(e); }
        },

        async finalizarOcupacion() {
            const confirm = await Swal.fire({ title: '¿Finalizar ocupación?', icon: 'question', showCancelButton: true, confirmButtonColor: '#D4AF37', confirmButtonText: 'Sí, finalizar' });
            if (!confirm.isConfirmed) return;
            try {
                const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/finalizar', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                if (data.success) {
                    this.habitacion.estado = 'Limpieza';
                    this.ocupacion = null;
                    Swal.fire({ icon: 'success', title: 'Ocupación finalizada', timer: 1500, showConfirmButton: false });
                }
            } catch(e) { console.error(e); }
        },

        async registrarCliente() {
            const form = this.$refs.clienteForm;
            const data = new FormData(form);
            try {
                const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/cliente', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: data,
                });
                const json = await res.json();
                if (json.success) {
                    form.reset();
                    await this.recargarOcupacion();
                    Swal.fire({ icon: 'success', title: 'Cliente registrado', timer: 1500, showConfirmButton: false });
                }
            } catch(e) { console.error(e); }
        },

        async agregarConsumo(productoId) {
            try {
                const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/consumo', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ producto_id: productoId, cantidad: 1 }),
                });
                const json = await res.json();
                if (json.success) {
                    await this.recargarOcupacion();
                    Swal.fire({ icon: 'success', title: 'Consumo agregado', timer: 1000, showConfirmButton: false });
                }
            } catch(e) { console.error(e); }
        },

        async registrarPago() {
            const monto = this.$refs.pagoMonto.value;
            const forma = this.$refs.pagoForma.value;
            if (!monto || monto <= 0) { Swal.fire({ icon: 'error', title: 'Ingrese un monto válido' }); return; }
            try {
                const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/pago', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ monto: parseInt(monto), forma_pago: forma }),
                });
                const json = await res.json();
                if (json.success) {
                    this.$refs.pagoMonto.value = '';
                    await this.recargarOcupacion();
                    if (json.saldo_restante <= 0) {
                        this.habitacion.estado = 'Limpieza';
                        this.ocupacion = null;
                        Swal.fire({ icon: 'success', title: 'Pago completado. Habitación enviada a limpieza.', timer: 2000, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'success', title: 'Pago registrado', timer: 1500, showConfirmButton: false });
                    }
                }
            } catch(e) { console.error(e); }
        },

        async agregarObservacion() {
            const contenido = this.$refs.obsInput.value;
            if (!contenido) return;
            try {
                const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/observacion', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ contenido }),
                });
                const json = await res.json();
                if (json.success) {
                    this.$refs.obsInput.value = '';
                    await this.recargarOcupacion();
                }
            } catch(e) { console.error(e); }
        },

        async recargarOcupacion() {
            if (!this.ocupacion) return;
            const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id);
            const data = await res.json();
            this.ocupacion = data.ocupacion;
        },

        estadoColor(estado) {
            const map = { Disponible: 'green', Ocupada: 'red', Reservada: 'yellow', Limpieza: 'blue', Mantenimiento: 'gray' };
            return map[estado] || 'gray';
        },

        totalOcupacion() {
            if (!this.ocupacion) return 0;
            const base = this.ocupacion.precio_base || 0;
            const consumos = (this.ocupacion.consumos || []).filter(c => c.origen === 'Consumo').reduce((s, c) => s + c.total, 0);
            return base + consumos;
        },

        totalPagado() {
            if (!this.ocupacion || !this.ocupacion.pagos) return 0;
            return this.ocupacion.pagos.reduce((s, p) => s + p.monto, 0);
        },

        saldoPendiente() {
            return this.totalOcupacion() - this.totalPagado();
        },

        formatCurrency(val) {
            return '$' + (val || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
    };
}
</script>
@endpush
