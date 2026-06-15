<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_student_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('boarding_dormitory_id')->constrained('boarding_dormitories')->cascadeOnDelete();
            $table->foreignId('boarding_room_id')->constrained('boarding_rooms')->cascadeOnDelete();
            $table->foreignId('boarding_bed_id')->nullable()->constrained('boarding_beds')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'status']);
            $table->index(['boarding_dormitory_id', 'status']);
            $table->index(['boarding_room_id', 'status']);
            $table->index(['boarding_bed_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_student_assignments');
    }
};
