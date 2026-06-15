<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_subscription_plans', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 120);
            $table->string('billing_cycle', 20)->default('monthly');
            $table->unsignedBigInteger('monthly_price')->default(0);
            $table->unsignedBigInteger('yearly_price')->default(0);
            $table->json('features')->nullable();
            $table->string('status', 20)->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('saas_school_subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('saas_subscription_plan_id')->constrained('saas_subscription_plans')->cascadeOnDelete();
            $table->string('status', 30)->default('trial');
            $table->string('billing_cycle', 20)->default('monthly');
            $table->date('starts_at')->nullable();
            $table->date('trial_ends_at')->nullable();
            $table->date('current_period_start')->nullable();
            $table->date('current_period_end')->nullable();
            $table->date('grace_until')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->foreignId('suspended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('suspend_reason', 1000)->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cancel_reason', 1000)->nullable();
            $table->text('internal_note')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status'], 'saas_subs_school_status_idx');
        });

        Schema::create('saas_tenant_invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('saas_school_subscription_id')->nullable()->constrained('saas_school_subscriptions')->nullOnDelete();
            $table->string('invoice_number', 80)->unique();
            $table->string('status', 30)->default('draft');
            $table->date('issued_at')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('subtotal_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->unsignedBigInteger('balance_amount')->default(0);
            $table->text('note')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('void_reason', 1000)->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status'], 'saas_inv_school_status_idx');
        });

        Schema::create('saas_tenant_invoice_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('saas_tenant_invoice_id')->constrained('saas_tenant_invoices')->cascadeOnDelete();
            $table->string('description', 200);
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('amount')->default(0);
            $table->timestamps();
        });

        Schema::create('saas_tenant_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('saas_tenant_invoice_id')->constrained('saas_tenant_invoices')->cascadeOnDelete();
            $table->string('payment_number', 80)->unique();
            $table->unsignedBigInteger('amount');
            $table->date('payment_date');
            $table->string('method', 50)->default('manual_transfer');
            $table->string('reference', 120)->nullable();
            $table->string('status', 20)->default('posted');
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('void_reason', 1000)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status'], 'saas_pay_school_status_idx');
        });

        Schema::create('implementation_projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('project_number', 80)->unique();
            $table->string('title', 200);
            $table->string('stage', 50)->default('lead');
            $table->string('status', 30)->default('open');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('target_go_live_date')->nullable();
            $table->date('actual_go_live_date')->nullable();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'stage'], 'impl_projects_school_stage_idx');
        });

        Schema::create('onboarding_checklist_items', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 200);
            $table->string('category', 80)->default('general');
            $table->boolean('is_required')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('onboarding_checklist_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('implementation_project_id');
            $table->unsignedBigInteger('onboarding_checklist_item_id');
            $table->string('status', 30)->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->string('blocked_reason', 1000)->nullable();
            $table->timestamps();

            $table->unique(['implementation_project_id', 'onboarding_checklist_item_id'], 'onboarding_records_project_item_unique');
            $table->foreign('implementation_project_id', 'onboarding_records_project_fk')->references('id')->on('implementation_projects')->cascadeOnDelete();
            $table->foreign('onboarding_checklist_item_id', 'onboarding_records_item_fk')->references('id')->on('onboarding_checklist_items')->cascadeOnDelete();
        });

        Schema::create('support_tickets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('requester_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ticket_number', 80)->unique();
            $table->string('category', 50)->default('bug');
            $table->string('priority', 30)->default('medium');
            $table->string('status', 30)->default('open');
            $table->string('subject', 200);
            $table->text('description');
            $table->timestamp('first_response_due_at')->nullable();
            $table->timestamp('resolution_due_at')->nullable();
            $table->timestamp('first_responded_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->boolean('sla_breached')->default(false);
            $table->foreignId('incident_report_id')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status'], 'support_tickets_school_status_idx');
            $table->index(['priority', 'status'], 'support_tickets_priority_status_idx');
        });

        Schema::create('support_ticket_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visibility', 20)->default('public');
            $table->text('message');
            $table->boolean('is_first_response')->default(false);
            $table->timestamps();
        });

        Schema::create('sla_policies', function (Blueprint $table): void {
            $table->id();
            $table->string('priority', 30)->unique();
            $table->unsignedInteger('first_response_minutes');
            $table->unsignedInteger('resolution_minutes');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('incident_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('support_ticket_id')->nullable()->constrained('support_tickets')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('incident_number', 80)->unique();
            $table->string('severity', 20)->default('sev3');
            $table->string('status', 30)->default('open');
            $table->string('title', 200);
            $table->text('impact')->nullable();
            $table->json('timeline')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('mitigation')->nullable();
            $table->text('corrective_action')->nullable();
            $table->timestamp('detected_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status'], 'incidents_school_status_idx');
        });

        Schema::table('support_tickets', function (Blueprint $table): void {
            $table->foreign('incident_report_id')->references('id')->on('incident_reports')->nullOnDelete();
        });

        Schema::create('release_notes', function (Blueprint $table): void {
            $table->id();
            $table->string('version', 50)->unique();
            $table->string('title', 200);
            $table->text('summary')->nullable();
            $table->longText('body')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('knowledge_base_articles', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->string('category', 80)->default('general');
            $table->string('visibility', 30)->default('internal');
            $table->longText('body');
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('customer_success_notes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('note_type', 50)->default('check_in');
            $table->text('content');
            $table->date('next_follow_up_at')->nullable();
            $table->string('health_status', 30)->nullable();
            $table->timestamps();

            $table->index(['school_id', 'created_at'], 'cs_notes_school_created_idx');
        });

        Schema::create('product_usage_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->date('snapshot_date');
            $table->json('metrics');
            $table->unsignedTinyInteger('health_score')->default(0);
            $table->string('health_status', 30)->default('critical');
            $table->timestamps();

            $table->unique(['school_id', 'snapshot_date'], 'usage_snapshots_school_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_usage_snapshots');
        Schema::dropIfExists('customer_success_notes');
        Schema::dropIfExists('knowledge_base_articles');
        Schema::dropIfExists('release_notes');
        Schema::table('support_tickets', function (Blueprint $table): void {
            $table->dropForeign(['incident_report_id']);
        });
        Schema::dropIfExists('incident_reports');
        Schema::dropIfExists('sla_policies');
        Schema::dropIfExists('support_ticket_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('onboarding_checklist_records');
        Schema::dropIfExists('onboarding_checklist_items');
        Schema::dropIfExists('implementation_projects');
        Schema::dropIfExists('saas_tenant_payments');
        Schema::dropIfExists('saas_tenant_invoice_items');
        Schema::dropIfExists('saas_tenant_invoices');
        Schema::dropIfExists('saas_school_subscriptions');
        Schema::dropIfExists('saas_subscription_plans');
    }
};
