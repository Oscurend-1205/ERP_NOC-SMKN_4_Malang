<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>ERP NOC - Dashboard</title>
<!-- Tailwind CSS v3 CDN -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Google Fonts: Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols for modal icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style data-purpose="base-typography">
    body {
      font-family: 'Inter', sans-serif;
      background-color: #F8FAFC;
    }
  </style>
<style data-purpose="custom-layout">
    /* Custom scrollbar for a cleaner look */
    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
  </style>
</head>
<body class="flex min-h-screen">



@include('partials.sidebar')

<!-- BEGIN: Main Content Area -->
<main class="flex-grow flex flex-col h-screen overflow-y-auto">
@include('partials.topbar')

<!-- BEGIN: Dashboard Content -->
<div class="p-10 space-y-10">
<!-- BEGIN: Ringkasan Section -->
<section data-purpose="summary-stats">
<h3 class="text-xl font-bold mb-6 text-gray-800">Ringkasan Dashboard</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Card 1: Total Aset NOC -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
<div class="space-y-4">
<p class="text-gray-900 font-semibold">Total Aset NOC</p>
<h4 class="text-5xl font-bold text-gray-900">{{ $totalItems }}</h4>
</div>
<div class="bg-white p-2">
    <img alt="Total Aset Icon" class="w-16 h-16 object-contain" src="{{ asset('asset/icon/total-aset-noc.svg') }}"/>
</div>
</div>
<!-- Card 2: Peminjaman Aktif -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
<div class="space-y-4">
<p class="text-gray-900 font-semibold">Peminjaman Aktif</p>
<h4 class="text-5xl font-bold text-gray-900">{{ $itemsMaintenance ?? 0 }}</h4>
</div>
<div class="bg-white p-2">
    <img alt="Peminjaman Aktif Icon" class="w-16 h-16 object-contain" src="{{ asset('asset/icon/peminjaman-aktif.svg') }}"/>
</div>
</div>
<!-- Card 3: Sisa Barang -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
<div class="space-y-4">
<p class="text-gray-900 font-semibold">Sisa Barang</p>
<h4 class="text-5xl font-bold text-gray-900">{{ $itemsBaik }}</h4>
</div>
<div class="bg-white p-2">
    <img alt="Sisa Barang Icon" class="w-16 h-16 object-contain" src="{{ asset('asset/icon/sisa-barang.svg') }}"/>
</div>
</div>
</div>
</section>
<!-- END: Ringkasan Section -->

<!-- BEGIN: Activity Table Section -->
<section data-purpose="activity-table-container">
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
<div class="p-6 flex items-center justify-between">
<h3 class="text-lg font-bold text-gray-800">Aktivitas Terkini</h3>
<button class="text-[#3F51B5] text-sm font-medium hover:underline">Lihat Semua</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-gray-100 text-gray-700">
<tr>
<th class="px-6 py-4 font-semibold text-center">Tanggal</th>
<th class="px-6 py-4 font-semibold text-center">Nama Barang</th>
<th class="px-6 py-4 font-semibold text-center">Tipe</th>
<th class="px-6 py-4 font-semibold text-center">Kuantitas</th>
<th class="px-6 py-4 font-semibold text-center">Status</th>
<th class="px-6 py-4 font-semibold text-center">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-100">
@foreach($recentMovements as $movement)
<tr class="hover:bg-gray-50 transition-colors">
<td class="px-6 py-5 text-center text-gray-600">{{ $movement->created_at->format('H:i d/m/Y') }}</td>
<td class="px-6 py-5 text-center text-gray-800 font-medium">{{ $movement->item->name ?? 'N/A' }}</td>
<td class="px-6 py-5 text-center text-gray-600 capitalize">{{ $movement->type }}</td>
<td class="px-6 py-5 text-center text-gray-600">{{ $movement->quantity }} Unit</td>
<td class="px-6 py-5 text-center">
    @php
        $badgeClass = match($movement->type) {
            'masuk' => 'bg-[#2ECC71] text-white',
            'keluar' => 'bg-red-500 text-white',
            'pindah' => 'bg-[#3F51B5] text-white',
            default => 'bg-gray-400 text-white'
        };
        $badgeText = match($movement->type) {
            'masuk' => 'Masuk',
            'keluar' => 'Keluar',
            'pindah' => 'Dipinjam',
            default => ucfirst($movement->type)
        };
    @endphp
    <span class="px-4 py-1.5 {{ $badgeClass }} text-xs font-semibold rounded-md">{{ $badgeText }}</span>
</td>
<td class="px-6 py-5 text-center">
    <a href="{{ route('items.show', $movement->item_id) }}" class="text-gray-400 hover:text-[#3F51B5] transition-colors">
        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
    </a>
</td>
</tr>
@endforeach
@if($recentMovements->isEmpty())
<tr>
    <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Belum ada aktivitas terbaru</td>
</tr>
@endif
</tbody>
</table>
</div>
<!-- Table Footer Padding -->
<div class="py-4"></div>
</div>
</section>
<!-- END: Activity Table Section -->
</div>
<!-- END: Dashboard Content -->
</main>
<!-- END: Main Content Area -->

<!-- Realtime Clock Script -->
<script>
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('realtime-clock-display');
        if (el) el.textContent = h + ':' + m + ':' + s;

        // Update operational status
        const hour = now.getHours();
        const statusEl = document.getElementById('operational-status');
        if (statusEl) {
            if (hour >= 6 && hour < 15) {
                statusEl.textContent = 'OPEN';
                statusEl.className = 'font-bold text-[12px] text-green-400';
            } else {
                statusEl.textContent = 'CLOSED';
                statusEl.className = 'font-bold text-[12px] text-red-400';
            }
        }
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Phone number input formatting
    const phoneInput = document.getElementById('borrower_phone_input');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Expandable Data Master Menu script
    function initMasterMenu() {
        const btnMaster = document.getElementById('btn-data-master');
        const subMaster = document.getElementById('sub-data-master');
        const iconMaster = document.getElementById('icon-data-master');

        if (btnMaster && subMaster) {
            btnMaster.onclick = function() {
                const isHidden = subMaster.classList.contains('hidden');
                
                if (isHidden) {
                    subMaster.classList.remove('hidden');
                    subMaster.classList.add('flex');
                    if (iconMaster) iconMaster.style.transform = 'rotate(180deg)';
                } else {
                    subMaster.classList.add('hidden');
                    subMaster.classList.remove('flex');
                    if (iconMaster) iconMaster.style.transform = 'rotate(0deg)';
                }
            };
            
            // Auto expand if current route is within master data
            const currentPath = window.location.pathname;
            if (currentPath.includes('categories') || currentPath.includes('locations')) {
                subMaster.classList.remove('hidden');
                subMaster.classList.add('flex');
                if (iconMaster) iconMaster.style.transform = 'rotate(180deg)';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', initMasterMenu);
    if (window.Turbo) {
        document.addEventListener('turbo:load', initMasterMenu);
    }
</script>

</body></html>