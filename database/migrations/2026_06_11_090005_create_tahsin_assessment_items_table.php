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
        Schema::create('tahsin_assessment_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('tahsin_assessment_id')
                ->constrained('tahsin_assessments')
                ->cascadeOnDelete();

            $table->foreignId('tahsin_skill_id')
                ->constrained('tahsin_skills')
                ->restrictOnDelete();

            $table->decimal('score', 5, 2)->default(0);

            $table->enum('status', [
                'mastered',
                'progress',
                'weak',
                'not_tested',
            ])->default('not_tested');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique(
                ['tahsin_assessment_id', 'tahsin_skill_id'],
                'tahsin_assessment_skill_unique'
            );

            $table->index('tahsin_skill_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahsin_assessment_items');
    }
};
