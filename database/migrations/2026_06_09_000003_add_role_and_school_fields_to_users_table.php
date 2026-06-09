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
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();

            $table->foreignId('school_id')
                ->nullable()
                ->after('role_id')
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('username')->nullable()->unique()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('password');
            $table->timestamp('last_login_at')->nullable()->after('is_active');

            $table->index('role_id');
            $table->index('school_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['school_id']);

            $table->dropIndex(['role_id']);
            $table->dropIndex(['school_id']);
            $table->dropIndex(['is_active']);

            $table->dropColumn([
                'role_id',
                'school_id',
                'username',
                'phone',
                'is_active',
                'last_login_at',
            ]);
        });
    }
};
