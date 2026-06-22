<script>

    (function () {
        const carousel = document.getElementById('memberCarousel');
        const track = document.getElementById('memberTrack');
        const dotsWrap = document.getElementById('memberDots');

        // Guard: kalau markup carousel tidak ada di halaman, hentikan script
        if (!carousel || !track || !dotsWrap) return;

        const cards = Array.from(track.children);
        if (cards.length === 0) return;

        const AUTOPLAY_DELAY = 0; // ms, set 0 untuk mematikan autoplay

        // Jumlah kartu yang tampil penuh (tanpa intip) per breakpoint
        function getVisibleCount() {
            const w = window.innerWidth;
            if (w <= 576) return 1;
            if (w <= 992) return 2;
            return 3;
        }

        let current = 0;
        let visible = getVisibleCount();
        let maxIndex = Math.max(cards.length - visible, 0);
        let autoplayTimer = null;

        function buildDots() {
            dotsWrap.innerHTML = '';
            for (let i = 0; i <= maxIndex; i++) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.setAttribute('aria-label', 'Ke slide ' + (i + 1));
                if (i === current) dot.classList.add('active');
                dot.addEventListener('click', () => goTo(i));
                dotsWrap.appendChild(dot);
            }
        }

        function update() {
            const cardWidth = cards[0].getBoundingClientRect().width;
            const gap = parseFloat(getComputedStyle(track).gap) || 0;
            const offset = current * (cardWidth + gap);
            track.style.transform = `translateX(-${offset}px)`;

            Array.from(dotsWrap.children).forEach((dot, i) => {
                dot.classList.toggle('active', i === current);
            });
        }

        function goTo(index) {
            current = Math.min(Math.max(index, 0), maxIndex);
            update();
        }

        function next() {
            goTo(current + 1 > maxIndex ? 0 : current + 1);
        }

        function prev() {
            goTo(current - 1 < 0 ? maxIndex : current - 1);
        }

        function startAutoplay() {
            if (AUTOPLAY_DELAY <= 0) return;
            stopAutoplay();
            autoplayTimer = setInterval(next, AUTOPLAY_DELAY);
        }

        function stopAutoplay() {
            if (autoplayTimer) clearInterval(autoplayTimer);
        }

        // Recalculate saat resize (jumlah kartu terlihat bisa berubah)
        window.addEventListener('resize', () => {
            visible = getVisibleCount();
            maxIndex = Math.max(cards.length - visible, 0);
            current = Math.min(current, maxIndex);
            buildDots();
            update();
        });

        // Swipe / drag manual (mouse & touch via Pointer Events)
        let startX = 0;
        let isDragging = false;

        track.addEventListener('pointerdown', (e) => {
            isDragging = true;
            startX = e.clientX;
            stopAutoplay();
        });

        window.addEventListener('pointerup', (e) => {
            if (!isDragging) return;
            isDragging = false;
            const diff = e.clientX - startX;
            if (diff > 50) prev();
            else if (diff < -50) next();
            startAutoplay();
        });

        // Pause autoplay saat hover
        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);

        // Init
        buildDots();
        update();
        startAutoplay();
    })();
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const header = document.getElementById("headerSticky");
        if (!header) return;

        const hasPageHeader = document.querySelector(".page-header") !== null;

        if (!hasPageHeader) {
            // If there is no page-header on the page, always show the sticky header immediately at the top
            header.classList.add("show");
            header.style.position = "sticky";
            header.style.top = "0";
            header.style.zIndex = "999";
        } else {
            // Otherwise, apply the scroll hide/show behavior
            window.addEventListener("scroll", function () {
                if (window.scrollY > 150) {
                    header.classList.add("show");
                } else {
                    header.classList.remove("show");
                }
            });
        }
    });
</script>
<script>

    const scrollBtn = document.getElementById("scrollTop");
    const circle = document.querySelector("#scrollTop circle");

    const circumference = 138;

    window.addEventListener("scroll", function () {

        let scrollTop = document.documentElement.scrollTop;
        let scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;

        let progress = scrollTop / scrollHeight;

        let offset = circumference - progress * circumference;

        circle.style.strokeDashoffset = offset;

        if (scrollTop > 300) {
            scrollBtn.classList.add("show");
        } else {
            scrollBtn.classList.remove("show");
        }

    });

    scrollBtn.addEventListener("click", function () {

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

    });

