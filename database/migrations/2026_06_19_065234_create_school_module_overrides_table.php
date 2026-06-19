<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_module_overrides', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('system_module_id')
                ->constrained('system_modules')
                ->cascadeOnDelete();

            $table->boolean('is_enabled')->default(true);

            $table->string('reason')->nullable();

            $table->timestamp('expires_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['school_id', 'system_module_id'], 'school_module_override_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_module_overrides');
    }
};
