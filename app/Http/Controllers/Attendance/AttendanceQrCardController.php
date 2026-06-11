<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceQrTokenService;
use App\Services\Attendance\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceQrCardController extends Controller
{
    public function index(
        Request $request,
        AttendanceAccessService $accessService,
        AttendanceQrTokenService $tokenService
    ): View {
        abort_unless($accessService->canManageQr($request->user()), 403);

        $students = Student::query()
            ->with('attendanceQrToken')
            ->orderBy('full_name')
            ->paginate(30);

        foreach ($students as $student) {
            $tokenService->ensureActiveToken($student, $request->user());
        }

        return view('attendance.qr-cards.index', compact('students'));
    }

    public function print(
        Request $request,
        AttendanceAccessService $accessService,
        AttendanceQrTokenService $tokenService,
        QrCodeService $qrCodeService
    ): View {
        abort_unless($accessService->canManageQr($request->user()), 403);

        $students = Student::query()
            ->with('attendanceQrToken')
            ->orderBy('full_name')
            ->get();

        $qrCards = $students->map(function (Student $student) use ($tokenService, $qrCodeService, $request): array {
            $token = $tokenService->ensureActiveToken($student, $request->user());
            $payload = $tokenService->qrPayload($token);

            return [
                'student' => $student,
                'svg' => $qrCodeService->svg($payload, 220),
            ];
        });

        return view('attendance.qr-cards.print', compact('qrCards'));
    }

    public function rotate(
        Student $student,
        Request $request,
        AttendanceAccessService $accessService,
        AttendanceQrTokenService $tokenService
    ): RedirectResponse {
        abort_unless($accessService->canManageQr($request->user()), 403);

        $tokenService->rotateToken($student, $request->user());

        return back()->with('success', 'QR token santri berhasil diganti.');
    }
}
