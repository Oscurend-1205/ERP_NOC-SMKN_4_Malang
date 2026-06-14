@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<!-- BEGIN: Page Title -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-slate-900">Pengaturan Sistem</h1>
    <p class="text-sm text-slate-500 mt-1">Konfigurasi tema, manajemen database, dan pemeliharaan sistem.</p>
</div>
<!-- END: Page Title -->

@if (session('error'))
<div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-start gap-3">
    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

@if (session('success'))
<div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 flex items-start gap-3">
    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- ============================================================ -->
<!-- SECTION 1: System Info -->
<!-- ============================================================ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- System Information -->
    <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">
                <i data-lucide="info" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900">Informasi Sistem</h2>
                <p class="text-xs text-slate-500">Status dan versi komponen sistem.</p>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-2">
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">PHP</div>
                <div class="text-sm font-bold text-slate-800 mt-1 font-mono">{{ $systemInfo['php_version'] }}</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Laravel</div>
                <div class="text-sm font-bold text-slate-800 mt-1 font-mono">{{ $systemInfo['laravel_version'] }}</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">DB Driver</div>
                <div class="text-sm font-bold text-slate-800 mt-1 font-mono">{{ strtoupper($systemInfo['db_driver']) }}</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Environment</div>
                <div class="text-sm font-bold mt-1 font-mono {{ $systemInfo['app_env'] === 'production' ? 'text-green-600' : 'text-amber-600' }}">{{ ucfirst($systemInfo['app_env']) }}</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Database</div>
                <div class="text-sm font-bold text-slate-800 mt-1 font-mono truncate" title="{{ $systemInfo['db_name'] }}">{{ $systemInfo['db_name'] }}</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Storage Link</div>
                <div class="text-sm font-bold mt-1 font-mono {{ $systemInfo['storage_linked'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $systemInfo['storage_linked'] ? 'Sudah Ada' : 'Belum Ada' }}
                </div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Total Items</div>
                <div class="text-sm font-bold text-indigo-600 mt-1 font-mono">{{ number_format($stats['items']) }}</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Total Users</div>
                <div class="text-sm font-bold text-indigo-600 mt-1 font-mono">{{ number_format($stats['users']) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECTION 2: Database Statistics -->
<!-- ============================================================ -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="flex items-center mb-4">
        <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mr-3 flex-shrink-0">
            <i data-lucide="database" class="w-5 h-5"></i>
        </div>
        <div>
            <h2 class="text-base font-bold text-slate-900">Statistik Database</h2>
            <p class="text-xs text-slate-500">Jumlah data saat ini di setiap tabel utama.</p>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100 text-center">
            <i data-lucide="package" class="w-5 h-5 text-blue-500 mx-auto mb-2"></i>
            <div class="text-xl font-bold text-blue-700 font-mono">{{ number_format($stats['items']) }}</div>
            <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-1">Barang</div>
        </div>
        <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100 text-center">
            <i data-lucide="tags" class="w-5 h-5 text-purple-500 mx-auto mb-2"></i>
            <div class="text-xl font-bold text-purple-700 font-mono">{{ number_format($stats['categories']) }}</div>
            <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-1">Kategori</div>
        </div>
        <div class="bg-teal-50/50 rounded-xl p-4 border border-teal-100 text-center">
            <i data-lucide="map-pin" class="w-5 h-5 text-teal-500 mx-auto mb-2"></i>
            <div class="text-xl font-bold text-teal-700 font-mono">{{ number_format($stats['locations']) }}</div>
            <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-1">Lokasi</div>
        </div>
        <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100 text-center">
            <i data-lucide="users" class="w-5 h-5 text-indigo-500 mx-auto mb-2"></i>
            <div class="text-xl font-bold text-indigo-700 font-mono">{{ number_format($stats['users']) }}</div>
            <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-1">Pengguna</div>
        </div>
        <div class="bg-amber-50/50 rounded-xl p-4 border border-amber-100 text-center">
            <i data-lucide="handshake" class="w-5 h-5 text-amber-500 mx-auto mb-2"></i>
            <div class="text-xl font-bold text-amber-700 font-mono">{{ number_format($stats['peminjaman']) }}</div>
            <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-1">Peminjaman</div>
        </div>
        <div class="bg-rose-50/50 rounded-xl p-4 border border-rose-100 text-center">
            <i data-lucide="arrow-right-left" class="w-5 h-5 text-rose-500 mx-auto mb-2"></i>
            <div class="text-xl font-bold text-rose-700 font-mono">{{ number_format($stats['movements']) }}</div>
            <div class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-1">Mutasi</div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECTION 3: Maintenance & Tools -->
<!-- ============================================================ -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

    <!-- Clear Cache -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 flex-shrink-0">
                <i data-lucide="wind" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900">Bersihkan Cache</h2>
                <p class="text-xs text-slate-500">Hapus cache config, route, view, dan aplikasi.</p>
            </div>
        </div>
        <p class="text-xs text-slate-600 mb-4 flex-grow">Gunakan ini jika perubahan konfigurasi atau tampilan tidak langsung diterapkan. Aman dijalankan kapan saja.</p>
        <form action="{{ route('settings.clear-cache') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-[#005bbf] hover:bg-[#004494] text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm flex items-center justify-center transition-colors text-sm">
                <i data-lucide="trash" class="w-4 h-4 mr-2"></i> Bersihkan Cache
            </button>
        </form>
    </div>

    <!-- Run Migrations -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 flex-shrink-0">
                <i data-lucide="database" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900">Jalankan Migrasi</h2>
                <p class="text-xs text-slate-500">Terapkan migrasi database yang belum dijalankan.</p>
            </div>
        </div>
        <p class="text-xs text-slate-600 mb-4 flex-grow">Gunakan setelah update kode yang mengandung perubahan skema database. Tidak menghapus data yang sudah ada.</p>
        <form action="{{ route('settings.run-migrations') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-[#005bbf] hover:bg-[#004494] text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm flex items-center justify-center transition-colors text-sm">
                <i data-lucide="play" class="w-4 h-4 mr-2"></i> Jalankan Migrasi
            </button>
        </form>
    </div>

    <!-- Storage Link -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 flex-shrink-0">
                <i data-lucide="link" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900">Storage Link</h2>
                <p class="text-xs text-slate-500">Buat symlink storage ke folder public.</p>
            </div>
        </div>
        <p class="text-xs text-slate-600 mb-4 flex-grow">Diperlukan agar file upload (foto barang, QR code) dapat diakses dari browser. Hanya perlu dijalankan sekali.</p>
        <form action="{{ route('settings.storage-link') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-[#005bbf] hover:bg-[#004494] text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm flex items-center justify-center transition-colors text-sm {{ $systemInfo['storage_linked'] ? 'opacity-60' : '' }}">
                <i data-lucide="link" class="w-4 h-4 mr-2"></i> {{ $systemInfo['storage_linked'] ? 'Sudah Terpasang' : 'Buat Storage Link' }}
            </button>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECTION 3.5: MySQL Strict Mode Fix -->
<!-- ============================================================ -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="flex items-center mb-4">
        <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 mr-3 flex-shrink-0">
            <i data-lucide="database-zap" class="w-5 h-5"></i>
        </div>
        <div>
            <h2 class="text-base font-bold text-slate-900">Fix MySQL Strict Mode</h2>
            <p class="text-xs text-slate-500">Perbaiki error <code class="bg-slate-100 px-1 py-0.5 rounded text-[10px] font-mono">ONLY_FULL_GROUP_BY</code> pada shared hosting (InfinityFree, dll).</p>
        </div>
    </div>

    <!-- Live Status Panel -->
    <div id="sqlModeStatusPanel" class="mb-4">
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-2 h-2 rounded-full bg-slate-300 animate-pulse" id="sqlModeStatusDot"></div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider" id="sqlModeStatusLabel">Memuat status...</span>
            </div>
            <div id="sqlModeDetails" class="space-y-2 hidden">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">GLOBAL sql_mode</span>
                    <code class="block bg-white border border-slate-200 rounded-lg px-3 py-2 text-[11px] font-mono text-slate-600 break-all leading-relaxed" id="sqlModeGlobal">-</code>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">SESSION sql_mode</span>
                    <code class="block bg-white border border-slate-200 rounded-lg px-3 py-2 text-[11px] font-mono text-slate-600 break-all leading-relaxed" id="sqlModeSession">-</code>
                </div>
                <div class="flex items-center gap-4 pt-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Laravel Strict</span>
                        <span id="laravelStrictBadge" class="px-2 py-0.5 rounded-full text-[10px] font-bold">-</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ONLY_FULL_GROUP_BY</span>
                        <span id="groupByBadge" class="px-2 py-0.5 rounded-full text-[10px] font-bold">-</span>
                    </div>
                </div>
            </div>
            <div id="sqlModeUnsupported" class="hidden">
                <p class="text-xs text-slate-500 italic">Fitur ini hanya tersedia untuk database MySQL/MariaDB.</p>
            </div>
            <div id="sqlModeError" class="hidden">
                <p class="text-xs text-red-600"><i data-lucide="alert-circle" class="w-3.5 h-3.5 inline mr-1"></i>Gagal memuat status sql_mode.</p>
            </div>
        </div>
    </div>

    <!-- Description -->
    <p class="text-xs text-slate-600 mb-4">
        Error <code class="bg-red-50 text-red-600 px-1 py-0.5 rounded text-[10px] font-mono">SQLSTATE[42000]: 1055 isn't in GROUP BY</code> terjadi karena MySQL strict mode mengaktifkan <code class="bg-slate-100 px-1 py-0.5 rounded text-[10px] font-mono">ONLY_FULL_GROUP_BY</code>. Klik tombol di bawah untuk menonaktifkannya.
    </p>

    <!-- Action Button -->
    <form action="{{ route('settings.fix-strict-mode') }}" method="POST" id="fixStrictModeForm">
        @csrf
        <button type="submit" id="fixStrictModeBtn" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm flex items-center justify-center transition-colors text-sm active:scale-[0.98]" disabled>
            <i data-lucide="zap" class="w-4 h-4 mr-2"></i>
            <span id="fixStrictModeBtnText">Memuat...</span>
        </button>
    </form>

    <!-- Config Hint -->
    <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-3">
        <div class="flex items-start gap-2">
            <i data-lucide="lightbulb" class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5"></i>
            <div class="text-xs text-amber-800">
                <strong>Tip:</strong> Pastikan juga <code class="bg-amber-100 px-1 py-0.5 rounded font-mono text-[10px]">'strict' => false</code> ada di <code class="bg-amber-100 px-1 py-0.5 rounded font-mono text-[10px]">config/database.php</code> pada koneksi MySQL. Ini mencegah Laravel mengaktifkan strict mode otomatis di setiap koneksi baru.
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECTION 4: Database Management (Danger Zone) -->
<!-- ============================================================ -->
<div class="bg-white rounded-2xl shadow-sm border border-red-200 p-6 relative overflow-hidden">
    <!-- Warning BG -->
    <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
        <i data-lucide="alert-triangle" class="w-40 h-40 text-red-500"></i>
    </div>

    <div class="flex items-center mb-5 relative z-10">
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 mr-3 flex-shrink-0">
            <i data-lucide="shield-alert" class="w-5 h-5"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-900">Zona Berbahaya</h2>
            <p class="text-xs text-slate-500">Tindakan di bawah ini bersifat <strong>destruktif</strong> dan tidak dapat dibatalkan. Hanya Superadmin yang dapat menjalankan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative z-10">

        <!-- Seed Dummy Data -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 flex flex-col">
            <div class="flex items-center mb-2">
                <i data-lucide="flask-conical" class="w-5 h-5 text-amber-600 mr-2"></i>
                <h3 class="text-sm font-bold text-slate-900">Isi Data Dummy</h3>
            </div>
            <p class="text-xs text-slate-600 mb-4 flex-grow">Tambahkan data contoh (kategori, barang, pengguna, peminjaman) ke database saat ini. <strong>Tidak menghapus</strong> data yang sudah ada.</p>
            <form action="{{ route('settings.seed-dummy') }}" method="POST" onsubmit="return confirm('Yakin ingin menambahkan data dummy ke database? Data yang sudah ada tidak akan terhapus.')">
                @csrf
                <button type="submit" class="w-full bg-[#005bbf] hover:bg-[#004494] text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm flex items-center justify-center transition-colors text-sm">
                    <i data-lucide="flask-conical" class="w-4 h-4 mr-2"></i> Tambah Dummy Data
                </button>
            </form>
        </div>

        <!-- Reset Database (Truncate + Re-create admins) -->
        <div class="bg-red-50 border border-red-200 rounded-xl p-5 flex flex-col">
            <div class="flex items-center mb-2">
                <i data-lucide="rotate-ccw" class="w-5 h-5 text-red-600 mr-2"></i>
                <h3 class="text-sm font-bold text-slate-900">Reset Database</h3>
            </div>
            <p class="text-xs text-slate-600 mb-4 flex-grow">Kosongkan <strong>SEMUA</strong> tabel dan buat ulang akun admin default. Anda akan otomatis logout setelah proses.</p>
            <form action="{{ route('settings.reset-database') }}" method="POST" onsubmit="return confirmResetDatabase(event)">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm flex items-center justify-center transition-colors text-sm">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i> Reset Database
                </button>
            </form>
        </div>

        <!-- Full System Reset (migrate:fresh --seed) -->
        <div class="bg-red-100 border border-red-300 rounded-xl p-5 flex flex-col">
            <div class="flex items-center mb-2">
                <i data-lucide="bomb" class="w-5 h-5 text-red-700 mr-2"></i>
                <h3 class="text-sm font-bold text-slate-900">Reset Penuh Sistem</h3>
            </div>
            <p class="text-xs text-slate-600 mb-4 flex-grow">Hapus <strong>semua tabel</strong> dan jalankan migrasi + seeder dari awal. Termasuk skema database. Anda akan logout.</p>
            <form action="{{ route('settings.reset') }}" method="POST" onsubmit="return confirmFullReset(event)">
                @csrf
                <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm flex items-center justify-center transition-colors text-sm">
                    <i data-lucide="bomb" class="w-4 h-4 mr-2"></i> Reset Penuh (Migrate Fresh)
                </button>
            </form>
        </div>
    </div>

    <!-- Default Credentials Info -->
    <div class="mt-5 bg-slate-50 border border-slate-200 rounded-xl p-4 relative z-10">
        <div class="flex items-start gap-3">
            <i data-lucide="key-round" class="w-5 h-5 text-slate-500 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-xs font-bold text-slate-700 mb-1">Akun Default Setelah Reset:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-slate-600">
                    <div class="font-mono bg-white border border-slate-200 rounded-lg px-3 py-2">
                        <span class="font-bold text-red-600">Superadmin:</span> superadmin / Superadmin2026
                    </div>
                    <div class="font-mono bg-white border border-slate-200 rounded-lg px-3 py-2">
                        <span class="font-bold text-blue-600">Admin:</span> admin / Admin2026
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for PJAX compatibility -->
<script>
    function confirmResetDatabase(e) {
        e.preventDefault();
        const input = prompt("PERINGATAN: Semua data akan dihapus!\n\nKetik 'RESET' untuk melanjutkan:");
        if (input === 'RESET') {
            e.target.submit();
            return true;
        } else if (input !== null) {
            alert('Dibatalkan. Kata yang diketik tidak sesuai.');
        }
        return false;
    }

    function confirmFullReset(e) {
        e.preventDefault();
        const input = prompt("PERINGATAN KERAS: SEMUA tabel dan data akan dihapus total!\n\nKetik 'HANCURKAN' untuk melanjutkan:");
        if (input === 'HANCURKAN') {
            e.target.submit();
            return true;
        } else if (input !== null) {
            alert('Dibatalkan. Kata yang diketik tidak sesuai.');
        }
        return false;
    }

    // ==========================================
    // SQL Mode Status Loader (Fix Strict Mode)
    // ==========================================
    function loadSqlModeStatus() {
        const dot = document.getElementById('sqlModeStatusDot');
        const label = document.getElementById('sqlModeStatusLabel');
        const details = document.getElementById('sqlModeDetails');
        const unsupported = document.getElementById('sqlModeUnsupported');
        const errorPanel = document.getElementById('sqlModeError');
        const btn = document.getElementById('fixStrictModeBtn');
        const btnText = document.getElementById('fixStrictModeBtnText');

        if (!dot) return; // Element not on page

        fetch('{{ route("settings.sql-mode-status") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                dot.className = 'w-2 h-2 rounded-full bg-red-500';
                label.textContent = 'Error';
                errorPanel.classList.remove('hidden');
                btn.disabled = true;
                btnText.textContent = 'Tidak Tersedia';
                return;
            }

            if (!data.supported) {
                dot.className = 'w-2 h-2 rounded-full bg-slate-400';
                label.textContent = 'Tidak Didukung (' + data.driver.toUpperCase() + ')';
                unsupported.classList.remove('hidden');
                btn.disabled = true;
                btnText.textContent = 'Tidak Tersedia';
                btn.className = btn.className.replace('bg-amber-500 hover:bg-amber-600', 'bg-slate-300 cursor-not-allowed');
                return;
            }

            // Show details
            details.classList.remove('hidden');
            document.getElementById('sqlModeGlobal').textContent = data.global_mode || '(kosong)';
            document.getElementById('sqlModeSession').textContent = data.session_mode || '(kosong)';

            // Laravel strict badge
            const strictBadge = document.getElementById('laravelStrictBadge');
            if (data.laravel_strict) {
                strictBadge.textContent = 'Aktif';
                strictBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200';
            } else {
                strictBadge.textContent = 'Nonaktif';
                strictBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200';
            }

            // ONLY_FULL_GROUP_BY badge
            const groupByBadge = document.getElementById('groupByBadge');
            if (data.has_issue) {
                groupByBadge.textContent = 'Aktif ⚠';
                groupByBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200';
                dot.className = 'w-2 h-2 rounded-full bg-amber-500 animate-pulse';
                label.textContent = 'Perlu Diperbaiki';
                label.className = 'text-xs font-bold text-amber-600 uppercase tracking-wider';
                btn.disabled = false;
                btnText.textContent = 'Perbaiki Strict Mode';
            } else {
                groupByBadge.textContent = 'Nonaktif ✓';
                groupByBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200';
                dot.className = 'w-2 h-2 rounded-full bg-green-500';
                label.textContent = 'Tidak Ada Masalah';
                label.className = 'text-xs font-bold text-green-600 uppercase tracking-wider';
                btn.disabled = false;
                btnText.textContent = 'Sudah Normal — Jalankan Ulang';
                btn.className = btn.className.replace('bg-amber-500 hover:bg-amber-600', 'bg-green-500 hover:bg-green-600');
            }
        })
        .catch(() => {
            dot.className = 'w-2 h-2 rounded-full bg-red-500';
            label.textContent = 'Gagal Memuat';
            errorPanel.classList.remove('hidden');
            btn.disabled = true;
            btnText.textContent = 'Tidak Tersedia';
        });
    }

    // Load on page ready
    loadSqlModeStatus();

    // Re-init Lucide icons if available (PJAX compat)
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
</script>
@endsection
