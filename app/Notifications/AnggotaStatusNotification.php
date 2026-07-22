<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnggotaStatusNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $notes;

    public function __construct($status, $notes = null)
    {
        $this->status = $status;
        $this->notes = $notes;
    }

    public function via(object $notifiable): array
    {
        return ["database"];
    }

    public function toArray(object $notifiable): array
    {
        $title = "";
        $message = "";

        if ($this->status === "approved") {
            $title = "[Profil Anggota] Verifikasi Berhasil";
            $message = "Selamat! Data akun dan profil keanggotaan Anda telah diverifikasi dan disetujui oleh Sekretariat.";
        } elseif ($this->status === "rejected") {
            $title = "[Profil Anggota] Verifikasi Ditolak";
            $message = "Mohon maaf, pengajuan verifikasi profil Anda ditolak. Silakan periksa catatan dari admin dan perbarui data Anda.";
        }

        return [
            "type" => "anggota_verification",
            "title" => $title,
            "message" => $message,
            "status" => $this->status,
            "notes" => $this->notes,
        ];
    }
}
