@extends('layouts.landing')

@section('title', 'Contacto - Motel Los Gatitos')

@section('content')
<section class="relative pt-32 pb-20 lg:pb-32 min-h-screen overflow-hidden">
    <div data-gsap-bg class="absolute inset-0 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/img/contacto.jpg');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/70"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-[#D4AF37] uppercase tracking-[0.2em] text-sm font-semibold">Contacto</span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mt-4">Estamos aquí para ti</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10" data-aos="fade-up">
            <div class="bg-white/5 backdrop-blur-xl rounded-3xl p-8 border border-white/10">
                <h3 class="text-xl font-bold text-white mb-6">Información de Contacto</h3>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-[#D4AF37]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Dirección</p>
                            <p class="text-gray-400 text-sm">{{ $config['direccion'] ?? 'Av. Macul 4849, Santiago, Chile' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-[#D4AF37]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Email</p>
                            <p class="text-gray-400 text-sm">{{ $config['email'] ?? 'motellosgatitos@gmail.com' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-[#D4AF37]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Teléfono</p>
                            <p class="text-gray-400 text-sm">{{ $config['telefono'] ?? '+56 9 1234 5678' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-[#D4AF37]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Horario de Atención</p>
                            <p class="text-gray-400 text-sm">{{ $config['horario_atencion'] ?? 'Lunes a Domingo 24 horas' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white/5 backdrop-blur-xl rounded-3xl overflow-hidden border border-white/10 h-[500px]">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3327.1404011832533!2d-70.60026012574865!3d-33.49772407337079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9662d03e1f3b2cdd%3A0xef9e7076e7a11612!2sAv.%20Macul%204849%2C%207821081%20Macul%2C%20Regi%C3%B3n%20Metropolitana!5e0!3m2!1ses!2scl!4v1782945633381!5m2!1ses!2scl" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    
    </div>
    </div>
</section>
@endsection
