<?php

namespace App\Services\Mobile;

use App\Models\MobileAppVersion;
use App\Models\School;
use App\Models\TenantModule;
use App\Models\User;
use App\Services\SaasOps\PlanModuleAccessService;

class MobileBootstrapService
{
    public function __construct(
        private readonly MobileAuthService $auth,
        private readonly PlanModuleAccessService $planAccess,
    ) {}

    public function config(User $user, School $school, ?string $platform = null, ?string $appVersion = null): array
    {
        $school->loadMissing(['brandProfile', 'themeSetting', 'pwaSetting']);

        $version = $platform
            ? MobileAppVersion::query()
                ->where('platform', $platform)
                ->where('is_active', true)
                ->latest('released_at')
                ->latest('id')
                ->first()
            : null;

        return [
            'active_tenant' => $this->auth->schoolPayload($school),
            'user' => $this->auth->userPayload($user),
            'branding' => [
                'display_name' => $school->brandProfile?->display_name ?? $school->name,
                'short_name' => $school->brandProfile?->short_name ?? $school->code,
                'tagline' => $school->brandProfile?->tagline,
                'logo_path' => $school->brandProfile?->logo_path ?? $school->logo_path,
                'contact_email' => $school->brandProfile?->public_contact_email ?? $school->email,
                'contact_phone' => $school->brandProfile?->public_contact_phone ?? $school->phone,
                'address' => $school->brandProfile?->public_address ?? $school->address,
            ],
            'theme' => [
                'primary_color' => $school->themeSetting?->primary_color ?? $school->primary_color ?? '#84cc16',
                'secondary_color' => $school->themeSetting?->secondary_color ?? $school->secondary_color ?? '#0f172a',
                'accent_color' => $school->themeSetting?->accent_color ?? '#22c55e',
                'text_color' => $school->themeSetting?->text_color ?? '#0f172a',
                'background_color' => $school->themeSetting?->background_color ?? '#f8fafc',
                'card_radius' => $school->themeSetting?->card_radius ?? '8',
                'button_radius' => $school->themeSetting?->button_radius ?? '8',
            ],
            'modules' => $this->modules($school),
            'feature_flags' => [
                'parent_portal' => true,
                'student_portal' => true,
                'teacher_workflow' => true,
                'merchant_pos' => $this->moduleEnabled($school, 'cashless', $user),
                'push_notifications' => false,
                'offline_cashless' => false,
            ],
            'app_version' => [
                'platform' => $platform,
                'current' => $appVersion,
                'latest' => $version?->version,
                'minimum_supported' => $version?->minimum_supported_version,
                'force_update' => $this->requiresForceUpdate($version, $appVersion),
                'release_notes' => $version?->release_notes,
            ],
            'support' => [
                'email' => $school->brandProfile?->public_contact_email ?? $school->email,
                'phone' => $school->brandProfile?->public_contact_phone ?? $school->phone,
            ],
            'navigation' => $this->navigationForRole($user, $school),
        ];
    }

    public function requiresForceUpdate(?MobileAppVersion $version, ?string $currentVersion): bool
    {
        if (! $version || ! $currentVersion || ! $version->minimum_supported_version) {
            return false;
        }

        return version_compare($currentVersion, $version->minimum_supported_version, '<')
            || (bool) $version->is_force_update;
    }

    private function modules(School $school): array
    {
        return TenantModule::query()
            ->where('school_id', $school->id)
            ->orderBy('module_key')
            ->get()
            ->filter(fn (TenantModule $module): bool => $this->planAccess->canAccessModule(null, (int) $school->id, $module->module_key))
            ->map(fn (TenantModule $module): array => [
                'key' => $module->module_key,
                'name' => $module->module_name,
                'enabled' => (bool) $module->is_enabled,
            ])
            ->values()
            ->all();
    }

    private function moduleEnabled(School $school, string $key, ?User $user = null): bool
    {
        return $this->planAccess->canAccessModule($user, (int) $school->id, $key);
    }

    private function navigationForRole(User $user, School $school): array
    {
        $items = match ($user->role?->name) {
            'parent' => ['children', 'progress', 'attendance', 'finance', 'cashless', 'notifications'],
            'student' => ['summary', 'tahfizh', 'attendance', 'cashless', 'qr-card', 'notifications'],
            'teacher' => ['dashboard', 'classes', 'students', 'tahfizh-input', 'attendance', 'tahsin', 'notifications'],
            'cashier', 'merchant' => ['merchant-profile', 'products', 'pos-session', 'checkout', 'sales'],
            default => ['dashboard', 'notifications'],
        };

        $moduleByNavigationItem = [
            'progress' => 'tahfizh',
            'tahfizh' => 'tahfizh',
            'tahfizh-input' => 'tahfizh',
            'attendance' => 'attendance',
            'tahsin' => 'tahsin',
            'finance' => 'finance',
            'cashless' => 'cashless',
            'qr-card' => 'cashless',
            'merchant-profile' => 'cashless',
            'products' => 'cashless',
            'pos-session' => 'cashless',
            'checkout' => 'cashless',
            'sales' => 'cashless',
            'notifications' => 'notifications',
        ];

        return collect($items)
            ->filter(function (string $item) use ($moduleByNavigationItem, $school, $user): bool {
                $moduleKey = $moduleByNavigationItem[$item] ?? null;

                return ! $moduleKey || $this->moduleEnabled($school, $moduleKey, $user);
            })
            ->values()
            ->all();
    }
}
