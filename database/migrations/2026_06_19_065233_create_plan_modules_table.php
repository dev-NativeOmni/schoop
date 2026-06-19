<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_modules', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->cascadeOnDelete();

            $table->foreignId('system_module_id')
                ->constrained('system_modules')
                ->cascadeOnDelete();

            $table->boolean('is_included')->default(true);

            $table->json('limits')->nullable();
            $table->json('features')->nullable();

            $table->timestamps();

            $table->unique(['subscription_plan_id', 'system_module_id'], 'plan_module_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_modules');
    }
};
