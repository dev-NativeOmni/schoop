<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_modules', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('module_key');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_core')->default(false);

            $table->timestamps();

            $table->unique(['school_id', 'module_key']);
            $table->index(['school_id', 'is_enabled']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_modules');
    }
};
