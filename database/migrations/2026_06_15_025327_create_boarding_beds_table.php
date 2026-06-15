<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_beds', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('boarding_room_id')->constrained('boarding_rooms')->cascadeOnDelete();
            $table->string('code');
            $table->string('status')->default('available');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['boarding_room_id', 'code']);
            $table->index(['boarding_room_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_beds');
    }
};
