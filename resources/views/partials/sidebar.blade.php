<!-- BEGIN: Sidebar -->
<aside class="w-64 bg-[#0B0E37] text-white flex-shrink-0 flex flex-col" data-purpose="sidebar">
<!-- Sidebar Logo Section -->
<div class="px-3 pt-3 pb-0 flex justify-center">
<img alt="SMKN 4 MALANG NOC Logo" class="w-[210px] h-auto object-contain" src="{{ asset('asset/noc-smkn4.svg') }}"/>
</div>
<!-- Navigation Menu -->
<nav class="mt-1 flex-grow px-4 space-y-2">
<!-- Active Item: Beranda -->
<a class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#3A3D5C] text-white font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}" href="{{ route('dashboard') }}">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<img src="{{ asset('asset/icon/beranda.svg') }}" alt="Beranda" class="w-[25px] h-[25px] flex-shrink-0 object-contain brightness-0 invert"/>
</div>
<span>Beranda</span>
</a>
<!-- Input Pinjaman -->
<a class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('qr.*') ? 'bg-[#3A3D5C] text-white font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}" href="{{ route('qr.admin') }}">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<img src="{{ asset('asset/icon/peminjaman.svg') }}" alt="QR Peminjaman" class="w-[25px] h-[25px] flex-shrink-0 object-contain brightness-0 invert"/>
</div>
<span>Input Pinjaman</span>
</a>
<!-- Data Master -->
@if(Auth::user()->role === 'Superadmin')
<div class="flex flex-col">
<button id="btn-data-master" class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-white/5 transition-colors {{ request()->routeIs('locations.*') || request()->routeIs('categories.*') ? 'text-white font-medium' : 'text-gray-400 hover:text-white' }} border-none cursor-pointer bg-transparent text-left">
<div class="flex items-center space-x-3">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<svg class="w-[27px] h-[27px] flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
<span>Data Master</span>
</div>
<span id="icon-data-master" class="material-symbols-outlined text-[18px] text-white transition-transform duration-300">expand_more</span>
</button>
<div id="sub-data-master" class="hidden flex-col gap-1 pl-16 mt-1 overflow-hidden transition-all duration-300">
<a href="{{ route('locations.index') }}" class="py-2 text-sm font-medium {{ request()->routeIs('locations.*') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }} transition-colors">Data Ruangan</a>
<a href="#" class="py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">Data Supplier</a>
<a href="#" class="py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">Data Jurusan</a>
<a href="{{ route('categories.index') }}" class="py-2 text-sm font-medium {{ request()->routeIs('categories.*') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }} transition-colors">Data Kategori Barang</a>
<a href="#" class="py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">Data Kondisi Barang</a>
<a href="#" class="py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">Manajemen Asal Barang</a>
</div>
</div>
@endif
<!-- Data Barang -->
<a class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('items.*') ? 'bg-[#3A3D5C] text-white font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}" href="{{ route('items.index') }}">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<img src="{{ asset('asset/icon/total-aset-noc.svg') }}" alt="Data Barang" class="w-[25px] h-[25px] flex-shrink-0 object-contain brightness-0 invert"/>
</div>
<span>Data Barang</span>
</a>
<!-- Data Peminjaman -->
<a class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors text-gray-400 hover:bg-white/5 hover:text-white" href="#">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<img src="{{ asset('asset/icon/data-peminjaman.svg') }}" alt="Data Peminjaman" class="w-7 h-7 flex-shrink-0 object-contain brightness-0 invert"/>
</div>
<span>Data Peminjaman</span>
</a>
<!-- Data Pengembalian -->
<a class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('movements.*') ? 'bg-[#3A3D5C] text-white font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}" href="{{ route('movements.index') }}">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<img src="{{ asset('asset/icon/data-pengembalian.svg') }}" alt="Data Pengembalian" class="w-[26px] h-[26px] flex-shrink-0 object-contain brightness-0 invert"/>
</div>
<span>Mutasi Barang</span>
</a>
</nav>

<!-- Logout Section -->
<div class="mt-auto p-4 border-t border-white/10">
<form action="{{ route('logout') }}" method="POST">
@csrf
<button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors text-gray-400 hover:bg-white/5 hover:text-white border-none cursor-pointer bg-transparent text-left">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<span class="material-symbols-outlined text-[20px] text-white">logout</span>
</div>
<span class="text-sm font-medium">Keluar</span>
</button>
</form>
</div>
</aside>
<!-- END: Sidebar -->
