@extends('layouts.landing')

@section('title', 'Reservar - Motel Los Gatitos')

@section('content')
<section class="relative pt-32 pb-20 lg:pb-32 min-h-screen bg-black">
    <div class="absolute inset-0 bg-gradient-to-b from-[#D4AF37]/5 via-transparent to-black"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Reserva</span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Reserva tu Habitación</h1>
        </div>

        <div data-aos="fade-up" class="bg-white/5 backdrop-blur-xl rounded-3xl p-8 lg:p-12 border border-white/10">
            <form id="form-reserva" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">RUT <span class="text-[#D4AF37]">*</span></label>
                        <input type="text" id="rut" name="rut" placeholder="11.111.111-1" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                        <p id="errorRut" class="text-red-400 text-xs mt-1 hidden"></p>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" placeholder="+56 9 1234 5678" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                        <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Fecha <span class="text-[#D4AF37]">*</span></label>
                        <input type="date" id="fecha" name="fecha" min="" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Hora <span class="text-[#D4AF37]">*</span></label>
                        <input type="time" id="hora" name="hora" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                        <select id="categoria" name="categoria" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                            <option value="Suite" class="bg-gray-900">Suite</option>
                            <option value="Departamento" class="bg-gray-900">Departamento</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Personas <span class="text-[#D4AF37]">*</span></label>
                        <select id="personas" name="personas" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">Observaciones</label>
                    <textarea id="observaciones" name="observaciones" rows="3" placeholder="Alguna observación o requerimiento especial..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none resize-none"></textarea>
                </div>

                {{-- Resumen de Precios --}}
                <div id="resumen-precios" class="bg-white/5 rounded-2xl p-6 border border-white/5 hidden">
                    <h4 class="text-white font-semibold mb-4">Resumen de tu reserva</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Precio base</span>
                            <span id="precio-base" class="text-white">$0</span>
                        </div>
                        <div id="horas-adicionales-row" class="flex justify-between hidden">
                            <span class="text-gray-400">Horas adicionales</span>
                            <span id="precio-horas" class="text-white">+$0</span>
                        </div>
                        <div id="tercera-persona-row" class="flex justify-between hidden">
                            <span class="text-gray-400">Tercera persona</span>
                            <span id="precio-tercera" class="text-white">+$0</span>
                        </div>
                        <div class="border-t border-white/10 pt-2 flex justify-between">
                            <span class="text-[#D4AF37] font-bold">Total</span>
                            <span id="precio-total" class="text-[#D4AF37] font-bold text-lg">$0</span>
                        </div>
                    </div>
                </div>

                <button type="submit" id="btn-reservar" class="w-full bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-bold py-4 rounded-xl transition-all duration-300 text-lg shadow-lg shadow-[#D4AF37]/25 hover:shadow-[#D4AF37]/40 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="btn-texto">Reservar Ahora</span>
                </button>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
class ReservaForm {
    constructor() {
        this.rut = '';
        this.nombre = '';
        this.email = '';
        this.telefono = '';
        this.fecha = '';
        this.hora = '';
        this.categoria = 'Suite';
        this.personas = 2;
        this.observaciones = '';
        this.terceraPersona = false;
        this.precios = { precio_base: 0, horas_adicionales: 0, tercera_persona: 0, total: 0 };
        this.enviando = false;

        this.elRut = document.getElementById('rut');
        this.elNombre = document.getElementById('nombre');
        this.elEmail = document.getElementById('email');
        this.elTelefono = document.getElementById('telefono');
        this.elFecha = document.getElementById('fecha');
        this.elHora = document.getElementById('hora');
        this.elCategoria = document.getElementById('categoria');
        this.elPersonas = document.getElementById('personas');
        this.elObservaciones = document.getElementById('observaciones');
        this.elErrorRut = document.getElementById('errorRut');
        this.elResumen = document.getElementById('resumen-precios');
        this.elPrecioBase = document.getElementById('precio-base');
        this.elPrecioHoras = document.getElementById('precio-horas');
        this.elPrecioTercera = document.getElementById('precio-tercera');
        this.elPrecioTotal = document.getElementById('precio-total');
        this.elHorasRow = document.getElementById('horas-adicionales-row');
        this.elTerceraRow = document.getElementById('tercera-persona-row');
        this.elBtnTexto = document.getElementById('btn-texto');
        this.elBoton = document.getElementById('btn-reservar');

        this.elFecha.min = new Date().toISOString().split('T')[0];

        this.initPersonasOptions();
        this.bindEvents();
    }

    initPersonasOptions() {
        for (let i = 1; i <= 6; i++) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i;
            if (i === 2) opt.selected = true;
            this.elPersonas.appendChild(opt);
        }
    }

    bindEvents() {
        this.elRut.addEventListener('input', () => {
            this.rut = this.elRut.value;
            this.formatearRUT();
        });
        this.elNombre.addEventListener('input', () => this.nombre = this.elNombre.value);
        this.elEmail.addEventListener('input', () => this.email = this.elEmail.value);
        this.elTelefono.addEventListener('input', () => this.telefono = this.elTelefono.value);
        this.elFecha.addEventListener('change', () => {
            this.fecha = this.elFecha.value;
            this.calcularPrecio();
        });
        this.elHora.addEventListener('input', () => this.hora = this.elHora.value);
        this.elCategoria.addEventListener('change', () => {
            this.categoria = this.elCategoria.value;
            this.calcularPrecio();
        });
        this.elPersonas.addEventListener('change', () => {
            this.personas = parseInt(this.elPersonas.value);
            this.terceraPersona = this.personas >= 3;
            this.calcularPrecio();
        });
        this.elObservaciones.addEventListener('input', () => this.observaciones = this.elObservaciones.value);
        document.getElementById('form-reserva').addEventListener('submit', (e) => {
            e.preventDefault();
            this.enviarReserva();
        });
    }

    formatearRUT() {
        let valor = this.rut.replace(/[^kK\d]/g, '');
        if (valor.length > 1) {
            let cuerpo = valor.slice(0, -1);
            let dv = valor.slice(-1);
            cuerpo = cuerpo.replace(/\./g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            this.rut = cuerpo + '-' + dv.toUpperCase();
            this.elRut.value = this.rut;
        }
    }

    async calcularPrecio() {
        if (!this.fecha) return;
        try {
            const res = await fetch('{{ route('landing.calcular-precio') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    categoria: this.categoria,
                    fecha: this.fecha,
                    horas_adicionales: 0,
                    tercera_persona: this.terceraPersona,
                })
            });
            const data = await res.json();
            this.precios = data;
            this.actualizarResumen();
        } catch(e) { console.error(e); }
    }

    actualizarResumen() {
        if (this.precios.total > 0) {
            this.elResumen.classList.remove('hidden');
            this.elPrecioBase.textContent = '$' + this.formatPrecio(this.precios.precio_base);
            if (this.precios.horas_adicionales > 0) {
                this.elHorasRow.classList.remove('hidden');
                this.elPrecioHoras.textContent = '+$' + this.formatPrecio(this.precios.horas_adicionales);
            } else {
                this.elHorasRow.classList.add('hidden');
            }
            if (this.precios.tercera_persona > 0) {
                this.elTerceraRow.classList.remove('hidden');
                this.elPrecioTercera.textContent = '+$' + this.formatPrecio(this.precios.tercera_persona);
            } else {
                this.elTerceraRow.classList.add('hidden');
            }
            this.elPrecioTotal.textContent = '$' + this.formatPrecio(this.precios.total);
        } else {
            this.elResumen.classList.add('hidden');
        }
    }

    async enviarReserva() {
        this.elErrorRut.classList.add('hidden');
        if (!this.rut || !this.fecha || !this.hora) {
            Swal.fire('Error', 'Completa todos los campos obligatorios.', 'error');
            return;
        }
        this.enviando = true;
        this.elBoton.disabled = true;
        this.elBtnTexto.textContent = 'Procesando...';
        try {
            const res = await fetch('{{ route('landing.reservar.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    rut: this.rut,
                    nombre: this.nombre,
                    email: this.email,
                    telefono: this.telefono,
                    fecha: this.fecha,
                    hora: this.hora,
                    categoria: this.categoria,
                    personas: this.personas,
                    observaciones: this.observaciones,
                    tercera_persona: this.terceraPersona,
                })
            });
            const data = await res.json();
            if (data.error) {
                Swal.fire('Reserva Existente', data.mensaje, 'warning');
            } else {
                Swal.fire('¡Reserva Exitosa!', data.mensaje || 'Tu reserva ha sido creada.', 'success');
                this.rut = ''; this.nombre = ''; this.email = ''; this.telefono = '';
                this.fecha = ''; this.hora = ''; this.personas = 2; this.observaciones = '';
                this.precios = { precio_base: 0, horas_adicionales: 0, tercera_persona: 0, total: 0 };
                this.elRut.value = ''; this.elNombre.value = ''; this.elEmail.value = '';
                this.elTelefono.value = ''; this.elFecha.value = ''; this.elHora.value = '';
                this.elPersonas.value = 2; this.elObservaciones.value = '';
                this.elResumen.classList.add('hidden');
            }
        } catch(e) {
            Swal.fire('Error', 'Ocurrió un error al procesar tu reserva.', 'error');
        }
        this.enviando = false;
        this.elBoton.disabled = false;
        this.elBtnTexto.textContent = 'Reservar Ahora';
    }

    formatPrecio(val) {
        return (val || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
}

document.addEventListener('DOMContentLoaded', () => { new ReservaForm(); });
</script>
@endpush
@endsection
