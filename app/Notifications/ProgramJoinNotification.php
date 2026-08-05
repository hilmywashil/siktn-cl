<?php

namespace App\Notifications;

use App\Models\Anggota;
use App\Models\Program;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProgramJoinNotification extends Notification
{
    use Queueable;

    protected $anggota;
    protected $program;

    public function __construct(Anggota $anggota, Program $program)
    {
        $this->anggota = $anggota;
        $this->program = $program;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'program_join_pending',
            'title' => 'Pengajuan Join Program Kerja',
            'message' => "Anggota {$this->anggota->nama_lengkap} mengajukan pendaftaran pada program '{$this->program->nama_program}'.",
            'status' => 'pending',
            'program_id' => $this->program->id,
            'anggota_id' => $this->anggota->id,
            'url' => route('admin.program.index'),
        ];
    }
}
