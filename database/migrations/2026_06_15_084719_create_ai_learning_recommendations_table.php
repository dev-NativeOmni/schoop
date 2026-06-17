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
        Schema::create('ai_learning_recommendations', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('ai_learning_profile_id')->nullable()->constrained('ai_learning_profiles')->nullOnDelete();

            $table->string('recommendation_type');
            $table->string('priority')->default('normal');
            $table->string('status')->default('draft');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('recommended_actions')->nullable();
            $table->json('evidence')->nullable();
            $table->json('related_content')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'student_id']);
            $table->index(['recommendation_type']);
            $table->index(['priority']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_learning_recommendations');
    }
};
