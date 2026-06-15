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
        Schema::create('mobile_app_versions', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('version');
            $table->unsignedInteger('build_number')->nullable();
            $table->string('minimum_supported_version')->nullable();
            $table->boolean('is_force_update')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('release_notes')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            $table->unique(['platform', 'version']);
            $table->index('platform');
            $table->index('version');
            $table->index('is_active');
            $table->index('is_force_update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_app_versions');
    }
};
