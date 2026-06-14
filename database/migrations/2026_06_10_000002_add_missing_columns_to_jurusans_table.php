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
        Schema::table('jurusans', function (Blueprint $table) {
            // Only add columns if they don't already exist
            if (!Schema::hasColumn('jurusans', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('jurusans', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('jurusans', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurusans', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'is_active']);
        });
    }
};
