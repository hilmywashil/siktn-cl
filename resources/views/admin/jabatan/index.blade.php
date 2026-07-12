@extends('admin.layouts.admin-layout')

@section('title', 'Master Jabatan')
@section('page-title', 'Master Jabatan')

@push('styles')
<style>
    .content-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        max-width: 100%;
        overflow-x: auto;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
    }

    .btn-primary {
        background: #0a2540;
        color: white;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #164e63;
        transform: translateY(-1px);
    }

    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f9fafb;
        padding: 0.875rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table td {
        padding: 1rem 0.875rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
    }

    .data-table tr:hover {
        background: #f9fafb;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit, .btn-delete {
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-edit:hover {
        background: #bfdbfe;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        opacity: 0.5;
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    /* Hierarchy Visualization Styles */
    .hierarchy-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
        padding: 2rem 0;
    }

<style>
    .tree {
        text-align: center;
        display: block;
        min-width: max-content;
        padding: 1.5rem 3rem 1rem 3rem; /* Padding kiri-kanan besar biar node tidak kepotong */
    }
    .tree ul {
        padding-top: 20px; 
        position: relative;
        transition: all 0.5s;
        padding-left: 0;
        display: flex;
        justify-content: center; /* Tetap center untuk tampilan bagan yg baik */
        margin: 0;
    }
    .tree li {
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 30px 20px 0 20px;
        transition: all 0.5s;
    }
    /* Garis horizontal */
    .tree li::before, .tree li::after {
        content: '';
        position: absolute; top: 0; right: 50%;
        border-top: 2px solid #cbd5e1;
        width: 50%; height: 30px;
    }
    .tree li::after {
        right: auto; left: 50%;
        border-left: 2px solid #cbd5e1;
    }
    /* Sembunyikan garis kalau anak tunggal */
    .tree li:only-child::after, .tree li:only-child::before {
        display: none;
    }
    .tree li:only-child { padding-top: 0; }
    /* Putus garis di ujung */
    .tree li:first-child::before, .tree li:last-child::after {
        border: 0 none;
    }
    /* Tambah lengkungan di garis terluar */
    .tree li:last-child::before {
        border-right: 2px solid #cbd5e1;
        border-radius: 0 5px 0 0;
    }
    .tree li:first-child::after {
        border-radius: 5px 0 0 0;
    }
    /* Garis vertikal ke anak */
    .tree ul ul::before {
        content: '';
        position: absolute; top: 0; left: 50%;
        border-left: 2px solid #cbd5e1;
        width: 0; height: 30px;
    }
    
    .tree-node {
        border: 2px solid #e2e8f0;
        border-top: 4px solid #0a2540;
        padding: 12px 20px;
        text-decoration: none;
        color: #1e293b;
        display: inline-block;
        border-radius: 8px;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        min-width: 150px;
        font-weight: 600;
        position: relative;
        font-size: 0.9rem;
        z-index: 1; /* Supaya node di atas garis */
        margin-bottom: 20px; /* Jarak antara node dan garis anak */
    }
    .tree-node .node-urutan {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: #0a2540;
        color: white;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 12px;
        white-space: nowrap;
        z-index: 2;
    }
    
    /* Penyesuaian Select2 agar setinggi form-input (42px) */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 6px !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px !important;
        padding-left: 0.625rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }

    /* Scrollbar ada di dalam card, bukan di browser */
    .hierarchy-container {
        overflow: auto;       /* Scroll kiri-kanan DAN atas-bawah */
        max-height: 520px;    /* Tinggi maksimal card visualisasi */
        width: 100%;
        padding-bottom: 0.5rem;
        display: block;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fafbfc;
    }

    .org-add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        border: 2px solid #cbd5e1;
        cursor: pointer;
    }
    .org-add-btn:hover { background: #0a2540; color: white; border-color: #0a2540; }

    .sibling-btn {
        position: absolute;
        right: -12px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .sibling-btn:hover { background: #0ea5e9; border-color: #0ea5e9; color: white; }

    .child-btn {
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .child-btn:hover { background: #10b981; border-color: #10b981; color: white; }
</style>
@endpush

@section('content')


    <div class="content-card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <div>
                <h3 class="card-title">Visualisasi Struktur Jabatan</h3>
                <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem;">Bagan otomatis merender berdasarkan urutan berjenjang (misal: 1, 1.1, 1.1.1)</p>
            </div>
        </div>
        
        {{-- PENTING: width: max-content; margin: 0 auto; agar bagan ke tengah jika kecil, dan rata kiri jika besar agar tidak terpotong --}}
        <div class="hierarchy-container" style="display: block;">
            <div class="tree" style="width: max-content; margin: 0 auto; text-align: center;">
                @if(isset($jabatanTree) && $jabatanTree->count() > 0)
                    <ul>
                        @foreach($jabatanTree as $root)
                            @include('admin.jabatan.partials.tree-node', ['node' => $root])
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state" style="padding: 1rem; text-align: center;">
                        <p>Belum ada hierarki jabatan untuk divisualisasikan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header" style="flex-direction: column; align-items: stretch; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="flex: 1; min-width: 250px;">
                    <h3 class="card-title">Master Jabatan</h3>
                    <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem;">Kelola daftar jabatan dan urutan hierarkinya.</p>
                </div>
                <div style="position: relative; width: 250px;">
                    <select id="searchInput" class="select2-search" style="width: 100%;">
                        <option value="">Cari Jabatan...</option>
                        @foreach($jabatans as $jab)
                            <option value="{{ strtolower($jab->nama_jabatan) }}">{{ $jab->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="background: #f8fafc; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 0.5rem;">
                <form action="{{ route('admin.jabatan.store') }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-start; flex-wrap: wrap;">
                    @csrf
                    <div style="flex: 2; min-width: 200px;">
                        <label for="nama_jabatan" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Nama Jabatan *</label>
                        <input type="text" id="nama_jabatan" name="nama_jabatan" class="form-input @error('nama_jabatan') error @enderror" placeholder="Contoh: Bidang Organisasi" required style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px;">
                        @error('nama_jabatan') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                    </div>
                    <div style="flex: 1; min-width: 250px;">
                        <label for="urutan" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Pilih / Ketik Urutan *</label>
                        <select id="urutan" name="urutan" class="form-input select2-tags @error('urutan') error @enderror" required style="width: 100%;">
                            <option value="">-- Rekomendasi Urutan --</option>
                            @if(isset($uniqueSuggestions))
                                @php
                                    // Kelompokkan data berdasarkan 'group'
                                    $groupedSuggestions = collect($uniqueSuggestions)->groupBy('group');
                                @endphp
                                @foreach($groupedSuggestions as $groupName => $items)
                                    <optgroup label="{{ $groupName }}">
                                        @foreach($items as $sug)
                                            <option value="{{ $sug['id'] }}">{{ $sug['text'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endif
                        </select>
                        <span style="font-size: 0.7rem; color: #6b7280; display: block; margin-top: 0.25rem;">Pilih dari daftar atau ketik manual urutan lalu tekan Enter.</span>
                        @error('urutan') <span style="color: #dc2626; font-size: 0.75rem;">{{ $message }}</span> @enderror
                    </div>
                    <div style="min-width: 120px; padding-top: 28px;">
                        <button type="submit" class="btn-primary" style="width: 100%; height: 42px; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-container">
            @if($jabatans->count() > 0)
                <div style="padding: 1rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: flex-start;">
                    <form id="bulk-delete-form" action="{{ route('admin.jabatan.bulk-delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="btn-bulk-delete" class="btn-action btn-delete" style="display: none;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" style="margin-right: 0.25rem;">
                                <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            Hapus Terpilih (<span id="selected-count">0</span>)
                        </button>
                    </form>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%" style="text-align: center;"><input type="checkbox" id="check-all" class="form-checkbox"></th>
                            <th width="15%" style="text-align: center;">Urutan</th>
                            <th>Nama Jabatan</th>
                            <th width="15%" style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jabatans as $item)
                            <!-- Baris Tampil Data -->
                            <tr id="view-row-{{ $item->id }}" style="border-bottom: 1px solid #e2e8f0; transition: all 0.2s;" class="hover-bg-slate">
                                <td style="text-align: center;">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="check-item form-checkbox" form="bulk-delete-form">
                                </td>
                                <td style="text-align: center; font-weight: bold;">
                                    <span style="background: #f3f4f6; padding: 4px 10px; border-radius: 6px; color: #374151;">
                                        {{ $item->urutan }}
                                    </span>
                                </td>
                                <td style="font-weight: 600;">{{ $item->nama_jabatan }}</td>
                                <td style="text-align: center;">
                                    <div class="action-buttons" style="display: flex; justify-content: center; gap: 0.5rem;">
                                        <button type="button" onclick="toggleEdit({{ $item->id }})" class="btn-action btn-edit">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        
                                        <form action="{{ route('admin.jabatan.destroy', $item) }}" method="POST" class="delete-form" style="margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete delete-btn">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="14" height="14">
                                                    <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Baris Form Edit (Tersembunyi) -->
                            <tr id="edit-row-{{ $item->id }}" style="display: none; background-color: #f8fafc; border-bottom: 2px solid #0a2540;">
                                <td></td>
                                <td style="padding: 0.75rem;">
                                    <input type="text" form="edit-form-{{ $item->id }}" name="urutan" value="{{ $item->urutan }}" class="form-input" required style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center;">
                                </td>
                                <td style="padding: 0.75rem;">
                                    <input type="text" form="edit-form-{{ $item->id }}" name="nama_jabatan" value="{{ $item->nama_jabatan }}" class="form-input" required style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 4px;">
                                </td>
                                <td style="padding: 0.75rem; text-align: center;">
                                    <form id="edit-form-{{ $item->id }}" action="{{ route('admin.jabatan.update', $item) }}" method="POST" style="display: flex; gap: 0.5rem; justify-content: center; margin: 0;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn-primary" style="padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.875rem;">Simpan</button>
                                        <button type="button" onclick="toggleEdit({{ $item->id }})" class="btn-action" style="background: #e2e8f0; color: #475569; padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.875rem; border: none; cursor: pointer;">Batal</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                        <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                    </svg>
                    <h3>Belum ada data jabatan</h3>
                    <p>Mulai tambahkan master jabatan</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleEdit(id) {
        const viewRow = document.getElementById('view-row-' + id);
        const editRow = document.getElementById('edit-row-' + id);
        
        if (viewRow.style.display === 'none') {
            viewRow.style.display = 'table-row';
            editRow.style.display = 'none';
        } else {
            // Sembunyikan baris edit lain
            document.querySelectorAll('[id^="edit-row-"]').forEach(row => {
                row.style.display = 'none';
            });
            document.querySelectorAll('[id^="view-row-"]').forEach(row => {
                row.style.display = 'table-row';
            });
            
            viewRow.style.display = 'none';
            editRow.style.display = 'table-row';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for Urutan with Tags (allow custom typing)
        $('.select2-tags').select2({
            placeholder: "-- Pilih atau Ketik Urutan --",
            allowClear: true,
            tags: true, // Allow custom input!
            theme: 'default'
        });

        // Initialize Select2 for Search
        $('#searchInput').select2({
            placeholder: "Cari jabatan...",
            allowClear: true,
            theme: 'default'
        }).on('change', function() {
            const filter = $(this).val();
            const rows = document.querySelectorAll('.data-table tbody tr[id^="view-row-"]');
            
            rows.forEach(row => {
                const text = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (!filter || text.includes(filter)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
            // Selalu sembunyikan baris edit saat searching
            document.querySelectorAll('.data-table tbody tr[id^="edit-row-"]').forEach(row => {
                row.style.display = 'none';
            });
        });

        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'Hapus Jabatan?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'swal2-confirm-btn',
                        cancelButton: 'swal2-cancel-btn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Bulk Delete Logic
        const checkAll = document.getElementById('check-all');
        const checkItems = document.querySelectorAll('.check-item');
        const btnBulkDelete = document.getElementById('btn-bulk-delete');
        const selectedCountSpan = document.getElementById('selected-count');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.check-item:checked').length;
            selectedCountSpan.textContent = checkedCount;
            if (checkedCount > 0) {
                btnBulkDelete.style.display = 'inline-flex';
            } else {
                btnBulkDelete.style.display = 'none';
                checkAll.checked = false;
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                checkItems.forEach(item => {
                    item.checked = this.checked;
                });
                updateBulkDeleteButton();
            });
        }

        checkItems.forEach(item => {
            item.addEventListener('change', updateBulkDeleteButton);
        });

        if (btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function() {
                const checkedCount = document.querySelectorAll('.check-item:checked').length;
                Swal.fire({
                    title: 'Hapus ' + checkedCount + ' Jabatan Terpilih?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'swal2-confirm-btn',
                        cancelButton: 'swal2-cancel-btn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkDeleteForm.submit();
                    }
                });
            });
        }
    });

    function prefillJabatan(urutan, type) {
        // Find the target urutan based on type and current urutan
        let targetUrutan = '';
        if (type === 'child') {
            targetUrutan = urutan + '.a'; // first child
        } else if (type === 'sibling') {
            let parts = urutan.split('.');
            let last = parts.pop();
            let parent = parts.join('.');
            let nextLast = isNaN(last) ? String.fromCharCode(last.charCodeAt(0) + 1) : parseInt(last) + 1;
            targetUrutan = parent ? parent + '.' + nextLast : nextLast.toString();
        }

        // Set select2 value
        let urutanSelect = $('#urutan');
        
        // Cek apakah opsi tersebut ada di dropdown
        if (urutanSelect.find("option[value='" + targetUrutan + "']").length) {
            urutanSelect.val(targetUrutan).trigger('change');
        } else {
            // Bisa tambahkan logic kalau tidak ada di list (optional)
        }

        // Scroll to form and focus name input
        document.querySelector('.form-card').scrollIntoView({ behavior: 'smooth' });
        setTimeout(() => {
            document.getElementById('nama_jabatan').focus();
        }, 500);
    }
</script>
@endpush
