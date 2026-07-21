@extends('layouts.app')

@section('title', $katalog->company_name )

@push('styles')
    <style>
        
        /* ── Banner ── */
        .detail-banner {
            background-color: #022648;
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 80px 20px;
        }

        .detail-banner-inner {
            width: 100%;
            max-width: 1300px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
        }

        .detail-banner-inner .info h1 {
            font-size: 38px;
            font-weight: 700;
            color: #fff000;
            margin: 0 0 12px;
            text-transform: uppercase;
        }

        .detail-banner-inner .info p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0 0 24px;
        }

        .detail-banner-inner .logo {
            flex-shrink: 0;
            background: #fff;
            border-radius: 12px;
            padding: 24px 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-banner-inner .logo img {
            width: 180px;
            max-width: 180px;
            height: auto;
            object-fit: contain;
        }

        /* ── Description ── */
        .detail-desc-section {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1300px;
            gap: 20px;
        }

        .detail-desc-section h2 {
            margin: 0;
            font-size: 22px;
            color: #022648;
            font-weight: 700;
            text-transform: uppercase;
        }

        .detail-desc-section p {
            font-size: 15px;
            color: #444;
            line-height: 1.9;
            margin: 0;
        }

        /* ── Gallery ── */
        .detail-gallery-section {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1300px;
            gap: 24px;
        }

        .detail-gallery-section h2 {
            margin: 0;
            font-size: 22px;
            color: #022648;
            font-weight: 700;
            text-transform: uppercase;
        }

        .detail-gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .detail-gallery-grid img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .detail-gallery-grid img:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        /* ── Contact ── */
        .detail-contact-section {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1300px;
            gap: 24px;
        }

        .detail-contact-section h2 {
            margin: 0;
            font-size: 22px;
            color: #022648;
            font-weight: 700;
            text-transform: uppercase;
        }

        .detail-contact-body {
            display: flex;
            flex-direction: row;
            gap: 50px;
            align-items: stretch;
        }

        .detail-contact-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            flex: 1;
        }

        .detail-contact-item {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 16px;
        }

        .detail-contact-item i {
            font-size: 18px;
            color: #022648;
            margin-top: 2px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .detail-contact-item p {
            font-size: 14px;
            color: #000;
            margin: 0;
            line-height: 1.6;
        }

        .detail-map {
            flex: 1.5;
            min-height: 280px;
            border-radius: 10px;
            overflow: hidden;
        }

        .detail-map iframe {
            width: 100%;
            height: 100%;
            min-height: 280px;
            border: none;
            display: block;
        }

        /* ── Back button ── */
        .detail-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: color 0.2s;
            margin-bottom: 8px;
        }

        .detail-back:hover {
            color: #FFE701;
        }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .detail-gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .detail-contact-body {
                flex-direction: column;
                gap: 30px;
            }
        }

        @media (max-width: 768px) {
            .detail-banner {
                padding: 50px 16px;
            }

            .detail-banner-inner {
                flex-direction: column-reverse;
                align-items: flex-start;
                gap: 30px;
            }

            .detail-banner-inner .info h1 {
                font-size: 26px;
            }

            .detail-banner-inner .logo {
                align-self: flex-start;
                padding: 16px 24px;
            }

            .detail-banner-inner .logo img {
                width: 120px;
            }

            .detail-gallery-grid {
                grid-template-columns: 1fr;
            }

            .detail-gallery-grid img {
                height: 200px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- BANNER --}}
    <div class="detail-banner">
        <div class="detail-banner-inner" data-aos="fade-up">
            <div class="info">
                <a href="{{ route('e-katalog') }}" class="detail-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke E-Katalog
                </a>
                <h1>{{ $katalog->company_name }}</h1>
                <p>{{ $katalog->business_field }}</p>
            </div>
            <div class="logo">
                <img src="{{ $katalog->logo_url }}" alt="{{ $katalog->company_name }}">
            </div>
        </div>
    </div>

    {{-- DESKRIPSI --}}
    @if($katalog->description)
        <section class="wrapper-white-1">
            <div class="detail-desc-section" data-aos="fade-up">
                <h2>Tentang Perusahaan</h2>
                <p>{{ $katalog->description }}</p>
            </div>
        </section>
    @endif

    {{-- INFO LEBIH LANJUT --}}
    @if($katalog->kategori || $katalog->harga || $katalog->wilayah || $katalog->website_url || $katalog->marketplace_url)
    <section class="wrapper-white-1" style="padding-top: 0;">
        <div class="detail-desc-section" data-aos="fade-up">
            <h2>Informasi Produk & Harga</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                @if($katalog->kategori)
                <div style="background: #f9fafb; padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Kategori</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #022648; margin-top: 0.25rem;">{{ $katalog->kategori->nama }}</div>
                </div>
                @endif
                @if($katalog->harga)
                <div style="background: #f9fafb; padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Harga</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #022648; margin-top: 0.25rem;">{{ $katalog->harga }}</div>
                </div>
                @endif
                @if($katalog->wilayah)
                <div style="background: #f9fafb; padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase;">Wilayah</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #022648; margin-top: 0.25rem;">{{ $katalog->wilayah }}</div>
                </div>
                @endif
            </div>
            @if($katalog->website_url || $katalog->marketplace_url)
            <div style="display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
                @if($katalog->website_url)
                <a href="{{ $katalog->website_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #022648; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                    <i class="fas fa-globe"></i> Kunjungi Website
                </a>
                @endif
                @if($katalog->marketplace_url)
                <a href="{{ $katalog->marketplace_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #10B981; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.875rem;">
                    <i class="fas fa-shopping-bag"></i> Beli di Marketplace
                </a>
                @endif
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- GALERI --}}
    @if($katalog->images && count($katalog->images) > 0)
        <section class="wrapper-white-1" style="padding-top: 0;">
            <div class="detail-gallery-section" data-aos="fade-up">
                <h2>Galeri</h2>
                <div class="detail-gallery-grid">
                    @foreach($katalog->images_url as $imageUrl)
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $katalog->company_name }}" loading="lazy">
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- KONTAK & MAP --}}
    <section class="wrapper-lightblue">
        <div class="detail-contact-section" data-aos="fade-up">
            <h2>Informasi Kontak</h2>
            <div class="detail-contact-body">
                <div class="detail-contact-list">
                    @if($katalog->address)
                        <div class="detail-contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <p>{{ $katalog->address }}</p>
                        </div>
                    @endif
                    @if($katalog->phone)
                        <div class="detail-contact-item">
                            <i class="fas fa-phone"></i>
                            <p>{{ $katalog->phone }}</p>
                        </div>
                    @endif
                    @if($katalog->email)
                        <div class="detail-contact-item">
                            <i class="fas fa-envelope"></i>
                            <p>{{ $katalog->email }}</p>
                        </div>
                    @endif
                </div>

                @if($katalog->map_embed_url)
                    <div class="detail-map">
                        <iframe src="{{ $katalog->map_embed_url }}" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection