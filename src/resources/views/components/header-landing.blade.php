<header id="site-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent">
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

            <button class="lg:hidden text-white p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg id="close-icon" class="w-6 h-6 d-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div class="offcanvas offcanvas-end lg:hidden bg-black/95" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header border-b border-white/10">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-6">
            <div class="space-y-4">
                <a href="{{ route('landing.index') }}" class="block text-gray-300 hover:text-[#D4AF37] py-2 uppercase text-sm tracking-wider" data-bs-dismiss="offcanvas">Inicio</a>
                <a href="{{ route('landing.habitaciones') }}" class="block text-gray-300 hover:text-[#D4AF37] py-2 uppercase text-sm tracking-wider" data-bs-dismiss="offcanvas">Habitaciones</a>
                <a href="{{ route('landing.promociones') }}" class="block text-gray-300 hover:text-[#D4AF37] py-2 uppercase text-sm tracking-wider" data-bs-dismiss="offcanvas">Promociones</a>
                <a href="{{ route('landing.contacto') }}" class="block text-gray-300 hover:text-[#D4AF37] py-2 uppercase text-sm tracking-wider" data-bs-dismiss="offcanvas">Contacto</a>
                <a href="{{ route('landing.reservar') }}" class="block text-center bg-[#D4AF37] text-black font-semibold px-6 py-3 rounded-full uppercase text-sm tracking-wider" data-bs-dismiss="offcanvas">Reservar</a>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var header = document.getElementById('site-header');

    function updateScroll() {
        if (window.scrollY > 50) {
            header.classList.add('bg-black/90', 'backdrop-blur-md', 'shadow-lg', 'shadow-black/50');
            header.classList.remove('bg-transparent');
        } else {
            header.classList.remove('bg-black/90', 'backdrop-blur-md', 'shadow-lg', 'shadow-black/50');
            header.classList.add('bg-transparent');
        }
    }

    window.addEventListener('scroll', updateScroll);
    updateScroll();

    var mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu) {
        var hamburger = document.getElementById('hamburger-icon');
        var closeIcon = document.getElementById('close-icon');

        mobileMenu.addEventListener('show.bs.offcanvas', function () {
            hamburger.classList.add('d-none');
            closeIcon.classList.remove('d-none');
        });

        mobileMenu.addEventListener('hide.bs.offcanvas', function () {
            hamburger.classList.remove('d-none');
            closeIcon.classList.add('d-none');
        });
    }
});
</script>
