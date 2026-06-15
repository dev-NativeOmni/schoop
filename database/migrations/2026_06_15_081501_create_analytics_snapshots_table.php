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
        Schema::create('analytics_snapshots', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->date('snapshot_date');
            $table->string('period_type')->default('daily');
            $table->string('scope')->default('school');
            $table->string('metric_key')->index();
            $table->decimal('metric_value', 20, 4)->default(0);
            $table->json('dimensions')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->unique([
                'school_id',
                'snapshot_date',
                'period_type',
                'scope',
                'metric_key',
            ], 'analytics_snapshot_unique');

            $table->index(['snapshot_date', 'period_type']);
            $table->index(['school_id', 'snapshot_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_snapshots');
    }
};
