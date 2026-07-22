<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anggota;
use App\Models\Admin;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class NotifyBirthdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:birthdays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi ke admin untuk anggota yang berulang tahun hari ini';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $birthdayAnggotas = Anggota::whereMonth('tanggal_lahir', $today->month)
            ->whereDay('tanggal_lahir', $today->day)
            ->where('status', 'approved')
            ->get();

        if ($birthdayAnggotas->isEmpty()) {
            $this->info('Tidak ada yang berulang tahun hari ini.');
            return;
        }

        $admins = Admin::all();
        
        foreach ($birthdayAnggotas as $anggota) {
            $age = $anggota->tanggal_lahir->diffInYears($today);
            
            Notification::send($admins, new AdminNotification(
                'birthday',
                'Ulang Tahun Anggota',
                "Hari ini adalah ulang tahun {$anggota->nama_lengkap} yang ke-{$age}. Jangan lupa beri ucapan selamat!"
            ));
        }

        $this->info('Notifikasi ulang tahun berhasil dikirim ke admin.');
    }
}
