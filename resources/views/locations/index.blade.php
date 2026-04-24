<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Manajemen Ruangan - Inventory System SMKN 4 Malang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://unpkg.com/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body class="bg-background font-body-md text-on-background antialiased notranslate" translate="no">

@include('partials.sidebar')

<!-- Main Content Wrapper -->
<div class="ml-[200px] min-h-screen">
    @include('partials.topbar')

    <!-- Main Canvas -->
    <main class="p-4 pt-16 space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-end">
            <div>
                <nav class="flex text-[10px] text-outline mb-1 uppercase font-bold tracking-wider">
                    <span class="hover:text-primary cursor-default">Data Master</span>
                    <span class="mx-2">/</span>
                    <span class="text-on-surface">Data Ruangan</span>
                </nav>
                <h1 class="text-lg font-bold text-on-background">Manajemen Ruangan</h1>
                <p class="text-xs text-outline">Kelola lokasi penyimpanan barang laboratorium.</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="bg-primary text-white px-4 py-2 rounded-lg font-medium text-sm flex items-center gap-2 hover:bg-blue-700 transition-all shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[20px]" data-icon="add">add</span>
                    Tambah Ruangan
                </button>
            </div>
        </div>

        <!-- Stats Bento Grid -->
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-outline uppercase tracking-wider mb-1">Total Ruangan</p>
                    <h2 class="text-2xl font-black text-on-surface">{{ $locations->total() }}</h2>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-primary opacity-5 group-hover:opacity-10 transition-opacity" data-icon="meeting_room" style="font-variation-settings: 'FILL' 1;">meeting_room</span>
            </div>
            <div class="col-span-12 md:col-span-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-outline uppercase tracking-wider mb-1">Pengelola</p>
                    <h2 class="text-2xl font-black text-on-surface">12</h2>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-secondary opacity-5 group-hover:opacity-10 transition-opacity" data-icon="groups" style="font-variation-settings: 'FILL' 1;">groups</span>
            </div>
            <div class="col-span-12 md:col-span-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden group border-b-4 border-b-error">
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-outline uppercase tracking-wider mb-1">Kapasitas</p>
                    <h2 class="text-2xl font-black text-on-surface">86%</h2>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-error opacity-5 group-hover:opacity-10 transition-opacity" data-icon="warning" style="font-variation-settings: 'FILL' 1;">warning</span>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-low text-outline text-[10px] uppercase font-bold tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3">NO</th>
                            <th class="px-4 py-3">KODE RUANGAN</th>
                            <th class="px-4 py-3">NAMA RUANGAN</th>
                            <th class="px-4 py-3">PENANGGUNG JAWAB</th>
                            <th class="px-4 py-3">BARANG</th>
                            <th class="px-4 py-3 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-[11px]">
                        @forelse($locations as $index => $location)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-outline">{{ $locations->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <span class="font-black text-primary tracking-tight">{{ $location->code }}</span>
                            </td>
                            <td class="px-4 py-3 font-bold text-on-surface">{{ $location->name }}</td>
                            <td class="px-4 py-3 text-outline">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-primary">AD</div>
                                    <span>Administrator</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[9px] font-bold uppercase">{{ $location->items_count }} UNIT</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors">
                                        <span class="material-symbols-outlined !text-[18px]" data-icon="edit">edit</span>
                                    </button>
                                    <button class="p-1 text-error hover:bg-error-container/20 rounded transition-colors">
                                        <span class="material-symbols-outlined !text-[18px]" data-icon="delete">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-outline italic text-xs">Belum ada data ruangan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 py-3 bg-white border-t border-gray-50 flex items-center justify-between">
                <p class="text-[10px] text-outline italic">Menampilkan {{ $locations->firstItem() ?? 0 }} hingga {{ $locations->lastItem() ?? 0 }} entri</p>
                <div class="flex items-center gap-1">
                    {{ $locations->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
