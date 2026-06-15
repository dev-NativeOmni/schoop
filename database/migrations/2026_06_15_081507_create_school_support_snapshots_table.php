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
        Schema::create('school_support_snapshots', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->date('snapshot_date');
            $table->string('period_type')->default('daily');

            $table->unsignedInteger('open_tickets_count')->default(0);
            $table->unsignedInteger('critical_tickets_count')->default(0);
            $table->unsignedInteger('high_tickets_count')->default(0);
            $table->unsignedInteger('medium_tickets_count')->default(0);
            $table->unsignedInteger('low_tickets_count')->default(0);
            $table->unsignedInteger('sla_breached_tickets_count')->default(0);
            $table->unsignedInteger('incidents_count')->default(0);
            $table->unsignedInteger('sev1_incidents_count')->default(0);
            $table->unsignedInteger('sev2_incidents_count')->default(0);
            $table->decimal('average_first_response_minutes', 10, 2)->default(0);
            $table->decimal('average_resolution_minutes', 10, 2)->default(0);

            $table->json('raw_metrics')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'snapshot_date', 'period_type'], 'school_support_snapshot_unique');
            $table->index(['snapshot_date', 'period_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_support_snapshots');
    }
};
