<?php

namespace App\Http\Controllers\Api\Mobile\V1\Bootstrap;

use App\Http\Controllers\Controller;
use App\Services\Mobile\MobileBootstrapService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;

class MobileBootstrapController extends Controller
{
    public function __construct(
        private readonly MobileBootstrapService $bootstrap,
    ) {}

    public function show(Request $request)
    {
        return MobileApiResponse::ok(
            $this->bootstrap->config(
                $request->user(),
                $request->attributes->get('mobile_school'),
                $request->header('X-Mobile-Platform') ?: $request->input('platform'),
                $request->header('X-Mobile-Version') ?: $request->input('app_version'),
            )
        );
    }
}
