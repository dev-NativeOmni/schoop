<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table): void {
            if (! Schema::hasColumn('schools', 'tenant_code')) {
                $table->string('tenant_code')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('schools', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('tenant_code');
            }

            if (! Schema::hasColumn('schools', 'tenant_status')) {
                $table->string('tenant_status')->default('active')->after('slug');
            }

            if (! Schema::hasColumn('schools', 'is_tenant_enabled')) {
                $table->boolean('is_tenant_enabled')->default(true)->after('tenant_status');
            }

            if (! Schema::hasColumn('schools', 'tenant_activated_at')) {
                $table->timestamp('tenant_activated_at')->nullable()->after('is_tenant_enabled');
            }

            if (! Schema::hasColumn('schools', 'tenant_suspended_at')) {
                $table->timestamp('tenant_suspended_at')->nullable()->after('tenant_activated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table): void {
            $columns = [
                'tenant_code',
                'slug',
                'tenant_status',
                'is_tenant_enabled',
                'tenant_activated_at',
                'tenant_suspended_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('schools', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
