<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_leave_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->default('short_leave');
            $table->string('status')->default('submitted');
            $table->string('destination')->nullable();
            $table->text('reason')->nullable();
            $table->dateTime('leave_start_at');
            $table->dateTime('leave_end_at')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->text('approval_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'status']);
            $table->index(['type', 'status']);
            $table->index(['leave_start_at', 'leave_end_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_leave_requests');
    }
};
