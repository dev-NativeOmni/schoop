<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\ScanAttendanceQrRequest;
use App\Models\AttendanceSession;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceScanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceScannerController extends Controller
{
    public function index(Request $request, AttendanceAccessService $accessService): View
    {
        abort_unless($accessService->canScan($request->user()), 403);

        $sessions = AttendanceSession::query()
            ->where('status', 'active')
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->get();

        return view('attendance.scanner.index', compact('sessions'));
    }

    public function scan(
        ScanAttendanceQrRequest $request,
        AttendanceScanService $scanService
    ): JsonResponse {
        $session = AttendanceSession::query()
            ->whereKey($request->validated('attendance_session_id'))
            ->firstOrFail();

        $record = $scanService->scan(
            payload: $request->validated('qr_payload'),
            session: $session,
            scanner: $request->user()
        );

        $record->load('student');

        return response()->json([
            'message' => 'Presensi berhasil disimpan.',
            'record' => [
                'student_name' => $record->student?->full_name ?? $record->student?->nama_lengkap ?? $record->student?->name ?? 'Santri',
                'status' => $record->status,
                'check_in_at' => $record->check_in_at?->format('H:i:s'),
                'check_out_at' => $record->check_out_at?->format('H:i:s'),
            ],
        ]);
    }
}
