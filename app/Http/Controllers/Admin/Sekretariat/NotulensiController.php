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
        $fileName = 'Template_Import_Notulensi_Rapat.xls';
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="utf-8">';
        $html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Notulensi Rapat</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; font-family: Calibri, Arial, sans-serif; font-size: 11pt; }';
        $html .= 'th { background-color: #022648; color: #ffffff; font-weight: bold; text-align: center; padding: 10px 14px; border: 1px solid #cbd5e1; white-space: nowrap; }';
        $html .= 'td { padding: 9px 12px; border: 1px solid #cbd5e1; vertical-align: middle; }';
        $html .= '.title-row { background-color: #022648; color: #b7830f; font-size: 14pt; font-weight: bold; text-align: center; padding: 14px; border: 1px solid #022648; }';
        $html .= '</style>';
        $html .= '</head><body>';
        $html .= '<table border="1" style="border: 1px solid #cbd5e1;">';
        $html .= '<colgroup>';
        $html .= '<col width="280" style="width: 280px;">';
        $html .= '<col width="180" style="width: 180px;">';
        $html .= '<col width="240" style="width: 240px;">';
        $html .= '<col width="480" style="width: 480px;">';
        $html .= '<col width="400" style="width: 400px;">';
        $html .= '</colgroup>';
        $html .= '<tr><td colspan="5" class="title-row">TEMPLATE IMPORT NOTULENSI RAPAT - SIKTN</td></tr>';
        $html .= '<thead><tr>';
        $html .= '<th width="280" style="width: 280px;">JUDUL RAPAT</th>';
        $html .= '<th width="180" style="width: 180px;">TANGGAL RAPAT</th>';
        $html .= '<th width="240" style="width: 240px;">PEMIMPIN RAPAT</th>';
        $html .= '<th width="480" style="width: 480px;">RINGKASAN HASIL RAPAT</th>';
        $html .= '<th width="400" style="width: 400px;">LINK GOOGLE DRIVE</th>';
        $html .= '</tr></thead><tbody>';
        
        $examples = [
            [
                'judul' => 'Rapat Kerja Sekretariat Daerah',
                'tanggal' => '2026-08-08 14:00',
                'pemimpin' => 'Ketua Umum Karang Taruna',
                'ringkasan' => 'Penetapan alokasi anggaran dan jadwal kegiatan semester II tahun 2026.',
                'drive' => 'https://drive.google.com/file/d/example-notulensi-1'
            ],
            [
                'judul' => 'Rapat Koordinasi Bidang Humas & Publikasi',
                'tanggal' => '2026-08-10 09:30',
                'pemimpin' => 'Sekretaris Umum',
                'ringkasan' => 'Pembentukan tim pengelola media sosial dan penyusunan buletin bulanan.',
                'drive' => 'https://drive.google.com/file/d/example-notulensi-2'
            ],
            [
                'judul' => 'Rapat Evaluasi Program Pemberdayaan Pemuda',
                'tanggal' => '2026-08-15 13:00',
                'pemimpin' => 'Kepala Bidang Pemuda',
                'ringkasan' => 'Evaluasi pencapaian target pelatihan kewirausahaan pemuda kelurahan.',
                'drive' => 'https://drive.google.com/file/d/example-notulensi-3'
            ],
            [
                'judul' => 'Rapat Persiapan Peringatan HUT RI ke-81',
                'tanggal' => '2026-08-17 19:30',
                'pemimpin' => 'Ketua Panitia HUT RI',
                'ringkasan' => 'Finalisasi susunan panitia, rincian lomba, dan teknis upacara bendera.',
                'drive' => 'https://drive.google.com/file/d/example-notulensi-4'
            ],
            [
                'judul' => 'Rapat Pleno Pengurus Karang Taruna Kecamatan',
                'tanggal' => '2026-08-20 10:00',
                'pemimpin' => 'Ketua Karang Taruna',
                'ringkasan' => 'Penyampaian laporan pertanggungjawaban kegiatan triwulan II tahun 2026.',
                'drive' => 'https://drive.google.com/file/d/example-notulensi-5'
            ]
        ];

        foreach ($examples as $idx => $row) {
            $bgColor = ($idx % 2 === 0) ? '#ffffff' : '#f8fafc';
            $html .= '<tr style="background-color: ' . $bgColor . ';">';
            $html .= '<td width="280" style="width: 280px;">' . htmlspecialchars($row['judul']) . '</td>';
            $html .= '<td width="180" style="width: 180px; text-align: center;">' . $row['tanggal'] . '</td>';
            $html .= '<td width="240" style="width: 240px;">' . htmlspecialchars($row['pemimpin']) . '</td>';
            $html .= '<td width="480" style="width: 480px;">' . htmlspecialchars($row['ringkasan']) . '</td>';
            $html .= '<td width="400" style="width: 400px; color: #0284c7;">' . $row['drive'] . '</td>';
            $html .= '</tr>';
        }

        // Add 18 empty formatted grid rows for user input
        for ($i = 6; $i <= 25; $i++) {
            $bgColor = ($i % 2 === 0) ? '#ffffff' : '#f8fafc';
            $html .= '<tr style="background-color: ' . $bgColor . ';">';
            $html .= '<td width="280" style="width: 280px; border: 1px solid #cbd5e1;">&nbsp;</td>';
            $html .= '<td width="180" style="width: 180px; border: 1px solid #cbd5e1;">&nbsp;</td>';
            $html .= '<td width="240" style="width: 240px; border: 1px solid #cbd5e1;">&nbsp;</td>';
            $html .= '<td width="480" style="width: 480px; border: 1px solid #cbd5e1;">&nbsp;</td>';
            $html .= '<td width="400" style="width: 400px; border: 1px solid #cbd5e1;">&nbsp;</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
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
