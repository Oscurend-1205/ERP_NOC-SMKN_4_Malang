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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // Nama barang
            $table->string('code')->unique();             // Kode inventaris (INV-00001)
            $table->string('serial_number')->nullable();  // Serial number
            $table->string('brand')->nullable();          // Merek (Cisco, MikroTik, TP-Link, dll)
            $table->string('model')->nullable();          // Model barang
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->enum('status', ['tersedia', 'dipinjam', 'maintenance', 'dimusnahkan'])->default('tersedia');
            $table->date('purchase_date')->nullable();    // Tanggal pembelian
            $table->decimal('purchase_price', 15, 2)->nullable(); // Harga beli
            $table->text('notes')->nullable();            // Catatan tambahan
            $table->string('image')->nullable();          // Foto barang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
