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
        Schema::create('lms_activity_logs', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('activity_type'); // e.g. course_view, lesson_complete, etc.
            $table->text('description');

            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('created_at')->nullable();

            $table->index('school_id');
            $table->index('user_id');
            $table->index('activity_type');
            $table->index(['subject_type', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_activity_logs');
    }
};
