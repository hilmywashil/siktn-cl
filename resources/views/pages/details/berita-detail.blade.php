@extends('layouts.app')

@section('title', $berita->judul)

@push('styles')
    <style>
        .detail-berita-wrapper {
            background-color: #F0F0F6;
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 60px 20px 100px;
        }

        .detail-berita-container {
            width: 100%;
            max-width: 1300px;
            display: flex;
            flex-direction: row;
            gap: 60px;
            align-items: flex-start;
        }

        /* ── Main Article ── */
        .detail-berita-main {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .detail-berita-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: #777;
        }

        .detail-berita-meta .meta-date {
            font-style: italic;
        }

        .detail-berita-meta .meta-dot {
            width: 4px;
            height: 4px;
            background-color: #FFF000;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .detail-berita-badge {
            display: inline-block;
            background-color: #FFF000;
            color: #000;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .detail-berita-main-image {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            border-radius: 8px;
            display: block;
        }

        .detail-berita-body {
            font-size: 15px;
            line-height: 1.85;
            color: #222;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        .detail-berita-body p {
            margin: 0 0 1.25em;
        }

        /* Share / Back Row */
        .detail-berita-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            gap: 16px;
            flex-wrap: wrap;
        }

        .detail-berita-actions .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #022648;
            text-decoration: none;
            font-weight: 600;
            transition: gap 0.2s;
        }

        .detail-berita-actions .back-link:hover {
            gap: 12px;
            color: #FFF000;
        }

        .detail-berita-actions .share-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-berita-actions .share-label {
            font-size: 13px;
            color: #555;
        }

        .detail-berita-actions .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #022648;
            color: #fff;
            font-size: 14px;
            text-decoration: none;
            transition: background-color 0.2s, transform 0.2s;
        }

        .detail-berita-actions .share-btn:hover {
            background-color: #FFF000;
            transform: translateY(-2px);
        }

        /* ── Sidebar ── */
        .detail-berita-sidebar {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 30px;
            position: sticky;
            top: 150px;
        }

        .sidebar-heading {
            margin: 0 0 20px;
            font-size: 18px;
            color: #022648;
            font-weight: 700;
            text-transform: uppercase;
            padding-bottom: 12px;
            border-bottom: 3px solid #FFF000;
        }

        /* Sidebar news items */
        .sidebar-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
            text-decoration: none;
            color: inherit;
            transition: opacity 0.2s;
        }

        .sidebar-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .sidebar-item:hover {
            opacity: 0.75;
        }

        .sidebar-item-image {
            flex-shrink: 0;
            width: 90px;
            height: 70px;
            border-radius: 6px;
            overflow: hidden;
        }

        .sidebar-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s;
        }

        .sidebar-item:hover .sidebar-item-image img {
            transform: scale(1.05);
        }

        .sidebar-item-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .sidebar-item-content h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #022648;
            line-height: 1.4;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .sidebar-item-content .item-date {
            font-size: 12px;
            color: #999;
            font-style: italic;
        }

        /* =============================================
                                                                                       HERO BANNER — berita detail
                                                                                       Menggunakan class yang sama (.wrapper-page)
                                                                                       namun dengan padding lebih kecil
                                                                                       ============================================= */

        .detail-hero-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            padding: 40px 20px 60px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 340px;
        }

        .detail-hero-banner {
            color: #fff;
            width: 100%;
            max-width: 1300px;
        }

        .detail-hero-banner h1 {
            margin: 0 0 10px;
            font-size: 40px;
            font-weight: 700;
            line-height: 1.3;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            max-width: 860px;
            text-transform: capitalize;
        }

        .detail-hero-banner .hero-date {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
        }

        /* =============================================
                                                                                       RESPONSIVE
                                                                                       ============================================= */

        @media (max-width: 1024px) {
            .detail-berita-container {
                gap: 40px;
            }

            .detail-hero-banner h1 {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            .detail-berita-wrapper {
                padding: 40px 16px 60px;
            }

            .detail-berita-container {
                flex-direction: column;
                gap: 40px;
            }

            .detail-berita-sidebar {
                position: static;
                width: 100%;
            }

            .detail-hero-wrapper {
                min-height: 260px;
                padding: 30px 16px 40px;
            }

            .detail-hero-banner h1 {
                font-size: 22px;
            }

            .sidebar-item-image {
                width: 76px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .detail-hero-banner h1 {
                font-size: 18px;
            }

            .detail-berita-body {
                font-size: 14px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ── Hero Banner ── --}}
    <section class="detail-hero-wrapper"
        style="background-image: linear-gradient(to bottom, rgba(11,19,84,0.55) 0%, rgba(11,19,84,0.85) 100%), url('{{ $berita->gambar_url }}');">
        <div class="detail-hero-banner">
            <span class="detail-berita-badge">Informasi / Kegiatan</span>
            <h1>{{ $berita->judul }}</h1>
            <p class="hero-date">
                <i class="bi bi-calendar3"></i>&nbsp;{{ $berita->tanggal_format }}
            </p>
        </div>
    </section>

    {{-- ── Main Content ── --}}
    <section class="detail-berita-wrapper">
        <div class="detail-berita-container">

            {{-- ── Article ── --}}
            <article class="detail-berita-main">

                <img class="detail-berita-main-image" src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}">

                <div class="detail-berita-body">
                    {!! nl2br(e($berita->konten)) !!}
                </div>

                {{-- Back + Share ── --}}
                <div class="detail-berita-actions">
                    <a href="{{ route('berita') }}" class="back-link">
                        <i class="fa fa-arrow-left"></i> Kembali ke Berita
                    </a>

                    <div class="share-group">
                        <span class="share-label">Bagikan:</span>
                        {{-- WhatsApp --}}
                        <a class="share-btn"
                            href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . url()->current()) }}"
                            target="_blank" rel="noopener" title="Bagikan via WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        {{-- Facebook --}}
                        <a class="share-btn"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank" rel="noopener" title="Bagikan ke Facebook">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                        {{-- Twitter / X --}}
                        <a class="share-btn"
                            href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul) }}&url={{ urlencode(url()->current()) }}"
                            target="_blank" rel="noopener" title="Bagikan ke X">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    </div>
                </div>

            </article>

            {{-- ── Sidebar ── --}}
            <aside class="detail-berita-sidebar">
                <h2 class="sidebar-heading">Berita Lainnya</h2>

                @forelse($beritaTerbaru as $terbaru)
                    <a href="{{ route('berita-detail', $terbaru->slug) }}" class="sidebar-item">
                        <div class="sidebar-item-image">
                            <img src="{{ $terbaru->gambar_url }}" alt="{{ $terbaru->judul }}">
                        </div>
                        <div class="sidebar-item-content">
                            <h3>{{ $terbaru->judul }}</h3>
                            <span class="item-date">{{ $terbaru->tanggal_format }}</span>
                        </div>
                    </a>
                @empty
                    <p style="color: #9ca3af; font-size: 0.875rem;">Belum ada berita lainnya.</p>
                @endforelse
            </aside>

        </div>
    </section>

@endsection