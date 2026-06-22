@extends('layouts.app')

@section('title', 'Organisasi - ASITA Jawa Barat')

@section('content')
    <section class="page-banner">
        <h1>Struktur Organisasi</h1>
        <p>Struktur Organisasi BPD ASITA Jawa Barat</p>
    </section>
    <section class="organisasi">
        {{-- Ketua Umum --}}
        @php
            $ketuaUmum = \App\Models\Organisasi::aktif()->kategori('ketua_umum')->ordered()->first();
        @endphp
        @if($ketuaUmum)
            <div class="organisasi-layout1">
                <div class="green-accent" style="align-self: center;"></div>
                <h2 class="org-heading">Ketua Umum</h2>
                <div class="organisasi-card" onclick="showModal('{{ addslashes($ketuaUmum->nama) }}', '{{ addslashes($ketuaUmum->jabatan) }}', '{{ $ketuaUmum->kategori_label }}', '{{ $ketuaUmum->foto_url }}')">
                    <img src="{{ $ketuaUmum->foto_url }}" alt="{{ $ketuaUmum->nama }}">
                    <div class="container">
                        <h4><b>{{ Str::limit($ketuaUmum->nama, 20, '...') }}</b></h4>
                        <p>{{ $ketuaUmum->jabatan }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Wakil Ketua Umum --}}
        @php
            $wakilKetuaUmum = \App\Models\Organisasi::aktif()->kategori('wakil_ketua_umum')->ordered()->get();
        @endphp
        @if($wakilKetuaUmum->count() > 0)
            <div class="organisasi-layout1">
                <div class="green-accent" style="align-self: center; background-color: #4287f5"></div>
                <h2 class="org-heading">Wakil Ketua Umum</h2>
                @foreach($wakilKetuaUmum as $wakil)
                    <div class="organisasi-card" onclick="showModal('{{ addslashes($wakil->nama) }}', '{{ addslashes($wakil->jabatan) }}', '{{ $wakil->kategori_label }}', '{{ $wakil->foto_url }}')">
                        <img src="{{ $wakil->foto_url }}" alt="{{ $wakil->nama }}">
                        <div class="container">
                            <h4><b>{{ Str::limit($wakil->nama, 20, '...') }}</b></h4>
                            <p>{{ $wakil->jabatan }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Ketua Bidang --}}
        @php
            $ketuaBidang = \App\Models\Organisasi::aktif()->kategori('ketua_bidang')->ordered()->get();
        @endphp
        @if($ketuaBidang->count() > 0)
            <div class="organisasi-layout2">
                <div class="green-accent" style="align-self: center; background-color: #ec1846"></div>
                <h2 class="org-heading">Ketua Bidang - Bidang</h2>
                <div class="organisasi-layout2-content">
                    @foreach($ketuaBidang as $bidang)
                        <div class="organisasi-card" onclick="showModal('{{ addslashes($bidang->nama) }}', '{{ addslashes($bidang->jabatan) }}', '{{ $bidang->kategori_label }}', '{{ $bidang->foto_url }}')">
                            <img src="{{ $bidang->foto_url }}" alt="{{ $bidang->nama }}">
                            <div class="container">
                                <h4><b>{{ Str::limit($bidang->nama, 20, '...') }}</b></h4>
                                <p>{{ $bidang->jabatan }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Sekretaris Umum --}}
        @php
            $sekretarisUmum = \App\Models\Organisasi::aktif()->kategori('sekretaris_umum')->ordered()->first();
        @endphp
        @if($sekretarisUmum)
            <div class="organisasi-layout1">
                <div class="green-accent" style="align-self: center;"></div>
                <h2 class="org-heading">Sekretaris Umum</h2>
                <div class="organisasi-card" onclick="showModal('{{ addslashes($sekretarisUmum->nama) }}', '{{ addslashes($sekretarisUmum->jabatan) }}', '{{ $sekretarisUmum->kategori_label }}', '{{ $sekretarisUmum->foto_url }}')">
                    <img src="{{ $sekretarisUmum->foto_url }}" alt="{{ $sekretarisUmum->nama }}">
                    <div class="container">
                        <h4><b>{{ Str::limit($sekretarisUmum->nama, 20, '...') }}</b></h4>
                        <p>{{ $sekretarisUmum->jabatan }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Wakil Sekretaris Umum --}}
        @php
            $wakilSekretarisUmum = \App\Models\Organisasi::aktif()->kategori('wakil_sekretaris_umum')->ordered()->get();
        @endphp
        @if($wakilSekretarisUmum->count() > 0)
            <div class="organisasi-layout2">
                <div class="green-accent" style="align-self: center; background-color: #4287f5"></div>
                <h2 class="org-heading">Wakil Sekretaris Umum</h2>
                <div class="organisasi-layout2-content">
                    @foreach($wakilSekretarisUmum as $wakilSekretaris)
                        <div class="organisasi-card" onclick="showModal('{{ addslashes($wakilSekretaris->nama) }}', '{{ addslashes($wakilSekretaris->jabatan) }}', '{{ $wakilSekretaris->kategori_label }}', '{{ $wakilSekretaris->foto_url }}')">
                            <img src="{{ $wakilSekretaris->foto_url }}" alt="{{ $wakilSekretaris->nama }}">
                            <div class="container">
                                <h4><b>{{ Str::limit($wakilSekretaris->nama, 20, '...') }}</b></h4>
                                <p>{{ $wakilSekretaris->jabatan }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    {{-- Modal Popup --}}
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            
            {{-- Header dengan Logo --}}
            <div class="modal-header">
                <div class="modal-header-content">
                    <img src="{{ asset('images/hipmi-logo.png') }}" alt="ASITA Logo" class="modal-logo">
                    <div class="modal-header-text">
                        <div class="modal-org-name">ASITA</div>
                        <div class="modal-org-region">JAWA BARAT</div>
                    </div>
                </div>
            </div>

            {{-- Diagonal Split Design --}}
            <div class="modal-photo-container">
                <div class="modal-diagonal-bg"></div>
                <div class="modal-photo-circle">
                    <img id="modalPhoto" class="modal-photo" src="" alt="">
                </div>
            </div>

            {{-- Body Content --}}
            <div class="modal-body">
                <h2 id="modalNama" class="modal-nama"></h2>
                <p id="modalJabatan" class="modal-jabatan"></p>
            </div>
        </div>
    </div>
    <script>
        function showModal(nama, jabatan, kategori, foto) {
            document.getElementById('modalPhoto').src = foto;
            document.getElementById('modalNama').textContent = nama;
            document.getElementById('modalJabatan').textContent = jabatan;
            document.getElementById('modalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(event) {
            if (event && event.target !== document.getElementById('modalOverlay')) {
                return;
            }
            document.getElementById('modalOverlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
@endsection