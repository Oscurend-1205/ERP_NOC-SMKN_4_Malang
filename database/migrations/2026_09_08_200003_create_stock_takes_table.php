<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk sesi stok opname dan detail item-nya.
     */
    public function up(): void
    {
        // Tabel sesi stok opname
        Schema::create('stock_takes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // Format: SO-YYYY-NNN
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('started_by')->constrained('users')->restrictOnDelete();
            $table->string('status', 20)->default('draft'); // draft, in_progress, completed, approved
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes_summary')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('code');
        });

        // Tabel detail item per sesi stok opname
        Schema::create('stock_take_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_take_id')->constrained('stock_takes')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->integer('system_quantity')->default(0);
            $table->string('system_condition', 50)->nullable();
            $table->integer('actual_quantity')->nullable(); // null = belum dicek
            $table->string('actual_condition', 50)->nullable();
            $table->integer('difference')->default(0); // actual - system
            $table->text('notes')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();

            $table->unique(['stock_take_id', 'item_id']);
            $table->index('stock_take_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_take_items');
        Schema::dropIfExists('stock_takes');
    }
};
