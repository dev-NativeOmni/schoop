<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $body,
        private readonly string $type = 'info',
        private readonly ?string $actionUrl = null,
        private readonly ?string $senderName = null,
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
        return [
            'category' => 'announcement',
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'action_url' => $this->actionUrl,
            'sender_name' => $this->senderName,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
