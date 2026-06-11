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
        Schema::create('attendance_sessions', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('class_room_id')
                ->nullable()
                ->constrained('class_rooms')
                ->nullOnDelete();

            $table->string('name');
            $table->date('attendance_date');

            $table->time('check_in_starts_at')->nullable();
            $table->time('late_after_at')->nullable();
            $table->time('check_in_ends_at')->nullable();

            $table->time('check_out_starts_at')->nullable();
            $table->time('check_out_ends_at')->nullable();

            $table->enum('status', [
                'draft',
                'active',
                'closed',
            ])->default('active');

            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'attendance_date']);
            $table->index(['class_room_id', 'attendance_date']);
            $table->index(['status', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
