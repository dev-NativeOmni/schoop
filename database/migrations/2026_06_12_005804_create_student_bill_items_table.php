<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_bill_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('student_bill_id')
                ->constrained('student_bills')
                ->cascadeOnDelete();

            $table->foreignId('finance_fee_item_id')
                ->nullable()
                ->constrained('finance_fee_items')
                ->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('unit_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);

            $table->timestamps();

            $table->index('student_bill_id');
            $table->index('finance_fee_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_bill_items');
    }
};
