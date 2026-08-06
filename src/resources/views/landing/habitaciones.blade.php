@extends('layouts.landing')

@section('title', 'Habitaciones - Motel Los Gatitos')
@section('meta_description', 'Conoce las suites y departamentos de Motel Los Gatitos: cama King Size, TV Smart 50", aire acondicionado y WiFi premium. Estacionamiento privado incluido, atención 24 horas.')
@section('og_image', url('/img/habitaciones.jpeg'))

@section('content')
<section class="relative pt-32 pb-20 lg:pb-32 min-h-screen overflow-hidden">
    <div data-gsap-bg class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/img/habitaciones.jpeg');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Nuestras Habitaciones</span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Encuentra tu espacio ideal</h1>
            <p class="text-gray-400 mt-4 max-w-2xl mx-auto">Contamos con suites y departamentos diseñados para brindarte la máxima comodidad y privacidad.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            @php
                $categorias = [
                    [
                        'nombre' => 'Suite',
                        'descripcion' => 'Ambiente íntimo y acogedor con acabados de lujo. Perfecta para parejas.',
                        'precio' => '$44.200',
                        'img' => null,
                        'amenities' => ['Cama King Size', 'Jacuzzi', 'TV LED 50"', 'Aire Acondicionado', 'Mini Bar', 'Wifi Premium']
                    ],
                    [
                        'nombre' => 'Departamento',
                        'descripcion' => 'Mayor espacio y comodidad. Ideal para estadías prolongadas.',
                        'precio' => '$49.200',
                        'img' => null,
                        'amenities' => ['Sala de Estar', 'Hidromasaje', 'TV LED 65"', 'Cocina Equipada', 'Terraza', 'Wifi Premium']
                    ]
                ];
            @endphp
            @foreach($categorias as $cat)
            <div data-aos="fade-up" class="bg-white/5 backdrop-blur-xl rounded-2xl sm:rounded-3xl overflow-hidden border border-white/10 group hover:border-[#D4AF37]/30 transition-all duration-500">
                <div class="h-36 sm:h-64 bg-gradient-to-br from-[#1a1a2e] to-black flex items-center justify-center">
                    <span class="text-[#D4AF37]/20 text-5xl sm:text-8xl font-bold">{{ $cat['nombre'][0] }}</span>
                </div>
                <div class="p-4 sm:p-8">
                    <h2 class="text-base sm:text-2xl font-bold text-white mb-2 sm:mb-3">{{ $cat['nombre'] }}</h2>
                    <p class="text-gray-400 mb-4 sm:mb-6 text-xs sm:text-base">{{ $cat['descripcion'] }}</p>
                    <div class="grid grid-cols-2 gap-1 sm:gap-3 mb-4 sm:mb-6">
                        @foreach($cat['amenities'] as $amenity)
                        <div class="flex items-center text-xs sm:text-sm text-gray-300">
                            <i class="fas fa-check text-[#D4AF37] text-[9px] sm:text-xs mr-1.5 sm:mr-2"></i>
                            {{ $amenity }}
                        </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base sm:text-2xl font-bold text-[#D4AF37]">Desde {{ $cat['precio'] }}</p>
                            <p class="text-gray-500 text-xs sm:text-sm">8 horas</p>
                        </div>
                        {{-- <a href="{{ route('landing.reservar') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-full transition-all duration-300 text-xs sm:text-sm">Reservar</a> --}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
