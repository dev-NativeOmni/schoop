<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\StoreAnnouncementRequest;
use App\Models\ClassRoom;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Services\Notifications\NotificationDispatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function create(): View
    {
        return view('notifications.announcements.create', [
            'roles' => Role::query()
                ->where('is_active', true)
                ->orderBy('label')
                ->get(),
            'classRooms' => ClassRoom::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'students' => Student::query()
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(),
            'users' => User::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(
        StoreAnnouncementRequest $request,
        NotificationDispatchService $dispatchService
    ): RedirectResponse {
        $sentCount = $dispatchService->sendAnnouncement(
            payload: $request->validated(),
            sender: $request->user(),
        );

        return redirect()
            ->route('notifications.index')
            ->with('success', "Pengumuman berhasil dikirim ke {$sentCount} user.");
    }
}
