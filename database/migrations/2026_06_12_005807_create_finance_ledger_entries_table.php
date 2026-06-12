<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_ledger_entries', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->date('entry_date');

            $table->enum('direction', [
                'debit',
                'credit',
            ]);

            $table->unsignedBigInteger('amount')->default(0);

            $table->string('source_type');
            $table->unsignedBigInteger('source_id');

            $table->string('description')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['school_id', 'entry_date']);
            $table->index(['student_id', 'entry_date']);
            $table->index(['source_type', 'source_id']);
            $table->index('direction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_ledger_entries');
    }
};
