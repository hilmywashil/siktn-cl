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
            'existingNotulensiJuduls' => Notulensi::pluck('judul_rapat')->toArray(),
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
        $linkDrive = $validated['link_drive'] ?? null;

        if ($request->hasFile('file_pdf') && $request->file('file_pdf')->isValid()) {
            $uploadedFile = $request->file('file_pdf');
            $filePdfPath = $uploadedFile->store('notulensi_pdf', 'public');

            if (!$linkDrive) {
                try {
                    $driveService = new \App\Services\GoogleDriveService();
                    if ($driveService->isConfigured()) {
                        $safeJudul = preg_replace('/[^\w\-]/', '_', $validated['judul_rapat']);
                        $driveResult = $driveService->uploadFile($uploadedFile, $safeJudul . '_' . $uploadedFile->getClientOriginalName(), 'Notulensi Rapat');
                        if ($driveResult && !empty($driveResult['link'])) {
                            $linkDrive = $driveResult['link'];
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Google Drive Notulensi upload error: ' . $e->getMessage());
                }
            }
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
            'link_drive' => $linkDrive,
            'file_pdf' => $filePdfPath,
            'foto_dokumentasi' => !empty($fotoPaths) ? $fotoPaths : null,
            'created_by' => $admin->id,
        ]);

        $this->logActivity('notulensi', 'Tambah', $notulensi->id, $validated['judul_rapat']);

        return redirect()->route('admin.sekretariat.notulensi.index')->with('success', 'Notulensi Rapat berhasil ditambahkan.');
    }

    public function storeBulk(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $type = $request->input('bulk_type', 'files');

        if ($type === 'files') {
            $files = $request->file('files') ?? $request->file('pdf_files');
            if (empty($files)) {
                return redirect()->back()->with('error', 'Pilih minimal 1 berkas PDF Notulensi.');
            }
            if (!is_array($files)) {
                $files = [$files];
            }

            $titles = $request->input('judul_rapat', []);
            $dates = $request->input('tanggal_rapat', []);
            $leaders = $request->input('pemimpin_rapat', []);
            $summaries = $request->input('ringkasan_hasil', []);
            $drives = $request->input('link_drive', []);

            $createdCount = 0;
            foreach ($files as $idx => $file) {
                if ($file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $defaultTitle = ucwords(str_replace(['_', '-'], ' ', $originalName));

                    $cleanTitle = !empty($titles[$idx]) ? trim($titles[$idx]) : $defaultTitle;
                    $tanggalRapat = !empty($dates[$idx]) ? \Carbon\Carbon::parse(trim($dates[$idx]))->format('Y-m-d H:i:s') : \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                    $pemimpinRapat = !empty($leaders[$idx]) ? trim($leaders[$idx]) : null;
                    $ringkasan = !empty($summaries[$idx]) ? trim($summaries[$idx]) : null;
                    $linkDrive = !empty($drives[$idx]) ? trim($drives[$idx]) : null;

                    $pdfPath = $file->store('notulensi_pdf', 'public');

                    Notulensi::create([
                        'judul_rapat' => $cleanTitle,
                        'tanggal_rapat' => $tanggalRapat,
                        'pemimpin_rapat' => $pemimpinRapat,
                        'ringkasan_hasil' => $ringkasan,
                        'link_drive' => $linkDrive,
                        'file_pdf' => $pdfPath,
                        'created_by' => $admin->id,
                    ]);
                    $createdCount++;
                }
            }

            $this->logActivity('notulensi', 'Bulk Store', null, "Upload bulk {$createdCount} berkas notulensi PDF/Word");

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil mengunggah {$createdCount} Notulensi Rapat secara massal.",
                    'redirect' => route('admin.sekretariat.notulensi.index')
                ]);
            }

            return redirect()->route('admin.sekretariat.notulensi.index')->with('success', "Berhasil menambahkan {$createdCount} Notulensi Rapat secara massal.");

        } else {
            if ($request->filled('notulensi_rows')) {
                $rows = json_decode($request->notulensi_rows, true);
                if (is_array($rows) && count($rows) > 0) {
                    $importedCount = 0;
                    foreach ($rows as $row) {
                        $judulRapat = trim($row['judul_rapat'] ?? '');
                        if (empty($judulRapat)) continue;
                        $tanggalVal = trim($row['tanggal_rapat'] ?? '');
                        $tanggalRapat = !empty($tanggalVal) ? \Carbon\Carbon::parse($tanggalVal)->format('Y-m-d H:i:s') : \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                        $pemimpinRapat = !empty($row['pemimpin_rapat']) ? trim($row['pemimpin_rapat']) : null;
                        $ringkasan = !empty($row['ringkasan_hasil']) ? trim($row['ringkasan_hasil']) : null;
                        $linkDrive = !empty($row['link_drive']) ? trim($row['link_drive']) : null;

                        Notulensi::create([
                            'judul_rapat' => $judulRapat,
                            'tanggal_rapat' => $tanggalRapat,
                            'pemimpin_rapat' => $pemimpinRapat,
                            'ringkasan_hasil' => $ringkasan,
                            'link_drive' => $linkDrive,
                            'created_by' => $admin->id,
                        ]);
                        $importedCount++;
                    }
                    $this->logActivity('notulensi', 'Import Excel', null, "Import bulk {$importedCount} data notulensi rapat");
                    return redirect()->route('admin.sekretariat.notulensi.index')->with('success', "Berhasil mengimpor {$importedCount} data Notulensi Rapat dari file Excel.");
                }
            }

            $request->validate([
                'excel_file' => 'required|file|max:5120',
            ]);

            $file = $request->file('excel_file');
            $content = file_get_contents($file->getRealPath());
            $importedCount = 0;

            if (strpos($content, '<table') !== false) {
                // Parse HTML Table (.xls styled template)
                $dom = new \DOMDocument();
                @$dom->loadHTML($content);
                $rows = $dom->getElementsByTagName('tr');

                foreach ($rows as $rowIndex => $tr) {
                    if ($rowIndex === 0) continue; // Skip title row
                    $tds = $tr->getElementsByTagName('td');
                    if ($tds->length < 1) {
                        $tds = $tr->getElementsByTagName('th');
                    }
                    if ($tds->length === 0) continue;

                    $judulRapat = trim($tds->item(0)->nodeValue ?? '');
                    if (empty($judulRapat) || strtolower($judulRapat) === 'judul rapat' || stristr($judulRapat, 'TEMPLATE')) continue;

                    $tanggalVal = trim($tds->item(1)->nodeValue ?? '');
                    $tanggalRapat = !empty($tanggalVal) ? \Carbon\Carbon::parse($tanggalVal)->format('Y-m-d H:i:s') : \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                    $pemimpinRapat = $tds->item(2) ? trim($tds->item(2)->nodeValue) : null;
                    $ringkasan = $tds->item(3) ? trim($tds->item(3)->nodeValue) : null;
                    $linkDrive = $tds->item(4) ? trim($tds->item(4)->nodeValue) : null;

                    Notulensi::create([
                        'judul_rapat' => $judulRapat,
                        'tanggal_rapat' => $tanggalRapat,
                        'pemimpin_rapat' => $pemimpinRapat,
                        'ringkasan_hasil' => $ringkasan,
                        'link_drive' => $linkDrive,
                        'created_by' => $admin->id,
                    ]);
                    $importedCount++;
                }
            } else {
                // CSV Fallback
                $lines = file($file->getRealPath());
                if (count($lines) <= 1) {
                    return redirect()->back()->with('error', 'File Excel/CSV kosong atau format tidak sesuai.');
                }

                $header = array_shift($lines);

                foreach ($lines as $line) {
                    $row = str_getcsv($line);
                    if (empty($row[0])) continue;

                    $judulRapat = trim($row[0]);
                    if (strtolower($judulRapat) === 'judul rapat') continue;

                    $tanggalRapat = !empty($row[1]) ? \Carbon\Carbon::parse(trim($row[1]))->format('Y-m-d H:i:s') : \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                    $pemimpinRapat = !empty($row[2]) ? trim($row[2]) : null;
                    $ringkasan = !empty($row[3]) ? trim($row[3]) : null;
                    $linkDrive = !empty($row[4]) ? trim($row[4]) : null;

                    Notulensi::create([
                        'judul_rapat' => $judulRapat,
                        'tanggal_rapat' => $tanggalRapat,
                        'pemimpin_rapat' => $pemimpinRapat,
                        'ringkasan_hasil' => $ringkasan,
                        'link_drive' => $linkDrive,
                        'created_by' => $admin->id,
                    ]);
                    $importedCount++;
                }
            }

            $this->logActivity('notulensi', 'Import Excel', null, "Import bulk {$importedCount} data notulensi rapat");
            return redirect()->route('admin.sekretariat.notulensi.index')->with('success', "Berhasil mengimpor {$importedCount} data Notulensi Rapat dari file Excel.");
        }
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/Template_Import_Notulensi_Rapat.xls');
        if (!file_exists($filePath)) {
            $filePath = public_path('templates/TemplateNotulensi.xls');
        }
        if (!file_exists($filePath)) {
            $filePath = base_path('TemplateNotulensi.xls');
        }

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Template_Import_Notulensi_Rapat.xls', [
                'Content-Type' => 'application/vnd.ms-excel',
            ]);
        }

        return redirect()->back()->with('error', 'Berkas template contoh tidak ditemukan.');
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
