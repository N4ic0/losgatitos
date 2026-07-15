@extends('layouts.landing')

@section('title', 'Motel Los Gatitos - Hotel de Lujo en Santiago')

@section('content')
{{-- HERO SECTION WITH VIDEO --}}
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <video class="absolute inset-0 w-full h-full object-cover" autoplay loop muted playsinline>
        <source src="/img/inicio.mp4" type="video/mp4">
    </video>
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black"></div>
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        
        <h1 data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-4 tracking-tight">
            Motel <span class="text-[#D4AF37]">Los Gatitos</span>
        </h1>
        <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" class="text-gray-300 text-lg sm:text-xl md:text-2xl mb-10 max-w-2xl mx-auto font-light">
            Tu momento perfecto te espera. Disfruta de una experiencia única en un ambiente de lujo y privacidad.
        </p>
        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600" class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('landing.reservar') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-bold px-10 py-4 rounded-full transition-all duration-300 text-lg shadow-lg shadow-[#D4AF37]/30 hover:shadow-[#D4AF37]/50 hover:scale-105">Reservar Ahora</a>
            <a href="{{ route('landing.habitaciones') }}" class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white font-semibold px-10 py-4 rounded-full transition-all duration-300 text-lg border border-white/20">Ver Habitaciones</a>
            <a href="{{ route('landing.promociones') }}" class="text-[#D4AF37] hover:text-white font-semibold px-6 py-4 transition-all duration-300 text-lg">Promociones →</a>
        </div>

        {{-- AVAILABILITY --}}
        @php
            $suiteCount = $habitaciones->where('categoria', 'Suite')->count();
            $deptoCount = $habitaciones->where('categoria', 'Departamento')->count();
            $totalDisponibles = $suiteCount + $deptoCount;
        @endphp
        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="800" class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-6">
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl px-8 py-5 border border-white/10 min-w-[200px] text-center">
                <p class="text-[#D4AF37] text-3xl font-bold">{{ $suiteCount }}</p>
                <p class="text-gray-400 text-sm mt-1">Suite Disponible{{ $suiteCount !== 1 ? 's' : '' }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl px-8 py-5 border border-white/10 min-w-[200px] text-center">
                <p class="text-[#D4AF37] text-3xl font-bold">{{ $deptoCount }}</p>
                <p class="text-gray-400 text-sm mt-1">Departamento Disponible{{ $deptoCount !== 1 ? 's' : '' }}</p>
            </div>
        </div>
    </div>
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
    </div>
</section>

{{-- HABITACIONES SECTION --}}
<section id="habitaciones" class="relative py-20 lg:py-32 overflow-hidden">
    <div data-gsap-bg class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/img/habitaciones.jpeg');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Nuestras Habitaciones</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Elige tu espacio perfecto</h2>
            <p class="text-gray-400 mt-4 max-w-2xl mx-auto">Descubre nuestras suites y departamentos diseñados para brindarte la mejor experiencia.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div data-aos="fade-right" class="group bg-white/5 backdrop-blur-xl rounded-3xl p-8 border border-white/10 hover:border-[#D4AF37]/30 transition-all duration-500 hover:shadow-2xl hover:shadow-[#D4AF37]/5">
                <div class="w-16 h-16 bg-[#D4AF37]/10 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Suite</h3>
                <p class="text-gray-400 mb-6">Ambiente íntimo y acogedor. Perfecta para parejas que buscan una experiencia inolvidable.</p>
                <ul class="space-y-2 text-sm text-gray-400 mb-6">
                    <li class="flex items-center"><svg class="w-4 h-4 text-[#D4AF37] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> Cama King Size</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-[#D4AF37] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> Jacuzzi</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-[#D4AF37] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> TV LED 50"</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-[#D4AF37] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> Aire Acondicionado</li>
                </ul>
                <p class="text-2xl font-bold text-[#D4AF37]">Desde $44.200</p>
                <p class="text-gray-500 text-sm mt-1">8 horas</p>
            </div>
            <div data-aos="fade-left" class="group bg-white/5 backdrop-blur-xl rounded-3xl p-8 border border-white/10 hover:border-[#D4AF37]/30 transition-all duration-500 hover:shadow-2xl hover:shadow-[#D4AF37]/5">
                <div class="w-16 h-16 bg-[#D4AF37]/10 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Departamento</h3>
                <p class="text-gray-400 mb-6">Más espacio y comodidad. Ideal para quienes buscan una estadía prolongada con todas las facilidades.</p>
                <ul class="space-y-2 text-sm text-gray-400 mb-6">
                    <li class="flex items-center"><svg class="w-4 h-4 text-[#D4AF37] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> Sala de Estar</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-[#D4AF37] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> Hidromasaje</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-[#D4AF37] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> Mini Bar</li>
                    <li class="flex items-center"><svg class="w-4 h-4 text-[#D4AF37] mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg> Wifi Premium</li>
                </ul>
                <p class="text-2xl font-bold text-[#D4AF37]">Desde $49.200</p>
                <p class="text-gray-500 text-sm mt-1">8 horas</p>
            </div>
        </div>
    </div>
</section>

{{-- SERVICIOS SECTION --}}
<section class="relative py-20 lg:py-32 overflow-hidden">
    <div data-gsap-bg class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/img/servicios.jpeg');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Servicios</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Todo lo que necesitas</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $servicios = [
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => '24 Horas', 'desc' => 'Atención y check-in las 24 horas del día'],
                    ['icon' => 'M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3', 'title' => 'Estacionamiento', 'desc' => 'Estacionamiento privado y seguro'],
                    ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'TV & Streaming', 'desc' => 'TV LED con Netflix y Disney+'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'WiFi Premium', 'desc' => 'Internet de alta velocidad gratuito'],
                ];
            @endphp
            @foreach ($servicios as $servicio)
            <div data-aos="fade-up" class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/5 hover:border-[#D4AF37]/20 transition-all duration-300 group">
                <div class="w-12 h-12 bg-[#D4AF37]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#D4AF37]/20 transition-colors">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $servicio['icon'] }}"/></svg>
                </div>
                <h3 class="text-white font-semibold mb-2">{{ $servicio['title'] }}</h3>
                <p class="text-gray-400 text-sm">{{ $servicio['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TARIFAS SECTION --}}
<section class="relative py-20 lg:py-32 overflow-hidden">
    <div data-gsap-bg class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/img/precios.jpeg');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Tarifas</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Precios transparentes</h2>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div data-aos="fade-right" class="bg-white/5 backdrop-blur-xl rounded-3xl p-8 border border-white/10">
                <h3 class="text-2xl font-bold text-white mb-2">Suite</h3>
                <p class="text-gray-400 mb-6">8 horas</p>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-white/5">
                        <span class="text-gray-300">Domingo a Jueves</span>
                        <span class="text-white font-bold">$44.200</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-white/5">
                        <span class="text-gray-300">Viernes</span>
                        <span class="text-white font-bold">$47.200</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-white/5">
                        <span class="text-gray-300">Sábado</span>
                        <span class="text-white font-bold">$47.200</span>
                    </div>
                </div>
            </div>
            <div data-aos="fade-left" class="bg-white/5 backdrop-blur-xl rounded-3xl p-8 border border-white/10">
                <h3 class="text-2xl font-bold text-white mb-2">Departamento</h3>
                <p class="text-gray-400 mb-6">8 horas</p>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-white/5">
                        <span class="text-gray-300">Domingo a Jueves</span>
                        <span class="text-white font-bold">$49.200</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-white/5">
                        <span class="text-gray-300">Viernes</span>
                        <span class="text-white font-bold">$53.200</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-white/5">
                        <span class="text-gray-300">Sábado</span>
                        <span class="text-white font-bold">$53.200</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-10" data-aos="fade-up">
            <p class="text-gray-400 text-sm">Hora adicional: $5.500 D-J | $6.000 V-S</p>
            <p class="text-gray-500 text-xs mt-1">* Tercera persona tiene un cargo adicional del 50% de la tarifa base</p>
        </div>
        </div>
    </div>
</section>

{{-- PROMOCIONES SECTION --}}
<section class="relative py-20 lg:py-32 overflow-hidden">
    <div data-gsap-bg class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/img/ofertas.jpeg');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Promociones</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Ofertas especiales</h2>
        </div>
        @if(isset($promocionActiva) && $promocionActiva)
        <div data-aos="zoom-in" class="max-w-3xl mx-auto bg-gradient-to-br from-[#D4AF37]/10 to-black rounded-3xl p-8 lg:p-12 border border-[#D4AF37]/20">
            <div class="flex items-center space-x-2 mb-4">
                <span class="bg-[#D4AF37] text-black text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Activa</span>
            </div>
            <h3 class="text-2xl lg:text-3xl font-bold text-white mb-4">{{ $promocionActiva->titulo }}</h3>
            <p class="text-gray-300 mb-6 leading-relaxed">{{ $promocionActiva->descripcion }}</p>
            <p class="text-[#D4AF37] font-semibold">Válida hasta: {{ $promocionActiva->fecha_fin->format('d/m/Y') }}</p>
        </div>
        @else
        <div class="text-center">
            <p class="text-gray-400">No hay promociones activas en este momento. Vuelve pronto.</p>
        </div>
        @endif
    </div>
</section>

{{-- MAPA SECTION --}}
<section class="py-20 lg:py-32 bg-gradient-to-b from-black to-[#0a0a0a]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Ubicación</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Encuéntranos</h2>
            <p class="text-gray-400 mt-4">Av. Macul 4849, Santiago, Chile</p>
        </div>
        <div data-aos="fade-up" class="rounded-3xl overflow-hidden border border-white/10 h-[400px]">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3327.1404011832533!2d-70.60026012574865!3d-33.49772407337079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9662d03e1f3b2cdd%3A0xef9e7076e7a11612!2sAv.%20Macul%204849%2C%207821081%20Macul%2C%20Regi%C3%B3n%20Metropolitana!5e0!3m2!1ses!2scl!4v1782945633381!5m2!1ses!2scl" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

{{-- POPUP PROMOCIÓN --}}
@if(isset($promocionActiva) && $promocionActiva)
<div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-gradient-to-br from-[#1a1a2e] to-black rounded-3xl p-8 lg:p-12 border border-[#D4AF37]/20 shadow-2xl shadow-[#D4AF37]/10">
            <button type="button" class="btn-close btn-close-white absolute top-4 right-4" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center">
                <div class="w-16 h-16 bg-[#D4AF37]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </div>
                <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-xs font-semibold">Promoción Especial</span>
                <h3 class="text-2xl font-bold text-white mt-3 mb-4">{{ $promocionActiva->titulo }}</h3>
                <p class="text-gray-300 text-sm leading-relaxed mb-6">{{ $promocionActiva->descripcion }}</p>
                <a href="{{ route('landing.reservar') }}" class="inline-block bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-bold px-8 py-3 rounded-full transition-all duration-300">Aprovechar Oferta</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var promoId = {{ $promocionActiva->id }};
    var seen = localStorage.getItem('promo_seen_' + promoId);
    if (!seen) {
        var modal = new bootstrap.Modal(document.getElementById('promoModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
        document.getElementById('promoModal').addEventListener('hidden.bs.modal', function() {
            localStorage.setItem('promo_seen_' + promoId, '1');
        });
    }
});
</script>
@endpush
@endif
@endsection
