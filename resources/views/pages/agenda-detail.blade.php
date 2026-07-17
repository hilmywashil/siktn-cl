{{-- resources/views/pages/agenda-detail.blade.php --}}
@extends ('layouts.app')

@php
    // Dummy Data untuk render layout detail sementara
    $kegiatan = (object)[
        'judul' => 'Rapat Kerja Nasional Karang Taruna',
        'bidang_name' => 'Organisasi',
        'tanggal_publish' => now()->addDays(10),
        'gambar' => 'dummy',
        'konten' => '<p>Pembahasan program kerja strategis Pengurus Nasional Karang Taruna (PNKT) untuk periode mendatang. Kegiatan ini akan dihadiri oleh seluruh pengurus tingkat nasional dari 34 provinsi.</p><p>Rapat ini bertujuan untuk menyelaraskan visi misi organisasi.</p>',
        'gambar_dokumentasi' => ['dummy1', 'dummy2', 'dummy3'],
        'gambar_dokumentasi_url' => [asset('assets-front/images/hero_bg.jpg'), asset('assets-front/images/hero_bg.jpg'), asset('assets-front/images/hero_bg.jpg')]
    ];

    $kegiatanPopuler = collect([
        (object)[
            'slug' => 'temu-karya',
            'judul' => 'Temu Karya Nasional',
            'bidang_name' => 'Kaderisasi',
            'tanggal_publish' => now()->addDays(20),
            'gambar' => 'dummy',
            'konten' => 'Kegiatan Temu Karya Karang Taruna Nasional.'
        ]
    ]);

    $kegiatanLainnya = collect([
        (object)[
            'slug' => 'bakti-sosial',
            'judul' => 'Bakti Sosial Serentak',
            'bidang_name' => 'Sosial',
            'tanggal_publish' => now()->addDays(30),
            'gambar' => 'dummy',
            'konten' => 'Bakti sosial di seluruh wilayah Indonesia.'
        ]
    ]);
@endphp

@section('title', $kegiatan->judul . ' - Agenda PNKT')

@section('hero-background', asset('assets-front/images/hero_bg.jpg'))
@section('page-title', Str::upper($kegiatan->judul))
@section('page-description', 'Detail Agenda Resmi Pengurus Nasional Karang Taruna.')
@section('hero-buttons', 'hide')

