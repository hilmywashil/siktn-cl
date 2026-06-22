@extends ('layouts.app')

@section('title', 'Informasi Kegiatan - ASITA Jawa Barat')

@section('content')
    <section class="page-banner">
        <h1>Informasi Kegiatan BPD</h1>
        <p>Anggota & Pengurus ASITA Jawa Barat</p>
    </section>

    <section class="search-katalog">
        <form action="{{ route('e-katalog') }}" method="GET" class="search-box">
            <input type="text" name="search" placeholder="Cari Kegiatan..." value="{{ request('search') }}">
            <button type="submit" style="background: none; border: none; cursor: pointer;">
            </button>
        </form>
    </section>

    <section class="informasi-kegiatan">
        <div class="informasi-kegiatan-cards">
            <div class="informasi-kegiatan-card">
                <a href="{{ route('detail-kegiatan') }}" class="informasi-kegiatan-card-image">
                    <img src="{{ asset('images/bg.png') }}" alt="dada">
                </a>
                <div class="informasi-kegiatan-card-text">
                    <h3>Visi dan Misi ASITA Jawa Barat</h3>
                    <p style="margin-bottom: 15px">Oktober 28, 2070</p>
                    <p>{{ Str::limit('Isi berita Lorem ipsum dolor sit amet, consectetur
                                                                    adipiscing elit. Donec ullamcorper, est a
                                                                    scelerisque
                                                                    semper', 150, '...') }}
                    </p>

                </div>
                <a href="{{ route('detail-kegiatan') }}"" class=" info-kegiatan-btn-more">Baca Selengkapnya</a>

            </div>
            <div class="informasi-kegiatan-card">
                <a href="{{ route('detail-kegiatan') }}" class="informasi-kegiatan-card-image">
                    <img src="{{ asset('images/bg.png') }}" alt="dada">
                </a>
                <div class="informasi-kegiatan-card-text">
                    <h3>Visi dan Misi ASITA Jawa Barat</h3>
                    <p style="margin-bottom: 15px">Oktober 28, 2070</p>
                    <p>{{ Str::limit('Isi berita Lorem ipsum dolor sit amet, consectetur
                                                                    adipiscing elit. Donec ullamcorper, est a
                                                                    scelerisque
                                                                    semper', 150, '...') }}
                    </p>

                </div>
                <a href="{{ route('detail-kegiatan') }}"" class=" info-kegiatan-btn-more">Baca Selengkapnya</a>

            </div>
            <div class="informasi-kegiatan-card">
                <a href="{{ route('detail-kegiatan') }}" class="informasi-kegiatan-card-image">
                    <img src="{{ asset('images/bg.png') }}" alt="dada">
                </a>
                <div class="informasi-kegiatan-card-text">
                    <h3>Visi dan Misi ASITA Jawa Barat</h3>
                    <p style="margin-bottom: 15px">Oktober 28, 2070</p>
                    <p>{{ Str::limit('Isi berita Lorem ipsum dolor sit amet, consectetur
                                                                    adipiscing elit. Donec ullamcorper, est a
                                                                    scelerisque
                                                                    semper', 150, '...') }}
                    </p>

                </div>
                <a href="{{ route('detail-kegiatan') }}"" class=" info-kegiatan-btn-more">Baca Selengkapnya</a>

            </div>
            <div class="informasi-kegiatan-card">
                <a href="{{ route('detail-kegiatan') }}" class="informasi-kegiatan-card-image">
                    <img src="{{ asset('images/bg.png') }}" alt="dada">
                </a>
                <div class="informasi-kegiatan-card-text">
                    <h3>Visi dan Misi ASITA Jawa Barat</h3>
                    <p style="margin-bottom: 15px">Oktober 28, 2070</p>
                    <p>{{ Str::limit('Isi berita Lorem ipsum dolor sit amet, consectetur
                                                                    adipiscing elit. Donec ullamcorper, est a
                                                                    scelerisque
                                                                    semper', 150, '...') }}
                    </p>

                </div>
                <a href="{{ route('detail-kegiatan') }}"" class=" info-kegiatan-btn-more">Baca Selengkapnya</a>

            </div>
        </div>
    </section>
@endsection