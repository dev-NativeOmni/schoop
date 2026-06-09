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
        Schema::create('quran_surahs', function (Blueprint $table): void {
            $table->id();

            $table->unsignedTinyInteger('number')->unique();
            $table->string('name_latin');
            $table->string('name_arabic')->nullable();
            $table->string('meaning')->nullable();
            $table->unsignedSmallInteger('total_ayah');
            $table->string('revelation_place')->nullable();

            $table->foreignId('start_juz_id')
                ->nullable()
                ->constrained('quran_juzs')
                ->nullOnDelete();

            $table->foreignId('end_juz_id')
                ->nullable()
                ->constrained('quran_juzs')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('number');
            $table->index('name_latin');
            $table->index('start_juz_id');
            $table->index('end_juz_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_surahs');
    }
};
