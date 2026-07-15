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

        <div data-aos="fade-up" x-data="reservaForm()" class="bg-white/5 backdrop-blur-xl rounded-3xl p-8 lg:p-12 border border-white/10">
            <form @submit.prevent="enviarReserva" class="space-y-6" id="form-reserva">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">RUT <span class="text-[#D4AF37]">*</span></label>
                        <input type="text" x-model="rut" @input="formatearRUT" placeholder="11.111.111-1" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                        <p x-show="errorRut" x-text="errorRut" class="text-red-400 text-xs mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Teléfono</label>
                        <input type="text" x-model="telefono" placeholder="+56 9 1234 5678" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Nombre</label>
                        <input type="text" x-model="nombre" placeholder="Tu nombre" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                        <input type="email" x-model="email" placeholder="correo@ejemplo.com" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Fecha <span class="text-[#D4AF37]">*</span></label>
                        <input type="date" x-model="fecha" x-on:change="calcularPrecio" :min="hoy" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Hora <span class="text-[#D4AF37]">*</span></label>
                        <input type="time" x-model="hora" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Categoría</label>
                        <select x-model="categoria" x-on:change="calcularPrecio" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                            <option value="Suite" class="bg-gray-900">Suite</option>
                            <option value="Departamento" class="bg-gray-900">Departamento</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Personas <span class="text-[#D4AF37]">*</span></label>
                        <select x-model="personas" x-on:change="terceraPersona = personas >= 3; calcularPrecio()" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none">
                            <template x-for="i in 6" :key="i">
                                <option :value="i" x-text="i" :selected="i === 2" class="bg-gray-900"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">Observaciones</label>
                    <textarea x-model="observaciones" rows="3" placeholder="Alguna observación o requerimiento especial..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] transition-all outline-none resize-none"></textarea>
                </div>

                {{-- Resumen de Precios --}}
                <div x-show="precios.total > 0" x-transition class="bg-white/5 rounded-2xl p-6 border border-white/5">
                    <h4 class="text-white font-semibold mb-4">Resumen de tu reserva</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Precio base</span>
                            <span class="text-white" x-text="'$' + formatPrecio(precios.precio_base)"></span>
                        </div>
                        <div class="flex justify-between" x-show="precios.horas_adicionales > 0">
                            <span class="text-gray-400">Horas adicionales</span>
                            <span class="text-white" x-text="'+$' + formatPrecio(precios.horas_adicionales)"></span>
                        </div>
                        <div class="flex justify-between" x-show="precios.tercera_persona > 0">
                            <span class="text-gray-400">Tercera persona</span>
                            <span class="text-white" x-text="'+$' + formatPrecio(precios.tercera_persona)"></span>
                        </div>
                        <div class="border-t border-white/10 pt-2 flex justify-between">
                            <span class="text-[#D4AF37] font-bold">Total</span>
                            <span class="text-[#D4AF37] font-bold text-lg" x-text="'$' + formatPrecio(precios.total)"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" :disabled="enviando" class="w-full bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-bold py-4 rounded-xl transition-all duration-300 text-lg shadow-lg shadow-[#D4AF37]/25 hover:shadow-[#D4AF37]/40 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!enviando">Reservar Ahora</span>
                    <span x-show="enviando">Procesando...</span>
                </button>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
function reservaForm() {
    return {
        rut: '',
        nombre: '',
        email: '',
        telefono: '',
        fecha: '',
        hora: '',
        categoria: 'Suite',
        personas: 2,
        observaciones: '',
        terceraPersona: false,
        precios: { precio_base: 0, horas_adicionales: 0, tercera_persona: 0, total: 0 },
        enviando: false,
        errorRut: '',
        hoy: new Date().toISOString().split('T')[0],

        formatearRUT() {
            let valor = this.rut.replace(/[^kK\d]/g, '');
            if (valor.length > 1) {
                let cuerpo = valor.slice(0, -1);
                let dv = valor.slice(-1);
                cuerpo = cuerpo.replace(/\./g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                this.rut = cuerpo + '-' + dv.toUpperCase();
            }
        },

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
            } catch(e) { console.error(e); }
        },

        async enviarReserva() {
            this.errorRut = '';
            if (!this.rut || !this.fecha || !this.hora) {
                Swal.fire('Error', 'Completa todos los campos obligatorios.', 'error');
                return;
            }
            this.enviando = true;
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
                }
            } catch(e) {
                Swal.fire('Error', 'Ocurrió un error al procesar tu reserva.', 'error');
            }
            this.enviando = false;
        },

        formatPrecio(val) {
            return (val || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }
}
</script>
@endpush
@endsection
