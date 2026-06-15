<?php

namespace App\Http\Controllers\Api\Mobile\V1\Student;

use App\Http\Controllers\Controller;
use App\Services\Mobile\MobileAccessService;
use App\Services\Mobile\MobilePortalSummaryService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;

class StudentMobilePortalController extends Controller
{
    public function __construct(
        private readonly MobileAccessService $access,
        private readonly MobilePortalSummaryService $summaries,
    ) {}

    public function summary(Request $request)
    {
        return MobileApiResponse::ok($this->summaries->summary($this->access->studentForUser($request->user())));
    }

    public function tahfizh(Request $request)
    {
        return MobileApiResponse::ok($this->summaries->tahfizh($this->access->studentForUser($request->user())));
    }

    public function mutabaah(Request $request)
    {
        return MobileApiResponse::ok($this->summaries->mutabaah($this->access->studentForUser($request->user())));
    }

    public function attendance(Request $request)
    {
        return MobileApiResponse::ok($this->summaries->attendance($this->access->studentForUser($request->user())));
    }

    public function tahsin(Request $request)
    {
        return MobileApiResponse::ok($this->summaries->tahsin($this->access->studentForUser($request->user())));
    }

    public function finance(Request $request)
    {
        return MobileApiResponse::ok($this->summaries->finance($this->access->studentForUser($request->user())));
    }

    public function cashless(Request $request)
    {
        return MobileApiResponse::ok($this->summaries->cashless($this->access->studentForUser($request->user())));
    }

    public function qrCard(Request $request)
    {
        return MobileApiResponse::ok($this->summaries->qrCard($this->access->studentForUser($request->user())));
    }

    public function notifications(Request $request)
    {
        $this->access->studentForUser($request->user());

        return MobileApiResponse::ok($this->summaries->notifications($request->user()));
    }
}
