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
        Schema::create('mutabaah_records', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('mutabaah_activity_id')
                ->constrained('mutabaah_activities')
                ->restrictOnDelete();

            $table->date('record_date');

            $table->enum('status', [
                'done',
                'not_done',
                'excused',
            ])->default('not_done');

            $table->unsignedSmallInteger('score')->nullable();
            $table->unsignedSmallInteger('count_value')->nullable();
            $table->text('text_value')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('submitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('source', [
                'admin',
                'teacher',
                'parent',
                'student',
            ])->default('teacher');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['student_id', 'mutabaah_activity_id', 'record_date'],
                'mutabaah_unique_student_activity_date'
            );

            $table->index(['school_id', 'record_date']);
            $table->index(['student_id', 'record_date']);
            $table->index(['mutabaah_activity_id', 'record_date']);
            $table->index(['source', 'record_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutabaah_records');
    }
};
