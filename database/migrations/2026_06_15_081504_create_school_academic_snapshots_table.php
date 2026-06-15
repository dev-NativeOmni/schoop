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
        Schema::create('school_academic_snapshots', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->date('snapshot_date');
            $table->string('period_type')->default('daily');

            $table->unsignedInteger('active_students_count')->default(0);
            $table->unsignedInteger('active_teachers_count')->default(0);
            $table->unsignedInteger('hafalan_records_count')->default(0);
            $table->unsignedInteger('hafalan_total_lines')->default(0);
            $table->decimal('tahfizh_target_achievement_rate', 8, 2)->default(0);
            $table->unsignedInteger('students_behind_target_count')->default(0);
            $table->unsignedInteger('mutabaah_records_count')->default(0);
            $table->decimal('mutabaah_completion_rate', 8, 2)->default(0);
            $table->unsignedInteger('tahsin_assessments_count')->default(0);
            $table->decimal('tahsin_average_score', 8, 2)->default(0);

            $table->json('raw_metrics')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'snapshot_date', 'period_type'], 'school_academic_snapshot_unique');
            $table->index(['snapshot_date', 'period_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_academic_snapshots');
    }
};
