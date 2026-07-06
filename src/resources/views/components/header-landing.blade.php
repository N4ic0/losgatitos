<header x-data="{ mobileOpen: false, scrolled: false }" 
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 50)"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="scrolled ? 'bg-black/90 backdrop-blur-md shadow-lg shadow-black/50' : 'bg-transparent'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <a href="{{ route('landing.index') }}" class="flex items-center space-x-3">
                <img src="/img/logooscuro.png" alt="Motel Los Gatitos" class="h-12 w-auto lg:h-12">
            </a>

            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('landing.index') }}" class="text-gray-300 hover:text-[#D4AF37] transition-colors duration-300 text-sm uppercase tracking-wider font-medium">Inicio</a>
                <a href="{{ route('landing.habitaciones') }}" class="text-gray-300 hover:text-[#D4AF37] transition-colors duration-300 text-sm uppercase tracking-wider font-medium">Habitaciones</a>
                <a href="{{ route('landing.promociones') }}" class="text-gray-300 hover:text-[#D4AF37] transition-colors duration-300 text-sm uppercase tracking-wider font-medium">Promociones</a>
                <a href="{{ route('landing.contacto') }}" class="text-gray-300 hover:text-[#D4AF37] transition-colors duration-300 text-sm uppercase tracking-wider font-medium">Contacto</a>
                <a href="{{ route('landing.reservar') }}" class="bg-[#D4AF37] hover:bg-[#C49A2C] text-black font-semibold px-6 py-2.5 rounded-full transition-all duration-300 text-sm uppercase tracking-wider shadow-lg shadow-[#D4AF37]/25 hover:shadow-[#D4AF37]/40">Reservar</a>
            </nav>

            <button @click="mobileOpen = !mobileOpen" class="lg:hidden text-white p-2">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden bg-black/95 backdrop-blur-md border-t border-white/10">
        <div class="px-4 py-6 space-y-4">
            <a href="{{ route('landing.index') }}" @click="mobileOpen = false" class="block text-gray-300 hover:text-[#D4AF37] py-2 uppercase text-sm tracking-wider">Inicio</a>
            <a href="{{ route('landing.habitaciones') }}" @click="mobileOpen = false" class="block text-gray-300 hover:text-[#D4AF37] py-2 uppercase text-sm tracking-wider">Habitaciones</a>
            <a href="{{ route('landing.promociones') }}" @click="mobileOpen = false" class="block text-gray-300 hover:text-[#D4AF37] py-2 uppercase text-sm tracking-wider">Promociones</a>
            <a href="{{ route('landing.contacto') }}" @click="mobileOpen = false" class="block text-gray-300 hover:text-[#D4AF37] py-2 uppercase text-sm tracking-wider">Contacto</a>
            <a href="{{ route('landing.reservar') }}" @click="mobileOpen = false" class="block text-center bg-[#D4AF37] text-black font-semibold px-6 py-3 rounded-full uppercase text-sm tracking-wider">Reservar</a>
        </div>
    </div>
</header>
