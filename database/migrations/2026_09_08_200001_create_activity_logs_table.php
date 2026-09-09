<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk mencatat semua aktivitas perubahan data (Audit Trail).
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50); // created, updated, deleted, login, logout, exported
            $table->string('model_type')->nullable(); // e.g. App\Models\Item
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('description'); // Human-readable description
            $table->json('old_values')->nullable(); // Snapshot sebelum perubahan
            $table->json('new_values')->nullable(); // Snapshot setelah perubahan
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index(['model_type', 'model_id']);
            $table->index('action');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
