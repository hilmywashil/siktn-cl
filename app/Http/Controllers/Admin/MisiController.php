<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Misi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $misi = Misi::ordered()->get();
        return view('admin.misi.index', compact('misi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.misi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ], [
            'title.required' => 'Judul misi wajib diisi',
            'title.max' => 'Judul misi maksimal 255 karakter',
            'description.required' => 'Deskripsi misi wajib diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
            'order.required' => 'Urutan wajib diisi',
            'order.integer' => 'Urutan harus berupa angka',
            'order.min' => 'Urutan minimal 0'
        ]);

        // Set is_active berdasarkan checkbox
        $validated['is_active'] = $request->has('is_active');

        // Handle upload gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('misi', $filename, 'public');
            $validated['image'] = $path;
        }

        // Simpan data
        Misi::create($validated);

        return redirect()->route('admin.misi.index')
            ->with('success', 'Misi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Misi $misi)
    {
        // Jika diperlukan untuk preview detail
        return view('admin.misi.show', compact('misi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Misi $misi)
    {
        return view('admin.misi.edit', compact('misi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Misi $misi)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ], [
            'title.required' => 'Judul misi wajib diisi',
            'title.max' => 'Judul misi maksimal 255 karakter',
            'description.required' => 'Deskripsi misi wajib diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
            'order.required' => 'Urutan wajib diisi',
            'order.integer' => 'Urutan harus berupa angka',
            'order.min' => 'Urutan minimal 0'
        ]);

        // Set is_active berdasarkan checkbox
        $validated['is_active'] = $request->has('is_active');

        // Handle upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($misi->image && Storage::disk('public')->exists($misi->image)) {
                Storage::disk('public')->delete($misi->image);
            }

            // Upload gambar baru
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('misi', $filename, 'public');
            $validated['image'] = $path;
        }

        // Update data
        $misi->update($validated);

        return redirect()->route('admin.misi.index')
            ->with('success', 'Misi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Misi $misi)
    {
        // Hapus gambar jika ada
        if ($misi->image && Storage::disk('public')->exists($misi->image)) {
            Storage::disk('public')->delete($misi->image);
        }

        // Hapus data
        $misi->delete();

        return redirect()->route('admin.misi.index')
            ->with('success', 'Misi berhasil dihapus!');
    }

    /**
     * Toggle status aktif/nonaktif
     */
    public function toggleStatus(Misi $misi)
    {
        $misi->update([
            'is_active' => !$misi->is_active
        ]);

        $status = $misi->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.misi.index')
            ->with('success', "Misi berhasil {$status}!");
    }

    /**
     * Bulk delete (hapus banyak sekaligus)
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.misi.index')
                ->with('error', 'Tidak ada data yang dipilih');
        }

        // Hapus gambar dari storage
        $misiList = Misi::whereIn('id', $ids)->get();
        foreach ($misiList as $misi) {
            if ($misi->image && Storage::disk('public')->exists($misi->image)) {
                Storage::disk('public')->delete($misi->image);
            }
        }

        // Hapus data
        Misi::whereIn('id', $ids)->delete();

        return redirect()->route('admin.misi.index')
            ->with('success', count($ids) . ' misi berhasil dihapus!');
    }

    /**
     * Reorder misi (atur ulang urutan)
     */
    public function reorder(Request $request)
    {
        $orders = $request->input('orders', []);

        foreach ($orders as $order) {
            Misi::where('id', $order['id'])->update(['order' => $order['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan misi berhasil diperbarui'
        ]);
    }
}