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
        Schema::create('school_operational_snapshots', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->date('snapshot_date');
            $table->string('period_type')->default('daily');

            $table->unsignedInteger('attendance_records_count')->default(0);
            $table->unsignedInteger('present_count')->default(0);
            $table->unsignedInteger('late_count')->default(0);
            $table->unsignedInteger('absent_count')->default(0);
            $table->unsignedInteger('sick_count')->default(0);
            $table->unsignedInteger('permission_count')->default(0);
            $table->decimal('attendance_rate', 8, 2)->default(0);
            $table->decimal('late_rate', 8, 2)->default(0);

            $table->unsignedInteger('boarding_roll_call_records_count')->default(0);
            $table->unsignedInteger('boarding_leave_requests_count')->default(0);
            $table->unsignedInteger('boarding_health_logs_count')->default(0);
            $table->unsignedInteger('boarding_discipline_logs_count')->default(0);

            $table->json('raw_metrics')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'snapshot_date', 'period_type'], 'school_operational_snapshot_unique');
            $table->index(['snapshot_date', 'period_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_operational_snapshots');
    }
};
