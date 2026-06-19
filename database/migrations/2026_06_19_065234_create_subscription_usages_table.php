<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_usages', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->string('usage_key');

            $table->unsignedBigInteger('used')->default(0);
            $table->unsignedBigInteger('limit')->nullable();

            $table->date('period_starts_at')->nullable();
            $table->date('period_ends_at')->nullable();

            $table->timestamps();

            $table->unique(['school_id', 'usage_key', 'period_starts_at'], 'subscription_usage_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_usages');
    }
};
