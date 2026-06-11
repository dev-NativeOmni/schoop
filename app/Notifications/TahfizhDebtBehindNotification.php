<?php

namespace App\Notifications;

use App\Models\TahfizhDebt;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TahfizhDebtBehindNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly TahfizhDebt $debt
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
        $this->debt->loadMissing([
            'student',
            'classRoom',
        ]);

        return [
            'category' => 'tahfizh_debt_behind',
            'title' => 'Santri belum mencapai target',
            'body' => $this->debt->student?->full_name . ' masih memiliki hutang hafalan.',
            'type' => 'warning',
            'student_id' => $this->debt->student_id,
            'student_name' => $this->debt->student?->full_name,
            'class_room_name' => $this->debt->classRoom?->name,
            'debt_id' => $this->debt->id,
            'period_type' => $this->debt->period_type,
            'period_start' => $this->debt->period_start?->format('Y-m-d'),
            'period_end' => $this->debt->period_end?->format('Y-m-d'),
            'target_lines' => $this->debt->target_lines,
            'actual_lines' => $this->debt->actual_lines,
            'debt_lines' => $this->debt->debt_lines,
            'cumulative_debt_lines' => $this->debt->cumulative_debt_lines,
            'action_url' => null,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
