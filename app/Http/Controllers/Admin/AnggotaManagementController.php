<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Display a listing of anggota
     */
    public function index(Request $request)
    {
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
        return view('admin.anggota.create');
    }

    /**
     * Store a newly created anggota (Akun Awal by Sekretariat)
     */
    public function store(Request $request)
    {
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
     * Show detail anggota
     */
    public function show(Anggota $anggota)
    {
        $this->checkAnggotaAccess($anggota);
        return view('admin.anggota.show', compact('anggota'));
    }

    /**
     * Update data anggota
     */
    public function update(Request $request, Anggota $anggota)
    {
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
    public function approve(Anggota $anggota)
    {
        $this->checkAnggotaAccess($anggota);
        
        $admin = auth()->guard('admin')->user();

        $anggota->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Anggota berhasil disetujui!');
    }

    /**
     * Reject anggota
     */
    public function reject(Request $request, Anggota $anggota)
    {
        $this->checkAnggotaAccess($anggota);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $admin = auth()->guard('admin')->user();

        $anggota->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => $admin->id,
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
        $this->checkAnggotaAccess($anggota);
        
        $anggota->delete();

        return redirect()
            ->route('admin.anggota.list')
            ->with('success', 'Data anggota berhasil dihapus (masuk tong sampah).');
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
            'status' => 'success',
            'message' => $deletedCount . ' data anggota berhasil dihapus (masuk tong sampah).'
        ]);
    }

    /**
     * Kembalikan anggota dari tong sampah
     */
    public function restore($id)
    {
        $anggota = Anggota::onlyTrashed()->findOrFail($id);
        $anggota->restore();

        return redirect()->back()->with('success', 'Akun anggota berhasil dikembalikan.');
    }

    /**
     * Hapus permanen anggota
     */
    public function forceDelete($id)
    {
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
}