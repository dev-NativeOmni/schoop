<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_fee_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('finance_fee_category_id')
                ->nullable()
                ->constrained('finance_fee_categories')
                ->nullOnDelete();

            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('default_amount')->default(0);

            $table->enum('billing_cycle', [
                'once',
                'daily',
                'weekly',
                'monthly',
                'quarterly',
                'semester',
                'yearly',
            ])->default('once');

            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index('finance_fee_category_id');
            $table->index('billing_cycle');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_fee_items');
    }
};
