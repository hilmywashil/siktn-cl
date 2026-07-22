<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontakController extends Controller
{
    /**
     * Display a listing of member contacts.
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Anggota::query()->where('status', 'approved');

        // Apply Domisili Filter based on Admin Level (PPKT / PKKT)
        if (in_array($admin->category, ['ppkt', 'pkkt']) && !empty($admin->domisili)) {
            $query->where('domisili', $admin->domisili);
        }

        // Search Filter (Nama, Email, No. HP, Jabatan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Filter Domisili
        if ($request->filled('domisili')) {
            $query->where('domisili', $request->domisili);
        }

        // Filter Jabatan
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        $kontaks = $query->orderBy('nama_lengkap', 'asc')->paginate(12)->withQueryString();

        // Get filter options (Unique & Clean)
        $domisiliList = Anggota::where('status', 'approved')
            ->whereNotNull('domisili')
            ->where('domisili', '!=', '')
            ->distinct()
            ->pluck('domisili')
            ->sort()
            ->values();

        $jabatanList = Anggota::where('status', 'approved')
            ->whereNotNull('jabatan')
            ->where('jabatan', '!=', '')
            ->distinct()
            ->pluck('jabatan')
            ->sort()
            ->values();

        $activeMenu = 'kontak';

        return view('admin.kontak.index', compact(
            'kontaks',
            'domisiliList',
            'jabatanList',
            'admin',
            'activeMenu'
        ));
    }

    /**
     * Export data direktori kontak ke file Excel (.xls) dengan Styling SIKTN Navy & Gold
     */
    public function export(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Anggota::query()->where('status', 'approved');

        // Apply Domisili Filter based on Admin Level (PPKT / PKKT)
        if (in_array($admin->category, ['ppkt', 'pkkt']) && !empty($admin->domisili)) {
            $query->where('domisili', $admin->domisili);
        }

        // Search Filter (Nama, Email, No. HP, Jabatan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Filter Domisili
        if ($request->filled('domisili')) {
            $query->where('domisili', $request->domisili);
        }

        // Filter Jabatan
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        $kontaks = $query->orderBy('nama_lengkap', 'asc')->get();

        $wilayahTitle = !empty($admin->domisili) ? strtoupper($admin->domisili) : 'NASIONAL';
        $fileName = 'Direktori_Kontak_SIKTN_' . date('Ymd_His') . '.xls';

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Direktori Kontak SIKTN</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body style="font-family: Arial, sans-serif;">';

        $html .= '<table style="border-collapse: collapse; width: 100%;">';

        // Title Header Banner SIKTN (Navy Blue & Gold)
        $html .= '<tr><td colspan="7" style="height: 15px;"></td></tr>';
        $html .= '<tr>';
        $html .= '<td colspan="7" style="background-color: #0a2540; color: #ffd700; font-size: 16pt; font-weight: bold; text-align: center; padding: 16px; border: 2px solid #0a2540;">SISTEM INFORMASI KARANG TARUNA NASIONAL (SIKTN)</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="7" style="background-color: #164e63; color: #ffffff; font-size: 11pt; font-weight: bold; text-align: center; padding: 10px; border: 1px solid #164e63;">DIREKTORI KONTAK PENGURUS & ANGGOTA - WILAYAH: ' . htmlspecialchars($wilayahTitle) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="7" style="font-size: 9pt; color: #64748b; text-align: right; padding: 6px; font-style: italic;">Tanggal Export: ' . date('d F Y H:i:s') . ' WIB</td>';
        $html .= '</tr>';
        $html .= '<tr><td colspan="7" style="height: 10px;"></td></tr>';

        // Header Table Columns (Navy Blue Header with Gold Text)
        $html .= '<tr>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">NO</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">NAMA LENGKAP</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">JABATAN</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">DOMISILI / WILAYAH</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">NO. WHATSAPP / HP</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: left; font-weight: bold; font-size: 10pt;">EMAIL</th>';
        $html .= '<th style="background-color: #0a2540; color: #ffd700; border: 1px solid #02182b; padding: 10px; text-align: center; font-weight: bold; font-size: 10pt;">DIRECT WA LINK</th>';
        $html .= '</tr>';

        $no = 1;
        foreach ($kontaks as $k) {
            $bgColor = ($no % 2 === 0) ? '#f8fafc' : '#ffffff';
            $waNumber = preg_replace('/[^0-9]/', '', $k->no_hp ?? '');
            if (str_starts_with($waNumber, '0')) {
                $waNumber = '62' . substr($waNumber, 1);
            }
            $waLink = $waNumber ? 'https://wa.me/' . $waNumber : '-';

            $html .= '<tr style="background-color: ' . $bgColor . ';">';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-size: 9.5pt;">' . $no++ . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold; font-size: 9.5pt;">' . htmlspecialchars($k->nama_lengkap ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($k->jabatan ?? 'Anggota') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($k->domisili ?? 'Nasional') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-family: monospace; text-align: center; font-size: 9.5pt; mso-number-format:\'\@\';">\'' . ($k->no_hp ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt;">' . htmlspecialchars($k->email ?? '-') . '</td>';
            $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-size: 9pt; color: #25d366;">' . htmlspecialchars($waLink) . '</td>';
            $html .= '</tr>';
        }

        // Summary Row
        $html .= '<tr><td colspan="7" style="height: 10px;"></td></tr>';
        $html .= '<tr>';
        $html .= '<td colspan="7" style="background-color: #0a2540; color: #ffffff; padding: 10px; font-weight: bold; font-size: 10pt; text-align: right;">Total Data Kontak: ' . count($kontaks) . ' Orang</td>';
        $html .= '</tr>';

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
