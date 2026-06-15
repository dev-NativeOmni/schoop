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
        Schema::create('tenant_health_scores', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->date('score_date');
            $table->unsignedTinyInteger('score')->default(0);
            $table->string('status')->default('unknown');
            $table->text('summary')->nullable();
            $table->json('risk_flags')->nullable();
            $table->json('recommendations')->nullable();

            $table->timestamps();

            $table->unique(['school_id', 'score_date']);
            $table->index(['score_date', 'status']);
            $table->index(['school_id', 'score_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_health_scores');
    }
};
