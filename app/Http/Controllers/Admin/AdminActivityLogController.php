<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin) {
            abort(403);
        }

        $query = AdminActivityLog::orderBy('created_at', 'desc');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('admin_name', 'like', "%{$search}%")
                  ->orWhere('record_label', 'like', "%{$search}%")
                  ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(15)->appends($request->query());

        $stats = [
            'total'   => AdminActivityLog::count(),
            'tambah'  => AdminActivityLog::where('action', 'Tambah')->count(),
            'edit'    => AdminActivityLog::where('action', 'Edit')->count(),
            'hapus'   => AdminActivityLog::where('action', 'Hapus')->count(),
        ];

        $modules = AdminActivityLog::$moduleLabels;
        $actions = AdminActivityLog::distinct()->pluck('action')->toArray();

        return view('admin.settings.activity-logs', [
            'activeMenu' => 'settings_activity_logs',
            'admin'      => $admin,
            'logs'       => $logs,
            'stats'      => $stats,
            'modules'    => $modules,
            'actions'    => $actions,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin) abort(403);

        $query = AdminActivityLog::orderBy('created_at', 'desc');

        if ($request->filled('module')) $query->where('module', $request->module);
        if ($request->filled('action')) $query->where('action', $request->action);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('admin_name', 'like', "%{$search}%")
                  ->orWhere('record_label', 'like', "%{$search}%")
                  ->orWhere('detail', 'like', "%{$search}%");
            });
        }
        if ($request->filled('date')) $query->whereDate('created_at', $request->date);

        $logs = $query->get();
        $fileName = 'Log_Aktivitas_SIKTN_' . date('Ymd_His') . '.html';

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $html .= '<title>Log Aktivitas Admin - SIKTN</title>';
        $html .= '<style>';
        $html .= 'body { font-family: "Segoe UI", Arial, sans-serif; color: #111827; margin: 20px; line-height: 1.5; }';
        $html .= '.header-banner { background: #022648; color: #ffd700; text-align: center; padding: 18px; border-radius: 8px 8px 0 0; }';
        $html .= '.header-banner h2 { margin: 0; font-size: 16pt; text-transform: uppercase; letter-spacing: 0.5px; }';
        $html .= '.sub-header { background: #0a3a6b; color: #ffffff; text-align: center; padding: 10px; font-size: 10pt; font-weight: bold; }';
        $html .= '.meta-info { font-size: 8.5pt; color: #6b7280; margin: 10px 0; text-align: right; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 9pt; }';
        $html .= 'th { background: #022648; color: #ffd700; padding: 10px 8px; text-align: left; font-size: 8.5pt; text-transform: uppercase; border: 1px solid #02182b; }';
        $html .= 'td { padding: 9px 8px; border-bottom: 1px solid #e5e7eb; border-left: 1px solid #f3f4f6; border-right: 1px solid #f3f4f6; vertical-align: top; }';
        $html .= 'tr:nth-child(even) { background: #f9fafb; }';
        $html .= '.badge { font-weight: bold; padding: 2px 6px; border-radius: 4px; font-size: 8pt; display: inline-block; }';
        $html .= '@media print { body { margin: 0; } .no-print { display: none; } }';
        $html .= '</style></head><body>';

        $html .= '<div class="no-print" style="margin-bottom: 15px; text-align: right;">';
        $html .= '<button onclick="window.print()" style="background:#022648;color:white;padding:8px 16px;border:none;border-radius:4px;font-weight:bold;cursor:pointer;">🖨️ Cetak / Simpan sebagai PDF</button>';
        $html .= '</div>';

        $html .= '<div class="header-banner"><h2>Sistem Informasi Karang Taruna Nasional (SIKTN)</h2></div>';
        $html .= '<div class="sub-header">LAPORAN REKAM JEJAK AUDIT TRAIL / LOG AKTIVITAS ADMINISTRATOR</div>';
        $html .= '<div class="meta-info">Tanggal Cetak: ' . date('d F Y, H:i') . ' WIB | Total Record: ' . count($logs) . ' Log</div>';

        $html .= '<table><thead><tr>';
        $html .= '<th style="width:30px;">NO</th>';
        $html .= '<th style="width:120px;">WAKTU & TANGGAL</th>';
        $html .= '<th style="width:100px;">MODUL</th>';
        $html .= '<th style="width:90px;">AKSI</th>';
        $html .= '<th>RECORD / DOKUMEN</th>';
        $html .= '<th style="width:130px;">PELAKU (ADMIN)</th>';
        $html .= '<th>DETAIL / KETERANGAN</th>';
        $html .= '<th style="width:100px;">IP ADDRESS</th>';
        $html .= '</tr></thead><tbody>';

        $no = 1;
        foreach ($logs as $log) {
            $modLabel = AdminActivityLog::$moduleLabels[$log->module] ?? ucfirst($log->module);
            $html .= '<tr>';
            $html .= '<td style="text-align:center;">' . $no++ . '</td>';
            $html .= '<td>' . $log->created_at->format('d/m/Y H:i') . ' WIB</td>';
            $html .= '<td><strong>' . htmlspecialchars($modLabel) . '</strong></td>';
            $html .= '<td><span class="badge">' . htmlspecialchars($log->action) . '</span></td>';
            $html .= '<td><strong>' . htmlspecialchars($log->record_label ?? '-') . '</strong></td>';
            $html .= '<td>' . htmlspecialchars($log->admin_name) . '</td>';
            $html .= '<td>' . htmlspecialchars($log->detail ?? '-') . '</td>';
            $html .= '<td style="font-family:monospace;">' . htmlspecialchars($log->ip_address ?? '-') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    public function exportTxt(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin) abort(403);

        $query = AdminActivityLog::orderBy('created_at', 'desc');

        if ($request->filled('module')) $query->where('module', $request->module);
        if ($request->filled('action')) $query->where('action', $request->action);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('admin_name', 'like', "%{$search}%")
                  ->orWhere('record_label', 'like', "%{$search}%")
                  ->orWhere('detail', 'like', "%{$search}%");
            });
        }
        if ($request->filled('date')) $query->whereDate('created_at', $request->date);

        $logs = $query->get();
        $fileName = 'log_aktivitas_siktn_' . date('Y-m-d_His') . '.txt';

        $content = "=================================================================================\n";
        $content .= " SIKTN - SYSTEM AUDIT TRAIL LOG REPORT\n";
        $content .= " Tanggal Export : " . date('Y-m-d H:i:s') . " WIB\n";
        $content .= " Total Record   : " . count($logs) . " Log\n";
        $content .= "=================================================================================\n\n";

        foreach ($logs as $index => $log) {
            $modLabel = AdminActivityLog::$moduleLabels[$log->module] ?? ucfirst($log->module);
            $content .= sprintf(
                "[%s] [%s] [%s] - Admin: %s | Record: %s | Detail: %s | IP: %s\n",
                $log->created_at->format('Y-m-d H:i:s'),
                str_pad($modLabel, 15),
                str_pad($log->action, 10),
                $log->admin_name,
                $log->record_label ?? '-',
                $log->detail ?? '-',
                $log->ip_address ?? '-'
            );
        }

        $headers = [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        return response($content, 200, $headers);
    }
}
