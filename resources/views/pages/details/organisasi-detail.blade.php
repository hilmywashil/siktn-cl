@extends('layouts.app')

@section('title', $organisasi->nama)

@push('styles')
    <style>
        .container {
            width: 100%;
            max-width: 1300px;
            margin: auto;
            padding: 0 20px;
        }

        /* HERO */
        .hero {
            background: #022648;
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1fr 350px;
            min-height: 420px;
        }

        .hero-info {
            padding: 60px 50px 60px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-back {
            color: rgba(255, 255, 255, .5);
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .hero-back:hover {
            color: #FFF000;
        }

        .hero-name {
            color: white;
            font-size: 40px;
            margin: 0 0 10px;
        }

        .hero-sub {
            color: rgba(255, 255, 255, .6);
            margin-bottom: 30px;
        }

        .hero-contacts {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-row {
            color: white;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .contact-row i {
            color: #FFF000;
        }

        .hero-photo {
            overflow: hidden;
        }

        .hero-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #132d50;
            color: #FFF000;
            font-size: 70px;
            font-weight: bold;
        }

        .hero-bar {
            height: 5px;
            background: #FFF000;
        }

        /* BODY */
        .page-body {
            background: #f3f4f8;
            padding: 60px 20px;
        }

        .section-header {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 14px;
            color: #BB8D14;
            letter-spacing: 1px;
        }

        .section-header:after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ddd;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-key {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
        }

        .info-val {
            font-size: 18px;
            font-weight: 600;
            color: #022648;
        }

        .card-desc {
            line-height: 1.8;
            color: #555;
        }

        @media (max-width: 768px) {
            .hero-inner {
                grid-template-columns: 1fr;
            }

            .hero-photo {
                height: 300px;
            }

            .hero-info {
                padding: 40px 0;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .hero-name {
                font-size: 28px;
            }
        }
    </style>
@endpush

@section('content')

    <section class="hero">
        <div class="container">
            <div class="hero-inner">

                <div class="hero-info">
                    <a href="{{ route('organisasi') }}" class="hero-back">
                        <i class="fa fa-arrow-left"></i>
                        Kembali ke Struktur Organisasi
                    </a>

                    <h1 class="hero-name">{{ $organisasi->nama }}</h1>

                    <p class="hero-sub">{{ ucwords(str_replace('_', ' ', $organisasi->kategori)) }}</p>

                    <div class="hero-contacts">
                        <div class="contact-row">
                            <i class="fa fa-briefcase"></i>
                            <span>{{ $organisasi->jabatan }}</span>
                        </div>

                        <div class="contact-row">
                            <i class="fa fa-sort-numeric-up"></i>
                            <span>Urutan : {{ $organisasi->urutan ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="hero-photo">
                    @if($organisasi->foto)
                        <img src="{{ Storage::url($organisasi->foto) }}" alt="{{ $organisasi->nama }}">
                    @else
                        <div class="hero-placeholder">
                            {{ strtoupper(substr($organisasi->nama, 0, 2)) }}
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <div class="hero-bar"></div>
    </section>

    <div class="page-body">
        <div class="container">
            <div>
                <div class="section-header">
                    <h2>DETAIL ORGANISASI</h2>
                </div>

                <div class="card">
                    <div class="info-grid">
                        <div>
                            <div class="info-key">Nama</div>
                            <div class="info-val">{{ $organisasi->nama }}</div>
                        </div>

                        <div>
                            <div class="info-key">Jabatan</div>
                            <div class="info-val">{{ $organisasi->jabatan }}</div>
                        </div>

                        <div>
                            <div class="info-key">Kategori</div>
                            <div class="info-val">{{ ucwords(str_replace('_', ' ', $organisasi->kategori)) }}</div>
                        </div>

                        <div>
                            <div class="info-key">Status</div>
                            <div class="info-val">
                                @if($organisasi->aktif)
                                    Aktif
                                @else
                                    Tidak Aktif
                                @endif
                            </div>
                        </div>
                    </div>

                    <p class="card-desc">
                        Anggota organisasi <strong>{{ $organisasi->nama }}</strong>
                        menjabat sebagai <strong>{{ $organisasi->jabatan }}</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection