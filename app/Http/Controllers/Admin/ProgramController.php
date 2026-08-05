<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Jabatan;
use App\Models\Anggota;
use App\Models\Organisasi;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    use LogsAdminActivity;
    /**
     * Check if the authenticated admin has authorization.
     * Pimpinan hanya bisa melihat (viewOnly), sedangkan PNKT bisa mengelola (CRUD).
     */
    private function checkAuthorization($viewOnly = false)
    {
        $admin = Auth::guard('admin')->user();
        
        // Super Admin selalu bisa akses semua
        if ($admin->isSuperAdmin()) {
            return;
        }

        // PNKT bisa akses semua
        if ($admin->isPNKT()) {
            return;
        }

        // Pimpinan hanya bisa view (index)
        if ($viewOnly && $admin->isPimpinan()) {
            return;
        }

        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
    }

    /**
     * Tampilkan daftar seluruh program
     */
    public function index(Request $request)
    {
        $this->checkAuthorization(true); // true means viewOnly is allowed
        
        $query = Program::with('jabatan')->withCount('peserta');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_program', 'like', "%{$search}%")
                  ->orWhere('mitra', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->get('kategori'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $programs = $query->orderBy('created_at', 'desc')->get();
        
        // Unfiltered stats for top cards
        $stats = [
            'total' => Program::count(),
            'perencanaan' => Program::where('status', 'Perencanaan')->count(),
            'berjalan' => Program::where('status', 'Berjalan')->count(),
            'selesai' => Program::where('status', 'Selesai')->count(),
        ];

        $activeMenu = 'program';
        return view('admin.program.index', compact('programs', 'stats', 'activeMenu'));
    }

    /**
     * Tampilkan form pembuatan program baru
     */
    public function create()
    {
        $this->checkAuthorization();
        // Ambil data jabatan unik untuk dropdown Bidang / Program Kerja
        $jabatans = Jabatan::all()->unique('nama_jabatan');
        
        // Ambil daftar Program Kerja (kategori Bidang) untuk pilihan link CSR
        $programKerjas = Program::where('kategori', 'Bidang')->orderBy('nama_program', 'asc')->get();

        // Ambil seluruh data anggota untuk dropdown PIC default
        $anggotaNames = Anggota::pluck('nama_lengkap')->toArray();
        $organisasiNames = Organisasi::pluck('nama')->toArray();
        $picOptions = collect(array_merge($anggotaNames, $organisasiNames))->filter()->unique()->values();
        
        $activeMenu = 'program';
        return view('admin.program.create', compact('jabatans', 'programKerjas', 'picOptions', 'activeMenu'));
    }

    /**
     * Simpan program ke database
     */
    public function store(Request $request)
    {
        $this->checkAuthorization();
        $validator = Validator::make($request->all(), [
            'nama_program' => 'required|string|max:255',
            'kategori' => 'required|in:CSR,Bidang',
            'program_kerja_id' => 'nullable|exists:programs,id',
            'status' => 'required_if:kategori,Bidang|in:Perencanaan,Berjalan,Selesai',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'pic' => 'required|string|max:255',
            'target_output' => 'required|string',
            'anggaran' => 'nullable|numeric|min:0',
            'mitra' => 'required_if:kategori,CSR|nullable|string|max:255',
            'jabatan_id' => 'required_if:kategori,Bidang|nullable|exists:jabatans,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'mitra.required_if' => 'Nama Mitra wajib diisi untuk program CSR.',
            'jabatan_id.required_if' => 'Bidang wajib dipilih untuk program Kerja.',
            'periode_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal menambahkan program. Silakan periksa form Anda.');
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Pastikan folder ada di disk public
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('programs');
            $file->storeAs('programs', $filename, 'public');
            $gambarPath = $filename;
        }

        // Simpan data
        Program::create([
            'nama_program' => $request->nama_program,
            'kategori' => $request->kategori,
            'program_kerja_id' => $request->kategori == 'CSR' ? $request->program_kerja_id : null,
            'status' => $request->kategori == 'Bidang' ? $request->status : 'Berjalan',
            'periode_mulai' => $request->periode_mulai,
            'periode_selesai' => $request->periode_selesai,
            'pic' => $request->pic,
            'target_output' => $request->target_output,
            'anggaran' => $request->kategori == 'Bidang' ? $request->anggaran : null,
            'gambar' => $gambarPath,
            'mitra' => $request->kategori == 'CSR' ? $request->mitra : null,
            'jabatan_id' => $request->kategori == 'Bidang' ? $request->jabatan_id : null,
        ]);

        $newProgram = Program::latest()->first();
        $this->logActivity('program', 'Tambah', $newProgram?->id, $request->nama_program, 'Kategori: ' . $request->kategori);

        // Notifikasi ke Seluruh Anggota Aktif saat Program Baru Diterbitkan
        try {
            if ($newProgram) {
                $anggotas = Anggota::where('status', 'approved')->get();
                if ($anggotas->count() > 0) {
                    \Illuminate\Support\Facades\Notification::send($anggotas, new \App\Notifications\ProgramNotification($newProgram));
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim notifikasi program baru: ' . $e->getMessage());
        }

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail program
     */
    public function show(Program $program)
    {
        $this->checkAuthorization(true); // true means viewOnly is allowed

        $program->load('jabatan');
        $activeMenu = 'program';

        return view('admin.program.show', compact('program', 'activeMenu'));
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Program $program)
    {
        $this->checkAuthorization();
        $jabatans = Jabatan::all()->unique('nama_jabatan');
        
        // Ambil daftar Program Kerja (kategori Bidang) kecuali program ini sendiri
        $programKerjas = Program::where('kategori', 'Bidang')->where('id', '!=', $program->id)->orderBy('nama_program', 'asc')->get();

        // Cek PIC berdasarkan Kategori/Jabatan
        $picOptions = [];
        if ($program->kategori == 'Bidang' && $program->jabatan_id) {
            $jab = Jabatan::find($program->jabatan_id);
            if ($jab) {
                $orgNames = Organisasi::where('jabatan', $jab->nama_jabatan)->pluck('nama')->toArray();
                $picOptions = collect($orgNames)->filter()->unique()->values();
            }
        } else {
            $anggotaNames = Anggota::pluck('nama_lengkap')->toArray();
            $organisasiNames = Organisasi::pluck('nama')->toArray();
            $picOptions = collect(array_merge($anggotaNames, $organisasiNames))->filter()->unique()->values();
        }
        
        $activeMenu = 'program';
        return view('admin.program.edit', compact('program', 'jabatans', 'programKerjas', 'picOptions', 'activeMenu'));
    }

    /**
     * Update data program
     */
    public function update(Request $request, Program $program)
    {
        $this->checkAuthorization();
        $validator = Validator::make($request->all(), [
            'nama_program' => 'required|string|max:255',
            'kategori' => 'required|in:CSR,Bidang',
            'program_kerja_id' => 'nullable|exists:programs,id',
            'status' => 'required_if:kategori,Bidang|in:Perencanaan,Berjalan,Selesai',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'pic' => 'required|string|max:255',
            'target_output' => 'required|string',
            'anggaran' => 'nullable|numeric|min:0',
            'mitra' => 'required_if:kategori,CSR|nullable|string|max:255',
            'jabatan_id' => 'required_if:kategori,Bidang|nullable|exists:jabatans,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ], [
            'mitra.required_if' => 'Nama Mitra wajib diisi untuk program CSR.',
            'jabatan_id.required_if' => 'Bidang wajib dipilih untuk program Bidang.',
            'periode_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal memperbarui program. Silakan periksa form Anda.');
        }

        $gambarPath = $program->getRawOriginal('gambar'); // ambil nilai mentah, bukan accessor
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Pastikan folder ada di disk public
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('programs');
            
            // Hapus gambar lama kalau ada
            if ($gambarPath && \Illuminate\Support\Facades\Storage::disk('public')->exists('programs/' . $gambarPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('programs/' . $gambarPath);
            }
            
            $file->storeAs('programs', $filename, 'public');
            $gambarPath = $filename;
        }

        $program->update([
            'nama_program' => $request->nama_program,
            'kategori' => $request->kategori,
            'program_kerja_id' => $request->kategori == 'CSR' ? $request->program_kerja_id : null,
            'status' => $request->kategori == 'Bidang' ? $request->status : 'Berjalan',
            'periode_mulai' => $request->periode_mulai,
            'periode_selesai' => $request->periode_selesai,
            'pic' => $request->pic,
            'target_output' => $request->target_output,
            'anggaran' => $request->kategori == 'Bidang' ? $request->anggaran : null,
            'gambar' => $gambarPath,
            'mitra' => $request->kategori == 'CSR' ? $request->mitra : null,
            'jabatan_id' => $request->kategori == 'Bidang' ? $request->jabatan_id : null,
        ]);

        $this->logActivity('program', 'Edit', $program->id, $program->nama_program, 'Status: ' . $request->status);

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil diperbarui.');
    }

    /**
     * Hapus data program
     */
    public function destroy(Program $program)
    {
        $this->checkAuthorization();

        if ($program->gambar && \Illuminate\Support\Facades\Storage::exists('public/programs/' . $program->gambar)) {
            \Illuminate\Support\Facades\Storage::delete('public/programs/' . $program->gambar);
        }

        $label = $program->nama_program;
        $id = $program->id;
        $program->delete();

        $this->logActivity('program', 'Hapus', $id, $label);

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil dihapus.');
    }

    /**
     * Hapus banyak program sekaligus (Bulk Delete)
     */
    public function bulkDestroy(Request $request)
    {
        $this->checkAuthorization();
        
        $ids = $request->input('ids');
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada program yang dipilih untuk dihapus.');
        }

        $programs = Program::whereIn('id', $ids)->get();
        foreach ($programs as $program) {
            if ($program->getRawOriginal('gambar') && \Illuminate\Support\Facades\Storage::disk('public')->exists('programs/' . $program->getRawOriginal('gambar'))) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('programs/' . $program->getRawOriginal('gambar'));
            }
            $program->delete();
        }

        return redirect()->route('admin.program.index')->with('success', count($ids) . ' program berhasil dihapus.');
    }

    /**
     * API untuk mengambil list PIC berdasarkan Bidang/Jabatan (Struktur Organisasi)
     */
    public function getPicsByJabatan(Request $request)
    {
        $this->checkAuthorization();
        
        $jabatanId = $request->query('jabatan_id');
        
        if (!$jabatanId) {
            // Jika kosong / CSR, kembalikan semua anggota & organisasi
            $anggotaNames = Anggota::pluck('nama_lengkap')->toArray();
            $organisasiNames = Organisasi::pluck('nama')->toArray();
            $allNames = collect(array_merge($anggotaNames, $organisasiNames))->filter()->unique()->values();
            return response()->json($allNames);
        }

        $jabatan = Jabatan::find($jabatanId);
        if (!$jabatan) {
            return response()->json([]);
        }

        // Ambil nama dari Struktur Organisasi yang jabatannya sesuai urutan
        $orgNames = Organisasi::where('jabatan', $jabatan->nama_jabatan)->pluck('nama')->toArray();
        $names = collect($orgNames)->filter()->unique()->values();
        
        return response()->json($names);
    }

    /**
     * Update status program via tombol aksi
     */
    public function updateStatus(Request $request, Program $program)
    {
        $this->checkAuthorization();
        
        $request->validate([
            'status' => 'required|in:Perencanaan,Berjalan,Selesai'
        ]);

        $program->update([
            'status' => $request->status
        ]);

        $this->logActivity('program', 'Ubah Status', $program->id, $program->nama_program, $request->status);

        return redirect()->back()->with('success', 'Status program berhasil diubah menjadi ' . $request->status . '.');
    }

    /**
     * Export data peserta program ke file Excel (.xls) dengan Styling SIKTN Navy & Gold
     */
    public function exportPeserta(Program $program)
    {
        $this->checkAuthorization(true);

        $pesertas = $program->peserta()->orderBy('nama_lengkap', 'asc')->get();
        $colSpan = 8;
        $fileName = 'Peserta_Program_' . \Str::slug($program->nama_program) . '_' . date('Ymd_His') . '.xls';

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Peserta Program SIKTN</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body style="font-family: Arial, sans-serif;">';
        $html .= '<table style="border-collapse: collapse; width: 100%;">';
        $html .= '<colgroup>';
        $html .= '<col style="width: 50px;">';
        $html .= '<col style="width: 180px;">';
        $html .= '<col style="width: 220px;">';
        $html .= '<col style="width: 140px;">';
        $html .= '<col style="width: 200px;">';
        $html .= '<col style="width: 160px;">';
        $html .= '<col style="width: 220px;">';
        $html .= '<col style="width: 150px;">';
        $html .= '</colgroup>';

        // Title Header Banner SIKTN (Navy Blue & Gold) - Directly on Row 1
        $html .= '<tr>';
        $html .= '<td colspan="' . $colSpan . '" style="background-color: #0a2540; color: #ffd700; font-size: 16pt; font-weight: bold; text-align: center; padding: 16px; border: 2px solid #0a2540;">SISTEM INFORMASI KARANG TARUNA NASIONAL (SIKTN)</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="' . $colSpan . '" style="background-color: #164e63; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; padding: 10px; border: 1px solid #164e63;">DAFTAR PESERTA ANGGOTA - PROGRAM: ' . htmlspecialchars(strtoupper($program->nama_program)) . ' (' . strtoupper($program->kategori) . ')</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="' . $colSpan . '" style="font-size: 9pt; color: #64748b; text-align: right; padding: 6px; font-style: italic;">Tanggal Export: ' . date('d F Y H:i:s') . ' WIB</td>';
        $html .= '</tr>';
        $html .= '<tr><td colspan="' . $colSpan . '" style="height: 10px;"></td></tr>';

        // Header Table Columns (Navy Blue Header with Gold Text)
        $html .= '<tr>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt; width: 50px;">NO</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt; width: 180px;">NIA</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt; width: 220px;">NAMA LENGKAP</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt; width: 140px;">USERNAME</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt; width: 200px;">WILAYAH / DOMISILI</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt; width: 160px;">NO WHATSAPP</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt; width: 220px;">EMAIL</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt; width: 150px;">TANGGAL JOIN</th>';
        $html .= '</tr>';

        // Data Rows
        if ($pesertas->isEmpty()) {
            $html .= '<tr><td colspan="' . $colSpan . '" style="text-align: center; padding: 20px; color: #64748b; font-style: italic; border: 1px solid #cbd5e1;">Belum ada peserta terdaftar pada program ini.</td></tr>';
        } else {
            foreach ($pesertas as $i => $item) {
                $rowBg = ($i % 2 == 1) ? '#f8fafc' : '#ffffff';

                $html .= '<tr style="background-color: ' . $rowBg . ';">';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">' . ($i + 1) . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-weight: bold; mso-number-format:\'@\';">' . htmlspecialchars($item->nik ?? '-') . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold;">' . htmlspecialchars($item->nama_lengkap) . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px;">' . htmlspecialchars($item->username) . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px;">' . htmlspecialchars($item->domisili ?? '-') . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; mso-number-format:\'@\';">' . htmlspecialchars($item->no_hp ?? '-') . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px;">' . htmlspecialchars($item->email) . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">' . ($item->pivot->created_at ? $item->pivot->created_at->format('d/m/Y H:i') : '-') . '</td>';
                $html .= '</tr>';
            }
        }

        // Summary Footer Row
        $html .= '<tr><td colspan="' . $colSpan . '" style="height: 10px;"></td></tr>';
        $html .= '<tr>';
        $html .= '<td colspan="' . $colSpan . '" style="background-color: #f1f5f9; color: #0a2540; font-size: 9.5pt; font-weight: bold; padding: 10px; border: 1px solid #cbd5e1;">TOTAL PESERTA TERDAFTAR: ' . $pesertas->count() . ' ANGGOTA</td>';
        $html .= '</tr>';

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    public function getPesertaList(Program $program)
    {
        try {
            $peserta = $program->peserta()
                ->select('anggota.id', 'anggota.nama_lengkap', 'anggota.username', 'anggota.email', 'anggota.no_hp', 'anggota.domisili', 'anggota.foto_diri')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_lengkap' => $item->nama_lengkap,
                        'username' => $item->username,
                        'email' => $item->email,
                        'no_hp' => $item->no_hp ?? '-',
                        'domisili' => $item->domisili ?? '-',
                        'status' => isset($item->pivot->status) ? $item->pivot->status : 'approved',
                        'tanggal_daftar' => $item->pivot->created_at ? $item->pivot->created_at->format('d M Y H:i') : '-'
                    ];
                });

            return response()->json([
                'success' => true,
                'program_nama' => $program->nama_program,
                'peserta' => $peserta
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update participant approval status (ACC / Tolak)
     */
    public function updatePesertaStatus(\Illuminate\Http\Request $request, Program $program, \App\Models\Anggota $anggota)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $program->peserta()->updateExistingPivot($anggota->id, [
            'status' => $request->status
        ]);

        $statusLabel = [
            'approved' => 'DISETUJUI (ACC)',
            'rejected' => 'DITOLAK',
            'pending' => 'PENDING'
        ];

        return response()->json([
            'success' => true,
            'message' => 'Status pendaftaran ' . $anggota->nama_lengkap . ' berhasil diperbarui menjadi ' . ($statusLabel[$request->status] ?? strtoupper($request->status))
        ]);
    }
}
