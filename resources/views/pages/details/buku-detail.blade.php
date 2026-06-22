@extends('layouts.app')

@section('title', $anggota->nama_lengkap . ' - Buku Informasi Anggota Karang Taruna')

@push('styles')
    <style>
        /* ── Reset & Base ── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* ── Container ── */
        .container {
            width: 100%;
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* =========================================
                   HERO
                   ========================================= */

        .hero {
            background-color: #022648;
            width: 100%;
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1fr 320px;
            min-height: 420px;
        }

        /* Info */
        .hero-info {
            padding: 52px 60px 52px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 6px;
        }

        .hero-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.45);
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 24px;
            transition: color 0.2s;
            width: fit-content;
        }

        .hero-back:hover {
            color: #FFF000;
        }

        .hero-back i {
            font-size: 14px;
        }

        .hero-name {
            font-size: 36px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 6px;
            line-height: 1.2;
        }

        .hero-sub {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.45);
            margin: 0 0 28px;
            font-style: italic;
        }

        .hero-contacts {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .contact-row {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
        }

        .contact-row i {
            font-size: 15px;
            color: #FFF000;
            width: 16px;
            flex-shrink: 0;
        }

        /* Photo */
        .hero-photo {
            position: relative;
            overflow: hidden;
        }

        .hero-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            display: block;
        }

        .hero-photo-placeholder {
            width: 100%;
            height: 100%;
            background: #131b5e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            font-weight: 700;
            color: rgba(255, 231, 1, 0.25);
        }

        /* Yellow bottom bar */
        .hero-bar {
            height: 4px;
            background: #FFF000;
            width: 100%;
        }

        /* =========================================
                   PAGE BODY
                   ========================================= */

        .page-body {
            background: #F0F0F6;
            width: 100%;
            padding: 60px 20px 80px;
        }

        .page-body .container {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        /* =========================================
                   SECTION HEADER
                   ========================================= */

        .section-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #BB8D14;
            margin: 0;
            white-space: nowrap;
        }

        .section-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #d8d8e0;
        }

        /* =========================================
                   CARD
                   ========================================= */

        .card {
            background: #fff;
            border: 1px solid #e2e2ea;
            border-radius: 8px;
            padding: 32px;
        }

        /* Info grid (nama perusahaan / bidang usaha) */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 1px solid #ededf3;
            margin-bottom: 24px;
            padding-bottom: 24px;
        }

        .info-item {
            padding: 0 24px 0 0;
        }

        .info-item+.info-item {
            padding: 0 0 0 24px;
            border-left: 1px solid #ededf3;
        }

        .info-key {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #9090a0;
            margin-bottom: 5px;
        }

        .info-val {
            font-size: 15px;
            font-weight: 600;
            color: #022648;
        }

        .card-desc {
            font-size: 14px;
            color: #444;
            line-height: 1.85;
            margin: 0;
        }

        /* =========================================
                   GALLERY
                   ========================================= */

        .gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .gallery-item {
            border-radius: 6px;
            overflow: hidden;
            aspect-ratio: 4/3;
            background: #e0e0e8;
        }

        .gallery-item:first-child {
            grid-column: span 2;
            aspect-ratio: 16/9;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.04);
        }

        /* =========================================
                   EMPTY STATE
                   ========================================= */

        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #aaa;
            font-size: 14px;
            font-style: italic;
            background: #fff;
            border: 1px dashed #d0d0da;
            border-radius: 8px;
        }

        /* =========================================
                   RESPONSIVE
                   ========================================= */

        @media (max-width: 1024px) {
            .hero-inner {
                grid-template-columns: 1fr 260px;
            }
        }

        @media (max-width: 768px) {
            .hero-inner {
                grid-template-columns: 1fr;
            }

            .hero-photo {
                height: 260px;
                order: -1;
            }

            .hero-info {
                padding: 32px 0 40px;
            }

            .hero-name {
                font-size: 26px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .info-item+.info-item {
                padding: 0;
                border-left: none;
                border-top: 1px solid #ededf3;
                padding-top: 20px;
            }

            .gallery {
                grid-template-columns: 1fr 1fr;
            }

            .gallery-item:first-child {
                grid-column: span 2;
            }

            .card {
                padding: 24px 20px;
            }

            .page-body {
                padding: 40px 16px 60px;
            }
        }

        @media (max-width: 480px) {
            .hero-name {
                font-size: 22px;
            }

            .gallery {
                grid-template-columns: 1fr;
            }

            .gallery-item:first-child {
                grid-column: span 1;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ── Hero ── --}}
    <section class="hero">
        <div class="container">
            <div class="hero-inner">

                {{-- Info --}}
                <div class="hero-info">
                    <a href="{{ route('buku-anggota') }}" class="hero-back">
                        <i class="fa fa-arrow-left"></i>
                        Kembali ke Buku Anggota
                    </a>

                    <h1 class="hero-name">{{ $anggota->nama_lengkap }}</h1>
                    <p class="hero-sub">
                        Alumni STIP Jakarta &mdash; Angkatan {{ $anggota->angkatan }}
                        (NRP. {{ $anggota->nrp }})
                    </p>

                    <div class="hero-contacts">
                        @if($anggota->alamat_domisili)
                            <div class="contact-row">
                                <i class="fa fa-map-marker"></i>
                                <span>{{ $anggota->alamat_domisili }}</span>
                            </div>
                        @endif
                        @if($anggota->no_telp)
                            <div class="contact-row">
                                <i class="fa fa-phone"></i>
                                <span>{{ $anggota->no_telp }}</span>
                            </div>
                        @endif
                        @if($anggota->email)
                            <div class="contact-row">
                                <i class="fa fa-envelope"></i>
                                <span>{{ $anggota->email }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Photo --}}
                <div class="hero-photo">
                    @if($anggota->foto_diri)
                        <img src="{{ Storage::url($anggota->foto_diri) }}" alt="{{ $anggota->nama_lengkap }}">
                    @else
                        <div class="hero-photo-placeholder">
                            {{ strtoupper(substr($anggota->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
        <div class="hero-bar"></div>
    </section>

    {{-- ── Body ── --}}
    <div class="page-body">
        <div class="container">

            @if($anggota->katalog && $anggota->katalog->is_active)

                {{-- Informasi Bisnis --}}
                <div>
                    <div class="section-header">
                        <h2>Informasi Bisnis &amp; Usaha</h2>
                    </div>
                    <div class="card">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-key">Nama Perusahaan</div>
                                <div class="info-val">{{ $anggota->katalog->company_name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-key">Bidang Usaha</div>
                                <div class="info-val">{{ $anggota->katalog->business_field }}</div>
                            </div>
                        </div>
                        @if($anggota->katalog->description)
                            <p class="card-desc">{{ $anggota->katalog->description }}</p>
                        @else
                            <p class="card-desc" style="color: #aaa; font-style: italic;">
                                Deskripsi usaha belum tersedia.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Galeri --}}
                @if($anggota->katalog->images_url && count($anggota->katalog->images_url) > 0)
                    <div>
                        <div class="section-header">
                            <h2>Galeri Usaha</h2>
                        </div>
                        <div class="gallery">
                            @foreach($anggota->katalog->images_url as $imgUrl)
                                @if($imgUrl)
                                    <div class="gallery-item">
                                        <img src="{{ $imgUrl }}" alt="{{ $anggota->katalog->company_name }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            @else

                <div class="empty-state">
                    Data katalog usaha belum tersedia atau belum diaktifkan oleh anggota ini.
                </div>

            @endif

        </div>
    </div>

@endsection