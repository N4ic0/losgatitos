@extends('layouts.admin')

@section('title', 'Dashboard - Administración')

@section('content')
<div class="space-y-8">
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5 d-flex flex-column justify-content-center align-items-center">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Disponibles</p>
            <p class="text-3xl font-bold text-green-400 mt-2">{{ $disponibles }}</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5 d-flex flex-column justify-content-center align-items-center">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Ocupadas</p>
            <p class="text-3xl font-bold text-red-400 mt-2">{{ $ocupadas }}</p>
        </div>
        <a href="{{ route('admin.reservas.create') }}" class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5 hover:border-[#D4AF37]/30 transition-all duration-200 block d-flex flex-column justify-content-center align-items-center">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Reservadas</p>
            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $reservadas }}</p>
        </a>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5 d-flex flex-column justify-content-center align-items-center">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Limpieza</p>
            <p class="text-3xl font-bold text-blue-400 mt-2">{{ $limpieza }}</p>
        </div>
    </div>

    {{-- Room Grid --}}
    <div>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem;">
            @foreach($habitaciones as $habitacion)
            <div onclick="dashboard.abrirModal({{ $habitacion->id }})"
                 class="cursor-pointer bg-white/5 backdrop-blur-xl rounded-xl px-3 py-2.5 border border-white/5 hover:border-[#D4AF37]/30 hover:bg-white/[0.07] transition-all duration-300 flex flex-col" style="min-height: 5rem;">
                <div class="flex items-center justify-between">
                    <span class="text-white font-bold text-sm">{{ $habitacion->numero }}</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full font-medium
                        @if($habitacion->estado === 'Disponible') bg-green-500/20 text-green-400
                        @elseif($habitacion->estado === 'Ocupada') bg-red-500/20 text-red-400
                        @elseif($habitacion->estado === 'Reservada') bg-yellow-500/20 text-yellow-400
                        @elseif($habitacion->estado === 'Limpieza') bg-blue-500/20 text-blue-400
                        @else bg-gray-500/20 text-gray-400 @endif">
                        {{ $habitacion->estado }}
                    </span>
                </div>
                <p class="text-gray-400 text-[10px] leading-tight mt-0.5">{{ $habitacion->categoria }}</p>

                <div class="flex items-center gap-1.5 mt-auto pt-1">
                    @if($habitacion->ultimoEstado && $habitacion->estado !== 'Disponible')
                    <span class="timer-{{ $habitacion->id }} text-[#D4AF37] text-[10px] font-mono"
                          data-inicio="{{ $habitacion->ultimoEstado->fecha_inicio->timestamp }}">
                        <span class="tiempo-valor">00:00:00</span>
                    </span>
                    @endif
                    @if($habitacion->estado === 'Ocupada' && $habitacion->ocupacionActiva && $habitacion->ocupacionActiva->tarifa)
                        <span class="text-gray-300 text-[9px] font-medium bg-red-500/20 text-red-400 px-1.5 py-0.5 rounded">{{ $habitacion->ocupacionActiva->tarifa->tipo_tiempo }}</span>
                    @elseif($habitacion->estado === 'Reservada' && $habitacion->reservaActiva)
                        <span class="text-[#D4AF37] text-[10px] font-medium bg-yellow-500/20 text-yellow-400 px-1.5 py-0.5 rounded">{{ \Carbon\Carbon::parse($habitacion->reservaActiva->hora)->format('H:i') }} hrs</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Management Modal --}}
    @include('admin.dashboard.partials._modal')
</div>
@endsection

@push('scripts')
<script>
class DashboardManager {
    constructor() {
        this.modalOpen = false;
        this.loading = false;
        this.habitacionId = null;
        this.habitacion = null;
        this.ocupacion = null;
        this.activeTab = 'estado';
        this.productos = [];
        this.promociones = [];
        this.tipoTiempo = '8h';
        this.tarifaInfo = null;
        this.personasAdicionales = 0;
        this.ocupacionVehiculo = 1;
        this.ocupacionPatente = '';
        this.fechaNacimiento = '';
        this.clienteNombres = '';
        this.clienteApellidos = '';
        this.clienteDocumento = '';
        this.rutValido = null;
        this.consumoSelectorOpen = false;
        this.categoriaFiltro = null;
        this.promocionesAplicables = [];
        this.cortesiaAdded = false;
        this._tieneCortesiaBackend = false;

        this.modalInstance = null;
        this.init();
    }

    async init() {
        this.iniciarTimers();
        try {
            const res = await fetch('/admin/dashboard/promociones');
            this.promociones = await res.json();
            const res2 = await fetch('/admin/dashboard/productos');
            this.productos = await res2.json();
        } catch(e) { console.error(e); }
    }

    _onModalHidden() {
        this.modalOpen = false;
        this.habitacion = null;
        this.ocupacion = null;
        this.tarifaInfo = null;
        location.reload();
    }

    async calcularTarifaPreview() {
        if (!this.habitacion) return;
        try {
            const res = await fetch('/admin/dashboard/calcular-tarifa?categoria=' + this.habitacion.categoria + '&tipo_tiempo=' + this.tipoTiempo);
            if (!res.ok) { this.tarifaInfo = null; this._renderTarifaInfo(); return; }
            this.tarifaInfo = await res.json();
            this._renderTarifaInfo();
        } catch(e) { this.tarifaInfo = null; this._renderTarifaInfo(); }
    }

    iniciarTimers() {
        document.querySelectorAll('[data-inicio]').forEach(el => {
            const inicio = parseInt(el.dataset.inicio) * 1000;
            if (isNaN(inicio)) return;
            const span = el.querySelector('.tiempo-valor');
            setInterval(() => {
                const diff = Math.max(0, Date.now() - inicio);
                const h = Math.floor(diff / 3600000).toString().padStart(2, '0');
                const m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
                const s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
                span.textContent = h + ':' + m + ':' + s;
            }, 1000);
        });
    }

