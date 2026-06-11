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
        Schema::create('tahsin_skills', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('tahsin_level_id')
                ->nullable()
                ->constrained('tahsin_levels')
                ->nullOnDelete();

            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('maximum_score')->default(100);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index(['tahsin_level_id', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahsin_skills');
    }
};