</script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
</script>
<script>
    const swiper = new Swiper(".mySwiper", {
        loop: true,

        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },

        grabCursor: true,

        autoplay: {
            delay: 3000,
            disableOnInteraction: true,
        },
    });
</script>
<script>
    /**
 * hamburger.js
 * Mengelola hamburger menu untuk sticky header & page header
 */

    (function () {
        'use strict';

        /* ── Buat elemen overlay & drawer sekali ── */
        const overlay = document.createElement('div');
        overlay.classList.add('mobile-nav-overlay');
        document.body.appendChild(overlay);

        const drawer = document.createElement('div');
        drawer.classList.add('mobile-nav-drawer');
        document.body.appendChild(drawer);

        /* ── Ambil nav links & buttons dari header manapun ── */
        // Prioritas: page-header → sticky header
        function getNavSource() {
            const pageHeader = document.querySelector('.page-header .header-inner');
            const stickyHeader = document.querySelector('.header-sticky .header-inner');
            return pageHeader || stickyHeader;
        }

        function buildDrawer() {
            const source = getNavSource();
            if (!source) return;

            /* Logo */
            const logoImg = source.querySelector('.logo img');
            const logoHref = source.querySelector('.logo a')?.getAttribute('href') || '#';

            /* Nav links */
            const navLinks = source.querySelectorAll('.nav-link a');

            /* Buttons */
            const buttons = source.querySelectorAll('.buttons a');

            drawer.innerHTML = `
            <div class="drawer-header">
                <a href="${logoHref}">
                    <img src="${logoImg ? logoImg.src : ''}" alt="Logo">
                </a>
                <button class="drawer-close" aria-label="Tutup menu">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="drawer-links">
                ${Array.from(navLinks).map(link => `
                    <a href="${link.getAttribute('href')}"
                       ${link.classList.contains('active') ? 'class="active"' : ''}>
                        ${link.textContent.trim()}
                    </a>
                `).join('')}
            </nav>

            ${buttons.length ? `
                <div class="drawer-buttons">
                    ${Array.from(buttons).map((btn, i) =>
                `<a href="${btn.getAttribute('href')}"
                            class="${i === 0 ? 'btn-drawer-white' : 'btn-drawer-yellow'}">
                            ${btn.textContent.trim()}
                        </a>`
            ).join('')}
                </div>
            ` : ''}
        `;

            /* Close button di dalam drawer */
            drawer.querySelector('.drawer-close')?.addEventListener('click', closeMenu);
        }

        /* ── Inject hamburger button ke setiap header-inner ── */
        function injectHamburgers() {
            const headers = document.querySelectorAll('.header-inner');
            headers.forEach(headerInner => {
                if (headerInner.querySelector('.hamburger')) return; // sudah ada

                const btn = document.createElement('button');
                btn.classList.add('hamburger');
                btn.setAttribute('aria-label', 'Buka menu');
                btn.setAttribute('aria-expanded', 'false');
                btn.innerHTML = `
                <span></span>
                <span></span>
                <span></span>
            `;
                btn.addEventListener('click', toggleMenu);
                headerInner.appendChild(btn);
            });
        }

        /* ── Toggle ── */
        function openMenu() {
            buildDrawer();
            drawer.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';

            document.querySelectorAll('.hamburger').forEach(btn => {
                btn.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
            });
        }

        function closeMenu() {
            drawer.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';

            document.querySelectorAll('.hamburger').forEach(btn => {
                btn.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
            });
        }

        function toggleMenu() {
            drawer.classList.contains('open') ? closeMenu() : openMenu();
        }

        /* ── Tutup saat klik overlay ── */
        overlay.addEventListener('click', closeMenu);

        /* ── Tutup saat resize ke desktop ── */
        window.addEventListener('resize', () => {
            if (window.innerWidth > 900) closeMenu();
        });

        /* ── Tutup saat tekan Escape ── */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeMenu();
        });

        /* ── Init ── */
        document.addEventListener('DOMContentLoaded', () => {
            injectHamburgers();
        });

        // Fallback jika DOM sudah siap
        if (document.readyState !== 'loading') {
            injectHamburgers();
        }
    })();
</script>