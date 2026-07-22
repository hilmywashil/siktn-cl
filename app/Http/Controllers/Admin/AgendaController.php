<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Anggota;
use App\Models\Admin;

class AgendaController extends Controller
{
    public function index()
    {
        $anggotas = Anggota::select('id', 'nama_lengkap as name')
            ->whereNotNull('nama_lengkap')
            ->where('nama_lengkap', '!=', '')
            ->orderBy('nama_lengkap')
            ->get();
            
        $admins = Admin::select('id', 'name')
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderBy('name')
            ->get();

        return view('admin.agenda.index', [
            'activeMenu' => 'agenda',
            'anggotas' => $anggotas,
            'admins' => $admins
        ]);
    }

    public function getEvents()
    {
        $agendas = Agenda::all();
        
        $events = $agendas->map(function($agenda) {
            $isArchived = Carbon::now()->greaterThan($agenda->waktu_selesai);
            
            // Tentukan warna berdasarkan jenis kegiatan
            $color = '#3b82f6'; // blue (default)
            if ($isArchived) {
                $color = '#9ca3af'; // gray untuk arsip
            } else {
                switch ($agenda->jenis_kegiatan) {
                    case 'Meeting Internal': $color = '#10b981'; break; // green
                    case 'Temu Karya': $color = '#f59e0b'; break; // yellow
                    case 'Acara Publik': $color = '#ef4444'; break; // red
                    case 'Webinar': $color = '#8b5cf6'; break; // purple
                }
            }

            $start = Carbon::parse($agenda->waktu_mulai);
            $end = Carbon::parse($agenda->waktu_selesai);
            
            // Cek apakah agenda lintas hari
            $isMultiDay = $start->copy()->startOfDay()->ne($end->copy()->startOfDay());

            return [
                'id' => $agenda->id,
                'title' => $agenda->judul,
                'start' => $start->toIso8601String(),
                'end' => $end->toIso8601String(),
                'color' => $color,
                'allDay' => $isMultiDay,
                'extendedProps' => [
                    'jenis_kegiatan' => $agenda->jenis_kegiatan,
                    'lokasi' => $agenda->lokasi,
                    'deskripsi' => $agenda->deskripsi,
                    'pic_name' => $agenda->pic_name,
                    'isArchived' => $isArchived
                ]
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|string|max:255',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = Auth::guard('admin')->id();

        Agenda::create($validated);

        return response()->json(['success' => true, 'message' => 'Agenda berhasil ditambahkan.']);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis_kegiatan' => 'required|string|max:255',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
        ]);

        $agenda->update($validated);

        return response()->json(['success' => true, 'message' => 'Agenda berhasil diperbarui.']);
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();
        return response()->json(['success' => true, 'message' => 'Agenda berhasil dihapus.']);
    }

    public function exportICal(Request $request)
    {
        $query = Agenda::query();

        // Filter berdasarkan tanggal jika ada parameter start dan end
        if ($request->filled('start') && $request->filled('end')) {
            $start = Carbon::parse($request->start)->setTimezone(config('app.timezone'));
            $end = Carbon::parse($request->end)->setTimezone(config('app.timezone'));
            
            // Ambil agenda yang berjalan di antara rentang waktu tersebut
            $query->where(function($q) use ($start, $end) {
                $q->where('waktu_mulai', '<', $end)
                  ->where('waktu_selesai', '>=', $start);
            });
        }

        $agendas = $query->get();
        return $this->generateIcalResponse($agendas, "agenda-pnkt.ics");
    }

    public function publicICalFeed()
    {
        // Untuk feed publik Google Calendar, kita ambil semua data atau yang ke depan
        // Untuk amannya, ambil semua data (atau bisa dibatasi misal 1 tahun ke belakang sampai ke depan)
        $agendas = Agenda::all();
        return $this->generateIcalResponse($agendas, "siktn-feed.ics");
    }

    private function generateIcalResponse($agendas, $filename)
    {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//SIKTN//Agenda//ID\r\n";
        
        foreach ($agendas as $agenda) {
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:" . md5($agenda->id) . "@siktn.id\r\n";
            $ical .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
            $ical .= "DTSTART:" . $agenda->waktu_mulai->setTimezone('UTC')->format('Ymd\THis\Z') . "\r\n";
            $ical .= "DTEND:" . $agenda->waktu_selesai->setTimezone('UTC')->format('Ymd\THis\Z') . "\r\n";
            $ical .= "SUMMARY:" . $this->escapeString($agenda->judul) . "\r\n";
            $ical .= "DESCRIPTION:" . $this->escapeString($agenda->deskripsi) . "\r\n";
            $ical .= "LOCATION:" . $this->escapeString($agenda->lokasi) . "\r\n";
            $ical .= "END:VEVENT\r\n";
        }
        
        $ical .= "END:VCALENDAR";

        return response($ical)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function getHolidays()
    {
        // Menggunakan repositori github terpercaya yang di-maintain untuk Libur Indonesia
        $url = 'https://raw.githubusercontent.com/guangrei/APIHariLibur_V2/main/calendar.json';
        try {
            $ctx = stream_context_create(['http'=>['timeout'=>5]]);
            $json = file_get_contents($url, false, $ctx);
            $data = json_decode($json, true);
            
            $events = [];
            foreach ($data as $date => $info) {
                if ($date === 'info') continue; // Skip metadata
                
                // Jika libur nasional warna merah pekat, jika bukan (seperti cuti bersama/perayaan) warna merah muda/orange
                $isHoliday = $info['holiday'] ?? false;
                $color = $isHoliday ? '#dc2626' : '#f87171';
                
                $events[] = [
                    'title' => isset($info['summary'][0]) ? $info['summary'][0] : 'Hari Libur',
                    'start' => $date,
                    'allDay' => true,
                    'color' => $color,
                    'display' => 'block'
                ];
            }

            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    private function escapeString($string) {
        return preg_replace('/([\,;])/','\\\$1', $string);
    }
}
