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
        Schema::create('ai_practice_plan_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('ai_practice_plan_id')->constrained('ai_practice_plans')->cascadeOnDelete();
            $table->date('practice_date');
            $table->string('item_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('estimated_minutes')->default(10);
            $table->json('linked_content')->nullable();
            $table->string('completion_status')->default('not_started');
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['practice_date']);
            $table->index(['item_type']);
            $table->index(['completion_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_practice_plan_items');
    }
};
