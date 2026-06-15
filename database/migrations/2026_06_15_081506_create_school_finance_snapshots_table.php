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
        Schema::create('school_finance_snapshots', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->date('snapshot_date');
            $table->string('period_type')->default('daily');

            $table->decimal('student_bills_total', 20, 2)->default(0);
            $table->decimal('student_payments_total', 20, 2)->default(0);
            $table->decimal('student_outstanding_total', 20, 2)->default(0);
            $table->unsignedInteger('overdue_bills_count')->default(0);
            $table->unsignedInteger('void_bills_count')->default(0);
            $table->unsignedInteger('void_payments_count')->default(0);

            $table->decimal('cashless_topup_total', 20, 2)->default(0);
            $table->decimal('cashless_purchase_total', 20, 2)->default(0);
            $table->decimal('cashless_refund_total', 20, 2)->default(0);
            $table->unsignedInteger('cashless_void_count')->default(0);
            $table->unsignedInteger('cashless_negative_balance_anomaly_count')->default(0);
            $table->unsignedInteger('cashless_pending_settlement_count')->default(0);

            $table->json('raw_metrics')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'snapshot_date', 'period_type'], 'school_finance_snapshot_unique');
            $table->index(['snapshot_date', 'period_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_finance_snapshots');
    }
};
