<!DOCTYPE html>

<html lang="id" class="notranslate" translate="no"><head>
<meta name="google" content="notranslate" />
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>ERP NOC - Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
@vite(['resources/js/dashboard.js', 'resources/css/dashboard.css'])
</head>
<body class="bg-background font-body-md text-on-background antialiased notranslate" translate="no">
@include('partials.sidebar')
<!-- Main Content Wrapper -->
<div class="ml-[200px] min-h-screen">
@include('partials.topbar')
<!-- Main Canvas -->
<main class="p-4 pt-16 space-y-6">
<!-- Page Header -->
<div class="flex items-center justify-between">
<div>
<h1 class="text-lg font-bold text-on-background">Ringkasan Dasbor</h1>
<p class="text-xs text-outline">Selamat datang kembali, Admin.</p>
</div>
<div class="flex items-center gap-3">
    <!-- Realtime Clock -->
    <div class="bg-black border border-gray-600 px-4 py-1.5 rounded-lg text-white font-mono text-base flex items-center justify-center shadow-inner min-w-[70px]">
        <span id="realtime-clock-display" class="font-extrabold tracking-widest text-emerald-400">00:00</span>
    </div>

    <button class="flex items-center gap-2 px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-700 transition-all shadow-sm active:scale-95 text-sm">
        <span class="material-symbols-outlined text-[20px]" data-icon="add">add</span>
        Input Barang
    </button>
