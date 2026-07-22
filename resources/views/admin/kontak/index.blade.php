@extends('admin.layouts.admin-layout')

@section('title', 'Direktori Kontak Pengurus & Anggota')
@section('page-title', 'Direktori Kontak')

@push('styles')
<style>
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0a2540;
    }

    .form-control {
        padding: 0.625rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s;
        width: 100%;
    }

    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%230a2540' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
        padding-right: 2.5rem;
        background-color: #ffffff;
        cursor: pointer;
    }

    .form-control:focus {
        border-color: #0a2540;
        box-shadow: 0 0 0 3px rgba(10, 37, 64, 0.1);
    }

    .btn-submit {
        background: #0a2540;
        color: white;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-submit:hover {
        background: #164e63;
    }

    .btn-reset {
        background: #f3f4f6;
        color: #4b5563;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-reset:hover {
        background: #e5e7eb;
        color: #1f2937;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .contact-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        border: 1px solid #f3f4f6;
    }

    .contact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    .avatar-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: 1rem;
        border: 3px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        font-size: 2rem;
        font-weight: 700;
        color: #0a2540;
    }

    .contact-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .contact-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #e0f2fe;
        color: #0369a1;
        margin-bottom: 0.75rem;
    }

    .contact-info {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #4b5563;
        margin-bottom: 1.25rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f3f4f6;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        word-break: break-all;
    }

    .info-item svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        color: #64748b;
    }

    .contact-actions {
        width: 100%;
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
    }

    .btn-wa {
        flex: 1;
        background: #25d366;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        transition: background 0.2s;
    }

    .btn-wa:hover {
        background: #1da851;
        color: white;
    }

    .btn-email {
        background: #f1f5f9;
        color: #334155;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .btn-email:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 3rem;
        text-align: center;
        color: #64748b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
{{-- Filter Box --}}
<div class="filter-card">
    <form method="GET" action="{{ route('admin.kontak.index') }}">
        <div class="filter-grid">
            <div class="form-group">
                <label for="search">Cari Nama / Email / HP</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
            </div>

            <div class="form-group">
                <label for="domisili">Wilayah / Domisili</label>
                <select name="domisili" id="domisili" class="form-control select2-basic">
                    <option value="">-- Semua Wilayah --</option>
                    @foreach($domisiliList as $d)
                        <option value="{{ $d }}" {{ request('domisili') == $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="jabatan">Jabatan</label>
                <select name="jabatan" id="jabatan" class="form-control select2-basic">
                    <option value="">-- Semua Jabatan --</option>
                    @foreach($jabatanList as $j)
                        <option value="{{ $j }}" {{ request('jabatan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button type="submit" class="btn-submit">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filter
                </button>
                <a href="{{ route('admin.kontak.export', request()->query()) }}" class="btn-submit" style="background: #10b981; text-decoration: none;" title="Export Data Kontak ke Excel" onclick="Toast.fire({ icon: 'success', title: 'File Excel Direktori Kontak sedang diunduh...' })">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export Excel
                </a>
                @if(request()->anyFilled(['search', 'domisili', 'jabatan']))
                    <a href="{{ route('admin.kontak.index') }}" class="btn-reset">Reset</a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- Contact Grid --}}
@if($kontaks->count() > 0)
    <div class="contact-grid">
        @foreach($kontaks as $k)
            @php
                // Format No. WhatsApp (ubah 08xx jadi 628xx)
                $waNumber = preg_replace('/[^0-9]/', '', $k->no_hp ?? '');
                if (str_starts_with($waNumber, '0')) {
                    $waNumber = '62' . substr($waNumber, 1);
                }
            @endphp
            <div class="contact-card">
                <div class="avatar-wrapper">
                    @if($k->foto_diri && Storage::disk('public')->exists($k->foto_diri))
                        <img src="{{ asset('storage/' . $k->foto_diri) }}" alt="{{ $k->nama_lengkap }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($k->nama_lengkap ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="contact-name">{{ $k->nama_lengkap ?? 'Anggota' }}</div>
                <div class="contact-badge">{{ $k->jabatan ?? 'Anggota Karang Taruna' }}</div>

                <div class="contact-info">
                    <div class="info-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>{{ $k->domisili ?? 'Nasional' }}</span>
                    </div>

                    @if($k->email)
                    <div class="info-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <span>{{ $k->email }}</span>
                    </div>
                    @endif

                    @if($k->no_hp)
                    <div class="info-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>{{ $k->no_hp }}</span>
                    </div>
                    @endif
                </div>

                <div class="contact-actions">
                    @if($waNumber)
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn-wa">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.144 4.177 4.287-1.124z"/></svg>
                            WhatsApp
                        </a>
                    @endif
                    @if($k->email)
                        <a href="mailto:{{ $k->email }}" class="btn-email" title="Kirim Email">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $kontaks->links() }}
    </div>
@else
    <div class="empty-state">
        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 1rem; color: #94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <h3>Tidak ada data kontak ditemukan</h3>
        <p>Coba ubah kata kunci pencarian atau filter domisili/jabatan.</p>
    </div>
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-basic').select2({
            minimumResultsForSearch: 5,
            width: '100%'
        });
    });
</script>
@endpush
