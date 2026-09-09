@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<!-- Page Title -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Pengaturan Sistem</h1>
    <p class="text-sm text-gray-500 mt-1">Konfigurasi dan manajemen sistem ERP NOC</p>
</div>

<!-- BEGIN: Settings Layout with Sidebar -->
<div class="flex gap-6">
    <!-- Sidebar Navigation -->
    <div class="w-64 flex-shrink-0 hidden lg:block">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 sticky top-6">
            <div class="mb-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Menu Pengaturan</h2>
                <p class="text-xs text-gray-500">Navigasi cepat ke fitur sistem</p>
            </div>
            <nav class="space-y-1">
                <a href="#general" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">settings</span>
                    Umum
                </a>
                <a href="#system" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    Informasi Sistem
                </a>
                <a href="#database" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">storage</span>
                    Database
                </a>
                <a href="#maintenance" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">build</span>
                    Pemeliharaan
                </a>
                <a href="#logs" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">description</span>
                    System Logs
                </a>
                <div class="pt-3 mt-3 border-t border-gray-200">
                    <a href="#danger" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">warning</span>
                        Zona Berbahaya
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 min-w-0">
        <!-- Mobile Navigation -->
        <div class="lg:hidden mb-4">
            <select onchange="window.location.hash = this.value" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm">
                <option value="">Pilih Menu...</option>
                <option value="#general">Umum</option>
                <option value="#system">Informasi Sistem</option>
                <option value="#database">Database</option>
                <option value="#maintenance">Pemeliharaan</option>
                <option value="#logs">System Logs</option>
                <option value="#danger">Zona Berbahaya</option>
            </select>
        </div>

        @if (session('error'))
        <div class="mb-4 p-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
            <span class="material-symbols-outlined text-[20px] text-red-600">error</span>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if (session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3">
            <span class="material-symbols-outlined text-[20px] text-green-600">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- SECTION: General Settings -->
        <div id="general" class="mb-6 scroll-mt-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">settings</span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Pengaturan Umum</h2>
                        <p class="text-xs text-gray-500">Konfigurasi dasar aplikasi</p>
                    </div>
                </div>

                <form action="{{ route('settings.update-general') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Aplikasi</label>
                            <input type="text" name="app_name" value="{{ config('app.name') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Timezone</label>
                            <select name="timezone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @foreach(['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura', 'UTC'] as $tz)
                                <option value="{{ $tz }}" {{ config('app.timezone') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Environment</label>
                            <select name="app_env" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="local" {{ config('app.env') === 'local' ? 'selected' : '' }}>Local</option>
                                <option value="production" {{ config('app.env') === 'production' ? 'selected' : '' }}>Production</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Debug Mode</label>
                            <select name="app_debug" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <option value="true" {{ config('app.debug') ? 'selected' : '' }}>Aktif</option>
                                <option value="false" {{ !config('app.debug') ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SECTION: System Info -->
        <div id="system" class="mb-6 scroll-mt-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">info</span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Informasi Sistem</h2>
                        <p class="text-xs text-gray-500">Status dan versi komponen sistem</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">PHP</div>
                        <div class="text-sm font-bold text-gray-800 mt-1 font-mono">{{ $systemInfo['php_version'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Laravel</div>
                        <div class="text-sm font-bold text-gray-800 mt-1 font-mono">{{ $systemInfo['laravel_version'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">DB Driver</div>
                        <div class="text-sm font-bold text-gray-800 mt-1 font-mono">{{ strtoupper($systemInfo['db_driver']) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Environment</div>
                        <div class="text-sm font-bold mt-1 font-mono {{ $systemInfo['app_env'] === 'production' ? 'text-green-600' : 'text-amber-600' }}">{{ ucfirst($systemInfo['app_env']) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Database</div>
                        <div class="text-sm font-bold text-gray-800 mt-1 font-mono truncate" title="{{ $systemInfo['db_name'] }}">{{ $systemInfo['db_name'] }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Storage Link</div>
                        <div class="text-sm font-bold mt-1 font-mono {{ $systemInfo['storage_linked'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $systemInfo['storage_linked'] ? 'Sudah Ada' : 'Belum Ada' }}
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Total Items</div>
                        <div class="text-sm font-bold text-indigo-600 mt-1 font-mono">{{ number_format($stats['items']) }}</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Total Users</div>
                        <div class="text-sm font-bold text-indigo-600 mt-1 font-mono">{{ number_format($stats['users']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION: Database -->
        <div id="database" class="mb-6 scroll-mt-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mr-3 flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">storage</span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Statistik Database</h2>
                        <p class="text-xs text-gray-500">Jumlah data saat ini di setiap tabel utama</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3">
                    <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100 text-center">
                        <span class="material-symbols-outlined text-[20px] text-blue-500">inventory_2</span>
                        <div class="text-xl font-bold text-blue-700 font-mono mt-2">{{ number_format($stats['items']) }}</div>
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-1">Barang</div>
                    </div>
                    <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100 text-center">
                        <span class="material-symbols-outlined text-[20px] text-purple-500">label</span>
                        <div class="text-xl font-bold text-purple-700 font-mono mt-2">{{ number_format($stats['categories']) }}</div>
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-1">Kategori</div>
                    </div>
                    <div class="bg-teal-50/50 rounded-xl p-4 border border-teal-100 text-center">
                        <span class="material-symbols-outlined text-[20px] text-teal-500">place</span>
                        <div class="text-xl font-bold text-teal-700 font-mono mt-2">{{ number_format($stats['locations']) }}</div>
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-1">Lokasi</div>
                    </div>
                    <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100 text-center">
                        <span class="material-symbols-outlined text-[20px] text-indigo-500">people</span>
                        <div class="text-xl font-bold text-indigo-700 font-mono mt-2">{{ number_format($stats['users']) }}</div>
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-1">Pengguna</div>
                    </div>
                    <div class="bg-amber-50/50 rounded-xl p-4 border border-amber-100 text-center">
                        <span class="material-symbols-outlined text-[20px] text-amber-500">assignment</span>
                        <div class="text-xl font-bold text-amber-700 font-mono mt-2">{{ number_format($stats['peminjaman']) }}</div>
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-1">Peminjaman</div>
                    </div>
                    <div class="bg-rose-50/50 rounded-xl p-4 border border-rose-100 text-center">
                        <span class="material-symbols-outlined text-[20px] text-rose-500">sync_alt</span>
                        <div class="text-xl font-bold text-rose-700 font-mono mt-2">{{ number_format($stats['movements']) }}</div>
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-1">Mutasi</div>
                    </div>
                    <div class="bg-green-50/50 rounded-xl p-4 border border-green-100 text-center">
                        <span class="material-symbols-outlined text-[20px] text-green-500">build</span>
                        <div class="text-xl font-bold text-green-700 font-mono mt-2">{{ number_format($stats['perawatans']) }}</div>
                        <div class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mt-1">Perawatan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION: Maintenance -->
        <div id="maintenance" class="mb-6 scroll-mt-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Clear Cache -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">wind_power</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Bersihkan Cache</h3>
                            <p class="text-xs text-gray-500">Hapus cache sistem</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mb-4">Gunakan jika perubahan konfigurasi atau tampilan tidak langsung diterapkan.</p>
                    <form action="{{ route('settings.clear-cache') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors">
                            Bersihkan Cache
                        </button>
                    </form>
                </div>

                <!-- Run Migrations -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">database</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Jalankan Migrasi</h3>
                            <p class="text-xs text-gray-500">Update skema database</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mb-4">Gunakan setelah update kode yang mengandung perubahan skema database.</p>
                    <form action="{{ route('settings.run-migrations') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors">
                            Jalankan Migrasi
                        </button>
                    </form>
                </div>

                <!-- Storage Link -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">link</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Storage Link</h3>
                            <p class="text-xs text-gray-500">Symlink storage ke public</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mb-4">Diperlukan agar file upload dapat diakses dari browser.</p>
                    <form action="{{ route('settings.storage-link') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors {{ $systemInfo['storage_linked'] ? 'opacity-60' : '' }}">
                            {{ $systemInfo['storage_linked'] ? 'Sudah Terpasang' : 'Buat Storage Link' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECTION: System Logs -->
        <div id="logs" class="mb-6 scroll-mt-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 mr-3 flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">description</span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">System Logs</h2>
                        <p class="text-xs text-gray-500">Log aktivitas dan error sistem</p>
                    </div>
                </div>
                <div class="mb-4 flex items-center gap-3">
                    <select id="logFileSelect" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="laravel">laravel.log</option>
                    </select>
                    <button onclick="loadLogs()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors">
                        <span class="material-symbols-outlined text-[16px] align-middle mr-1">refresh</span>
                        Refresh
                    </button>
                </div>
                <div class="bg-gray-900 rounded-xl p-4 overflow-x-auto max-h-96">
                    <pre id="logContent" class="text-xs text-green-400 font-mono whitespace-pre-wrap">Memuat log...</pre>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('settings.download-logs') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition-colors inline-flex items-center">
                        <span class="material-symbols-outlined text-[16px] mr-1">download</span>
                        Download Logs
                    </a>
                    <form action="{{ route('settings.clear-logs') }}" method="POST" class="inline" onsubmit="return confirmClearLogs(event)">
                        @csrf
                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 font-semibold py-2 px-4 rounded-lg text-sm transition-colors inline-flex items-center">
                            <span class="material-symbols-outlined text-[16px] mr-1">delete</span>
                            Clear Logs
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECTION: Danger Zone -->
        <div id="danger" class="mb-6 scroll-mt-6">
            <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                    <span class="material-symbols-outlined text-[80px] text-red-500">warning</span>
                </div>

                <div class="flex items-center mb-5 relative z-10">
                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 mr-3 flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">shield_alert</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Zona Berbahaya</h2>
                        <p class="text-xs text-gray-500">Tindakan destruktif yang tidak dapat dibatalkan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative z-10">
                    <!-- Seed Dummy Data -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                        <div class="flex items-center mb-2">
                            <span class="material-symbols-outlined text-[20px] text-amber-600 mr-2">science</span>
                            <h3 class="text-sm font-bold text-gray-900">Isi Data Dummy</h3>
                        </div>
                        <p class="text-xs text-gray-600 mb-4">Tambahkan data contoh tanpa menghapus data yang sudah ada.</p>
                        <form action="{{ route('settings.seed-dummy') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors">
                                Tambah Dummy Data
                            </button>
                        </form>
                    </div>

                    <!-- Reset Database -->
                    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                        <div class="flex items-center mb-2">
                            <span class="material-symbols-outlined text-[20px] text-red-600 mr-2">refresh</span>
                            <h3 class="text-sm font-bold text-gray-900">Reset Database</h3>
                        </div>
                        <p class="text-xs text-gray-600 mb-4">Kosongkan semua tabel dan buat ulang akun admin default.</p>
                        <form action="{{ route('settings.reset-database') }}" method="POST" onsubmit="return confirmResetDatabase(event)">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors">
                                Reset Database
                            </button>
                        </form>
                    </div>

                    <!-- Full System Reset -->
                    <div class="bg-red-100 border border-red-300 rounded-xl p-5">
                        <div class="flex items-center mb-2">
                            <span class="material-symbols-outlined text-[20px] text-red-700 mr-2">delete_forever</span>
                            <h3 class="text-sm font-bold text-gray-900">Reset Penuh Sistem</h3>
                        </div>
                        <p class="text-xs text-gray-600 mb-4">Hapus semua tabel dan jalankan migrasi + seeder dari awal.</p>
                        <form action="{{ route('settings.reset') }}" method="POST" onsubmit="return confirmFullReset(event)">
                            @csrf
                            <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors">
                                Reset Penuh
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-5 bg-gray-50 border border-gray-200 rounded-xl p-4 relative z-10">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[20px] text-gray-500">key</span>
                        <div>
                            <p class="text-xs font-bold text-gray-700 mb-1">Akun Default Setelah Reset:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-gray-600">
                                <div class="font-mono bg-white border border-gray-200 rounded-lg px-3 py-2">
                                    <span class="font-bold text-red-600">Superadmin:</span> superadmin / Superadmin2026
                                </div>
                                <div class="font-mono bg-white border border-gray-200 rounded-lg px-3 py-2">
                                    <span class="font-bold text-blue-600">Admin:</span> admin / Admin2026
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmResetDatabase(e) {
    e.preventDefault();
    const input = prompt("PERINGATAN: Semua data akan dihapus!\n\nKetik 'RESET' untuk melanjutkan:");
    if (input === 'RESET') {
        e.target.submit();
        return true;
    }
    return false;
}

function confirmFullReset(e) {
    e.preventDefault();
    const input = prompt("PERINGATAN KERAS: SEMUA tabel dan data akan dihapus total!\n\nKetik 'HANCURKAN' untuk melanjutkan:");
    if (input === 'HANCURKAN') {
        e.target.submit();
        return true;
    }
    return false;
}

function confirmClearLogs(e) {
    e.preventDefault();
    if (confirm('Apakah Anda yakin ingin menghapus semua log? Tindakan ini tidak dapat dibatalkan.')) {
        e.target.submit();
        return true;
    }
    return false;
}

function loadLogs() {
    const logFile = document.getElementById('logFileSelect').value;
    const logContent = document.getElementById('logContent');

    logContent.textContent = 'Memuat log...';

    fetch('{{ route("settings.view-logs") }}?file=' + logFile + '&lines=200', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            logContent.textContent = 'Error: ' + data.error;
        } else {
            logContent.textContent = data.content || 'Log kosong';
        }
    })
    .catch(() => {
        logContent.textContent = 'Gagal memuat log.';
    });
}

// Load logs on page ready
document.addEventListener('DOMContentLoaded', loadLogs);
</script>
@endsection
