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
        Schema::create('ai_assistance_requests', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();

            $table->string('request_type');
            $table->string('status')->default('pending');
            $table->json('input_context')->nullable();
            $table->json('sanitized_context')->nullable();
            $table->text('purpose')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'request_type']);
            $table->index(['status']);
            $table->index(['student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_assistance_requests');
    }
};
