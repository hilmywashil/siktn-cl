<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Notifications\AnggotaStatusNotification;

class AnggotaManagementController extends Controller
{
    /**
     * Terapkan filter domisili jika user adalah PPKT atau PKKT
     */
    private function applyDomisiliFilter($query)
    {
        $admin = auth()->guard('admin')->user();
        if (in_array($admin->category, ['ppkt', 'pkkt']) && !empty($admin->domisili)) {
            $query->where('domisili', $admin->domisili);
        }
        return $query;
    }

    /**
     * Cek apakah admin punya hak akses ke data anggota ini
     */
    private function checkAnggotaAccess(Anggota $anggota)
    {
        $admin = auth()->guard('admin')->user();
        if (in_array($admin->category, ['ppkt', 'pkkt']) && !empty($admin->domisili)) {
            if ($anggota->domisili !== $admin->domisili) {
                abort(403, 'Akses Ditolak: Anda hanya dapat mengelola data anggota dari wilayah Anda sendiri (' . $admin->domisili . ').');
            }
        }
    }

    private function checkRoleAuthorization($viewOnly = false)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin->isSuperAdmin() && !$admin->canApproveAnggota()) {
            if ($viewOnly && $admin->isPimpinan()) {
                return; // Pimpinan allowed to view
            }
            abort(403, 'Akses ditolak: Anda tidak memiliki hak akses ke fitur ini.');
        }
    }

    /**
     * Display a listing of anggota
     */
    public function index(Request $request)
    {
        $this->checkRoleAuthorization(true);

        $status = $request->get('status', 'all');
        
        $query = Anggota::query();
        $query = $this->applyDomisiliFilter($query);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $anggota = $query->latest()->paginate(15);
        
        // Stats
        $statsQuery = Anggota::query();
        $statsQuery = $this->applyDomisiliFilter($statsQuery);
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'pending_profile' => (clone $statsQuery)->where('status', 'pending_profile')->count(),
            'pending_verification' => (clone $statsQuery)->where('status', 'pending_verification')->count(),
            'approved' => (clone $statsQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $statsQuery)->where('status', 'rejected')->count(),
        ];
        
        return view('admin.anggota.index', compact('anggota', 'stats', 'status'));
    }

    /**
     * Show the form for creating a new anggota
     */
    public function create()
    {
        $this->checkRoleAuthorization();

        return view('admin.anggota.create');
    }

    /**
     * Store a newly created anggota (Akun Awal by Sekretariat)
     */
    public function store(Request $request)
    {
        $this->checkRoleAuthorization();

        $request->validate([
            'username' => 'required|string|max:255|unique:anggota,username',
            'nama_lengkap' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:anggota,email',
            'password' => 'required|string',
            'jabatan' => 'nullable|string|max:255',
            'domisili' => 'nullable|string|max:255',
        ], [
            'username.unique' => 'Username ini sudah digunakan, silakan pilih yang lain.',
            'email.unique' => 'Email yang di-generate sudah terdaftar, coba modifikasi username.',
        ]);

        $admin = auth()->guard('admin')->user();
        
        // Kunci domisili jika yang membuat adalah PPKT/PKKT
        $domisili = $request->domisili;
        if (in_array($admin->category, ['ppkt', 'pkkt']) && !empty($admin->domisili)) {
            $domisili = $admin->domisili;
        }

        Anggota::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'domisili' => $domisili,
            'password' => Hash::make($request->password),
            'initial_password' => $request->password,
            'status' => 'pending_profile',
        ]);

        return redirect()->back()->with('created_credentials', [
            'username' => $request->username,
            'password' => $request->password,
            'login_url' => url('/login')
        ])->with('success', "Akun anggota berhasil dibuat!");
    }

    /**
     * Export data anggota ke file Excel (.xls) dengan Styling SIKTN Navy & Gold
     */
    public function export(Request $request)
    {
        $this->checkRoleAuthorization(true);

        $query = Anggota::query();
        $this->applyDomisiliFilter($query);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('domisili')) {
            $query->where('domisili', $request->domisili);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $anggotas = $query->orderBy('nama_lengkap', 'asc')->get();

        $admin = auth()->guard('admin')->user();
        $isSuperAdmin = $admin->isSuperAdmin();
        $colSpan = $isSuperAdmin ? 13 : 12;

        $wilayahTitle = !empty($admin->domisili) ? strtoupper($admin->domisili) : 'NASIONAL';
        $fileName = 'Data_Anggota_SIKTN_' . date('Ymd_His') . '.xls';

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Database Anggota SIKTN</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body style="font-family: Arial, sans-serif;">';

        $html .= '<table style="border-collapse: collapse; width: 100%;">';

        // Title Header Banner SIKTN (Navy Blue & Gold)
        $html .= '<tr><td colspan="' . $colSpan . '" style="height: 15px;"></td></tr>';
        $html .= '<tr>';
        $html .= '<td colspan="' . $colSpan . '" style="background-color: #0a2540; color: #ffd700; font-size: 16pt; font-weight: bold; text-align: center; padding: 16px; border: 2px solid #0a2540;">SISTEM INFORMASI KARANG TARUNA NASIONAL (SIKTN)</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="' . $colSpan . '" style="background-color: #164e63; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; padding: 10px; border: 1px solid #164e63;">LAPORAN DATABASE ANGGOTA & PENGURUS - WILAYAH: ' . htmlspecialchars($wilayahTitle) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="' . $colSpan . '" style="font-size: 9pt; color: #64748b; text-align: right; padding: 6px; font-style: italic;">Tanggal Export: ' . date('d F Y H:i:s') . ' WIB</td>';
        $html .= '</tr>';
        $html .= '<tr><td colspan="' . $colSpan . '" style="height: 10px;"></td></tr>';

        // Header Table Columns (Navy Blue Header with Gold Text)
        $html .= '<tr>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">NO</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">NIK</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">NAMA LENGKAP</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">USERNAME</th>';
        if ($isSuperAdmin) {
            $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">PASSWORD</th>';
        }
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">EMAIL</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">NO. WHATSAPP / HP</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">JABATAN</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">DOMISILI / WILAYAH</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">TEMPAT & TGL LAHIR</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">PENDIDIKAN</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">PEKERJAAN</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">STATUS AKUN</th>';
        $html .= '</tr>';

        $no = 1;
        foreach ($anggotas as $a) {
            $tglLahir = $a->tanggal_lahir ? $a->tanggal_lahir->format('d/m/Y') : '-';
            $ttl = trim(($a->tempat_lahir ?? '') . ', ' . $tglLahir, ', ');
            $bgColor = ($no % 2 === 0) ? '#f8fafc' : '#ffffff';

            $statusText = match ($a->status) {
                'approved' => 'Terverifikasi (Approved)',
                'pending_verification' => 'Menunggu Verifikasi',
                'pending_profile' => 'Belum Lengkap Profil',
                'rejected' => 'Ditolak',
                default => ucfirst($a->status ?? '-'),
            };

            $html .= '<tr style="background-color: ' . $bgColor . ';">';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-size: 9.5pt;">' . $no++ . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-family: monospace; font-size: 9.5pt; mso-number-format:\'\@\';">\'' . ($a->nik ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold; font-size: 9.5pt;">' . htmlspecialchars($a->nama_lengkap ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($a->username ?? '-') . '</td>';
            if ($isSuperAdmin) {
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-family: monospace; font-weight: bold; font-size: 9.5pt; color: #0f766e;">' . htmlspecialchars($a->initial_password ?? '-') . '</td>';
            }
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($a->email ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-family: monospace; text-align: center; font-size: 9.5pt; mso-number-format:\'\@\';">\'' . ($a->no_hp ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($a->jabatan ?? 'Anggota') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($a->domisili ?? 'Nasional') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($ttl) . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($a->pendidikan_terakhir ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($a->pekerjaan ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-weight: bold; font-size: 9.5pt;">' . htmlspecialchars($statusText) . '</td>';
            $html .= '</tr>';
        }

        // Summary Row at Bottom
        $html .= '<tr><td colspan="' . $colSpan . '" style="height: 10px;"></td></tr>';
        $html .= '<tr>';
        $html .= '<td colspan="' . $colSpan . '" style="background-color: #0a2540; color: #ffffff; padding: 10px; font-weight: bold; font-size: 10pt; text-align: right;">Total Data Anggota: ' . count($anggotas) . ' Orang</td>';
        $html .= '</tr>';

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Show detail anggota
     */
    public function show(Anggota $anggota)
    {
        $this->checkRoleAuthorization(true);
        $this->checkAnggotaAccess($anggota);
        $jabatans = \App\Models\Jabatan::orderBy('urutan')->get();
        return view('admin.anggota.show', compact('anggota', 'jabatans'));
    }

    /**
     * Update data anggota
     */
    public function update(Request $request, Anggota $anggota)
    {
        $this->checkRoleAuthorization();
        $this->checkAnggotaAccess($anggota);
        
        $validated = $request->validate([
            // Data Perusahaan
            'nama_perusahaan' => 'required|string|max:255',
            'trade_mark' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'email_website_perusahaan' => 'required|email|max:255',
            'telepon_wa_perusahaan' => 'required|string|max:20',
            'alamat_kantor' => 'required|string',
            
            // Data Pimpinan
            'nama_pimpinan' => 'required|string|max:255',
            'email_pimpinan' => 'required|email|max:255',
            'telepon_wa_pimpinan' => 'required|string|max:20',
            'alamat_pimpinan' => 'required|string',
            
            // Legalitas
            'akte_notaris' => 'required|string|max:255',
            'nomor_induk_berusaha_tdup' => 'required|string|max:255',
            'npwp_perusahaan' => 'required|string|max:255',
        ]);

        $anggota->update($validated);

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Data anggota berhasil diperbarui!');
    }

    /**
     * Update password anggota
     */
    public function updatePassword(Request $request, Anggota $anggota)
    {
        $this->checkRoleAuthorization();
        $this->checkAnggotaAccess($anggota);
        
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Update password
        $anggota->update([
            'password' => Hash::make($request->new_password),
            'initial_password' => null, // Hapus initial password
        ]);

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Password anggota berhasil diubah!');
    }

    /**
     * Approve anggota
     */
    public function approve(Request $request, Anggota $anggota)
    {
        $this->checkRoleAuthorization();
        $this->checkAnggotaAccess($anggota);
        
        $admin = auth()->guard('admin')->user();

        // 1. Update status anggota
        $anggota->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
            'updated_fields' => null,
        ]);

        // 2. Handle Jabatan dan Organisasi secara Otomatis
        $jabatanNama = $anggota->jabatan;

        if ($jabatanNama) {
            $newUrutan = '1';

            // Cari apakah jabatan ini sudah ada di struktur
            $existingJabatan = \App\Models\Jabatan::where('nama_jabatan', $jabatanNama)->first();

            if ($existingJabatan) {
                $existingUrutan = $existingJabatan->urutan;
                $parts = explode('.', $existingUrutan);
                
                if (count($parts) > 1) {
                    // Ambil urutan parent-nya
                    array_pop($parts);
                    $parentUrutan = implode('.', $parts);
                    
                    // Cari semua child dari parent ini
                    $children = \App\Models\Jabatan::where('urutan', 'like', $parentUrutan . '.%')->get()
                        ->filter(function($j) use ($parentUrutan) {
                            $p = explode('.', $j->urutan);
                            $parentP = explode('.', $parentUrutan);
                            return count($p) === count($parentP) + 1;
                        });

                    if ($children->count() > 0) {
                        $maxChild = $children->sortByDesc(function($j) {
                            $p = explode('.', $j->urutan);
                            return end($p);
                        })->first();

                        $p = explode('.', $maxChild->urutan);
                        $last = array_pop($p);
                        
                        if (is_numeric($last)) {
                            $nextLast = (int)$last + 1;
                        } else {
                            $nextLast = ++$last;
                        }
                        $newUrutan = implode('.', $p) . '.' . $nextLast;
                    } else {
                        $newUrutan = $parentUrutan . '.a';
                    }
                } else {
                    // Jika yang existing adalah root (tidak ada titik), tambahkan sebagai root baru
                    $roots = \App\Models\Jabatan::all()->filter(function($j) {
                        return !str_contains($j->urutan, '.');
                    });
                    $maxRoot = 0;
                    foreach ($roots as $r) {
                        if (is_numeric($r->urutan) && (int)$r->urutan > $maxRoot) {
                            $maxRoot = (int)$r->urutan;
                        }
                    }
                    $newUrutan = (string)($maxRoot + 1);
                }
            } else {
                // Jabatan sama sekali baru, jadikan Root default
                $roots = \App\Models\Jabatan::all()->filter(function($j) {
                    return !str_contains($j->urutan, '.');
                });
                
                $maxRoot = 0;
                if ($roots->count() > 0) {
                    foreach ($roots as $r) {
                        if (is_numeric($r->urutan) && (int)$r->urutan > $maxRoot) {
                            $maxRoot = (int)$r->urutan;
                        }
                    }
                }
                $newUrutan = (string)($maxRoot + 1);
            }

            // Simpan Jabatan Baru khusus untuk orang ini
            $masterJabatan = \App\Models\Jabatan::create([
                'nama_jabatan' => $jabatanNama,
                'urutan' => $newUrutan
            ]);

            // Update field jabatan di Anggota
            $anggota->update(['jabatan' => $jabatanNama]);

            // Masukkan ke Struktur Organisasi
            Organisasi::updateOrCreate(
                ['anggota_id' => $anggota->id],
                [
                    'nama' => $anggota->nama_lengkap ?? $anggota->username,
                    'jabatan' => $masterJabatan->nama_jabatan,
                    'kategori' => $masterJabatan->nama_jabatan, // Default kategori dari nama jabatan
                    'foto' => $anggota->foto_diri ?? null,
                    'aktif' => true,
                    'urutan' => $masterJabatan->urutan,
                ]
            );
        }

        $anggota->notify(new AnggotaStatusNotification('approved'));

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Anggota berhasil disetujui dan ditambahkan ke struktur organisasi!');
    }

    /**
     * Reject anggota
     */
    public function reject(Request $request, Anggota $anggota)
    {
        $this->checkRoleAuthorization();
        $this->checkAnggotaAccess($anggota);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $admin = auth()->guard('admin')->user();

        $anggota->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => $admin->id,
            'updated_fields' => null,
        ]);

        $anggota->notify(new AnggotaStatusNotification('rejected', $request->rejection_reason));

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Pendaftaran anggota ditolak.');
    }

    /**
     * Soft delete anggota
     */
    public function destroy(Anggota $anggota)
    {
        $this->checkRoleAuthorization();
        $this->checkAnggotaAccess($anggota);
        
        $anggota->delete();

        return redirect()
            ->route('admin.anggota.list')
            ->with('deleted', 'Data anggota berhasil dihapus.');
    }

    /**
     * Tampilkan data anggota yang terhapus
     * (Route ini dipindah ke TrashController, tapi saya biarkan kosong untuk keamanan jika masih dipanggil)
     */
    public function trash()
    {
        return redirect()->route('admin.trash.index');
    }

    /**
     * Bulk Soft delete anggota
     */
    public function bulkDestroy(Request $request)
    {
        $this->checkRoleAuthorization();

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $query = Anggota::whereIn('id', $request->ids);
        
        // Cegah penghapusan data beda wilayah melalui request bulk
        $admin = auth()->guard('admin')->user();
        if (in_array($admin->category, ['ppkt', 'pkkt']) && !empty($admin->domisili)) {
            $query->where('domisili', $admin->domisili);
        }
        
        $deletedCount = $query->delete();

        return response()->json([
            'status' => 'deleted',
            'message' => $deletedCount . ' data anggota berhasil dihapus.'
        ]);
    }

    /**
     * Kembalikan anggota dari tong sampah
     */
    public function restore($id)
    {
        $this->checkRoleAuthorization();

        $anggota = Anggota::onlyTrashed()->findOrFail($id);
        $anggota->restore();

        return redirect()->back()->with('success', 'Akun anggota berhasil dikembalikan.');
    }

    /**
     * Hapus permanen anggota
     */
    public function forceDelete($id)
    {
        $this->checkRoleAuthorization();

        $anggota = Anggota::onlyTrashed()->findOrFail($id);

        // Hapus file-file yang terkait
        $files = [
            $anggota->surat_permohonan,
            $anggota->akte_pendirian_perusahaan,
            $anggota->nib_atau_tdup,
            $anggota->ktp_pimpinan,
            $anggota->npwp_perusahaan_file,
            $anggota->foto_diri,
            $anggota->foto_ktp,
        ];

        foreach ($files as $file) {
            if ($file && \Storage::disk('public')->exists($file)) {
                \Storage::disk('public')->delete($file);
            }
        }

        $anggota->forceDelete();

        return redirect()->back()->with('success', 'Data anggota dihapus permanen.');
    }

    public function downloadTemplate()
    {
        $this->checkRoleAuthorization();

        $fileName = 'Template_Import_Anggota.xls';

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Template</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body>';

        $html .= '<table style="font-family: Arial, sans-serif; border-collapse: collapse;">';

        // Baris kosong di atas
        $html .= '<tr><td colspan="2" style="height: 30px;"></td></tr>';

        // Baris: Judul
        $html .= '<tr>';
        $html .= '<td colspan="2" style="text-align: center; font-size: 16pt; font-weight: bold; padding: 15px; color: #0a2540;">TEMPLATE IMPORT ANGGOTA KARANG TARUNA</td>';
        $html .= '</tr>';

        // Baris: Petunjuk
        $html .= '<tr>';
        $html .= '<td colspan="2" style="text-align: center; font-size: 10pt; color: #666; padding-bottom: 20px;">
            <b>Petunjuk:</b> Isi kolom Username saja. Password dibuat otomatis oleh sistem.
        </td>';
        $html .= '</tr>';

        // Baris: Header Tabel
        $html .= '<tr>';
        $html .= '<th width="60" style="background-color: #0a2540; color: #ffd700; border: 1px solid #000; padding: 12px; text-align: center; font-weight: bold;">No</th>';
        $html .= '<th width="300" style="background-color: #0a2540; color: #ffd700; border: 1px solid #000; padding: 12px; text-align: left; font-weight: bold;">Username</th>';
        $html .= '</tr>';

        // 50 baris kosong untuk diisi
        for ($i = 1; $i <= 50; $i++) {
            $html .= '<tr>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 10px; text-align: center; height: 25px; background-color: #f9fafb;">' . $i . '</td>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 10px; height: 25px;"></td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Import anggota dari file Excel
     */
    public function import(Request $request)
    {
        $this->checkRoleAuthorization();

        $request->validate([
            'usernames' => 'required|string',
        ], [
            'usernames.required' => 'Daftar username wajib diisi (File kosong atau tidak terbaca).',
        ]);

        $admin = auth()->guard('admin')->user();
        
        $usernamesList = json_decode($request->usernames, true);
        
        if (!is_array($usernamesList) || empty($usernamesList)) {
            return redirect()->back()
                ->with('error', 'Format data tidak valid atau file kosong.');
        }

        $credentials = [];

        foreach ($usernamesList as $username) {
            $username = trim($username);
            $username = preg_replace('/[^a-zA-Z0-9_]/', '', $username);
            
            if (empty($username)) continue;
            if (strtolower($username) === 'no' || strtolower($username) === 'username') continue;

            // Cek apakah username sudah ada
            if (Anggota::where('username', $username)->exists()) {
                continue;
            }

            // Generate password unik
            $randomSuffix = str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $password = $username . $randomSuffix;

            // Buat anggota baru
            Anggota::create([
                'username' => $username,
                'nama_lengkap' => null,
                'email' => $username . '@karangtaruna.org',
                'jabatan' => null,
                'domisili' => null,
                'password' => Hash::make($password),
                'initial_password' => $password,
                'status' => 'pending_profile',
            ]);

            $credentials[] = [
                'username' => $username,
                'password' => $password,
            ];
        }

        $count = count($credentials);

        if ($count === 0) {
            return redirect()->back()
                ->with('error', 'Tidak ada anggota baru. Semua username di file mungkin sudah terdaftar atau format tidak valid.');
        }

        // Simpan credentials ke session untuk ditampilkan
        session()->put('exportable_credentials', $credentials);
        return redirect()->back()
            ->with('import_credentials', $credentials)
            ->with('success', "Berhasil mengimport {$count} anggota baru!");
    }



    /**
     * Export credentials hasil import ke CSV
     */
    public function exportCredentials(Request $request)
    {
        $this->checkRoleAuthorization();

        $credentials = $request->session()->get('exportable_credentials', []);

        if (empty($credentials)) {
            return redirect()->back()->with('error', 'Tidak ada kredensial untuk diexport.');
        }

        $fileName = 'Kredensial_Anggota_' . date('Ymd_His') . '.xls';

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Credentials</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></head>';
        $html .= '<body>';

        $html .= '<table style="font-family: Arial, sans-serif; border-collapse: collapse;">';

        // Header
        $html .= '<tr>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #000; padding: 12px; text-align: center; font-weight: bold;">No</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #000; padding: 12px; text-align: center; font-weight: bold;">Username</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #000; padding: 12px; text-align: center; font-weight: bold;">Password</th>';
        $html .= '</tr>';

        $no = 1;
        foreach ($credentials as $cred) {
            $html .= '<tr>';
            $html .= '<td style="border: 1px solid #000; padding: 10px; text-align: center;">' . $no++ . '</td>';
            $html .= '<td style="border: 1px solid #000; padding: 10px; font-family: monospace;">' . $cred['username'] . '</td>';
            $html .= '<td style="border: 1px solid #000; padding: 10px; font-family: monospace; font-weight: bold;">' . $cred['password'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}