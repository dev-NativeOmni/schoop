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
        Schema::create('tahsin_assessments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('tahsin_level_id')
                ->nullable()
                ->constrained('tahsin_levels')
                ->nullOnDelete();

            $table->date('assessment_date');

            $table->enum('assessment_type', [
                'placement',
                'daily',
                'weekly',
                'monthly',
                'final',
            ])->default('daily');

            $table->decimal('overall_score', 5, 2)->default(0);

            $table->enum('grade', [
                'excellent',
                'good',
                'fair',
                'needs_improvement',
            ])->default('needs_improvement');

            $table->enum('status', [
                'draft',
                'submitted',
                'reviewed',
            ])->default('submitted');

            $table->text('note')->nullable();
            $table->text('recommendation')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'assessment_date']);
            $table->index(['student_id', 'assessment_date']);
            $table->index(['teacher_id', 'assessment_date']);
            $table->index(['tahsin_level_id', 'assessment_date']);
            $table->index('assessment_type');
            $table->index('grade');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahsin_assessments');
    }
};
