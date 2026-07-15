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
        <a href="{{ route('admin.reservas.create') }}" class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5 hover:border-[#D4AF37]/30 transition-all duration-200 block">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Reservadas</p>
            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $reservadas }}</p>
        </a>
        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5">
            <p class="text-gray-400 text-sm uppercase tracking-wider">Limpieza</p>
            <p class="text-3xl font-bold text-blue-400 mt-2">{{ $limpieza }}</p>
        </div>
    </div>

    {{-- Room Grid --}}
    <div>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem;">
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

                {{-- Timer for any state except Disponible --}}
                @if($habitacion->ultimoEstado && $habitacion->estado !== 'Disponible')
                <div class="timer-{{ $habitacion->id }} text-[#D4AF37] text-xs font-mono"
                     data-inicio="{{ $habitacion->ultimoEstado->fecha_inicio->timestamp }}">
                    <span class="inline-block tiempo-valor">00:00:00</span>
                    
                </div>
                @endif

                @if($habitacion->estado === 'Ocupada' && $habitacion->ocupacionActiva && $habitacion->ocupacionActiva->tarifa)
                    <p class="text-gray-400 text-[10px] mt-1">{{ $habitacion->ocupacionActiva->tarifa->tipo_tiempo }}</p>
                @elseif($habitacion->estado === 'Reservada' && $habitacion->reservaActiva)
                    <p class="text-[#D4AF37] text-xs mt-2">{{ \Carbon\Carbon::parse($habitacion->reservaActiva->hora)->format('H:i') }} hrs</p>
                @endif
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
        tipoTiempo: '8h',
        tarifaInfo: null,
        personasAdicionales: 0,
        ocupacionVehiculo: 1,
        ocupacionPatente: '',
        fechaNacimiento: '',
        clienteNombres: '',
        clienteApellidos: '',
        clienteDocumento: '',
        rutValido: null,
        consumoSelectorOpen: false,
        categoriaFiltro: null,
        promocionesAplicables: [],

        async init() {
            this.iniciarTimers();
            try {
                const res = await fetch('/admin/dashboard/promociones');
                this.promociones = await res.json();
                const res2 = await fetch('/admin/dashboard/productos');
                this.productos = await res2.json();
            } catch(e) { console.error(e); }
        },

        async calcularTarifaPreview() {
            if (!this.habitacion) return;
            try {
                const res = await fetch('/admin/dashboard/calcular-tarifa?categoria=' + this.habitacion.categoria + '&tipo_tiempo=' + this.tipoTiempo);
                if (!res.ok) { this.tarifaInfo = null; return; }
                this.tarifaInfo = await res.json();
            } catch(e) { this.tarifaInfo = null; }
        },

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
        },

        async abrirModal(id) {
            this.habitacionId = id;
            this.loading = true;
            this.modalOpen = true;
            this.activeTab = 'estado';
            this.tipoTiempo = '8h';
            this.tarifaInfo = null;
            this.personasAdicionales = 0;
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
                }
                await this.calcularTarifaPreview();
                this.$nextTick(() => this.iniciarTimers());
            } catch(e) { console.error(e); }
            this.loading = false;
        },

        cerrarModal() {
            this.modalOpen = false;
            this.habitacion = null;
            this.ocupacion = null;
            this.tarifaInfo = null;
            location.reload();
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
                    this.activeTab = 'ocupacion';
                    Swal.fire({ icon: 'success', title: 'Ocupación iniciada (' + this.tipoTiempo + ')', timer: 1500, showConfirmButton: false });
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
                    Swal.fire({ icon: 'success', title: 'Promoción aplicada: ' + promocion.titulo, timer: 2000, showConfirmButton: false });
                }
            } catch(e) { console.error(e); }
        },

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
                    Swal.fire({ icon: 'success', title: 'Productos de promoción agregados', timer: 2000, showConfirmButton: false });
                }
            } catch(e) { console.error(e); }
        },

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
        },

        validarRutInput() {
            const rut = this.clienteDocumento;
            if (!rut || rut.length < 2) { this.rutValido = null; return; }
            this.rutValido = this.validarRutCompleto(rut);
        },

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
        },

        async registrarCliente() {
            const tipoDoc = this.$refs.clienteForm.querySelector('[name="tipo_documento"]').value;
            if (tipoDoc === 'RUT' && this.rutValido !== true) {
                Swal.fire({ icon: 'error', title: 'RUT inválido', text: 'Verifique el RUT ingresado', confirmButtonColor: '#D4AF37' });
                return;
            }
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
                    this.clienteDocumento = '';
                    this.rutValido = null;
                    form.reset();
                    await this.recargarOcupacion();
                    Swal.fire({ icon: 'success', title: 'Cliente registrado', timer: 1500, showConfirmButton: false });
                }
            } catch(e) { console.error(e); }
        },

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
        },

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
        },

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
            } catch(e) { console.error(e); }
            await this.recargarOcupacion();
        },

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
        },

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
                    Swal.fire({ icon: 'success', title: 'Pago registrado', timer: 1500, showConfirmButton: false });
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
            this.ocupacionVehiculo = this.ocupacion.vehiculo ? 1 : 0;
            this.ocupacionPatente = this.ocupacion.patente || '';
            this.promocionesAplicables = data.promociones_aplicables || [];
        },

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
