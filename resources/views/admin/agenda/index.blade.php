@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Agenda')
@section('page-title', 'Manajemen Agenda PNKT')

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css' rel='stylesheet' />
    <style>
        .fc-toolbar-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            color: #022648;
            font-size: 1.5rem !important;
        }
        /* Custom FullCalendar Buttons */
        .fc .fc-button-primary {
            background-color: #022648 !important;
            border-color: #022648 !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-weight: 600 !important;
            text-transform: capitalize !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 4px rgba(2, 38, 72, 0.1) !important;
        }
        .fc .fc-button-primary:hover {
            background-color: #1c2780 !important;
            border-color: #1c2780 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(2, 38, 72, 0.2) !important;
        }
        .fc .fc-button-primary:disabled {
            background-color: #9ca3af !important;
            border-color: #9ca3af !important;
            box-shadow: none !important;
        }
        .fc .fc-button-active {
            background-color: #C59217 !important;
            border-color: #C59217 !important;
            color: #022648 !important;
        }
        
        .fc-event {
            cursor: pointer;
            border: none !important;
            border-radius: 6px !important;
            padding: 3px 8px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            margin-bottom: 3px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .fc-event:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
            filter: brightness(1.1);
        }
        .calendar-container {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.02);
        }

        /* Custom Dropdown for Export */
        .export-dropdown {
            position: relative;
            display: inline-block;
        }
        .export-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 110%;
            background-color: #fff;
            min-width: 220px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 1000;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 8px 0;
            list-style: none;
            margin: 0;
        }
        .export-dropdown-menu li {
            margin: 0;
            padding: 0;
        }
        .export-dropdown-menu a {
            color: #4b5563;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }
        .export-dropdown-menu a:hover {
            background-color: #f3f4f6;
            color: #C59217;
        }
        .export-dropdown.show .export-dropdown-menu {
            display: block;
        }
        .legend-container {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .legend-item {
            display: inline-flex;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4b5563;
            background: #f3f4f6;
            padding: 6px 12px;
            border-radius: 20px;
        }
        .legend-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        /* Modal Custom Styles */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.show {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 550px;
            padding: 2rem;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalFadeIn 0.3s ease-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-close {
            position: absolute;
            top: 20px; right: 20px;
            cursor: pointer;
            background: #f3f4f6; border: none;
            width: 32px; height: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #4b5563;
            transition: all 0.2s;
        }
        .modal-close:hover {
            background: #e5e7eb;
            color: #1f2937;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: inherit;
            background-color: #f9fafb;
            color: #1f2937;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }
        .form-control:focus {
            outline: none;
            border-color: #022648;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(2,38,72,0.1);
        }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.2rem;
            padding-right: 2.5rem;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .btn {
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Select2 Custom Styling to match .form-control */
        .select2-container--default .select2-selection--single {
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            height: 46px;
            display: flex;
            align-items: center;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #022648 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(2,38,72,0.1) !important;
            outline: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937;
            font-size: 0.95rem;
            padding-left: 1rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
            right: 10px;
        }
        .select2-dropdown {
            border: 1px solid #022648 !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            overflow: hidden;
            z-index: 1060 !important;
        }
        .select2-search__field {
            outline: none !important;
            border-radius: 4px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #022648 !important;
            color: white !important;
        }
        .btn-primary { 
            background: #022648; 
            color: white; 
            box-shadow: 0 4px 6px rgba(2, 38, 72, 0.1);
        }
        .btn-primary:hover {
            background: #1c2780;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(2, 38, 72, 0.15);
        }
        .btn-danger { 
            background: #dc2626; 
            color: white; 
        }
        .btn-danger:hover {
            background: #b91c1c;
        }
        .btn-google {
            background: #ffffff;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .btn-google:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        /* Custom Toolbar Styles */
        .custom-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 15px;
        }
        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .calendar-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #022648;
            margin: 0 15px;
            font-family: 'Montserrat', sans-serif;
            min-width: 180px;
            text-align: center;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 0.5rem 1rem;
        }
        .btn-outline:hover, .btn-outline.active {
            background: #f3f4f6;
            border-color: #9ca3af;
        }
        .btn-icon {
            padding: 0.5rem;
        }
    </style>
@endpush

@section('content')
<div class="calendar-container">
    <!-- Custom Toolbar -->
    <div class="custom-toolbar">
        <!-- Left Side: Nav & Title -->
        <div class="toolbar-group">
            <button class="btn btn-outline btn-icon" id="btnPrev" title="Sebelumnya">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button class="btn btn-outline" id="btnToday">Hari Ini</button>
            <button class="btn btn-outline btn-icon" id="btnNext" title="Selanjutnya">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
            
            <div class="calendar-title" id="customCalendarTitle">Memuat...</div>
        </div>

        <!-- Right Side: Views & Actions -->
        <div class="toolbar-group">
            <div style="display: flex; background: #f3f4f6; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; margin-right: 10px;">
                <button class="btn btn-outline active" style="border:none; border-radius:0; padding: 0.5rem 1rem;" id="btnViewMonth">Bulan</button>
                <button class="btn btn-outline" style="border:none; border-radius:0; border-left: 1px solid #e5e7eb; padding: 0.5rem 1rem;" id="btnViewWeek">Minggu</button>
                <button class="btn btn-outline" style="border:none; border-radius:0; border-left: 1px solid #e5e7eb; padding: 0.5rem 1rem;" id="btnViewDay">Hari</button>
            </div>

            <div class="export-dropdown" id="exportDropdownContainer">
                <button type="button" class="btn" style="background: #C59217; color: #022648;" onclick="toggleExportDropdown()">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2" style="vertical-align: middle;">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg> Export Kalender (.ics)
                </button>
                <ul class="export-dropdown-menu" id="exportMenu">
                    <li><a href="javascript:void(0)" onclick="exportCalendar('active')">Export Tampilan Saat Ini</a></li>
                    <li><a href="javascript:void(0)" onclick="exportCalendar('thisMonth')">Export Bulan Ini</a></li>
                    <li><a href="javascript:void(0)" onclick="exportCalendar('prevMonth')">Export Bulan Kemarin</a></li>
                    <li><a href="javascript:void(0)" onclick="exportCalendar('nextMonth')">Export Bulan Depan</a></li>
                </ul>
            </div>
            
            <button class="btn" style="background:#f8f9fa; color:#C59217; border:1px solid #C59217;" onclick="copySyncLink()" title="Salin link auto-sync Google Calendar">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2" style="vertical-align: middle;">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg> Auto-Sync
            </button>
            
            <button class="btn btn-primary" onclick="openModal()">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg> Tambah Agenda
            </button>
        </div>
    </div>

    <div id='calendar'></div>
    
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <div class="legend-container mt-4 pt-4" style="border-top: 1px solid #e5e7eb;">
            <div class="legend-item"><span class="legend-box" style="background: #10b981;"></span> Meeting Internal</div>
            <div class="legend-item"><span class="legend-box" style="background: #f59e0b;"></span> Temu Karya</div>
            <div class="legend-item"><span class="legend-box" style="background: #ef4444;"></span> Acara Publik</div>
            <div class="legend-item"><span class="legend-box" style="background: #8b5cf6;"></span> Webinar</div>
            <div class="legend-item"><span class="legend-box" style="background: #3b82f6;"></span> Kategori Lainnya</div>
            <div class="legend-item"><span class="legend-box" style="background: #9ca3af;"></span> Arsip/Selesai</div>
        </div>
    </div>
</div>

<!-- Agenda Modal -->
<div class="modal-overlay" id="agendaModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">×</button>
        <h3 id="modalTitle" style="margin-top:0; color:#0a2540; font-weight:700;">Tambah Agenda</h3>
        <form id="agendaForm">
            <input type="hidden" id="agenda_id" name="id">
            <div class="form-group">
                <label>Judul Kegiatan</label>
                <input type="text" id="judul" name="judul" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis Kegiatan</label>
                <select id="jenis_kegiatan" name="jenis_kegiatan" class="form-control" style="width: 100%;" required>
                    <option value="">Pilih atau Ketik Baru...</option>
                    <option value="Meeting Internal">Meeting Internal</option>
                    <option value="Temu Karya">Temu Karya</option>
                    <option value="Acara Publik">Acara Publik</option>
                    <option value="Webinar">Webinar</option>
                </select>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Waktu Mulai</label>
                    <input type="text" id="waktu_mulai" name="waktu_mulai" class="form-control" placeholder="Pilih waktu mulai..." required>
                </div>
                <div class="form-group">
                    <label>Waktu Selesai</label>
                    <input type="text" id="waktu_selesai" name="waktu_selesai" class="form-control" placeholder="Pilih waktu selesai..." required>
                </div>
            </div>
            <div class="form-group">
                <label>Lokasi / Link Zoom</label>
                <input type="text" id="lokasi" name="lokasi" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Penanggung Jawab (PIC)</label>
                <select id="pic_name" name="pic_name" class="form-control" style="width: 100%;">
                    <option value="">Pilih PIC (Opsional)...</option>
                    <optgroup label="Pengurus (Admin)">
                        @foreach($admins as $admin)
                            <option value="{{ $admin->name }}">{{ $admin->name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Anggota">
                        @foreach($anggotas as $anggota)
                            <option value="{{ $anggota->name }}">{{ $anggota->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <label>Deskripsi Acara</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:25px; align-items:center;">
                <button type="button" class="btn btn-danger" id="btnDelete" style="display:none;" onclick="deleteAgenda()">Hapus</button>
                
                <div style="margin-left:auto; display:flex; gap:10px;">
                    <button type="button" class="btn btn-google" id="btnGoogleCal" style="display:none;" onclick="exportToGoogleCalendar()">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Google Calendar
                    </button>
                    <button type="button" class="btn" onclick="closeModal()" style="background:#f3f4f6; color:#374151;">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Flatpickr CSS & JS (in case not global) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/icalendar@6.1.10/index.global.min.js'></script>
<script>
    let calendar;
    let fpStart, fpEnd;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 with tags enabled for Kegiatan
        $('#jenis_kegiatan').select2({
            dropdownParent: $('#agendaModal'),
            tags: true, // Allow custom text input
            placeholder: "Pilih atau ketik kegiatan..."
        });

        // Initialize Select2 for PIC (Searchable)
        $('#pic_name').select2({
            dropdownParent: $('#agendaModal'),
            placeholder: "Cari & Pilih PIC (Opsional)...",
            allowClear: true
        });

        // Initialize Flatpickr
        fpStart = flatpickr("#waktu_mulai", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });
        fpEnd = flatpickr("#waktu_selesai", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'standard',
            height: 700,
            contentHeight: 'auto',
            headerToolbar: false, // Kita matikan header bawaan
            eventSources: [
                {
                    url: '{{ route("admin.agenda.get-events") }}' // Data agenda internal SIKTN
                },
                {
                    url: '{{ route("admin.agenda.holidays") }}'
                }
            ],
            editable: false,
            droppable: false,
            dayMaxEvents: true, // Akan memunculkan '+2 more' kalau di hari yang sama ada banyak agenda biar ngga penuh
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            datesSet: function(info) {
                // Update title custom saat ganti bulan/minggu
                document.getElementById('customCalendarTitle').innerText = info.view.title;
            },
            dateClick: function(info) {
                // Saat tanggal di klik kosong
                openModal();
                // Set default datetime (08:00 to 10:00)
                let dateStr = info.dateStr;
                let startVal = dateStr.includes('T') ? dateStr.substring(0, 16) : dateStr + 'T08:00';
                let endVal = dateStr.includes('T') ? dateStr.substring(0, 16) : dateStr + 'T10:00';
                
                fpStart.setDate(startVal);
                fpEnd.setDate(endVal);
            },
            eventClick: function(info) {
                // Saat event di klik
                let event = info.event;
                openModal(true);
                
                document.getElementById('agenda_id').value = event.id;
                document.getElementById('judul').value = event.title;
                $('#jenis_kegiatan').val(event.extendedProps.jenis_kegiatan).trigger('change');
                
                // Format tanggal untuk input type datetime-local
                let start = event.start.toISOString().substring(0,16).replace('T', ' ');
                let end = event.end ? event.end.toISOString().substring(0,16).replace('T', ' ') : start;
                
                fpStart.setDate(start);
                fpEnd.setDate(end);
                
                document.getElementById('lokasi').value = event.extendedProps.lokasi;
                
                // Set PIC Name
                let pic = event.extendedProps.pic_name;
                if(pic) {
                    // Check if pic exists in options, if not add it dynamically so it doesn't fail
                    if ($('#pic_name').find("option[value='" + pic + "']").length) {
                        $('#pic_name').val(pic).trigger('change');
                    } else { 
                        var newOption = new Option(pic, pic, true, true);
                        $('#pic_name').append(newOption).trigger('change');
                    }
                } else {
                    $('#pic_name').val(null).trigger('change');
                }
                
                document.getElementById('deskripsi').value = event.extendedProps.deskripsi;
            }
        });
        calendar.render();

        // Handle form...
        document.getElementById('agendaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let id = document.getElementById('agenda_id').value;
            let url = id ? `/admin/agenda/${id}` : '/admin/agenda';
            let method = id ? 'PUT' : 'POST';

            let formData = {
                judul: document.getElementById('judul').value,
                jenis_kegiatan: document.getElementById('jenis_kegiatan').value,
                waktu_mulai: document.getElementById('waktu_mulai').value,
                waktu_selesai: document.getElementById('waktu_selesai').value,
                lokasi: document.getElementById('lokasi').value,
                pic_name: document.getElementById('pic_name').value,
                deskripsi: document.getElementById('deskripsi').value,
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Toast.fire({ icon: 'success', title: data.message });
                    closeModal();
                    calendar.refetchEvents();
                } else {
                    Toast.fire({ icon: 'error', title: 'Gagal menyimpan data.' });
                }
            }).catch(err => {
                console.error(err);
                Toast.fire({ icon: 'error', title: 'Terjadi kesalahan sistem.' });
            });
        });
        // Bind Custom Navigation Buttons
        document.getElementById('btnPrev').addEventListener('click', () => calendar.prev());
        document.getElementById('btnNext').addEventListener('click', () => calendar.next());
        document.getElementById('btnToday').addEventListener('click', () => calendar.today());
        
        // Bind Custom View Buttons
        const viewButtons = {
            'btnViewMonth': 'dayGridMonth',
            'btnViewWeek': 'timeGridWeek',
            'btnViewDay': 'timeGridDay'
        };

        for (const [btnId, viewName] of Object.entries(viewButtons)) {
            document.getElementById(btnId).addEventListener('click', function() {
                calendar.changeView(viewName);
                
                // Update active state
                document.querySelectorAll('.toolbar-group .btn-outline').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        }
    });

    function toggleExportDropdown() {
        document.getElementById('exportDropdownContainer').classList.toggle('show');
    }

    // Close dropdown when clicking outside
    window.onclick = function(event) {
        if (!event.target.closest('.export-dropdown')) {
            var dropdowns = document.getElementsByClassName("export-dropdown");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    function exportCalendar(type) {
        let start, end;
        let today = new Date();
        let currentYear = today.getFullYear();
        let currentMonth = today.getMonth();

        if (type === 'active') {
            start = calendar.view.activeStart.toISOString();
            end = calendar.view.activeEnd.toISOString();
        } else if (type === 'thisMonth') {
            start = new Date(currentYear, currentMonth, 1).toISOString();
            end = new Date(currentYear, currentMonth + 1, 1).toISOString();
        } else if (type === 'prevMonth') {
            start = new Date(currentYear, currentMonth - 1, 1).toISOString();
            end = new Date(currentYear, currentMonth, 1).toISOString();
        } else if (type === 'nextMonth') {
            start = new Date(currentYear, currentMonth + 1, 1).toISOString();
            end = new Date(currentYear, currentMonth + 2, 1).toISOString();
        }

        let exportUrl = `{{ route('admin.agenda.export') }}?start=${encodeURIComponent(start)}&end=${encodeURIComponent(end)}`;
        window.location.href = exportUrl;
    }

    function copySyncLink() {
        // Link rahasia publik
        let syncUrl = "{{ url('/agenda/feed.ics') }}";
        
        // Copy to clipboard
        navigator.clipboard.writeText(syncUrl).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Link Berhasil Disalin!',
                text: 'Buka Google Calendar -> Add Calendar from URL, lalu paste link ini.',
                confirmButtonColor: '#C59217'
            });
        }).catch(function(err) {
            alert('Gagal menyalin link. Copy manual: ' + syncUrl);
        });
    }

    function openModal(isEdit = false) {
        document.getElementById('agendaForm').reset();
        document.getElementById('agenda_id').value = '';
        $('#jenis_kegiatan').val('').trigger('change'); // Reset Select2
        $('#pic_name').val(null).trigger('change'); // Reset Select2 PIC
        
        document.getElementById('modalTitle').innerText = isEdit ? 'Edit Agenda' : 'Tambah Agenda';
        document.getElementById('btnDelete').style.display = isEdit ? 'inline-flex' : 'none';
        document.getElementById('btnGoogleCal').style.display = isEdit ? 'inline-flex' : 'none';
        
        document.getElementById('agendaModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('agendaModal').classList.remove('show');
    }

    function deleteAgenda() {
        let id = document.getElementById('agenda_id').value;
        if(!id) return;

        Swal.fire({
            title: 'Hapus Agenda?',
            text: "Agenda yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: { confirmButton: 'swal2-confirm btn-danger' }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/agenda/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Toast.fire({ icon: 'success', title: data.message });
                        closeModal();
                        calendar.refetchEvents();
                    }
                });
            }
        });
    }
    function exportToGoogleCalendar() {
        let title = encodeURIComponent(document.getElementById('judul').value);
        let loc = encodeURIComponent(document.getElementById('lokasi').value);
        let desc = encodeURIComponent(document.getElementById('deskripsi').value + "\n\nPIC: " + document.getElementById('pic_name').value);
        
        let startRaw = document.getElementById('waktu_mulai').value;
        let endRaw = document.getElementById('waktu_selesai').value;
        
        if(!startRaw || !endRaw) return;

        // Format to YYYYMMDDTHHMMSSZ (UTC)
        let startDate = new Date(startRaw);
        let endDate = new Date(endRaw);
        
        let formatGoogleDate = (date) => {
            return date.toISOString().replace(/-|:|\.\d\d\d/g, "");
        };

        let dates = formatGoogleDate(startDate) + '/' + formatGoogleDate(endDate);
        
        let url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${dates}&details=${desc}&location=${loc}`;
        window.open(url, '_blank');
    }
</script>
@endpush
