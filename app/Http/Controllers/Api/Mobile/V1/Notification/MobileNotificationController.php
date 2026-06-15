<?php

namespace App\Http\Controllers\Api\Mobile\V1\Notification;

use App\Http\Controllers\Controller;
use App\Services\Mobile\MobileAccessService;
use App\Services\Mobile\MobilePortalSummaryService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;

class MobileNotificationController extends Controller
{
    public function __construct(
        private readonly MobileAccessService $access,
        private readonly MobilePortalSummaryService $summaries,
    ) {}

    public function teacher(Request $request)
    {
        $this->access->ensureRole($request->user(), ['super_admin', 'admin', 'teacher']);

        return MobileApiResponse::ok($this->summaries->notifications($request->user()));
    }
}
