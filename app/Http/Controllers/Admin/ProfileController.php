<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        $jabatans = \App\Models\Jabatan::select('nama_jabatan')
            ->distinct()
            ->whereNotNull('nama_jabatan')
            ->where('nama_jabatan', '!=', '')
            ->orderBy('nama_jabatan')
            ->get();
        
        return view('admin.profile.index', [
            'admin' => $admin,
            'jabatans' => $jabatans,
            'activeMenu' => 'profile'
        ]);
    }

    public function updateJabatan(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        
        $validated = $request->validate([
            'jabatan_diajukan' => ['required', 'string', 'max:255'],
        ]);

        $jabatanNama = $validated['jabatan_diajukan'];
        $isKetum = (strtolower(trim($jabatanNama)) === 'ketua umum');
        $isPNKT = $admin->isPNKT() || $admin->category === 'pnkt';

        // Aturan:
        // PNKT memilih selain Ketum -> langsung approved & auto-assign ke struktur organisasi!
        // PNKT memilih Ketum -> pending (perlu ACC Super Admin)
        // PPKT & PKKT memilih jabatan apapun -> pending (perlu ACC PNKT / Super Admin)
        if ($isPNKT && !$isKetum) {
            $admin->update([
                'jabatan_diajukan' => $jabatanNama,
                'status_jabatan' => 'approved',
                'keterangan_jabatan' => 'Disetujui Otomatis (Akses Pengurus Pusat PNKT)',
            ]);

            // Auto install ke Organisasi
            $this->assignAdminToOrganisasi($admin, $jabatanNama);

            return redirect()->route('admin.profile')
                ->with('success', "Jabatan '{$jabatanNama}' berhasil ditetapkan dan terpasang di Struktur Organisasi!");
        }

        // Selain itu (PPKT, PKKT, atau PNKT yang ajukan Ketum) -> status PENDING
        $admin->update([
            'jabatan_diajukan' => $jabatanNama,
            'status_jabatan' => 'pending',
            'keterangan_jabatan' => 'Menunggu Verifikasi & ACC dari Pengurus Pusat / Super Admin.',
        ]);

        // Send notification to Super Admin & PNKT admins
        try {
            $superAdminsAndPnkt = \App\Models\Admin::whereIn('category', ['super_admin', 'pnkt'])->get();
            if ($superAdminsAndPnkt->count() > 0) {
                \Illuminate\Support\Facades\Notification::send(
                    $superAdminsAndPnkt,
                    new \App\Notifications\AdminNotification(
                        'pengajuan_jabatan',
                        'Pengajuan Jabatan Baru',
                        $admin->name . ' mengajukan posisi ' . $jabatanNama . ' (' . ($admin->domisili ?? 'Daerah') . ').',
                        ['url' => route('admin.verifikasi-jabatan.index')]
                    )
                );
            }
        } catch (\Throwable $e) {
            // Log or ignore gracefully
        }

        return redirect()->route('admin.profile')
            ->with('success', "Pengajuan jabatan '{$jabatanNama}' telah dikirim. Menunggu verifikasi (ACC) dari Pengurus Pusat / Super Admin.");
    }

    private function assignAdminToOrganisasi($admin, string $namaJabatan)
    {
        $jabatanObj = \App\Models\Jabatan::where('nama_jabatan', $namaJabatan)->first();
        $urutan = $jabatanObj ? $jabatanObj->urutan : '99';

        $provinsi = 'Nasional';
        $kabupaten = null;

        if ($admin->isPPKT() || $admin->category === 'bpd') {
            $provinsi = $admin->domisili ?? 'Jawa Barat';
        } elseif ($admin->isPKKT() || $admin->category === 'bpc') {
            $kabupaten = $admin->domisili ?? 'Kabupaten Bandung';
            $provinsi = \App\Helpers\WilayahHelper::getProvinsiFromDomisili($kabupaten);
        }

        $org = \App\Models\Organisasi::where('provinsi', $provinsi)
            ->where('kabupaten', $kabupaten)
            ->where(function($q) use ($namaJabatan, $urutan) {
                $q->where('urutan', $urutan)->orWhere('jabatan', $namaJabatan);
            })->first();

        if ($org) {
            $org->update([
                'nama' => $admin->name,
                'jabatan' => $namaJabatan,
                'foto' => $admin->photo ?? $admin->foto_profil,
                'aktif' => true,
            ]);
        } else {
            \App\Models\Organisasi::create([
                'nama' => $admin->name,
                'jabatan' => $namaJabatan,
                'urutan' => (string) $urutan,
                'kategori' => 'Pengurus Harian',
                'provinsi' => $provinsi,
                'kabupaten' => $kabupaten,
                'foto' => $admin->photo ?? $admin->foto_profil,
                'aktif' => true,
            ]);
        }
    }

    public function update(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:admins,username,' . $admin->id],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
        ]);

        $admin->update($validated);

        return redirect()->route('admin.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $admin->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Password berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ], [
            'photo.required' => 'Silakan pilih foto terlebih dahulu.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpeg, png, atau jpg.',
            'photo.max' => 'Ukuran foto maksimal 10MB.',
        ]);

        // Delete old photo if exists
        if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
            Storage::disk('public')->delete($admin->photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('admin-photos', 'public');

        $admin->update(['photo' => $path]);

        return redirect()->route('admin.profile')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function deletePhoto()
    {
        $admin = auth()->guard('admin')->user();

        if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
            Storage::disk('public')->delete($admin->photo);
        }

        $admin->update(['photo' => null]);

        return redirect()->route('admin.profile')
            ->with('success', 'Foto profil berhasil dihapus!');
    }
}