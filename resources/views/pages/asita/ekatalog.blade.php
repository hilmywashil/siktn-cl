@extends('layouts.app')

@section('title', 'E-Katalog - ASITA Jawa Barat')

@section('content')

    <section class="hero-abouts">
        <div class="hero-about" data-aos="fade-up">
            <h1>Showcase</h1>
            <p>Catalog of ASITA West Java Members</p>
        </div>
    </section>

    <section class="search-katalogs">
        <div class="search-katalog" data-aos="fade-up">
            <form action="{{ route('e-katalog') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Cari nama perusahaan atau bidang..."
                    value="{{ request('search') }}">
                <button type="submit" style="background: none; border: none; cursor: pointer;">
                </button>
            </form>
        </div>
    </section>

    <section class="showcase-wrappers">
        <div class="e-katalog-content" data-aos="fade-up">
            @forelse($katalogs as $katalog)
                <a href="{{ route('e-katalog.detail', $katalog->id) }}">
                    <div class="katalog-card">
                        <img src="{{ url('/uploads/storage/app/public/'.$katalog->logo) }}" alt="{{ $katalog->company_name }}">
                        <div class="container">
                            <h4><b>{{ Str::limit($katalog->company_name, 17, '-') }}</b></h4>
                            <p>{{ Str::limit($katalog->business_field, 30, '-') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #6b7280;">
                    <svg viewBox="0 0 24 24" style="width: 80px; height: 80px; margin: 0 auto 1rem; stroke: #d1d5db;"
                        fill="none" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <line x1="9" y1="9" x2="15" y2="9" />
                        <line x1="9" y1="15" x2="15" y2="15" />
                    </svg>
                    <h3>{{ request('search') ? 'Tidak ada hasil pencarian' : 'Belum ada katalog tersedia' }}</h3>
                    <p>{{ request('search') ? 'Coba kata kunci lain' : 'Silakan cek kembali nanti' }}</p>
                </div>
            @endforelse
        </div>
    </section>

    @if($katalogs->hasPages())
        <div class="custom-pagination">
            {{ $katalogs->links('pagination::semantic-ui') }}
        </div>
    @endif

@endsection