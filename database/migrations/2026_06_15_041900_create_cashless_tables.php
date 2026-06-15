<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashless_merchants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('code', 50);
            $table->string('type', 30)->default('canteen');
            $table->string('status', 20)->default('active');
            $table->string('phone', 30)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'code']);
            $table->index(['school_id', 'status']);
        });

        Schema::create('cashless_merchant_users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 30)->default('cashier');
            $table->boolean('can_refund')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['cashless_merchant_id', 'user_id']);
            $table->index(['school_id', 'user_id', 'is_active']);
        });

        Schema::create('cashless_products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('sku', 80)->nullable();
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('stock')->nullable();
            $table->boolean('track_stock')->default(false);
            $table->string('status', 20)->default('active');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'cashless_merchant_id', 'sku'], 'cashless_products_school_merchant_sku_unique');
            $table->index(['school_id', 'cashless_merchant_id', 'status'], 'cashless_products_school_merchant_status_idx');
        });

        Schema::create('cashless_wallets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('wallet_number', 50)->unique();
            $table->unsignedBigInteger('balance')->default(0);
            $table->string('status', 20)->default('active');
            $table->timestamp('frozen_at')->nullable();
            $table->foreignId('frozen_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('freeze_reason', 1000)->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'student_id']);
            $table->index(['school_id', 'status']);
        });

        Schema::create('cashless_pos_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
            $table->foreignId('cashier_id')->constrained('users')->cascadeOnDelete();
            $table->string('session_number', 80)->unique();
            $table->string('shift_name', 100)->nullable();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->text('opening_note')->nullable();
            $table->text('closing_note')->nullable();
            $table->unsignedBigInteger('total_sales')->default(0);
            $table->unsignedBigInteger('total_refunds')->default(0);
            $table->unsignedInteger('sales_count')->default(0);
            $table->string('status', 20)->default('open');
            $table->timestamps();

            $table->index(['school_id', 'cashless_merchant_id', 'status'], 'cashless_sessions_school_merchant_status_idx');
            $table->index(['cashier_id', 'opened_at']);
        });

        Schema::create('cashless_sales', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
            $table->foreignId('cashless_pos_session_id')->constrained('cashless_pos_sessions')->cascadeOnDelete();
            $table->foreignId('cashier_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_wallet_id')->constrained('cashless_wallets')->cascadeOnDelete();
            $table->string('transaction_number', 80)->unique();
            $table->string('receipt_number', 80)->unique();
            $table->string('idempotency_key', 120);
            $table->unsignedBigInteger('total_amount');
            $table->unsignedBigInteger('refunded_amount')->default(0);
            $table->string('status', 30)->default('posted');
            $table->text('note')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'idempotency_key'], 'cashless_sales_school_idempotency_unique');
            $table->index(['school_id', 'student_id', 'created_at'], 'cashless_sales_school_student_created_idx');
            $table->index(['school_id', 'cashless_merchant_id', 'status'], 'cashless_sales_school_merchant_status_idx');
        });

        Schema::create('cashless_sale_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_sale_id')->constrained('cashless_sales')->cascadeOnDelete();
            $table->foreignId('cashless_product_id')->nullable()->constrained('cashless_products')->nullOnDelete();
            $table->string('product_name', 150);
            $table->string('product_sku', 80)->nullable();
            $table->unsignedBigInteger('unit_price');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();

            $table->index(['school_id', 'cashless_product_id'], 'cashless_sale_items_school_product_idx');
        });

        Schema::create('cashless_wallet_transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_wallet_id')->constrained('cashless_wallets')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_sale_id')->nullable()->constrained('cashless_sales')->nullOnDelete();
            $table->foreignId('cashless_refund_id')->nullable();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('transaction_number', 80)->unique();
            $table->string('type', 40);
            $table->string('direction', 10);
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('balance_before');
            $table->unsignedBigInteger('balance_after');
            $table->text('note')->nullable();
            $table->string('status', 20)->default('posted');
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'cashless_wallet_id', 'created_at'], 'cashless_wallet_trx_school_wallet_created_idx');
            $table->index(['school_id', 'type', 'created_at']);
        });

        Schema::create('cashless_refunds', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_sale_id')->constrained('cashless_sales')->cascadeOnDelete();
            $table->foreignId('cashless_wallet_id')->constrained('cashless_wallets')->cascadeOnDelete();
            $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
            $table->string('refund_number', 80)->unique();
            $table->unsignedBigInteger('amount');
            $table->string('type', 20)->default('refund');
            $table->string('status', 20)->default('posted');
            $table->text('reason');
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'cashless_sale_id'], 'cashless_refunds_school_sale_idx');
            $table->index(['school_id', 'created_at']);
        });

        Schema::table('cashless_wallet_transactions', function (Blueprint $table): void {
            $table->foreign('cashless_refund_id')->references('id')->on('cashless_refunds')->nullOnDelete();
        });

        Schema::create('cashless_settlements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('settlement_number', 80)->unique();
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedBigInteger('total_sales')->default(0);
            $table->unsignedBigInteger('total_refunds')->default(0);
            $table->bigInteger('net_sales')->default(0);
            $table->unsignedInteger('sales_count')->default(0);
            $table->string('status', 20)->default('draft');
            $table->text('note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'cashless_merchant_id', 'period_start', 'period_end'], 'cashless_settlements_school_merchant_period_idx');
        });

        Schema::create('cashless_audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 80);
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'action']);
            $table->index(['user_id', 'created_at']);
            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashless_audit_logs');
        Schema::dropIfExists('cashless_settlements');
        Schema::table('cashless_wallet_transactions', function (Blueprint $table): void {
            $table->dropForeign(['cashless_refund_id']);
        });
        Schema::dropIfExists('cashless_refunds');
        Schema::dropIfExists('cashless_wallet_transactions');
        Schema::dropIfExists('cashless_sale_items');
        Schema::dropIfExists('cashless_sales');
        Schema::dropIfExists('cashless_pos_sessions');
        Schema::dropIfExists('cashless_wallets');
        Schema::dropIfExists('cashless_products');
        Schema::dropIfExists('cashless_merchant_users');
        Schema::dropIfExists('cashless_merchants');
    }
};
