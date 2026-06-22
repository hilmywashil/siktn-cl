@extends('layouts.app')

@section('title', 'Beranda')

@section('hero-background', asset('assets-front/images/hero_bg.jpg'))
@section('page-title', 'MEMBANGUN ORGANISASI MODERN, TRANPARANSI, DAN TERINTEGRASI')
@section('page-description', 'Platform digital resmi SIKTN untuk pengelolaan organisasi, data anggota, e-katalog bisnis, dan informasi terbaru secara profesional.')
@section('hero-buttons', 'show')

@section('content')
    @include('layouts.components.hero')
    <section class="wrapper-white-1">
        <div class="about-section" data-aos="fade-up">
            <div class="left">
                <img src="{{ asset('assets-front/images/about_image.jpg') }}" alt="Tentang Karang Taruna">
            </div>
            <div class="right">
                <h2>TENTANG KAMI <br>
                    KARANG TARUNA
                </h2>
                <div>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente laboriosam officia adipisci aliquam
                        consequuntur veniam iure dolor pariatur veritatis ipsam quidem minus fuga dicta, possimus obcaecati
                        ad, voluptate, nihil qui.
                    </p>
                    <p>
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Odit dolores, unde sapiente commodi
                        officia rerum qui ab at modi, illum corrupti odio repudiandae architecto beatae sint veritatis
                        accusamus, quam impedit.
                    </p>
                </div>
                <a href="{{ route('about') }}" class="btn-yellow-text-black">Pelajari Lebih Lanjut </a>
            </div>
        </div>
    </section>
    <section class="wrapper-blue-1">
        <div class="stats-section" data-aos="fade-up">
            <div class="grid">
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/user-group.png') }}" alt="Icon">
                    </div>
                    <h2>200+</h2>
                    <p>Total Anggota</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/location.png') }}" alt="Icon">
                    </div>
                    <h2>150+</h2>
                    <p>Total Wilayah</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/puzzle.png') }}" alt="Icon">
                    </div>
                    <h2>20</h2>
                    <p>Total Program</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/catalog.png') }}" alt="Icon">
                    </div>
                    <h2>120+</h2>
                    <p>Total E-Catalog</p>
                </div>
            </div>
        </div>
    </section>

    <section class="wrapper-white-1">
        <div class="organization-section" data-aos="fade-up">
            <div class="left">
                <h2>STRUKTUR<br>ORGANISASI</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde hic vitae dolor amet autem aspernatur, ab
                    nisi minus impedit ut eligendi modi id ea laborum ducimus in, sed vero dolorem!</p>
                <a href="{{ route('organisasi') }}" class="btn-yellow-text-black">Lihat Selengkapnya</a>
            </div>

            <div class="right">
                <div class="member-carousel" id="memberCarousel">
                    <div class="member-track" id="memberTrack">
                        <div class="member-card"
                            style="background-image:url('{{ asset('assets-front/images/people/jokowi.jpg') }}')">
                            <div class="member-info">
                                <span class="name">Pria Solo</span>
                                <span class="role">Ketua Karang Taruna</span>
                            </div>
                        </div>
                        <div class="member-card"
                            style="background-image:url('{{ asset('assets-front/images/people/jokowi.jpg') }}')">
                            <div class="member-info">
                                <span class="name">Pria Solo</span>
                                <span class="role">Ketua Karang Taruna</span>
                            </div>
                        </div>
                        <div class="member-card"
                            style="background-image:url('{{ asset('assets-front/images/people/jokowi.jpg') }}')">
                            <div class="member-info">
                                <span class="name">Pria Solo</span>
                                <span class="role">Ketua Karang Taruna</span>
                            </div>
                        </div>
                        <div class="member-card"
                            style="background-image:url('{{ asset('assets-front/images/people/jokowi.jpg') }}')">
                            <div class="member-info">
                                <span class="name">Pria Solo</span>
                                <span class="role">Ketua Karang Taruna</span>
                            </div>
                        </div>
                        <div class="member-card"
                            style="background-image:url('{{ asset('assets-front/images/people/jokowi.jpg') }}')">
                            <div class="member-info">
                                <span class="name">Pria Solo</span>
                                <span class="role">Ketua Karang Taruna</span>
                            </div>
                        </div>
                        <div class="member-card"
                            style="background-image:url('{{ asset('assets-front/images/people/jokowi.jpg') }}')">
                            <div class="member-info">
                                <span class="name">Pria Solo</span>
                                <span class="role">Ketua Karang Taruna</span>
                            </div>
                        </div>
                        <div class="member-card"
                            style="background-image:url('{{ asset('assets-front/images/people/jokowi.jpg') }}')">
                            <div class="member-info">
                                <span class="name">Pria Solo</span>
                                <span class="role">Ketua Karang Taruna</span>
                            </div>
                        </div>
                        <div class="member-card"
                            style="background-image:url('{{ asset('assets-front/images/people/jokowi.jpg') }}')">
                            <div class="member-info">
                                <span class="name">Pria Solo</span>
                                <span class="role">Ketua Karang Taruna</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="member-dots" id="memberDots"></div>
            </div>
        </div>
    </section>
    <section class="wrapper-lightblue">
        <div class="program-org-section" data-aos="fade-up">
            <div>
                <h2>PROGRAM ORGANISASI</h2>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quam dolorum deserunt sapiente ratione,
                    repellendus tenetur magni nulla iste! Obcaecati neque molestiae nemo ab quis laborum, possimus magnam.
                    Delectus, porro commodi!</p>
            </div>
            <div class="grid">
                <div class="item">
                    <img src="{{ asset('assets-front/images/icons/corporate.png') }}" alt="Icon">
                    <div class="content">
                        <h3>CSR</h3>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ea quidem asperiores rerum laboriosam
                            inventore quis fuga provident, in, vitae non, delectus praesentium autem? In culpa nihil,
                            repudiandae ea totam corporis.</p>
                    </div>
                </div>
                <div class="item">
                    <img src="{{ asset('assets-front/images/icons/system.png') }}" alt="Icon">
                    <div class="content">
                        <h3>BIDANG</h3>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ea quidem asperiores rerum laboriosam
                            inventore quis fuga provident, in, vitae non, delectus praesentium autem? In culpa nihil,
                            repudiandae ea totam corporis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper-blue-1">
        <div class="katalog-section" data-aos="fade-up">
            <div>
                <h2>E-Catalog Preview</h2>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Alias nesciunt culpa earum rem perspiciatis
                    saepe nostrum nisi fugiat laudantium beatae, aspernatur, eos libero fugit maxime dolorum tempora atque
                    voluptatibus amet!
                </p>
            </div>
            <div class="grid">
                @forelse($katalogs as $katalog)
                    <a href="{{ route('e-katalog.detail', $katalog->id) }}">
                        <div class="item">
                            <img src="{{ $katalog->logo_url ?? asset('assets-front/images/logo_karang_taruna.png') }}"
                                alt="{{ $katalog->company_name }}">
                            <div>
                                <h3>{{ $katalog->company_name }}</h3>
                                <p>{{ $katalog->business_field }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <a href="#">
                        <div class="item">
                            <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Logo">
                            <div>
                                <h3>E-Catalog</h3>
                                <p>Business</p>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="item">
                            <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Logo">
                            <div>
                                <h3>E-Catalog</h3>
                                <p>Business</p>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="item">
                            <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Logo">
                            <div>
                                <h3>E-Catalog</h3>
                                <p>Business</p>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="item">
                            <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Logo">
                            <div>
                                <h3>E-Catalog</h3>
                                <p>Business</p>
                            </div>
                        </div>
                    </a>

                @endforelse
            </div>
            <a href="{{ route('e-katalog') }}" class="btn-yellow">Lihat Lebih Banyak</a>
        </div>
    </section>
    <section class="wrapper-image-2">
        <div class="bergabung-section" data-aos="fade-up">
            <h1>“Bergabung Menjadi Bagian dari Transformasi Organisasi Digital”</h1>
            <a href="{{ route('contact') }}" class="btn-yellow"><i class="fa fa-phone" style="margin-right: 10px;"></i>
                Hubungi Sekretariat</a>
        </div>
    </section>
    <section class="wrapper-white-1">
        <div class="kegiatan-section" data-aos="fade-up">
            <div>
                <h2>INFORMASI DAN KEGIATAN KAMI</h2>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quam dolorum deserunt sapiente ratione,
                    repellendus tenetur magni nulla iste! Obcaecati neque molestiae nemo ab quis laborum, possimus magnam.
                    Delectus, porro commodi!</p>
            </div>
            <div class="content">
                <div class="left">
                    <div class="kegiatan-featured-card">
                        <div class="kegiatan-featured-card__banner">
                            <img src="{{ asset('assets-front/images/foto_1.jpg') }}" alt="Foto Kegiatan"
                                class="kegiatan-featured-card__image" />
                            <div class="kegiatan-featured-card__overlay">
                                <span class="kegiatan-featured-card__category">Category</span>
                                <h2>Membangun Sinergi: Rapat Kerja Tahunan Organisasi</h2>
                                <p>Senin, 27 April 2020</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <div class="kegiatan-card">
                        <img src="{{ asset('assets-front/images/foto_1.jpg') }}" alt="Thumbnail">
                        <div class="content-card">
                            <h2 class="line-clamp-3">
                                Mencetak Pemimpin Masa Depan Melalui Pelatihan Kepemimpinan
                            </h2>
                            <p>Senin, 27 April 2020</p>
                        </div>
                    </div>
                    <div class="kegiatan-card">
                        <img src="{{ asset('assets-front/images/foto_1.jpg') }}" alt="Thumbnail">
                        <div class="content-card">
                            <h2 class="line-clamp-3">
                                Berbagi Kepedulian Melalui Aksi Sosial Bersama
                            </h2>
                            <p>Senin, 27 April 2020</p>
                        </div>
                    </div>
                    <div class="kegiatan-card">
                        <img src="{{ asset('assets-front/images/foto_1.jpg') }}" alt="Thumbnail">
                        <div class="content-card">
                            <h2 class="line-clamp-3">
                                Menghadapi Tantangan Era Digital dengan Seminar Inovasi Organisasi
                            </h2>
                            <p>Senin, 27 April 2020</p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('buku-anggota') }}" class="btn-yellow-text-black">Lihat Lebih Banyak</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const track = document.getElementById('kegiatanTrack');
            const dotsEl = document.getElementById('kegiatanDots');
            const btnPrev = document.getElementById('kegiatanPrev');
            const btnNext = document.getElementById('kegiatanNext');

            const GAP = 25; // harus sama dengan gap di CSS

            const cards = track.querySelectorAll('.card');
            const total = cards.length;

            let current = 0;

            /* ── Berapa card yang tampil sesuai lebar layar ── */
            function getVisible() {
                const w = window.innerWidth;
                if (w <= 480) return 1;
                if (w <= 768) return 2;
                if (w <= 1024) return 3;
                return 4;
            }

            /* ── Hitung lebar 1 step ── */
            function stepWidth() {
                const VISIBLE = getVisible();
                const trackW = track.parentElement.offsetWidth;
                return (trackW + GAP) / VISIBLE;
            }

            /* ── Maksimum index yang bisa dicapai ── */
            function getMaxStep() {
                return Math.max(0, total - getVisible());
            }

            /* ── Rebuild dots sesuai maxStep ── */
            function buildDots() {
                dotsEl.innerHTML = '';
                const maxStep = getMaxStep();
                for (var i = 0; i <= maxStep; i++) {
                    var dot = document.createElement('button');
                    dot.className = 'dot' + (i === current ? ' active' : '');
                    dot.setAttribute('aria-label', 'Slide ' + (i + 1));
                    dot.dataset.idx = i;
                    dot.addEventListener('click', function () { goTo(+this.dataset.idx); });
                    dotsEl.appendChild(dot);
                }
            }

            /* ── Geser track ── */
            function goTo(idx) {
                const maxStep = getMaxStep();
                current = ((idx % (maxStep + 1)) + (maxStep + 1)) % (maxStep + 1); // loop
                track.style.transform = 'translateX(-' + (current * stepWidth()) + 'px)';
                dotsEl.querySelectorAll('.dot').forEach(function (d, i) {
                    d.classList.toggle('active', i === current);
                });
            }

            /* ── Recalculate on resize ── */
            window.addEventListener('resize', function () {
                buildDots();
                // Clamp current agar tidak melebihi maxStep baru
                if (current > getMaxStep()) current = getMaxStep();
                goTo(current);
            });

            /* ── Arrow buttons ── */
            btnPrev.addEventListener('click', function () { goTo(current - 1); });
            btnNext.addEventListener('click', function () { goTo(current + 1); });

            /* ── Swipe support (mobile) ── */
            var touchStartX = 0;
            track.addEventListener('touchstart', function (e) {
                touchStartX = e.touches[0].clientX;
            }, { passive: true });
            track.addEventListener('touchend', function (e) {
                var dx = e.changedTouches[0].clientX - touchStartX;
                if (Math.abs(dx) > 40) goTo(dx < 0 ? current + 1 : current - 1);
            });

            /* ── Init ── */
            buildDots();
            goTo(0);
        })();
    </script>
@endpush