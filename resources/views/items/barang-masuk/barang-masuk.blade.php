<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Barang Masuk - ERP NOC</title>
    <!-- Tailwind CSS CDN with plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style data-purpose="typography">
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
    <style>
        html { zoom: 0.9; }
        /* Fix viewport height when zoomed */
        .min-h-screen { min-height: calc(100vh / 0.9) !important; }
        .h-screen { height: calc(100vh / 0.9) !important; }
        
        /* Consistent table header styling */
        table thead {
            background-color: #e5e7eb !important;
            border-bottom: 1px solid #d1d5db !important;
        }
        table thead th {
            color: #1f2937 !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 1rem 1.5rem !important;
        }
    </style>
</head>
<body class="flex min-h-screen bg-[#F8FAFC]">

    @include('partials.sidebar')

    <!-- BEGIN: Main Content Area -->
    <main class="flex-grow flex flex-col h-screen overflow-y-auto">
        @include('partials.topbar')

        <!-- BEGIN: Main Page Content -->
        <div class="p-4 md:p-10 pt-4 md:pt-6 space-y-6" data-purpose="main-layout">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Barang Masuk</h2>
                    <p class="text-sm text-gray-500 mt-1">Riwayat penerimaan barang masuk aset NOC</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('items.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 font-semibold rounded-lg hover:bg-gray-200 transition-all text-sm border border-gray-200">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Kembali
                    </a>
                    <button class="flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Barang Masuk
                    </button>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap items-center gap-3">
                <div class="relative">
                    <select class="appearance-none pl-4 pr-9 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white text-gray-700">
                        <option value="">Rentang Tanggal</option>
                        <option>Hari Ini</option>
                        <option>Minggu Ini</option>
                        <option>Bulan Ini</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="relative">
                    <select class="appearance-none pl-4 pr-9 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer bg-white text-gray-700">
                        <option value="">Semua Kondisi</option>
                        <option>Baik</option>
                        <option>Rusak Ringan</option>
                        <option>Rusak Berat</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="relative ml-auto">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input type="text" placeholder="Cari barang..." class="pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none w-56 text-gray-700 placeholder-gray-400" />
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider w-12 text-center">No</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">ID Barang</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Placeholder rows --}}
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 text-sm text-gray-500 text-center">1</td>
                                <td class="py-4 px-6 text-sm text-gray-600">10 Apr 2026</td>
                                <td class="py-4 px-6 text-sm text-gray-600 font-mono">241020</td>
                                <td class="py-4 px-6 font-semibold text-sm text-gray-800">Macbook Air M2</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Laptop</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Baik</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="#" class="text-[#3F51B5] hover:underline font-medium text-sm">Detail</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 text-sm text-gray-500 text-center">2</td>
                                <td class="py-4 px-6 text-sm text-gray-600">10 Apr 2026</td>
                                <td class="py-4 px-6 text-sm text-gray-600 font-mono">241030</td>
                                <td class="py-4 px-6 font-semibold text-sm text-gray-800">VGA</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Hardware</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Baik</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="#" class="text-[#3F51B5] hover:underline font-medium text-sm">Detail</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 text-sm text-gray-500 text-center">3</td>
                                <td class="py-4 px-6 text-sm text-gray-600">10 Apr 2026</td>
                                <td class="py-4 px-6 text-sm text-gray-600 font-mono">241040</td>
                                <td class="py-4 px-6 font-semibold text-sm text-gray-800">RAM 8GB</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Hardware</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Baik</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="#" class="text-[#3F51B5] hover:underline font-medium text-sm">Detail</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6 text-sm text-gray-500 text-center">4</td>
                                <td class="py-4 px-6 text-sm text-gray-600">10 Apr 2026</td>
                                <td class="py-4 px-6 text-sm text-gray-600 font-mono">241050</td>
                                <td class="py-4 px-6 font-semibold text-sm text-gray-800">Macbook Air M2</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Laptop</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Baik</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="#" class="text-[#3F51B5] hover:underline font-medium text-sm">Detail</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END: Main Page Content -->
    </main>
    <!-- END: Main Content Area -->
    @include('components.accessibility-button')
</body>
</html>


