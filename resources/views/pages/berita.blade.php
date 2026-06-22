@extends('layouts.app')

@section('title', 'Berita')

@section('hero-background', asset('assets-front/images/hero_bg.jpg'))
@section('page-title', 'INFORMASI DAN KEGIATAN TERBARU')
@section('page-subtitle', 'Corps Alumni Akademi Ilmu Pelayaran')
@section('page-description', 'Berita organisasi, program kerja, kegiatan sosial, dan perkembangan terbaru anggota SIKTN.')
@section('hero-buttons', 'hide')

@section('content')
    @include('layouts.components.hero')
    <section class="wrapper-white-1">
        <div class="berita-page-section" data-aos="fade-up">
            <div class="left">
                @forelse($beritas as $item)
                    <div class="item">
                        <div class="image">
                            <a href="{{ route('berita-detail', $item->slug) }}">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}">
                            </a>
                        </div>
                        <div class="content">
                            <a href="{{ route('berita-detail', $item->slug) }}">
                                <h2>{{ $item->judul }}</h2>
                            </a>
                            <p class="date">{{ $item->tanggal_format }}</p>
                            <p>{{ Str::limit(strip_tags($item->konten), 200) }}</p>
                            <a href="{{ route('berita-detail', $item->slug) }}" class="btn">Baca Selengkapnya <i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: #6b7280; width: 100%;">
                        <p>Belum ada berita yang dipublikasikan.</p>
                    </div>
                @endforelse
            </div>
            <div class="right">
                <a href="#" class="btn-navy">BERITA POPULER</a>
                <hr class="divider-line">
                @forelse($beritaPopuler as $item)
                    <div class="item">
                        <div class="content">
                            <a href="{{ route('berita-detail', $item->slug) }}">
                                <h2>{{ $item->judul }}</h2>
                            </a>
                            <p class="date">{{ $item->tanggal_format }}</p>
                            <p>{{ Str::limit(strip_tags($item->konten), 150) }}</p>
                            <a href="{{ route('berita-detail', $item->slug) }}" class="btn">Baca Selengkapnya <i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <hr class="divider-line">
                @empty
                    <div style="padding: 20px; text-align: center; color: #6b7280;">
                        <p>Belum ada berita populer.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    @if($beritas->hasPages())
        <section class="wrapper-pagination-white">
            <div class="pagination-section" data-aos="fade-up">
                {{ $beritas->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </section>
    @endif
@endsection