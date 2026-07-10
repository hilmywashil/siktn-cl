<?php
// app/Http/Controllers/Admin/OrganisasiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganisasiController extends Controller
{
    public function index()
    {
        $organisasi = Organisasi::ordered()->get();
        $organisasiByUrutan = $organisasi->groupBy('urutan');
        
        return view('admin.organisasi.index', [
            'activeMenu' => 'organisasi',
            'organisasi' => $organisasi,
            'organisasiByUrutan' => $organisasiByUrutan
        ]);
    }

    public function create()
    {
        $jabatans = Jabatan::all();
        return view('admin.organisasi.create', [
            'activeMenu' => 'organisasi',
            'jabatans' => $jabatans
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aktif' => 'boolean'
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('organisasi', 'public');
        }

        $masterJabatan = Jabatan::where('nama_jabatan', $validated['jabatan'])->first();
        $validated['urutan'] = $masterJabatan ? $masterJabatan->urutan : 999;
        $validated['kategori'] = $validated['jabatan']; // Backward compatibility

        Organisasi::create($validated);

        return redirect()->route('admin.organisasi.index')
            ->with('success', 'Data organisasi berhasil ditambahkan');
    }

    public function edit(Organisasi $organisasi)
    {
        $jabatans = Jabatan::all();
        return view('admin.organisasi.edit', [
            'activeMenu' => 'organisasi',
            'organisasi' => $organisasi,
            'jabatans' => $jabatans
        ]);
    }

    public function update(Request $request, Organisasi $organisasi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aktif' => 'boolean'
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($organisasi->foto) {
                Storage::disk('public')->delete($organisasi->foto);
            }
            $validated['foto'] = $request->file('foto')->store('organisasi', 'public');
        }

        $masterJabatan = Jabatan::where('nama_jabatan', $validated['jabatan'])->first();
        $validated['urutan'] = $masterJabatan ? $masterJabatan->urutan : 999;
        $validated['kategori'] = $validated['jabatan']; // Backward compatibility

        $organisasi->update($validated);

        return redirect()->route('admin.organisasi.index')
            ->with('success', 'Data organisasi berhasil diperbarui');
    }

    public function destroy(Organisasi $organisasi)
    {
        // Delete photo if exists
        if ($organisasi->foto) {
            Storage::disk('public')->delete($organisasi->foto);
        }

        $organisasi->delete();

        return redirect()->route('admin.organisasi.index')
            ->with('success', 'Data organisasi berhasil dihapus');
    }
}