<?php

namespace App\Notifications;

use App\Models\Program;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProgramNotification extends Notification
{
    use Queueable;

    protected $program;

    public function __construct(Program $program)
    {
        $this->program = $program;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'program_baru',
            'title' => '📢 Program Baru: ' . $this->program->nama_program,
            'message' => "Terdapat program baru '{$this->program->nama_program}' ({$this->program->kategori}) yang baru saja diterbitkan.",
            'status' => 'program',
            'program_id' => $this->program->id,
        ];
    }
}
