<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class JurusanDummySeeder extends Seeder
{
    /**
     * Seed akun Jurusan (1 per jurusan) beserta contoh barang di Data Master.
     * Jalankan SETELAH NocSeeder (agar master data sudah ada).
     */
    public function run(): void
    {
        $now = Carbon::now();

        // ====================================================================
        // 1. Pastikan Data Jurusan ada (insert jika belum ada)
        // ====================================================================
        $jurusanData = [
            ['kode_jurusan' => 'TKJ',  'name' => 'Teknik Komputer dan Jaringan',           'kepala_jurusan' => 'Ahmad Fauzi, S.Kom',       'description' => 'Jurusan TKJ',  'is_active' => 1],
            ['kode_jurusan' => 'RPL',  'name' => 'Rekayasa Perangkat Lunak',                'kepala_jurusan' => 'Sari Dewi, S.T',            'description' => 'Jurusan RPL',  'is_active' => 1],
            ['kode_jurusan' => 'MM',   'name' => 'Multimedia',                              'kepala_jurusan' => 'Budi Santoso, S.Pd',        'description' => 'Jurusan MM',   'is_active' => 1],
            ['kode_jurusan' => 'SIJA', 'name' => 'Sistem Informatika Jaringan dan Aplikasi','kepala_jurusan' => 'Rizky Pratama, M.Kom',      'description' => 'Jurusan SIJA', 'is_active' => 1],
            ['kode_jurusan' => 'DKV',  'name' => 'Desain Komunikasi Visual',                'kepala_jurusan' => 'Rina Kusuma, S.Ds',         'description' => 'Jurusan DKV',  'is_active' => 1],
        ];

        $jurusanIds = [];
        foreach ($jurusanData as $j) {
            $existing = DB::table('jurusans')->where('kode_jurusan', $j['kode_jurusan'])->first();
            if ($existing) {
                $jurusanIds[$j['kode_jurusan']] = $existing->id;
            } else {
                $jurusanIds[$j['kode_jurusan']] = DB::table('jurusans')->insertGetId(array_merge($j, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // ====================================================================
        // 2. Akun Jurusan — 1 akun per jurusan, password: jurusan123
        //    username: tkj / rpl / mm / sija / dkv
        // ====================================================================
        $jurusanAccounts = [
            [
                'kode'       => 'TKJ',
                'name'       => 'Akun Jurusan TKJ',
                'username'   => 'tkj',
                'email'      => 'TKJ',
                'password'   => Hash::make('jurusan123'),
                'role'       => 'Jurusan',
                'is_active'  => 1,
            ],
            [
                'kode'       => 'RPL',
                'name'       => 'Akun Jurusan RPL',
                'username'   => 'rpl',
                'email'      => 'RPL',
                'password'   => Hash::make('jurusan123'),
                'role'       => 'Jurusan',
                'is_active'  => 1,
            ],
            [
                'kode'       => 'MM',
                'name'       => 'Akun Jurusan MM',
                'username'   => 'mm',
                'email'      => 'MM',
                'password'   => Hash::make('jurusan123'),
                'role'       => 'Jurusan',
                'is_active'  => 1,
            ],
            [
                'kode'       => 'SIJA',
                'name'       => 'Akun Jurusan SIJA',
                'username'   => 'sija',
                'email'      => 'SIJA',
                'password'   => Hash::make('jurusan123'),
                'role'       => 'Jurusan',
                'is_active'  => 1,
            ],
            [
                'kode'       => 'DKV',
                'name'       => 'Akun Jurusan DKV',
                'username'   => 'dkv',
                'email'      => 'DKV',
                'password'   => Hash::make('jurusan123'),
                'role'       => 'Jurusan',
                'is_active'  => 1,
            ],
        ];

        foreach ($jurusanAccounts as $acc) {
            $existing = DB::table('users')->where('username', $acc['username'])->first();
            if (!$existing) {
                $maxId = DB::table('users')->max('id') ?? 0;
                DB::table('users')->insert([
                    'user_code'  => 'USR-' . str_pad($maxId + 1, 3, '0', STR_PAD_LEFT),
                    'name'       => $acc['name'],
                    'username'   => $acc['username'],
                    'email'      => $acc['email'],
                    'password'   => $acc['password'],
                    'role'       => $acc['role'],
                    'is_active'  => $acc['is_active'],
                    'jurusan_id' => $jurusanIds[$acc['kode']] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $this->command->info("  [+] Akun '{$acc['username']}' dibuat.");
            } else {
                $this->command->warn("  [!] Akun '{$acc['username']}' sudah ada, dilewati.");
            }
        }

        // ====================================================================
        // 3. Contoh Barang Tambahan (jika kategori sudah ada dari NocSeeder)
        //    Hanya 5 barang ringan agar tidak overwhelming
        // ====================================================================
        $categories = DB::table('categories')->pluck('id', 'slug');
        $locations  = DB::table('locations')->pluck('id')->toArray();
        $suppliers  = DB::table('suppliers')->pluck('id')->toArray();
        $asals      = DB::table('asal_barangs')->pluck('id')->toArray();
        $kondisis   = DB::table('kondisi_barangs')->pluck('id', 'name');

        if ($categories->isEmpty() || empty($locations)) {
            $this->command->warn('  [!] Master data (kategori/lokasi) belum ada. Jalankan NocSeeder dulu.');
            return;
        }

        $sampleItems = [
            ['Router Cisco 2901',  'Cisco',    'CISCO2901', 'router',      'CSC', $locations[0], 'Baik',         'tersedia', 18000000],
            ['Laptop Dell Vostro', 'Dell',     'V3500',     'laptop',      'DEL', $locations[0], 'Baik',         'tersedia',  9500000],
            ['Switch Zyxel',       'Zyxel',    'GS1200-5',  'switch-hub',  'ZYX', $locations[0], 'Baik',         'tersedia',  1200000],
            ['Monitor ASUS',       'ASUS',     'VP228HE',   'monitor',     'ASS', $locations[0], 'Rusak Ringan', 'maintenance', 1700000],
            ['LAN Tester Rj45',    'Generic',  'Pro-3000',  'tools',       'GEN', $locations[0], 'Baik',         'tersedia',    150000],
        ];

        foreach ($sampleItems as [$name, $brand, $model, $catSlug, $subPrefix, $locId, $kondisiName, $status, $price]) {
            $catId = $categories[$catSlug] ?? null;
            if (!$catId) continue;

            $catRow   = DB::table('categories')->find($catId);
            $nextNum  = $catRow->last_code_number + 1;
            DB::table('categories')->where('id', $catId)->update([
                'last_code_number' => $nextNum,
                'updated_at'       => $now,
            ]);

            $code = $catRow->prefix . '-' . strtoupper($subPrefix) . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

            $conditionMap = [
                'Baik'         => 'baik',
                'Rusak Ringan' => 'rusak_ringan',
                'Rusak Berat'  => 'rusak_berat',
                'Hilang'       => 'hilang',
            ];

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
                'kondisi_barang_id' => $kondisis[$kondisiName] ?? null,
                'quantity'          => 1,
                'condition'         => $conditionMap[$kondisiName] ?? 'baik',
                'status'            => $status,
                'purchase_date'     => Carbon::now()->subDays(rand(60, 365))->format('Y-m-d'),
                'purchase_price'    => $price,
                'notes'             => "Barang dummy - $brand $model",
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);

            $this->command->info("  [+] Barang '{$name}' ({$code}) ditambahkan.");
        }

        $this->command->info('JurusanDummySeeder selesai!');
        $this->command->line('');
        $this->command->line('  Akun Jurusan yang dibuat:');
        $this->command->line('  ┌──────────────────────────────────────────┐');
        $this->command->line('  │  Username  │  Password    │  Jurusan     │');
        $this->command->line('  ├──────────────────────────────────────────┤');
        $this->command->line('  │  tkj       │  jurusan123  │  TKJ         │');
        $this->command->line('  │  rpl       │  jurusan123  │  RPL         │');
        $this->command->line('  │  mm        │  jurusan123  │  MM          │');
        $this->command->line('  │  sija      │  jurusan123  │  SIJA        │');
        $this->command->line('  │  dkv       │  jurusan123  │  DKV         │');
        $this->command->line('  └──────────────────────────────────────────┘');
    }
}
