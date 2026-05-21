<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel scan_sessions: menyimpan token QR yang di-generate admin.
     * Token ini memiliki masa berlaku dan hanya bisa digunakan sekali.
     */
    public function up(): void
    {
        Schema::create('scan_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('expired_at');
            $table->boolean('is_used')->default(false);
            $table->timestamps();

            $table->index(['token', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_sessions');
    }
};
