@extends('admin.layouts.admin-layout')

@section('title', 'Notulensi Rapat - SIKTN Admin')
@section('page-title', 'Sekretariat - Notulensi Rapat')

@push('styles')
<style>
    @keyframes select2DropdownFadeIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .select2-container--default .select2-selection--single {
        height: 40px; padding: 0.35rem 0.75rem; font-size: 0.8125rem; font-weight: 600;
        color: var(--navy); background-color: #fff; border: 1px solid var(--gray-300);
        border-radius: var(--radius-md); display: flex; align-items: center;
        transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    }
    .select2-dropdown {
        border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-size: 0.8125rem;
        z-index: 9999; box-shadow: 0 12px 28px rgba(2,38,72,0.15); margin-top: 4px; overflow: hidden;
    }
    .select2-container--open .select2-dropdown {
        animation: select2DropdownFadeIn 0.2s cubic-bezier(0.16,1,0.3,1) forwards;
    }
    .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #022648 !important; color: #ffffff !important; font-weight: 600 !important;
    }

    .admin-ui-scope {
        --navy: #022648; --navy-dark: #01162f; --navy-light: #0a3a6b;
        --gold: #b7830f; --green: #059669; --blue: #2563eb; --red: #dc2626; --amber: #d97706;
        --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-300: #d1d5db;
        --gray-500: #6b7280; --gray-700: #374151; --gray-900: #111827;
        --radius-sm: 4px; --radius-md: 6px; --radius-lg: 8px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Stat Cards Benchmark */
    .stat-cards-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem; margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #fff; border-radius: var(--radius-lg); padding: 1.25rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(2,38,72,0.05);
        display: flex; align-items: center; gap: 1rem; position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--navy);
    }
    .stat-card.internal-card::before { background: var(--blue); }
    .stat-card.pleno-card::before   { background: var(--gold); }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; background: var(--gray-100); color: var(--navy);
    }
    .stat-info h4 {
        margin: 0; font-size: 0.75rem; color: var(--gray-500);
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .stat-info .value {
        font-size: 1.6rem; font-weight: 800; color: var(--navy); margin-top: 0.2rem;
        font-family: 'Montserrat', sans-serif;
    }

    /* Buttons */
    .btn-solid-navy {
        background: var(--navy); color: white; padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem;
        border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;
        text-decoration: none; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 2px 4px rgba(2,38,72,0.12);
    }
    .btn-solid-navy:hover { background: var(--navy-light); color: white; transform: translateY(-1px); }
    .btn-outline-secondary {
        background: white; color: var(--gray-700); padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem;
        border: 1px solid var(--gray-300); cursor: pointer; display: inline-flex;
        align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease;
    }
    .btn-outline-secondary:hover { background: var(--gray-100); color: var(--navy); }

    /* Filter Card */
    .filter-card {
        background: white; border-radius: var(--radius-lg); padding: 1.25rem 1.5rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 1.5rem;
    }
    .filter-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem; align-items: flex-end;
    }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group label { font-size: 0.85rem; font-weight: 700; color: var(--navy); }
    .form-control {
        padding: 0.55rem 0.875rem; border-radius: var(--radius-md);
        border: 1px solid var(--gray-300); font-size: 0.875rem; outline: none;
        background: white; transition: all 0.2s ease; width: 100%;
    }
    .form-control:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(2,38,72,0.1); }

    /* Page Actions Row */
    .page-actions-row {
        display: flex; justify-content: flex-end; margin-bottom: 1.25rem; gap: 0.75rem;
    }

    /* Table Container */
    .table-container { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
    .table-wrapper { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
    .table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--gray-700); text-transform: uppercase; letter-spacing: 0.05em; }
    .table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); font-size: 0.875rem; color: var(--gray-900); vertical-align: middle; }
    .table tbody tr:hover { background: var(--gray-50); }

    /* Action Trigger (⋮) */
    .aksi-wrapper { position: relative; display: inline-block; }
    .btn-aksi-trigger {
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
        background: var(--navy); color: #ffffff; border: none; border-radius: var(--radius-md);
        cursor: pointer; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    }
    .btn-aksi-trigger:hover { background: var(--navy-light); transform: scale(1.08) translateY(-1px); }
    .aksi-dropdown {
        display: block; position: fixed; min-width: 175px; background: #fff;
        border: 1px solid var(--gray-200); border-radius: var(--radius-md);
        box-shadow: 0 14px 32px rgba(2,38,72,0.18); padding: 6px; z-index: 9999;
        opacity: 0; visibility: hidden; transform: translateY(-8px) scale(0.96);
        transition: opacity 0.18s cubic-bezier(0.16,1,0.3,1), transform 0.18s cubic-bezier(0.16,1,0.3,1), visibility 0.18s;
        pointer-events: none;
    }
    .aksi-dropdown.is-open { opacity: 1; visibility: visible; transform: translateY(0) scale(1); pointer-events: auto; }
    .aksi-item {
        display: flex; align-items: center; gap: 9px; width: 100%;
        padding: 0.55rem 0.65rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: var(--radius-sm); color: var(--gray-900); text-decoration: none !important;
        border: none; background: transparent; text-align: left; cursor: pointer;
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
    }
    .aksi-item:hover { background: var(--gray-100); transform: translateX(4px); }
    .aksi-item.aksi-edit:hover  { color: var(--blue); }
    .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }
    .aksi-divider { height: 1px; background: var(--gray-200); margin: 4px 0; }

    /* Modal */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15,23,42,0.6); display: none;
        align-items: center; justify-content: center; z-index: 9999; padding: 1rem;
    }
    .modal-overlay.active { display: flex; }
    .modal-content {
        background: white; border-radius: var(--radius-lg); max-width: 560px; width: 100%;
        padding: 1.5rem; max-height: 90vh; overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Total Notulensi</h4>
                <div class="value">{{ $notulensis->total() }}</div>
            </div>
        </div>

        <div class="stat-card internal-card">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Rapat Internal</h4>
                <div class="value" style="color: var(--blue);">{{ $notulensis->whereNull('agenda_id')->count() }}</div>
            </div>
        </div>

        <div class="stat-card pleno-card">
            <div class="stat-icon" style="background: #fefce8; color: var(--gold);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Terkait Agenda</h4>
                <div class="value" style="color: var(--gold);">{{ $notulensis->whereNotNull('agenda_id')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Box -->
    <div class="filter-card">
        <form action="{{ route('admin.sekretariat.notulensi.index') }}" method="GET">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari Judul Rapat / Pemimpin</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.sekretariat.notulensi.index') }}" class="btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Page Action -->
    <div class="page-actions-row">
        <button type="button" class="btn-solid-navy" onclick="openCreateModal()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Notulensi Baru
        </button>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>JUDUL RAPAT</th>
                        <th>TAUTAN AGENDA</th>
                        <th>TANGGAL & WAKTU</th>
                        <th>PEMIMPIN RAPAT</th>
                        <th>DOKUMEN (DRIVE)</th>
                        <th style="text-align: center; width: 80px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notulensis as $item)
                    <tr>
                        <td>
                            <strong style="color: var(--navy);">{{ $item->judul_rapat }}</strong>
                            @if($item->ringkasan_hasil)
                                <div style="font-size: 0.8rem; color: var(--gray-500); margin-top: 2px;">{{ Str::limit($item->ringkasan_hasil, 80) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($item->agenda)
                                <span style="font-size: 0.8rem; padding: 3px 8px; background: #e0f2fe; color: #0369a1; border-radius: 6px; font-weight: 600; display: inline-block;">
                                    {{ $item->agenda->judul }}
                                </span>
                            @else
                                <span style="color: var(--gray-500); font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_rapat)->format('d M Y, H:i') }} WIB</td>
                        <td>{{ $item->pemimpin_rapat ?? '-' }}</td>
                        <td>
                            @if($item->link_drive)
                                <a href="{{ $item->link_drive }}" target="_blank" style="color: var(--blue); text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    Buka Drive
                                </a>
                            @else
                                <span style="color: var(--gray-500); font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <!-- Action Dropdown Trigger (⋮) -->
                            <div class="aksi-wrapper">
                                <button type="button" class="btn-aksi-trigger" data-target="dropdown-not-{{ $item->id }}" aria-label="Menu Aksi">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.75"></circle>
                                        <circle cx="12" cy="12" r="1.75"></circle>
                                        <circle cx="12" cy="19" r="1.75"></circle>
                                    </svg>
                                </button>

                                <div class="aksi-dropdown" id="dropdown-not-{{ $item->id }}">
                                    <button type="button" class="aksi-item aksi-edit" onclick="openEditModal({{ json_encode($item) }})">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit Notulensi
                                    </button>

                                    <div class="aksi-divider"></div>

                                    <button type="button" class="aksi-item aksi-delete" onclick="confirmDeleteNotulensi({{ $item->id }}, '{{ addslashes($item->judul_rapat) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus Notulensi
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.sekretariat.notulensi.destroy', $item->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: var(--gray-500);">Belum ada data Notulensi Rapat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 1rem;">
        {{ $notulensis->links() }}
    </div>

</div>

<!-- Create Modal -->
<div class="modal-overlay" id="createModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: var(--navy); margin: 0; font-weight: 700;">Tambah Notulensi Rapat Baru</h3>
            <button type="button" onclick="closeCreateModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--gray-500);">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.notulensi.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Tautkan ke Agenda / Rapat (Opsional)</label>
                <select name="agenda_id" class="form-control select2-basic" style="width: 100%;">
                    <option value="">-- Pilih Agenda Rapat Terkait --</option>
                    @foreach($agendas as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->judul }} ({{ \Carbon\Carbon::parse($ag->waktu_mulai)->format('d M Y') }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Judul Rapat</label>
                <input type="text" name="judul_rapat" class="form-control" placeholder="Contoh: Rapat Kerja Sekretariat Nasional..." required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label>Tanggal & Waktu Rapat</label>
                    <input type="text" name="tanggal_rapat" class="form-control datetimepicker" style="background: white;" value="{{ date('Y-m-d H:i') }}" placeholder="Pilih tanggal & waktu..." required>
                </div>
                <div class="form-group">
                    <label>Pemimpin Rapat</label>
                    <input type="text" name="pemimpin_rapat" class="form-control" placeholder="Nama pemimpin rapat...">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Ringkasan Hasil Notulensi</label>
                <textarea name="ringkasan_hasil" class="form-control" style="height: 90px;" placeholder="Poin-poin penting hasil rapat..."></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>Link Tautan Dokumen (Google Drive)</label>
                <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/file/d/...">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeCreateModal()" class="btn-outline-secondary">Batal</button>
                <button type="submit" class="btn-solid-navy" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Notulensi Rapat sedang disimpan...' })">Simpan Notulensi</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: var(--navy); margin: 0; font-weight: 700;">Edit Notulensi Rapat</h3>
            <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--gray-500);">&times;</button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Tautkan ke Agenda / Rapat (Opsional)</label>
                <select name="agenda_id" id="editAgendaId" class="form-control select2-basic" style="width: 100%;">
                    <option value="">-- Pilih Agenda Rapat Terkait --</option>
                    @foreach($agendas as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->judul }} ({{ \Carbon\Carbon::parse($ag->waktu_mulai)->format('d M Y') }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Judul Rapat</label>
                <input type="text" name="judul_rapat" id="editJudulRapat" class="form-control" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label>Tanggal & Waktu Rapat</label>
                    <input type="text" name="tanggal_rapat" id="editTanggalRapat" class="form-control datetimepicker" style="background: white;" required>
                </div>
                <div class="form-group">
                    <label>Pemimpin Rapat</label>
                    <input type="text" name="pemimpin_rapat" id="editPemimpinRapat" class="form-control">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Ringkasan Hasil Notulensi</label>
                <textarea name="ringkasan_hasil" id="editRingkasanHasil" class="form-control" style="height: 90px;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>Link Tautan Dokumen (Google Drive)</label>
                <input type="url" name="link_drive" id="editLinkDrive" class="form-control">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeEditModal()" class="btn-outline-secondary">Batal</button>
                <button type="submit" class="btn-solid-navy" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Notulensi Rapat berhasil diperbarui...' })">Update Notulensi</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({ minimumResultsForSearch: -1, width: '100%' });
        }

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                allowInput: true
            });
        }

        let activeDropdown = null;

        document.querySelectorAll('.btn-aksi-trigger').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const dropdown = document.getElementById(targetId);

                if (activeDropdown && activeDropdown !== dropdown) {
                    activeDropdown.classList.remove('is-open');
                }

                if (dropdown.classList.contains('is-open')) {
                    dropdown.classList.remove('is-open');
                    activeDropdown = null;
                } else {
                    const rect = this.getBoundingClientRect();
                    dropdown.style.top = (rect.bottom + 4) + 'px';
                    dropdown.style.left = (rect.right - 175) + 'px';
                    dropdown.classList.add('is-open');
                    activeDropdown = dropdown;
                }
            });
        });

        document.addEventListener('click', function () {
            if (activeDropdown) { activeDropdown.classList.remove('is-open'); activeDropdown = null; }
        });
        window.addEventListener('scroll', function () {
            if (activeDropdown) { activeDropdown.classList.remove('is-open'); activeDropdown = null; }
        }, true);
    });

    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#createModal .select2-basic').select2({ width: '100%', dropdownParent: $('#createModal') });
        }
    }
    function closeCreateModal() { document.getElementById('createModal').classList.remove('active'); }

    function openEditModal(item) {
        document.getElementById('editForm').action = "/admin/sekretariat/notulensi/" + item.id;
        document.getElementById('editAgendaId').value = item.agenda_id || '';
        document.getElementById('editJudulRapat').value = item.judul_rapat;
        document.getElementById('editTanggalRapat').value = item.tanggal_rapat ? item.tanggal_rapat.substring(0, 16) : '';
        document.getElementById('editPemimpinRapat').value = item.pemimpin_rapat || '';
        document.getElementById('editRingkasanHasil').value = item.ringkasan_hasil || '';
        document.getElementById('editLinkDrive').value = item.link_drive || '';
        document.getElementById('editModal').classList.add('active');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#editModal .select2-basic').select2({ width: '100%', dropdownParent: $('#editModal') });
        }
    }
    function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }

    function confirmDeleteNotulensi(id, judul) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Notulensi?',
                text: `Apakah Anda yakin ingin menghapus notulensi "${judul}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        } else if (confirm(`Apakah Anda yakin ingin menghapus notulensi "${judul}"?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
