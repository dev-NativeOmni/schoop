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
        Schema::create('executive_report_sections', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('executive_report_run_id')
                ->constrained('executive_report_runs')
                ->cascadeOnDelete();

            $table->string('section_key');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->json('metrics')->nullable();
            $table->json('charts')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['executive_report_run_id', 'section_key'], 'exec_rep_sec_run_id_key_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('executive_report_sections');
    }
};
