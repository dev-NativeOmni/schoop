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
        Schema::create('tahfizh_debts', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('class_room_id')
                ->nullable()
                ->constrained('class_rooms')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('tahfizh_target_id')
                ->nullable()
                ->constrained('tahfizh_targets')
                ->nullOnDelete();

            $table->string('period_type')->default('daily');
            $table->date('calculation_date');
            $table->date('period_start');
            $table->date('period_end');

            $table->unsignedInteger('target_lines')->default(0);
            $table->unsignedInteger('actual_lines')->default(0);
            $table->unsignedInteger('debt_lines')->default(0);
            $table->unsignedInteger('surplus_lines')->default(0);
            $table->unsignedInteger('cumulative_debt_lines')->default(0);

            $table->string('status')->default('no_target');

            $table->foreignId('calculated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['student_id', 'period_type', 'period_start', 'period_end'],
                'tahfizh_debt_unique_period'
            );

            $table->index('school_id');
            $table->index('class_room_id');
            $table->index('student_id');
            $table->index('tahfizh_target_id');
            $table->index('period_type');
            $table->index('calculation_date');
            $table->index(['period_start', 'period_end']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahfizh_debts');
    }
};
