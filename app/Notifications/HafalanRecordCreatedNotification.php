<?php

namespace App\Notifications;

use App\Models\HafalanRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HafalanRecordCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly HafalanRecord $record
    ) {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->record->loadMissing([
            'student',
            'teacher',
            'startSurah',
            'endSurah',
        ]);

        return [
            'category' => 'hafalan_record_created',
            'title' => 'Setoran tahfizh baru',
            'body' => $this->record->student?->full_name.' telah menambahkan setoran tahfizh.',
            'type' => 'success',
            'student_id' => $this->record->student_id,
            'student_name' => $this->record->student?->full_name,
            'teacher_name' => $this->record->teacher?->name,
            'record_id' => $this->record->id,
            'record_date' => $this->record->record_date?->format('Y-m-d'),
            'range' => "Hlm {$this->record->start_page}:{$this->record->start_line} - Hlm {$this->record->end_page}:{$this->record->end_line}",
            'total_lines' => $this->record->total_lines,
            'status' => $this->record->status,
            'action_url' => null,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
