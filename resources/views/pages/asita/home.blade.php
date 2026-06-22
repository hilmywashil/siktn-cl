@extends('layouts.app')

@section('title', 'ASITA JABAR - Association Of The Indonesian Tours & Travel Agencies')

@push('styles')
    <style>
        /* =====================
           FLYER POPUP
        ===================== */
        .flyer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .flyer-overlay.show {
            display: flex;
            animation: flyerFadeIn 0.3s ease forwards;
        }

        .flyer-overlay.hide {
            display: flex;
            animation: flyerFadeOut 0.3s ease forwards;
        }

        .flyer-overlay.hide .flyer-modal {
            animation: flyerSlideDown 0.3s ease forwards;
        }

        @keyframes flyerFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes flyerFadeOut {
            from { opacity: 1; }
            to   { opacity: 0; }
        }

        @keyframes flyerSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        @keyframes flyerSlideDown {
            from { transform: translateY(0);    opacity: 1; }
            to   { transform: translateY(30px); opacity: 0; }
        }

        .flyer-modal {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-width: 420px;
            width: 92%;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            animation: flyerSlideUp 0.3s ease forwards;
        }

        .flyer-close {
            position: absolute;
            top: 10px;
            right: 14px;
            background: rgba(0, 0, 0, 0.45);
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #fff;
            z-index: 10;
            line-height: 1;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .flyer-close:hover { background: rgba(0, 0, 0, 0.7); }

        .flyer-image {
            width: 100%;
            line-height: 0;
        }

        .flyer-image img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .flyer-actions {
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #fff;
        }

        .flyer-register-btn {
            display: block;
            text-align: center;
            background: #1a2e5a;
            color: #fff !important;
            padding: 13px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 0.05em;
            font-size: 0.95rem;
            transition: background 0.2s ease;
        }

        .flyer-register-btn:hover { background: #253d78; }

        .flyer-no-show {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #666;
            cursor: pointer;
            user-select: none;
        }

        .flyer-no-show input {
            cursor: pointer;
            width: 14px;
            height: 14px;
        }

        @media (max-width: 480px) {
            .flyer-modal { max-width: 95%; }
        }
    </style>
@endpush

@section('content')

    <section class="hero">
        <div class="hero-1" data-aos="fade-up">
            <h1>Welcome to ASITA JABAR</h1>
            <p>Association Of The Indonesian Tours & Travel Agencies</p>
            <div class="hero-buttons">
                <a href="{{ route('about') }}" class="btn">About Us</a>
            </div>
        </div>
    </section>

    <section class="main-info">
        <div class="info-cards">
            <div class="info-card" data-aos="fade-up">
                <img src="{{ asset('images/icons/handshake.png') }}" alt="Anggota" class="icon">
                <h3>Mitra Handal</h3>
                <p>Standart operasional prosedur selalu di terapkan dalam tiap system kegiatan dan kerjasama</p>
            </div>
            <div class="info-card" data-aos="fade-up">
                <img src="{{ asset('images/icons/achievement.png') }}" alt="Perusahaan" class="icon">
                <h3>Berpengalaman</h3>
                <p>Sumber daya terlatih, menjadikan ASITA sebagai mitra yang tepat pariwisata di Indonesia</p>
            </div>
            <div class="info-card" data-aos="fade-up">
                <img src="{{ asset('images/icons/globe.png') }}" alt="Klasifikasi Usaha" class="icon">
                <h3>Relasi Luas</h3>
                <p>Terbukti ASITA membawa devisa besar untuk negara Indonesia dalam tingkat nasional</p>
            </div>
        </div>
    </section>

    <section class="about">
        <div class="abouts" data-aos="fade-up">
            <div class="about-image">
                <img src="{{ asset('images/10-des.jpg') }}" alt="about Image">
            </div>
            <div class="about-content">
                <div class="about-heading">
                    <h2>About ASITA JABAR</h2>
                </div>
                <div class="about-items">
                    <a href="{{ route('outline') }}" style="text-decoration: none;">
                        <div class="about-item">
                            <img src="{{ asset('images/icons/building.png') }}">
                            <h3>Outline of ASITA</h3>
                        </div>
                    </a>
                    <a href="" style="text-decoration: none;">
                        <div class="about-item">
                            <img src="{{ asset('images/icons/access.png') }}">
                            <h3>Activities</h3>
                        </div>
                    </a>
                    <a href="" style="text-decoration: none;">
                        <div class="about-item">
                            <img src="{{ asset('images/icons/world-map.png') }}">
                            <h3>Regional Board - JABAR</h3>
                        </div>
                    </a>
                    <a href="{{ route('about') }}" style="text-decoration: none;">
                        <div class="about-item">
                            <img src="{{ asset('images/icons/user.png') }}">
                            <h3>ASITA Profile</h3>
                        </div>
                    </a>
                    <a href="" style="text-decoration: none;">
                        <div class="about-item">
                            <img src="{{ asset('images/icons/graph.png') }}">
                            <h3>Tourism Statistic</h3>
                        </div>
                    </a>
                    <a href="{{ route('contact') }}" style="text-decoration: none;">
                        <div class="about-item">
                            <img src="{{ asset('images/icons/phone-call.png') }}">
                            <h3>Contact Us</h3>
                        </div>
                    </a>
                    <a href="{{ route('vision-mission') }}" style="text-decoration: none;">
                        <div class="about-item">
                            <img src="{{ asset('images/icons/target.png') }}">
                            <h3>Vision & Mission</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="buku-informasi-home">
        <h2 data-aos="fade-up">ASITA News Update</h2>
        <div class="buku-informasi-home-content" data-aos="fade-up">
            <div class="owl-carousel anggota-carousel">
                @foreach($dokumentasiBerita->take(6) as $b)
                    <a href="{{ route('berita-detail', $b->slug) }}">
                        <div class="buku-card">
                            <img src="{{ $b->gambar_url }}" alt="Usaha Anggota 1" loading="lazy">
                            <div class="container">
                                <p>{{ $b->tanggal_format }}</p>
                                <h4>{{ Str::limit($b->judul, limit: 60) }}</h4>
                                <p>Read More</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if ($dokumentasiBerita->isEmpty())
                <div style="text-align: center; padding: 0px 0px 50px 0px;">
                    <p>Belum ada Artikel yang tersedia.</p>
                </div>
            @endif
            <div style="text-align:center; margin-top:25px;">
                <a href="{{ route('berita') }}" class="btn">See More</a>
            </div>
        </div>
    </section>

    <section class="join-us">
        <div class="join-us-container">
            <div class="join-us-children" data-aos="fade-up">
                <h2>Join Us</h2>
                <a href="{{ route('how-to-join') }}" style="text-decoration: none;">
                    <div class="about-item">
                        <img src="{{ asset('images/icons/join.png') }}">
                        <h3>How to join ASITA?</h3>
                    </div>
                </a>
                <a href="" style="text-decoration: none;">
                    <div class="about-item">
                        <img src="{{ asset('images/icons/help.png') }}">
                        <h3>FAQ</h3>
                    </div>
                </a>
            </div>
            <div class="join-us-children" data-aos="fade-up">
                <h2>Roaster</h2>
                <a href="{{ route('active-member') }}" style="text-decoration: none;">
                    <div class="about-item">
                        <img src="{{ asset('images/icons/check.png') }}">
                        <h3>Active member search alphabetically</h3>
                    </div>
                </a>
                <a href="" style="text-decoration: none;">
                    <div class="about-item">
                        <img src="{{ asset('images/icons/group-users.png') }}">
                        <h3>Associated Member</h3>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="ekatalog-home">
        <div class="ekatalog-homes" data-aos="fade-up">
            <h2>Showcase</h2>
            <div class="owl-carousel showcase-carousel">
                @foreach($katalogs->take(6) as $katalog)
                    <a href="{{ route('e-katalog.detail', $katalog->id) }}">
                        <div class="katalog-card">
                            <img src="{{ $katalog->logo_url }}" alt="{{ $katalog->company_name }}">
                            <div class="container">
                                <h4><b>{{ Str::limit($katalog->company_name, 20, '...') }}</b></h4>
                                <p>{{ Str::limit($katalog->business_field, 25, '...') }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if ($katalogs->isEmpty())
                <a href="{{ route('e-katalog') }}" style="text-decoration: none; color: inherit;">
                    <div class="katalog-card">
                        <img src="{{ asset('images/asita-logo.png') }}">
                        <div class="container">
                            <h4><b>Belum Ada Data</b></h4>
                            <p>Klik untuk lihat katalog</p>
                        </div>
                    </div>
                </a>
            @endif
            <div style="margin-top: 50px;">
                <a href="{{ route('e-katalog') }}" class="btn">See More</a>
            </div>
        </div>
    </section>

    <section class="bottom-homes">
        <div class="bottom-home" data-aos="fade-up">
            <div class="bottom-home-1">
                <div class="bottom-home-1-card">
                    <img src="{{ asset('images/icons/enter.png') }}" alt="">
                    <h1>Member Login</h1>
                </div>
                <div class="bottom-home-1-card">
                    <img src="{{ asset('images/icons/calendar.png') }}" alt="">
                    <h1>Calendar Events</h1>
                </div>
                <div class="bottom-home-1-card">
                    <img src="{{ asset('images/icons/web.png') }}" alt="">
                    <h1>Wholesale Tour Operator</h1>
                </div>
            </div>
            <div class="bottom-home-2"></div>
        </div>
    </section>

    {{-- ===================== FLYER POPUP MODAL ===================== --}}
    <div id="flyerModal" class="flyer-overlay">
        <div class="flyer-modal">
            <button class="flyer-close" id="flyerClose">&times;</button>
            <div class="flyer-image">
                <img src="{{ asset('images/flyer-img.jpeg') }}" alt="Event Flyer">
            </div>
            <div class="flyer-actions">
                <a href="https://rakerda.asita-jabar.org/asita" class="flyer-register-btn" target="blank">REGISTER NOW</a>
                <label class="flyer-no-show">
                    <input type="checkbox" id="dontShowAgain">
                    Jangan tampilkan lagi
                </label>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===================== FLYER POPUP =====================
    const STORAGE_KEY = 'asita_flyer_hidden';
    const modal    = document.getElementById('flyerModal');
    const closeBtn = document.getElementById('flyerClose');
    const checkbox = document.getElementById('dontShowAgain');

    if (!modal) return;

    // Tampilkan popup jika belum pernah disembunyikan
    if (localStorage.getItem(STORAGE_KEY) !== 'true') {
        setTimeout(() => {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }, 500);
    }

    function closeModal() {
        // Simpan preferensi jika checkbox dicentang
        if (checkbox && checkbox.checked) {
            localStorage.setItem(STORAGE_KEY, 'true');
        }

        // Jalankan animasi keluar
        modal.classList.remove('show');
        modal.classList.add('hide');

        // Setelah animasi selesai (300ms), benar-benar sembunyikan
        setTimeout(() => {
            modal.classList.remove('hide');
            document.body.style.overflow = '';
        }, 300);
    }

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    // Klik area gelap di luar modal
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // Tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    // ===================== COUNTER =====================
    const counters = document.querySelectorAll('.counter');

    const animateCounter = (counter) => {
        const target    = parseInt(counter.getAttribute('data-target'));
        const duration  = 2000;
        const increment = target / (duration / 16);
        let current     = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };

        updateCounter();
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));

});
</script>
@endpush