</div>
</div>
<!-- Metrics Row (Bento Style) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg">
<!-- Card 1: Total Barang -->
<div class="bg-white p-4 rounded-xl border border-outline-variant shadow-sm relative overflow-hidden">
<div class="flex justify-between items-start">
<div>
<p class="text-[10px] uppercase font-bold tracking-wider text-outline">Total Barang</p>
<h2 class="text-2xl font-bold mt-1">{{ $totalItems }}</h2>
</div>
<div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center">
<span class="material-symbols-outlined text-primary" data-icon="inventory_2">inventory_2</span>
</div>
</div>
<div class="absolute bottom-0 left-0 w-full h-1 bg-primary"></div>
</div>
<!-- Card 2: Kondisi Rusak -->
<div class="bg-white p-4 rounded-xl border border-outline-variant shadow-sm relative overflow-hidden">
<div class="flex justify-between items-start">
<div>
<p class="text-[10px] uppercase font-bold tracking-wider text-outline">Kondisi Rusak</p>
<h2 class="text-2xl font-bold mt-1 text-error">{{ $itemsRusak }}</h2>
</div>
<div class="w-12 h-12 rounded-full bg-error-container flex items-center justify-center">
<span class="material-symbols-outlined text-error" data-icon="warning">warning</span>
</div>
</div>
<div class="absolute bottom-0 left-0 w-full h-1 bg-error"></div>
</div>
<!-- Card 3: Masuk Hari Ini -->
<div class="bg-white p-4 rounded-xl border border-outline-variant shadow-sm relative overflow-hidden">
<div class="flex justify-between items-start">
<div>
<p class="text-[10px] uppercase font-bold tracking-wider text-outline">Masuk Hari Ini</p>
<h2 class="text-2xl font-bold mt-1">{{ $itemsEnteredToday }}</h2>
</div>
<div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center">
<span class="material-symbols-outlined text-on-surface-variant" data-icon="move_to_inbox">move_to_inbox</span>
</div>
</div>
<div class="absolute bottom-0 left-0 w-full h-1 bg-outline"></div>
</div>
<!-- Card 4: Total Kategori -->
<div class="bg-white p-4 rounded-xl border border-outline-variant shadow-sm relative overflow-hidden">
<div class="flex justify-between items-start">
<div>
<p class="text-[10px] uppercase font-bold tracking-wider text-outline">Total Kategori</p>
<h2 class="text-2xl font-bold mt-1">{{ $totalCategories }}</h2>
</div>
<div class="w-12 h-12 rounded-full bg-tertiary-fixed flex items-center justify-center">
<span class="material-symbols-outlined text-tertiary" data-icon="category">category</span>
</div>
</div>
<div class="absolute bottom-0 left-0 w-full h-1 bg-tertiary"></div>
</div>
</div>
<!-- Analytics Row -->
<div class="grid grid-cols-12 gap-lg">
<!-- Bar Chart: Aktivitas Barang Masuk -->
<div class="col-span-12 lg:col-span-8 bg-white p-lg rounded-xl border border-outline-variant shadow-sm">
<div class="flex items-center justify-between mb-xl">
<h3 class="font-h3 text-h3">Aktivitas Barang Masuk</h3>
<select class="text-body-sm border-gray-200 rounded-lg py-1 px-3 focus:ring-primary focus:border-primary">
<option>7 Bulan Terakhir</option>
<option>12 Bulan Terakhir</option>
</select>
</div>
<div class="h-40 flex items-end justify-between gap-4 px-4">
<div class="flex flex-col items-center gap-2 w-full">
<div class="bg-primary-fixed-dim w-full rounded-t-lg h-[40%] hover:bg-primary transition-colors"></div>
<span class="text-label-md text-outline">Jan</span>
</div>
<div class="flex flex-col items-center gap-2 w-full">
<div class="bg-primary-fixed-dim w-full rounded-t-lg h-[55%] hover:bg-primary transition-colors"></div>
<span class="text-label-md text-outline">Feb</span>
</div>
<div class="flex flex-col items-center gap-2 w-full">
<div class="bg-primary-fixed-dim w-full rounded-t-lg h-[80%] hover:bg-primary transition-colors"></div>
<span class="text-label-md text-outline">Mar</span>
</div>
<div class="flex flex-col items-center gap-2 w-full">
<div class="bg-primary w-full rounded-t-lg h-[95%] hover:bg-primary-fixed transition-colors"></div>
<span class="text-label-md text-outline">Apr</span>
</div>
<div class="flex flex-col items-center gap-2 w-full">
<div class="bg-primary-fixed-dim w-full rounded-t-lg h-[65%] hover:bg-primary transition-colors"></div>
<span class="text-label-md text-outline">May</span>
</div>
<div class="flex flex-col items-center gap-2 w-full">
<div class="bg-primary-fixed-dim w-full rounded-t-lg h-[45%] hover:bg-primary transition-colors"></div>
<span class="text-label-md text-outline">Jun</span>
</div>
<div class="flex flex-col items-center gap-2 w-full">
<div class="bg-primary-fixed-dim w-full rounded-t-lg h-[75%] hover:bg-primary transition-colors"></div>
<span class="text-label-md text-outline">Jul</span>
</div>
</div>
</div>
<!-- Donut Chart: Distribusi Kondisi Barang -->
<div class="col-span-12 lg:col-span-4 bg-white p-lg rounded-xl border border-outline-variant shadow-sm">
<h3 class="font-h3 text-h3 mb-xl">Distribusi Kondisi</h3>
<div class="relative flex items-center justify-center h-32 mb-4">
<svg class="w-full h-full transform -rotate-90">
<circle cx="50%" cy="50%" fill="transparent" r="50" stroke="#f1f3f4" stroke-width="18"></circle>
<circle cx="50%" cy="50%" fill="transparent" r="50" stroke="#005bbf" stroke-dasharray="314" stroke-dashoffset="80" stroke-linecap="round" stroke-width="18"></circle>
</svg>
<div class="absolute inset-0 flex flex-col items-center justify-center">
<span class="font-bold text-xl">{{ $totalItems }}</span>
<span class="text-[9px] text-outline uppercase">Total</span>
</div>
</div>
<div class="space-y-sm">
<div class="flex items-center justify-between text-body-md">
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-primary"></span>
<span>Kondisi Baik</span>
</div>
<span class="font-semibold">{{ $totalItems > 0 ? round(($itemsBaik / $totalItems) * 100) : 0 }}%</span>
</div>
<div class="flex items-center justify-between text-body-md">
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-error"></span>
<span>Kondisi Rusak</span>
</div>
<span class="font-semibold">{{ $totalItems > 0 ? round(($itemsRusak / $totalItems) * 100) : 0 }}%</span>
</div>
<div class="flex items-center justify-between text-body-md">
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-gray-400"></span>
<span>Lainnya (Hilang)</span>
</div>
<span class="font-semibold">{{ $totalItems > 0 ? round(($conditionStats['hilang'] / $totalItems) * 100) : 0 }}%</span>
</div>
</div>
</div>
</div>
<!-- Table Section: Recent Activity -->
<div class="bg-white rounded-xl border border-outline-variant shadow-sm overflow-hidden">
<div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
<h3 class="font-bold text-sm">Aktivitas Terbaru</h3>
<button class="text-primary text-body-sm font-medium hover:underline">Lihat Semua</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-container-low text-outline text-[12px] uppercase font-bold tracking-wider">
<tr>
<th class="px-4 py-2">Tanggal</th>
<th class="px-4 py-2">Nama Barang</th>
<th class="px-4 py-2">Tipe</th>
<th class="px-4 py-2">Kuantitas</th>
<th class="px-4 py-2">Status</th>
<th class="px-4 py-2 text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-50 text-[11px]">
@foreach($recentMovements as $movement)
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-4 py-2 text-outline">{{ $movement->created_at->format('d M Y') }}</td>
<td class="px-4 py-2 font-semibold text-on-surface">{{ $movement->item->name ?? 'N/A' }}</td>
<td class="px-4 py-2 capitalize">{{ $movement->type }}</td>
<td class="px-4 py-2">{{ $movement->quantity }} Unit</td>
<td class="px-4 py-2">
    @php
        $badgeClass = match($movement->type) {
            'masuk' => 'bg-secondary-container text-on-secondary-container',
            'keluar' => 'bg-error-container text-error',
            'pindah' => 'bg-primary-container text-on-primary-container',
            default => 'bg-surface-container-highest text-on-surface-variant'
        };
    @endphp
<span class="px-2.5 py-0.5 {{ $badgeClass }} rounded-md text-[10px] font-bold uppercase tracking-tight">{{ $movement->type }}</span>
</td>
<td class="px-4 py-2 text-right">
<a href="{{ route('items.show', $movement->item_id) }}" class="text-outline hover:text-primary transition-colors">
<span class="material-symbols-outlined !text-[18px]" data-icon="visibility">visibility</span>
</a>
</td>
</tr>
@endforeach
@if($recentMovements->isEmpty())
<tr>
    <td colspan="6" class="px-4 py-8 text-center text-outline italic">Belum ada aktivitas terbaru</td>
</tr>
@endif
</tbody>
</table>
</div>
</div>
</main>
</div>
</body></html>