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
        Schema::create('school_theme_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('theme_name')->default('Default');
            $table->string('primary_color', 20)->default('#2563eb');
            $table->string('secondary_color', 20)->default('#0f172a');
            $table->string('accent_color', 20)->default('#22c55e');
            $table->string('text_color', 20)->default('#111827');
            $table->string('background_color', 20)->default('#f8fafc');
            $table->string('sidebar_style')->default('default');
            $table->string('header_style')->default('default');
            $table->string('login_layout')->default('centered');
            $table->string('card_radius')->default('md');
            $table->string('button_radius')->default('md');
            $table->boolean('is_active')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('school_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_theme_settings');
    }
};
