<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceSessionRequest;
use App\Http\Requests\Attendance\UpdateAttendanceSessionRequest;
use App\Models\AttendanceSession;
use App\Models\ClassRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceSessionController extends Controller
{
    public function index(): View
    {
        $sessions = AttendanceSession::query()
            ->with('classRoom')
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('attendance.sessions.index', compact('sessions'));
    }

    public function create(): View
    {
        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        return view('attendance.sessions.create', compact('classRooms'));
    }

    public function store(StoreAttendanceSessionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['school_id'] = $request->user()->school_id ?? null;

        AttendanceSession::query()->create($data);

        return redirect()
            ->route('attendance.sessions.index')
            ->with('success', 'Session presensi berhasil dibuat.');
    }

    public function show(AttendanceSession $session): View
    {
        $session->load([
            'classRoom',
            'records.student',
            'records.scanner',
        ]);

        return view('attendance.sessions.show', compact('session'));
    }

    public function edit(AttendanceSession $session): View
    {
        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        return view('attendance.sessions.edit', compact('session', 'classRooms'));
    }

    public function update(UpdateAttendanceSessionRequest $request, AttendanceSession $session): RedirectResponse
    {
        $session->update($request->validated());

        return redirect()
            ->route('attendance.sessions.index')
            ->with('success', 'Session presensi berhasil diperbarui.');
    }

    public function destroy(AttendanceSession $session): RedirectResponse
    {
        $session->delete();

        return redirect()
            ->route('attendance.sessions.index')
            ->with('success', 'Session presensi berhasil dihapus.');
    }
}
