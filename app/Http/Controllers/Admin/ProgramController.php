<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Jabatan;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    /**
     * Check if the authenticated admin has authorization.
     * Hanya Super Admin dan PNKT yang berhak mengelola Program.
     */
    private function checkAuthorization()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->isSuperAdmin() && !$admin->isPNKT()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Sekretariat Nasional (PNKT) yang berhak mengelola Program Kerja.');
        }
    }

    /**
     * Tampilkan daftar seluruh program
     */
    public function index()
    {
        $this->checkAuthorization();
        $programs = Program::with('jabatan')->orderBy('created_at', 'desc')->get();
        return view('admin.program.index', compact('programs'));
    }

    /**
     * Tampilkan form pembuatan program baru
     */
    public function create()
    {
        $this->checkAuthorization();
        // Ambil data jabatan unik untuk dropdown Bidang
        $jabatans = Jabatan::all()->unique('nama_jabatan');
        
        // Ambil seluruh data anggota untuk dropdown PIC (Select2 Tags)
        $anggotas = Anggota::select('nama_lengkap')->get();
        
        return view('admin.program.create', compact('jabatans', 'anggotas'));
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
            'status' => 'required|in:Perencanaan,Berjalan,Selesai',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'pic' => 'required|string|max:255',
            'target_output' => 'required|string',
            'anggaran' => 'nullable|numeric|min:0',
            'mitra' => 'required_if:kategori,CSR|nullable|string|max:255',
            'jabatan_id' => 'required_if:kategori,Bidang|nullable|exists:jabatans,id',
        ], [
            'mitra.required_if' => 'Nama Mitra wajib diisi untuk program CSR.',
            'jabatan_id.required_if' => 'Bidang wajib dipilih untuk program Bidang.',
            'periode_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal menambahkan program. Silakan periksa form Anda.');
        }

        // Simpan data
        Program::create([
            'nama_program' => $request->nama_program,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'periode_mulai' => $request->periode_mulai,
            'periode_selesai' => $request->periode_selesai,
            'pic' => $request->pic,
            'target_output' => $request->target_output,
            'anggaran' => $request->anggaran,
            'mitra' => $request->kategori == 'CSR' ? $request->mitra : null,
            'jabatan_id' => $request->kategori == 'Bidang' ? $request->jabatan_id : null,
        ]);

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Program $program)
    {
        $this->checkAuthorization();
        $jabatans = Jabatan::all()->unique('nama_jabatan');
        $anggotas = Anggota::select('nama_lengkap')->get();
        
        return view('admin.program.edit', compact('program', 'jabatans', 'anggotas'));
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
            'status' => 'required|in:Perencanaan,Berjalan,Selesai',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'pic' => 'required|string|max:255',
            'target_output' => 'required|string',
            'anggaran' => 'nullable|numeric|min:0',
            'mitra' => 'required_if:kategori,CSR|nullable|string|max:255',
            'jabatan_id' => 'required_if:kategori,Bidang|nullable|exists:jabatans,id',
        ], [
            'mitra.required_if' => 'Nama Mitra wajib diisi untuk program CSR.',
            'jabatan_id.required_if' => 'Bidang wajib dipilih untuk program Bidang.',
            'periode_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal memperbarui program. Silakan periksa form Anda.');
        }

        $program->update([
            'nama_program' => $request->nama_program,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'periode_mulai' => $request->periode_mulai,
            'periode_selesai' => $request->periode_selesai,
            'pic' => $request->pic,
            'target_output' => $request->target_output,
            'anggaran' => $request->anggaran,
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
        $program->delete();
        return redirect()->route('admin.program.index')->with('success', 'Program berhasil dihapus.');
    }
}
