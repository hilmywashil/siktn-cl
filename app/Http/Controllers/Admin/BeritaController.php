<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $beritas = Berita::latest()->paginate(10);
        
        return view('admin.berita.index', compact('admin', 'beritas'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('admin.berita.create', compact('admin'));
    }

    public function store(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'is_populer' => 'boolean',
            'is_active' => 'boolean',
            'tanggal_publish' => 'required|date',
        ]);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $gambar->getClientOriginalExtension();
            $path = $gambar->storeAs('berita', $filename, 'public');
            $validated['gambar'] = $path;
        }

        $validated['is_populer'] = $request->has('is_populer');
        $validated['is_active'] = $request->has('is_active');

        Berita::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    public function show(Berita $berita)
    {
        // Not used
    }

    public function edit($id)
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $berita = Berita::findOrFail($id);

        return view('admin.berita.edit', compact('admin', 'berita'));
    }

    public function update(Request $request, $id)
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'is_populer' => 'boolean',
            'is_active' => 'boolean',
            'tanggal_publish' => 'required|date',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }

            $gambar = $request->file('gambar');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $gambar->getClientOriginalExtension();
            $path = $gambar->storeAs('berita', $filename, 'public');
            $validated['gambar'] = $path;
        }

        $validated['is_populer'] = $request->has('is_populer');
        $validated['is_active'] = $request->has('is_active');

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diupdate!');
    }

    public function destroy($id)
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $berita = Berita::findOrFail($id);

        // Hapus gambar
        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}