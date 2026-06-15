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
        Schema::create('school_pwa_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('app_name');
            $table->string('short_name')->nullable();
            $table->string('theme_color', 20)->default('#2563eb');
            $table->string('background_color', 20)->default('#ffffff');
            $table->string('icon_192_path')->nullable();
            $table->string('icon_512_path')->nullable();
            $table->string('start_url')->default('/');
            $table->string('display_mode')->default('standalone');
            $table->boolean('is_enabled')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('school_id');
            $table->index('is_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_pwa_settings');
    }
};
