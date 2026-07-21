<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * Export data anggota ke CSV
     */
    public function export(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        
        // Pengecekan hak akses sesuai request user: Pimpinan, PNKT, Super Admin
        if (!in_array($admin->category, ['super_admin', 'pimpinan', 'pnkt'])) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses untuk mengekspor data.');
        }

        $query = Anggota::query()->latest();

        // Terapkan filter domisili
        $query = $this->applyDomisiliFilter($query);

        // Terapkan pencarian dan filter jika ada (sama seperti di index)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('domisili', 'like', "%{$search}%");
            });
        }
        
        // Handle filter status_approval (same mapping as index method)
        if ($request->filled('status') && $request->status !== 'Semua') {
            $statusMap = [
                'Belum Lengkapi Profil' => 'pending_profile',
                'Menunggu Approve' => 'pending_approval',
                'Disetujui' => 'approved',
                'Ditolak' => 'rejected',
            ];
            
            if (isset($statusMap[$request->status])) {
                $query->where('status', $statusMap[$request->status]);
            }
        }

        $anggotas = $query->get();

        $fileName = 'Data_Anggota_Karang_Taruna_' . date('Ymd_His') . '.xls';

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Data Anggota</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body>';
        
        // Header Judul Laporan (Mulai di baris 3, kolom C)
        $html .= '<table style="font-family: Arial, sans-serif; border-collapse: collapse;">';
        
        // Baris 1 & 2 Kosong
        $html .= '<tr><td colspan="10" style="border: none;"></td></tr>';
        $html .= '<tr><td colspan="10" style="border: none;"></td></tr>';

        // Baris 3: Judul
        $html .= '<tr>';
        $html .= '<td style="border: none; width: 20px;"></td>'; // Kolom A
        $html .= '<td style="border: none; width: 20px;"></td>'; // Kolom B
        $html .= '<td colspan="8" style="text-align: center; font-size: 16pt; font-weight: bold; padding: 10px;">LAPORAN DATA ANGGOTA KARANG TARUNA</td>';
        $html .= '</tr>';
        
        // Baris 4: Waktu Ekspor
        $html .= '<tr>';
        $html .= '<td style="border: none;"></td>';
        $html .= '<td style="border: none;"></td>';
        $html .= '<td colspan="8" style="text-align: center; font-size: 11pt; color: #666; padding-bottom: 20px;">Diekspor pada: ' . date('d M Y H:i:s') . '</td>';
        $html .= '</tr>';
        
        // Baris 5: Header Tabel
        $html .= '<tr>';
        $html .= '<td style="border: none;"></td>'; // Kolom A
        $html .= '<td style="border: none;"></td>'; // Kolom B
        
        $headersList = [
            ['label' => 'No', 'width' => '50'],
            ['label' => 'Nama Lengkap', 'width' => '250'],
            ['label' => 'Username', 'width' => '150'],
            ['label' => 'Email', 'width' => '250'],
            ['label' => 'Jabatan', 'width' => '180'],
            ['label' => 'Domisili', 'width' => '150'],
            ['label' => 'Status Approval', 'width' => '180'],
            ['label' => 'Tanggal Daftar', 'width' => '180']
        ];

        foreach ($headersList as $head) {
            $html .= '<th width="' . $head['width'] . '" style="background-color: #0a2540; color: #ffd700; border: 1px solid #000000; padding: 12px; text-align: center; font-weight: bold; height: 30px; vertical-align: middle;">' . $head['label'] . '</th>';
        }
        $html .= '</tr>';

        // Isi Tabel
        $no = 1;
        foreach ($anggotas as $anggota) {
            $statusText = match($anggota->status) {
                'pending_profile' => 'Belum Lengkapi Profil',
                'pending_approval' => 'Menunggu Approve',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                default => $anggota->status
            };

            // Beri warna khusus untuk status Disetujui / Ditolak biar lebih rapih
            $statusColor = match($anggota->status) {
                'approved' => 'color: #059669; font-weight: bold;',
                'rejected' => 'color: #dc2626; font-weight: bold;',
                'pending_approval' => 'color: #d97706; font-weight: bold;',
                default => 'color: #4b5563;'
            };

            $html .= '<tr>';
            $html .= '<td style="border: none;"></td>'; // Kolom A
            $html .= '<td style="border: none;"></td>'; // Kolom B
            $html .= '<td style="border: 1px solid #000000; padding: 10px; text-align: center; vertical-align: middle; height: 25px;">' . $no++ . '</td>';
            $html .= '<td style="border: 1px solid #000000; padding: 10px; vertical-align: middle; height: 25px;">' . $anggota->nama_lengkap . '</td>';
            $html .= '<td style="border: 1px solid #000000; padding: 10px; vertical-align: middle; height: 25px;">' . $anggota->username . '</td>';
            $html .= '<td style="border: 1px solid #000000; padding: 10px; vertical-align: middle; height: 25px;">' . $anggota->email . '</td>';
            $html .= '<td style="border: 1px solid #000000; padding: 10px; vertical-align: middle; height: 25px;">' . ($anggota->jabatan ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #000000; padding: 10px; vertical-align: middle; height: 25px;">' . ($anggota->domisili ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #000000; padding: 10px; text-align: center; vertical-align: middle; height: 25px; ' . $statusColor . '">' . $statusText . '</td>';
            $html .= '<td style="border: 1px solid #000000; padding: 10px; text-align: center; vertical-align: middle; height: 25px;">' . ($anggota->created_at ? $anggota->created_at->format('d/m/Y H:i') : '-') . '</td>';
            $html .= '</tr>';
        }

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