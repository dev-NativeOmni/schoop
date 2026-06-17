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
        Schema::create('ai_learning_profiles', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

            $table->date('profile_date');
            $table->string('profile_status')->default('draft');
            $table->unsignedSmallInteger('confidence_score')->default(0);

            $table->string('tahfizh_trend')->nullable();
            $table->string('tahsin_trend')->nullable();
            $table->string('mutabaah_trend')->nullable();
            $table->string('attendance_trend')->nullable();
            $table->string('lms_engagement_trend')->nullable();

            $table->text('summary')->nullable();
            $table->json('strengths')->nullable();
            $table->json('focus_areas')->nullable();
            $table->json('evidence')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->unique(['student_id', 'profile_date']);
            $table->index(['school_id', 'profile_date']);
            $table->index(['school_id', 'profile_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_learning_profiles');
    }
};
