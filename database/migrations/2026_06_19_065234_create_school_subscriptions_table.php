<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_subscriptions', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->restrictOnDelete();

            $table->string('status')->default('trialing');

            $table->date('starts_at')->nullable();
            $table->date('trial_ends_at')->nullable();
            $table->date('current_period_starts_at')->nullable();
            $table->date('current_period_ends_at')->nullable();
            $table->date('canceled_at')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index('subscription_plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_subscriptions');
    }
};
