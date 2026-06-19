<?php

namespace App\Services\Billing;

use App\Models\School;
use App\Models\User;

class BillingNavigationService
{
    public function __construct(
        private readonly ModuleAccessService $moduleAccessService,
    ) {}

    public function visibleModulesFor(User $user, School $school): array
    {
        $items = [
            [
                'module_key' => 'tahfizh',
                'label' => 'Tahfizh',
                'route' => 'tahfizh.hafalan-records.index',
            ],
            [
                'module_key' => 'reports',
                'label' => 'Reports',
                'route' => 'reports.tahfizh.dashboard',
            ],
            [
                'module_key' => 'notifications',
                'label' => 'Notifications',
                'route' => 'notifications.index',
            ],
            [
                'module_key' => 'exports',
                'label' => 'Export',
                'route' => 'exports.tahfizh.index',
            ],
            [
                'module_key' => 'mutabaah',
                'label' => 'Mutabaah',
                'route' => 'mutabaah.activities.index',
            ],
            [
                'module_key' => 'attendance',
                'label' => 'Attendance',
                'route' => 'attendance.sessions.index',
            ],
            [
                'module_key' => 'tahsin',
                'label' => 'Tahsin',
                'route' => 'tahsin.levels.index',
            ],
            [
                'module_key' => 'finance',
                'label' => 'Finance',
                'route' => 'finance.reports.dashboard',
            ],
            [
                'module_key' => 'schoolos',
                'label' => 'SchoolOS',
                'route' => 'schoolos.dashboard',
            ],
        ];

        $isAdminLike = method_exists($user, 'hasRole')
            && $user->hasRole(['super_admin', 'admin', 'admin_sekolah']);

        return collect($items)
            ->map(function (array $item) use ($school, $isAdminLike): ?array {
                $enabled = $this->moduleAccessService->isEnabledForSchool($school, $item['module_key']);

                if (! $enabled && ! $isAdminLike) {
                    return null;
                }

                $item['enabled'] = $enabled;
                $item['locked'] = ! $enabled;

                return $item;
            })
            ->filter()
            ->values()
            ->all();
    }
}
