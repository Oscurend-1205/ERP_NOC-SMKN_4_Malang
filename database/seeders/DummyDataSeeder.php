<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Seed Categories
        $categories = [
            ['name' => 'Switch/Hub', 'slug' => Str::slug('Switch/Hub'), 'description' => 'Perangkat Switch dan Hub', 'prefix' => 'SWT', 'last_code_number' => 0],
            ['name' => 'Router', 'slug' => Str::slug('Router'), 'description' => 'Perangkat Router', 'prefix' => 'RTR', 'last_code_number' => 0],
            ['name' => 'Access Point', 'slug' => Str::slug('Access Point'), 'description' => 'Perangkat Access Point', 'prefix' => 'AP', 'last_code_number' => 0],
            ['name' => 'Server', 'slug' => Str::slug('Server'), 'description' => 'Komputer Server', 'prefix' => 'SRV', 'last_code_number' => 0],
            ['name' => 'Kabel Jaringan', 'slug' => Str::slug('Kabel Jaringan'), 'description' => 'Kabel UTP, Fiber Optic, dll', 'prefix' => 'CBL', 'last_code_number' => 0],
            ['name' => 'PC Client', 'slug' => Str::slug('PC Client'), 'description' => 'Personal Computer untuk Client', 'prefix' => 'PC', 'last_code_number' => 0],
            ['name' => 'Laptop', 'slug' => Str::slug('Laptop'), 'description' => 'Komputer Jinjing', 'prefix' => 'LPT', 'last_code_number' => 0],
            ['name' => 'Monitor', 'slug' => Str::slug('Monitor'), 'description' => 'Layar Monitor', 'prefix' => 'MNT', 'last_code_number' => 0],
            ['name' => 'Tools', 'slug' => Str::slug('Tools'), 'description' => 'Peralatan Jaringan (Crimping, Lan Tester, dll)', 'prefix' => 'TLS', 'last_code_number' => 0],
        ];
        
        $categoryIds = [];
        foreach ($categories as $cat) {
            $id = DB::table('categories')->insertGetId(array_merge($cat, ['created_at' => $now, 'updated_at' => $now]));
            $categoryIds[] = $id;
        }

        // 2. Seed Locations
        $locations = [
            ['code' => 'LOC-001', 'name' => 'Ruang Server NOC', 'description' => 'Ruang Server Utama Gedung A', 'penanggung_jawab' => 'Pak Budi'],
            ['code' => 'LOC-002', 'name' => 'Lab RPL 1', 'description' => 'Laboratorium Rekayasa Perangkat Lunak 1', 'penanggung_jawab' => 'Pak Anton'],
            ['code' => 'LOC-003', 'name' => 'Lab RPL 2', 'description' => 'Laboratorium Rekayasa Perangkat Lunak 2', 'penanggung_jawab' => 'Bu Siska'],
            ['code' => 'LOC-004', 'name' => 'Lab TKJ 1', 'description' => 'Laboratorium Teknik Komputer Jaringan 1', 'penanggung_jawab' => 'Pak Yanto'],
            ['code' => 'LOC-005', 'name' => 'Lab TKJ 2', 'description' => 'Laboratorium Teknik Komputer Jaringan 2', 'penanggung_jawab' => 'Pak Yanto'],
            ['code' => 'LOC-006', 'name' => 'Gudang NOC', 'description' => 'Gudang Penyimpanan Barang Jaringan', 'penanggung_jawab' => 'Pak Budi'],
            ['code' => 'LOC-007', 'name' => 'Ruang Guru', 'description' => 'Ruangan Guru Produktif', 'penanggung_jawab' => 'Kepala Bengkel'],
        ];

        $locationIds = [];
        foreach ($locations as $loc) {
            $id = DB::table('locations')->insertGetId(array_merge($loc, ['created_at' => $now, 'updated_at' => $now]));
            $locationIds[] = $id;
        }

        // 3. Seed Suppliers
        $suppliers = [
            ['name' => 'PT. MikroTik Indonesia', 'pic' => 'Hendro', 'phone' => '081234567890', 'email' => 'sales@mikrotik.co.id', 'address' => 'Jakarta', 'is_active' => 1],
            ['name' => 'CV. Sinar Jaya Komputer', 'pic' => 'Agus', 'phone' => '082233445566', 'email' => 'info@sinarkomputer.com', 'address' => 'Malang', 'is_active' => 1],
            ['name' => 'Toko Sentra Jaringan', 'pic' => 'Budi', 'phone' => '083344556677', 'email' => 'sentra.jaringan@gmail.com', 'address' => 'Surabaya', 'is_active' => 1],
            ['name' => 'Bhinneka', 'pic' => 'Siti', 'phone' => '084455667788', 'email' => 'corporate@bhinneka.com', 'address' => 'Jakarta', 'is_active' => 1],
            ['name' => 'Tidak Diketahui', 'pic' => '-', 'phone' => '-', 'email' => '-', 'address' => '-', 'is_active' => 0],
        ];

        $supplierIds = [];
        foreach ($suppliers as $sup) {
            $id = DB::table('suppliers')->insertGetId(array_merge($sup, ['created_at' => $now, 'updated_at' => $now]));
            $supplierIds[] = $id;
        }

        // 4. Seed Kondisi Barang
        $kondisis = [
            ['name' => 'Baik', 'label_color' => 'green', 'description' => 'Dapat berfungsi dengan normal'],
            ['name' => 'Rusak Ringan', 'label_color' => 'yellow', 'description' => 'Masih bisa digunakan dengan perbaikan kecil'],
            ['name' => 'Rusak Berat', 'label_color' => 'red', 'description' => 'Tidak dapat digunakan dan butuh perbaikan besar'],
            ['name' => 'Hilang', 'label_color' => 'gray', 'description' => 'Barang tidak ditemukan'],
        ];

        $kondisiIds = [];
        foreach ($kondisis as $kondisi) {
            $id = DB::table('kondisi_barangs')->insertGetId(array_merge($kondisi, ['created_at' => $now, 'updated_at' => $now]));
            $kondisiIds[] = $id;
        }

        // 5. Seed Asal Barang
        $asals = [
            ['name' => 'Dana BOS', 'description' => 'Pembelian dari dana BOS reguler', 'is_active' => 1],
            ['name' => 'Bantuan Pemerintah', 'description' => 'Bantuan dari Kemendikbud', 'is_active' => 1],
            ['name' => 'Hibah Perusahaan', 'description' => 'CSR dari Perusahaan Rekanan', 'is_active' => 1],
            ['name' => 'Komite Sekolah', 'description' => 'Sumbangan dari wali murid', 'is_active' => 1],
        ];

        $asalIds = [];
        foreach ($asals as $asal) {
            $id = DB::table('asal_barangs')->insertGetId(array_merge($asal, ['created_at' => $now, 'updated_at' => $now]));
            $asalIds[] = $id;
        }

        // 6. Seed Jurusan
        $jurusans = [
            ['name' => 'Rekayasa Perangkat Lunak', 'description' => 'Jurusan RPL', 'is_active' => 1],
            ['name' => 'Teknik Komputer dan Jaringan', 'description' => 'Jurusan TKJ', 'is_active' => 1],
            ['name' => 'Multimedia', 'description' => 'Jurusan MM', 'is_active' => 1],
            ['name' => 'Sistem Informatika Jaringan dan Aplikasi', 'description' => 'Jurusan SIJA', 'is_active' => 1],
        ];

        $jurusanIds = [];
        foreach ($jurusans as $jurusan) {
            $id = DB::table('jurusans')->insertGetId(array_merge($jurusan, ['created_at' => $now, 'updated_at' => $now]));
            $jurusanIds[] = $id;
        }

        // 7. Seed Users (Guru dan Siswa)
        $userIds = [];
        $startUserCode = 3; // Karena USR-001 & USR-002 sudah dipakai admin
        
        $firstNames = ['Budi', 'Anton', 'Siska', 'Yanto', 'Ani', 'Joko', 'Siti', 'Dewi', 'Rudi', 'Andi', 'Rina', 'Nina', 'Eko', 'Agus', 'Dwi', 'Tri'];
        $lastNames = ['Santoso', 'Wijaya', 'Pratama', 'Kusuma', 'Sari', 'Lestari', 'Hidayat', 'Saputra', 'Setiawan', 'Nugroho'];

        // 10 Guru
        for ($i = 1; $i <= 10; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
            $userIds[] = DB::table('users')->insertGetId([
                'user_code' => 'USR-' . str_pad($startUserCode++, 3, '0', STR_PAD_LEFT),
                'name' => $name,
                'username' => 'guru' . $i,
                'email' => 'guru' . $i . '@noc.smkn4malang.sch.id',
                'password' => Hash::make('password123'),
                'role' => 'Guru',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 40 Siswa
        $kelas = ['X RPL 1', 'X RPL 2', 'XI TKJ 1', 'XI TKJ 2', 'XII MM 1', 'XII SIJA'];
        for ($i = 1; $i <= 40; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
            $userIds[] = DB::table('users')->insertGetId([
                'user_code' => 'USR-' . str_pad($startUserCode++, 3, '0', STR_PAD_LEFT),
                'name' => $name,
                'username' => 'siswa' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'email' => $kelas[array_rand($kelas)], // Email digunakan untuk menyimpan kelas
                'password' => Hash::make('password123'),
                'role' => 'Siswa',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 8. Seed Items — setiap unit = 1 row, qty=1, dengan sub_prefix
        //    Format kode: PREFIX-SUBPREFIX-NUMBER (misal AP-UNI-0001)
        //    Kelompok di tabel: name + sub_prefix + category + location + condition + status
        $itemDefs = [
            // [name, brand, model, categorySlug, sub_prefix, unitCount, locationIndex, condition, status, price]
            ['Access Point UniFi',   'Ubiquiti',  'UAP-AC-Pro',     'access-point',   'UNI', 5, 0, 'baik', 'tersedia', 2500000],
            ['Access Point UniFi',   'Ubiquiti',  'UAP-AC-Pro',     'access-point',   'UNI', 3, 1, 'baik', 'tersedia', 2500000],
            ['Access Point UniFi',   'Ubiquiti',  'UAP-AC-Lite',    'access-point',   'UNI', 2, 3, 'baik', 'dipinjam', 1800000],
            ['Access Point TP-Link', 'TP-Link',   'EAP225',         'access-point',   'TPL', 4, 0, 'baik', 'tersedia', 850000],
            ['Access Point TP-Link', 'TP-Link',   'EAP225',         'access-point',   'TPL', 2, 4, 'rusak_ringan', 'maintenance', 850000],
            ['Router MikroTik',      'MikroTik',  'RB750Gr3',       'router',         'MKT', 6, 0, 'baik', 'tersedia', 1200000],
            ['Router MikroTik',      'MikroTik',  'RB750Gr3',       'router',         'MKT', 3, 3, 'baik', 'dipinjam', 1200000],
            ['Router MikroTik',      'MikroTik',  'RB951Ui-2HnD',   'router',         'MKT', 2, 1, 'rusak_ringan', 'tersedia', 900000],
            ['Router Cisco',         'Cisco',     'ISR4321',        'router',         'CSC', 2, 0, 'baik', 'tersedia', 15000000],
            ['Router Cisco',         'Cisco',     'ISR4321',        'router',         'CSC', 1, 0, 'rusak_berat', 'maintenance', 15000000],
            ['Switch TP-Link',       'TP-Link',   'TL-SG1024D',     'switch-hub',     'TPL', 4, 0, 'baik', 'tersedia', 1500000],
            ['Switch TP-Link',       'TP-Link',   'TL-SG1024D',     'switch-hub',     'TPL', 3, 1, 'baik', 'tersedia', 1500000],
            ['Switch Cisco',         'Cisco',     'Catalyst 2960',  'switch-hub',     'CSC', 3, 0, 'baik', 'tersedia', 8000000],
            ['Switch Cisco',         'Cisco',     'Catalyst 2960',  'switch-hub',     'CSC', 2, 3, 'baik', 'dipinjam', 8000000],
            ['Switch D-Link',        'D-Link',    'DGS-1008A',      'switch-hub',     'DLK', 2, 4, 'baik', 'tersedia', 650000],
            ['Server Dell',          'Dell',      'PowerEdge R440', 'server',         'DEL', 2, 0, 'baik', 'tersedia', 45000000],
            ['Server Dell',          'Dell',      'PowerEdge T340', 'server',         'DEL', 1, 0, 'baik', 'tersedia', 35000000],
            ['PC Client',            'Rakitan',   'Core i5 Gen10',  'pc-client',      'I5',  8, 1, 'baik', 'tersedia', 7000000],
            ['PC Client',            'Rakitan',   'Core i5 Gen10',  'pc-client',      'I5',  5, 2, 'baik', 'tersedia', 7000000],
            ['PC Client',            'Rakitan',   'Core i7 Gen11',  'pc-client',      'I7',  4, 1, 'baik', 'tersedia', 12000000],
            ['PC Client',            'Rakitan',   'Core i7 Gen11',  'pc-client',      'I7',  2, 2, 'rusak_ringan', 'maintenance', 12000000],
            ['Laptop Lenovo',        'Lenovo',    'V14 G3',         'laptop',         'LNV', 3, 0, 'baik', 'tersedia', 8500000],
            ['Laptop Lenovo',        'Lenovo',    'V14 G3',         'laptop',         'LNV', 2, 6, 'baik', 'dipinjam', 8500000],
            ['Laptop ASUS',          'ASUS',      'ExpertBook B1',  'laptop',         'ASS', 2, 0, 'baik', 'tersedia', 9000000],
            ['Monitor Samsung',      'Samsung',   'LS24A350',       'monitor',        'SMS', 5, 1, 'baik', 'tersedia', 2200000],
            ['Monitor Samsung',      'Samsung',   'LS24A350',       'monitor',        'SMS', 3, 2, 'baik', 'tersedia', 2200000],
            ['Monitor LG',           'LG',        '22MP410',        'monitor',        'LG',  4, 1, 'baik', 'tersedia', 1800000],
            ['Kabel UTP Belden',     'Belden',    'Cat6 305m',      'kabel-jaringan', 'BLD', 3, 5, 'baik', 'tersedia', 1500000],
            ['Kabel UTP AMP',        'AMP',       'Cat5e 305m',     'kabel-jaringan', 'AMP', 2, 5, 'baik', 'tersedia', 800000],
            ['Konektor RJ45',        'AMP',       'Cat6',           'kabel-jaringan', 'RJ4', 10, 5, 'baik', 'tersedia', 150000],
            ['Tang Crimping',        'TRENDnet',  'TC-CT68',        'tools',          'CRP', 3, 0, 'baik', 'tersedia', 250000],
            ['LAN Tester',           'TRENDnet',  'TC-NT12',        'tools',          'TST', 2, 0, 'baik', 'tersedia', 350000],
            ['Proyektor Epson',      'Epson',     'EB-X51',         'tools',          'EPS', 2, 6, 'baik', 'tersedia', 6500000],
            ['Proyektor Epson',      'Epson',     'EB-X51',         'tools',          'EPS', 1, 0, 'rusak_ringan', 'maintenance', 6500000],
        ];

        $itemIds = [];
        $itemCodes = [];
        foreach ($itemDefs as $def) {
            [$name, $brand, $model, $catSlug, $subPrefix, $unitCount, $locIdx, $condition, $status, $price] = $def;

            $catRow = DB::table('categories')->where('slug', $catSlug)->first();
            if (!$catRow) continue;
            $catId = $catRow->id;
            $catPrefix = $catRow->prefix;
            $locId = $locationIds[$locIdx] ?? $locationIds[0];
            $purchaseDate = Carbon::now()->subDays(rand(30, 700))->format('Y-m-d');

            for ($u = 0; $u < $unitCount; $u++) {
                // Atomic: increment last_code_number on category
                $nextNum = DB::table('categories')->where('id', $catId)->value('last_code_number') + 1;
                DB::table('categories')->where('id', $catId)->update(['last_code_number' => $nextNum, 'updated_at' => $now]);

                $code = $catPrefix . '-' . $subPrefix . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

                $id = DB::table('items')->insertGetId([
                    'name'              => $name,
                    'code'              => $code,
                    'sub_prefix'        => $subPrefix,
                    'serial_number'     => strtoupper($subPrefix) . '-' . rand(10000, 99999),
                    'brand'             => $brand,
                    'model'             => $model,
                    'category_id'       => $catId,
                    'location_id'       => $locId,
                    'supplier_id'       => $supplierIds[array_rand($supplierIds)],
                    'asal_barang_id'    => $asalIds[array_rand($asalIds)],
                    'kondisi_barang_id' => $kondisiIds[array_rand($kondisiIds)],
                    'quantity'          => 1,
                    'condition'         => $condition,
                    'status'            => $status,
                    'purchase_date'     => $purchaseDate,
                    'purchase_price'    => $price,
                    'notes'             => 'Dummy data - ' . $brand . ' ' . $model,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
                $itemIds[] = $id;
                $itemCodes[$id] = $code;
            }
        }

        // 9. Seed Item Movements
        $movements = [];
        $types = ['masuk', 'keluar', 'pindah', 'maintenance', 'rusak', 'musnahkan'];

        for ($i = 0; $i < 100; $i++) {
            $movement_date = Carbon::now()->subDays(rand(1, 300))->format('Y-m-d');
            $movements[] = [
                'item_id' => $itemIds[array_rand($itemIds)],
                'user_id' => User::whereIn('role', ['Admin', 'Superadmin'])->inRandomOrder()->value('id') ?? 1,
                'type' => $types[array_rand($types)],
                'quantity' => rand(1, 10),
                'from_location_id' => $locationIds[array_rand($locationIds)],
                'to_location_id' => $locationIds[array_rand($locationIds)],
                'notes' => 'Mutasi barang dummy',
                'movement_date' => $movement_date,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($movements, 50) as $chunk) {
            DB::table('item_movements')->insert($chunk);
        }

        // 10. Seed Peminjaman
        $peminjamans = [];
        $statuses = ['dipinjam', 'dikembalikan'];

        $kondisiOptions = ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'];
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

        for ($i = 0; $i < 50; $i++) {
            $status = $statuses[array_rand($statuses)];
            $tglPinjam = Carbon::now()->subDays(rand(1, 60));
            $tglKembali = (clone $tglPinjam)->addDays(rand(1, 7));
            $itemId = $itemIds[array_rand($itemIds)];

            $kondisiKembali = null;
            $keteranganKembali = null;
            if ($status === 'dikembalikan') {
                // 70% baik, 15% rusak_ringan, 10% rusak_berat, 5% hilang
                $rand = rand(1, 100);
                if ($rand <= 70) $kondisiKembali = 'baik';
                elseif ($rand <= 85) $kondisiKembali = 'rusak_ringan';
                elseif ($rand <= 95) $kondisiKembali = 'rusak_berat';
                else $kondisiKembali = 'hilang';

                $keteranganKembali = $keteranganOptions[array_rand($keteranganOptions)];
            }

            $peminjamans[] = [
                'nama_peminjam'       => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'kelas'               => $kelas[array_rand($kelas)],
                'item_id'             => $itemId,
                'item_code'           => $itemCodes[$itemId],
                'session_token'       => 'MANUAL-' . Str::random(10),
                'waktu_pinjam'        => $tglPinjam,
                'waktu_kembali'       => $status == 'dikembalikan' ? $tglKembali : null,
                'status'              => $status,
                'kondisi_saat_kembali'=> $kondisiKembali,
                'keterangan_kembali'  => $keteranganKembali,
                'catatan'             => 'Catatan peminjaman dummy ' . rand(1, 100),
                'created_at'          => $tglPinjam,
                'updated_at'          => $status == 'dikembalikan' ? $tglKembali : $now,
            ];
        }

        foreach (array_chunk($peminjamans, 50) as $chunk) {
            DB::table('peminjaman')->insert($chunk);
        }
    }
}

