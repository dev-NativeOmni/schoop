<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_rooms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('boarding_dormitory_id')->constrained('boarding_dormitories')->cascadeOnDelete();
            $table->string('name');
            $table->string('floor')->nullable();
            $table->unsignedSmallInteger('capacity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['boarding_dormitory_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_rooms');
    }
};
