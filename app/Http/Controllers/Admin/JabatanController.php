<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    /**
     * Check if the authenticated admin has authorization.
     */
    private function checkAuthorization()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->isSuperAdmin() && !$admin->isPNKT()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan PNKT yang berhak mengelola Master Jabatan.');
        }
    }

    public function index()
    {
        $this->checkAuthorization();
        
        $jabatans = Jabatan::orderBy('urutan', 'asc')->orderBy('nama_jabatan', 'asc')->get();
        $jabatansByUrutan = $jabatans->groupBy('urutan');
        return view('admin.jabatan.index', [
            'jabatans' => $jabatans,
            'jabatansByUrutan' => $jabatansByUrutan,
            'activeMenu' => 'jabatan'
        ]);
    }

    public function create()
    {
        $this->checkAuthorization();
        
        return view('admin.jabatan.create', [
            'activeMenu' => 'jabatan'
        ]);
    }

    public function store(Request $request)
    {
        $this->checkAuthorization();
        
        $request->validate([
            'nama_jabatan' => 'required|string|max:255|unique:jabatans,nama_jabatan',
            'urutan' => 'required|integer|min:1'
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique' => 'Nama jabatan ini sudah ada.',
            'urutan.required' => 'Urutan jabatan wajib diisi.',
            'urutan.integer' => 'Urutan jabatan harus berupa angka.'
        ]);

        Jabatan::create($request->only('nama_jabatan', 'urutan'));

        return redirect()->route('admin.jabatan.index')->with('success', 'Master Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        $this->checkAuthorization();
        
        return view('admin.jabatan.edit', [
            'jabatan' => $jabatan,
            'activeMenu' => 'jabatan'
        ]);
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $this->checkAuthorization();
        
        $request->validate([
            'nama_jabatan' => 'required|string|max:255|unique:jabatans,nama_jabatan,' . $jabatan->id,
            'urutan' => 'required|integer|min:1'
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique' => 'Nama jabatan ini sudah ada.',
            'urutan.required' => 'Urutan jabatan wajib diisi.',
            'urutan.integer' => 'Urutan jabatan harus berupa angka.'
        ]);

        $jabatan->update($request->only('nama_jabatan', 'urutan'));

        return redirect()->route('admin.jabatan.index')->with('success', 'Master Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $this->checkAuthorization();
        
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')->with('success', 'Master Jabatan berhasil dihapus.');
    }
}
