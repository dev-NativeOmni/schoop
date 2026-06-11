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
        Schema::create('tahsin_student_profiles', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('current_tahsin_level_id')
                ->nullable()
                ->constrained('tahsin_levels')
                ->nullOnDelete();

            $table->foreignId('assigned_teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', [
                'not_started',
                'in_progress',
                'passed',
                'needs_attention',
            ])->default('not_started');

            $table->decimal('placement_score', 5, 2)->nullable();
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique('student_id');
            $table->index(['school_id', 'status']);
            $table->index('current_tahsin_level_id');
            $table->index('assigned_teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahsin_student_profiles');
    }
};
