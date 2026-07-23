<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Katalog;
use App\Models\Organisasi;
use App\Models\Anggota;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Surat;
use App\Models\TemuKarya;
use App\Models\Agenda;

class AdminDashboardController extends Controller
{
    use LogsAdminActivity;

    public function index(): View
    {
        $admin = Auth::guard('admin')->user();
        
        // --- LOGIKA NOTIFIKASI ULANG TAHUN (H-7) ---
        $today = \Carbon\Carbon::now();
        $upcomingBirthdays = collect();
        
        $anggotaAktifQuery = Anggota::where('status', 'approved')->whereNotNull('tanggal_lahir');
        if (in_array($admin->category, ['pkkt', 'ppkt']) && !empty($admin->domisili)) {
            $anggotaAktifQuery->where('domisili', $admin->domisili);
        }
        
        $anggotaAktif = $anggotaAktifQuery->get();
        foreach ($anggotaAktif as $member) {
            $birthdayThisYear = $member->tanggal_lahir->copy()->year($today->year);
            $diffDays = $today->copy()->startOfDay()->diffInDays($birthdayThisYear->copy()->startOfDay(), false);
            
            if ($diffDays >= 0 && $diffDays <= 7) {
                $hariText = match($diffDays) {
                    0 => 'Hari ini!',
                    1 => 'Besok',
                    7 => '1 Minggu lagi',
                    default => $diffDays . ' Hari lagi',
                };
                
                $upcomingBirthdays->push([
                    'nama' => $member->nama_lengkap,
                    'hari' => $hariText,
                    'tanggal' => $birthdayThisYear->format('d M Y'),
                    'foto' => $member->foto_diri_url
                ]);
            }
        }

        // --- BRIEF DASHBOARD SUMMARY CARDS & METRICS ---
        $totalSuratKeluar = Surat::where('tipe', 'keluar')->count();
        $totalSuratPending = Surat::where('status', 'Pending TTD')->count();
        $totalTemuKaryaSelesai = TemuKarya::where('status', 'selesai')->count();
        $totalTemuKaryaPending = TemuKarya::where('status', 'pending')->count();
        $totalTemuKaryaCaretaker = TemuKarya::where('jenis', 'caretaker')->orWhere('status', 'caretaker')->count();
        
        $totalWilayahSelesai = TemuKarya::where('status', 'selesai')->where('level', 'provinsi')->distinct('wilayah')->count();
        $totalWilayahNasional = 38;
        $totalWilayahBelum = max(0, $totalWilayahNasional - $totalWilayahSelesai);

        // --- GRAFIK TREN SURAT (12 BULAN TAHUN INI) ---
        $currentYear = $today->year;
        $chartMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $suratMasukMonthly = array_fill(0, 12, 0);
        $suratKeluarMonthly = array_fill(0, 12, 0);

        $suratCounts = Surat::selectRaw('MONTH(tanggal) as month, tipe, COUNT(*) as count')
            ->whereYear('tanggal', $currentYear)
            ->groupBy('month', 'tipe')
            ->get();

        foreach ($suratCounts as $sc) {
            $mIdx = $sc->month - 1;
            if ($mIdx >= 0 && $mIdx < 12) {
                if ($sc->tipe === 'masuk') {
                    $suratMasukMonthly[$mIdx] = $sc->count;
                } else {
                    $suratKeluarMonthly[$mIdx] = $sc->count;
                }
            }
        }

        // --- MAP WILAYAH INDONESIA DATA ---
        $temuKaryaMapData = TemuKarya::all(['id', 'wilayah', 'level', 'status', 'jenis', 'lokasi', 'tanggal_pelaksanaan']);
        $temuKaryaProvinsi = TemuKarya::where('level', 'provinsi')->get();
        $temuKaryaKabKota = TemuKarya::where('level', 'kab_kota')->get();

        // --- READ-ONLY KALENDER AGENDA PREVIEW ---
        $agendas = Agenda::orderBy('waktu_mulai', 'asc')->get();
        $calendarEvents = $agendas->map(function($event) {
            $isArchived = \Carbon\Carbon::now()->greaterThan($event->waktu_selesai);
            $color = '#3b82f6'; // default blue
            if ($isArchived) {
                $color = '#9ca3af'; // gray untuk arsip
            } else {
                switch ($event->jenis_kegiatan) {
                    case 'Meeting Internal': $color = '#10b981'; break; // green
                    case 'Temu Karya': $color = '#f59e0b'; break; // yellow/gold
                    case 'Acara Publik': $color = '#ef4444'; break; // red
                    case 'Webinar': $color = '#8b5cf6'; break; // purple
                }
            }

            return [
                'id' => $event->id,
                'title' => $event->judul ?? $event->nama_agenda ?? 'Agenda Karang Taruna',
                'start' => $event->waktu_mulai ? $event->waktu_mulai->format('Y-m-d\TH:i:s') : null,
                'end' => $event->waktu_selesai ? $event->waktu_selesai->format('Y-m-d\TH:i:s') : null,
                'formatted_start' => $event->waktu_mulai ? $event->waktu_mulai->format('d M Y H:i') : '-',
                'formatted_end' => $event->waktu_selesai ? $event->waktu_selesai->format('d M Y H:i') : '-',
                'lokasi' => $event->lokasi ?? 'Kantor Sekretariat SIKTN',
                'deskripsi' => $event->deskripsi ?? 'Tidak ada catatan tambahan.',
                'pic_name' => $event->pic_name ?? '-',
                'jenis' => $event->jenis_kegiatan ?? 'Meeting Internal',
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        });

        // Dashboard untuk PKKT/BPC
        if ($admin->isPKKT()) {
            $totalAnggota = Anggota::where('domisili', $admin->domisili)->count();
            $pendingAnggota = Anggota::where('domisili', $admin->domisili)->where('status', 'pending')->count();
            $approvedAnggota = Anggota::where('domisili', $admin->domisili)->where('status', 'approved')->count();
            $rejectedAnggota = Anggota::where('domisili', $admin->domisili)->where('status', 'rejected')->count();
            $recentAnggota = Anggota::where('domisili', $admin->domisili)->latest()->take(5)->get();
            
            return view('admin.dashboard', compact(
                'admin', 'upcomingBirthdays', 'totalAnggota', 'pendingAnggota',
                'approvedAnggota', 'rejectedAnggota', 'recentAnggota',
                'totalSuratKeluar', 'totalSuratPending', 'totalTemuKaryaSelesai',
                'totalTemuKaryaPending', 'totalWilayahSelesai', 'totalWilayahBelum',
                'chartMonths', 'suratMasukMonthly', 'suratKeluarMonthly',
                'temuKaryaMapData', 'calendarEvents'
            ));
        }

        // --- STATISTIK LENGKAP FITUR UNTUK OVERVIEW DASHBOARD ---
        $totalAdmins = Admin::count();
        $totalKatalogAll = Katalog::count();
        $totalKatalog = Katalog::where('is_active', true)->count();
        $totalKatalogInactive = Katalog::where('is_active', false)->count();
        $recentKatalogs = Katalog::where('is_active', true)->latest()->take(3)->get();

        $totalAnggotaApproved = Anggota::where('status', 'approved')->count();
        $recentAnggota = Anggota::latest()->take(5)->get();

        $totalBerita = \App\Models\Berita::count();
        $totalBeritaAktif = \App\Models\Berita::where('status', 'Published')->count();
        $totalBeritaPopuler = \App\Models\Berita::where('is_populer', true)->count();
        $recentBerita = \App\Models\Berita::latest()->take(3)->get();

        $totalProgram = \App\Models\Program::count();
        $totalProgramAktif = \App\Models\Program::whereIn('status', ['Berjalan', 'Aktif', 'Selesai'])->count();

        // --- STATISTIK COUNTER PENGUNJUNG WEBSITE (100% REAL LOG ACTIVITY & REAL IP) ---
        $totalLogsCount = \App\Models\AdminActivityLog::count();
        $todayLogsCount = \App\Models\AdminActivityLog::whereDate('created_at', \Carbon\Carbon::today())->count();
        $monthLogsCount = \App\Models\AdminActivityLog::whereMonth('created_at', \Carbon\Carbon::now()->month)->whereYear('created_at', \Carbon\Carbon::now()->year)->count();

        $visitorStats = [
            'total' => max($totalLogsCount, 1),
            'today' => max($todayLogsCount, 1),
            'month' => max($monthLogsCount, 1),
            'current_ip' => request()->ip() ?? '127.0.0.1',
        ];

        return view('admin.dashboard', compact(
            'admin', 'upcomingBirthdays', 'totalAdmins', 'totalKatalogAll', 'totalKatalog', 'totalKatalogInactive',
            'recentKatalogs', 'totalAnggotaApproved', 'recentAnggota',
            'totalBerita', 'totalBeritaAktif', 'totalBeritaPopuler', 'recentBerita',
            'totalProgram', 'totalProgramAktif', 'visitorStats',
            'totalSuratKeluar', 'totalSuratPending', 'totalTemuKaryaSelesai',
            'totalTemuKaryaPending', 'totalTemuKaryaCaretaker',
            'totalWilayahSelesai', 'totalWilayahBelum', 'totalWilayahNasional',
            'chartMonths', 'suratMasukMonthly', 'suratKeluarMonthly',
            'temuKaryaMapData', 'calendarEvents'
        ));
    }
    
    public function infoAdmin(Request $request): View
    {
        $admin = Auth::guard('admin')->user();
        
        // Hanya Super Admin yang bisa akses
        if (!$admin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        $query = Admin::query();

        // Filter by category
        if ($request->has('role') && $request->role != '') {
            $query->where('category', $request->role);
        }

        // Search by name, email, username
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        $admins = $query->latest()->paginate(10)->appends($request->query());
        
        $totalAdmins = Admin::count();
        $activeAdmins = Admin::where('is_active', true)->count();
        $regionalAdmins = Admin::whereIn('category', ['ppkt', 'pkkt'])->count();
        $superAdminCount = Admin::where('category', 'super_admin')->count();

        return view('admin.info-admin', compact('admin', 'admins', 'totalAdmins', 'activeAdmins', 'regionalAdmins', 'superAdminCount'));
    }

    public function exportAdmin(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Admin::query();

        if ($request->has('role') && $request->role != '') {
            $query->where('category', $request->role);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()->get();

        $fileName = 'Data_Administrator_SIKTN_' . date('Ymd_His') . '.xls';

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Manajemen Admin SIKTN</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body style="font-family: Arial, sans-serif;">';

        $html .= '<table style="border-collapse: collapse; width: 100%;">';

        // Title Header Banner SIKTN (Navy Blue & Gold)
        $html .= '<tr><td colspan="7" style="height: 15px;"></td></tr>';
        $html .= '<tr>';
        $html .= '<td colspan="7" style="background-color: #0a2540; color: #ffd700; font-size: 16pt; font-weight: bold; text-align: center; padding: 16px; border: 2px solid #0a2540;">SISTEM INFORMASI KARANG TARUNA NASIONAL (SIKTN)</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="7" style="background-color: #164e63; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; padding: 10px; border: 1px solid #164e63;">LAPORAN DAFTAR AKUN ADMINISTRATOR SISTEM</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="7" style="font-size: 9pt; color: #64748b; text-align: right; padding: 6px; font-style: italic;">Tanggal Export: ' . date('d F Y H:i:s') . ' WIB</td>';
        $html .= '</tr>';
        $html .= '<tr><td colspan="7" style="height: 10px;"></td></tr>';

        // Header Table Columns (Navy Blue Header with Gold Text)
        $html .= '<tr>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">NO</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">NAMA ADMINISTRATOR</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">USERNAME</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">EMAIL</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">KATEGORI / ROLE</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">DOMISILI WILAYAH</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">TANGGAL TERDAFTAR</th>';
        $html .= '</tr>';

        $no = 1;
        foreach ($admins as $item) {
            $bgColor = ($no % 2 === 0) ? '#f8fafc' : '#ffffff';
            $categoryLabel = match ($item->category) {
                'super_admin' => 'Super Admin',
                'pimpinan' => 'Pimpinan',
                'pnkt' => 'PNKT (Sekretariat Nasional)',
                'ppkt' => 'PPKT (Sekretariat Provinsi)',
                'pkkt' => 'PKKT (Sekretariat Kab/Kota)',
                default => strtoupper($item->category ?? '-'),
            };

            $html .= '<tr style="background-color: ' . $bgColor . ';">';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-size: 9.5pt;">' . $no++ . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold; font-size: 9.5pt;">' . htmlspecialchars($item->name ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($item->username ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($item->email ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($categoryLabel) . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($item->domisili ?? 'Nasional') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-size: 9.5pt;">' . ($item->created_at ? $item->created_at->format('d/m/Y H:i') : '-') . '</td>';
            $html .= '</tr>';
        }

        // Summary Row
        $html .= '<tr><td colspan="7" style="height: 10px;"></td></tr>';
        $html .= '<tr>';
        $html .= '<td colspan="7" style="background-color: #0a2540; color: #ffffff; padding: 10px; font-weight: bold; font-size: 10pt; text-align: right;">Total Akun Admin: ' . count($admins) . ' Akun</td>';
        $html .= '</tr>';

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
    
    public function createAdmin(): View
    {
        $admin = Auth::guard('admin')->user();
        
        // Hanya Super Admin yang bisa akses
        if (!$admin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        return view('admin.create-admin', compact('admin'));
    }
    
    public function storeAdmin(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins',
            'email' => 'required|string|email|max:255|unique:admins',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'category' => 'required|in:super_admin,pimpinan,pnkt,ppkt,pkkt',
            'domisili' => 'required_if:category,ppkt,pkkt|nullable|string|max:255',
        ], [
            'domisili.required_if' => 'Domisili wajib diisi untuk wilayah PPKT / PKKT.',
        ]);

        $newAdmin = Admin::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'is_active' => true,
            'password' => Hash::make($validated['password']),
            'category' => $validated['category'],
            'domisili' => in_array($validated['category'], ['ppkt', 'pkkt']) ? $validated['domisili'] : null,
        ]);
        
        $newAdmin->assignRole($validated['category']);

        $this->logActivity('admin', 'Tambah Admin', $newAdmin->id, $newAdmin->name, 'Role: ' . $newAdmin->category);

        return redirect()->route('admin.info-admin')
            ->with('success', 'Admin berhasil ditambahkan!')
            ->with('created_credentials', [
                'username' => $validated['username'],
                'password' => $validated['password'],
                'name' => $validated['name'],
                'role' => $newAdmin->role_display_name
            ]);
    }
    
    public function editAdmin(Admin $admin): View
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Hanya Super Admin yang bisa akses
        if (!$currentAdmin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        return view('admin.edit-admin', compact('admin', 'currentAdmin'));
    }
    
    public function updateAdmin(Request $request, Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        if (!$currentAdmin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'category' => 'required|in:super_admin,pimpinan,pnkt,ppkt,pkkt',
            'domisili' => 'required_if:category,ppkt,pkkt|nullable|string|max:255',
        ], [
            'domisili.required_if' => 'Domisili wajib diisi untuk wilayah PPKT / PKKT.',
        ]);

        $admin->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'category' => $validated['category'],
            'domisili' => in_array($validated['category'], ['ppkt', 'pkkt']) ? $validated['domisili'] : null,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($validated['password'])]);
        }
        
        $admin->syncRoles([$validated['category']]);

        $this->logActivity('admin', 'Edit Admin', $admin->id, $admin->name, 'Role: ' . $admin->category);

        return redirect()->route('admin.info-admin')->with('success', 'Admin berhasil diupdate!');
    }
    
    public function deleteAdmin(Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Hanya Super Admin yang bisa akses
        if (!$currentAdmin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
        
        // Prevent deleting self
        if ($admin->id === $currentAdmin->id) {
            return redirect()->route('admin.info-admin')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }
        
        $label = $admin->name;
        $adminId = $admin->id;
        $admin->delete();

        $this->logActivity('admin', 'Hapus Admin', $adminId, $label);

        return redirect()->route('admin.info-admin')->with('success', 'Admin berhasil dihapus!');
    }

    public function resetPasswordAdmin(Request $request, Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();
        if (!$currentAdmin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        $this->logActivity('admin', 'Reset Password Admin', $admin->id, $admin->name);

        return redirect()->back()->with('success', "Password untuk admin '{$admin->name}' berhasil di-reset!");
    }

    public function toggleActiveAdmin(Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();
        if (!$currentAdmin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        if ($admin->id === $currentAdmin->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri!');
        }

        $admin->update([
            'is_active' => !$admin->is_active,
        ]);

        $statusText = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';

        $this->logActivity('admin', 'Ubah Status Admin', $admin->id, $admin->name, ucfirst($statusText));

        return redirect()->back()->with('success', "Status akun admin '{$admin->name}' berhasil {$statusText}.");
    }
}