<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class School extends Model
{
    protected $fillable = [
        'name',
        'code',
        'npsn',
        'email',
        'phone',
        'address',
        'logo_path',
        'primary_color',
        'secondary_color',
        'is_active',
        'tenant_code',
        'slug',
        'tenant_status',
        'is_tenant_enabled',
        'tenant_activated_at',
        'tenant_suspended_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_tenant_enabled' => 'boolean',
            'tenant_activated_at' => 'datetime',
            'tenant_suspended_at' => 'datetime',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function teacherProfiles(): HasMany
    {
        return $this->hasMany(TeacherProfile::class);
    }

    public function parentProfiles(): HasMany
    {
        return $this->hasMany(ParentProfile::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function tahfizhTargets(): HasMany
    {
        return $this->hasMany(TahfizhTarget::class);
    }

    public function hafalanRecords(): HasMany
    {
        return $this->hasMany(HafalanRecord::class);
    }

    public function tahfizhDebts(): HasMany
    {
        return $this->hasMany(TahfizhDebt::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(UserSchoolMembership::class);
    }

    public function tenantSettings(): HasMany
    {
        return $this->hasMany(TenantSetting::class);
    }

    public function tenantModules(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    public function brandProfile()
    {
        return $this->hasOne(SchoolBrandProfile::class);
    }

    public function themeSetting()
    {
        return $this->hasOne(SchoolThemeSetting::class);
    }

    public function domainMappings()
    {
        return $this->hasMany(SchoolDomainMapping::class);
    }

    public function pwaSetting()
    {
        return $this->hasOne(SchoolPwaSetting::class);
    }

    public function whiteLabelPublications()
    {
        return $this->hasMany(WhiteLabelPublication::class);
    }

    public function cashlessMerchants(): HasMany
    {
        return $this->hasMany(CashlessMerchant::class);
    }

    public function cashlessWallets(): HasMany
    {
        return $this->hasMany(CashlessWallet::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(SchoolSubscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(SchoolSubscription::class)
            ->whereIn('status', ['trialing', 'active'])
            ->latestOfMany();
    }

    public function moduleOverrides(): HasMany
    {
        return $this->hasMany(SchoolModuleOverride::class);
    }
}
