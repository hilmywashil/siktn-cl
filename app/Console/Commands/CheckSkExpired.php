<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuratKeputusan;
use App\Models\Admin;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class CheckSkExpired extends Command
{
    protected $signature = 'sk:check-expired';
    protected $description = 'Kirim notifikasi ke admin jika ada SK yang akan habis masa berlakunya dalam 7 hari (1 minggu)';

    public function handle()
    {
        $today = Carbon::today();
        $threshold = $today->copy()->addDays(7);

        // SK yang Aktif dan berakhir dalam 7 hari ke depan
        $expiringSks = SuratKeputusan::where('status', 'Aktif')
            ->whereBetween('tanggal_berakhir', [$today->toDateString(), $threshold->toDateString()])
            ->get();

        if ($expiringSks->isEmpty()) {
            $this->info('Tidak ada SK yang akan habis dalam 7 hari ke depan.');
            return;
        }

        $admins = Admin::whereIn('category', ['super_admin', 'pimpinan', 'pnkt'])->get();

        foreach ($expiringSks as $sk) {
            $expiredDate = Carbon::parse($sk->tanggal_berakhir)->startOfDay();
            $daysLeft = (int) $today->diffInDays($expiredDate, false);

            if ($daysLeft < 0) {
                $label = "sudah habis masa berlakunya";
            } elseif ($daysLeft === 0) {
                $label = "habis masa berlakunya HARI INI";
            } else {
                $label = "akan habis masa berlakunya dalam {$daysLeft} hari lagi ({$expiredDate->translatedFormat('d M Y')})";
            }

            if ($admins->count() > 0) {
                Notification::send($admins, new AdminNotification(
                    'sk_expired',
                    'SK Hampir Habis Masa Berlaku',
                    "Surat Keputusan '{$sk->nomor_sk}' ({$sk->judul_sk}) {$label}."
                ));
            }

            $this->info("Notifikasi terkirim untuk SK: {$sk->nomor_sk}");
        }

        $this->info("Total {$expiringSks->count()} SK ternotifikasi.");
    }
}
