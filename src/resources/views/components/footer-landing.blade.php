<footer class="bg-[#0a0a0a] border-t border-white/5 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <img src="/img/logooscuro.png" alt="Logo" class="h-10 w-auto">
                    <span class="text-white font-bold text-lg">Los Gatitos</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">Hotel de lujo en Santiago. Disfrute de una experiencia única con nuestras suites y departamentos de primer nivel.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Navegación</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('landing.index') }}" class="text-gray-400 hover:text-[#D4AF37] text-sm transition-colors">Inicio</a></li>
                    <li><a href="{{ route('landing.habitaciones') }}" class="text-gray-400 hover:text-[#D4AF37] text-sm transition-colors">Habitaciones</a></li>
                    <li><a href="{{ route('landing.promociones') }}" class="text-gray-400 hover:text-[#D4AF37] text-sm transition-colors">Promociones</a></li>
                    <li><a href="{{ route('landing.reservar') }}" class="text-gray-400 hover:text-[#D4AF37] text-sm transition-colors">Reservar</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Horario</h4>
                <p class="text-gray-400 text-sm">Abierto 24 horas</p>
                <p class="text-gray-400 text-sm mt-2">Lunes a Domingo</p>
                <p class="text-[#D4AF37] text-sm mt-4">Check-in: cualquier hora</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Contacto</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li class="flex items-start space-x-2">
                        <svg class="w-4 h-4 mt-0.5 text-[#D4AF37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $config['direccion'] ?? 'Macul 4849, Santiago, Chile' }}</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#D4AF37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $config['email'] ?? '' }}" class="hover:text-[#D4AF37] transition-colors">{{ $config['email'] ?? 'motellosgatitos@gmail.com' }}</a>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#D4AF37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ $config['telefono'] ?? '' }}" class="hover:text-[#D4AF37] transition-colors">{{ $config['telefono'] ?? '+56 4 4358 7999' }}</a>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#D4AF37] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $config['whatsapp'] ?? '') }}" target="_blank" rel="noopener" class="hover:text-[#D4AF37] transition-colors">{{ $config['whatsapp'] ?? $config['telefono'] ?? '' }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/5 mt-10 pt-8 flex flex-col md:flex-row items-center justify-between">
            <p class="text-gray-500 text-xs">&copy; {{ date('Y') }} Motel Los Gatitos. Todos los derechos reservados.</p>
            <div class="flex space-x-4 mt-4 md:mt-0">
                <span class="text-[#D4AF37] text-xs">Diseño Premium</span>
            </div>
        </div>
    </div>
</footer>
