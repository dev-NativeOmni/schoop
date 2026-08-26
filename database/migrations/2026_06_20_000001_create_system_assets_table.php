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
        if (!Schema::hasTable('system_assets')) {
            Schema::create('system_assets', function (Blueprint $table) {
                $table->id();
                $table->string('key', 255)->unique();
                $table->string('mime_type', 100)->default('image/png');
                $table->unsignedBigInteger('size')->default(0);
                $table->longText('data');
                $table->timestamps();

                $table->index('key');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_assets');
    }
};
