<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Katalog;

class KatalogStatusNotification extends Notification
{
    use Queueable;

    protected $katalog;
    protected $status;
    protected $notes;

    public function __construct(Katalog $katalog, $status, $notes = null)
    {
        $this->katalog = $katalog;
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
            $title = "[E-Katalog] Katalog Disetujui";
            $message = "Katalog perusahaan \"{$this->katalog->company_name}\" telah disetujui dan kini tayang di halaman publik.";
        } elseif ($this->status === "rejected") {
            $title = "[E-Katalog] Katalog Ditolak";
            $message = "Katalog perusahaan \"{$this->katalog->company_name}\" ditolak oleh Sekretariat.";
        } elseif ($this->status === "revision") {
            $title = "[E-Katalog] Katalog Perlu Revisi";
            $message = "Katalog perusahaan \"{$this->katalog->company_name}\" perlu direvisi sebelum dapat ditayangkan.";
        }

        return [
            "katalog_id" => $this->katalog->id,
            "title" => $title,
            "message" => $message,
            "status" => $this->status,
            "notes" => $this->notes,
        ];
    }
}
