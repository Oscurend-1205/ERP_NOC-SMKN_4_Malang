@extends('layouts.app')

@section('title', 'Dokumentasi & Panduan Sistem - ERP NOC SMKN 4 Malang')

@section('content')
<div class="space-y-6 max-w-[1400px] mx-auto pb-16">

    <!-- ================================================================= -->
    <!-- HEADER DOKUMENTASI INDUSTRI -->
    <!-- ================================================================= -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs relative overflow-hidden">
        <!-- Accent Top Bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700"></div>

        <!-- Breadcrumb Formal -->
        <nav class="flex items-center gap-2 text-xs text-gray-500 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-700 transition-colors">Sistem ERP NOC</a>
            <span class="text-gray-300">/</span>
            <span class="font-medium text-gray-700">Dokumentasi & Panduan Operasional</span>
        </nav>

        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="space-y-2 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-blue-50 border border-blue-100 text-blue-800 text-[11px] font-mono font-semibold uppercase tracking-wider">
                    <span>Dokumen Kontrol No: SOP-NOC-SIM-01</span>
                    <span class="w-1 h-1 rounded-full bg-blue-400"></span>
                    <span>Versi 2.4-Enterprise</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    Manual Operasional & Dokumentasi Sistem Informasi ERP Laboratorium NOC
                </h1>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Pedoman tata kelola inventarisasi, sirkulasi peminjaman, alur pengadaan alat, pemeliharaan preventif, dan rekonsiliasi fisik aset laboratorium Network Operation Center SMK Negeri 4 Malang.
                </p>
                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-gray-500 pt-1">
                    <div>Klasifikasi: <span class="font-semibold text-gray-700">Dokumen Resmi Internal</span></div>
                    <span class="text-gray-300 hidden sm:inline">•</span>
                    <div>Otorisasi: <span class="font-semibold text-gray-700">Kepala Laboratorium NOC</span></div>
                    <span class="text-gray-300 hidden sm:inline">•</span>
                    <div>Pembaruan Terakhir: <span class="font-semibold text-gray-700">September 2026</span></div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex sm:flex-col items-stretch gap-2 shrink-0">
                <button type="button" onclick="window.print()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-xl text-xs font-semibold shadow-2xs transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] text-gray-600">print</span>
                    <span>Cetak Dokumen / PDF</span>
                </button>
                <button type="button" onclick="expandAllFaq()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">unfold_more</span>
                    <span id="expandFaqBtnText">Buka Seluruh FAQ</span>
                </button>
            </div>
        </div>

        <!-- Filter & Realtime Search Bar -->
        <div class="mt-6 pt-5 border-t border-gray-100">
            <div class="relative max-w-2xl">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[20px] pointer-events-none">search</span>
                <input type="text" 
                       id="docSearchInput"
                       placeholder="Cari bab panduan, SOP, istilah teknis, alur modul, atau pertanyaan FAQ..." 
                       class="w-full pl-10 pr-10 py-2.5 bg-gray-50 hover:bg-white focus:bg-white border border-gray-200 focus:border-blue-500 rounded-xl text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-3 focus:ring-blue-100 transition-all">
                <button type="button" id="clearSearchBtn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs font-bold">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
            <div id="searchResultsCount" class="hidden text-xs text-gray-500 mt-2 font-medium"></div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- MAIN DUAL-COLUMN DOCUMENTATION LAYOUT -->
    <!-- ================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- ============================================================= -->
        <!-- LEFT COLUMN: STICKY TABLE OF CONTENTS (4 Cols) -->
        <!-- ============================================================= -->
        <aside class="lg:col-span-3 lg:sticky lg:top-4 z-20 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-2">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-blue-600 text-[17px]">list_alt</span>
                        Daftar Isi Manual
                    </h2>
                    <span class="text-[10px] font-mono text-gray-400 font-semibold">9 Bab</span>
                </div>

                <!-- Navigation Tree -->
                <nav id="docNavTree" class="space-y-0.5 max-h-[calc(100vh-220px)] overflow-y-auto pr-1 text-xs">
                    <a href="#sec-1" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">1.0</span>
                        <span class="truncate">Pendahuluan & Matriks Peran</span>
                    </a>
                    <a href="#sec-2" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">2.0</span>
                        <span class="truncate">Manajemen Data Barang</span>
                    </a>
                    <a href="#sec-3" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">3.0</span>
                        <span class="truncate">Peminjaman & Sirkulasi Alat</span>
                    </a>
                    <a href="#sec-4" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">4.0</span>
                        <span class="truncate">Pengadaan Alat (Procurement)</span>
                    </a>
                    <a href="#sec-5" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">5.0</span>
                        <span class="truncate">Pemeliharaan & Servis Alat</span>
                    </a>
                    <a href="#sec-6" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">6.0</span>
                        <span class="truncate">Rekonsiliasi & Stok Opname</span>
                    </a>
                    <a href="#sec-7" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">7.0</span>
                        <span class="truncate">Audit Trail & Laporan</span>
                    </a>
                    <a href="#sec-8" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">8.0</span>
                        <span class="truncate">SOP Penggunaan Alat Vital</span>
                    </a>
                    <a href="#sec-9" class="doc-nav-link flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-blue-700 transition-colors font-medium">
                        <span class="text-[11px] font-mono text-gray-400 w-6">9.0</span>
                        <span class="truncate">Tanya Jawab Teknis (FAQ)</span>
                    </a>
                </nav>
            </div>

            <!-- Help Desk Contact Card -->
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 text-xs text-gray-600 space-y-2">
                <div class="font-bold text-gray-800 flex items-center gap-1.5 uppercase tracking-wider text-[11px]">
                    <span class="material-symbols-outlined text-[16px] text-gray-600">headset_mic</span>
                    Bantuan & Dukungan Teknis
                </div>
                <p class="text-[11px] leading-relaxed text-gray-500">
                    Bila menemukan inkonsistensi sistem atau butuh reset hak akses, hubungi Administrator Laboratorium NOC SMKN 4 Malang.
                </p>
                <div class="pt-2 border-t border-gray-200/80 font-mono text-[11px] text-gray-700 space-y-0.5">
                    <div>Lokasi: Gedung B Lt. 2 - Ruang NOC</div>
                    <div>Email: noc@smkn4malang.sch.id</div>
                </div>
            </div>
        </aside>

        <!-- ============================================================= -->
        <!-- RIGHT COLUMN: DETAILED DOCUMENTATION CONTENT (8 Cols) -->
        <!-- ============================================================= -->
        <main class="lg:col-span-9 space-y-8">

            <!-- ========================================================= -->
            <!-- BAB 1: PENDAHULUAN & MATRIKS PERAN -->
            <!-- ========================================================= -->
            <section id="sec-1" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 1.0</div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                        Pendahuluan, Ruang Lingkup, & Matriks Otorisasi Peran
                    </h2>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-4 leading-relaxed">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-4">1.1 Latar Belakang & Tujuan Sistem</h3>
                    <p>
                        Sistem Enterprise Resource Planning (ERP) Laboratorium Network Operation Center (NOC) SMKN 4 Malang merupakan perangkat lunak tata kelola terintegrasi untuk mengadministrasikan seluruh aset fisik teknologi informasi, perangkat jaringan berkecepatan tinggi, perkakas praktikum, perputaran sirkulasi peminjaman, serta siklus hidup pengadaan dan pemeliharaan alat sekolah.
                    </p>

                    <!-- Matriks Peran (Role Matrix Table) -->
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-6">1.2 Matriks Hak Akses & Otorisasi Pengguna</h3>
                    <p>
                        Sistem membatasi akses berdasarkan peran fungsional pengguna untuk menjamin integritas data dan pemisahan wewenang operasional:
                    </p>

                    <div class="overflow-x-auto my-3">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 text-xs">
                            <thead class="bg-gray-50 text-gray-700 font-bold uppercase tracking-wider text-[11px]">
                                <tr>
                                    <th scope="col" class="px-4 py-2.5 text-left border-r border-gray-200">Fungsi / Modul</th>
                                    <th scope="col" class="px-3 py-2.5 text-center border-r border-gray-200">Superadmin</th>
                                    <th scope="col" class="px-3 py-2.5 text-center border-r border-gray-200">Admin Lab</th>
                                    <th scope="col" class="px-3 py-2.5 text-center border-r border-gray-200">Perwakilan Jurusan</th>
                                    <th scope="col" class="px-3 py-2.5 text-center">Siswa / Guru (Umum)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-700">
                                <tr>
                                    <td class="px-4 py-2 font-medium border-r border-gray-200">Katalog Barang & Status Ketersediaan</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Penuh (CRUD)</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Penuh (CRUD)</td>
                                    <td class="px-3 py-2 text-center text-blue-700 border-r border-gray-200">Baca (Read-Only)</td>
                                    <td class="px-3 py-2 text-center text-blue-700">Baca via QR</td>
                                </tr>
                                <tr class="bg-gray-50/50">
                                    <td class="px-4 py-2 font-medium border-r border-gray-200">Data Master (Ruangan, Jurusan, Kategori, User)</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Penuh (CRUD)</td>
                                    <td class="px-3 py-2 text-center text-gray-400 border-r border-gray-200">Tidak Ada</td>
                                    <td class="px-3 py-2 text-center text-gray-400 border-r border-gray-200">Tidak Ada</td>
                                    <td class="px-3 py-2 text-center text-gray-400">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium border-r border-gray-200">Peminjaman & Validasi Pengembalian Alat</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Penuh</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Penuh</td>
                                    <td class="px-3 py-2 text-center text-blue-700 border-r border-gray-200">Data Sendiri</td>
                                    <td class="px-3 py-2 text-center text-blue-700">Scan QR Pinjam</td>
                                </tr>
                                <tr class="bg-gray-50/50">
                                    <td class="px-4 py-2 font-medium border-r border-gray-200">Pengajuan Usulan Pengadaan (Procurement)</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Penuh</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Buat & Terima</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Buat Usulan</td>
                                    <td class="px-3 py-2 text-center text-gray-400">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium border-r border-gray-200">Persetujuan / Penolakan Pengadaan</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-bold border-r border-gray-200">Wewenang Penuh</td>
                                    <td class="px-3 py-2 text-center text-gray-400 border-r border-gray-200">Tidak Ada</td>
                                    <td class="px-3 py-2 text-center text-gray-400 border-r border-gray-200">Tidak Ada</td>
                                    <td class="px-3 py-2 text-center text-gray-400">Tidak Ada</td>
                                </tr>
                                <tr class="bg-gray-50/50">
                                    <td class="px-4 py-2 font-medium border-r border-gray-200">Stok Opname & Rekonsiliasi Fisik</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Inisiasi & Finalisasi</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Perekaman Fisik</td>
                                    <td class="px-3 py-2 text-center text-gray-400 border-r border-gray-200">Tidak Ada</td>
                                    <td class="px-3 py-2 text-center text-gray-400">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium border-r border-gray-200">Audit Trail & Rekapitulasi Laporan</td>
                                    <td class="px-3 py-2 text-center text-emerald-700 font-semibold border-r border-gray-200">Penuh</td>
                                    <td class="px-3 py-2 text-center text-blue-700 border-r border-gray-200">Laporan Saja</td>
                                    <td class="px-3 py-2 text-center text-gray-400 border-r border-gray-200">Tidak Ada</td>
                                    <td class="px-3 py-2 text-center text-gray-400">Tidak Ada</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Callout Info Standar Industri -->
                    <div class="p-3.5 bg-blue-50/70 border-l-4 border-blue-600 rounded-r-xl text-xs text-blue-900 mt-4 space-y-1">
                        <div class="font-bold text-blue-950 flex items-center gap-1.5 uppercase tracking-wide">
                            <span class="material-symbols-outlined text-[16px] text-blue-700">info</span>
                            Ketentuan Otorisasi Tingkat Tinggi
                        </div>
                        <p class="leading-relaxed text-blue-900">
                            Peran <strong>Superadmin</strong> dipegang oleh Kepala Laboratorium NOC dan Administrator Utama SMKN 4 Malang. Seluruh tindakan berisiko tinggi (penghapusan master data, pembatalan audit, dan persetujuan pengadaan bernilai besar) terekam secara permanen dalam sistem Audit Trail.
                        </p>
                    </div>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- BAB 2: MANAJEMEN DATA BARANG & INVENTARIS -->
            <!-- ========================================================= -->
            <section id="sec-2" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 2.0</div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                        Tata Kelola Data Barang, Penomoran Kode, & Mutasi
                    </h2>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-4 leading-relaxed">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-2">2.1 Standarisasi Penomoran Kode Barang</h3>
                    <p>
                        Setiap unit barang yang masuk ke dalam sistem wajib memiliki kode identifikasi unik (Asset Tagging Code). Format struktur pengkodean mengacu pada aturan berikut:
                    </p>

                    <div class="bg-gray-900 text-gray-100 p-4 rounded-xl font-mono text-xs space-y-2 border border-gray-800">
                        <div class="text-gray-400">// Format Baku: [PREFIX_KATEGORI]-[NOMOR_URUT_4_DIGIT]</div>
                        <div class="text-emerald-400">Contoh Jaringan: <span class="text-white">NET-0042</span> (Switch Managed Cisco CBS250)</div>
                        <div class="text-emerald-400">Contoh Komputer : <span class="text-white">PC-0015</span> (Server Dell PowerEdge R450)</div>
                        <div class="text-emerald-400">Contoh Perkakas : <span class="text-white">TLS-0108</span> (Fusion Splicer Fiber Optik)</div>
                    </div>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-6">2.2 Siklus Kondisi Fisik Barang</h3>
                    <p>
                        Sistem mengklasifikasikan kondisi fisik barang ke dalam 4 tingkatan baku yang mempengaruhi ketersediaan operasional:
                    </p>
                    <ul class="list-disc pl-5 space-y-1 text-xs">
                        <li><strong>Baik (Ready to Use)</strong>: Barang berada dalam performa prima, telah lolos uji kelayakan, dan siap dipinjamkan atau digunakan untuk pembelajaran.</li>
                        <li><strong>Rusak Ringan (Minor Fault)</strong>: Terdapat penurunan fungsi atau kerusakan minor yang tidak mematikan alat secara total. Memerlukan penjadwalan servis preventif.</li>
                        <li><strong>Rusak Berat (Non-Operational)</strong>: Unit tidak dapat menyala atau tidak dapat menjalankan fungsinya. Status barang otomatis dialihkan ke status <em>Perlu Servis / Maintenance</em> dan tidak dapat dipilih dalam form peminjaman.</li>
                        <li><strong>Hilang / Afkir</strong>: Barang tidak ditemukan pada saat audit fisik atau telah dinyatakan rusak permanen tanpa nilai ekonomis perbaikan.</li>
                    </ul>

                    <!-- Prosedur Input Barang Baru -->
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-6">2.3 Prosedur Input Penerimaan Barang Baru</h3>
                    <ol class="list-decimal pl-5 space-y-2 text-xs">
                        <li>Buka menu <strong>Data Barang</strong> pada navigasi samping.</li>
                        <li>Klik tombol <strong>+ Tambah Barang</strong> pada bilah aksi kanan atas.</li>
                        <li>Pilih <strong>Kategori</strong> yang relevan untuk memastikan generator kode menghasilkan prefix yang presisi.</li>
                        <li>Masukkan <strong>Nama Barang</strong>, <strong>Merk / Model</strong>, serta <strong>Serial Number (S/N)</strong> pabrikan unit.</li>
                        <li>Tentukan <strong>Lokasi Ruangan Penempatan</strong> (misal: Rack Server A, Lab TKJ 1, atau Lemari Perkakas).</li>
                        <li>Tentukan <strong>Kondisi Fisik</strong> awal dan nilai <strong>Harga Pembelian</strong> untuk akurasi valuasi aset sekolah.</li>
                        <li>Klik <strong>Simpan Data Barang</strong>. Sistem akan otomatis merekam log mutasi awal (Barang Masuk) dan mencetak QR Code aset.</li>
                    </ol>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- BAB 3: PEMINJAMAN & PENGEMBALIAN ALAT -->
            <!-- ========================================================= -->
            <section id="sec-3" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 3.0</div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                        Prosedur Peminjaman & Pengembalian Aset Laboratorium
                    </h2>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-4 leading-relaxed">
                    <p>
                        Sirkulasi peminjaman alat laboratorium dirancang efisien dengan metode verifikasi ganda: <strong>Pindai QR Code</strong> atau <strong>Input Manual Admin</strong>.
                    </p>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-4">3.1 Alur Peminjaman Melalui QR Code Scanner</h3>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 text-xs space-y-2">
                        <div class="font-bold text-gray-800">Alur Operasional:</div>
                        <div class="flex items-center gap-2 flex-wrap text-gray-600 font-mono">
                            <span class="px-2 py-1 bg-white border border-gray-200 rounded">1. Peminjam Buka URL QR</span>
                            <span>&rarr;</span>
                            <span class="px-2 py-1 bg-white border border-gray-200 rounded">2. Scan QR Barang</span>
                            <span>&rarr;</span>
                            <span class="px-2 py-1 bg-white border border-gray-200 rounded">3. Isi Form Pinjam</span>
                            <span>&rarr;</span>
                            <span class="px-2 py-1 bg-white border border-gray-200 rounded">4. Konfirmasi Admin</span>
                        </div>
                        <p class="text-gray-500 pt-1 text-[11px]">
                            Metode ini memangkas waktu antrian di laboratorium dan memastikan barang yang dipinjam diverifikasi secara fisik melalui label QR pada unit barang.
                        </p>
                    </div>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-6">3.2 Prosedur Validasi Pengembalian Barang</h3>
                    <ol class="list-decimal pl-5 space-y-2 text-xs">
                        <li>Peminjam membawa fisik alat ke ruang teknisi NOC SMKN 4 Malang.</li>
                        <li>Petugas Admin memeriksa fisik barang, kelengkapan kabel, power adapter, port, dan aksesoris.</li>
                        <li>Admin membuka menu <strong>Data Peminjaman</strong> pada sistem ERP.</li>
                        <li>Cari data peminjaman berdasarkan nama peminjam atau kode barang yang tertera.</li>
                        <li>Klik tombol <strong>Kembalikan Barang</strong>.</li>
                        <li>Tentukan status kondisi barang saat diterima (<em>Baik</em> atau <em>Perlu Servis / Rusak</em>).</li>
                        <li>Klik tombol <strong>Konfirmasi Pengembalian</strong>. Stok barang akan otomatis kembali bertambah di katalog aktif.</li>
                    </ol>

                    <div class="p-3.5 bg-amber-50/80 border-l-4 border-amber-600 rounded-r-xl text-xs text-amber-950 mt-4 space-y-1">
                        <div class="font-bold text-amber-950 flex items-center gap-1.5 uppercase tracking-wide">
                            <span class="material-symbols-outlined text-[16px] text-amber-700">warning</span>
                            Ketentuan Jika Barang Rusak / Tidak Lengkap
                        </div>
                        <p class="leading-relaxed">
                            Bila barang dikembalikan dalam kondisi rusak atau komponen hilang, petugas <strong>wajib mencentang opsi barang rusak</strong> pada dialog pengembalian dan mencatat keterangan kerusakan. Sistem akan mengarahkan barang tersebut langsung ke modul pemeliharaan (*Perawatan*) dan mencatat penanggung jawab terakhir.
                        </p>
                    </div>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- BAB 4: PENGADAAN ALAT (PROCUREMENT) -->
            <!-- ========================================================= -->
            <section id="sec-4" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 4.0</div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                        Siklus Pengajuan & Persetujuan Pengadaan Alat (Procurement)
                    </h2>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-4 leading-relaxed">
                    <p>
                        Modul Pengadaan Alat mengakomodasi proses perencanaan kebutuhan belanja modal laboratorium, mulai dari pengajuan proposal kebutuhan oleh Jurusan, evaluasi kelayakan teknis dan anggaran oleh Superadmin, hingga realisasi penerimaan barang fisik di laboratorium.
                    </p>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-4">4.1 Tahapan Status Pengadaan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-2.5 text-xs">
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl space-y-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-800">DRAFT</span>
                            <div class="font-bold text-gray-800 text-[11px]">Draf Usulan</div>
                            <p class="text-[10px] text-gray-500">Usulan masih disusun dan dapat diedit sewaktu-waktu oleh pemohon.</p>
                        </div>
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl space-y-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-200 text-amber-800">PENDING</span>
                            <div class="font-bold text-amber-900 text-[11px]">Menunggu Approval</div>
                            <p class="text-[10px] text-amber-700">Proposal telah disubmit dan menunggu review Kepala Lab / Superadmin.</p>
                        </div>
                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl space-y-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-200 text-emerald-800">APPROVED</span>
                            <div class="font-bold text-emerald-900 text-[11px]">Disetujui</div>
                            <p class="text-[10px] text-emerald-700">Proposal disetujui untuk dialokasikan anggaran belanjanya.</p>
                        </div>
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl space-y-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-200 text-blue-800">IN PROGRESS</span>
                            <div class="font-bold text-blue-900 text-[11px]">Proses Pembelian</div>
                            <p class="text-[10px] text-blue-700">Penerbitan PO dan pemesanan ke vendor resmi sarana sekolah.</p>
                        </div>
                        <div class="p-3 bg-indigo-50 border border-indigo-200 rounded-xl space-y-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-200 text-indigo-800">COMPLETED</span>
                            <div class="font-bold text-indigo-900 text-[11px]">Terealisasi</div>
                            <p class="text-[10px] text-indigo-700">Seluruh fisik barang telah diterima dan dicatat ke inventaris.</p>
                        </div>
                    </div>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-6">4.2 Prosedur Penerimaan Barang & Auto-Konversi Inventaris</h3>
                    <p>
                        Ketika barang pesanan tiba di gudang NOC, Superadmin atau Admin dapat membuka halaman detail pengajuan, lalu menekan tombol <strong>Terima Barang</strong>:
                    </p>
                    <ul class="list-disc pl-5 space-y-1.5 text-xs">
                        <li>Masukkan kuantitas fisik unit yang diterima secara nyata (*Goods Receipt Count*).</li>
                        <li>Centang opsi <strong>"Konversi langsung ke Data Inventaris Barang"</strong>.</li>
                        <li>Pilih <strong>Lokasi Ruangan</strong> penempatan aset baru dan kondisi fisik saat serah terima.</li>
                        <li>Sistem akan secara otomatis menerbitkan kode barang baru, menambah stok di katalog inventaris, dan mencatat mutasi barang masuk dengan jenis <em>Pengadaan</em>.</li>
                    </ul>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- BAB 5: PEMELIHARAAN & PERAWATAN ALAT -->
            <!-- ========================================================= -->
            <section id="sec-5" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 5.0</div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                        Pemeliharaan, Servis Preventif, & Pelaporan Kerusakan
                    </h2>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-4 leading-relaxed">
                    <p>
                        Modul Perawatan menjamin usia pakai (*life-cycle*) peralatan jaringan dan komputasi laboratorium tetap optimal melalui pemeliharaan berkala serta penanganan kerusakan insidental.
                    </p>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-4">5.1 Alur Penerbitan Tiket Perawatan</h3>
                    <ol class="list-decimal pl-5 space-y-1.5 text-xs">
                        <li>Buka menu <strong>Data Perawatan</strong> pada bilah navigasi utama.</li>
                        <li>Klik tombol <strong>+ Tambah Data Perawatan</strong>.</li>
                        <li>Pilih unit barang yang bermasalah. Status barang tersebut otomatis ditandai sedang dalam perawatan (*Maintenance*).</li>
                        <li>Uraikan detail indikasi kerusakan (misal: <em>Port 4-8 pada switch mati, kipas power supply server bising</em>).</li>
                        <li>Pilih jenis perawatan: <strong>Internal Lab</strong> (dikerjakan teknisi NOC) atau <strong>Pihak Ketiga / Garansi Vendor</strong>.</li>
                        <li>Estimasi target tanggal penyelesaian perbaikan.</li>
                    </ol>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-6">5.2 Penyelesaian Servis & Verifikasi Kelayakan</h3>
                    <p>
                        Setelah perbaikan selesai dilakukan, petugas wajib menekan tombol <strong>Verifikasi Perawatan</strong>, mencatat biaya riil (jika ada), dan mengembalikan kondisi barang menjadi <em>Baik</em> sehingga dapat langsung dialokasikan kembali untuk praktikum.
                    </p>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- BAB 6: STOK OPNAME & REKONSILIASI FISIK -->
            <!-- ========================================================= -->
            <section id="sec-6" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 6.0</div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                        Prosedur Stok Opname & Rekonsiliasi Fisik Aset
                    </h2>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-4 leading-relaxed">
                    <p>
                        Stok Opname (*Physical Stock Count*) adalah prosedur audit verifikasi berkala untuk mencocokkan jumlah kuantitas fisik aset di ruangan dengan data yang tercatat pada basis data ERP.
                    </p>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-4">6.1 Langkah Pelaksanaan Sesi Audit</h3>
                    <div class="space-y-3 text-xs">
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl space-y-1">
                            <div class="font-bold text-gray-900">Tahap 1: Inisiasi Sesi Opname (Oleh Superadmin)</div>
                            <p class="text-gray-600">Buka menu <strong>Stok Opname</strong> &rarr; <strong>Mulai Sesi Baru</strong>. Tentukan ruang lingkup audit (ruangan spesifik atau seluruh laboratorium sekolah). Sistem akan mengunci *snapshot* kuantitas sistem.</p>
                        </div>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl space-y-1">
                            <div class="font-bold text-gray-900">Tahap 2: Verifikasi Hitung Fisik Lapangan</div>
                            <p class="text-gray-600">Teknisi membuka lembar kerja stok opname, mencocokkan nomor seri, dan memasukkan jumlah fisik yang ditemukan di rak atau lemari.</p>
                        </div>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl space-y-1">
                            <div class="font-bold text-gray-900">Tahap 3: Deteksi Selisih (Discrepancy Resolution)</div>
                            <p class="text-gray-600">Sistem secara otomatis menghitung variansi (`Selisih = Fisik - Sistem`). Barang yang kurang ditandai dengan peringatan merah dan memerlukan justifikasi investigasi.</p>
                        </div>
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl space-y-1">
                            <div class="font-bold text-gray-900">Tahap 4: Finalisasi & Penyesuaian Saldo Aset</div>
                            <p class="text-gray-600">Superadmin menekan tombol <strong>Selesaikan Sesi Stok Opname</strong>. Data stok sistem akan disesuaikan dengan hasil hitung fisik yang telah diverifikasi.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- BAB 7: AUDIT TRAIL & LAPORAN -->
            <!-- ========================================================= -->
            <section id="sec-7" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 7.0</div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                        Audit Trail Kepatuhan & Ekspor Laporan Rekapitulasi
                    </h2>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-4 leading-relaxed">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-2">7.1 Sistem Rekam Jejak Aktivitas (Audit Trail)</h3>
                    <p>
                        Untuk memenuhi standar akuntabilitas operasional laboratorium, setiap transaksi pencatatan, mutasi, edit data, persetujuan anggaran, dan login pengguna direkam dalam tabel <code>activity_logs</code>. Log ini mencakup identitas pengguna, alamat IP, timestamp waktu nyata, serta rincian payload data lama dan data baru.
                    </p>

                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mt-6">7.2 Ekspor Dokumen Resmi</h3>
                    <p>
                        Pengguna berwenang dapat mencetak atau mengunduh data dalam berbagai format standar pelaporan sekolah:
                    </p>
                    <ul class="list-disc pl-5 space-y-1 text-xs">
                        <li><strong>Laporan Mutasi Barang Masuk & Keluar</strong>: Format CSV / Excel untuk integrasi akuntansi sarpras.</li>
                        <li><strong>Berita Acara Cetak Resmi</strong>: Dilengkapi kop surat resmi SMK Negeri 4 Malang, nomor surat otomatis, dan kolom tanda tangan Kepala Laboratorium & Waka Sarpras.</li>
                    </ul>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- BAB 8: SOP PENGGUNAAN ALAT VITAL -->
            <!-- ========================================================= -->
            <section id="sec-8" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5">
                    <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 8.0</div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                        Standar Operasional Prosedur (SOP) Alat Vital Laboratorium
                    </h2>
                </div>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-4 leading-relaxed">
                    <p>
                        Peralatan vital dengan sensitivitas tinggi (seperti Fusion Splicer Fiber Optik, Optical Time Domain Reflectometer / OTDR, Cisco Managed Core Switch, dan Rack Server) memiliki aturan penanganan khusus:
                    </p>

                    <div class="space-y-3 text-xs">
                        <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl">
                            <div class="font-bold text-gray-900">SOP-01: Peminjaman Fusion Splicer & OTDR</div>
                            <p class="text-gray-600 mt-1">Hanya dapat dipinjamkan atas rekomendasi tertulis Guru Pembimbing Praktikum Jaringan. Dilarang membersihkan elektroda splicer tanpa pengawasan teknisi laboratorium bersertifikasi.</p>
                        </div>
                        <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl">
                            <div class="font-bold text-gray-900">SOP-02: Akses Fisik Ruang Server NOC</div>
                            <p class="text-gray-600 mt-1">Ruang server terkunci 24/7. Seluruh kegiatan maintenance server produksi atau penarikan kabel patch cord wajib dicatat pada buku mutasi ruangan atau log audit ERP.</p>
                        </div>
                        <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-xl">
                            <div class="font-bold text-gray-900">SOP-03: Prosedur Pemadaman Listrik & Perlindungan UPS</div>
                            <p class="text-gray-600 mt-1">Bila terjadi pemadaman listrik dari PLN melebihi kapasitas backup UPS online (30 menit), teknisi wajib melakukan prosedur safe shutdown server virtualisasi sesuai urutan hierarki sistem.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ========================================================= -->
            <!-- BAB 9: TANYA JAWAB TEKNIS (FAQ) -->
            <!-- ========================================================= -->
            <section id="sec-9" class="doc-section bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs scroll-mt-6">
                <div class="border-b border-gray-100 pb-4 mb-5 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-mono font-bold text-blue-700 uppercase tracking-wider">Bagian 9.0</div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight mt-0.5">
                            Tanya Jawab Teknis & Pemecahan Masalah (FAQ)
                        </h2>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg">8 Pertanyaan</span>
                </div>

                <!-- FAQ Accordion List -->
                <div class="space-y-3" id="faqAccordionContainer">

                    <!-- FAQ 1 -->
                    <div class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all">
                        <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between gap-3 text-xs font-bold text-gray-800 transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="font-mono text-blue-700">Q1.</span>
                                <span>Bagaimana jika label QR Code pada unit barang rusak atau tidak dapat dipindai oleh kamera smartphone?</span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform duration-200 faq-icon">expand_more</span>
                        </button>
                        <div class="faq-content hidden p-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white space-y-2">
                            <p>
                                Petugas atau peminjam dapat menggunakan <strong>Metode Pencarian Kode Manual</strong>. Cek kode alfanumerik yang tertera pada badan barang (misal: <code>NET-0042</code>). Masukkan kode tersebut pada kolom pencarian menu <em>Data Barang</em> atau menu <em>Input Pinjaman</em> untuk memproses transaksi tanpa kendala.
                            </p>
                            <p class="text-blue-700 text-[11px] font-medium">
                                Rekomendasi teknis: Admin dapat mencetak ulang stiker QR Code pengganti melalui menu Detail Barang &rarr; Cetak Label QR.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all">
                        <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between gap-3 text-xs font-bold text-gray-800 transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="font-mono text-blue-700">Q2.</span>
                                <span>Siapa yang memiliki wewenang menyetujui (approve) proposal pengajuan pengadaan alat baru?</span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform duration-200 faq-icon">expand_more</span>
                        </button>
                        <div class="faq-content hidden p-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white">
                            <p>
                                Otoritas persetujuan (Approval) mutlak berada pada peran <strong>Superadmin</strong> (Kepala Laboratorium NOC SMKN 4 Malang). Perwakilan Jurusan hanya memiliki hak akses untuk menyusun draf dan mengirimkan (submit) usulan kebutuhan alat.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all">
                        <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between gap-3 text-xs font-bold text-gray-800 transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="font-mono text-blue-700">Q3.</span>
                                <span>Apa yang harus dilakukan jika pengajuan pengadaan ditolak (*Rejected*)?</span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform duration-200 faq-icon">expand_more</span>
                        </button>
                        <div class="faq-content hidden p-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white space-y-2">
                            <p>
                                Pemohon dapat memeriksa <strong>Alasan Penolakan</strong> yang dicatat oleh Superadmin pada lembar detail pengajuan. Status usulan yang ditolak dapat diedit kembali untuk diperbaiki spesifikasinya, disesuaikan jumlahnya, atau dikurangi estimasi anggarannya, kemudian diajukan ulang (*re-submit*).
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all">
                        <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between gap-3 text-xs font-bold text-gray-800 transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="font-mono text-blue-700">Q4.</span>
                                <span>Bagaimana menangani barang praktikum yang hilang saat dipinjam siswa?</span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform duration-200 faq-icon">expand_more</span>
                        </button>
                        <div class="faq-content hidden p-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white space-y-2">
                            <p>
                                Petugas Admin membuka transaksi peminjaman siswa yang bersangkutan. Pada saat verifikasi pengembalian, centang opsi <strong>"Barang Hilang"</strong>. Sistem akan:
                            </p>
                            <ol class="list-decimal pl-4 space-y-1">
                                <li>Mencatat berita acara kehilangan pada log audit sistem.</li>
                                <li>Mengurangi jumlah stok barang secara otomatis di katalog inventaris.</li>
                                <li>Menerbitkan surat pemberitahuan pertanggungjawaban penggantian unit kepada Guru Pembimbing Jurusan siswa tersebut.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all">
                        <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between gap-3 text-xs font-bold text-gray-800 transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="font-mono text-blue-700">Q5.</span>
                                <span>Kapan sesi Stok Opname wajib diselenggarakan di laboratorium?</span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform duration-200 faq-icon">expand_more</span>
                        </button>
                        <div class="faq-content hidden p-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white">
                            <p>
                                Sesuai pedoman baku mutu SMKN 4 Malang, Stok Opname aset laboratorium wajib dilaksanakan minimal **dua kali dalam satu tahun ajaran** (pada akhir semester ganjil dan akhir semester genap) atau sewaktu-waktu jika terjadi pergantian Kepala Laboratorium.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 6 -->
                    <div class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all">
                        <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between gap-3 text-xs font-bold text-gray-800 transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="font-mono text-blue-700">Q6.</span>
                                <span>Bagaimana cara memindahkan tombol terapung "Pintasan Cepat ERP" yang menutupi konten?</span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform duration-200 faq-icon">expand_more</span>
                        </button>
                        <div class="faq-content hidden p-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white">
                            <p>
                                Tombol bundar <strong>Pintasan Cepat ERP</strong> bersifat *Draggable*. Anda cukup menahan klik mouse (atau jari pada layar sentuh), lalu menggesernya ke sudut layar mana saja yang nyaman. Posisi baru tombol akan tersimpan otomatis di peramban Anda. Untuk mengembalikannya ke posisi awal, klik tombol tersebut lalu tekan opsi <em>Reset Posisi</em> di bagian bawah menu popup.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 7 -->
                    <div class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all">
                        <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between gap-3 text-xs font-bold text-gray-800 transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="font-mono text-blue-700">Q7.</span>
                                <span>Apakah data peminjaman masa lalu dapat dihapus dari sistem?</span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform duration-200 faq-icon">expand_more</span>
                        </button>
                        <div class="faq-content hidden p-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white">
                            <p>
                                Demi integritas audit kepatuhan ISO 9001 laboratorium, riwayat peminjaman yang telah selesai <strong>tidak disarankan untuk dihapus</strong>. Seluruh riwayat transaksi diarsipkan sebagai rekam jejak statistik utilisasi sarana praktikum sekolah. Hanya transaksi draf / salah input yang diizinkan dihapus oleh Superadmin.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 8 -->
                    <div class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all">
                        <button type="button" onclick="toggleFaq(this)" class="w-full text-left p-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between gap-3 text-xs font-bold text-gray-800 transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="font-mono text-blue-700">Q8.</span>
                                <span>Bagaimana jika akun pengguna terkunci atau lupa kata sandi login?</span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform duration-200 faq-icon">expand_more</span>
                        </button>
                        <div class="faq-content hidden p-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white">
                            <p>
                                Pengguna dapat melapor secara langsung ke Administrator Lab NOC. Superadmin memiliki fitur <em>Reset Kata Sandi</em> pada modul <strong>Data User / Pengguna</strong> untuk menerbitkan kata sandi sementara yang wajib diperbarui saat login pertama kali.
                            </p>
                        </div>
                    </div>

                </div>
            </section>

        </main>
    </div>

