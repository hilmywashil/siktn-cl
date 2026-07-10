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
            @if($organisasiByUrutan->isEmpty())
                <div style="text-align: center; padding: 50px 0; color: #6b7280;">
                    <p>Belum ada data struktur organisasi yang aktif.</p>
                </div>
            @else
                @foreach($organisasiByUrutan as $urutan => $anggotaList)
                    @php
                        $jabatanName = $anggotaList->first()->jabatan;
                    @endphp
                    <div class="grid-section">
                        <div class="grid-title">
                            <h2>{{ strtoupper($jabatanName) }}</h2>
                        </div>
                        <div class="{{ $anggotaList->count() == 1 ? 'grid-1' : 'grid-4' }}">
                            @foreach($anggotaList as $org)
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
                @endforeach
            @endif
        </div>
    </section>
@endsection