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
     * Bersihkan seluruh data lama dan ganti dengan data dummy ERP lengkap,
     * realistis, dan mencakup seluruh modul (Master Data, Aset, Mutasi,
     * Peminjaman, Perawatan, Stok Opname, Notifikasi, Audit Trail).
     */
    public function run(): void
    {
        $now = Carbon::now();

        // =========================================================================
        // 0. BERSIHKAN DATA LAMA SECARA BERSIH
        // =========================================================================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('activity_logs')->truncate();
        DB::table('notifications')->truncate();
        DB::table('stock_take_items')->truncate();
        DB::table('stock_takes')->truncate();
        DB::table('perawatans')->truncate();
        DB::table('peminjaman')->truncate();
        DB::table('item_movements')->truncate();
        DB::table('items')->truncate();
        DB::table('scan_sessions')->truncate();
        DB::table('users')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('asal_barangs')->truncate();
        DB::table('kondisi_barangs')->truncate();
        DB::table('jurusans')->truncate();
        DB::table('locations')->truncate();
        DB::table('categories')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =========================================================================
        // 1. MASTER DATA: Jurusan
        // =========================================================================
        $jurusanDefs = [
            ['kode_jurusan' => 'TKJ',  'name' => 'Teknik Komputer dan Jaringan',            'kepala_jurusan' => 'Ahmad Fauzi, S.Kom',         'description' => 'Konsentrasi Jaringan Komputer, Server, dan Fiber Optik', 'is_active' => 1],
            ['kode_jurusan' => 'RPL',  'name' => 'Rekayasa Perangkat Lunak',                'kepala_jurusan' => 'Sari Dewi, S.T',              'description' => 'Konsentrasi Pemrograman Web, Mobile, dan Database',       'is_active' => 1],
            ['kode_jurusan' => 'SIJA', 'name' => 'Sistem Informatika Jaringan dan Aplikasi','kepala_jurusan' => 'M. Rizky Pratama, M.Kom',     'description' => 'Konsentrasi Cloud Computing, IoT, dan Cyber Security',    'is_active' => 1],
            ['kode_jurusan' => 'DKV',  'name' => 'Desain Komunikasi Visual',                'kepala_jurusan' => 'Rina Kusuma, S.Ds',           'description' => 'Konsentrasi Grafis, Multimedia, dan UI/UX',               'is_active' => 1],
            ['kode_jurusan' => 'MM',   'name' => 'Multimedia',                              'kepala_jurusan' => 'Budi Santoso, S.Pd',          'description' => 'Konsentrasi Audio Visual, Animasi, dan Broadcasting',     'is_active' => 1],
        ];

        $jurusanIds = [];
        foreach ($jurusanDefs as $j) {
            $id = DB::table('jurusans')->insertGetId(array_merge($j, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
            $jurusanIds[$j['kode_jurusan']] = $id;
        }

        // =========================================================================
        // 2. USERS (Superadmin, Admin, Guru/Jurusan, Siswa)
        // =========================================================================
        // A. Superadmin
        $superadminId = DB::table('users')->insertGetId([
            'user_code'   => 'USR-001',
            'name'        => 'M. Rizky Pratama, S.Kom (Kepala Lab NOC)',
            'username'    => 'superadmin',
            'email'       => 'superadmin@noc.smkn4malang.sch.id',
            'password'    => Hash::make('Superadmin2026'),
            'role'        => 'Superadmin',
            'is_active'   => 1,
            'jurusan_id'  => $jurusanIds['TKJ'],
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // B. Admin NOC
        $adminId = DB::table('users')->insertGetId([
            'user_code'   => 'USR-002',
            'name'        => 'Fajar Wicaksono (Teknisi NOC)',
            'username'    => 'admin',
            'email'       => 'admin@noc.smkn4malang.sch.id',
            'password'    => Hash::make('Admin2026'),
            'role'        => 'Admin',
            'is_active'   => 1,
            'jurusan_id'  => $jurusanIds['TKJ'],
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // C. Akun Jurusan / Guru
        $jurusanAccounts = [
            ['code' => 'USR-003', 'name' => 'Drs. Bambang Sudarsono, M.T', 'user' => 'tkj',  'email' => 'guru.tkj@noc.smkn4malang.sch.id',  'jur' => 'TKJ'],
            ['code' => 'USR-004', 'name' => 'Siti Rahmawati, S.Pd',         'user' => 'rpl',  'email' => 'guru.rpl@noc.smkn4malang.sch.id',  'jur' => 'RPL'],
            ['code' => 'USR-005', 'name' => 'Ahmad Fauzi, S.Kom',           'user' => 'sija', 'email' => 'guru.sija@noc.smkn4malang.sch.id', 'jur' => 'SIJA'],
            ['code' => 'USR-006', 'name' => 'Rina Kusuma, S.Ds',            'user' => 'dkv',  'email' => 'guru.dkv@noc.smkn4malang.sch.id',  'jur' => 'DKV'],
            ['code' => 'USR-007', 'name' => 'Budi Santoso, S.Pd',           'user' => 'mm',   'email' => 'guru.mm@noc.smkn4malang.sch.id',   'jur' => 'MM'],
        ];

        $jurusanUserIds = [];
        foreach ($jurusanAccounts as $acc) {
            $id = DB::table('users')->insertGetId([
                'user_code'  => $acc['code'],
                'name'       => $acc['name'],
                'username'   => $acc['user'],
                'email'      => $acc['email'],
                'password'   => Hash::make('jurusan123'),
                'role'       => 'Jurusan',
                'is_active'  => 1,
                'jurusan_id' => $jurusanIds[$acc['jur']],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $jurusanUserIds[$acc['user']] = $id;
        }

        // =========================================================================
        // 3. MASTER DATA: Kategori Barang (Lengkap dengan prefix)
        // =========================================================================
        $categories = [
            ['name' => 'Switch / Hub',          'slug' => 'switch-hub',          'description' => 'Perangkat Switch Managed, Unmanaged, dan PoE', 'prefix' => 'SWT', 'last_code_number' => 0],
            ['name' => 'Router Gateway',         'slug' => 'router',              'description' => 'Router MikroTik, Cisco, dan EdgeRouter',       'prefix' => 'RTR', 'last_code_number' => 0],
            ['name' => 'Wireless Access Point',  'slug' => 'access-point',        'description' => 'AP Indoor & Outdoor Ubiquiti, TP-Link, Aruba', 'prefix' => 'AP',  'last_code_number' => 0],
            ['name' => 'Komputer Server',        'slug' => 'server',              'description' => 'Rackmount & Tower Server Dell, HP, Lenovo',    'prefix' => 'SRV', 'last_code_number' => 0],
            ['name' => 'Kabel & Fiber Optik',    'slug' => 'kabel-jaringan',      'description' => 'Kabel UTP Cat6, Patch Cord, Fiber Optic, Dropcore', 'prefix' => 'CBL', 'last_code_number' => 0],
            ['name' => 'PC Client Laboratorium', 'slug' => 'pc-client',           'description' => 'PC Desktop untuk Praktik Siswa dan Lab',       'prefix' => 'PC',  'last_code_number' => 0],
            ['name' => 'Laptop Inventaris',      'slug' => 'laptop',              'description' => 'Laptop Operasional NOC dan Peminjaman Pengajar', 'prefix' => 'LPT', 'last_code_number' => 0],
            ['name' => 'Monitor Display',        'slug' => 'monitor',             'description' => 'Layar Monitor LED / IPS untuk Lab dan NOC',     'prefix' => 'MNT', 'last_code_number' => 0],
            ['name' => 'Tools & Alat Ukur',      'slug' => 'tools',               'description' => 'Fusion Splicer, OTDR, Cable Tester, Crimping Tool', 'prefix' => 'TLS', 'last_code_number' => 0],
            ['name' => 'Power & UPS Backup',     'slug' => 'ups-power',           'description' => 'Uninterruptible Power Supply (UPS) & PDU Rack', 'prefix' => 'UPS', 'last_code_number' => 0],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $id = DB::table('categories')->insertGetId(array_merge($cat, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
            $catIds[$cat['slug']] = $id;
        }

        // =========================================================================
        // 4. MASTER DATA: Lokasi / Ruangan
        // =========================================================================
        $locations = [
            ['code' => 'LOC-001', 'name' => 'Ruang Server NOC Utama',          'penanggung_jawab' => 'M. Rizky Pratama (Kepala NOC)', 'description' => 'Pusat data server, router border, dan core switch sekolah'],
            ['code' => 'LOC-002', 'name' => 'Lab Jaringan & Fiber Optik',       'penanggung_jawab' => 'Ahmad Fauzi, S.Kom',           'description' => 'Laboratorium praktik routing, switching, dan splicer fiber optik'],
            ['code' => 'LOC-003', 'name' => 'Lab Komputer TKJ 1',               'penanggung_jawab' => 'Drs. Bambang Sudarsono',       'description' => 'Lab komputer praktik jaringan dasar dan administrasi server'],
            ['code' => 'LOC-004', 'name' => 'Lab Komputer TKJ 2',               'penanggung_jawab' => 'Fajar Wicaksono',              'description' => 'Lab simulasi jaringan Packet Tracer dan MikroTik Academy'],
            ['code' => 'LOC-005', 'name' => 'Lab Software Rekayasa Perangkat Lunak', 'penanggung_jawab' => 'Siti Rahmawati, S.Pd',      'description' => 'Lab pengembangan aplikasi desktop, mobile, dan web'],
            ['code' => 'LOC-006', 'name' => 'Gudang Aset & Peralatan NOC',      'penanggung_jawab' => 'Fajar Wicaksono',              'description' => 'Penyimpanan cadangan perangkat, kabel roll, dan suku cadang'],
            ['code' => 'LOC-007', 'name' => 'Ruang Guru Produktif TKI',         'penanggung_jawab' => 'Kepala Program TKI',           'description' => 'Ruang kerja pengajar produktif TKJ, RPL, dan SIJA'],
        ];

        $locIds = [];
        foreach ($locations as $loc) {
            $id = DB::table('locations')->insertGetId(array_merge($loc, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
            $locIds[] = $id;
        }

        // =========================================================================
        // 5. MASTER DATA: Supplier
        // =========================================================================
        $suppliers = [
            ['name' => 'PT. MikroTik Cipta Solusi Indonesia', 'pic' => 'Hendro Prasetyo',  'phone' => '081234567890', 'email' => 'sales@mikrotik.co.id',        'address' => 'Gedung Cyber 2 Lt. 15, Kuningan, Jakarta', 'is_active' => 1],
            ['name' => 'PT. Cisco Systems Indonesia',         'pic' => 'Bambang Wijaya',   'phone' => '082199887766', 'email' => 'partner@cisco.com',           'address' => 'World Trade Center 2, Sudirman, Jakarta',   'is_active' => 1],
            ['name' => 'CV. Sentra Jaringan Mandiri Malang',  'pic' => 'Agus Budiman',     'phone' => '083812345678', 'email' => 'kontak@sentrajaringan.id',    'address' => 'Jl. Soekarno Hatta No. 45, Lowokwaru, Malang', 'is_active' => 1],
            ['name' => 'PT. Telkom Akses Regional V Jatim',   'pic' => 'Dwi Cahyono',      'phone' => '081357924680', 'email' => 'mitra.telkomakses@telkom.co.id','address' => 'Jl. Ketintang No. 156, Gayungan, Surabaya', 'is_active' => 1],
            ['name' => 'PT. Bhinneka Mentari Dimensi',        'pic' => 'Siti Nurhaliza',   'phone' => '082244668800', 'email' => 'b2b.gov@bhinneka.com',        'address' => 'Jl. Gunung Sahari Raya 73C, Jakarta Pusat', 'is_active' => 1],
        ];

        $supIds = [];
        foreach ($suppliers as $sup) {
            $supIds[] = DB::table('suppliers')->insertGetId(array_merge($sup, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
        }

        // =========================================================================
        // 6. MASTER DATA: Kondisi Barang
        // =========================================================================
        $kondisis = [
            'baik'         => ['name' => 'Baik',         'label_color' => 'green',  'description' => 'Fungsi 100% normal dan siap digunakan'],
            'rusak_ringan' => ['name' => 'Rusak Ringan', 'label_color' => 'yellow', 'description' => 'Fungsi terganggu sebagian, masih dapat diperbaiki'],
            'rusak_berat'  => ['name' => 'Rusak Berat',  'label_color' => 'red',    'description' => 'Kerusakan fisik/elektronik parah, perlu perbaikan besar / kanibal'],
            'hilang'       => ['name' => 'Hilang',       'label_color' => 'gray',   'description' => 'Aset tidak ditemukan pada tempat semestinya'],
        ];

        $kondisiIds = [];
        foreach ($kondisis as $key => $k) {
            $kondisiIds[$key] = DB::table('kondisi_barangs')->insertGetId(array_merge($k, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
        }

        // =========================================================================
        // 7. MASTER DATA: Asal Barang
        // =========================================================================
        $asals = [
            ['name' => 'Dana BOS Reguler 2024',         'description' => 'Pengadaan rutin anggaran operasional sekolah tahun 2024', 'is_active' => 1],
            ['name' => 'Bantuan DAK Fisik SMK 2025',     'description' => 'Program revitalisasi laboratorium kejuruan SMK Kemendikbudristek', 'is_active' => 1],
            ['name' => 'Hibah Industri PT. Telkom Akses','description' => 'Bantuan perangkat fiber optik & CSR kemitraan industri', 'is_active' => 1],
            ['name' => 'Komite Sekolah',                'description' => 'Partisipasi orang tua siswa untuk penunjang sertifikasi kompetensi', 'is_active' => 1],
        ];

        $asalIds = [];
        foreach ($asals as $a) {
            $asalIds[] = DB::table('asal_barangs')->insertGetId(array_merge($a, [
                'created_at' => $now, 'updated_at' => $now,
            ]));
        }

        // =========================================================================
        // 8. DATA BARANG (ITEMS) REALISTIS ERP NOC
        // =========================================================================
        $itemCatalog = [
            // [name, brand, model, catSlug, subPrefix, unitCount, locIdx, condition, status, price]
            // --- Router Gateway ---
            ['Router MikroTik Cloud Core', 'MikroTik',  'CCR1036-8G-2S+',   'router',         'CCR', 2, 0, 'baik',         'tersedia',    18500000],
            ['Router MikroTik Cloud Core', 'MikroTik',  'CCR2004-16G-2S+',  'router',         'CCR', 1, 0, 'baik',         'tersedia',    14000000],
            ['Router MikroTik RB4011',     'MikroTik',  'RB4011iGS+RM',     'router',         'MKT', 3, 1, 'baik',         'tersedia',     4200000],
            ['Router MikroTik RB4011',     'MikroTik',  'RB4011iGS+RM',     'router',         'MKT', 1, 1, 'baik',         'dipinjam',     4200000],
            ['Router MikroTik hEX S',       'MikroTik',  'RB760iGS',         'router',         'HEX', 6, 1, 'baik',         'tersedia',     1350000],
            ['Router MikroTik hEX S',       'MikroTik',  'RB760iGS',         'router',         'HEX', 2, 2, 'baik',         'dipinjam',     1350000],
            ['Router Cisco ISR',           'Cisco',     'ISR 4331/K9',      'router',         'CSC', 1, 0, 'baik',         'tersedia',    32000000],
            ['Router Cisco ISR',           'Cisco',     'ISR 4321/K9',      'router',         'CSC', 1, 1, 'rusak_ringan', 'maintenance', 22000000],

            // --- Switch / Hub ---
            ['Core Switch Cisco Catalyst', 'Cisco',     'Catalyst 9200-24P','switch-hub',     'CSC', 2, 0, 'baik',         'tersedia',    28000000],
            ['Distribution Switch Cisco',  'Cisco',     'Catalyst 2960X-48', 'switch-hub',     'CSC', 2, 0, 'baik',         'tersedia',    16500000],
            ['Distribution Switch Cisco',  'Cisco',     'Catalyst 2960X-24', 'switch-hub',     'CSC', 1, 1, 'rusak_ringan', 'maintenance', 12500000],
            ['Switch MikroTik Cloud Router','MikroTik', 'CRS326-24G-2S+RM', 'switch-hub',     'CRS', 4, 1, 'baik',         'tersedia',     4500000],
            ['Switch MikroTik Cloud Router','MikroTik', 'CRS326-24G-2S+RM', 'switch-hub',     'CRS', 2, 2, 'baik',         'dipinjam',     4500000],
            ['Switch TP-Link PoE Managed', 'TP-Link',   'TL-SG3428MP',      'switch-hub',     'TPL', 3, 1, 'baik',         'tersedia',     5200000],
            ['Switch D-Link Gigabit',      'D-Link',    'DGS-1210-28P',     'switch-hub',     'DLK', 2, 3, 'baik',         'tersedia',     3800000],
            ['Switch D-Link 8-Port Desktop','D-Link',   'DGS-1008A',        'switch-hub',     'DLK', 4, 5, 'baik',         'tersedia',      320000],

            // --- Wireless Access Point ---
            ['Access Point Ubiquiti UniFi','Ubiquiti',  'U6-Pro WiFi 6',    'access-point',   'UNI', 4, 0, 'baik',         'tersedia',     3100000],
            ['Access Point Ubiquiti UniFi','Ubiquiti',  'UAP-AC-LR',        'access-point',   'UNI', 5, 2, 'baik',         'tersedia',     1950000],
            ['Access Point Ubiquiti UniFi','Ubiquiti',  'UAP-AC-LR',        'access-point',   'UNI', 2, 3, 'baik',         'dipinjam',     1950000],
            ['Access Point TP-Link Omada', 'TP-Link',   'EAP610 WiFi 6',    'access-point',   'TPL', 3, 1, 'baik',         'tersedia',     1650000],
            ['Access Point TP-Link Outdoor','TP-Link',  'EAP225-Outdoor',   'access-point',   'TPL', 2, 0, 'baik',         'tersedia',     1250000],
            ['Access Point Mikrotik cAP',  'MikroTik',  'cAP ac (RBcAPGi)', 'access-point',   'CAP', 3, 5, 'rusak_ringan', 'tersedia',     1100000],

            // --- Komputer Server ---
            ['Server Rackmount Dell PowerEdge', 'Dell',  'PowerEdge R740 2U','server',         'DEL', 2, 0, 'baik',         'tersedia',    68000000],
            ['Server Rackmount Dell PowerEdge', 'Dell',  'PowerEdge R440 1U','server',         'DEL', 1, 0, 'baik',         'tersedia',    42000000],
            ['Server Tower HP ProLiant',   'HP',        'ProLiant ML350 G10','server',        'HP',  1, 0, 'baik',         'tersedia',    36000000],
            ['Storage NAS Synology Rack',  'Synology',  'RackStation RS2423+','server',       'SYN', 1, 0, 'baik',         'tersedia',    29500000],

            // --- PC Client & Workstation ---
            ['PC Workstation Core i7 Gen12','Rakitan NOC','Core i7-12700 32GB','pc-client',    'I7',  8, 1, 'baik',         'tersedia',    13500000],
            ['PC Client Lab TKJ Core i5',  'Rakitan NOC','Core i5-11400 16GB','pc-client',    'I5', 10, 2, 'baik',         'tersedia',     8200000],
            ['PC Client Lab TKJ Core i5',  'Rakitan NOC','Core i5-11400 16GB','pc-client',    'I5',  2, 2, 'rusak_ringan', 'maintenance',  8200000],
            ['PC Client Lab RPL Core i5',  'Rakitan NOC','Core i5-10400 16GB','pc-client',    'I5', 10, 4, 'baik',         'tersedia',     7500000],

            // --- Laptop Inventaris ---
            ['Laptop Lenovo ThinkPad',     'Lenovo',    'ThinkPad L14 Gen3','laptop',         'LNV', 3, 0, 'baik',         'tersedia',    12800000],
            ['Laptop Lenovo ThinkPad',     'Lenovo',    'ThinkPad L14 Gen3','laptop',         'LNV', 2, 6, 'baik',         'dipinjam',    12800000],
            ['Laptop ASUS ExpertBook',     'ASUS',      'ExpertBook B1400', 'laptop',         'ASS', 2, 0, 'baik',         'tersedia',     9800000],

            // --- Monitor Display ---
            ['Monitor LED Dell UltraSharp','Dell',      'U2422H 24 Inch IPS','monitor',       'DEL', 4, 0, 'baik',         'tersedia',     4100000],
            ['Monitor LED LG Full HD',     'LG',        '24MP400 24 Inch',  'monitor',        'LG',  8, 1, 'baik',         'tersedia',     1850000],
            ['Monitor LED Samsung IPS',    'Samsung',   'LF24T350 24 Inch', 'monitor',        'SMS', 8, 2, 'baik',         'tersedia',     1750000],

            // --- Tools & Alat Ukur Fiber Optik ---
            ['Fusion Splicer Fiber Optik', 'Fujikura',  '90S+ Core Alignment','tools',        'FUS', 1, 1, 'baik',         'tersedia',    78000000],
            ['Fusion Splicer Fiber Optik', 'Ilsintech', 'Swift K11',        'tools',          'FUS', 1, 1, 'baik',         'dipinjam',    48000000],
            ['OTDR Optical Time Reflectometer','Yokogawa','AQ1210A',        'tools',          'OTD', 1, 1, 'baik',         'tersedia',    35000000],
            ['Optical Power Meter & Light Source','Grandway','FHP2P01',      'tools',          'OPM', 2, 1, 'baik',         'tersedia',     3200000],
            ['Optical Power Meter & Light Source','Grandway','FHP2P01',      'tools',          'OPM', 1, 1, 'baik',         'dipinjam',     3200000],
            ['Tang Crimping RJ45 & RJ11',  'Proskit',   'CP-376TR',         'tools',          'TLS', 6, 1, 'baik',         'tersedia',      380000],
            ['Tang Crimping RJ45 & RJ11',  'Proskit',   'CP-376TR',         'tools',          'TLS', 2, 1, 'baik',         'dipinjam',      380000],
            ['LAN Cable Tester Digital',   'Noyafa',    'NF-8209 Pro',      'tools',          'TLS', 3, 1, 'baik',         'tersedia',      550000],
            ['Visual Fault Locator (Laser)','Joinwit',  'JW3105P 10mW',     'tools',          'TLS', 4, 1, 'baik',         'tersedia',      180000],

            // --- Kabel Jaringan & Roll ---
            ['Kabel UTP Roll Cat6 Belden', 'Belden',    '7814A Cat6 305m',  'kabel-jaringan', 'BLD', 3, 5, 'baik',         'tersedia',     1950000],
            ['Kabel UTP Roll Cat6 Schneider','Schneider','Actassi Cat6 305m','kabel-jaringan', 'SCH', 2, 5, 'baik',         'tersedia',     1750000],
            ['Kabel Fiber Optik Dropcore 1 Core','ZTE', 'G657A 1000 Meter', 'kabel-jaringan', 'FBO', 2, 5, 'baik',         'tersedia',      750000],

            // --- Power & UPS ---
            ['UPS Online Rackmount APC',   'APC',       'Smart-UPS RT 3000VA','ups-power',    'APC', 2, 0, 'baik',         'tersedia',    24000000],
            ['UPS Tower ICA Sinewave',     'ICA',       'CN1300 1300VA',    'ups-power',      'ICA', 2, 0, 'baik',         'tersedia',     3800000],
        ];

        $itemIds = [];
        $itemObjects = [];
        $dipinjamItems = [];
        $maintenanceItems = [];

        foreach ($itemCatalog as $def) {
            [$name, $brand, $model, $catSlug, $subPrefix, $unitCount, $locIdx, $condition, $status, $price] = $def;

            $catId = $catIds[$catSlug] ?? null;
            if (!$catId) continue;

            $catPrefix = DB::table('categories')->where('id', $catId)->value('prefix');
            $locId = $locIds[$locIdx] ?? $locIds[0];
            $condId = $kondisiIds[$condition] ?? $kondisiIds['baik'];

            for ($u = 0; $u < $unitCount; $u++) {
                $nextNum = DB::table('categories')->where('id', $catId)->value('last_code_number') + 1;
                DB::table('categories')->where('id', $catId)->update([
                    'last_code_number' => $nextNum,
                    'updated_at'       => $now,
                ]);

                $code = $catPrefix . '-' . strtoupper($subPrefix) . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
                $purchaseDate = Carbon::now()->subDays(rand(40, 650))->format('Y-m-d');
                $sn = strtoupper($subPrefix) . '-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);

                $itemId = DB::table('items')->insertGetId([
                    'name'              => $name,
                    'code'              => $code,
                    'sub_prefix'        => strtoupper($subPrefix),
                    'serial_number'     => $sn,
                    'brand'             => $brand,
                    'model'             => $model,
                    'category_id'       => $catId,
                    'location_id'       => $locId,
                    'supplier_id'       => $supIds[array_rand($supIds)],
                    'asal_barang_id'    => $asalIds[array_rand($asalIds)],
                    'kondisi_barang_id' => $condId,
                    'quantity'          => 1,
                    'condition'         => $condition,
                    'status'            => $status,
                    'purchase_date'     => $purchaseDate,
                    'purchase_price'    => $price,
                    'notes'             => "Perangkat spesifikasi {$brand} {$model}. Terverifikasi NOC SMKN 4 Malang.",
                    'created_at'        => Carbon::parse($purchaseDate),
                    'updated_at'        => $now,
                ]);

                $itemIds[] = $itemId;
                $itemObj = (object)[
                    'id'          => $itemId,
                    'name'        => $name,
                    'code'        => $code,
                    'location_id' => $locId,
                    'condition'   => $condition,
                    'status'      => $status,
                    'brand'       => $brand,
                    'model'       => $model,
                ];
                $itemObjects[] = $itemObj;

                if ($status === 'dipinjam') {
                    $dipinjamItems[] = $itemObj;
                } elseif ($status === 'maintenance') {
                    $maintenanceItems[] = $itemObj;
                }
            }
        }

        // =========================================================================
        // 9. RIWAYAT MUTASI BARANG (ITEM MOVEMENTS) - TREN SEPANJANG TAHUN
        // =========================================================================
        $movements = [];
        $currentYear = now()->year;

        // Distribusikan barang masuk per bulan di tahun ini agar chart dashboard hidup
        for ($month = 1; $month <= 12; $month++) {
            $monthIncomingCount = rand(4, 10);
            for ($k = 0; $k < $monthIncomingCount; $k++) {
                $randDay = rand(1, 28);
                $mDate = Carbon::create($currentYear, $month, $randDay, rand(8, 16), rand(10, 50));
                if ($mDate->isFuture()) continue;

                $randomItem = $itemObjects[array_rand($itemObjects)];
                $movements[] = [
                    'item_id'          => $randomItem->id,
                    'user_id'          => (rand(1, 10) > 4) ? $adminId : $superadminId,
                    'type'             => 'masuk',
                    'quantity'         => 1,
                    'from_location_id' => null,
                    'to_location_id'   => $randomItem->location_id,
                    'notes'            => "Penerimaan pengadaan aset baru ({$randomItem->name}) ke {$randomItem->code}",
                    'movement_date'    => $mDate->format('Y-m-d'),
                    'created_at'       => $mDate,
                    'updated_at'       => $mDate,
                ];
            }
        }

        // Tambah mutasi lain: pindah lokasi, maintenance, keluar
        for ($i = 0; $i < 40; $i++) {
            $randomItem = $itemObjects[array_rand($itemObjects)];
            $mType = ['pindah', 'maintenance', 'keluar', 'pindah'][array_rand(['pindah', 'maintenance', 'keluar', 'pindah'])];
            $mDaysAgo = rand(5, 200);
            $mDate = Carbon::now()->subDays($mDaysAgo);

            $fromLoc = $locIds[array_rand($locIds)];
            $toLoc = ($mType === 'pindah') ? $locIds[array_rand($locIds)] : null;

            $movements[] = [
                'item_id'          => $randomItem->id,
                'user_id'          => $adminId,
                'type'             => $mType,
                'quantity'         => 1,
                'from_location_id' => $fromLoc,
                'to_location_id'   => $toLoc,
                'notes'            => "Mutasi {$mType} perangkat {$randomItem->name} ({$randomItem->code})",
                'movement_date'    => $mDate->format('Y-m-d'),
                'created_at'       => $mDate,
                'updated_at'       => $mDate,
            ];
        }

        foreach (array_chunk($movements, 50) as $chunk) {
            DB::table('item_movements')->insert($chunk);
        }

        // =========================================================================
        // 10. PEMINJAMAN ASET (AKTIF & RIWAYAT PENGEMBALIAN)
        // =========================================================================
        $peminjamans = [];
        $borrowerPool = [
            ['nama' => 'Muhammad Ilham Pratama', 'kelas' => 'XII TKJ 1'],
            ['nama' => 'Aditya Bagus Saputra',   'kelas' => 'XII TKJ 2'],
            ['nama' => 'Nabila Putri Cahyani',    'kelas' => 'XI TKJ 1'],
            ['nama' => 'Dimas Arya Yudha',       'kelas' => 'XI TKJ 2'],
            ['nama' => 'Farhan Fathurrahman',    'kelas' => 'XII SIJA'],
            ['nama' => 'Kevin Christian',        'kelas' => 'XI RPL 1'],
            ['nama' => 'Rahmat Hidayatullah',    'kelas' => 'XII TKJ 1'],
            ['nama' => 'Siti Nur Aisyah',        'kelas' => 'XI TKJ 2'],
            ['nama' => 'Drs. Bambang Sudarsono', 'kelas' => 'Guru TKJ'],
            ['nama' => 'Ahmad Fauzi, S.Kom',     'kelas' => 'Guru SIJA'],
        ];

        // A. Peminjaman AKTIF (sesuai item status='dipinjam')
        foreach ($dipinjamItems as $item) {
            $borrower = $borrowerPool[array_rand($borrowerPool)];
            $loanDaysAgo = rand(1, 10);
            $waktuPinjam = Carbon::now()->subDays($loanDaysAgo)->subHours(rand(1, 6));

            $peminjamans[] = [
                'nama_peminjam'        => $borrower['nama'],
                'kelas'                => $borrower['kelas'],
                'item_id'              => $item->id,
                'item_code'            => $item->code,
                'session_token'        => 'NOC-PINJAM-' . strtoupper(Str::random(8)),
                'waktu_pinjam'         => $waktuPinjam,
                'waktu_kembali'        => null,
                'status'               => 'dipinjam',
                'kondisi_saat_kembali' => null,
                'keterangan_kembali'   => null,
                'foto_kembali'         => null,
                'catatan'              => 'Dipinjam untuk keperluan praktikum jaringan & sertifikasi kompetensi',
                'created_at'           => $waktuPinjam,
                'updated_at'           => $waktuPinjam,
            ];
        }

        // B. Riwayat Peminjaman Lampau (DIKEMBALIKAN)
        $historicalCandidates = array_values(array_filter($itemObjects, fn($i) => $i->status !== 'dipinjam'));
        for ($j = 0; $j < 30; $j++) {
            $item = $historicalCandidates[array_rand($historicalCandidates)];
            $borrower = $borrowerPool[array_rand($borrowerPool)];
            $loanDaysAgo = rand(15, 120);
            $waktuPinjam = Carbon::now()->subDays($loanDaysAgo);
            $durasiHari = rand(1, 5);
            $waktuKembali = (clone $waktuPinjam)->addDays($durasiHari)->addHours(rand(1, 4));

            $peminjamans[] = [
                'nama_peminjam'        => $borrower['nama'],
                'kelas'                => $borrower['kelas'],
                'item_id'              => $item->id,
                'item_code'            => $item->code,
                'session_token'        => 'HIST-' . strtoupper(Str::random(8)),
                'waktu_pinjam'         => $waktuPinjam,
                'waktu_kembali'        => $waktuKembali,
                'status'               => 'dikembalikan',
                'kondisi_saat_kembali' => 'baik',
                'keterangan_kembali'   => 'Barang telah dikembalikan lengkap dengan adaptor & kabel, fungsi normal.',
                'foto_kembali'         => null,
                'catatan'              => 'Pengembalian tepat waktu telah diverifikasi teknisi NOC',
                'created_at'           => $waktuPinjam,
                'updated_at'           => $waktuKembali,
            ];
        }

        foreach (array_chunk($peminjamans, 50) as $chunk) {
            DB::table('peminjaman')->insert($chunk);
        }

        // =========================================================================
        // 11. PERAWATAN & MAINTENANCE ASET
        // =========================================================================
        $perawatans = [];
        // Maintenance untuk item yang berstatus 'maintenance'
        foreach ($maintenanceItems as $mItem) {
            $perawatans[] = [
                'item_id'           => $mItem->id,
                'user_id'           => $adminId,
                'jenis_perawatan'   => 'Corrective Maintenance',
                'tanggal_pengajuan' => Carbon::now()->subDays(rand(2, 7))->format('Y-m-d'),
                'tanggal_selesai'   => null,
                'status'            => 'proses',
                'catatan'           => "Pengecekan kendala operasional pada {$mItem->name} ({$mItem->code}). Menunggu penggantian suku cadang.",
                'token_link'        => 'MAINT-' . Str::random(12),
                'teknisi_nama'      => 'Fajar Wicaksono (Teknisi NOC)',
                'biaya'             => 450000,
                'foto_bukti'        => null,
                'created_at'        => Carbon::now()->subDays(rand(2, 7)),
                'updated_at'        => $now,
            ];
        }

        // Riwayat perawatan SELESAI
        $finishedCandidates = array_values(array_filter($itemObjects, fn($i) => $i->status === 'tersedia'));
        for ($p = 0; $p < 8; $p++) {
            $fItem = $finishedCandidates[array_rand($finishedCandidates)];
            $tglAjukan = Carbon::now()->subDays(rand(20, 90));
            $tglSelesai = (clone $tglAjukan)->addDays(rand(2, 5));

            $perawatans[] = [
                'item_id'           => $fItem->id,
                'user_id'           => $adminId,
                'jenis_perawatan'   => 'Preventive Maintenance',
                'tanggal_pengajuan' => $tglAjukan->format('Y-m-d'),
                'tanggal_selesai'   => $tglSelesai->format('Y-m-d'),
                'status'            => 'selesai',
                'catatan'           => "Pembersihan internal modul fan, penggantian pasta thermal, dan update firmware versi stabil.",
                'token_link'        => 'MAINT-' . Str::random(12),
                'teknisi_nama'      => 'Fajar Wicaksono (Teknisi NOC)',
                'biaya'             => 150000,
                'foto_bukti'        => null,
                'created_at'        => $tglAjukan,
                'updated_at'        => $tglSelesai,
            ];
        }

        DB::table('perawatans')->insert($perawatans);

        // =========================================================================
        // 12. STOK OPNAME FISIK (STOCK TAKE)
        //     - Sesi 1: Approved (Sesi Akhir Tahun 2025)
        //     - Sesi 2: In Progress (Sesi Semester Genap 2026 Lab Jaringan)
        // =========================================================================
        // Sesi 1: Approved
        $so1Id = DB::table('stock_takes')->insertGetId([
            'code'          => 'SO-2025-001',
            'title'         => 'Stok Opname Aset Tahunan NOC SMKN 4 Malang 2025',
            'description'   => 'Rekonsiliasi inventaris seluruh ruangan laboratorium dan ruang server sebelum tutup buku anggaran.',
            'location_id'   => null, // Semua lokasi
            'started_by'    => $adminId,
            'status'        => 'approved',
            'started_at'    => Carbon::create(2025, 12, 15, 8, 0),
            'completed_at'  => Carbon::create(2025, 12, 18, 16, 0),
            'approved_by'   => $superadminId,
            'approved_at'   => Carbon::create(2025, 12, 19, 10, 30),
            'notes_summary' => 'Seluruh aset fisik di 7 ruangan telah diperiksa 100%. Ditemukan 1 port switch rusak dan telah dijadwalkan perawatan.',
            'created_at'    => Carbon::create(2025, 12, 15, 8, 0),
            'updated_at'    => Carbon::create(2025, 12, 19, 10, 30),
        ]);

        // Detail Sesi 1 (ambil 20 item sample)
        $so1Sample = array_slice($itemObjects, 0, 20);
        foreach ($so1Sample as $sample) {
            DB::table('stock_take_items')->insert([
                'stock_take_id'    => $so1Id,
                'item_id'          => $sample->id,
                'system_quantity'  => 1,
                'system_condition' => $sample->condition,
                'actual_quantity'  => 1,
                'actual_condition' => $sample->condition,
                'difference'       => 0,
                'notes'            => 'Barang fisik sesuai dan terverifikasi label barcode.',
                'checked_by'       => $adminId,
                'checked_at'       => Carbon::create(2025, 12, 16, 14, 0),
                'created_at'       => Carbon::create(2025, 12, 15, 8, 0),
                'updated_at'       => Carbon::create(2025, 12, 16, 14, 0),
            ]);
        }

        // Sesi 2: IN PROGRESS (Lab Jaringan & Fiber Optik) - Muncul di Dashboard Banner!
        $labJaringanId = $locIds[1]; // LOC-002
        $so2Id = DB::table('stock_takes')->insertGetId([
            'code'          => 'SO-2026-001',
            'title'         => 'Stok Opname Triwulan I 2026 - Lab Jaringan & Fiber Optik',
            'description'   => 'Pengecekan fisik berkala router praktik, switch manageable, fusion splicer, dan kabel fiber optik.',
            'location_id'   => $labJaringanId,
            'started_by'    => $adminId,
            'status'        => 'in_progress',
            'started_at'    => Carbon::now()->subDays(1),
            'completed_at'  => null,
            'approved_by'   => null,
            'approved_at'   => null,
            'notes_summary' => null,
            'created_at'    => Carbon::now()->subDays(1),
            'updated_at'    => $now,
        ]);

        // Populate item di Lab Jaringan
        $labJaringanItems = array_values(array_filter($itemObjects, fn($i) => $i->location_id === $labJaringanId));
        foreach ($labJaringanItems as $index => $ljItem) {
            $isAlreadyChecked = ($index < count($labJaringanItems) / 2);
            DB::table('stock_take_items')->insert([
                'stock_take_id'    => $so2Id,
                'item_id'          => $ljItem->id,
                'system_quantity'  => 1,
                'system_condition' => $ljItem->condition,
                'actual_quantity'  => $isAlreadyChecked ? 1 : null,
                'actual_condition' => $isAlreadyChecked ? $ljItem->condition : null,
                'difference'       => 0,
                'notes'            => $isAlreadyChecked ? 'Kondisi fisik telah diperiksa.' : null,
                'checked_by'       => $isAlreadyChecked ? $adminId : null,
                'checked_at'       => $isAlreadyChecked ? Carbon::now()->subHours(rand(2, 10)) : null,
                'created_at'       => Carbon::now()->subDays(1),
                'updated_at'       => $now,
            ]);
        }

        // =========================================================================
        // 13. NOTIFIKASI SISTEM (IN-APP NOTIFICATIONS)
        // =========================================================================
        $notificationsData = [
            [
                'type'       => 'warning',
                'icon'       => 'warning',
                'title'      => 'Peringatan Jatuh Tempo Peminjaman',
                'message'    => 'Router MikroTik RB4011 (RTR-MKT-0004) dipinjam oleh Nabila Putri Cahyani (XI TKJ 1) melewati batas estimasi pengembalian.',
                'action_url' => '/data-peminjaman',
                'is_read'    => false,
                'hours_ago'  => 1,
            ],
            [
                'type'       => 'info',
                'icon'       => 'fact_check',
                'title'      => 'Sesi Stok Opname Sedang Berlangsung',
                'message'    => 'Sesi SO-2026-001 di Lab Jaringan & Fiber Optik sedang aktif. Silakan lakukan verifikasi fisik barang.',
                'action_url' => '/stock-take/' . $so2Id,
                'is_read'    => false,
                'hours_ago'  => 3,
            ],
            [
                'type'       => 'danger',
                'icon'       => 'error',
                'title'      => 'Laporan Kendala Perangkat',
                'message'    => 'Switch Cisco Catalyst 2960X dilaporkan mengalami kerusakan kipas pendingin dan dipindahkan ke status Maintenance.',
                'action_url' => '/data-perawatan',
                'is_read'    => false,
                'hours_ago'  => 8,
            ],
            [
                'type'       => 'success',
                'icon'       => 'task_alt',
                'title'      => 'Perawatan Rutin Server Selesai',
                'message'    => 'Pembersihan heatsink dan penggantian thermal paste Server Dell PowerEdge R740 telah selesai dan lolos tes beban kerja.',
                'action_url' => '/data-perawatan',
                'is_read'    => true,
                'hours_ago'  => 24,
            ],
            [
                'type'       => 'info',
                'icon'       => 'add_shopping_cart',
                'title'      => 'Aset Baru Ditambahkan',
                'message'    => '4 Unit Access Point Ubiquiti UniFi U6-Pro berhasil didaftarkan ke Data Barang NOC.',
                'action_url' => '/items',
                'is_read'    => true,
                'hours_ago'  => 48,
            ],
            [
                'type'       => 'warning',
                'icon'       => 'inventory_2',
                'title'      => 'Peringatan Stok Habis Pakai',
                'message'    => 'Konektor RJ45 Cat6 dan kabel roll Belden tersisa sedikit di Gudang Aset NOC. Mohon rencanakan pengadaan.',
                'action_url' => '/items',
                'is_read'    => true,
                'hours_ago'  => 72,
            ],
        ];

        // Kirim ke Superadmin dan Admin
        foreach ([$superadminId, $adminId] as $targetUserId) {
            foreach ($notificationsData as $notif) {
                $time = Carbon::now()->subHours($notif['hours_ago']);
                DB::table('notifications')->insert([
                    'id'         => (string) Str::uuid(),
                    'user_id'    => $targetUserId,
                    'type'       => $notif['type'],
                    'icon'       => $notif['icon'],
                    'title'      => $notif['title'],
                    'message'    => $notif['message'],
                    'action_url' => $notif['action_url'],
                    'is_read'    => $notif['is_read'],
                    'read_at'    => $notif['is_read'] ? $time->copy()->addMinutes(15) : null,
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

        // =========================================================================
        // 14. AUDIT TRAIL (ACTIVITY LOGS)
        // =========================================================================
        $auditLogs = [
            [
                'user_id'     => $superadminId,
                'action'      => 'login',
                'model_type'  => 'App\Models\User',
                'model_id'    => $superadminId,
                'description' => 'User superadmin berhasil masuk ke sistem ERP NOC',
                'old_values'  => null,
                'new_values'  => null,
                'hours_ago'   => 2,
            ],
            [
                'user_id'     => $adminId,
                'action'      => 'created',
                'model_type'  => 'App\Models\StockTake',
                'model_id'    => $so2Id,
                'description' => 'Membuat sesi Stok Opname baru: SO-2026-001 (Lab Jaringan & Fiber Optik)',
                'old_values'  => null,
                'new_values'  => ['code' => 'SO-2026-001', 'title' => 'Stok Opname Triwulan I 2026 - Lab Jaringan & Fiber Optik', 'status' => 'in_progress'],
                'hours_ago'   => 24,
            ],
            [
                'user_id'     => $superadminId,
                'action'      => 'updated',
                'model_type'  => 'App\Models\StockTake',
                'model_id'    => $so1Id,
                'description' => 'Menyetujui (Approve) Sesi Stok Opname SO-2025-001',
                'old_values'  => ['status' => 'completed', 'approved_by' => null],
                'new_values'  => ['status' => 'approved', 'approved_by' => $superadminId],
                'hours_ago'   => 48,
            ],
            [
                'user_id'     => $adminId,
                'action'      => 'created',
                'model_type'  => 'App\Models\Peminjaman',
                'model_id'    => 1,
                'description' => 'Mencatat peminjaman perangkat router RTR-MKT-0004 kepada Nabila Putri Cahyani (XI TKJ 1)',
                'old_values'  => null,
                'new_values'  => ['peminjam' => 'Nabila Putri Cahyani', 'item_code' => 'RTR-MKT-0004', 'status' => 'dipinjam'],
                'hours_ago'   => 50,
            ],
            [
                'user_id'     => $adminId,
                'action'      => 'updated',
                'model_type'  => 'App\Models\Item',
                'model_id'    => $itemIds[0],
                'description' => 'Memperbarui lokasi aset Router MikroTik CCR1036 ke Ruang Server NOC Utama',
                'old_values'  => ['location_id' => $locIds[5]],
                'new_values'  => ['location_id' => $locIds[0]],
                'hours_ago'   => 72,
            ],
            [
                'user_id'     => $superadminId,
                'action'      => 'created',
                'model_type'  => 'App\Models\Item',
                'model_id'    => $itemIds[1],
                'description' => 'Menambahkan data aset baru Router MikroTik CCR2004-16G-2S+',
                'old_values'  => null,
                'new_values'  => ['name' => 'Router MikroTik Cloud Core', 'code' => 'RTR-CCR-0002', 'condition' => 'baik'],
                'hours_ago'   => 96,
            ],
        ];

        foreach ($auditLogs as $log) {
            $lTime = Carbon::now()->subHours($log['hours_ago']);
            DB::table('activity_logs')->insert([
                'user_id'     => $log['user_id'],
                'action'      => $log['action'],
                'model_type'  => $log['model_type'],
                'model_id'    => $log['model_id'],
                'description' => $log['description'],
                'old_values'  => $log['old_values'] ? json_encode($log['old_values']) : null,
                'new_values'  => $log['new_values'] ? json_encode($log['new_values']) : null,
                'ip_address'  => '127.0.0.1',
                'user_agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/128.0.0.0 Safari/537.36',
                'created_at'  => $lTime,
                'updated_at'  => $lTime,
            ]);
        }

        $this->command->info("NocSeeder: Berhasil membersihkan data lama dan men-seed data ERP lengkap!");
        $this->command->info("- Total Aset Items: " . count($itemIds));
        $this->command->info("- Total Mutasi: " . count($movements));
        $this->command->info("- Total Peminjaman: " . count($peminjamans));
        $this->command->info("- Total Perawatan: " . count($perawatans));
        $this->command->info("- Total Sesi Stok Opname: 2 (1 Approved, 1 In Progress)");
        $this->command->info("- Total Notifikasi: " . (count($notificationsData) * 2));
        $this->command->info("- Total Audit Trail Logs: " . count($auditLogs));
    }
}
