<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NocSeeder extends Seeder
{
    /**
     * Seed data lengkap ERP NOC SMKN 4 Malang.
     * Logika:
     *  - 1 row items = 1 unit fisik (quantity = 1)
     *  - Kode barang: PREFIX-SUBPREFIX-NUMBER (atomic increment dari categories.last_code_number)
     *  - condition (baik/rusak_ringan/rusak_berat/hilang) sinkron ke kondisi_barang_id
     *  - status (tersedia/dipinjam/maintenance/dimusnahkan) konsisten dengan peminjaman
     *  - User Guru/Siswa punya jurusan_id
     */
    public function run(): void
    {
        // Bersihkan data lama agar seeder bisa dijalankan berulang (idempotent)
        // Urutan: child tables dulu, baru parent tables
        DB::statement('DELETE FROM perawatans');
        DB::statement('DELETE FROM peminjaman');
        DB::statement('DELETE FROM item_movements');
        DB::statement('DELETE FROM items');
        DB::statement('DELETE FROM scan_sessions');
        DB::statement("DELETE FROM users WHERE role NOT IN ('Superadmin', 'Admin')");
        DB::statement('DELETE FROM suppliers');
        DB::statement('DELETE FROM asal_barangs');
        DB::statement('DELETE FROM kondisi_barangs');
        DB::statement('DELETE FROM jurusans');
        DB::statement('DELETE FROM locations');
        DB::statement('DELETE FROM categories');

        $now = Carbon::now();

        // =========================================================================
        // 1. MASTER DATA: Kategori (dengan prefix & last_code_number)
        // =========================================================================
        $categories = [
            ['name' => 'Switch/Hub',      'slug' => 'switch-hub',      'description' => 'Perangkat Switch dan Hub',              'prefix' => 'SWT', 'last_code_number' => 0],
            ['name' => 'Router',           'slug' => 'router',          'description' => 'Perangkat Router',                     'prefix' => 'RTR', 'last_code_number' => 0],
            ['name' => 'Access Point',     'slug' => 'access-point',    'description' => 'Perangkat Access Point',               'prefix' => 'AP',  'last_code_number' => 0],
            ['name' => 'Server',           'slug' => 'server',          'description' => 'Komputer Server',                      'prefix' => 'SRV', 'last_code_number' => 0],
            ['name' => 'Kabel Jaringan',   'slug' => 'kabel-jaringan',  'description' => 'Kabel UTP, Fiber Optic, dll',         'prefix' => 'CBL', 'last_code_number' => 0],
            ['name' => 'PC Client',        'slug' => 'pc-client',       'description' => 'Personal Computer untuk Client',       'prefix' => 'PC',  'last_code_number' => 0],
            ['name' => 'Laptop',           'slug' => 'laptop',          'description' => 'Komputer Jinjing',                     'prefix' => 'LPT', 'last_code_number' => 0],
            ['name' => 'Monitor',          'slug' => 'monitor',         'description' => 'Layar Monitor',                        'prefix' => 'MNT', 'last_code_number' => 0],
            ['name' => 'Tools',            'slug' => 'tools',           'description' => 'Peralatan Jaringan (Crimping, Tester)','prefix' => 'TLS', 'last_code_number' => 0],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $id = DB::table('categories')->insertGetId(array_merge($cat, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
            $catIds[$cat['slug']] = $id;
        }

        // =========================================================================
        // 2. MASTER DATA: Lokasi
        // =========================================================================
        $locations = [
            ['code' => 'LOC-001', 'name' => 'Ruang Server NOC', 'description' => 'Ruang Server Utama Gedung A',       'penanggung_jawab' => 'Pak Budi'],
            ['code' => 'LOC-002', 'name' => 'Lab RPL 1',         'description' => 'Laboratorium Rekayasa Perangkat Lunak 1', 'penanggung_jawab' => 'Pak Anton'],
            ['code' => 'LOC-003', 'name' => 'Lab RPL 2',         'description' => 'Laboratorium Rekayasa Perangkat Lunak 2', 'penanggung_jawab' => 'Bu Siska'],
            ['code' => 'LOC-004', 'name' => 'Lab TKJ 1',         'description' => 'Laboratorium Teknik Komputer Jaringan 1', 'penanggung_jawab' => 'Pak Yanto'],
            ['code' => 'LOC-005', 'name' => 'Lab TKJ 2',         'description' => 'Laboratorium Teknik Komputer Jaringan 2', 'penanggung_jawab' => 'Pak Yanto'],
            ['code' => 'LOC-006', 'name' => 'Gudang NOC',        'description' => 'Gudang Penyimpanan Barang Jaringan',       'penanggung_jawab' => 'Pak Budi'],
            ['code' => 'LOC-007', 'name' => 'Ruang Guru',        'description' => 'Ruangan Guru Produktif',                   'penanggung_jawab' => 'Kepala Bengkel'],
        ];

        $locIds = [];
        foreach ($locations as $loc) {
            $locIds[] = DB::table('locations')->insertGetId(array_merge($loc, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
        }

        // =========================================================================
        // 3. MASTER DATA: Supplier
        // =========================================================================
        $suppliers = [
            ['name' => 'PT. MikroTik Indonesia',    'pic' => 'Hendro', 'phone' => '081234567890', 'email' => 'sales@mikrotik.co.id',     'address' => 'Jakarta',  'is_active' => 1],
            ['name' => 'CV. Sinar Jaya Komputer',   'pic' => 'Agus',   'phone' => '082233445566', 'email' => 'info@sinarkomputer.com',    'address' => 'Malang',   'is_active' => 1],
            ['name' => 'Toko Sentra Jaringan',      'pic' => 'Budi',   'phone' => '083344556677', 'email' => 'sentra.jaringan@gmail.com', 'address' => 'Surabaya', 'is_active' => 1],
            ['name' => 'Bhinneka',                  'pic' => 'Siti',   'phone' => '084455667788', 'email' => 'corporate@bhinneka.com',    'address' => 'Jakarta',  'is_active' => 1],
            ['name' => 'Tidak Diketahui',           'pic' => '-',      'phone' => '-',           'email' => '-',                          'address' => '-',        'is_active' => 0],
        ];

        $supIds = [];
        foreach ($suppliers as $sup) {
            $supIds[] = DB::table('suppliers')->insertGetId(array_merge($sup, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
        }

        // =========================================================================
        // 4. MASTER DATA: Kondisi Barang
        //    Mapping condition field -> kondisi_barang_id (sinkron)
        // =========================================================================
        $kondisis = [
            'baik'         => ['name' => 'Baik',         'label_color' => 'green',  'description' => 'Dapat berfungsi dengan normal'],
            'rusak_ringan' => ['name' => 'Rusak Ringan', 'label_color' => 'yellow', 'description' => 'Masih bisa digunakan dengan perbaikan kecil'],
            'rusak_berat'  => ['name' => 'Rusak Berat',  'label_color' => 'red',    'description' => 'Tidak dapat digunakan dan butuh perbaikan besar'],
            'hilang'       => ['name' => 'Hilang',       'label_color' => 'gray',   'description' => 'Barang tidak ditemukan'],
        ];

        $kondisiIds = []; // key = condition string, value = id
        foreach ($kondisis as $key => $kondisi) {
            $kondisiIds[$key] = DB::table('kondisi_barangs')->insertGetId(array_merge($kondisi, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
        }

        // =========================================================================
        // 5. MASTER DATA: Asal Barang
        // =========================================================================
        $asals = [
            ['name' => 'Dana BOS',           'description' => 'Pembelian dari dana BOS reguler',    'is_active' => 1],
            ['name' => 'Bantuan Pemerintah', 'description' => 'Bantuan dari Kemendikbud',           'is_active' => 1],
            ['name' => 'Hibah Perusahaan',   'description' => 'CSR dari Perusahaan Rekanan',        'is_active' => 1],
            ['name' => 'Komite Sekolah',     'description' => 'Sumbangan dari wali murid',          'is_active' => 1],
        ];

        $asalIds = [];
        foreach ($asals as $asal) {
            $asalIds[] = DB::table('asal_barangs')->insertGetId(array_merge($asal, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
        }

        // =========================================================================
        // 6. MASTER DATA: Jurusan
        // =========================================================================
        $jurusans = [
            ['name' => 'Rekayasa Perangkat Lunak',              'description' => 'Jurusan RPL',  'is_active' => 1],
            ['name' => 'Teknik Komputer dan Jaringan',          'description' => 'Jurusan TKJ',  'is_active' => 1],
            ['name' => 'Multimedia',                            'description' => 'Jurusan MM',   'is_active' => 1],
            ['name' => 'Sistem Informatika Jaringan dan Aplikasi','description' => 'Jurusan SIJA','is_active' => 1],
        ];

        $jurusanIds = [];
        foreach ($jurusans as $jurusan) {
            $jurusanIds[] = DB::table('jurusans')->insertGetId(array_merge($jurusan, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
        }

        // =========================================================================
        // 7. USER: Guru (10) & Siswa (40) — dengan jurusan_id
        // =========================================================================
        $userCounter = 3; // USR-001 & USR-002 sudah dipakai Superadmin & Admin

        $firstNames = ['Budi','Anton','Siska','Yanto','Ani','Joko','Siti','Dewi','Rudi','Andi','Rina','Nina','Eko','Agus','Dwi','Tri'];
        $lastNames  = ['Santoso','Wijaya','Pratama','Kusuma','Sari','Lestari','Hidayat','Saputra','Setiawan','Nugroho'];

        // Helper: generate user_code
        $userCode = function () use (&$userCounter) {
            return 'USR-' . str_pad($userCounter++, 3, '0', STR_PAD_LEFT);
        };

        // 10 Guru — assign jurusan_id
        $guruIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $guruIds[] = DB::table('users')->insertGetId([
                'user_code'  => $userCode(),
                'name'       => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'username'   => 'guru' . $i,
                'email'      => 'guru' . $i . '@noc.smkn4malang.sch.id',
                'password'   => Hash::make('password123'),
                'role'       => 'Guru',
                'is_active'  => true,
                'jurusan_id' => $jurusanIds[array_rand($jurusanIds)],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 40 Siswa — assign jurusan_id, email digunakan untuk menyimpan kelas
        $kelas = ['X RPL 1', 'X RPL 2', 'XI TKJ 1', 'XI TKJ 2', 'XII MM 1', 'XII SIJA'];
        $siswaIds = [];
        for ($i = 1; $i <= 40; $i++) {
            $siswaIds[] = DB::table('users')->insertGetId([
                'user_code'  => $userCode(),
                'name'       => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'username'   => 'siswa' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'email'      => $kelas[array_rand($kelas)],
                'password'   => Hash::make('password123'),
                'role'       => 'Siswa',
                'is_active'  => true,
                'jurusan_id' => $jurusanIds[array_rand($jurusanIds)],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $allUserIds = array_merge($guruIds, $siswaIds);

        // =========================================================================
        // 8. ITEMS — Setiap unit = 1 row, qty=1, dengan sub_prefix
        //    Format kode: PREFIX-SUBPREFIX-NUMBER
        //    condition sinkron ke kondisi_barang_id
        //    status konsisten: dipinjam hanya jika ada peminjaman aktif
        // =========================================================================
        $itemDefs = [
            // [name, brand, model, categorySlug, sub_prefix, unitCount, locIdx, condition, status, price]
            // --- Access Point ---
            ['Access Point UniFi',   'Ubiquiti',  'UAP-AC-Pro',    'access-point',   'UNI', 5, 0, 'baik',         'tersedia',    2500000],
            ['Access Point UniFi',   'Ubiquiti',  'UAP-AC-Pro',    'access-point',   'UNI', 3, 1, 'baik',         'tersedia',    2500000],
            ['Access Point UniFi',   'Ubiquiti',  'UAP-AC-Lite',   'access-point',   'UNI', 2, 3, 'baik',         'dipinjam',    1800000],
            ['Access Point TP-Link', 'TP-Link',   'EAP225',        'access-point',   'TPL', 4, 0, 'baik',         'tersedia',    850000],
            ['Access Point TP-Link', 'TP-Link',   'EAP225',        'access-point',   'TPL', 2, 4, 'rusak_ringan', 'maintenance', 850000],
            // --- Router ---
            ['Router MikroTik',      'MikroTik',  'RB750Gr3',      'router',         'MKT', 6, 0, 'baik',         'tersedia',    1200000],
            ['Router MikroTik',      'MikroTik',  'RB750Gr3',      'router',         'MKT', 3, 3, 'baik',         'dipinjam',    1200000],
            ['Router MikroTik',      'MikroTik',  'RB951Ui-2HnD',  'router',         'MKT', 2, 1, 'rusak_ringan', 'tersedia',    900000],
            ['Router Cisco',         'Cisco',     'ISR4321',       'router',         'CSC', 2, 0, 'baik',         'tersedia',    15000000],
            ['Router Cisco',         'Cisco',     'ISR4321',       'router',         'CSC', 1, 0, 'rusak_berat',  'maintenance', 15000000],
            // --- Switch ---
            ['Switch TP-Link',       'TP-Link',   'TL-SG1024D',    'switch-hub',     'TPL', 4, 0, 'baik',         'tersedia',    1500000],
            ['Switch TP-Link',       'TP-Link',   'TL-SG1024D',    'switch-hub',     'TPL', 3, 1, 'baik',         'tersedia',    1500000],
            ['Switch Cisco',         'Cisco',     'Catalyst 2960', 'switch-hub',     'CSC', 3, 0, 'baik',         'tersedia',    8000000],
            ['Switch Cisco',         'Cisco',     'Catalyst 2960', 'switch-hub',     'CSC', 2, 3, 'baik',         'dipinjam',    8000000],
            ['Switch D-Link',        'D-Link',    'DGS-1008A',     'switch-hub',     'DLK', 2, 4, 'baik',         'tersedia',    650000],
            // --- Server ---
            ['Server Dell',          'Dell',      'PowerEdge R440','server',         'DEL', 2, 0, 'baik',         'tersedia',    45000000],
            ['Server Dell',          'Dell',      'PowerEdge T340','server',         'DEL', 1, 0, 'baik',         'tersedia',    35000000],
            // --- PC Client ---
            ['PC Client',            'Rakitan',   'Core i5 Gen10', 'pc-client',      'I5',  8, 1, 'baik',         'tersedia',    7000000],
            ['PC Client',            'Rakitan',   'Core i5 Gen10', 'pc-client',      'I5',  5, 2, 'baik',         'tersedia',    7000000],
            ['PC Client',            'Rakitan',   'Core i7 Gen11', 'pc-client',      'I7',  4, 1, 'baik',         'tersedia',    12000000],
            ['PC Client',            'Rakitan',   'Core i7 Gen11', 'pc-client',      'I7',  2, 2, 'rusak_ringan', 'maintenance', 12000000],
            // --- Laptop ---
            ['Laptop Lenovo',        'Lenovo',    'V14 G3',        'laptop',         'LNV', 3, 0, 'baik',         'tersedia',    8500000],
            ['Laptop Lenovo',        'Lenovo',    'V14 G3',        'laptop',         'LNV', 2, 6, 'baik',         'dipinjam',    8500000],
            ['Laptop ASUS',          'ASUS',      'ExpertBook B1', 'laptop',         'ASS', 2, 0, 'baik',         'tersedia',    9000000],
            // --- Monitor ---
            ['Monitor Samsung',      'Samsung',   'LS24A350',      'monitor',        'SMS', 5, 1, 'baik',         'tersedia',    2200000],
            ['Monitor Samsung',      'Samsung',   'LS24A350',      'monitor',        'SMS', 3, 2, 'baik',         'tersedia',    2200000],
            ['Monitor LG',           'LG',        '22MP410',       'monitor',        'LG',  4, 1, 'baik',         'tersedia',    1800000],
            // --- Kabel ---
            ['Kabel UTP Belden',     'Belden',    'Cat6 305m',     'kabel-jaringan', 'BLD', 3, 5, 'baik',         'tersedia',    1500000],
            ['Kabel UTP AMP',        'AMP',       'Cat5e 305m',    'kabel-jaringan', 'AMP', 2, 5, 'baik',         'tersedia',    800000],
            ['Konektor RJ45',        'AMP',       'Cat6',          'kabel-jaringan', 'RJ4', 10,5, 'baik',         'tersedia',    150000],
            // --- Tools ---
            ['Tang Crimping',        'TRENDnet',  'TC-CT68',       'tools',          'CRP', 3, 0, 'baik',         'tersedia',    250000],
            ['LAN Tester',           'TRENDnet',  'TC-NT12',       'tools',          'TST', 2, 0, 'baik',         'tersedia',    350000],
            ['Proyektor Epson',      'Epson',     'EB-X51',        'tools',          'EPS', 2, 6, 'baik',         'tersedia',    6500000],
            ['Proyektor Epson',      'Epson',     'EB-X51',        'tools',          'EPS', 1, 0, 'rusak_ringan', 'maintenance', 6500000],
        ];

        $itemIds = [];
        $itemCodes = [];
        $dipinjamItemIds = []; // Track items with status 'dipinjam' for peminjaman seeding

        foreach ($itemDefs as $def) {
            [$name, $brand, $model, $catSlug, $subPrefix, $unitCount, $locIdx, $condition, $status, $price] = $def;

            $catId     = $catIds[$catSlug] ?? null;
            if (!$catId) continue;

            // Get current prefix from category
            $catPrefix = DB::table('categories')->where('id', $catId)->value('prefix');
            $locId     = $locIds[$locIdx] ?? $locIds[0];
            $purchaseDate = Carbon::now()->subDays(rand(30, 700))->format('Y-m-d');

            // Map condition string to kondisi_barang_id
            $kondisiBarangId = $kondisiIds[$condition] ?? null;

            for ($u = 0; $u < $unitCount; $u++) {
                // Atomic: increment last_code_number pada category
                $nextNum = DB::table('categories')->where('id', $catId)->value('last_code_number') + 1;
                DB::table('categories')->where('id', $catId)->update([
                    'last_code_number' => $nextNum,
                    'updated_at' => $now,
                ]);

                // Format kode: PREFIX-SUBPREFIX-NUMBER
                $code = $catPrefix . '-' . strtoupper($subPrefix) . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

                $id = DB::table('items')->insertGetId([
                    'name'              => $name,
                    'code'              => $code,
                    'sub_prefix'        => strtoupper($subPrefix),
                    'serial_number'     => strtoupper($subPrefix) . '-' . rand(10000, 99999),
                    'brand'             => $brand,
                    'model'             => $model,
                    'category_id'       => $catId,
                    'location_id'       => $locId,
                    'supplier_id'       => $supIds[array_rand($supIds)],
                    'asal_barang_id'    => $asalIds[array_rand($asalIds)],
                    'kondisi_barang_id' => $kondisiBarangId, // Sinkron dengan condition
                    'quantity'          => 1,
                    'condition'         => $condition,
                    'status'            => $status,
                    'purchase_date'     => $purchaseDate,
                    'purchase_price'    => $price,
                    'notes'             => $brand . ' ' . $model,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);

                $itemIds[] = $id;
                $itemCodes[$id] = $code;

                // Track dipinjam items
                if ($status === 'dipinjam') {
                    $dipinjamItemIds[] = $id;
                }
            }
        }

        // =========================================================================
        // 9. ITEM MOVEMENTS — riwayat pergerakan barang
        //    Type: masuk, keluar, pindah, maintenance, rusak, musnahkan
        // =========================================================================
        $adminIds = DB::table('users')
            ->whereIn('role', ['Admin', 'Superadmin'])
            ->pluck('id')
            ->toArray();

        $movementTypes = ['masuk', 'keluar', 'pindah', 'maintenance', 'rusak', 'musnahkan'];
        $movements = [];

        for ($i = 0; $i < 100; $i++) {
            $type = $movementTypes[array_rand($movementTypes)];
            $movementDate = Carbon::now()->subDays(rand(1, 300));

            $movements[] = [
                'item_id'          => $itemIds[array_rand($itemIds)],
                'user_id'          => $adminIds[array_rand($adminIds)],
                'type'             => $type,
                'quantity'         => 1, // qty selalu 1 karena 1 row = 1 unit
                'from_location_id' => $locIds[array_rand($locIds)],
                'to_location_id'   => $locIds[array_rand($locIds)],
                'notes'            => 'Mutasi barang - ' . $type,
                'movement_date'    => $movementDate->format('Y-m-d'),
                'created_at'       => $movementDate,
                'updated_at'       => $movementDate,
            ];
        }

        foreach (array_chunk($movements, 50) as $chunk) {
            DB::table('item_movements')->insert($chunk);
        }

        // =========================================================================
        // 10. PEMINJAMAN — konsisten dengan item status
        //     - Item dengan status 'dipinjam' punya peminjaman aktif (status='dipinjam')
        //     - Item lain punya peminjaman lampau (status='dikembalikan')
        //     - kondisi_saat_kembali & keterangan_kembali hanya diisi jika sudah dikembalikan
        // =========================================================================
        $peminjamans = [];

        $keteranganOptions = [
            'Barang dikembalikan dalam kondisi baik, tidak ada kerusakan.',
            'Terdapat goresan ringan pada casing, fungsi normal.',
            'Port LAN agak longgar tapi masih bisa digunakan.',
            'Layar ada bercak kecil, masih bisa digunakan.',
            'Tombol power agak keras, perlu ditekan lebih kuat.',
            'Kabel power sudah agak aus, perlu penggantian.',
            'Barang dikembalikan tanpa kelengkapan (kabel/adaptor).',
            'Kondisi fisik baik, tapi baterai sudah drop.',
        ];

        // A. Peminjaman aktif (status='dipinjam') untuk semua item yang berstatus 'dipinjam'
        foreach ($dipinjamItemIds as $itemId) {
            $tglPinjam = Carbon::now()->subDays(rand(1, 14));

            $peminjamans[] = [
                'nama_peminjam'        => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'kelas'                => $kelas[array_rand($kelas)],
                'item_id'              => $itemId,
                'item_code'            => $itemCodes[$itemId],
                'session_token'        => 'SEED-' . Str::random(10),
                'waktu_pinjam'         => $tglPinjam,
                'waktu_kembali'        => null,
                'status'               => 'dipinjam',
                'kondisi_saat_kembali' => null,
                'keterangan_kembali'   => null,
                'foto_kembali'         => null,
                'catatan'              => 'Peminjaman aktif dari seeder',
                'created_at'           => $tglPinjam,
                'updated_at'           => $now,
            ];
        }

        // B. Peminjaman lampau (status='dikembalikan') — random 40 record
        $returnedItemIds = array_diff($itemIds, $dipinjamItemIds);
        $returnedItemIds = array_values($returnedItemIds);

        for ($i = 0; $i < 40; $i++) {
            $itemId = $returnedItemIds[array_rand($returnedItemIds)];
            $tglPinjam   = Carbon::now()->subDays(rand(15, 90));
            $tglKembali  = (clone $tglPinjam)->addDays(rand(1, 7));

            // Distribusi kondisi kembali: 70% baik, 15% rusak_ringan, 10% rusak_berat, 5% hilang
            $rand = rand(1, 100);
            if ($rand <= 70)      $kondisiKembali = 'baik';
            elseif ($rand <= 85)  $kondisiKembali = 'rusak_ringan';
            elseif ($rand <= 95)  $kondisiKembali = 'rusak_berat';
            else                  $kondisiKembali = 'hilang';

            $peminjamans[] = [
                'nama_peminjam'        => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'kelas'                => $kelas[array_rand($kelas)],
                'item_id'              => $itemId,
                'item_code'            => $itemCodes[$itemId],
                'session_token'        => 'SEED-' . Str::random(10),
                'waktu_pinjam'         => $tglPinjam,
                'waktu_kembali'        => $tglKembali,
                'status'               => 'dikembalikan',
                'kondisi_saat_kembali' => $kondisiKembali,
                'keterangan_kembali'   => $keteranganOptions[array_rand($keteranganOptions)],
                'foto_kembali'         => null,
                'catatan'              => 'Riwayat peminjaman dari seeder',
                'created_at'           => $tglPinjam,
                'updated_at'           => $tglKembali,
            ];
        }

        foreach (array_chunk($peminjamans, 50) as $chunk) {
            DB::table('peminjaman')->insert($chunk);
        }

        $this->command->info('NocSeeder: ' . count($itemIds) . ' items, ' . count($peminjamans) . ' peminjaman, ' . count($movements) . ' movements seeded.');
    }
}
