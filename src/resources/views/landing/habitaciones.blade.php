@extends('layouts.landing')

@section('title', 'Habitaciones - Motel Los Gatitos')

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
            <div data-aos="fade-up" class="bg-white/5 backdrop-blur-xl rounded-3xl overflow-hidden border border-white/10 group hover:border-[#D4AF37]/30 transition-all duration-500">
                <div class="h-64 bg-gradient-to-br from-[#1a1a2e] to-black flex items-center justify-center">
                    <span class="text-[#D4AF37]/20 text-8xl font-bold">{{ $cat['nombre'][0] }}</span>
                </div>
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-white mb-3">{{ $cat['nombre'] }}</h2>
                    <p class="text-gray-400 mb-6">{{ $cat['descripcion'] }}</p>
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        @foreach($cat['amenities'] as $amenity)
                        <div class="flex items-center text-sm text-gray-300">
                            <svg class="w-4 h-4 text-[#D4AF37] mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            {{ $amenity }}
                        </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-2xl font-bold text-[#D4AF37]">Desde {{ $cat['precio'] }}</p>
                            <p class="text-gray-500 text-sm">8 horas</p>
                        </div>
                        <a href="{{ route('landing.reservar') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-3 rounded-full transition-all duration-300 text-sm">Reservar</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
