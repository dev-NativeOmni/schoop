<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_supervisor_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->foreignId('boarding_dormitory_id')->nullable()->constrained('boarding_dormitories')->nullOnDelete();
            $table->foreignId('boarding_room_id')->nullable()->constrained('boarding_rooms')->nullOnDelete();
            $table->string('phone')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('user_id');
            $table->index(['school_id', 'status']);
            $table->index(['boarding_dormitory_id', 'boarding_room_id'], 'boarding_sup_dorm_room_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_supervisor_profiles');
    }
};
