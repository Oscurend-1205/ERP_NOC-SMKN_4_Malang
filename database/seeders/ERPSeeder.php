<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Location;
use App\Models\Item;
use Illuminate\Support\Str;

class ERPSeeder extends Seeder
{
    /**
     * Seed data awal untuk ERP NOC.
     */
    public function run(): void
    {
        // Kategori Barang Elektronik Lab
        $categories = [
            ['name' => 'Router', 'slug' => 'router', 'description' => 'Perangkat router untuk jaringan'],
            ['name' => 'Switch', 'slug' => 'switch', 'description' => 'Perangkat switch jaringan'],
            ['name' => 'Access Point', 'slug' => 'access-point', 'description' => 'Perangkat wireless access point'],
            ['name' => 'Kabel & Konektor', 'slug' => 'kabel-konektor', 'description' => 'Kabel UTP, STP, Fiber Optic, dan konektor'],
            ['name' => 'Server', 'slug' => 'server', 'description' => 'Perangkat server dan rack'],
            ['name' => 'Komputer', 'slug' => 'komputer', 'description' => 'PC Desktop dan workstation'],
            ['name' => 'Monitor', 'slug' => 'monitor', 'description' => 'Monitor dan display'],
            ['name' => 'Tools & Alat Ukur', 'slug' => 'tools-alat-ukur', 'description' => 'Tang crimping, LAN tester, multimeter, dll'],
            ['name' => 'Peripheral', 'slug' => 'peripheral', 'description' => 'Keyboard, mouse, headset, dll'],
            ['name' => 'Lainnya', 'slug' => 'lainnya', 'description' => 'Perangkat lain yang tidak masuk kategori di atas'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Lokasi Laboratorium
        $locations = [
            ['name' => 'Lab Jaringan 1', 'code' => 'LAB-JR1', 'description' => 'Laboratorium Jaringan Dasar'],
            ['name' => 'Lab Jaringan 2', 'code' => 'LAB-JR2', 'description' => 'Laboratorium Jaringan Lanjut'],
            ['name' => 'Lab Komputer 1', 'code' => 'LAB-KP1', 'description' => 'Laboratorium Komputer Umum'],
            ['name' => 'Lab Server', 'code' => 'LAB-SVR', 'description' => 'Ruang Server NOC'],
            ['name' => 'Gudang NOC', 'code' => 'GDG-NOC', 'description' => 'Gudang penyimpanan peralatan NOC'],
            ['name' => 'Ruang Instruktur', 'code' => 'RNG-INS', 'description' => 'Ruang kerja instruktur'],
        ];

        foreach ($locations as $loc) {
            Location::create($loc);
        }

        // Contoh Data Barang
        $items = [
            ['name' => 'MikroTik RB750Gr3', 'code' => 'INV-00001', 'brand' => 'MikroTik', 'model' => 'RB750Gr3', 'category_id' => 1, 'location_id' => 1, 'quantity' => 5, 'condition' => 'baik', 'status' => 'tersedia', 'purchase_price' => 850000],
            ['name' => 'Cisco Catalyst 2960', 'code' => 'INV-00002', 'brand' => 'Cisco', 'model' => 'Catalyst 2960-24TT', 'category_id' => 2, 'location_id' => 1, 'quantity' => 3, 'condition' => 'baik', 'status' => 'tersedia', 'purchase_price' => 5500000],
            ['name' => 'TP-Link EAP245', 'code' => 'INV-00003', 'brand' => 'TP-Link', 'model' => 'EAP245 V3', 'category_id' => 3, 'location_id' => 2, 'quantity' => 4, 'condition' => 'baik', 'status' => 'tersedia', 'purchase_price' => 1200000],
            ['name' => 'Kabel UTP Cat6 305m', 'code' => 'INV-00004', 'brand' => 'Belden', 'model' => 'Cat6 UTP', 'category_id' => 4, 'location_id' => 5, 'quantity' => 10, 'condition' => 'baik', 'status' => 'tersedia', 'purchase_price' => 1800000],
            ['name' => 'Dell PowerEdge T340', 'code' => 'INV-00005', 'brand' => 'Dell', 'model' => 'PowerEdge T340', 'category_id' => 5, 'location_id' => 4, 'quantity' => 1, 'condition' => 'baik', 'status' => 'tersedia', 'purchase_price' => 25000000],
            ['name' => 'PC Desktop Lenovo V530', 'code' => 'INV-00006', 'brand' => 'Lenovo', 'model' => 'V530 Tower', 'category_id' => 6, 'location_id' => 3, 'quantity' => 20, 'condition' => 'baik', 'status' => 'tersedia', 'purchase_price' => 8500000],
            ['name' => 'Monitor LG 22MK430H', 'code' => 'INV-00007', 'brand' => 'LG', 'model' => '22MK430H', 'category_id' => 7, 'location_id' => 3, 'quantity' => 20, 'condition' => 'baik', 'status' => 'tersedia', 'purchase_price' => 1500000],
            ['name' => 'Tang Crimping RJ45', 'code' => 'INV-00008', 'brand' => 'AMP', 'model' => 'Crimping Tool', 'category_id' => 8, 'location_id' => 1, 'quantity' => 15, 'condition' => 'rusak_ringan', 'status' => 'tersedia', 'purchase_price' => 150000],
            ['name' => 'MikroTik CCR1009', 'code' => 'INV-00009', 'brand' => 'MikroTik', 'model' => 'CCR1009-7G-1C-1S+', 'category_id' => 1, 'location_id' => 4, 'quantity' => 1, 'condition' => 'baik', 'status' => 'tersedia', 'purchase_price' => 7500000],
            ['name' => 'Cisco Router 2901', 'code' => 'INV-00010', 'brand' => 'Cisco', 'model' => '2901/K9', 'category_id' => 1, 'location_id' => 2, 'quantity' => 2, 'condition' => 'rusak_berat', 'status' => 'maintenance', 'purchase_price' => 12000000],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
