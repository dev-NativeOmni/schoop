<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_roll_call_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('boarding_dormitory_id')->nullable()->constrained('boarding_dormitories')->nullOnDelete();
            $table->foreignId('boarding_room_id')->nullable()->constrained('boarding_rooms')->nullOnDelete();
            $table->date('session_date');
            $table->string('session_type')->default('night');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->string('status')->default('open');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['session_date', 'session_type']);
            $table->index(['boarding_dormitory_id', 'status']);
            $table->index(['boarding_room_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_roll_call_sessions');
    }
};
