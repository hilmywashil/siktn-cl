@extends('layouts.app')

@section('title', 'Buku Informasi Anggota - Karang Taruna')

@section('hero-background', asset('assets-front/images/foto_5.png'))
@section('page-title', 'BUKU INFORMASI ANGGOTA')
@section('page-subtitle', 'Corps Alumni Akademi Ilmu Pelayaran')
@section('page-description', 'Daftar resmi alumni STIP Jakarta yang terdaftar dalam Karang Taruna.')
@section('hero-buttons', 'hide')

@push('styles')
    <style>
        /* ── Search Bar ── */
        .search-container {
            max-width: 1000px;
            margin: auto;
            position: relative;
            z-index: 10;
            padding: 0 20px;
        }

        .search-form {
            display: flex;
            background: #ffffff;
            border-radius: 50px;
            padding: 8px 8px 8px 24px;
            box-shadow: 0 15px 35px rgba(9, 11, 98, 0.08);
            border: 1px solid rgba(9, 11, 98, 0.08);
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .search-form:focus-within {
            border-color: #090B62;
            box-shadow: 0 15px 35px rgba(9, 11, 98, 0.15);
            transform: translateY(-2px);
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 0.95rem;
            color: #04293B;
            font-family: 'Google Sans', sans-serif;
            background: transparent;
        }

        .search-input::placeholder {
            color: #9CA3AF;
        }

        .search-btn {
            background: #090B62;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            font-family: 'Google Sans', sans-serif;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(9, 11, 98, 0.2);
        }

        .search-btn:hover {
            background: #FFE701;
            color: #000000;
            box-shadow: 0 4px 10px rgba(255, 231, 1, 0.3);
        }

        .reset-btn {
            background: #EF4444;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 12px 20px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Google Sans', sans-serif;
            transition: all 0.2s ease;
        }

        .reset-btn:hover {
            background: #DC2626;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(9, 11, 98, 0.05);
            width: 100%;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: rgba(9, 11, 98, 0.15);
            margin-bottom: 20px;
            display: block;
        }

        .empty-state h3 {
            font-weight: 700;
            color: #090B62;
            font-size: 1.4rem;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 0.95rem;
            color: #6B7280;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
        }
    </style>
@endpush

@section('content')
    @include('layouts.components.hero')

    {{-- SEARCH BAR --}}
    <section style="background-color: #F0F0F6; padding: 50px 0 0 0;">
        <div class="search-container" data-aos="fade-up">
            <form action="{{ route('buku-anggota') }}" method="GET" class="search-form">
                <i class="fas fa-search" style="color: #090B62; opacity: 0.5;"></i>
                <input type="text" name="search" class="search-input" value="{{ request('search') }}"
                    placeholder="Cari nama anggota atau angkatan...">
                @if(request()->filled('search'))
                    <a href="{{ route('buku-anggota') }}" class="reset-btn">Reset</a>
                @endif
                <button type="submit" class="search-btn">Cari</button>
            </form>
        </div>
    </section>

    {{-- GRID ANGGOTA --}}
    <section class="wrapper-white-1">
        <div class="anggota-section" data-aos="fade-up">

            {{-- Grid --}}
            @if($anggotas->count() > 0)
                <div class="grid">
                    @forelse($anggotas as $anggota)
                        <a href="{{ route('detail-buku', $anggota->id) }}">
                            <div class="item">
                                @if($anggota->foto_diri)
                                    <img src="{{ Storage::url($anggota->foto_diri) }}" alt="{{ $anggota->nama_lengkap }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($anggota->nama_lengkap) }}&background=090b62&color=fff&size=120"
                                        alt="{{ $anggota->nama_lengkap }}">
                                @endif
                                <div>
                                    <h3>{{ Str::words($anggota->nama_lengkap, 2, '') }}</h3>
                                    <p>Angkatan {{ $anggota->angkatan }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="item" style="grid-column: span 4; text-align: center; padding: 20px; color: #666; width: 100%;">
                            <p>Belum ada informasi anggota.</p>
                        </div>
                    @endforelse
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-user-slash"></i>
                    <h3>Anggota Tidak Ditemukan</h3>
                    <p>
                        @if(request()->filled('search'))
                            Tidak ada anggota yang cocok dengan kata kunci
                            "<strong>{{ request('search') }}</strong>".
                        @else
                            Belum ada anggota terdaftar saat ini.
                        @endif
                    </p>
                </div>
            @endif

        </div>
    </section>

    {{-- PAGINATION --}}
    @if($anggotas->hasPages())
        <section class="wrapper-pagination-white">
            <div class="pagination-section" data-aos="fade-up">
                {{ $anggotas->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </section>
    @endif
@endsection