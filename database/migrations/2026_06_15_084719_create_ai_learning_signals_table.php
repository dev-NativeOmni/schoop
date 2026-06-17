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
        Schema::create('ai_learning_signals', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('ai_learning_profile_id')->nullable()->constrained('ai_learning_profiles')->nullOnDelete();

            $table->date('signal_date');
            $table->string('source_module');
            $table->string('signal_key');
            $table->string('severity')->default('info');
            $table->decimal('score', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->json('evidence')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'student_id', 'signal_date']);
            $table->index(['source_module', 'signal_key']);
            $table->index(['severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_learning_signals');
    }
};
