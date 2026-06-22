<?php
// app/Http/Controllers/Admin/OrganisasiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganisasiController extends Controller
{
    public function index()
    {
        $organisasi = Organisasi::ordered()->get();
        
        return view('admin.organisasi.index', [
            'activeMenu' => 'organisasi',
            'organisasi' => $organisasi
        ]);
    }

    public function create()
    {
        return view('admin.organisasi.create', [
            'activeMenu' => 'organisasi'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:ketua_umum,wakil_ketua_umum,ketua_bidang,sekretaris_umum,wakil_sekretaris_umum',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'boolean'
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('organisasi', 'public');
        }

        Organisasi::create($validated);

        return redirect()->route('admin.organisasi.index')
            ->with('success', 'Data organisasi berhasil ditambahkan');
    }

    public function edit(Organisasi $organisasi)
    {
        return view('admin.organisasi.edit', [
            'activeMenu' => 'organisasi',
            'organisasi' => $organisasi
        ]);
    }

    public function update(Request $request, Organisasi $organisasi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:ketua_umum,wakil_ketua_umum,ketua_bidang,sekretaris_umum,wakil_sekretaris_umum',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'boolean'
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($organisasi->foto) {
                Storage::disk('public')->delete($organisasi->foto);
            }
            $validated['foto'] = $request->file('foto')->store('organisasi', 'public');
        }

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