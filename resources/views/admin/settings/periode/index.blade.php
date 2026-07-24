@extends('admin.layouts.admin-layout')

@section('title', 'Manajemen Periode Kepengurusan - SIKTN Admin')
@section('page-title', 'Manajemen Periode Kepengurusan')

@push('styles')
<style>
    .admin-ui-scope {
        --navy: #022648;
        --navy-dark: #01162f;
        --navy-light: #0a3a6b;
        --gold: #b7830f;
        --green: #059669;
        --blue: #2563eb;
        --red: #dc2626;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-500: #64748b;
        --gray-700: #334155;
        --gray-900: #0f172a;
        --radius-md: 6px;
        font-family: 'Montserrat', sans-serif;
    }

    /* Summary Stat Cards */
    .periode-stat-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 768px) {
        .periode-stat-cards {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        padding: 1.15rem 1.25rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(2, 38, 72, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-left: 4px solid var(--navy);
    }
    .stat-card.stat-gold { border-left-color: var(--gold); }
    .stat-card.stat-green { border-left-color: var(--green); }

    .stat-info .stat-lbl {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    .stat-info .stat-val {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--navy);
        line-height: 1;
    }
    .stat-icon-bg {
        width: 44px;
        height: 44px;
        border-radius: 6px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--navy);
    }
    .stat-card.stat-gold .stat-icon-bg { background: #fef3c7; color: var(--gold); }
    .stat-card.stat-green .stat-icon-bg { background: #d1fae5; color: var(--green); }

    /* Card Container */
    .content-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-200);
        box-shadow: 0 2px 10px rgba(2, 38, 72, 0.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .card-header-bar {
        padding: 1rem 1.25rem;
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .card-header-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--navy);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin: 0;
    }

    .btn-add-periode {
        background: var(--navy);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.8125rem;
        padding: 0.55rem 1.1rem;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-add-periode:hover {
        background: var(--navy-dark);
        color: #ffffff;
    }

    /* Table Styling */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .admin-table th {
        background: #f8fafc;
        color: var(--navy);
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.725rem;
        letter-spacing: 0.5px;
        padding: 0.85rem 1rem;
        border-bottom: 2px solid var(--gray-200);
        text-align: left;
    }
    .admin-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--gray-200);
        color: var(--gray-700);
        vertical-align: middle;
        font-weight: 600;
    }
    .admin-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Status Badges */
    .badge-status {
        padding: 0.35rem 0.75rem;
        border-radius: 4px;
        font-size: 0.725rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .badge-status-active {
        background: #d1fae5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .badge-status-archive {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    /* Action Dropdown Trigger Titik Tiga (⋮) */
    .dropdown-action-container {
        position: relative;
        display: inline-block;
    }
    .btn-aksi-trigger {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: var(--navy);
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: 800;
        font-size: 1.1rem;
        transition: all 0.2s ease;
    }
    .btn-aksi-trigger:hover {
        background: var(--navy);
        color: #ffffff;
        border-color: var(--navy);
    }
    .aksi-dropdown {
        position: fixed;
        display: none;
        background: #ffffff;
        border: 1px solid var(--gray-200);
        border-radius: 6px;
        box-shadow: 0 10px 30px rgba(2, 38, 72, 0.18);
        z-index: 99999;
        min-width: 175px;
        overflow: hidden;
        padding: 4px 0;
    }
    .aksi-dropdown.is-open {
        display: block !important;
        animation: select2DropdownFadeIn 0.15s ease-out;
    }
    .aksi-item {
        width: 100%;
        padding: 0.6rem 0.9rem;
        text-align: left;
        background: none;
        border: none;
        font-size: 0.8125rem;
        font-weight: 700;
        color: var(--navy);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s;
    }
    .aksi-item:hover {
        background: var(--gray-100);
        color: var(--navy);
    }
    .aksi-item.text-red { color: var(--red); }
    .aksi-item.text-red:hover { background: #fef2f2; }

    /* Custom Fixed Modal Overlay */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(1, 22, 47, 0.65);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        padding: 1rem;
        backdrop-filter: blur(2px);
    }
    .modal-overlay.active {
        display: flex !important;
        animation: select2DropdownFadeIn 0.2s ease-out;
    }
    .modal-card {
        background: #ffffff;
        border-radius: 8px;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 20px 40px rgba(2, 38, 72, 0.25);
        overflow: hidden;
    }
    .modal-header-bar {
        background: #022648;
        color: #ffffff;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-header-bar h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #ffffff;
    }
    .modal-close-btn {
        background: none;
        border: none;
        color: #ffffff;
        font-size: 1.4rem;
        font-weight: 700;
        cursor: pointer;
        line-height: 1;
        opacity: 0.8;
    }
    .modal-close-btn:hover { opacity: 1; }
    .modal-body-content {
        padding: 1.25rem;
    }
    .modal-footer-bar {
        background: #f8fafc;
        padding: 0.85rem 1.25rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.6rem;
    }
    .btn-cancel {
        background: #e2e8f0;
        color: #334155;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.8125rem;
        cursor: pointer;
    }
    .btn-submit {
        background: #022648;
        color: #ffffff;
        border: none;
        padding: 0.5rem 1.1rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.8125rem;
        cursor: pointer;
    }
    .btn-submit:hover { background: #01162f; }

    .form-group-item {
        margin-bottom: 1rem;
    }
    .form-group-item label {
        display: block;
        font-weight: 700;
        color: #022648;
        font-size: 0.825rem;
        margin-bottom: 0.35rem;
    }
    .form-group-item input[type="text"],
    .form-group-item input[type="number"],
    .form-group-item textarea {
        width: 100%;
        padding: 0.55rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #022648;
        box-sizing: border-box;
    }
    .form-group-item input:focus,
    .form-group-item textarea:focus {
        outline: none;
        border-color: #022648;
        box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.1);
    }

    @keyframes select2DropdownFadeIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="admin-ui-scope">
    {{-- Summary Stat Cards --}}
    <div class="periode-stat-cards">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-lbl">Total Periode</div>
                <div class="stat-val">{{ $totalPeriode }}</div>
            </div>
            <div class="stat-icon-bg">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-info">
                <div class="stat-lbl">Periode Aktif</div>
                <div class="stat-val">{{ $totalAktif }}</div>
            </div>
            <div class="stat-icon-bg">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
            </div>
        </div>
        <div class="stat-card stat-gold">
            <div class="stat-info">
                <div class="stat-lbl">Periode Arsip / Riwayat</div>
                <div class="stat-val">{{ $totalArsip }}</div>
            </div>
            <div class="stat-icon-bg">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
            </div>
        </div>
    </div>

    {{-- Main Content Table Card --}}
    <div class="content-card">
        <div class="card-header-bar">
            <h3 class="card-header-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Daftar Periode Kepengurusan Karang Taruna
            </h3>
            <button type="button" class="btn-add-periode" onclick="openAddModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Periode Baru
            </button>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Periode</th>
                        <th>Masa Jabatan</th>
                        <th>No. SK Pengesahan</th>
                        <th>Total Pengurus</th>
                        <th>Status</th>
                        <th style="width: 80px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodes as $index => $periode)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong style="color: #022648; font-size: 0.9rem;">{{ $periode->nama_periode }}</strong>
                            @if($periode->keterangan)
                            <div style="font-size: 0.775rem; color: #64748b; font-weight: 500; margin-top: 2px;">
                                {{ Str::limit($periode->keterangan, 60) }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <span style="background: #f1f5f9; padding: 3px 8px; border-radius: 4px; border: 1px solid #cbd5e1; font-weight: 700; color: #022648;">
                                {{ $periode->tahun_mulai }} – {{ $periode->tahun_selesai }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: #334155;">
                                {{ $periode->nomor_sk ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: #022648;">
                                {{ $periode->organisasi_count }} Pengurus
                            </span>
                        </td>
                        <td>
                            @if($periode->is_aktif)
                            <span class="badge-status badge-status-active">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Aktif (Berjalan)
                            </span>
                            @else
                            <span class="badge-status badge-status-archive">
                                Arsip / Riwayat
                            </span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div class="dropdown-action-container">
                                <button type="button" class="btn-aksi-trigger" onclick="toggleActionDropdown(this, event)">⋮</button>
                                <div class="aksi-dropdown">
                                    @if(!$periode->is_aktif)
                                    <button type="button" class="aksi-item" onclick="confirmSetActive('{{ route('admin.settings.periode.set-active', $periode->id) }}', '{{ $periode->nama_periode }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        Set Periode Aktif
                                    </button>
                                    @endif
                                    <button type="button" class="aksi-item" onclick="openEditModal({{ json_encode($periode) }})">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#022648" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit Periode
                                    </button>
                                    @if(!$periode->is_aktif)
                                    <button type="button" class="aksi-item text-red" onclick="confirmDelete('{{ route('admin.settings.periode.destroy', $periode->id) }}', '{{ $periode->nama_periode }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus Periode
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: #64748b;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin-bottom: 0.5rem;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <div style="font-weight: 700; font-size: 0.95rem; color: #022648;">Belum ada Data Periode Kepengurusan</div>
                            <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 4px;">Klik tombol "Tambah Periode Baru" di atas untuk membuat periode pertama.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL OVERLAY TAMBAH PERIODE --}}
<div class="modal-overlay" id="modalAddPeriode">
    <div class="modal-card">
        <div class="modal-header-bar">
            <h3>Tambah Periode Kepengurusan</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalAddPeriode')">&times;</button>
        </div>
        <form action="{{ route('admin.settings.periode.store') }}" method="POST">
            @csrf
            <div class="modal-body-content">
                <div class="form-group-item">
                    <label>Nama Periode <span class="text-danger">*</span></label>
                    <input type="text" name="nama_periode" placeholder="Contoh: Periode 2025 - 2030" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div class="form-group-item">
                        <label>Tahun Mulai <span class="text-danger">*</span></label>
                        <input type="number" name="tahun_mulai" placeholder="2025" required min="1900" max="2100">
                    </div>
                    <div class="form-group-item">
                        <label>Tahun Selesai <span class="text-danger">*</span></label>
                        <input type="number" name="tahun_selesai" placeholder="2030" required min="1900" max="2100">
                    </div>
                </div>

                <div class="form-group-item">
                    <label>Nomor SK Pengesahan</label>
                    <input type="text" name="nomor_sk" placeholder="Contoh: SK/001/PNKT-NAS/2025">
                </div>

                <div class="form-group-item">
                    <label>Keterangan / Catatan</label>
                    <textarea name="keterangan" rows="3" placeholder="Catatan opsional mengenai kepengurusan periode ini..."></textarea>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <input type="checkbox" name="is_aktif" value="1" id="addIsAktif" style="width: 16px; height: 16px; accent-color: #022648; cursor: pointer;">
                    <label for="addIsAktif" style="margin: 0; font-weight: 700; color: #022648; font-size: 0.85rem; cursor: pointer;">
                        Jadikan Periode Aktif Saat Ini
                    </label>
                </div>
            </div>
            <div class="modal-footer-bar">
                <button type="button" class="btn-cancel" onclick="closeModal('modalAddPeriode')">Batal</button>
                <button type="submit" class="btn-submit">Simpan Periode</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL OVERLAY EDIT PERIODE --}}
<div class="modal-overlay" id="modalEditPeriode">
    <div class="modal-card">
        <div class="modal-header-bar">
            <h3>Edit Periode Kepengurusan</h3>
            <button type="button" class="modal-close-btn" onclick="closeModal('modalEditPeriode')">&times;</button>
        </div>
        <form id="editPeriodeForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body-content">
                <div class="form-group-item">
                    <label>Nama Periode <span class="text-danger">*</span></label>
                    <input type="text" name="nama_periode" id="editNamaPeriode" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div class="form-group-item">
                        <label>Tahun Mulai <span class="text-danger">*</span></label>
                        <input type="number" name="tahun_mulai" id="editTahunMulai" required min="1900" max="2100">
                    </div>
                    <div class="form-group-item">
                        <label>Tahun Selesai <span class="text-danger">*</span></label>
                        <input type="number" name="tahun_selesai" id="editTahunSelesai" required min="1900" max="2100">
                    </div>
                </div>

                <div class="form-group-item">
                    <label>Nomor SK Pengesahan</label>
                    <input type="text" name="nomor_sk" id="editNomorSk">
                </div>

                <div class="form-group-item">
                    <label>Keterangan / Catatan</label>
                    <textarea name="keterangan" id="editKeterangan" rows="3"></textarea>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <input type="checkbox" name="is_aktif" value="1" id="editIsAktif" style="width: 16px; height: 16px; accent-color: #022648; cursor: pointer;">
                    <label for="editIsAktif" style="margin: 0; font-weight: 700; color: #022648; font-size: 0.85rem; cursor: pointer;">
                        Jadikan Periode Aktif Saat Ini
                    </label>
                </div>
            </div>
            <div class="modal-footer-bar">
                <button type="button" class="btn-cancel" onclick="closeModal('modalEditPeriode')">Batal</button>
                <button type="submit" class="btn-submit">Perbarui Periode</button>
            </div>
        </form>
    </div>
</div>

{{-- Hidden Form for Delete & Set Active --}}
<form id="actionForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="_method" id="actionMethod" value="POST">
</form>
@endsection

@push('scripts')
<script>
    let activeDropdown = null;

    function toggleActionDropdown(btn, event) {
        event.stopPropagation();
        const container = btn.closest('.dropdown-action-container');
        const dropdown = container.querySelector('.aksi-dropdown');

        if (activeDropdown && activeDropdown !== dropdown) {
            activeDropdown.classList.remove('is-open');
        }

        if (dropdown.classList.contains('is-open')) {
            dropdown.classList.remove('is-open');
            activeDropdown = null;
        } else {
            const rect = btn.getBoundingClientRect();
            dropdown.style.top = (rect.bottom + 4) + 'px';
            dropdown.style.left = (rect.right - 175) + 'px';
            dropdown.classList.add('is-open');
            activeDropdown = dropdown;
        }
    }

    document.addEventListener('click', function(e) {
        if (activeDropdown) {
            activeDropdown.classList.remove('is-open');
            activeDropdown = null;
        }
    });

    window.addEventListener('scroll', function() {
        if (activeDropdown) {
            activeDropdown.classList.remove('is-open');
            activeDropdown = null;
        }
    }, true);

    // Custom Overlay Modals
    function openAddModal() {
        document.getElementById('modalAddPeriode').classList.add('active');
    }

    function openEditModal(periode) {
        document.getElementById('editPeriodeForm').action = "{{ url('admin/settings/periode') }}/" + periode.id;
        document.getElementById('editNamaPeriode').value = periode.nama_periode;
        document.getElementById('editTahunMulai').value = periode.tahun_mulai;
        document.getElementById('editTahunSelesai').value = periode.tahun_selesai;
        document.getElementById('editNomorSk').value = periode.nomor_sk || '';
        document.getElementById('editKeterangan').value = periode.keterangan || '';
        document.getElementById('editIsAktif').checked = periode.is_aktif ? true : false;

        document.getElementById('modalEditPeriode').classList.add('active');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    // SweetAlert2 Confirm Modal
    function confirmSetActive(url, namaPeriode) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Set Periode Aktif?',
                html: `Apakah kamu yakin ingin menetapkan <strong style="color: #022648;">${namaPeriode}</strong> sebagai Periode Aktif?<br><small style="color: #64748b;">Periode yang sebelumnya aktif akan otomatis menjadi arsip.</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#022648',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Set Sebagai Aktif',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('actionForm');
                    form.action = url;
                    document.getElementById('actionMethod').value = 'POST';
                    form.submit();
                }
            });
        } else if (confirm(`Set ${namaPeriode} sebagai Periode Aktif?`)) {
            const form = document.getElementById('actionForm');
            form.action = url;
            document.getElementById('actionMethod').value = 'POST';
            form.submit();
        }
    }

    function confirmDelete(url, namaPeriode) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Periode Kepengurusan?',
                html: `Apakah kamu yakin ingin menghapus <strong style="color: #dc2626;">${namaPeriode}</strong>?<br><small style="color: #64748b;">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus Periode',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('actionForm');
                    form.action = url;
                    document.getElementById('actionMethod').value = 'DELETE';
                    form.submit();
                }
            });
        } else if (confirm(`Hapus periode ${namaPeriode}?`)) {
            const form = document.getElementById('actionForm');
            form.action = url;
            document.getElementById('actionMethod').value = 'DELETE';
            form.submit();
        }
    }

    // Toast Notifications
    @if(session('success'))
        if (typeof Toast !== 'undefined') {
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        } else {
            alert("{{ session('success') }}");
        }
    @endif

    @if(session('error'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: "{{ session('error') }}",
                confirmButtonColor: '#022648'
            });
        } else {
            alert("{{ session('error') }}");
        }
    @endif
</script>
@endpush
