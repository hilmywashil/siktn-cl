<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class AnggotaController extends Controller
{
    /**
     * =========================
     * DETAIL ANGGOTA (PUBLIC)
     * =========================
     */
    public function show(Anggota $anggota)
    {
        if ($anggota->status !== 'approved') {
            abort(404);
        }

        return view('pages.details.buku-detail', compact('anggota'));
    }

    /**
     * =========================
     * STORE PENDAFTARAN ANGGOTA
     * =========================
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== FORM SUBMISSION STARTED ===');
            Log::info('Request Data:', $request->except(['_token', 'password', 'foto_ktp', 'foto_diri']));

            // Validasi input - SESUAI MODEL PERSONAL ANGGOTA
            $validated = $request->validate([
                'nama_usaha' => 'required|string|max:255',
                'nrp' => 'required|string|max:255',
                'angkatan' => 'required|integer',
                'gender' => 'required|string|in:laki-laki,perempuan',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date|before_or_equal:today',
                'agama' => 'required|string|max:255',
                'no_telp' => 'required|string|max:255',
                'alamat_domisili' => 'required|string|max:1000',
                'kode_pos' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:anggota,email_website_perusahaan',
                'no_ktp' => 'required|string|max:255',
                'foto_ktp' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120',
                'foto_diri' => 'required|file|mimes:jpeg,jpg,png|max:5120',
                
                // Organisasi
                'sfc_hipmi' => 'nullable|string|max:255',
                'ref_hipmi' => 'required|string|in:Ya,Tidak',
                'aktif_org_lain' => 'required|string|in:Ya,Tidak',
            ], [
                // Custom error messages
                'email.unique' => 'Email ini sudah terdaftar.',
                'foto_ktp.mimes' => 'Format file KTP harus berupa jpeg, jpg, png, atau pdf.',
                'foto_diri.mimes' => 'Format foto diri harus berupa jpeg, jpg, atau png.',
                'foto_ktp.max' => 'Ukuran file KTP maksimal 5MB.',
                'foto_diri.max' => 'Ukuran foto diri maksimal 5MB.',
            ]);

            Log::info('Validation passed');

            // Start database transaction
            DB::beginTransaction();

            try {
                // Generate random password
                $passwordPlain = Str::random(12);
                Log::info('Password generated');

                // Upload files dengan error handling
                Log::info('Starting file uploads...');
                $filePaths = [];
                
                $fileFields = [
                    'foto_ktp' => 'anggota/foto_ktp',
                    'foto_diri' => 'anggota/foto_diri',
                ];

                foreach ($fileFields as $field => $path) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        if ($file->isValid()) {
                            $filePaths[$field] = $file->store($path, 'public');
                            Log::info("File {$field} uploaded: {$filePaths[$field]}");
                        } else {
                            throw new Exception("File {$field} tidak valid");
                        }
                    } else {
                        throw new Exception("File {$field} tidak ditemukan");
                    }
                }

                Log::info('All files uploaded successfully');

                // Create anggota record
                Log::info('Creating anggota record...');
                $anggota = Anggota::create([
                    // AUTH & LOGIN Compatibility
                    'nama_perusahaan' => $validated['nama_usaha'],
                    'email_website_perusahaan' => $validated['email'],
                    'telepon_wa_perusahaan' => $validated['no_telp'],
                    
                    // Backwards Compatibility for other fields
                    'nama_pimpinan' => $validated['nama_usaha'],
                    'email_pimpinan' => $validated['email'],
                    'telepon_wa_pimpinan' => $validated['no_telp'],
                    'alamat_pimpinan' => $validated['alamat_domisili'],
                    
                    // Personal fields
                    'nrp' => $validated['nrp'],
                    'angkatan' => $validated['angkatan'],
                    'nama_lengkap' => $validated['nama_usaha'],
                    'gender' => $validated['gender'],
                    'tempat_lahir_personal' => $validated['tempat_lahir'],
                    'tanggal_lahir' => $validated['tanggal_lahir'],
                    'agama' => $validated['agama'],
                    'no_telp' => $validated['no_telp'],
                    'alamat_domisili' => $validated['alamat_domisili'],
                    'kode_pos' => $validated['kode_pos'],
                    'email' => $validated['email'],
                    'no_ktp' => $validated['no_ktp'],
                    'foto_ktp' => $filePaths['foto_ktp'],
                    'foto_diri' => $filePaths['foto_diri'],
                    
                    // Organization
                    'sfc_hipmi' => $validated['sfc_hipmi'] ?? null,
                    'ref_hipmi' => $validated['ref_hipmi'],
                    'aktif_org_lain' => $validated['aktif_org_lain'],

                    // AUTH & STATUS
                    'password' => Hash::make($passwordPlain),
                    'initial_password' => $passwordPlain,
                    'status' => 'pending',
                ]);

                Log::info('Anggota created successfully', ['id' => $anggota->id]);

                // Commit transaction
                DB::commit();

                // Auto login setelah registrasi
                Auth::guard('anggota')->login($anggota, true);
                Log::info('Auto login successful');

                // Regenerate session
                $request->session()->regenerate();

                // Flash password ke session
                session()->flash('generated_password', $passwordPlain);

                Log::info('=== REGISTRATION SUCCESSFUL ===');
                return redirect()->route('registration-success');

            } catch (Exception $e) {
                // Rollback transaction
                DB::rollBack();
                
                // Delete uploaded files if any
                foreach ($filePaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
                
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors()
            ]);
            
            return back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (Exception $e) {
            Log::error('=== REGISTRATION FAILED ===', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi atau hubungi administrator. Detail: ' . $e->getMessage()]);
        }
    }

    /**
     * =========================
     * REGISTRATION SUCCESS PAGE
     * =========================
     */
    public function registrationSuccess()
    {
        // Pastikan user sudah login
        if (!Auth::guard('anggota')->check()) {
            return redirect()->route('home')
                ->with('error', 'Sesi anda telah berakhir');
        }

        // Pastikan password masih ada di session
        if (!session()->has('generated_password')) {
            return redirect()->route('profile-anggota')
                ->with('info', 'Password hanya ditampilkan saat registrasi');
        }

        $anggota = Auth::guard('anggota')->user();
        return view('pages.registration-success', compact('anggota'));
    }

    /**
     * =========================
     * PROFILE ANGGOTA
     * =========================
     */
    public function profile()
    {
        $anggota = Auth::guard('anggota')->user();

        if (!$anggota) {
            return redirect()->route('anggota.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('pages.profile-anggota', compact('anggota'));
    }

    /**
     * =========================
     * UPDATE PROFILE
     * =========================
     */
    public function updateProfile(Request $request)
    {
        $anggota = Auth::guard('anggota')->user();

        $validated = $request->validate([
            'nik' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'alamat_lengkap' => 'required|string|max:1000',
            'pendidikan_terakhir' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'riwayat_organisasi' => 'required|string',
            'kompetensi' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:anggota,email,' . $anggota->id,
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'foto_diri' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
        ], [
            '*.mimes' => 'Format foto harus JPG atau PNG',
            '*.max' => 'Ukuran file maksimal adalah 5MB',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain',
        ]);

        // Upload foto jika ada
        $filePaths = [];
        if ($request->hasFile('foto_diri')) {
            $file = $request->file('foto_diri');
            if ($file->isValid()) {
                if ($anggota->foto_diri && Storage::disk('public')->exists($anggota->foto_diri)) {
                    Storage::disk('public')->delete($anggota->foto_diri);
                }
                $filePaths['foto_diri'] = $file->store('anggota/foto_diri', 'public');
            }
        }

        $updateData = array_merge($validated, $filePaths);
        
        // Update status ke pending_verification jika masih pending_profile
        if ($anggota->status === 'pending_profile') {
            $updateData['status'] = 'pending_verification';
        }

        $anggota->update($updateData);

        $message = $anggota->status === 'pending_profile' 
            ? 'Profil berhasil disimpan dan diajukan untuk verifikasi.'
            : 'Profil berhasil diperbarui.';

        return back()->with('success', $message);
    }

    /**
     * =========================
     * GANTI PASSWORD
     * =========================
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $anggota = Auth::guard('anggota')->user();

        if (!Hash::check($request->current_password, $anggota->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $anggota->update([
            'password' => Hash::make($request->new_password),
            'initial_password' => null,
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * =============================================
     * SHOW FORM — MEMBER YANG SUDAH ADA, BELUM DAFTAR WEB
     * =============================================
     */
    public function showMemberRegister()
    {
        if (Auth::guard('anggota')->check()) {
            return redirect()->route('profile-anggota')
                ->with('info', 'Anda sudah terdaftar dan login sebagai anggota.');
        }

        return view('pages.member-register');
    }

    /**
     * =============================================
     * STORE — MEMBER YANG SUDAH ADA, BELUM DAFTAR WEB
     * Form simpel: tanpa upload dokumen, tanpa data pimpinan lengkap
     * =============================================
     */
    public function storeMember(Request $request)
    {
        try {
            Log::info('=== MEMBER REGISTER FORM SUBMITTED ===');

            $validated = $request->validate([
                // DATA PERUSAHAAN
                'nama_perusahaan'           => 'required|string|max:255',
                'trade_mark'                => 'required|string|max:255',
                'tanggal_lahir'             => 'required|date|before_or_equal:today',
                'alamat_kantor'             => 'required|string|max:1000',
                'telepon_wa_perusahaan'     => 'required|string|max:30',
                'email_website_perusahaan'  => 'required|email|max:255|unique:anggota,email_website_perusahaan',

                // LEGALITAS
                'nia'                       => 'required|string|max:255',
                'nomor_induk_berusaha_tdup' => 'required|string|max:255',
                'npwp_perusahaan'           => 'required|string|max:255',

                // BIO / PRODUK USAHA
                'bio_perusahaan'            => 'required|string|max:2000',
            ], [
                'tanggal_lahir.required'            => 'Tanggal berdiri perusahaan wajib diisi',
                'tanggal_lahir.date'                => 'Format tanggal tidak valid',
                'tanggal_lahir.before_or_equal'     => 'Tanggal berdiri tidak boleh di masa depan',
                'email_website_perusahaan.unique'   => 'Email perusahaan sudah terdaftar, silakan gunakan email lain',
                'nia.required'                      => 'NIA (Nomor Induk Anggota) wajib diisi',
                'bio_perusahaan.required'           => 'Produk usaha / bio perusahaan wajib diisi',
            ]);

            Log::info('Member register validation passed');

            DB::beginTransaction();

            try {
                $passwordPlain = Str::random(12);

                // Kolom NOT NULL yang tidak ada di form ini diisi otomatis
                // agar tidak perlu ubah struktur database
                $anggota = Anggota::create([
                    // DATA PERUSAHAAN
                    'nama_perusahaan'           => $validated['nama_perusahaan'],
                    'trade_mark'                => $validated['trade_mark'],
                    'tanggal_lahir'             => $validated['tanggal_lahir'],
                    'alamat_kantor'             => $validated['alamat_kantor'],
                    'telepon_wa_perusahaan'     => $validated['telepon_wa_perusahaan'],
                    'email_website_perusahaan'  => $validated['email_website_perusahaan'],

                    // DATA PIMPINAN — otomatis dari data perusahaan karena NOT NULL
                    'nama_pimpinan'             => $validated['nama_perusahaan'],
                    'alamat_pimpinan'           => $validated['alamat_kantor'],
                    'telepon_wa_pimpinan'       => $validated['telepon_wa_perusahaan'],
                    'email_pimpinan'            => $validated['email_website_perusahaan'],

                    // LEGALITAS — NIA disimpan di kolom akte_notaris
                    'akte_notaris'              => $validated['nia'],
                    'nomor_induk_berusaha_tdup' => $validated['nomor_induk_berusaha_tdup'],
                    'npwp_perusahaan'           => $validated['npwp_perusahaan'],

                    // BIO disimpan sebagai JSON di produk_usaha_yang_akan_dijual
                    'produk_usaha_yang_akan_dijual' => [$validated['bio_perusahaan']],

                    // DOKUMEN — placeholder string karena kolom NOT NULL
                    'surat_permohonan'          => '-',
                    'akte_pendirian_perusahaan' => '-',
                    'nib_atau_tdup'             => $validated['nomor_induk_berusaha_tdup'],
                    'ktp_pimpinan'              => '-',
                    'npwp_perusahaan_file'      => $validated['npwp_perusahaan'],

                    // AUTH & STATUS
                    'password'          => Hash::make($passwordPlain),
                    'initial_password'  => $passwordPlain,
                    'status'            => 'pending',
                ]);

                Log::info('Member anggota created', ['id' => $anggota->id]);

                DB::commit();

                // Auto login langsung setelah daftar
                Auth::guard('anggota')->login($anggota, true);
                $request->session()->regenerate();

                // Flash kredensial — hanya ditampilkan sekali
                session()->flash('generated_password', $passwordPlain);

                Log::info('=== MEMBER REGISTER SUCCESS ===');
                return redirect()->route('registration-success');

            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Member register validation failed', ['errors' => $e->errors()]);
            return back()->withInput()->withErrors($e->errors());

        } catch (Exception $e) {
            Log::error('=== MEMBER REGISTER FAILED ===', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi. Detail: ' . $e->getMessage()]);
        }
    }
}