@section('content')
    @include('layouts.components.hero')

    <section class="detail-berita" style="margin-top: 60px;">
        <div class="detail-berita-content">
            {{-- Info Metadata --}}
            <div class="kegiatan-meta">
                <div class="meta-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>{{ $kegiatan->tanggal_publish->format('d F Y') }}</span>
                </div>
                <div class="meta-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Diterbitkan oleh <strong>{{ $kegiatan->bidang_name }}</strong></span>
                </div>
            </div>

            <div class="thumbnail-wrapper">
                <img src="{{ asset('assets-front/images/hero_bg.jpg') }}" alt="{{ $kegiatan->judul }}">
            </div>

            {{-- Tampilkan konten dengan HTML formatting --}}
            <div class="konten-kegiatan">
                {!! $kegiatan->konten !!}
            </div>

            {{-- Carousel Dokumentasi --}}
            @if($kegiatan->gambar_dokumentasi && count($kegiatan->gambar_dokumentasi) > 0)
                <div class="dokumentasi-section">
                    <h2 class="dokumentasi-title">Dokumentasi Kegiatan</h2>

                    <div class="carousel-container">
                        <div class="carousel-wrapper">
                            @foreach($kegiatan->gambar_dokumentasi as $index => $gambar)
                                <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('assets-front/images/hero_bg.jpg') }}" alt="Dokumentasi {{ $index + 1 }}"
                                        onclick="openLightbox({{ $index }})">
                                </div>
                            @endforeach
                        </div>

                        {{-- Navigation Buttons --}}
                        @if(count($kegiatan->gambar_dokumentasi) > 1)
                            <button class="carousel-btn carousel-prev" onclick="moveCarousel(-1)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <button class="carousel-btn carousel-next" onclick="moveCarousel(1)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>

                            {{-- Dots Indicator --}}
                            <div class="carousel-dots">
                                @foreach($kegiatan->gambar_dokumentasi as $index => $gambar)
                                    <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></span>
                                @endforeach
                            </div>

                            {{-- Counter --}}
                            <div class="carousel-counter">
                                <span id="current-slide">1</span> / {{ count($kegiatan->gambar_dokumentasi) }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="berita-detail-right">
            {{-- Kegiatan Populer --}}
            <div class="sidebar-section">
                <h1 class="berita-badge">Kegiatan Populer</h1>

                @forelse($kegiatanPopuler as $item)
                    <div class="berita-detail-right-item" style="margin-bottom: 2rem;">
                        <a href="{{ route('agenda.detail', $item->slug) }}" class="berita-detail-right-item-image"
                            style="position: relative;">
                        <img src="{{ asset('assets-front/images/hero_bg.jpg') }}" alt="{{ $item->judul }}">

                            {{-- Badge Bidang di pojok kanan atas --}}
                            <span class="bidang-badge"
                                style="position: absolute; top: 0.5rem; right: 0.5rem; margin-bottom: 0;">
                                {{ $item->bidang_name }}
                            </span>

                            <div class="image-overlay">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </div>
                        </a>
                        <div class="berita-detail-right-item-content">
                            <div>
                                <span class="berita-home-date"
                                    style="margin-bottom: 0.5rem; display: block;">{{ $item->tanggal_publish->format('F d, Y') }}</span>
                                <h3>{{ $item->judul }}</h3>
                                <p>{{ Str::limit(strip_tags($item->konten), 100, '...') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #6b7280; padding: 1rem;">Belum ada kegiatan populer</p>
                @endforelse
            </div>

            {{-- Kegiatan Lainnya --}}
            <div class="sidebar-section">
                <h1 class="berita-badge">Kegiatan Lainnya</h1>

                @forelse($kegiatanLainnya as $item)
                    <div class="berita-detail-right-item" style="margin-bottom: 2rem;">
                        <a href="{{ route('agenda.detail', $item->slug) }}" class="berita-detail-right-item-image"
                            style="position: relative;">
                            <img src="{{ asset('assets-front/images/hero_bg.jpg') }}" alt="{{ $item->judul }}">

                            {{-- Badge Bidang di pojok kanan atas --}}
                            <span class="bidang-badge"
                                style="position: absolute; top: 0.5rem; right: 0.5rem; margin-bottom: 0;">
                                {{ $item->bidang_name }}
                            </span>

                            <div class="image-overlay">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </div>
                        </a>
                        <div class="berita-detail-right-item-content">
                            <div>
                                <span class="berita-home-date"
                                    style="margin-bottom: 0.5rem; display: block;">{{ $item->tanggal_publish->format('F d, Y') }}</span>
                                <h3>{{ $item->judul }}</h3>
                                <p>{{ Str::limit(strip_tags($item->konten), 100, '...') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #6b7280; padding: 1rem;">Belum ada kegiatan lainnya</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Lightbox Modal --}}
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <button class="lightbox-prev" onclick="changeLightboxImage(-1); event.stopPropagation();">&#10094;</button>
        <button class="lightbox-next" onclick="changeLightboxImage(1); event.stopPropagation();">&#10095;</button>
        <img class="lightbox-content" id="lightbox-img" onclick="event.stopPropagation()">
        <div class="lightbox-caption" id="lightbox-caption"></div>
    </div>

    <style>
        .berita-wr {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .berita {
            width: 100%;
            max-width: 1400px;
            display: flex;
            flex-direction: row;
            gap: 50px;
        }

        .berita-left {
            width: 65%;
            display: flex;
            flex-direction: column;
            padding: 0px 0px 100px 0px;
            gap: 25px;
        }

        .berita-item {
            display: flex;
            flex-direction: row;
            gap: 15px;
        }

        .berita-item-image {
            width: 300px;
            height: 100%;
        }

        .berita-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
        }

        .berita-item-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 20px;
        }

        .berita-right {
            width: 35%;
            display: flex;
            flex-direction: column;
            padding: 0px 0px 100px 0px;
            gap: 25px;
        }

        .berita-right-item {
            display: flex;
            flex-direction: column;
        }

        .berita-right-item-image img {
            width: 100%;
            max-width: 500px;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
        }

        .berita-right-item-content {
            padding: 20px 0px 0px 0px;
            display: flex;
            flex-direction: column;
        }

        .detail-berita {
            display: flex;
            flex-direction: row;
            padding: 0px 100px 100px 100px;
            gap: 50px;
        }

        .detail-berita-content {
            width: 70%;
            text-align: justify;
        }

        .detail-berita-content img {
            width: 100%;
            max-width: 1500px;
            margin-bottom: 30px;
            border-radius: 20px;
        }

        .berita-detail-right {
            width: 30%;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .berita-detail-right-item {
            display: flex;
            flex-direction: column;
        }

        .berita-detail-right-item-image img {
            width: 100%;
            max-width: 500px;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
        }

        .berita-detail-right-item-content {
            padding: 20px 0px 0px 0px;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 1024px) {
            .berita-wr {
                padding: 50px 20px;
            }

            .berita {
                display: flex;
                flex-direction: column;
                width: 100%;
            }

            .berita-left {
                width: 100%;
                display: flex;
                flex-direction: column;
                padding: 0px 0px 0px 0px;
                gap: 25px;
            }

            .berita-item {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .berita-item-image {
                width: 100%;
                height: auto;
            }

            .berita-item-image img {
                width: 100%;
                object-fit: cover;
                border-radius: 15px;
            }

            .berita-item-content {
                padding: 10px;
                display: flex;
                flex-direction: column;
                justify-content: start;
                gap: 20px;
            }

            .berita-right {
                width: 100%;
                padding: 0px 0px 0px 0px;

            }

            .berita-right h1 {
                font-size: 25px;
            }

            .detail-berita {
                display: flex;
                flex-direction: column;
                padding: 0px 20px 20px 20px;
                gap: 50px;
            }

            .detail-berita-content {
                width: 100%;
                text-align: justify;
            }

            .berita-detail-right {
                width: 100%;
            }

            .berita-detail-right-item {
                display: flex;
                flex-direction: column;
            }

            .berita-detail-right-item-image img {
                width: 100%;
                max-width: 500px;
                height: 250px;
                object-fit: cover;
                border-radius: 15px;
            }

            .berita-detail-right-item-content {
                padding: 20px 0px 0px 0px;
                display: flex;
                flex-direction: column;
            }


            /* Fix text overflow untuk berita */
            .berita-item-content h3,
            .berita-item-content p,
            .berita-right-item-content h3,
            .berita-right-item-content p,
            .berita-detail-right-item-content h3,
            .berita-detail-right-item-content p,
            .detail-berita-content p,
            .page-banner h1 {
                word-wrap: break-word;
                word-break: break-word;
                overflow-wrap: break-word;
                hyphens: auto;
            }

            /* Batasi lebar konten */
            .berita-item-content,
            .berita-right-item-content,
            .berita-detail-right-item-content {
                max-width: 100%;
                overflow: hidden;
            }
        }

        /* Kegiatan Metadata */
        .kegiatan-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            padding: 1rem 0;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.9375rem;
        }

        .meta-item svg {
            flex-shrink: 0;
            color: #0a2540;
        }

        .meta-item strong {
            color: #0a2540;
            font-weight: 600;
        }

        /* Thumbnail Gambar Utama */
        .thumbnail-wrapper {
            width: 100%;
            max-width: 100%;
            margin: 0 0 2rem 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .thumbnail-wrapper img {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            display: block;
        }

        /* Konten Kegiatan Styling */
        .konten-kegiatan {
            line-height: 1.8;
            color: #374151;
            font-size: 1rem;
            margin-bottom: 2rem;
            max-width: 100%;
        }

        .konten-kegiatan h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1.5rem 0 1rem;
            color: #1f2937;
            line-height: 1.3;
        }

        .konten-kegiatan h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 1.25rem 0 0.75rem;
            color: #1f2937;
            line-height: 1.4;
        }

        .konten-kegiatan h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 1rem 0 0.5rem;
            color: #1f2937;
            line-height: 1.4;
        }

        .konten-kegiatan p {
            margin: 1rem 0;
            line-height: 1.8;
        }

        .konten-kegiatan strong,
        .konten-kegiatan b {
            font-weight: 700;
            color: #111827;
        }

        .konten-kegiatan em,
        .konten-kegiatan i {
            font-style: italic;
        }

        .konten-kegiatan u {
            text-decoration: underline;
        }

        .konten-kegiatan ul,
        .konten-kegiatan ol {
            margin: 1rem 0;
            padding-left: 2rem;
        }

        .konten-kegiatan ul {
            list-style-type: disc;
        }

        .konten-kegiatan ol {
            list-style-type: decimal;
        }

        .konten-kegiatan li {
            margin: 0.5rem 0;
            line-height: 1.8;
        }

        .konten-kegiatan a {
            color: #0a2540;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .konten-kegiatan a:hover {
            color: #ffd700;
        }

        .konten-kegiatan blockquote {
            border-left: 4px solid #0a2540;
            padding-left: 1rem;
            margin: 1.5rem 0;
            color: #6b7280;
            font-style: italic;
        }

        .konten-kegiatan code {
            background: #f3f4f6;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        .konten-kegiatan pre {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1rem 0;
        }

        .konten-kegiatan pre code {
            background: none;
            padding: 0;
        }

        /* Dokumentasi Section */
        .dokumentasi-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid #e5e7eb;
            max-width: 100%;
        }

        .dokumentasi-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        /* Carousel Container */
        .carousel-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            margin: 0;
            border-radius: 12px;
            overflow: hidden;
            background: #f9fafb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .carousel-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            overflow: hidden;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-slide.active {
            opacity: 1;
            z-index: 1;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: zoom-in;
        }

        /* Carousel Navigation Buttons */
        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .carousel-btn:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-btn svg {
            color: #374151;
        }

        .carousel-prev {
            left: 16px;
        }

        .carousel-next {
            right: 16px;
        }

        /* Dots Indicator */
        .carousel-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .dot:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: scale(1.2);
        }

        .dot.active {
            background: white;
            width: 12px;
            height: 12px;
        }

        /* Counter */
        .carousel-counter {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            z-index: 10;
        }

        /* Sidebar Section Spacing */
        .sidebar-section {
            margin-bottom: 2.5rem;
        }

        .sidebar-section:last-child {
            margin-bottom: 0;
        }

        /* Badge Bidang */
        .bidang-badge {
            display: inline-block;
            background: linear-gradient(135deg, #0a2540 0%, #1a4068 100%);
            color: #ffd700;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(10, 37, 64, 0.2);
        }

        /* Image Preview Overlay */
        .berita-detail-right-item-image {
            position: relative;
            display: block;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 37, 64, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-overlay svg {
            color: #ffd700;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .berita-detail-right-item-image:hover .image-overlay {
            opacity: 1;
        }

        .berita-detail-right-item-image img {
            transition: transform 0.3s ease;
        }

        .berita-detail-right-item-image:hover img {
            transform: scale(1.05);
        }

        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            overflow: auto;
        }

        .lightbox-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 85vh;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: zoom 0.3s;
            cursor: default;
        }

        @keyframes zoom {
            from {
                transform: translate(-50%, -50%) scale(0.8);
            }

            to {
                transform: translate(-50%, -50%) scale(1);
            }
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 40px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
            transition: color 0.3s;
        }

        .lightbox-close:hover {
            color: #bbb;
        }

        .lightbox-prev,
        .lightbox-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            font-size: 30px;
            padding: 16px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            z-index: 10000;
        }

        .lightbox-prev:hover,
        .lightbox-next:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        .lightbox-prev {
            left: 20px;
        }

        .lightbox-next {
            right: 20px;
        }

        .lightbox-caption {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            text-align: center;
            font-size: 16px;
            padding: 10px 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .kegiatan-meta {
                flex-direction: column;
                gap: 0.75rem;
                padding: 0.75rem 0;
                margin-bottom: 1rem;
            }

            .meta-item {
                font-size: 0.875rem;
            }

            .thumbnail-wrapper {
                border-radius: 8px;
                margin-bottom: 1.5rem;
            }

            .thumbnail-wrapper img {
                max-height: 350px;
            }

            .konten-kegiatan {
                font-size: 0.9375rem;
            }

            .konten-kegiatan h2 {
                font-size: 1.25rem;
            }

            .konten-kegiatan h3 {
                font-size: 1.125rem;
            }

            .konten-kegiatan h4 {
                font-size: 1rem;
            }

            .carousel-wrapper {
                aspect-ratio: 4 / 3;
            }

            .carousel-btn {
                width: 40px;
                height: 40px;
            }

            .carousel-prev {
                left: 8px;
            }

            .carousel-next {
                right: 8px;
            }

            .carousel-counter {
                font-size: 12px;
                padding: 4px 10px;
            }

            .sidebar-section {
                margin-bottom: 2rem;
            }

            .bidang-badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.6rem;
            }

            .berita-detail-right-item-image {
                margin-bottom: 0.875rem;
            }

            .image-overlay svg {
                width: 28px;
                height: 28px;
            }

            .lightbox-content {
                max-width: 95%;
                max-height: 80vh;
            }

            .lightbox-close {
                top: 10px;
                right: 20px;
                font-size: 30px;
            }

            .lightbox-prev,
            .lightbox-next {
                padding: 12px 16px;
                font-size: 24px;
            }
        }
    </style>

    <script>
        const images = @json($kegiatan->gambar_dokumentasi_url ?? []);
        let currentCarouselIndex = 0;
        let currentLightboxIndex = 0;

        // Carousel Functions
        function moveCarousel(direction) {
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.dot');

            slides[currentCarouselIndex].classList.remove('active');
            dots[currentCarouselIndex].classList.remove('active');

            currentCarouselIndex += direction;

            if (currentCarouselIndex >= slides.length) {
                currentCarouselIndex = 0;
            } else if (currentCarouselIndex < 0) {
                currentCarouselIndex = slides.length - 1;
            }

            slides[currentCarouselIndex].classList.add('active');
            dots[currentCarouselIndex].classList.add('active');

            document.getElementById('current-slide').textContent = currentCarouselIndex + 1;
        }

        function goToSlide(index) {
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.dot');

            slides[currentCarouselIndex].classList.remove('active');
            dots[currentCarouselIndex].classList.remove('active');

            currentCarouselIndex = index;

            slides[currentCarouselIndex].classList.add('active');
            dots[currentCarouselIndex].classList.add('active');

            document.getElementById('current-slide').textContent = currentCarouselIndex + 1;
        }

        // Lightbox Functions
        function openLightbox(index) {
            currentLightboxIndex = index;
            const lightbox = document.getElementById('lightbox');
            const img = document.getElementById('lightbox-img');
            const caption = document.getElementById('lightbox-caption');

            lightbox.style.display = 'block';
            img.src = images[currentLightboxIndex];
            caption.textContent = `Dokumentasi ${currentLightboxIndex + 1} dari ${images.length}`;
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function changeLightboxImage(direction) {
            currentLightboxIndex += direction;

            if (currentLightboxIndex >= images.length) {
                currentLightboxIndex = 0;
            } else if (currentLightboxIndex < 0) {
                currentLightboxIndex = images.length - 1;
            }

            const img = document.getElementById('lightbox-img');
            const caption = document.getElementById('lightbox-caption');

            img.src = images[currentLightboxIndex];
            caption.textContent = `Dokumentasi ${currentLightboxIndex + 1} dari ${images.length}`;
        }

        // Keyboard Navigation
        document.addEventListener('keydown', function (e) {
            const lightbox = document.getElementById('lightbox');

            // Lightbox navigation
            if (lightbox.style.display === 'block') {
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft') {
                    changeLightboxImage(-1);
                } else if (e.key === 'ArrowRight') {
                    changeLightboxImage(1);
                }
            }
            // Carousel navigation (when lightbox is closed)
            else {
                if (e.key === 'ArrowLeft') {
                    moveCarousel(-1);
                } else if (e.key === 'ArrowRight') {
                    moveCarousel(1);
                }
            }
        });

        // Touch/Swipe Support for Carousel
        let touchStartX = 0;
        let touchEndX = 0;

        const carouselWrapper = document.querySelector('.carousel-wrapper');

        if (carouselWrapper) {
            carouselWrapper.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            });

            carouselWrapper.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }

        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                moveCarousel(1); // Swipe left
            }
            if (touchEndX > touchStartX + 50) {
                moveCarousel(-1); // Swipe right
            }
        }
    </script>

@endsection