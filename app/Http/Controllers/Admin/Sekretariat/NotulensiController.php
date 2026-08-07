<?php

namespace App\Http\Controllers\Admin\Sekretariat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notulensi;
use App\Models\Agenda;
use App\Traits\LogsAdminActivity;
use Illuminate\Support\Facades\Auth;

class NotulensiController extends Controller
{
    use LogsAdminActivity;
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Notulensi::with('agenda');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_rapat', 'like', "%{$search}%")
                  ->orWhere('pemimpin_rapat', 'like', "%{$search}%")
                  ->orWhere('ringkasan_hasil', 'like', "%{$search}%");
            });
        }

        $notulensis = $query->orderBy('tanggal_rapat', 'desc')->paginate(10)->appends($request->query());
        $agendas = Agenda::where('waktu_mulai', '>=', \Carbon\Carbon::now()->startOfDay())
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        return view('admin.sekretariat.notulensi.index', [
            'activeMenu' => 'sekretariat_notulensi',
            'admin' => $admin,
            'notulensis' => $notulensis,
            'agendas' => $agendas,
        ]);
    }

    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'agenda_id' => 'nullable|exists:agendas,id',
            'judul_rapat' => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'pemimpin_rapat' => 'nullable|string|max:255',
            'ringkasan_hasil' => 'nullable|string',
            'link_drive' => 'nullable|url',
            'file_pdf' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'foto_dokumentasi' => 'nullable|array',
            'foto_dokumentasi.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $filePdfPath = null;
        if ($request->hasFile('file_pdf') && $request->file('file_pdf')->isValid()) {
            $filePdfPath = $request->file('file_pdf')->store('notulensi_pdf', 'public');
        }

        $fotoPaths = [];
        if ($request->hasFile('foto_dokumentasi')) {
            foreach ($request->file('foto_dokumentasi') as $foto) {
                if ($foto->isValid()) {
                    $fotoPaths[] = $foto->store('notulensi_dokumentasi', 'public');
                }
            }
        }

        $notulensi = Notulensi::create([
            'agenda_id' => $validated['agenda_id'] ?? null,
            'judul_rapat' => $validated['judul_rapat'],
            'tanggal_rapat' => $validated['tanggal_rapat'],
            'pemimpin_rapat' => $validated['pemimpin_rapat'] ?? null,
            'ringkasan_hasil' => $validated['ringkasan_hasil'] ?? null,
            'link_drive' => $validated['link_drive'] ?? null,
            'file_pdf' => $filePdfPath,
            'foto_dokumentasi' => !empty($fotoPaths) ? $fotoPaths : null,
            'created_by' => $admin->id,
        ]);

        $this->logActivity('notulensi', 'Tambah', $notulensi->id, $validated['judul_rapat']);

        return redirect()->route('admin.sekretariat.notulensi.index')->with('success', 'Notulensi Rapat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $notulensi = Notulensi::findOrFail($id);

        $validated = $request->validate([
            'agenda_id' => 'nullable|exists:agendas,id',
            'judul_rapat' => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'pemimpin_rapat' => 'nullable|string|max:255',
            'ringkasan_hasil' => 'nullable|string',
            'link_drive' => 'nullable|url',
            'file_pdf' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'foto_dokumentasi' => 'nullable|array',
            'foto_dokumentasi.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        if ($request->hasFile('file_pdf') && $request->file('file_pdf')->isValid()) {
            if ($notulensi->file_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($notulensi->file_pdf)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($notulensi->file_pdf);
            }
            $validated['file_pdf'] = $request->file('file_pdf')->store('notulensi_pdf', 'public');
        }

        if ($request->hasFile('foto_dokumentasi')) {
            $newFotoPaths = is_array($notulensi->foto_dokumentasi) ? $notulensi->foto_dokumentasi : [];
            foreach ($request->file('foto_dokumentasi') as $foto) {
                if ($foto->isValid()) {
                    $newFotoPaths[] = $foto->store('notulensi_dokumentasi', 'public');
                }
            }
            $validated['foto_dokumentasi'] = $newFotoPaths;
        }

        $notulensi->update($validated);

        $this->logActivity('notulensi', 'Edit', $notulensi->id, $notulensi->judul_rapat);

        return redirect()->route('admin.sekretariat.notulensi.index')->with('success', 'Notulensi Rapat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $notulensi = Notulensi::findOrFail($id);
        $label = $notulensi->judul_rapat;
        $notId = $notulensi->id;

        if ($notulensi->file_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($notulensi->file_pdf)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($notulensi->file_pdf);
        }
        if (is_array($notulensi->foto_dokumentasi)) {
            foreach ($notulensi->foto_dokumentasi as $foto) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($foto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($foto);
                }
            }
        }

        $notulensi->delete();

        $this->logActivity('notulensi', 'Hapus', $notId, $label);

        return redirect()->route('admin.sekretariat.notulensi.index')->with('success', 'Notulensi Rapat berhasil dihapus.');
    }

    /**
     * Hapus Banyak Notulensi (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:notulensis,id',
        ]);

        $items = Notulensi::whereIn('id', $request->ids)->get();
        $count = $items->count();

        foreach ($items as $notulensi) {
            if ($notulensi->file_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($notulensi->file_pdf)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($notulensi->file_pdf);
            }
            if (is_array($notulensi->foto_dokumentasi)) {
                foreach ($notulensi->foto_dokumentasi as $foto) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($foto)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($foto);
                    }
                }
            }
            $this->logActivity('notulensi', 'Hapus (Bulk)', $notulensi->id, $notulensi->judul_rapat);
            $notulensi->delete();
        }

        return redirect()->back()->with('success', "{$count} Notulensi Rapat berhasil dihapus.");
    }

    /**
     * Download Banyak Notulensi sekaligus (Bulk Download ZIP)
     */
    public function bulkDownload(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:notulensis,id',
        ]);

        $notulensis = Notulensi::whereIn('id', $request->ids)->get();
        if ($notulensis->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada Notulensi terpilih.');
        }

        $zipName = 'notulensi-terpilih-' . time() . '.zip';
        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $zipPath = $tempDir . '/' . $zipName;

        $zip = new \ZipArchive;
        $fileCount = 0;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($notulensis as $notulensi) {
                $filePath = $notulensi->file_dokumen ?? null;
                if ($filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                    $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($filePath);
                    $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $cleanName = \Illuminate\Support\Str::slug($notulensi->judul_rapat);
                    $inZipName = $cleanName . '.' . $extension;
                    $zip->addFile($fullPath, $inZipName);
                    $fileCount++;
                }
            }
            $zip->close();
        }

        if ($fileCount === 0) {
            if (file_exists($zipPath)) unlink($zipPath);
            return redirect()->back()->with('error', 'Notulensi yang dipilih tidak memiliki berkas dokumen terunggah.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
