@extends ('layouts.app')

@section('title', 'Berita - ASITA Jawa Barat')

@section('content')

<section class="hero-howtojoins">
    <div class="hero-howtojoin" data-aos="fade-up">
        <h1>Article ASITA JABAR</h1>
        <p>Articles About ASITA JABAR</p>
    </div>
</section>

<section class="beritas">

    <div class="berita-content" data-aos="fade-up">

        @foreach($beritas as $b)
            <a href="{{ route('berita-detail', $b->slug) }}" style="text-decoration:none; color:inherit;">
                <div class="buku-card">
                    <img src="{{ $b->gambar_url }}" alt="Usaha Anggota 1" loading="lazy">
                    <div class="container">
                        <p>{{ $b->tanggal_format }}</p>
                        <h4>{{ Str::limit($b->judul, 60) }}</h4>
                        <p>Read More</p>
                    </div>
                </div>
            </a>
        @endforeach

    </div>

    {{-- Pagination --}}
    @if ($beritas->hasPages())
        <div style="margin-top:2.5rem; display:flex; justify-content:center; gap:8px; flex-wrap:wrap;">

            {{-- Prev --}}
            @if ($beritas->onFirstPage())
                <span style="padding:8px 14px; background:#eee; color:#999; border-radius:6px;">
                    Prev
                </span>
            @else
                <a href="{{ $beritas->previousPageUrl() }}"
                   style="padding:8px 14px; background:#0d6efd; color:#fff; text-decoration:none; border-radius:6px;">
                    Prev
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($beritas->getUrlRange(1, $beritas->lastPage()) as $page => $url)
                @if ($page == $beritas->currentPage())
                    <span style="padding:8px 14px; background:#212529; color:#fff; border-radius:6px;">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       style="padding:8px 14px; background:#f1f1f1; color:#000; text-decoration:none; border-radius:6px;">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($beritas->hasMorePages())
                <a href="{{ $beritas->nextPageUrl() }}"
                   style="padding:8px 14px; background:#0d6efd; color:#fff; text-decoration:none; border-radius:6px;">
                    Next
                </a>
            @else
                <span style="padding:8px 14px; background:#eee; color:#999; border-radius:6px;">
                    Next
                </span>
            @endif

        </div>
    @endif

</section>

@endsection