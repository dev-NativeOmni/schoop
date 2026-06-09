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
        Schema::create('mushaf_pages', function (Blueprint $table): void {
            $table->id();

            $table->unsignedSmallInteger('page_number')->unique();
            $table->unsignedTinyInteger('total_lines')->default(15);

            $table->foreignId('start_surah_id')
                ->nullable()
                ->constrained('quran_surahs')
                ->nullOnDelete();

            $table->unsignedSmallInteger('start_ayah')->nullable();

            $table->foreignId('end_surah_id')
                ->nullable()
                ->constrained('quran_surahs')
                ->nullOnDelete();

            $table->unsignedSmallInteger('end_ayah')->nullable();

            $table->foreignId('juz_id')
                ->nullable()
                ->constrained('quran_juzs')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('page_number');
            $table->index('juz_id');
            $table->index('start_surah_id');
            $table->index('end_surah_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mushaf_pages');
    }
};