</div>

<!-- Style Dokumen Resmi -->
<style>
html {
    scroll-behavior: smooth;
}

@media print {
    /* Sembunyikan elemen navigasi browser & sidebar */
    #mainSidebar, 
    header, 
    #erpQuickWidgetContainer, 
    aside,
    button, 
    .no-print {
        display: none !important;
    }

    body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 11pt !important;
    }

    .doc-section {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin-bottom: 24pt !important;
        page-break-inside: avoid;
    }

    /* Buka seluruh FAQ saat print */
    .faq-content {
        display: block !important;
    }
}
</style>

<script>
(function() {
    // 1. Logika Real-time Instant Search
    const searchInput = document.getElementById('docSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const resultsCount = document.getElementById('searchResultsCount');
    const sections = document.querySelectorAll('.doc-section');
    const faqItems = document.querySelectorAll('.faq-item');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            
            if (clearBtn) {
                clearBtn.classList.toggle('hidden', query === '');
            }

            if (query === '') {
                sections.forEach(sec => sec.classList.remove('hidden'));
                faqItems.forEach(item => {
                    item.classList.remove('hidden');
                    // Reset faq content state jika tidak sedang dibuka
                });
                if (resultsCount) resultsCount.classList.add('hidden');
                return;
            }

            let matchesCount = 0;

            // Cari di sections
            sections.forEach(sec => {
                const text = sec.innerText.toLowerCase();
                const matches = text.includes(query);
                sec.classList.toggle('hidden', !matches);
                if (matches) matchesCount++;
            });

            // Khusus FAQ: buka accordion jika query cocok
            faqItems.forEach(item => {
                const text = item.innerText.toLowerCase();
                const matches = text.includes(query);
                item.classList.toggle('hidden', !matches);
                if (matches) {
                    const content = item.querySelector('.faq-content');
                    const icon = item.querySelector('.faq-icon');
                    if (content) content.classList.remove('hidden');
                    if (icon) icon.textContent = 'expand_less';
                    matchesCount++;
                }
            });

            if (resultsCount) {
                resultsCount.classList.remove('hidden');
                resultsCount.textContent = `Ditemukan ${matchesCount} bagian dokumen yang memuat kata kunci "${query}".`;
            }
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });
        }
    }

    // 2. Active Scrollspy & Smooth Scroll untuk Daftar Isi
    const navLinks = document.querySelectorAll('.doc-nav-link');

    // Smooth scroll saat mengeklik Daftar Isi Bab
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (!targetId || targetId === '#') return;
            const targetEl = document.querySelector(targetId);
            if (targetEl) {
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                history.pushState(null, null, targetId);
            }
        });
    });

    // 2. Active Scrollspy untuk Daftar Isi
    window.addEventListener('scroll', function() {
        let currentSectionId = '';
        sections.forEach(sec => {
            const secTop = sec.offsetTop - 120;
            if (window.pageYOffset >= secTop) {
                currentSectionId = sec.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            const href = link.getAttribute('href').replace('#', '');
            if (href === currentSectionId) {
                link.classList.add('bg-blue-50', 'text-blue-700', 'font-bold');
                link.classList.remove('text-gray-600');
            } else {
                link.classList.remove('bg-blue-50', 'text-blue-700', 'font-bold');
                link.classList.add('text-gray-600');
            }
        });
    }, { passive: true });

})();

// 3. Fungsi Toggle Accordion FAQ
function toggleFaq(button) {
    const item = button.closest('.faq-item');
    if (!item) return;
    const content = item.querySelector('.faq-content');
    const icon = item.querySelector('.faq-icon');

    const isHidden = content.classList.contains('hidden');
    if (isHidden) {
        content.classList.remove('hidden');
        if (icon) icon.textContent = 'expand_less';
    } else {
        content.classList.add('hidden');
        if (icon) icon.textContent = 'expand_more';
    }
}

// 4. Buka / Tutup Seluruh FAQ Sekaligus
let allFaqExpanded = false;
function expandAllFaq() {
    allFaqExpanded = !allFaqExpanded;
    const contents = document.querySelectorAll('.faq-content');
    const icons = document.querySelectorAll('.faq-icon');
    const btnText = document.getElementById('expandFaqBtnText');

    contents.forEach(c => {
        c.classList.toggle('hidden', !allFaqExpanded);
    });

    icons.forEach(i => {
        i.textContent = allFaqExpanded ? 'expand_less' : 'expand_more';
    });

    if (btnText) {
        btnText.textContent = allFaqExpanded ? 'Tutup Seluruh FAQ' : 'Buka Seluruh FAQ';
    }
}
</script>
@endsection
