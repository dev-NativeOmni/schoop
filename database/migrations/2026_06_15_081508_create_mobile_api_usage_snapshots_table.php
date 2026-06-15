<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mobile_api_usage_snapshots', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->date('snapshot_date');
            $table->string('period_type')->default('daily');

            $table->unsignedInteger('mobile_devices_count')->default(0);
            $table->unsignedInteger('active_mobile_devices_count')->default(0);
            $table->unsignedInteger('android_devices_count')->default(0);
            $table->unsignedInteger('ios_devices_count')->default(0);
            $table->unsignedInteger('force_update_devices_count')->default(0);

            $table->unsignedInteger('api_requests_count')->default(0);
            $table->unsignedInteger('api_error_count')->default(0);
            $table->unsignedInteger('api_rate_limit_hits_count')->default(0);
            $table->unsignedInteger('api_failed_auth_count')->default(0);
            $table->unsignedInteger('webhook_success_count')->default(0);
            $table->unsignedInteger('webhook_failed_count')->default(0);

            $table->json('raw_metrics')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'snapshot_date', 'period_type'], 'mobile_api_usage_snapshot_unique');
            $table->index(['snapshot_date', 'period_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_api_usage_snapshots');
    }
};
