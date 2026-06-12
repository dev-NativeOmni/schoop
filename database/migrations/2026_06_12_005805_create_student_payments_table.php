<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_payments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('receipt_number')->unique();

            $table->date('payment_date');

            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'qris_external',
                'adjustment',
            ])->default('cash');

            $table->string('reference_number')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->text('note')->nullable();

            $table->enum('status', [
                'posted',
                'void',
            ])->default('posted');

            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('voided_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'payment_date']);
            $table->index(['student_id', 'payment_date']);
            $table->index('payment_method');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};
