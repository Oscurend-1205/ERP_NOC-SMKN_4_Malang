<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('items:update-codes')]
#[Description('Regenerate item codes using category prefix (PREFIX-NNNN format)')]
class UpdateItemCodes extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai regenerasi kode barang berbasis prefix kategori...');

        $categories = Category::all();
        $updatedCount = 0;

        foreach ($categories as $category) {
            if (empty($category->prefix)) {
                $this->warn("Kategori '{$category->name}' tidak memiliki prefix, dilewati.");
                continue;
            }

            $this->line("Kategori: {$category->name} (Prefix: {$category->prefix})");

            // Get all items in this category, ordered by id
            $items = Item::where('category_id', $category->id)
                ->orderBy('id', 'asc')
                ->get();

            $seq = 0;
            foreach ($items as $item) {
                $seq++;
                $newCode = $category->prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

                if ($item->code !== $newCode) {
                    $oldCode = $item->code;
                    DB::table('items')->where('id', $item->id)->update(['code' => $newCode]);
                    $this->line("  Diperbarui: [{$item->name}] {$oldCode} -> {$newCode}");
                    $updatedCount++;
                }
            }

            // Update last_code_number on category
            $category->update(['last_code_number' => $seq]);
            $this->line("  last_code_number diset ke: {$seq}");
        }

        $this->info("Selesai! {$updatedCount} kode barang telah diperbarui.");
    }
}
