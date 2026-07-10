<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('admin.layouts.admin-layout', function ($view) {
            if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
                $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
                $today = \Carbon\Carbon::now();
                $upcomingBirthdays = collect();
                
                $anggotaAktifQuery = \App\Models\Anggota::where('status', 'approved')->whereNotNull('tanggal_lahir');
                if (in_array($admin->category, ['pkkt', 'ppkt'])) {
                    $anggotaAktifQuery->where('domisili', $admin->domisili);
                }
                
                $anggotaAktif = $anggotaAktifQuery->get();
                foreach ($anggotaAktif as $member) {
                    $birthdayThisYear = $member->tanggal_lahir->copy()->year($today->year);
                    $diffDays = $today->copy()->startOfDay()->diffInDays($birthdayThisYear->copy()->startOfDay(), false);
                    
                    if ($diffDays >= 0 && $diffDays <= 7) {
                        if ($diffDays == 0) {
                            $hariText = 'Hari ini!';
                        } elseif ($diffDays == 1) {
                            $hariText = 'Besok';
                        } elseif ($diffDays == 7) {
                            $hariText = '1 Minggu lagi';
                        } else {
                            $hariText = $diffDays . ' Hari lagi';
                        }
                        
                        $upcomingBirthdays->push([
                            'nama' => $member->nama_lengkap,
                            'hari' => $hariText,
                            'tanggal' => $birthdayThisYear->format('d M Y'),
                            'foto' => $member->foto_diri_url
                        ]);
                    }
                }
                $view->with('upcomingBirthdays', $upcomingBirthdays);
            }
        });
    }
}
