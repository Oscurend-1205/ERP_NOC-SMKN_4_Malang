<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ToolsDummySeeder extends Seeder
{
    /**
     * Tambah dummy barang/alat jaringan yang realistis untuk demo ERP NOC.
     * Aman dijalankan berulang kali (idempotent via kode unik).
     */
    public function run(): void
    {
        $now = Carbon::now();

        $categories = DB::table('categories')->pluck('id', 'slug');
        $locations  = DB::table('locations')->pluck('id')->toArray();
        $suppliers  = DB::table('suppliers')->pluck('id')->toArray();
        $asals      = DB::table('asal_barangs')->pluck('id')->toArray();
        $kondisis   = DB::table('kondisi_barangs')->pluck('id', 'name');

        if ($categories->isEmpty() || empty($locations)) {
            $this->command->error('Master data belum ada. Jalankan NocSeeder terlebih dahulu.');
            return;
        }

        $locMain  = $locations[0]; // Ruang Server NOC
        $locLab1  = $locations[1] ?? $locMain; // Lab RPL 1
        $locLab2  = $locations[2] ?? $locMain; // Lab RPL 2
        $locLab3  = $locations[3] ?? $locMain; // Lab TKJ 1
        $locGudang = $locations[5] ?? $locMain; // Gudang NOC

        $conditionMap = [
            'baik'         => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            'hilang'       => 'Hilang',
            'dipinjam'     => 'Baik', // status dipinjam = kondisi baik
        ];

        // Format: [name, brand, model, catSlug, subPrefix, unitCount, locId, condition, status, price]
        $itemDefs = [

            // ================================================================
            // TOOLS — Alat Jaringan & Lab
            // ================================================================
            ['Tang Crimping RJ45',       'TRENDnet',  'TC-CT68',        'tools', 'CRP', 4, $locMain,   'baik',         'tersedia',    280000],
            ['Tang Crimping Fiber',      'Panduit',   'PCT100-C',       'tools', 'CRP', 2, $locGudang, 'baik',         'tersedia',    450000],
            ['LAN Tester RJ45/RJ11',     'TRENDnet',  'TC-NT12',        'tools', 'TST', 3, $locMain,   'baik',         'tersedia',    350000],
            ['LAN Tester Advanced',      'Fluke',     'MicroMapper',    'tools', 'TST', 1, $locMain,   'baik',         'tersedia',   2800000],
            ['Obeng Set Jaringan',       'Vessel',    'TD-56',          'tools', 'OBG', 5, $locMain,   'baik',         'tersedia',    120000],
            ['Obeng Set Magnetik',       'Stanley',   '65-PIECE',       'tools', 'OBG', 2, $locLab3,   'baik',         'dipinjam',    250000],
            ['Tang Potong Kabel',        'Knipex',    '97 52 63',       'tools', 'TNG', 3, $locMain,   'baik',         'tersedia',    180000],
            ['Tang Kupas Kabel',         'Fluke',     'TK-50',          'tools', 'TNG', 2, $locGudang, 'rusak_ringan', 'maintenance', 320000],
            ['Kabel Toner & Probe',      'Klein',     'VDV500-820',     'tools', 'TNG', 2, $locMain,   'baik',         'tersedia',    650000],
            ['Multimeter Digital',       'Sanwa',     'CD772',          'tools', 'MTR', 3, $locMain,   'baik',         'tersedia',    480000],
            ['Multimeter Digital',       'Fluke',     '115',            'tools', 'MTR', 1, $locMain,   'baik',         'tersedia',   3200000],
            ['Label Printer Kabel',      'Brady',     'BMP21-PLUS',     'tools', 'LBL', 2, $locMain,   'baik',         'tersedia',   1850000],
            ['Fiber Optic Power Meter',  'Sunma',     'XT-TP702',       'tools', 'FOM', 1, $locMain,   'baik',         'tersedia',   1200000],
            ['Fiber Optic Light Source', 'Sunma',     'XT-632A',        'tools', 'FOM', 1, $locMain,   'baik',         'tersedia',   1100000],
            ['Proyektor Epson',          'Epson',     'EB-X51',         'tools', 'EPS', 2, $locLab1,   'baik',         'tersedia',   6500000],
            ['Proyektor Epson',          'Epson',     'EB-E01',         'tools', 'EPS', 1, $locLab2,   'rusak_ringan', 'maintenance', 5800000],
            ['Layar Proyektor Lipat',    'Screen Int','Manual 100"',    'tools', 'SCR', 2, $locLab1,   'baik',         'tersedia',    950000],
            ['UPS 650VA',                'APC',       'BX650LI',        'tools', 'UPS', 4, $locMain,   'baik',         'tersedia',    900000],
            ['UPS 650VA',                'APC',       'BX650LI',        'tools', 'UPS', 2, $locLab3,   'rusak_ringan', 'maintenance', 900000],
            ['Rack Server 12U',          'Tantan',    'TT-1200',        'tools', 'RCK', 1, $locMain,   'baik',         'tersedia',   3500000],
            ['Patch Panel 24 Port',      'Panduit',   'CP24WSBLY',      'tools', 'PPL', 2, $locMain,   'baik',         'tersedia',   1200000],
            ['Wireless Controller',      'Ubiquiti',  'CloudKey+',      'tools', 'CTL', 1, $locMain,   'baik',         'tersedia',   3800000],
            ['KVM Switch 8-Port',        'ATEN',      'CS-1308A',       'tools', 'KVM', 1, $locMain,   'baik',         'tersedia',   2500000],
            ['Thermal Paste',            'Arctic',    'MX-4',           'tools', 'MNT', 10, $locGudang, 'baik',        'tersedia',     85000],
            ['Compressed Air Duster',    'Falcon',    '3-PACK',         'tools', 'CLN', 5, $locGudang, 'baik',         'tersedia',    120000],

            // ================================================================
            // KABEL — Kabel Jaringan & Aksesoris
            // ================================================================
            ['Kabel UTP Belden Cat6',    'Belden',    '1305E Cat6 305m','kabel-jaringan', 'BLD', 2, $locGudang, 'baik', 'tersedia', 1850000],
            ['Kabel UTP AMP Cat5e',      'AMP',       'Cat5e 305m',     'kabel-jaringan', 'AMP', 2, $locGudang, 'baik', 'tersedia',  820000],
            ['Kabel Fiber Optic SC-LC',  'Panduit',   'FISC3RAQNSNM001','kabel-jaringan', 'FBR', 5, $locMain,  'baik', 'tersedia',  180000],
            ['Kabel Fiber Optic SC-SC',  'Panduit',   'FISC3RAQNSNM003','kabel-jaringan', 'FBR', 5, $locMain,  'baik', 'tersedia',  200000],
            ['Konektor RJ45 Cat6 (Box)', 'AMP',       'Cat6 100pcs',    'kabel-jaringan', 'RJ4', 5, $locGudang, 'baik', 'tersedia',  200000],
            ['Keystone Jack Cat6',       'Panduit',   'CJ688TGI',       'kabel-jaringan', 'KST', 24, $locMain, 'baik', 'tersedia',   35000],
            ['Faceplate 2-Port',         'Panduit',   'CFP2IWH',        'kabel-jaringan', 'FPL', 12, $locMain, 'baik', 'tersedia',   25000],
            ['Cable Manager 1U',         'Panduit',   'WMPH2',          'kabel-jaringan', 'CMG', 2, $locMain,  'baik', 'tersedia',  350000],

            // ================================================================
            // ACCESS POINT — Tambahan
            // ================================================================
            ['Access Point Mikrotik',    'MikroTik',  'hAP ac²',        'access-point', 'MKT', 3, $locLab3, 'baik',         'tersedia',  850000],
            ['Access Point Mikrotik',    'MikroTik',  'wAP ac',         'access-point', 'MKT', 2, $locLab1, 'baik',         'dipinjam',  750000],
            ['Access Point Grandstream', 'Grandstream','GWN7605',       'access-point', 'GDS', 2, $locLab2, 'baik',         'tersedia', 1200000],

            // ================================================================
            // SWITCH — Tambahan
            // ================================================================
            ['Switch Unmanaged 8-Port',  'Netgear',   'GS308',          'switch-hub', 'NGR', 4, $locLab1, 'baik',         'tersedia',  450000],
            ['Switch Managed 24-Port',   'MikroTik',  'CRS326-24G',     'switch-hub', 'MKT', 2, $locMain, 'baik',         'tersedia', 4800000],
            ['Switch PoE 8-Port',        'TP-Link',   'TL-SG1210P',     'switch-hub', 'TPL', 3, $locLab3, 'baik',         'dipinjam', 1800000],
            ['Switch PoE 8-Port',        'TP-Link',   'TL-SG1210P',     'switch-hub', 'TPL', 1, $locLab2, 'rusak_ringan', 'maintenance', 1800000],

            // ================================================================
            // ROUTER — Tambahan
            // ================================================================
            ['Router Mikrotik hEX',      'MikroTik',  'RB750Gr3 hEX',   'router', 'MKT', 4, $locLab3, 'baik',         'tersedia',  750000],
            ['Router Mikrotik CCR',      'MikroTik',  'CCR1009-8G-1S',  'router', 'MKT', 1, $locMain, 'baik',         'tersedia', 5500000],
            ['Firewall Fortinet',        'Fortinet',  'FortiGate 40F',  'router', 'FTN', 1, $locMain, 'baik',         'tersedia',18000000],

            // ================================================================
            // LAPTOP — Tambahan
            // ================================================================
            ['Laptop HP Probook',        'HP',        'ProBook 450 G9', 'laptop', 'HP',  3, $locMain,  'baik',         'tersedia',  11500000],
            ['Laptop HP Probook',        'HP',        'ProBook 450 G9', 'laptop', 'HP',  1, $locLab2,  'dipinjam',     'dipinjam',  11500000],
            ['Laptop Acer Aspire',       'Acer',      'Aspire 5 A515',  'laptop', 'ACR', 2, $locLab1,  'baik',         'tersedia',   9000000],
            ['Laptop Acer Aspire',       'Acer',      'Aspire 5 A515',  'laptop', 'ACR', 1, $locLab1,  'rusak_ringan', 'maintenance', 9000000],

            // ================================================================
            // MONITOR — Tambahan
            // ================================================================
            ['Monitor AOC',              'AOC',       'C27G2ZE',        'monitor', 'AOC', 4, $locLab2, 'baik', 'tersedia', 3200000],
            ['Monitor Philips',          'Philips',   '243E9QJAB',      'monitor', 'PLP', 2, $locLab1, 'baik', 'dipinjam', 2100000],

            // ================================================================
            // PC CLIENT — Tambahan
            // ================================================================
            ['PC Client Mini',           'Lenovo',    'ThinkCentre M70q', 'pc-client', 'LNV', 5, $locLab1, 'baik', 'tersedia',  8500000],
            ['PC Client Mini',           'Lenovo',    'ThinkCentre M70q', 'pc-client', 'LNV', 2, $locLab2, 'baik', 'dipinjam',  8500000],
            ['PC All-in-One',            'ASUS',      'AIO V222FAK',      'pc-client', 'ASS', 3, $locLab2, 'baik', 'tersedia', 10000000],

            // ================================================================
            // SERVER — Tambahan
            // ================================================================
            ['NAS Server Synology',      'Synology',  'DS923+',         'server', 'SYN', 1, $locMain, 'baik', 'tersedia', 9500000],
            ['Raspberry Pi 4',           'Raspberry', 'Pi 4 Model B',   'server', 'RPI', 3, $locMain, 'baik', 'tersedia',  950000],
        ];

        $inserted = 0;

        foreach ($itemDefs as $def) {
            [$name, $brand, $model, $catSlug, $subPrefix, $unitCount, $locId, $condition, $status, $price] = $def;

            $catId = $categories[$catSlug] ?? null;
            if (!$catId) {
                $this->command->warn("  [!] Kategori '{$catSlug}' tidak ditemukan, dilewati.");
                continue;
            }

            $catRow    = DB::table('categories')->find($catId);
            $catPrefix = $catRow->prefix;
            $kondisiLabel = $conditionMap[$condition];
            $kondisiId = $kondisis[$kondisiLabel] ?? null;

            for ($u = 0; $u < $unitCount; $u++) {
                $nextNum = DB::table('categories')->where('id', $catId)->value('last_code_number') + 1;
                DB::table('categories')->where('id', $catId)->update([
                    'last_code_number' => $nextNum,
                    'updated_at'       => $now,
                ]);

                $code = $catPrefix . '-' . strtoupper($subPrefix) . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

                // Skip jika kode sudah ada (idempotent)
                if (DB::table('items')->where('code', $code)->exists()) {
                    continue;
                }

                DB::table('items')->insert([
                    'name'              => $name,
                    'code'              => $code,
                    'sub_prefix'        => strtoupper($subPrefix),
                    'serial_number'     => strtoupper($subPrefix) . '-SN-' . rand(10000, 99999),
                    'brand'             => $brand,
                    'model'             => $model,
                    'category_id'       => $catId,
                    'location_id'       => $locId,
                    'supplier_id'       => !empty($suppliers) ? $suppliers[array_rand($suppliers)] : null,
                    'asal_barang_id'    => !empty($asals) ? $asals[array_rand($asals)] : null,
                    'kondisi_barang_id' => $kondisiId,
                    'quantity'          => 1,
                    'condition'         => $condition,
                    'status'            => ($status === 'dipinjam') ? 'dipinjam' : $status,
                    'purchase_date'     => Carbon::now()->subDays(rand(30, 500))->format('Y-m-d'),
                    'purchase_price'    => $price,
                    'notes'             => "Dummy - {$brand} {$model}",
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);

                $inserted++;
            }
        }

        $this->command->info("ToolsDummySeeder selesai! {$inserted} unit barang berhasil ditambahkan.");
    }
}
