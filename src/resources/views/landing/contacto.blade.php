@extends('layouts.landing')

@section('title', 'Contacto - Motel Los Gatitos')
@section('meta_description', 'Contacta con Motel Los Gatitos en Av. Macul 4849, Santiago. Atención y check-in las 24 horas, estacionamiento privado y reservas disponibles de domingo a jueves.')
@section('og_image', url('/img/contacto.jpg'))

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
                            <a href="mailto:{{ $config['email'] ?? '' }}" class="text-gray-400 text-sm hover:text-[#D4AF37]">{{ $config['email'] ?? 'motellosgatitos@gmail.com' }}</a>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-[#D4AF37]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Teléfono</p>
                            <a href="tel:{{ $config['telefono'] ?? '' }}" class="text-gray-400 text-sm hover:text-[#D4AF37]">{{ $config['telefono'] ?? '+56 9 1234 5678' }}</a>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-[#D4AF37]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#D4AF37]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">WhatsApp</p>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $config['whatsapp'] ?? '') }}" target="_blank" rel="noopener" class="text-gray-400 text-sm hover:text-[#D4AF37]">{{ $config['whatsapp'] ?? $config['telefono'] ?? '' }}</a>
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
