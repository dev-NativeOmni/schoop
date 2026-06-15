<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_discipline_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->default('note');
            $table->string('category')->nullable();
            $table->text('description');
            $table->integer('points')->default(0);
            $table->text('action_taken')->nullable();
            $table->dateTime('logged_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'logged_at']);
            $table->index(['type', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_discipline_logs');
    }
};
