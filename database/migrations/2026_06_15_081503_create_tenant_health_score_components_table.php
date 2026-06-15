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
        Schema::create('tenant_health_score_components', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('tenant_health_score_id')
                ->constrained('tenant_health_scores')
                ->cascadeOnDelete();

            $table->string('component_key');
            $table->string('name');
            $table->unsignedSmallInteger('max_score')->default(0);
            $table->unsignedSmallInteger('score')->default(0);
            $table->text('explanation')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['tenant_health_score_id', 'component_key'], 'ths_comp_score_id_key_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_health_score_components');
    }
};
