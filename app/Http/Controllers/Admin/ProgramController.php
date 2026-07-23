<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Jabatan;
use App\Models\Anggota;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
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
        
        $query = Program::with('jabatan');

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
        // Ambil data jabatan unik untuk dropdown Bidang
        $jabatans = Jabatan::all()->unique('nama_jabatan');
        
        // Ambil seluruh data anggota untuk dropdown PIC default
        $anggotaNames = Anggota::pluck('nama_lengkap')->toArray();
        $organisasiNames = Organisasi::pluck('nama')->toArray();
        $picOptions = collect(array_merge($anggotaNames, $organisasiNames))->filter()->unique()->values();
        
        $activeMenu = 'program';
        return view('admin.program.create', compact('jabatans', 'picOptions', 'activeMenu'));
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
            'status' => 'required_if:kategori,Bidang|in:Perencanaan,Berjalan,Selesai',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'pic' => 'required|string|max:255',
            'target_output' => 'required|string',
            'anggaran' => 'nullable|numeric|min:0',
            'mitra' => 'required_if:kategori,CSR|nullable|string|max:255',
            'jabatan_id' => 'required_if:kategori,Bidang|nullable|exists:jabatans,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'mitra.required_if' => 'Nama Mitra wajib diisi untuk program CSR.',
            'jabatan_id.required_if' => 'Bidang wajib dipilih untuk program Bidang.',
            'periode_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 5MB.',
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
        return view('admin.program.edit', compact('program', 'jabatans', 'picOptions', 'activeMenu'));
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
            'status' => 'required_if:kategori,Bidang|in:Perencanaan,Berjalan,Selesai',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'pic' => 'required|string|max:255',
            'target_output' => 'required|string',
            'anggaran' => 'nullable|numeric|min:0',
            'mitra' => 'required_if:kategori,CSR|nullable|string|max:255',
            'jabatan_id' => 'required_if:kategori,Bidang|nullable|exists:jabatans,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'mitra.required_if' => 'Nama Mitra wajib diisi untuk program CSR.',
            'jabatan_id.required_if' => 'Bidang wajib dipilih untuk program Bidang.',
            'periode_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 5MB.',
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

        $program->delete();
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

        return redirect()->back()->with('success', 'Status program berhasil diubah menjadi ' . $request->status . '.');
    }
}
