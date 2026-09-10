<?php

namespace App\Services\Notifications;

use App\Models\HafalanRecord;
use App\Models\TahfizhDebt;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Notifications\HafalanRecordCreatedNotification;
use App\Notifications\TahfizhDebtBehindNotification;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\Notification;

class NotificationDispatchService
{
    public function __construct(
        private readonly NotificationRecipientResolver $recipientResolver,
    ) {
        //
    }

    public function sendToUsers(iterable $users, BaseNotification $notification): void
    {
        $collection = collect($users)
            ->filter()
            ->unique('id')
            ->values();

        if ($collection->isEmpty()) {
            return;
        }

        Notification::send($collection, $notification);
    }

    public function sendAnnouncement(array $payload, User $sender): int
    {
        $recipients = $this->recipientResolver->resolveFromAnnouncementPayload($payload);

        $this->sendToUsers(
            users: $recipients,
            notification: new AnnouncementNotification(
                title: $payload['title'],
                body: $payload['body'],
                type: $payload['type'] ?? 'info',
                actionUrl: $payload['action_url'] ?? null,
                senderName: $sender->name,
            )
        );

        return $recipients->count();
    }

    public function notifyHafalanRecordCreated(HafalanRecord $record): int
    {
        $record->loadMissing(['student.parents.user', 'student.user']);

        $recipients = collect();

        if ($record->student?->user) {
            $recipients->push($record->student->user);
        }

        if ($record->student) {
            $recipients = $recipients->merge(
                $this->recipientResolver->parentsOfStudent($record->student)
            );
        }

        $recipients = $recipients
            ->filter()
            ->unique('id')
            ->values();

        $this->sendToUsers(
            users: $recipients,
            notification: new HafalanRecordCreatedNotification($record)
        );

        return $recipients->count();
    }

    public function notifyTahfizhDebtBehind(TahfizhDebt $debt): int
    {
        $debt->loadMissing(['student.parents.user', 'student.user']);

        $recipients = collect();

        if ($debt->student?->user) {
            $recipients->push($debt->student->user);
        }

        if ($debt->student) {
            $recipients = $recipients->merge(
                $this->recipientResolver->parentsOfStudent($debt->student)
            );
        }

        $recipients = $recipients
            ->filter()
            ->unique('id')
            ->values();

        $this->sendToUsers(
            users: $recipients,
            notification: new TahfizhDebtBehindNotification($debt)
        );

        return $recipients->count();
    }
}
