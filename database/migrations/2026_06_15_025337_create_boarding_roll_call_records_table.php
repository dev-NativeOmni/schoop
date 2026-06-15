<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_roll_call_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('boarding_roll_call_session_id')->constrained('boarding_roll_call_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('present');
            $table->text('note')->nullable();
            $table->dateTime('recorded_at')->nullable();
            $table->timestamps();

            $table->unique(['boarding_roll_call_session_id', 'student_id'], 'boarding_roll_call_unique_student');
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_roll_call_records');
    }
};
