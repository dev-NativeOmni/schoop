<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('setting_key');
            $table->text('setting_value')->nullable();
            $table->string('value_type')->default('string');
            $table->boolean('is_public')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'setting_key'], 'tenant_setting_unique_key');
            $table->index(['school_id', 'is_public']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
