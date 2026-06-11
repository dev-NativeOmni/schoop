<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\System\DatabaseBackupService;
use App\Services\System\SystemHealthService;
use Illuminate\View\View;

class SystemStatusController extends Controller
{
    public function __invoke(
        SystemHealthService $systemHealthService,
        DatabaseBackupService $backupService
    ): View {
        return view('admin.system.status', [
            'checks' => $systemHealthService->check(),
            'isHealthy' => $systemHealthService->isHealthy(),
            'backups' => $backupService->listBackups(),
        ]);
    }
}
