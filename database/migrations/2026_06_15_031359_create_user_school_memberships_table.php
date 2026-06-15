<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_school_memberships', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->string('membership_status')->default('active');
            $table->boolean('is_default')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'school_id'], 'user_school_membership_unique');
            $table->index(['school_id', 'membership_status']);
            $table->index(['user_id', 'membership_status']);
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_school_memberships');
    }
};
