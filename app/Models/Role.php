<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getLabelAttribute($value): string
    {
        return match ($this->name) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin Sekolah',
            'principal' => 'Kepala Sekolah',
            'teacher' => 'Guru Tahfidz',
            'boarding_supervisor' => 'Pembina Asrama',
            'parent' => 'Orang Tua',
            'student' => 'Santri',
            'finance' => 'Finance / Keuangan',
            'cashier' => 'Kasir',
            'merchant' => 'Merchant / Kantin',
            'support_staff' => 'Support Staff',
            'customer_success' => 'Customer Success',
            'sales' => 'Sales',
            'operations_manager' => 'Operations Manager',
            default => $value ?? ucfirst(str_replace('_', ' ', $this->name)),
        };
    }
}
