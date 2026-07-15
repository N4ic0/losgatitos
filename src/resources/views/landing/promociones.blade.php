@extends('layouts.landing')

@section('title', 'Promociones - Motel Los Gatitos')

@section('content')
<section class="relative pt-32 pb-20 lg:pb-32 min-h-screen overflow-hidden">
    <div data-gsap-bg class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/img/ofertas.jpeg');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Promociones</span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Ofertas Especiales</h1>
            <p class="text-gray-400 mt-4 max-w-2xl mx-auto">Aprovecha nuestras promociones y vive una experiencia única.</p>
        </div>

        @forelse($promociones as $promocion)
        <div data-aos="fade-up" class="max-w-4xl mx-auto mb-6 bg-white/5 backdrop-blur-xl rounded-3xl p-8 lg:p-12 border border-white/10 hover:border-[#D4AF37]/30 transition-all duration-500">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div class="flex-1">
                    <span class="bg-[#D4AF37] text-black text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Activa</span>
                    <h3 class="text-2xl lg:text-3xl font-bold text-white mt-4 mb-4">{{ $promocion->titulo }}</h3>
                    <p class="text-gray-300 leading-relaxed">{{ $promocion->descripcion }}</p>
                    <p class="text-[#D4AF37] mt-4 text-sm">Válida: {{ $promocion->fecha_inicio->format('d/m/Y') }} - {{ $promocion->fecha_fin->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('landing.reservar') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-bold px-8 py-3 rounded-full transition-all duration-300 whitespace-nowrap">Reservar</a>
            </div>
        </div>
        @empty
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            <p class="text-gray-400 text-lg">No hay promociones activas en este momento.</p>
            <p class="text-gray-600 text-sm mt-2">Vuelve pronto para descubrir nuevas ofertas.</p>
        </div>
        @endforelse
    </div>
    </div>
</section>
@endsection