    _initModal() {
        if (!this.modalInstance) {
            const el = document.getElementById('roomModal');
            if (el) {
                this.modalInstance = new bootstrap.Modal(el, { backdrop: 'static', keyboard: false });
                el.addEventListener('hidden.bs.modal', () => this._onModalHidden());
                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        this.cerrarModal();
                    }
                });
            }
        }
    }

    async abrirModal(id) {
        this._initModal();
        this.habitacionId = id;
        this.loading = true;
        this.activeTab = 'estado';
        this.tipoTiempo = '8h';
        this.tarifaInfo = null;
        this.personasAdicionales = 0;
        this.cortesiaAdded = false;
        this._tieneCortesiaBackend = false;

        this._showLoading();
        this.modalInstance.show();

        try {
            const res = await fetch('/admin/dashboard/habitacion/' + id);
            this.habitacion = await res.json();
            this.ocupacion = this.habitacion.ocupacion_activa || null;
            if (this.ocupacion) {
                const res2 = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id);
                const data = await res2.json();
                this.ocupacion = data.ocupacion;
                this.promocionesAplicables = data.promociones_aplicables || [];
                this.ocupacionVehiculo = this.ocupacion.vehiculo ? 1 : 0;
                this.ocupacionPatente = this.ocupacion.patente || '';
                this._tieneCortesiaBackend = data.tiene_cortesia || false;
            }
        await this.calcularTarifaPreview();
        this._renderAll();
        this._hideLoading();
        requestAnimationFrame(() => this.iniciarTimers());
        if (this.ocupacion) {
            const ocupacionTab = bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-ocupacion-btn'));
            ocupacionTab.show();
        } else {
            const estadoTab = bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-estado-btn'));
            estadoTab.show();
        }
        } catch(e) { console.error(e); this._hideLoading(); }
        this.loading = false;
    }

    cerrarModal() {
        if (!this.ocupacion) {
            if (this.modalInstance) this.modalInstance.hide();
            return;
        }
        const hasClientes = this.ocupacion.clientes && this.ocupacion.clientes.length > 0;
        if (!hasClientes) {
            Swal.fire({
                icon: 'warning',
                title: 'Falto agregar clientes',
                text: 'Debe registrar al menos un cliente antes de cerrar.',
                confirmButtonColor: '#D4AF37',
                confirmButtonText: 'Entendido',
            }).then((result) => {
                if (result.isConfirmed) {
                    const tab = bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-clientes-btn'));
                    tab.show();
                }
            });
            return;
        }
        const tieneCortesia = this.cortesiaAdded || this._tieneCortesiaBackend;
        if (!tieneCortesia) {
            Swal.fire({
                icon: 'warning',
                title: 'No agrego consumo cortesia',
                text: 'Debe agregar al menos un ítem de cortesía antes de cerrar.',
                confirmButtonColor: '#D4AF37',
                confirmButtonText: 'Ir a Consumos',
            }).then((result) => {
                if (result.isConfirmed) {
                    const tab = bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-consumos-btn'));
                    tab.show();
                }
            });
            return;
        }
        if (this.modalInstance) this.modalInstance.hide();
    }

    _showLoading() {
        const el = document.getElementById('modal-loading');
        if (el) el.classList.remove('d-none');
    }

    _hideLoading() {
        const el = document.getElementById('modal-loading');
        if (el) el.classList.add('d-none');
    }

    _renderAll() {
        this._renderHeader();
        this._renderEstadoTab();
        this._renderOcupacionTab();
        this._renderClientesTab();
        this._renderConsumosTab();
        this._renderCobroTab();
        this._renderHistorialTab();
        this._renderObservacionesTab();
    }

    _renderHeader() {
        const header = document.getElementById('modal-header');
        if (!this.habitacion) {
            header.classList.add('d-none');
            header.classList.remove('d-flex', 'align-items-center', 'justify-content-between');
            return;
        }
        header.classList.remove('d-none');
        header.classList.add('d-flex', 'align-items-center', 'justify-content-between');

        document.getElementById('modal-hab-numero').textContent = 'Hab. ' + this.habitacion.numero;
        document.getElementById('modal-hab-categoria').textContent = this.habitacion.categoria || '';

        const badge = document.getElementById('modal-hab-estado-badge');
        const color = this.estadoColor(this.habitacion.estado);
        badge.className = 'text-xs px-3 py-1 rounded-full font-medium bg-' + color + '-500/20 text-' + color + '-400';
        badge.textContent = this.habitacion.estado;

        const timerEl = document.getElementById('modal-timer');
        if (this.habitacion.ultimo_estado && this.habitacion.estado !== 'Disponible') {
            timerEl.style.display = '';
            timerEl.dataset.inicio = new Date(this.habitacion.ultimo_estado.fecha_inicio).getTime() / 1000;
            const valEl = timerEl.querySelector('.tiempo-valor');
            if (valEl) valEl.textContent = '00:00:00';
        } else {
            timerEl.style.display = 'none';
        }
    }

    _renderEstadoTab() {
        const estados = ['Disponible', 'Reservada', 'Ocupada', 'Limpieza'];
        const container = document.getElementById('estado-btns');
        if (!container) return;
        container.innerHTML = estados.map(est => {
            const isActive = this.habitacion && this.habitacion.estado === est;
            return '<button onclick="dashboard.cambiarEstado(\'' + est + '\')" ' +
                   (isActive ? 'disabled ' : '') +
                   'class="py-3 px-4 rounded-xl font-medium text-sm transition-all border ' +
                   (isActive
                       ? 'bg-[#D4AF37]/20 border-[#D4AF37]/40 text-[#D4AF37] cursor-not-allowed'
                       : 'bg-white/5 border-white/10 text-gray-300 hover:bg-white/10 hover:border-[#D4AF37]/30') +
                   '">' + est + '</button>';
        }).join('');

        const ocupacionSection = document.getElementById('estado-iniciar-ocupacion');
        if (this.habitacion && (this.habitacion.estado === 'Disponible' || this.habitacion.estado === 'Reservada')) {
            ocupacionSection.classList.remove('d-none');
        } else {
            ocupacionSection.classList.add('d-none');
        }

        this._renderTarifaInfo();
        this._renderPersonasAdicionales();
    }

    _renderTarifaInfo() {
        const container = document.getElementById('tarifa-info');
        if (!container) return;
        if (!this.tarifaInfo) {
            container.classList.add('d-none');
            return;
        }
        container.classList.remove('d-none');
        const info = this.tarifaInfo;
        document.getElementById('tarifa-categoria').textContent = info.categoria + ' - ' + info.tipo_tiempo;
        document.getElementById('tarifa-regla').textContent = info.regla;
        document.getElementById('tarifa-valor').textContent = this.formatCurrency(info.precio);
        document.getElementById('tarifa-horario').textContent = info.hora_inicio + ' - ' + info.hora_termino;

        const personasSection = document.getElementById('tarifa-personas-section');
        if (personasSection) personasSection.classList.remove('d-none');
    }

    _renderPersonasAdicionales() {
        document.getElementById('personas-count').textContent = this.personasAdicionales;
        const clientesPersonasCount = document.getElementById('clientes-personas-count');
        if (clientesPersonasCount) {
            clientesPersonasCount.textContent = this.personasAdicionales;
        }
        const ocupacionPersonasCount = document.getElementById('ocupacion-personas-count');
        if (ocupacionPersonasCount) {
            ocupacionPersonasCount.textContent = this.ocupacion?.personas_adicionales ?? this.personasAdicionales;
        }
        const extraContainer = document.getElementById('personas-extra');
        if (this.personasAdicionales > 0 && this.tarifaInfo) {
            extraContainer.classList.remove('d-none');
            const extraTotal = Math.round(this.tarifaInfo.precio * 0.5 * this.personasAdicionales);
            document.getElementById('personas-extra-total').textContent = this.formatCurrency(extraTotal);
        } else {
            extraContainer.classList.add('d-none');
        }

        document.querySelectorAll('.tipo-tiempo-btn').forEach(btn => {
            const tt = btn.dataset.tipoTiempo;
            if (tt === this.tipoTiempo) {
                btn.className = 'flex-1 py-3 px-4 rounded-xl font-medium text-sm transition-all border bg-[#D4AF37]/30 border-[#D4AF37]/60 text-[#D4AF37] shadow-lg shadow-[#D4AF37]/20';
            } else {
                btn.className = 'flex-1 py-3 px-4 rounded-xl font-medium text-sm transition-all border bg-white/5 border-white/10 text-gray-300 hover:bg-white/10 hover:border-white/20';
            }
        });
    }

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
                this._renderHeader();
                this._renderEstadoTab();
                Swal.fire({ icon: 'success', title: 'Estado actualizado a ' + estado, timer: 1500, showConfirmButton: false });
            }
        } catch(e) { console.error(e); }
    }

    setTipoTiempo(tt) {
        this.tipoTiempo = tt;
        this.calcularTarifaPreview();
        this._renderPersonasAdicionales();
    }

    cambiarPersonasAdicionales(delta) {
        this.personasAdicionales = Math.max(0, this.personasAdicionales + delta);
        this._renderPersonasAdicionales();
    }

    async cambiarPersonasOcupacion(delta) {
        if (!this.ocupacion) return;
        const actual = this.ocupacion.personas_adicionales ?? this.personasAdicionales;
        const nueva = Math.max(0, actual + delta);
        if (nueva === actual) return;
        try {
            const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/personas-adicionales', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ cantidad: nueva }),
            });
            if (!res.ok) { console.error('Error al actualizar personas adicionales'); return; }
            const data = await res.json();
            if (data.success) {
                this.ocupacion = data.ocupacion;
                this.personasAdicionales = nueva;
                this._renderOcupacionTab();
                this._renderCobroTab();
                this._renderPersonasAdicionales();
            }
        } catch(e) { console.error(e); }
    }

    async iniciarOcupacion() {
        try {
            const res = await fetch('/admin/dashboard/habitacion/' + this.habitacion.id + '/iniciar-ocupacion', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ tipo_tiempo: this.tipoTiempo, personas_adicionales: this.personasAdicionales }),
            });
            const data = await res.json();
            if (data.success) {
                this.habitacion.estado = 'Ocupada';
                this.ocupacion = data.ocupacion;
                this._renderHeader();
                this._renderEstadoTab();
                this._renderOcupacionTab();
                this._renderClientesTab();
                this._renderConsumosTab();
                this._renderCobroTab();
                const tab = bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-ocupacion-btn'));
                tab.show();
                Swal.fire({ icon: 'success', title: 'Ocupación iniciada (' + this.tipoTiempo + ')', timer: 1500, showConfirmButton: false });
            }
        } catch(e) { console.error(e); }
    }

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
                this._renderHeader();
                this._renderEstadoTab();
                this._renderOcupacionTab();
                this._renderClientesTab();
                this._renderConsumosTab();
                this._renderCobroTab();
                this._renderHistorialTab();
                this._renderObservacionesTab();
                Swal.fire({ icon: 'success', title: 'Ocupación finalizada', timer: 1500, showConfirmButton: false });
            }
        } catch(e) { console.error(e); }
    }

    async tomarPromocion(promocion) {
        try {
            const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/tomar-promocion/' + promocion.id, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            });
            const data = await res.json();
            if (data.success) {
                this.ocupacion = data.ocupacion;
                this.promocionesAplicables = data.promociones_aplicables || [];
                this._renderOcupacionTab();
                Swal.fire({ icon: 'success', title: 'Promoción aplicada: ' + promocion.titulo, timer: 2000, showConfirmButton: false });
            }
        } catch(e) { console.error(e); }
    }

    async agregarPromoProductos(promocion) {
        const promocionId = promocion?.id || this.ocupacion?.promocion?.id;
        if (!promocionId) return;
        try {
            const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/productos-promocion', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ promocion_id: promocionId }),
            });
            const data = await res.json();
            if (data.success) {
                this.ocupacion = data.ocupacion;
                this.promocionesAplicables = data.promociones_aplicables || [];
                await this.recargarOcupacion();
            }
        } catch(e) { console.error(e); }
    }

    validarEdad() {
        if (!this.fechaNacimiento) return;
        const hoy = new Date();
        const nac = new Date(this.fechaNacimiento);
        let edad = hoy.getFullYear() - nac.getFullYear();
        const m = hoy.getMonth() - nac.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
        if (edad < 18) {
            const nombre = (this.clienteNombres + ' ' + this.clienteApellidos).trim() || 'Persona';
            Swal.fire({ icon: 'warning', title: nombre + ' es menor de edad (' + edad + ' años)', confirmButtonColor: '#D4AF37' });
        }
    }

    onClienteNacimientoChange(value) {
        this.fechaNacimiento = value;
        this.validarEdad();
    }

    onClienteDocumentoChange(value) {
        this.clienteDocumento = value.toUpperCase();
        document.getElementById('cliente-documento').value = this.clienteDocumento;
        this.validarRutInput();
        this._renderRutValidez();
    }

    validarRutInput() {
        const rut = this.clienteDocumento;
        if (!rut || rut.length < 2) { this.rutValido = null; return; }
        this.rutValido = this.validarRutCompleto(rut);
    }

    validarRutCompleto(rut) {
        if (!rut) return false;
        const limpio = rut.replace(/[^0-9kK-]/g, '').replace(/-/g, '');
        if (limpio.length < 2) return false;
        const cuerpo = limpio.slice(0, -1);
        const dv = limpio.slice(-1).toUpperCase();
        if (!/^\d+$/.test(cuerpo)) return false;
        let suma = 0;
        let multiplo = 2;
        for (let i = cuerpo.length - 1; i >= 0; i--) {
            suma += parseInt(cuerpo[i]) * multiplo;
            multiplo = multiplo === 7 ? 2 : multiplo + 1;
        }
        const resto = suma % 11;
        const dvCalculado = 11 - resto;
        let dvEsperado;
        if (dvCalculado === 11) dvEsperado = '0';
        else if (dvCalculado === 10) dvEsperado = 'K';
        else dvEsperado = dvCalculado.toString();
        return dv === dvEsperado;
    }

    onTipoDocumentoChange(value) {
        if (value !== 'RUT') {
            this.rutValido = null;
            this._renderRutValidez();
        }
    }

    _renderRutValidez() {
        const iconContainer = document.getElementById('rut-icon');
        if (!iconContainer) return;
        if (this.rutValido === true) {
            iconContainer.innerHTML = '<svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        } else if (this.rutValido === false) {
            iconContainer.innerHTML = '<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        } else {
            iconContainer.innerHTML = '';
        }
        const input = document.getElementById('cliente-documento');
        if (input) {
            input.classList.remove('border-green-500', 'border-red-500', 'border-white/10');
            if (this.rutValido === true) input.classList.add('border-green-500');
            else if (this.rutValido === false) input.classList.add('border-red-500');
            else input.classList.add('border-white/10');
        }
    }

    onClienteInput(id, value) {
        const uc = value.toUpperCase();
        document.getElementById(id).value = uc;
        if (id === 'cliente-nombres') this.clienteNombres = uc;
        if (id === 'cliente-apellidos') this.clienteApellidos = uc;
    }

    async registrarCliente() {
        const form = document.getElementById('cliente-form');
        const tipoDoc = form.querySelector('[name="tipo_documento"]').value;
        if (!tipoDoc) {
            Swal.fire({ icon: 'error', title: 'Seleccione tipo de documento', confirmButtonColor: '#D4AF37' });
            return;
        }
        const numDoc = form.querySelector('[name="numero_documento"]').value.trim();
        if (!numDoc) {
            Swal.fire({ icon: 'error', title: 'Ingrese número de documento', confirmButtonColor: '#D4AF37' });
            return;
        }
        if (tipoDoc === 'RUT' && this.rutValido !== true) {
            Swal.fire({ icon: 'error', title: 'RUT inválido', text: 'Verifique el RUT ingresado', confirmButtonColor: '#D4AF37' });
            return;
        }
        const nombres = form.querySelector('[name="nombres"]').value.trim();
        if (!nombres) {
            Swal.fire({ icon: 'error', title: 'Ingrese nombres', confirmButtonColor: '#D4AF37' });
            return;
        }
        const apellidos = form.querySelector('[name="apellidos"]').value.trim();
        if (!apellidos) {
            Swal.fire({ icon: 'error', title: 'Ingrese apellidos', confirmButtonColor: '#D4AF37' });
            return;
        }
        const nacionalidad = form.querySelector('[name="nacionalidad"]').value.trim();
        if (!nacionalidad) {
            Swal.fire({ icon: 'error', title: 'Ingrese nacionalidad', confirmButtonColor: '#D4AF37' });
            return;
        }
        const fechaNac = form.querySelector('[name="fecha_nacimiento"]').value;
        if (!fechaNac) {
            Swal.fire({ icon: 'error', title: 'Ingrese fecha de nacimiento', confirmButtonColor: '#D4AF37' });
            return;
        }
        const data = new FormData(form);
        try {
            const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/cliente', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: data,
            });
            const json = await res.json();
            if (json.success) {
                this.clienteDocumento = '';
                this.rutValido = null;
                form.reset();
                await this.recargarOcupacion();
                Swal.fire({ icon: 'success', title: 'Cliente registrado', timer: 1500, showConfirmButton: false });
            }
        } catch(e) { console.error(e); }
    }

    async abrirSelectorConsumos() {
        if (this.productos.length === 0) {
            try {
                const res = await fetch('/admin/dashboard/productos');
                this.productos = await res.json();
            } catch(e) { return; }
        }
        const categorias = [...new Set(this.productos.map(p => p.categoria))];
        let catActual = null;
        const selected = {};
        const self = this;

        const mapProd = (id) => self.productos.find(p => p.id === id);

        const render = (cat) => {
            const filtrados = cat ? self.productos.filter(p => p.categoria === cat) : self.productos;
            const totalSel = Object.entries(selected).reduce((s, [id, qty]) => s + (mapProd(parseInt(id))?.precio || 0) * qty, 0);
            const hasSel = Object.keys(selected).length > 0;
            return `
                <div class="cat-filter-bar" style="margin-bottom:10px;display:flex;flex-wrap:wrap;gap:5px;">
                    <button class="cat-btn ${!cat ? 'active' : ''}" data-cat="" style="padding:5px 12px;border-radius:6px;font-size:11px;font-weight:500;border:none;cursor:pointer;transition:all 0.2s;${!cat ? 'background:#D4AF37;color:#000;' : 'background:rgba(255,255,255,0.1);color:#d1d5db;'}">Todas</button>
                    ${categorias.map(c => `<button class="cat-btn ${cat === c ? 'active' : ''}" data-cat="${c}" style="padding:5px 12px;border-radius:6px;font-size:11px;font-weight:500;border:none;cursor:pointer;transition:all 0.2s;${cat === c ? 'background:#D4AF37;color:#000;' : 'background:rgba(255,255,255,0.1);color:#d1d5db;'}">${c}</button>`).join('')}
                </div>
                <div class="prod-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:${hasSel ? '10px' : '0'};">
                    ${filtrados.map(p => {
                        const qty = selected[p.id] || 0;
                        const sel = qty > 0;
                        return `<div class="prod-item" data-id="${p.id}" style="cursor:pointer;background:${sel ? 'rgba(212,175,55,0.15)' : 'rgba(255,255,255,0.05)'};border-radius:10px;border:1px solid ${sel ? 'rgba(212,175,55,0.3)' : 'rgba(255,255,255,0.05)'};overflow:hidden;transition:all 0.2s;position:relative;">
                            ${p.imagen
                                ? `<img src="/storage/${p.imagen}" style="width:100%;height:70px;object-fit:cover;display:block;">`
                                : `<div style="height:70px;background:rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;"><svg width="24" height="24" fill="none" stroke="#6b7280" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg></div>`
                            }
                            ${sel ? `<span style="position:absolute;top:4px;right:4px;background:#D4AF37;color:#000;font-size:10px;font-weight:bold;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;">${qty}</span>` : ''}
                            <div style="padding:6px;">
                                <p style="color:#fff;font-size:11px;font-weight:500;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.nombre}</p>
                                <p style="color:#D4AF37;font-size:11px;font-weight:bold;margin:2px 0 0 0;">${self.formatCurrency(p.precio)}</p>
                            </div>
                        </div>`;
                    }).join('')}
                </div>
                ${hasSel ? `
                <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:10px;">
                    <p style="color:rgba(255,255,255,0.6);font-size:11px;font-weight:500;margin:0 0 8px 0;">Productos seleccionados</p>
                    ${Object.entries(selected).map(([id, qty]) => {
                        const p = mapProd(parseInt(id));
                        if (!p) return '';
                        return `<div style="display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.05);border-radius:8px;padding:6px 10px;margin-bottom:4px;">
                            <span style="color:#fff;font-size:12px;flex:1;">${p.nombre}</span>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <button class="qty-btn" data-id="${id}" data-op="minus" style="width:24px;height:24px;border-radius:6px;border:none;cursor:pointer;background:rgba(255,255,255,0.1);color:#fff;font-size:14px;font-weight:bold;display:flex;align-items:center;justify-content:center;">−</button>
                                <span style="color:#D4AF37;font-size:13px;font-weight:bold;min-width:20px;text-align:center;">${qty}</span>
                                <button class="qty-btn" data-id="${id}" data-op="plus" style="width:24px;height:24px;border-radius:6px;border:none;cursor:pointer;background:rgba(255,255,255,0.1);color:#fff;font-size:14px;font-weight:bold;display:flex;align-items:center;justify-content:center;">+</button>
                                <span style="color:#D4AF37;font-size:12px;font-weight:bold;min-width:60px;text-align:right;">${self.formatCurrency((p.precio || 0) * qty)}</span>
                            </div>
                        </div>`;
                    }).join('')}
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;padding-top:8px;border-top:1px solid rgba(255,255,255,0.1);">
                        <span style="color:#fff;font-size:14px;font-weight:bold;">Total Consumos</span>
                        <span style="color:#D4AF37;font-size:16px;font-weight:bold;">${self.formatCurrency(totalSel)}</span>
                    </div>
                    <button class="confirm-consumos-btn" style="width:100%;margin-top:10px;padding:10px;border-radius:10px;border:none;cursor:pointer;background:#D4AF37;color:#000;font-size:13px;font-weight:bold;">Confirmar Consumos</button>
                </div>` : ''}
            `;
        };

        const refresh = () => {
            const htmlContainer = Swal.getHtmlContainer();
            if (htmlContainer) htmlContainer.innerHTML = render(catActual);
        };

        Swal.fire({
            title: 'Agregar Consumo (' + self.productos.length + ' prod.)',
            html: render(catActual),
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#6b7280',
            didOpen: () => {
                const popup = Swal.getPopup();
                popup.addEventListener('click', async (e) => {
                    const btn = e.target.closest('.cat-btn');
                    if (btn) {
                        catActual = btn.dataset.cat || null;
                        refresh();
                        return;
                    }
                    const item = e.target.closest('.prod-item');
                    if (item) {
                        const id = parseInt(item.dataset.id);
                        selected[id] = (selected[id] || 0) + 1;
                        refresh();
                        return;
                    }
                    const qtyBtn = e.target.closest('.qty-btn');
                    if (qtyBtn) {
                        const id = qtyBtn.dataset.id;
                        if (qtyBtn.dataset.op === 'plus') {
                            selected[id] = (selected[id] || 0) + 1;
                        } else {
                            selected[id]--;
                            if (selected[id] <= 0) delete selected[id];
                        }
                        refresh();
                        return;
                    }
                    const confirmBtn = e.target.closest('.confirm-consumos-btn');
                    if (confirmBtn) {
                        const entries = Object.entries(selected);
                        Swal.close();
                        await self.agregarConsumosFinalizar(entries, false);
                    }
                });
            },
            customClass: {
                popup: 'rounded-2xl border border-white/10 shadow-2xl',
                title: 'text-white font-bold text-lg',
                htmlContainer: 'w-full',
                cancelButton: 'font-semibold px-6 py-3 rounded-xl text-sm',
            },
            background: '#1a1a2e',
            color: '#e5e7eb',
        });
    }

    async abrirSelectorCortesias() {
        if (this.productos.length === 0) {
            try {
                const res = await fetch('/admin/dashboard/productos');
                this.productos = await res.json();
            } catch(e) { return; }
        }
        const cortesias = this.productos.filter(p => p.cortesia);
        if (cortesias.length === 0) {
            Swal.fire({ icon: 'info', title: 'No hay productos de cortesía disponibles.', confirmButtonColor: '#D4AF37' });
            return;
        }
        const categorias = [...new Set(cortesias.map(p => p.categoria))];
        let catActual = null;
        const selected = {};
        const self = this;

        const mapProd = (id) => cortesias.find(p => p.id === id);

        const render = (cat) => {
            const filtrados = cat ? cortesias.filter(p => p.categoria === cat) : cortesias;
            const hasSel = Object.keys(selected).length > 0;
            return `
                <div class="cat-filter-bar" style="margin-bottom:10px;display:flex;flex-wrap:wrap;gap:5px;">
                    <button class="cat-btn ${!cat ? 'active' : ''}" data-cat="" style="padding:5px 12px;border-radius:6px;font-size:11px;font-weight:500;border:none;cursor:pointer;transition:all 0.2s;${!cat ? 'background:#D4AF37;color:#000;' : 'background:rgba(255,255,255,0.1);color:#d1d5db;'}">Todas</button>
                    ${categorias.map(c => `<button class="cat-btn ${cat === c ? 'active' : ''}" data-cat="${c}" style="padding:5px 12px;border-radius:6px;font-size:11px;font-weight:500;border:none;cursor:pointer;transition:all 0.2s;${cat === c ? 'background:#D4AF37;color:#000;' : 'background:rgba(255,255,255,0.1);color:#d1d5db;'}">${c}</button>`).join('')}
                </div>
                <div class="prod-grid" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:${hasSel ? '10px' : '0'};">
                    ${filtrados.map(p => {
                        const qty = selected[p.id] || 0;
                        const sel = qty > 0;
                        return `<div class="prod-item" data-id="${p.id}" style="cursor:pointer;background:${sel ? 'rgba(212,175,55,0.15)' : 'rgba(255,255,255,0.05)'};border-radius:10px;border:1px solid ${sel ? 'rgba(212,175,55,0.3)' : 'rgba(255,255,255,0.05)'};overflow:hidden;transition:all 0.2s;position:relative;">
                            ${p.imagen
                                ? `<img src="/storage/${p.imagen}" style="width:100%;height:70px;object-fit:cover;display:block;">`
                                : `<div style="height:70px;background:rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;"><svg width="24" height="24" fill="none" stroke="#6b7280" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg></div>`
                            }
                            ${sel ? `<span style="position:absolute;top:4px;right:4px;background:#D4AF37;color:#000;font-size:10px;font-weight:bold;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;">${qty}</span>` : ''}
                            <div style="padding:6px;">
                                <p style="color:#fff;font-size:11px;font-weight:500;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.nombre}</p>
                                <p style="color:#10b981;font-size:11px;font-weight:bold;margin:2px 0 0 0;">Gratis</p>
                            </div>
                        </div>`;
                    }).join('')}
                </div>
                ${hasSel ? `
                <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:10px;">
                    <p style="color:rgba(255,255,255,0.6);font-size:11px;font-weight:500;margin:0 0 8px 0;">Productos seleccionados</p>
                    ${Object.entries(selected).map(([id, qty]) => {
                        const p = mapProd(parseInt(id));
                        if (!p) return '';
                        return `<div style="display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.05);border-radius:8px;padding:6px 10px;margin-bottom:4px;">
                            <span style="color:#fff;font-size:12px;flex:1;">${p.nombre}</span>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <button class="qty-btn" data-id="${id}" data-op="minus" style="width:24px;height:24px;border-radius:6px;border:none;cursor:pointer;background:rgba(255,255,255,0.1);color:#fff;font-size:14px;font-weight:bold;display:flex;align-items:center;justify-content:center;">−</button>
                                <span style="color:#D4AF37;font-size:13px;font-weight:bold;min-width:20px;text-align:center;">${qty}</span>
                                <button class="qty-btn" data-id="${id}" data-op="plus" style="width:24px;height:24px;border-radius:6px;border:none;cursor:pointer;background:rgba(255,255,255,0.1);color:#fff;font-size:14px;font-weight:bold;display:flex;align-items:center;justify-content:center;">+</button>
                                <span style="color:#10b981;font-size:12px;font-weight:bold;min-width:60px;text-align:right;">Gratis</span>
                            </div>
                        </div>`;
                    }).join('')}
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;padding-top:8px;border-top:1px solid rgba(255,255,255,0.1);">
                        <span style="color:#fff;font-size:14px;font-weight:bold;">Total Cortesía</span>
                        <span style="color:#10b981;font-size:16px;font-weight:bold;">$0</span>
                    </div>
                    <button class="confirm-cortesias-btn" style="width:100%;margin-top:10px;padding:10px;border-radius:10px;border:none;cursor:pointer;background:#10b981;color:#fff;font-size:13px;font-weight:bold;">Confirmar Cortesía</button>
                </div>` : ''}
            `;
        };

        const refresh = () => {
            const htmlContainer = Swal.getHtmlContainer();
            if (htmlContainer) htmlContainer.innerHTML = render(catActual);
        };

        Swal.fire({
            title: 'Agregar Cortesía',
            html: render(catActual),
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#6b7280',
            didOpen: () => {
                const popup = Swal.getPopup();
                popup.addEventListener('click', async (e) => {
                    const btn = e.target.closest('.cat-btn');
                    if (btn) {
                        catActual = btn.dataset.cat || null;
                        refresh();
                        return;
                    }
                    const item = e.target.closest('.prod-item');
                    if (item) {
                        const id = parseInt(item.dataset.id);
                        selected[id] = (selected[id] || 0) + 1;
                        refresh();
                        return;
                    }
                    const qtyBtn = e.target.closest('.qty-btn');
                    if (qtyBtn) {
                        const id = qtyBtn.dataset.id;
                        if (qtyBtn.dataset.op === 'plus') {
                            selected[id] = (selected[id] || 0) + 1;
                        } else {
                            selected[id]--;
                            if (selected[id] <= 0) delete selected[id];
                        }
                        refresh();
                        return;
                    }
                    const confirmBtn = e.target.closest('.confirm-cortesias-btn');
                    if (confirmBtn) {
                        const entries = Object.entries(selected);
                        Swal.close();
                        await self.agregarConsumosFinalizar(entries, true);
                    }
                });
            },
            customClass: {
                popup: 'rounded-2xl border border-white/10 shadow-2xl',
                title: 'text-white font-bold text-lg',
                htmlContainer: 'w-full',
                cancelButton: 'font-semibold px-6 py-3 rounded-xl text-sm',
            },
            background: '#1a1a2e',
            color: '#e5e7eb',
        });
    }

    async agregarConsumosFinalizar(entries, cortesia = false) {
        const url = cortesia
            ? '/admin/dashboard/ocupacion/' + this.ocupacion.id + '/cortesia'
            : '/admin/dashboard/ocupacion/' + this.ocupacion.id + '/consumo';
        const headers = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' };
        try {
            await Promise.all(entries.map(([id, qty]) =>
                fetch(url, {
                    method: 'POST',
                    headers,
                    body: JSON.stringify({ producto_id: parseInt(id), cantidad: qty }),
                }).then(r => r.json())
            ));
            if (cortesia) this.cortesiaAdded = true;
        } catch(e) { console.error(e); }
        await this.recargarOcupacion();
    }

    async actualizarCantidadConsumo(consumoId, nuevaCantidad) {
        if (nuevaCantidad < 1) {
            await this.eliminarConsumoItem(consumoId);
            return;
        }
        try {
            const res = await fetch('/admin/dashboard/consumo/' + consumoId, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ cantidad: nuevaCantidad }),
            });
            const json = await res.json();
            if (json.success) {
                await this.recargarOcupacion();
            }
        } catch(e) { console.error(e); }
    }

    async eliminarConsumoItem(consumoId) {
        try {
            const confirm = await Swal.fire({
                title: '¿Eliminar consumo?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#D4AF37',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            });
            if (!confirm.isConfirmed) return;
            const res = await fetch('/admin/dashboard/consumo/' + consumoId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            });
            const json = await res.json();
            if (json.success) {
                await this.recargarOcupacion();
            }
        } catch(e) { console.error(e); }
    }

    async registrarPago() {
        const monto = document.getElementById('pago-monto').value;
        const forma = document.getElementById('pago-forma').value;
        if (!monto || monto <= 0) { Swal.fire({ icon: 'error', title: 'Ingrese un monto válido' }); return; }
        try {
            const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/pago', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ monto: parseInt(monto), forma_pago: forma }),
            });
            const json = await res.json();
            if (json.success) {
                document.getElementById('pago-monto').value = '';
                await this.recargarOcupacion();
                Swal.fire({ icon: 'success', title: 'Pago registrado', timer: 1500, showConfirmButton: false });
            }
        } catch(e) { console.error(e); }
    }

    setSaldoPendiente() {
        document.getElementById('pago-monto').value = this.saldoPendiente();
    }

    async agregarObservacion() {
        const contenido = document.getElementById('obs-input').value;
        if (!contenido) return;
        try {
            const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/observacion', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ contenido }),
            });
            const json = await res.json();
            if (json.success) {
                document.getElementById('obs-input').value = '';
                await this.recargarOcupacion();
            }
        } catch(e) { console.error(e); }
    }

    onObsKeydown(e) {
        if (e.key === 'Enter') this.agregarObservacion();
    }

    async recargarOcupacion() {
        if (!this.ocupacion) return;
        const res = await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id);
        const data = await res.json();
        this.ocupacion = data.ocupacion;
        this.ocupacionVehiculo = this.ocupacion.vehiculo ? 1 : 0;
        this.ocupacionPatente = this.ocupacion.patente || '';
        this.promocionesAplicables = data.promociones_aplicables || [];
        this._tieneCortesiaBackend = data.tiene_cortesia || false;
        this._renderAll();
    }

    onVehiculoChange(value) {
        this.ocupacionVehiculo = value;
        this._renderClientesTab();
        this.actualizarVehiculo();
    }

    onPatenteChange(value) {
        this.ocupacionPatente = value.toUpperCase();
        document.getElementById('ocupacion-patente').value = this.ocupacionPatente;
        this.actualizarVehiculo();
    }

    async actualizarVehiculo() {
        if (!this.ocupacion) return;
        try {
            await fetch('/admin/dashboard/ocupacion/' + this.ocupacion.id + '/vehiculo', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    vehiculo: this.ocupacionVehiculo == 1,
                    patente: this.ocupacionPatente || null,
                }),
            });
        } catch(e) { console.error(e); }
    }

    _renderOcupacionTab() {
        const empty = document.getElementById('ocupacion-empty');
        const content = document.getElementById('ocupacion-content');
        if (!this.ocupacion) {
            empty.classList.remove('d-none');
            content.classList.add('d-none');
            return;
        }
        empty.classList.add('d-none');
        content.classList.remove('d-none');

        document.getElementById('ocupacion-inicio').textContent = new Date(this.ocupacion.fecha_inicio).toLocaleString('es-CL');
        document.getElementById('ocupacion-precio-base').textContent = this.formatCurrency(this.ocupacion.precio_base);

        const personasSection = document.getElementById('ocupacion-personas-section');
        const personasCount = document.getElementById('ocupacion-personas-count');
        const personasExtraCost = document.getElementById('ocupacion-personas-extra-cost');
        const personasExtraTotal = document.getElementById('ocupacion-personas-extra-total');
        if (personasSection && this.ocupacion.tarifa) {
            personasSection.classList.remove('d-none');
            const pa = this.ocupacion.personas_adicionales || 0;
            if (personasCount) personasCount.textContent = pa;
            if (pa > 0 && personasExtraCost) {
                personasExtraCost.classList.remove('d-none');
                const extraTotal = Math.round((this.ocupacion.precio_base / (1 + 0.5 * pa)) * 0.5 * pa);
                if (personasExtraTotal) personasExtraTotal.textContent = this.formatCurrency(extraTotal);
            } else {
                if (personasExtraCost) personasExtraCost.classList.add('d-none');
            }
        } else if (personasSection) {
            personasSection.classList.add('d-none');
        }

        const tarifaInfo = document.getElementById('ocupacion-tarifa-info');
        if (this.ocupacion.tarifa) {
            tarifaInfo.classList.remove('d-none');
            document.getElementById('ocupacion-tarifa-categoria').textContent = this.ocupacion.tarifa.categoria;
            document.getElementById('ocupacion-tarifa-tipo').textContent = this.ocupacion.tarifa.tipo_tiempo;
            document.getElementById('ocupacion-tarifa-horario').textContent = (this.ocupacion.tarifa.hora_inicio || '08:00') + ' - ' + (this.ocupacion.tarifa.hora_termino || '08:00');
        } else {
            tarifaInfo.classList.add('d-none');
        }

        const beneficioRow = document.getElementById('ocupacion-beneficio');
        if (this.ocupacion.horas_beneficio > 0) {
            beneficioRow.classList.remove('d-none');
            document.getElementById('ocupacion-beneficio-valor').textContent = this.ocupacion.horas_beneficio + ' horas';
        } else {
            beneficioRow.classList.add('d-none');
        }

        const promosContainer = document.getElementById('ocupacion-promos-container');
        const promosSection = document.getElementById('ocupacion-promos-section');
        const promociones = this.promocionesAplicables || [];

        if (!this.ocupacion.promocion && promociones.length > 0) {
            promosSection.classList.remove('d-none');
            promosContainer.innerHTML = promociones.map(p => {
                const hasProducts = p.productos && p.productos.length > 0;
                return '<div class="bg-gradient-to-r from-[#D4AF37]/10 to-transparent rounded-xl p-4 border border-[#D4AF37]/20">' +
                    '<div class="flex items-start justify-between">' +
                    '<div>' +
                    '<p class="text-[#D4AF37] font-semibold text-sm">🎉 ' + this._escapeHtml(p.titulo) + '</p>' +
                    '<p class="text-green-400 text-xs mt-1">Beneficio: ' + p.horas_beneficio + ' horas</p>' +
                    (hasProducts ? '<p class="text-gray-400 text-[10px] mt-1">Incluye ' + p.productos.length + ' producto(s)</p>' : '') +
                    '</div>' +
                    '<button onclick="dashboard.tomarPromocion(' + p.id + ')" class="bg-green-600 hover:bg-green-500 text-white font-bold px-5 py-2 rounded-xl transition-all text-xs">Tomar</button>' +
                    '</div></div>';
            }).join('');
        } else {
            promosSection.classList.add('d-none');
        }

        const promoActiva = document.getElementById('ocupacion-promo-activa');
        if (this.ocupacion.promocion) {
            promoActiva.classList.remove('d-none');
            document.getElementById('ocupacion-promo-activa-titulo').textContent = this.ocupacion.promocion.titulo;
        } else {
            promoActiva.classList.add('d-none');
        }
    }

    _renderClientesTab() {
        const empty = document.getElementById('clientes-empty');
        const content = document.getElementById('clientes-content');
        if (!this.ocupacion) {
            empty.classList.remove('d-none');
            content.classList.add('d-none');
            return;
        }
        empty.classList.add('d-none');
        content.classList.remove('d-none');

        const radio1 = document.getElementById('vehiculo-si');
        const radio2 = document.getElementById('vehiculo-no');
        if (this.ocupacionVehiculo == 1) { radio1.checked = true; } else { radio2.checked = true; }

        const patenteSection = document.getElementById('patente-section');
        if (this.ocupacionVehiculo == 1) {
            patenteSection.classList.remove('d-none');
            document.getElementById('ocupacion-patente').value = this.ocupacionPatente || '';
        } else {
            patenteSection.classList.add('d-none');
        }

        const clientesPersonasCount = document.getElementById('clientes-personas-count');
        if (clientesPersonasCount) {
            clientesPersonasCount.textContent = this.personasAdicionales;
        }

        const clientesList = document.getElementById('clientes-list');
        if (this.ocupacion.clientes && this.ocupacion.clientes.length > 0) {
            document.getElementById('clientes-registered').classList.remove('d-none');
            clientesList.innerHTML = this.ocupacion.clientes.map(c =>
                '<div class="bg-white/5 rounded-xl px-4 py-3 border border-white/5 flex items-center justify-between">' +
                '<div>' +
                '<p class="text-white text-sm font-medium">' + this._escapeHtml(c.nombres + ' ' + c.apellidos) + '</p>' +
                '<p class="text-gray-400 text-xs">' + this._escapeHtml(c.tipo_documento + ': ' + c.numero_documento) + '</p>' +
                '</div>' +
                '<span class="text-gray-500 text-xs">' + this._escapeHtml(c.nacionalidad || '') + '</span>' +
                '</div>'
            ).join('');
        } else {
            document.getElementById('clientes-registered').classList.add('d-none');
        }
    }

    _renderConsumosTab() {
        const empty = document.getElementById('consumos-empty');
        const content = document.getElementById('consumos-content');
        if (!this.ocupacion) {
            empty.classList.remove('d-none');
            content.classList.add('d-none');
            return;
        }
        empty.classList.add('d-none');
        content.classList.remove('d-none');

        const promoBtnContainer = document.getElementById('consumos-promo-btns');
        if (this.ocupacion.promocion && this.ocupacion.promocion.productos && this.ocupacion.promocion.productos.length > 0) {
            promoBtnContainer.innerHTML = '<button onclick="dashboard.agregarPromoProductos(dashboard.ocupacion.promocion)" class="bg-green-600 hover:bg-green-500 text-white font-semibold px-5 py-3 rounded-xl transition-all text-sm">+ Agregar Promoción</button>';
        } else if (!this.ocupacion.promocion) {
            const promos = this.promocionesAplicables || [];
            const withProducts = promos.filter(p => p.productos && p.productos.length > 0);
            if (withProducts.length > 0) {
                promoBtnContainer.innerHTML = withProducts.map(p =>
                    '<button onclick="dashboard.agregarPromoProductos(' + p.id + ')" class="w-full bg-green-600 hover:bg-green-500 text-white font-semibold px-5 py-3 rounded-xl transition-all text-sm">+ Agregar ' + this._escapeHtml(p.titulo) + '</button>'
                ).join('');
            } else {
                promoBtnContainer.innerHTML = '';
            }
        } else {
            promoBtnContainer.innerHTML = '';
        }

        const consumosList = document.getElementById('consumos-list');
        const consumosSection = document.getElementById('consumos-registered');
        if (this.ocupacion.consumos && this.ocupacion.consumos.length > 0) {
            consumosSection.classList.remove('d-none');
            consumosList.innerHTML = this.ocupacion.consumos.map(c => {
                const isPromo = c.origen === 'Promocion';
                return '<div class="px-4 py-3 flex items-center justify-between">' +
                    '<div class="flex items-center space-x-3">' +
                    '<span class="text-xs ' + (isPromo ? 'text-green-400' : 'text-white') + '">' + this._escapeHtml(c.producto?.nombre || 'Producto') + '</span>' +
                    (isPromo
                        ? '<span class="text-[10px] bg-green-500/20 text-green-400 px-2 py-0.5 rounded-full">Promo</span>'
                        : '<div class="flex items-center space-x-1">' +
                          '<button onclick="dashboard.actualizarCantidadConsumo(' + c.id + ', ' + (c.cantidad - 1) + ')" class="w-6 h-6 rounded-md bg-white/10 text-white hover:bg-white/20 transition-all flex items-center justify-center text-xs font-bold">−</button>' +
                          '<span class="text-[#D4AF37] text-xs font-bold min-w-[16px] text-center">' + c.cantidad + '</span>' +
                          '<button onclick="dashboard.actualizarCantidadConsumo(' + c.id + ', ' + (c.cantidad + 1) + ')" class="w-6 h-6 rounded-md bg-white/10 text-white hover:bg-white/20 transition-all flex items-center justify-center text-xs font-bold">+</button>' +
                          '</div>') +
                    '</div>' +
                    '<div class="flex items-center space-x-2">' +
                    '<span class="text-[#D4AF37] text-sm font-mono">' + (c.total === 0 ? 'Gratis' : this.formatCurrency(c.total)) + '</span>' +
                    (!isPromo
                        ? '<button onclick="dashboard.eliminarConsumoItem(' + c.id + ')" class="text-red-400 hover:text-red-300 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>'
                        : '') +
                    '</div></div>';
            }).join('');
        } else {
            consumosSection.classList.add('d-none');
        }
    }

    _renderCobroTab() {
        const empty = document.getElementById('cobro-empty');
        const content = document.getElementById('cobro-content');
        if (!this.ocupacion) {
            empty.classList.remove('d-none');
            content.classList.add('d-none');
            return;
        }
        empty.classList.add('d-none');
        content.classList.remove('d-none');

        document.getElementById('cobro-valor-base').textContent = this.formatCurrency(this.ocupacion.precio_base);

        const consumosTotal = (this.ocupacion.consumos || []).filter(c => c.origen === 'Consumo').reduce((s, c) => s + c.total, 0);
        document.getElementById('cobro-consumos').textContent = this.formatCurrency(consumosTotal);

        const promoRow = document.getElementById('cobro-promo-row');
        if (this.ocupacion.promocion) {
            promoRow.classList.remove('d-none');
            promoRow.classList.add('d-flex', 'justify-content-between', 'align-items-center');
            document.getElementById('cobro-promo-titulo').textContent = this.ocupacion.promocion.titulo;
        } else {
            promoRow.classList.add('d-none');
            promoRow.classList.remove('d-flex', 'justify-content-between', 'align-items-center');
        }

        document.getElementById('cobro-total').textContent = this.formatCurrency(this.totalOcupacion());
        document.getElementById('cobro-pagado').textContent = this.formatCurrency(this.totalPagado());

        const pendiente = this.saldoPendiente();
        const saldoEl = document.getElementById('cobro-saldo');
        saldoEl.textContent = this.formatCurrency(pendiente);
        saldoEl.className = 'font-bold' + (pendiente > 0 ? ' text-red-400' : ' text-green-400');

        document.getElementById('pago-monto').max = pendiente;

        const pagosSection = document.getElementById('pagos-registered');
        const pagosList = document.getElementById('pagos-list');
        if (this.ocupacion.pagos && this.ocupacion.pagos.length > 0) {
            pagosSection.classList.remove('d-none');
            pagosList.innerHTML = this.ocupacion.pagos.map(p =>
                '<div class="px-4 py-3 flex items-center justify-between">' +
                '<span class="text-gray-300 text-sm">' + new Date(p.created_at).toLocaleString('es-CL') + '</span>' +
                '<div class="flex items-center space-x-3">' +
                '<span class="text-xs text-gray-400 uppercase">' + this._escapeHtml(p.forma_pago) + '</span>' +
                '<span class="text-green-400 font-bold">' + this.formatCurrency(p.monto) + '</span>' +
                '</div></div>'
            ).join('');
        } else {
            pagosSection.classList.add('d-none');
        }
    }

    _renderHistorialTab() {
        const empty = document.getElementById('historial-empty');
        const content = document.getElementById('historial-content');
        if (!this.ocupacion) {
            empty.classList.remove('d-none');
            content.classList.add('d-none');
            return;
        }
        empty.classList.add('d-none');
        content.classList.remove('d-none');

        const timeline = document.getElementById('historial-timeline');
        let html = '';

        if (this.ocupacion.historial_estados) {
            html += this.ocupacion.historial_estados.map(h => {
                const color = this.estadoColor(h.estado);
                const fin = h.fecha_fin ? ' → ' + new Date(h.fecha_fin).toLocaleString('es-CL') : '';
                return '<div class="flex items-start space-x-3">' +
                    '<div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 bg-' + color + '-400"></div>' +
                    '<div>' +
                    '<p class="text-white text-sm">Estado: ' + this._escapeHtml(h.estado) + '</p>' +
                    '<p class="text-gray-500 text-xs">' + new Date(h.fecha_inicio).toLocaleString('es-CL') + fin + '</p>' +
                    '</div></div>';
            }).join('');
        }

        (this.ocupacion.consumos || []).forEach(c => {
            html += '<div class="flex items-start space-x-3">' +
                '<div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 bg-orange-400"></div>' +
                '<div>' +
                '<p class="text-white text-sm">Consumo: ' + this._escapeHtml(c.producto?.nombre || '') + (c.cantidad > 1 ? ' x' + c.cantidad : '') + '</p>' +
                '<p class="text-gray-500 text-xs">' + new Date(c.created_at).toLocaleString('es-CL') + '</p>' +
                '</div></div>';
        });

        (this.ocupacion.pagos || []).forEach(p => {
            html += '<div class="flex items-start space-x-3">' +
                '<div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 bg-green-400"></div>' +
                '<div>' +
                '<p class="text-white text-sm">Pago: ' + this.formatCurrency(p.monto) + ' (' + this._escapeHtml(p.forma_pago) + ')</p>' +
                '<p class="text-gray-500 text-xs">' + new Date(p.created_at).toLocaleString('es-CL') + '</p>' +
                '</div></div>';
        });

        (this.ocupacion.observaciones || []).forEach(o => {
            html += '<div class="flex items-start space-x-3">' +
                '<div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 bg-blue-400"></div>' +
                '<div>' +
                '<p class="text-white text-sm">' + this._escapeHtml(o.contenido) + '</p>' +
                '<p class="text-gray-500 text-xs">' + new Date(o.created_at).toLocaleString('es-CL') + (o.user ? ' por ' + this._escapeHtml(o.user.name) : '') + '</p>' +
                '</div></div>';
        });

        timeline.innerHTML = html || '<p class="text-gray-500 text-sm">Sin registros.</p>';
    }

    _renderObservacionesTab() {
        const empty = document.getElementById('obs-empty');
        const content = document.getElementById('obs-content');
        if (!this.ocupacion) {
            empty.classList.remove('d-none');
            content.classList.add('d-none');
            return;
        }
        empty.classList.add('d-none');
        content.classList.remove('d-none');

        const obsList = document.getElementById('observaciones-list');
        const obsSection = document.getElementById('observaciones-registered');
        if (this.ocupacion.observaciones && this.ocupacion.observaciones.length > 0) {
            obsSection.classList.remove('d-none');
            obsList.innerHTML = this.ocupacion.observaciones.map(o =>
                '<div class="bg-white/5 rounded-xl p-4 border border-white/5">' +
                '<p class="text-gray-300 text-sm">' + this._escapeHtml(o.contenido) + '</p>' +
                '<p class="text-gray-500 text-xs mt-1">' + new Date(o.created_at).toLocaleString('es-CL') + (o.user ? ' - ' + this._escapeHtml(o.user.name) : '') + '</p>' +
                '</div>'
            ).join('');
        } else {
            obsSection.classList.add('d-none');
        }
    }

    _escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    estadoColor(estado) {
        const map = { Disponible: 'green', Ocupada: 'red', Reservada: 'yellow', Limpieza: 'blue', Mantenimiento: 'gray' };
        return map[estado] || 'gray';
    }

    totalOcupacion() {
        if (!this.ocupacion) return 0;
        const base = this.ocupacion.precio_base || 0;
        const consumos = (this.ocupacion.consumos || []).filter(c => c.origen === 'Consumo').reduce((s, c) => s + c.total, 0);
        return base + consumos;
    }

    totalPagado() {
        if (!this.ocupacion || !this.ocupacion.pagos) return 0;
        return this.ocupacion.pagos.reduce((s, p) => s + p.monto, 0);
    }

    saldoPendiente() {
        return this.totalOcupacion() - this.totalPagado();
    }

    formatCurrency(val) {
        return '$' + (val || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
}

window.dashboard = new DashboardManager();
</script>
@endpush
