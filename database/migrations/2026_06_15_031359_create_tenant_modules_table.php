<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_modules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('module_key');
            $table->string('module_name');
            $table->boolean('is_enabled')->default(true);
            $table->json('configuration')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['school_id', 'module_key'], 'tenant_module_unique_key');
            $table->index(['school_id', 'is_enabled']);
            $table->index('module_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_modules');
    }
};
