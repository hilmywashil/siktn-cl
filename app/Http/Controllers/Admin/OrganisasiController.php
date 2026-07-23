<?php
// app/Http/Controllers/Admin/OrganisasiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\Jabatan;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganisasiController extends Controller
{
    use LogsAdminActivity;
    public function index()
    {
        $organisasi = Organisasi::ordered()->get();

        // Build jabatan tree with members
        $jabatans = Jabatan::orderBy('urutan')->get();

        // Map urutan -> jabatan for quick lookup
        $jabatanByUrutan = $jabatans->keyBy('urutan');

        // Map urutan -> org members
        $orgByUrutan = $organisasi->groupBy('urutan'); // grouped by urutan to be 100% unique per node

        // Build tree: jabatan with their members
        $jabatanTree = $jabatans->map(function ($jab) use ($orgByUrutan) {
            $jab->members = $orgByUrutan->get($jab->urutan, collect());
            $jab->children = collect(); // to be filled below
            return $jab;
        })->keyBy('urutan');

        $roots = collect();
        foreach ($jabatanTree as $urutan => $jab) {
            $parts = explode('.', $urutan);
            array_pop($parts);
            $parentUrutan = implode('.', $parts);

            if ($parentUrutan && isset($jabatanTree[$parentUrutan])) {
                $jabatanTree[$parentUrutan]->children->push($jab);
                $jab->atasan_id = $jabatanTree[$parentUrutan]->id;
            } else {
                $roots->push($jab);
                $jab->atasan_id = null;
            }
        }

        return view('admin.organisasi.index', [
            'activeMenu' => 'organisasi',
            'organisasi' => $organisasi,
            'organisasiByUrutan' => $organisasi->groupBy('urutan'),
            'jabatanTree' => $roots,
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
            'atasan_id' => 'nullable|exists:jabatans,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aktif' => 'boolean'
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('organisasi', 'public');
        }

        $jabatanNama = $validated['jabatan'];
        $atasanId = $validated['atasan_id'] ?? null;
        $newUrutan = '1';

        if ($atasanId) {
            $atasan = \App\Models\Jabatan::find($atasanId);
            if ($atasan) {
                $parentUrutan = $atasan->urutan;
                $children = \App\Models\Jabatan::where('urutan', 'like', $parentUrutan . '.%')->get()
                    ->filter(function($j) use ($parentUrutan) {
                        $parts = explode('.', $j->urutan);
                        $parentParts = explode('.', $parentUrutan);
                        return count($parts) === count($parentParts) + 1;
                    });

                if ($children->count() > 0) {
                    $maxChild = $children->sortByDesc(function($j) {
                        $parts = explode('.', $j->urutan);
                        return end($parts);
                    })->first();

                    $parts = explode('.', $maxChild->urutan);
                    $last = array_pop($parts);
                    
                    if (is_numeric($last)) {
                        $nextLast = (int)$last + 1;
                    } else {
                        $nextLast = ++$last;
                    }
                    $newUrutan = implode('.', $parts) . '.' . $nextLast;
                } else {
                    $newUrutan = $parentUrutan . '.a';
                }
            }
        } else {
            $roots = \App\Models\Jabatan::whereNot('urutan', 'like', '%.%')->get();
            if ($roots->count() > 0) {
                $maxRoot = $roots->sortByDesc(function($j) {
                    return (int)$j->urutan;
                })->first();
                $newUrutan = (string)((int)$maxRoot->urutan + 1);
            }
        }

        // Bikin record Jabatan baru untuk anggota ini (1 card 1 anggota)
        \App\Models\Jabatan::create([
            'nama_jabatan' => $jabatanNama,
            'urutan' => $newUrutan
        ]);

        $validated['urutan'] = $newUrutan;
        $validated['kategori'] = $jabatanNama; // Backward compatibility
        unset($validated['atasan_id']);

        Organisasi::create($validated);

        $org = Organisasi::latest()->first();
        $this->logActivity('organisasi', 'Tambah', $org?->id, $validated['nama'], $validated['jabatan']);

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

        // Sinkronisasi title di tabel jabatans
        $organisasiJabatan = Jabatan::where('urutan', $organisasi->urutan)->first();
        if ($organisasiJabatan) {
            $organisasiJabatan->update(['nama_jabatan' => $validated['jabatan']]);
        }

        $validated['kategori'] = $validated['jabatan']; // Backward compatibility
        // Jangan timpa urutannya, biarkan urutan lama tetap dipakai

        $organisasi->update($validated);

        $this->logActivity('organisasi', 'Edit', $organisasi->id, $organisasi->nama, $organisasi->jabatan);

        return redirect()->route('admin.organisasi.index')
            ->with('success', 'Data organisasi berhasil diperbarui');
    }

    public function destroy(Organisasi $organisasi)
    {
        // Delete photo if exists
        if ($organisasi->foto) {
            Storage::disk('public')->delete($organisasi->foto);
        }

        $label = $organisasi->nama;
        $id = $organisasi->id;
        $organisasi->delete();

        $this->logActivity('organisasi', 'Hapus', $id, $label);

        return redirect()->route('admin.organisasi.index')
            ->with('success', 'Data organisasi berhasil dihapus');
    }
}