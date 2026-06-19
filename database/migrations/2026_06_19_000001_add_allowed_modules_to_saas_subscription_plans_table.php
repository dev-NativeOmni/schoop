<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saas_subscription_plans', function (Blueprint $table): void {
            $table->json('allowed_modules')->nullable()->after('features');
        });
    }

    public function down(): void
    {
        Schema::table('saas_subscription_plans', function (Blueprint $table): void {
            $table->dropColumn('allowed_modules');
        });
    }
};
