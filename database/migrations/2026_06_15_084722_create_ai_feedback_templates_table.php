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
        Schema::create('ai_feedback_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->string('template_key');
            $table->string('title');
            $table->text('body_template');
            $table->string('tone')->default('supportive');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['school_id', 'template_key']);
            $table->index(['template_key']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_feedback_templates');
    }
};
