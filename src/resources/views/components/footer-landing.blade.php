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
                        <span>Macul 4849, Santiago, Chile</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#D4AF37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>motellosgatitos@gmail.com</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#D4AF37] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>+56 9 1234 5678</span>
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
