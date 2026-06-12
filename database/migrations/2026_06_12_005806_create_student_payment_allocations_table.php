<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_payment_allocations', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('student_payment_id')
                ->constrained('student_payments')
                ->cascadeOnDelete();

            $table->foreignId('student_bill_id')
                ->constrained('student_bills')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('amount')->default(0);

            $table->timestamps();

            $table->unique(
                ['student_payment_id', 'student_bill_id'],
                'student_payment_bill_unique'
            );

            $table->index('student_payment_id');
            $table->index('student_bill_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payment_allocations');
    }
};
