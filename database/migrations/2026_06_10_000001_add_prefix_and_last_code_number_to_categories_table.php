<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('categories', function (Blueprint $table) {
            $table->string('prefix', 10)->unique()->nullable();
            $table->unsignedInteger('last_code_number')->default(0);
        });

        // Backfill existing categories with auto-generated prefix from name
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            $clean = preg_replace('/[^A-Za-z0-9]/', '', $category->name);
            $prefix = strtoupper(substr($clean, 0, 3));
            $prefix = str_pad($prefix, 3, 'X');

            // Ensure uniqueness
            $base = $prefix;
            $suffix = 0;
            while (DB::table('categories')->where('prefix', $prefix)->where('id', '!=', $category->id)->exists()) {
                $suffix++;
                $prefix = $base . $suffix;
                if (strlen($prefix) > 10) {
                    $prefix = substr($base, 0, 2) . $suffix;
                }
            }

            // Count existing items for last_code_number
            $lastNumber = DB::table('items')
                ->where('category_id', $category->id)
                ->where('code', 'like', $prefix . '-%')
                ->count();

            DB::table('categories')
                ->where('id', $category->id)
                ->update([
                    'prefix' => $prefix,
                    'last_code_number' => $lastNumber,
                ]);
        }

        // Make prefix NOT NULL (MySQL only - SQLite doesn't support ALTER COLUMN)
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE categories MODIFY prefix VARCHAR(10) NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['prefix']);
            $table->dropColumn(['prefix', 'last_code_number']);
        });
    }
};
