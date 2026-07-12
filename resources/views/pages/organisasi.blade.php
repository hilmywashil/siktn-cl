@extends('layouts.app')

@section('title', 'Struktur Organisasi')

@section('hero-background', asset('assets-front/images/about_image.jpg'))
@section('page-title', 'STRUKTUR ORGANISASI SIKTN')
@section('page-description', 'Mengenal jajaran kepengurusan dan struktur organisasi SIKTN secara transparan dan profesional.')
@section('hero-buttons', 'hide')

@section('content')
    @include('layouts.components.hero')
    <section class="wrapper-white-1">
        <div class="organisasi-section" data-aos="fade-up">
            @if($ketuaUmum->count() > 0)
                <div class="grid-section">
                    <div class="grid-title">
                        <h2>Ketua Umum</h2>
                    </div>
                    <div class="grid-1">
                        @foreach($ketuaUmum as $org)
                            <a href="{{ route('organisasi.show', $org->nama) }}">
                                <div class="card">
                                    <img src="{{ $org->foto_url }}" alt="{{ $org->nama }}">
                                    <div>
                                        <h3>{{ Str::words($org->nama, 2, '') }}</h3>
                                        <p>{{ $org->jabatan }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if($wakilKetua->count() > 0)
                <div class="grid-section">
                    <div class="grid-title">
                        <h2>Ketua Umum</h2>
                    </div>
                    <div class="grid-1">
                        @foreach($wakilKetua as $org)
                            <a href="{{ route('organisasi.show', $org->nama) }}">
                                <div class="card">
                                    <img src="{{ $org->foto_url }}" alt="{{ $org->nama }}">
                                    <div>
                                        <h3>{{ Str::words($org->nama, 2, '') }}</h3>
                                        <p>{{ $org->jabatan }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($ketuaBidang->count() > 0)
                <div class="grid-section">
                    <div class="grid-title">
                        <h2>Ketua Bidang</h2>
                    </div>
                    <div class="grid-4">
                        @foreach($ketuaBidang as $org)
                            <a href="{{ route('organisasi.show', $org->nama) }}">
                                <div class="card">
                                    <img src="{{ $org->foto_url }}" alt="{{ $org->nama }}">
                                    <div>
                                        <h3>{{ Str::words($org->nama, 2, '') }}</h3>
                                        <p>{{ $org->jabatan }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($sekretarisUmum->count() > 0)
                <div class="grid-section">
                    <div class="grid-title">
                        <h2>Sekretaris Umum</h2>
                    </div>
                    <div class="grid-1">
                        @foreach($sekretarisUmum as $org)
                            <a href="{{ route('organisasi.show', $org->nama) }}">
                                <div class="card">
                                    <img src="{{ $org->foto_url }}" alt="{{ $org->nama }}">
                                    <div>
                                        <h3>{{ Str::words($org->nama, 2, '') }}</h3>
                                        <p>{{ $org->jabatan }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($pengurusLainnya->count() > 0)
                <div class="grid-section">
                    <div class="grid-title">
                        <h2>PENGURUS LAINNYA</h2>
                    </div>
                    <div class="grid-4">
                        @foreach($pengurusLainnya as $org)
                            <a href="{{ route('organisasi.show', $org->nama) }}">
                                <div class="card">
                                    <img src="{{ $org->foto_url }}" alt="{{ $org->nama }}">
                                    <div>
                                        <h3>{{ Str::words($org->nama, 2, '') }}</h3>
                                        <p>{{ $org->jabatan }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($ketuaUmum->isEmpty() && $ketuaBidang->isEmpty() && $sekretarisUmum->isEmpty() && $pengurusLainnya->isEmpty())
                <div style="text-align: center; padding: 50px 0; color: #6b7280;">
                    <p>Belum ada data struktur organisasi yang aktif.</p>
                </div>

            @endif
        </div>
    </section>
@endsection