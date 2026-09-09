<?php

namespace Database\Seeders;

use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\Category;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProcurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::where('role', 'Superadmin')->first() ?? User::first();
        $admin = User::where('role', 'Admin')->first() ?? $superadmin;
        $jurusanUser = User::where('role', 'Jurusan')->first() ?? $admin;

        $tkj = Jurusan::where('name', 'like', '%TKJ%')->orWhere('name', 'like', '%Teknik Komputer%')->first();
        $rpl = Jurusan::where('name', 'like', '%RPL%')->orWhere('name', 'like', '%Rekayasa Perangkat%')->first();
        $dkv = Jurusan::where('name', 'like', '%DKV%')->orWhere('name', 'like', '%Desain%')->first();

        $catNetwork = Category::where('name', 'like', '%Jaringan%')->orWhere('name', 'like', '%Network%')->first();
        $catHardware = Category::where('name', 'like', '%Hardware%')->orWhere('name', 'like', '%Komputer%')->first();
        $catTool = Category::where('name', 'like', '%Alat%')->orWhere('name', 'like', '%Perangkat%')->first();

        // Bersihkan data dummy lama jika sudah ada (agar seeder idempoten)
        $codes = [
            'PR-' . now()->year . '-001',
            'PR-' . now()->year . '-002',
            'PR-' . now()->year . '-003',
            'PR-' . now()->year . '-004',
            'PR-' . now()->year . '-005',
        ];
        $existing = Procurement::whereIn('code', $codes)->get();
        foreach ($existing as $ex) {
            $ex->items()->delete();
            $ex->delete();
        }

        // 1. Pengadaan TKJ - Status: Pending (Menunggu Persetujuan)
        $p1 = Procurement::create([
            'code' => 'PR-' . now()->year . '-001',
            'title' => 'Pengadaan Switch Managed 24-Port & Access Point Wifi 6 Lab TKJ',
            'user_id' => $jurusanUser ? $jurusanUser->id : $admin->id,
            'jurusan_id' => $tkj ? $tkj->id : null,
            'priority' => 'mendesak',
            'target_date' => now()->addDays(14),
            'status' => 'pending',
            'justification' => "Switch eksisting di Lab TKJ 2 mengalami kerusakan pada 8 port dan sering drop traffic saat praktikum VLAN & Routing OSPF kelas XII. Dibutuhkan segera switch gigabit managed dan AP Wifi 6 untuk mendukung kelancaran Uji Kompetensi Keahlian (UKK).",
            'notes' => 'Telah dikoordinasikan dengan Kepala Laboratorium TKJ.',
            'total_estimated_cost' => 0,
        ]);

        ProcurementItem::create([
            'procurement_id' => $p1->id,
            'category_id' => $catNetwork ? $catNetwork->id : null,
            'item_name' => 'Cisco Catalyst 24-Port Gigabit Managed Switch',
            'specification' => 'Model CBS250-24T-4G, 24x Gigabit Ethernet + 4x Gigabit SFP, Rackmount 1U',
            'quantity' => 2,
            'unit' => 'Unit',
            'estimated_unit_price' => 5400000,
            'reference_url' => 'https://www.cisco.com/c/en/us/products/switches/catalyst-business-250-series-switches/index.html',
        ]);

        ProcurementItem::create([
            'procurement_id' => $p1->id,
            'category_id' => $catNetwork ? $catNetwork->id : null,
            'item_name' => 'Ubiquiti UniFi 6 Pro Access Point (U6-Pro)',
            'specification' => 'Dual-Band WiFi 6 (802.11ax), Speed up to 5.3 Gbps, PoE powered',
            'quantity' => 3,
            'unit' => 'Unit',
            'estimated_unit_price' => 2850000,
            'reference_url' => 'https://ui.com/wi-fi/flagship',
        ]);
        $p1->recalculateTotal();

        // 2. Pengadaan Server & UPS NOC - Status: Approved (Disetujui)
        $p2 = Procurement::create([
            'code' => 'PR-' . now()->year . '-002',
            'title' => 'Pengadaan Server Virtualisasi & Online UPS 3kVA Ruang Server NOC',
            'user_id' => $admin->id,
            'jurusan_id' => null, // Pusat NOC
            'priority' => 'tinggi',
            'target_date' => now()->addDays(20),
            'status' => 'approved',
            'justification' => 'Peningkatan kapasitas server private cloud sekolah untuk hosting LMS Moodle SMKN 4 Malang, repositori git internal, dan backup terpusat data inventaris.',
            'approved_by' => $superadmin->id,
            'approved_at' => now()->subDays(2),
            'notes' => 'Disetujui menggunakan anggaran sarana prasarana sekolah semester ganjil.',
            'total_estimated_cost' => 0,
        ]);

        ProcurementItem::create([
            'procurement_id' => $p2->id,
            'category_id' => $catHardware ? $catHardware->id : null,
            'item_name' => 'Server Dell PowerEdge R450 Rackmount 1U',
            'specification' => 'Intel Xeon Silver 4310 12C/24T, 64GB DDR4 ECC, 2x 960GB SSD Enterprise RAID 1',
            'quantity' => 1,
            'unit' => 'Unit',
            'estimated_unit_price' => 38500000,
        ]);

        ProcurementItem::create([
            'procurement_id' => $p2->id,
            'category_id' => $catHardware ? $catHardware->id : null,
            'item_name' => 'APC Smart-UPS On-Line 3000VA (SRT3000XLI)',
            'specification' => 'Pure Sinewave Online Double Conversion, LCD Display, Rack/Tower convertible 2U',
            'quantity' => 1,
            'unit' => 'Unit',
            'estimated_unit_price' => 18900000,
        ]);
        $p2->recalculateTotal();

        // 3. Pengadaan Lab Animasi & RPL - Status: In Procurement (Proses Pengadaan PO)
        $p3 = Procurement::create([
            'code' => 'PR-' . now()->year . '-003',
            'title' => 'Pengadaan Pen Display Tablet Wacom & RAM Upgrade Lab Animasi',
            'user_id' => $jurusanUser ? $jurusanUser->id : $admin->id,
            'jurusan_id' => $dkv ? $dkv->id : ($rpl ? $rpl->id : null),
            'priority' => 'sedang',
            'target_date' => now()->addDays(7),
            'status' => 'in_procurement',
            'justification' => 'Mendukung mata pelajaran pembuatan aset 3D & Digital Sculpting kelas XI Animasi.',
            'approved_by' => $superadmin->id,
            'approved_at' => now()->subDays(5),
            'notes' => 'Surat Pesanan (PO) No. PO/NOC/2026/08 telah dikirimkan ke vendor resmi.',
            'total_estimated_cost' => 0,
        ]);

        ProcurementItem::create([
            'procurement_id' => $p3->id,
            'category_id' => $catHardware ? $catHardware->id : null,
            'item_name' => 'Wacom Cintiq 16 Pen Display Drawing Tablet',
            'specification' => '15.6 Inch Full HD Display, 8192 Pressure Levels Pro Pen 2, AG Film',
            'quantity' => 5,
            'unit' => 'Unit',
            'estimated_unit_price' => 9750000,
        ]);
        $p3->recalculateTotal();

        // 4. Pengadaan Bahan Praktikum Jaringan - Status: Completed (Terealisasi)
        $p4 = Procurement::create([
            'code' => 'PR-' . now()->year . '-004',
            'title' => 'Pengadaan Kabel UTP Cat6, Tang Crimping & LAN Tester Digital',
            'user_id' => $admin->id,
            'jurusan_id' => $tkj ? $tkj->id : null,
            'priority' => 'sedang',
            'target_date' => now()->subDays(10),
            'status' => 'completed',
            'justification' => 'Pengadaan habis pakai kabel jaringan dan perkakas crimping untuk semester baru siswa kelas X dan XI.',
            'approved_by' => $superadmin->id,
            'approved_at' => now()->subDays(15),
            'completed_at' => now()->subDays(2),
            'notes' => 'Seluruh barang telah diterima lengkap di gudang NOC dan didistribusikan ke Lab TKJ 1.',
            'total_estimated_cost' => 0,
        ]);

        $i1 = ProcurementItem::create([
            'procurement_id' => $p4->id,
            'category_id' => $catTool ? $catTool->id : null,
            'item_name' => 'Kabel Belden UTP Cat6 Solid U/UTP (305 Meter/Roll)',
            'specification' => 'Original Belden USA standard, 24 AWG Bare Copper, Blue jacket',
            'quantity' => 4,
            'unit' => 'Roll',
            'estimated_unit_price' => 1850000,
            'status' => 'received',
            'received_quantity' => 4,
        ]);

        $i2 = ProcurementItem::create([
            'procurement_id' => $p4->id,
            'category_id' => $catTool ? $catTool->id : null,
            'item_name' => 'Knipex Modular Plug Crimping Pliers Cat5/6/7',
            'specification' => 'Professional German Heavy Duty Crimping Tool RJ45/RJ11',
            'quantity' => 6,
            'unit' => 'Pcs',
            'estimated_unit_price' => 650000,
            'status' => 'received',
            'received_quantity' => 6,
        ]);
        $p4->recalculateTotal();

        // 5. Pengadaan Draf - Status: Draft
        $p5 = Procurement::create([
            'code' => 'PR-' . now()->year . '-005',
            'title' => 'Usulan Pengadaan Kamera Mirrorless & Gimbal Stabilizer DKV',
            'user_id' => $jurusanUser ? $jurusanUser->id : $admin->id,
            'jurusan_id' => $dkv ? $dkv->id : null,
            'priority' => 'rendah',
            'target_date' => now()->addMonth(),
            'status' => 'draft',
            'justification' => 'Perencanaan regenerasi peralatan videografi studio produksi SMKN 4 Malang untuk tahun ajaran berikutnya.',
            'total_estimated_cost' => 0,
        ]);

        ProcurementItem::create([
            'procurement_id' => $p5->id,
            'category_id' => $catHardware ? $catHardware->id : null,
            'item_name' => 'Sony Cinema Line FX30 Mirrorless Camera Body',
            'specification' => '26.1MP APS-C BSI CMOS Sensor, 4K up to 120p, 10-Bit 4:2:2 XAVC S-I',
            'quantity' => 1,
            'unit' => 'Unit',
            'estimated_unit_price' => 28000000,
        ]);

        ProcurementItem::create([
            'procurement_id' => $p5->id,
            'category_id' => $catHardware ? $catHardware->id : null,
            'item_name' => 'DJI RS 3 Pro Gimbal Stabilizer Combo',
            'specification' => 'Automated Axis Locks, 4.5kg Tested Payload, LiDAR Focusing',
            'quantity' => 1,
            'unit' => 'Set',
            'estimated_unit_price' => 12500000,
        ]);
        $p5->recalculateTotal();
    }
}
