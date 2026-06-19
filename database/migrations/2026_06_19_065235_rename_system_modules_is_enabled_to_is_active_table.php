<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_modules', function (Blueprint $table): void {
            if (Schema::hasColumn('system_modules', 'is_enabled') && !Schema::hasColumn('system_modules', 'is_active')) {
                $table->renameColumn('is_enabled', 'is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('system_modules', function (Blueprint $table): void {
            if (Schema::hasColumn('system_modules', 'is_active') && !Schema::hasColumn('system_modules', 'is_enabled')) {
                $table->renameColumn('is_active', 'is_enabled');
            }
        });
    }
};
