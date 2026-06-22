@extends ('layouts.app')

@section('title', 'Buku Informasi Anggota - ASITA Jawa Barat')

@section('content')
    <section class="page-banner">
        <h1>Buku Informasi Anggota</h1>
        <p>Berisi informasi mengenai anggota kami</p>
    </section>

    <section class="search-katalog">
        <form action="{{ route('buku-anggota') }}" method="GET" class="search-box">
            <input type="text" name="search" placeholder="Cari Anggota..." value="{{ request('search') }}">
            <button type="submit" style="background: none; border: none; cursor: pointer;">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </section>

    <section class="buku-informasi-anggota">
        <div class="buku-informasi-anggota-content">
            @forelse($anggotas as $anggota)
                <a href="{{ route('detail-buku', $anggota->id) }}">
                    <div class="buku-card">
                        <img src="{{ $anggota->photo_url }}" alt="{{ $anggota->nama_usaha }}" loading="lazy">
                        <div class="container">
                            <h4><b>{{ $anggota->nama_usaha }}</b></h4>
                            <p>{{ Str::limit($anggota->nama_usaha_perusahaan ?? 'Perusahaan Tidak Disebutkan', 30, '...') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 40px;">
                    <p>Tidak ada anggota yang ditemukan.</p>
                    @if(request('search'))
                        <a href="{{ route('buku-anggota') }}" style="color: #007bff; text-decoration: underline;">
                            Tampilkan Semua Anggota
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Custom Pagination --}}
        @if($anggotas->hasPages())
            <div class="pagination-wrapper">
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($anggotas->onFirstPage())
                        <li class="disabled"><span>&laquo;</span></li>
                    @else
                        <li><a href="{{ $anggotas->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" rel="prev">&laquo;</a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $start = max($anggotas->currentPage() - 2, 1);
                        $end = min($start + 4, $anggotas->lastPage());
                        $start = max($end - 4, 1);
                    @endphp

                    @if($start > 1)
                        <li><a href="{{ $anggotas->url(1) }}&{{ http_build_query(request()->except('page')) }}">1</a></li>
                        @if($start > 2)
                            <li class="disabled"><span>...</span></li>
                        @endif
                    @endif

                    @for($page = $start; $page <= $end; $page++)
                        @if ($page == $anggotas->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $anggotas->url($page) }}&{{ http_build_query(request()->except('page')) }}">{{ $page }}</a></li>
                        @endif
                    @endfor

                    @if($end < $anggotas->lastPage())
                        @if($end < $anggotas->lastPage() - 1)
                            <li class="disabled"><span>...</span></li>
                        @endif
                        <li><a href="{{ $anggotas->url($anggotas->lastPage()) }}&{{ http_build_query(request()->except('page')) }}">{{ $anggotas->lastPage() }}</a></li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($anggotas->hasMorePages())
                        <li><a href="{{ $anggotas->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" rel="next">&raquo;</a></li>
                    @else
                        <li class="disabled"><span>&raquo;</span></li>
                    @endif
                </ul>
            </div>
        @endif
    </section>

    <style>
        /* Pagination Styling */
        .pagination-wrapper {
            margin-top: 40px;
            text-align: center;
            padding: 20px 0;
        }

        .pagination {
            display: inline-flex;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
            align-items: center;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 8px 14px;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 6px;
            color: #333;
            background-color: #fff;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        /* Active page */
        .pagination .active span {
            background-color: #0a3d2e;
            color: #fff;
            border-color: #0a3d2e;
            cursor: default;
        }

        /* Hover effect */
        .pagination a:hover {
            background-color: #0a3d2e;
            color: #fff;
            border-color: #0a3d2e;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(10, 61, 46, 0.2);
        }

        /* Disabled state */
        .pagination .disabled span {
            color: #ccc;
            cursor: not-allowed;
            background-color: #f5f5f5;
            border-color: #e0e0e0;
        }

        /* Previous and Next buttons */
        .pagination li:first-child a,
        .pagination li:first-child span,
        .pagination li:last-child a,
        .pagination li:last-child span {
            font-weight: 600;
            font-size: 18px;
        }

        /* Card Styling Enhancement */
        .buku-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
        }

        .buku-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .buku-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            object-position: center;
            background-color: #f0f0f0;
        }

        .buku-card .container {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .buku-card h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #0a3d2e;
        }

        .buku-card p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pagination a,
            .pagination span {
                min-width: 36px;
                height: 36px;
                padding: 6px 10px;
                font-size: 14px;
            }

            .pagination {
                gap: 4px;
            }
        }
    </style>
@endsection