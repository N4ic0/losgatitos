import Alpine from 'alpinejs';
import Swiper from 'swiper';
import AOS from 'aos';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Swal from 'sweetalert2';

const DarkSwal = Swal.mixin({
    background: '#1a1a2e',
    color: '#e5e7eb',
    confirmButtonColor: '#D4AF37',
    cancelButtonColor: '#6b7280',
    iconColor: '#D4AF37',
    customClass: {
        popup: 'rounded-2xl border border-white/10 shadow-2xl',
        title: 'text-white font-bold text-lg',
        htmlContainer: 'text-gray-300',
        confirmButton: 'font-semibold px-6 py-3 rounded-xl text-sm',
        cancelButton: 'font-semibold px-6 py-3 rounded-xl text-sm',
        icon: 'border-0',
        timerProgressBar: 'bg-gold',
    },
});
window.Swal = DarkSwal;

window.Alpine = Alpine;
Alpine.start();

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        once: true,
        offset: 100,
    });

    gsap.utils.toArray('[data-gsap]').forEach(el => {
        const animation = el.dataset.gsap;
        gsap.from(el, {
            scrollTrigger: {
                trigger: el,
                start: 'top 85%',
                toggleActions: 'play none none reverse',
            },
            y: 50,
            opacity: 0,
            duration: 0.8,
            ease: 'power3.out',
        });
    });

    gsap.utils.toArray('[data-gsap-bg]').forEach(el => {
        gsap.fromTo(el,
            { opacity: 0 },
            {
                scrollTrigger: {
                    trigger: el.parentElement,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: 1,
                },
                opacity: 1,
                ease: 'none',
            }
        );
    });
});

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'aos/dist/aos.css';
