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
        // Tabel Pengajuan Pengadaan (Procurements)
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique(); // Format: PR-YYYY-NNN
            $table->string('title');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->nullOnDelete();
            $table->enum('priority', ['rendah', 'sedang', 'tinggi', 'mendesak'])->default('sedang');
            $table->date('target_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'in_procurement', 'completed'])->default('draft');
            $table->text('justification')->nullable(); // Alasan urgensi kebutuhan
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->string('attachment')->nullable(); // File proposal / penawaran
            $table->decimal('total_estimated_cost', 15, 2)->default(0);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('priority');
            $table->index('code');
        });

        // Tabel Detail Item Pengadaan (Procurement Items)
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procurement_id')->constrained('procurements')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('item_name');
            $table->text('specification')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('unit', 30)->default('Unit'); // Unit, Pcs, Box, Roll, Meter, Set, dll
            $table->decimal('estimated_unit_price', 15, 2)->default(0);
            $table->decimal('subtotal_price', 15, 2)->default(0);
            $table->string('reference_url')->nullable(); // Link toko / referensi
            $table->string('status', 30)->default('pending'); // pending, approved, rejected, received
            $table->integer('received_quantity')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('procurement_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_items');
        Schema::dropIfExists('procurements');
    }
};
