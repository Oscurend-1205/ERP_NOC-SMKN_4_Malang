<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DbSeederController extends Controller
{
    public function resetAndSeed()
    {
        try {
            // 1. Disable Foreign Keys
            Schema::disableForeignKeyConstraints();

            // 2. Truncate all tables
            DB::table('item_movements')->truncate();
            DB::table('peminjaman')->truncate();
            DB::table('scan_sessions')->truncate();
            DB::table('items')->truncate();
            
            DB::table('categories')->truncate();
            DB::table('locations')->truncate();
            DB::table('suppliers')->truncate();
            DB::table('kondisi_barangs')->truncate();
            DB::table('asal_barangs')->truncate();
            DB::table('jurusans')->truncate();
            DB::table('users')->truncate();

            // 3. Enable Foreign Keys
            Schema::enableForeignKeyConstraints();

            $now = Carbon::now();

            // ==========================================
            // SEEDING DATA MASTER
            // ==========================================

            // Users
            $users = [
                [
                    'name' => 'Super Admin NOC',
                    'username' => 'superadmin',
                    'user_code' => 'USR-1',
                    'email' => 'superadmin@noc.smkn4malang.sch.id',
                    'password' => Hash::make('SuperAdmin@2026'),
                    'role' => 'Superadmin',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Admin Gudang',
                    'username' => 'admin',
                    'user_code' => 'USR-2',
                    'email' => 'admin@noc.smkn4malang.sch.id',
                    'password' => Hash::make('Admin@2026'),
                    'role' => 'Admin',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Guru Jaringan',
                    'username' => 'guru_jaringan',
                    'user_code' => 'USR-3',
                    'email' => 'guru@noc.smkn4malang.sch.id',
                    'password' => Hash::make('password'),
                    'role' => 'Admin',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Budi Santoso',
                    'username' => 'budi_santoso',
                    'user_code' => 'USR-4',
                    'email' => 'XII TKJ 1', // Kelas disimpan di email sesuai standard aplikasi
                    'password' => Hash::make('password'),
                    'role' => 'Siswa',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ];
            foreach ($users as $user) {
                DB::table('users')->insert($user);
            }

            // Categories
            $categories = [
                ['name' => 'Router', 'slug' => 'router', 'prefix' => 'RTR', 'last_code_number' => 0, 'description' => 'Perangkat Router', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Switch', 'slug' => 'switch', 'prefix' => 'SWT', 'last_code_number' => 0, 'description' => 'Perangkat Switch Manageable dan Unmanageable', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Access Point', 'slug' => 'access-point', 'prefix' => 'AP', 'last_code_number' => 0, 'description' => 'Perangkat Pemancar WiFi', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Kabel', 'slug' => 'kabel', 'prefix' => 'KBL', 'last_code_number' => 0, 'description' => 'Kabel Jaringan (UTP, Fiber, dll)', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Tools', 'slug' => 'tools', 'prefix' => 'TLS', 'last_code_number' => 0, 'description' => 'Peralatan Jaringan (Crimping, Lan Tester, dll)', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Server', 'slug' => 'server', 'prefix' => 'SRV', 'last_code_number' => 0, 'description' => 'Perangkat Komputer Server', 'created_at' => $now, 'updated_at' => $now],
            ];
            DB::table('categories')->insert($categories);

            // Locations
            $locations = [
                ['name' => 'Gudang Utama', 'code' => 'GDG-01', 'penanggung_jawab' => 'Bpk. Ahmad', 'description' => 'Gudang penyimpanan utama peralatan', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Lab Jaringan 1', 'code' => 'LAB-01', 'penanggung_jawab' => 'Ibu Siti', 'description' => 'Laboratorium Praktik Jaringan 1', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Lab Jaringan 2', 'code' => 'LAB-02', 'penanggung_jawab' => 'Bpk. Budi', 'description' => 'Laboratorium Praktik Jaringan 2', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Ruang Server', 'code' => 'SRV-01', 'penanggung_jawab' => 'Bpk. Hendro', 'description' => 'Ruang Server Utama NOC', 'created_at' => $now, 'updated_at' => $now],
            ];
            DB::table('locations')->insert($locations);

            // Suppliers
            $suppliers = [
                ['name' => 'PT Mikrotik Indonesia', 'pic' => 'Bpk. Joko', 'phone' => '081234567890', 'email' => 'sales@mikrotik.co.id', 'address' => 'Jakarta Pusat', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'CV Cisco Mitra', 'pic' => 'Ibu Rina', 'phone' => '081298765432', 'email' => 'info@ciscomitra.com', 'address' => 'Surabaya', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Toko Kabel Makmur', 'pic' => 'Bpk. Doni', 'phone' => '085678912345', 'email' => 'kabelmakmur@gmail.com', 'address' => 'Malang', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ];
            DB::table('suppliers')->insert($suppliers);

            // Kondisi Barangs
            $kondisi = [
                ['name' => 'Baru', 'label_color' => 'green', 'description' => 'Barang baru dan belum dipakai', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Layak Pakai', 'label_color' => 'blue', 'description' => 'Barang bekas namun masih berfungsi baik', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Rusak Ringan', 'label_color' => 'yellow', 'description' => 'Barang mengalami kerusakan ringan dan perlu perbaikan', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Rusak Berat', 'label_color' => 'red', 'description' => 'Barang rusak tidak dapat digunakan', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ];
            DB::table('kondisi_barangs')->insert($kondisi);

            // Asal Barangs
            $asal = [
                ['name' => 'Pembelian Sekolah', 'description' => 'Barang dibeli dari anggaran sekolah', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'BOS (Bantuan Operasional Sekolah)', 'description' => 'Barang dari dana BOS', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Hibah / Donasi', 'description' => 'Barang pemberian pihak ketiga', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ];
            DB::table('asal_barangs')->insert($asal);

            // Jurusan
            $jurusans = [
                ['name' => 'Teknik Komputer dan Jaringan', 'description' => 'Jurusan TKJ', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Rekayasa Perangkat Lunak', 'description' => 'Jurusan RPL', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Multimedia', 'description' => 'Jurusan Multimedia', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ];
            DB::table('jurusans')->insert($jurusans);

            // ==========================================
            // SEEDING DATA ITEMS (BARANG) - 1 ROW = 1 UNIT ARCHITECTURE
            // ==========================================
            
            $catRouter = DB::table('categories')->where('slug', 'router')->first()->id;
            $catSwitch = DB::table('categories')->where('slug', 'switch')->first()->id;
            $catAP = DB::table('categories')->where('slug', 'access-point')->first()->id;
            $catTools = DB::table('categories')->where('slug', 'tools')->first()->id;

            $locGudang = DB::table('locations')->where('code', 'GDG-01')->first()->id;
            $locLab1 = DB::table('locations')->where('code', 'LAB-01')->first()->id;

            // Template item untuk di-generate secara individual
            $itemTemplates = [
                [
                    'name' => 'MikroTik RB750Gr3',
                    'serial_number_base' => 'MTK123456789',
                    'brand' => 'MikroTik',
                    'model' => 'RB750Gr3',
                    'category_id' => $catRouter,
                    'location_id' => $locLab1,
                    'quantity_to_seed' => 15,
                    'condition' => 'baik',
                    'status' => 'tersedia',
                    'purchase_date' => '2025-01-10',
                    'purchase_price' => 850000.00,
                    'notes' => 'Router untuk praktik lab',
                ],
                [
                    'name' => 'Cisco Catalyst 2960',
                    'serial_number_base' => 'CSC987654321',
                    'brand' => 'Cisco',
                    'model' => 'WS-C2960-24TC-L',
                    'category_id' => $catSwitch,
                    'location_id' => $locGudang,
                    'quantity_to_seed' => 5,
                    'condition' => 'baik',
                    'status' => 'tersedia',
                    'purchase_date' => '2024-11-15',
                    'purchase_price' => 3500000.00,
                    'notes' => 'Switch manageable 24 port',
                ],
                [
                    'name' => 'Ubiquiti UniFi AP AC LR',
                    'serial_number_base' => 'UBQ555555555',
                    'brand' => 'Ubiquiti',
                    'model' => 'UAP-AC-LR',
                    'category_id' => $catAP,
                    'location_id' => $locGudang,
                    'quantity_to_seed' => 10,
                    'condition' => 'baik',
                    'status' => 'tersedia',
                    'purchase_date' => '2026-02-20',
                    'purchase_price' => 1650000.00,
                    'notes' => 'Access point long range',
                ],
                [
                    'name' => 'Tang Crimping RJ45',
                    'serial_number_base' => null,
                    'brand' => 'Bosi Tools',
                    'model' => 'HT-568R',
                    'category_id' => $catTools,
                    'location_id' => $locLab1,
                    'quantity_to_seed' => 30,
                    'condition' => 'baik',
                    'status' => 'tersedia',
                    'purchase_date' => '2026-01-05',
                    'purchase_price' => 150000.00,
                    'notes' => 'Tang crimping untuk praktik kabel',
                ],
                [
                    'name' => 'LAN Tester',
                    'serial_number_base' => null,
                    'brand' => 'Noyafa',
                    'model' => 'NF-8108',
                    'category_id' => $catTools,
                    'location_id' => $locLab1,
                    'quantity_to_seed' => 20,
                    'condition' => 'rusak_ringan',
                    'status' => 'tersedia',
                    'purchase_date' => '2025-05-12',
                    'purchase_price' => 450000.00,
                    'notes' => 'LAN Tester dengan panjang kabel',
                ]
            ];

            $totalUnitsSeeded = 0;

            // Track running sequence per category to avoid duplicate codes
            $categoryCounters = [];

            foreach ($itemTemplates as $tpl) {
                // Generate code using category prefix
                $catId = $tpl['category_id'];
                if (!isset($categoryCounters[$catId])) {
                    $categoryCounters[$catId] = 0;
                }
                $catPrefix = DB::table('categories')->where('id', $catId)->value('prefix');

                for ($seq = 1; $seq <= $tpl['quantity_to_seed']; $seq++) {
                    $categoryCounters[$catId]++;
                    $code = $catPrefix . '-' . str_pad($categoryCounters[$catId], 4, '0', STR_PAD_LEFT);
                    $serial = $tpl['serial_number_base'] 
                        ? $tpl['serial_number_base'] . '-' . str_pad($seq, 2, '0', STR_PAD_LEFT)
                        : null;

                    DB::table('items')->insert([
                        'name' => $tpl['name'],
                        'code' => $code,
                        'serial_number' => $serial,
                        'brand' => $tpl['brand'],
                        'model' => $tpl['model'],
                        'category_id' => $tpl['category_id'],
                        'location_id' => $tpl['location_id'],
                        'quantity' => 1, // 1 row = 1 unit
                        'condition' => $tpl['condition'],
                        'status' => $tpl['status'],
                        'purchase_date' => $tpl['purchase_date'],
                        'purchase_price' => $tpl['purchase_price'],
                        'notes' => $tpl['notes'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $totalUnitsSeeded++;
                }
            }

            // Update last_code_number on each category after seeding
            foreach ($categoryCounters as $catId => $lastNum) {
                DB::table('categories')->where('id', $catId)->update(['last_code_number' => $lastNum]);
            }

            // ==========================================
            // SEEDING DATA PERGERAKAN / PEMINJAMAN
            // ==========================================
            
            // Cari unit spesifik Tang Crimping ke-4 (TLS-0004) untuk status dipinjam
            $borrowedItem = DB::table('items')->where('code', 'TLS-0004')->first();
            
            if ($borrowedItem) {
                // Set status unit ke-4 ini ke 'dipinjam' dan quantity ke 0
                DB::table('items')->where('id', $borrowedItem->id)->update([
                    'status' => 'dipinjam',
                    'quantity' => 0
                ]);
                
                // Masukkan data peminjaman
                DB::table('peminjaman')->insert([
                    'nama_peminjam' => 'Budi Santoso',
                    'kelas' => 'Teknik Komputer dan Jaringan',
                    'item_id' => $borrowedItem->id,
                    'item_code' => 'TLS-0004',
                    'session_token' => 'DUMMY-TOKEN-123',
                    'waktu_pinjam' => $now->copy()->subDays(2),
                    'waktu_kembali' => null,
                    'status' => 'dipinjam',
                    'catatan' => 'Pinjam untuk praktik bengkel',
                    'created_at' => $now->copy()->subDays(2),
                    'updated_at' => $now->copy()->subDays(2),
                ]);
            }

            // ==========================================
            // SEEDING AKTIVITAS / ITEM MOVEMENTS (DASHBOARD FEED)
            // ==========================================
            
            $superadminUserId = DB::table('users')->where('username', 'superadmin')->first()->id;

            // Cari beberapa barang acak untuk riwayat mutasi masuk
            $rbUnit1 = DB::table('items')->where('code', 'RTR-0001')->first();
            $cscUnit1 = DB::table('items')->where('code', 'SWT-0001')->first();
            $apUnit1 = DB::table('items')->where('code', 'AP-0001')->first();

            $movements = [];

            if ($rbUnit1) {
                $movements[] = [
                    'item_id' => $rbUnit1->id,
                    'user_id' => $superadminUserId,
                    'type' => 'masuk',
                    'from_location_id' => null,
                    'to_location_id' => $locLab1,
                    'quantity' => 1,
                    'notes' => 'Penerimaan barang baru dari PT Mikrotik Indonesia',
                    'movement_date' => '2025-01-10',
                    'created_at' => $now->copy()->subMonths(3),
                    'updated_at' => $now->copy()->subMonths(3),
                ];
            }

            if ($cscUnit1) {
                $movements[] = [
                    'item_id' => $cscUnit1->id,
                    'user_id' => $superadminUserId,
                    'type' => 'masuk',
                    'from_location_id' => null,
                    'to_location_id' => $locGudang,
                    'quantity' => 1,
                    'notes' => 'Penerimaan barang baru dari CV Cisco Mitra',
                    'movement_date' => '2024-11-15',
                    'created_at' => $now->copy()->subMonths(5),
                    'updated_at' => $now->copy()->subMonths(5),
                ];
            }

            if ($apUnit1) {
                $movements[] = [
                    'item_id' => $apUnit1->id,
                    'user_id' => $superadminUserId,
                    'type' => 'masuk',
                    'from_location_id' => null,
                    'to_location_id' => $locGudang,
                    'quantity' => 1,
                    'notes' => 'Penerimaan barang baru dari BOS',
                    'movement_date' => '2026-02-20',
                    'created_at' => $now->copy()->subDays(10),
                    'updated_at' => $now->copy()->subDays(10),
                ];
            }

            if (count($movements) > 0) {
                DB::table('item_movements')->insert($movements);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Database berhasil di-reset dan disuntik data dummy dengan rapi dan banyak!',
                'data_summary' => [
                    'users' => count($users),
                    'categories' => count($categories),
                    'locations' => count($locations),
                    'items_physical_units' => $totalUnitsSeeded,
                    'suppliers' => count($suppliers),
                    'kondisi' => count($kondisi),
                    'asal_barang' => count($asal),
                    'peminjaman' => $borrowedItem ? 1 : 0,
                    'movements' => count($movements),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mereset database: ' . $e->getMessage()
            ], 500);
        }
    }
}
