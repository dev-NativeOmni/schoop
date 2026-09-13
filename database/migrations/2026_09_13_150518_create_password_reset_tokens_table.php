<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * password_reset_tokens is normally bundled inside the stock
     * 0001_01_01_000000_create_users_table migration, but that file has
     * since diverged from the migrations that actually ran in some
     * environments (its own users/sessions tables were superseded by
     * dedicated migrations elsewhere). Guarding with hasTable() makes this
     * safe to run regardless of whether that table already exists.
     */
    public function up(): void
    {
        if (Schema::hasTable('password_reset_tokens')) {
            return;
        }

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
