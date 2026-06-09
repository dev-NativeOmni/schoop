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
        Schema::create('hafalan_records', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('tahfizh_target_id')
                ->nullable()
                ->constrained('tahfizh_targets')
                ->nullOnDelete();

            $table->date('record_date');

            $table->foreignId('start_surah_id')
                ->nullable()
                ->constrained('quran_surahs')
                ->nullOnDelete();

            $table->unsignedSmallInteger('start_ayah')->nullable();

            $table->foreignId('end_surah_id')
                ->nullable()
                ->constrained('quran_surahs')
                ->nullOnDelete();

            $table->unsignedSmallInteger('end_ayah')->nullable();

            $table->unsignedSmallInteger('start_page');
            $table->unsignedTinyInteger('start_line');
            $table->unsignedSmallInteger('end_page');
            $table->unsignedTinyInteger('end_line');

            $table->unsignedSmallInteger('total_lines')->default(0);

            $table->string('status')->default('kurang');
            $table->unsignedTinyInteger('quality_score')->nullable();
            $table->text('notes')->nullable();

            $table->boolean('is_sequence_valid')->default(false);
            $table->text('sequence_note')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('school_id');
            $table->index('student_id');
            $table->index('teacher_id');
            $table->index('record_date');
            $table->index('status');
            $table->index(['student_id', 'record_date']);
            $table->index(['start_page', 'start_line']);
            $table->index(['end_page', 'end_line']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hafalan_records');
    }
};
