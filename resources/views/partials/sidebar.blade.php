<!-- BEGIN: Sidebar Backdrop (Mobile Only) -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

<!-- BEGIN: Sidebar -->
<aside id="mainSidebar" class="fixed inset-y-0 left-0 md:sticky md:top-0 h-full md:h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 w-64 bg-[#0B0E37] text-white flex flex-col flex-shrink-0 z-50" data-purpose="sidebar">
<style>
    /* Custom Scrollbar for Sidebar */
    #sidebar-nav-container {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        scrollbar-gutter: stable;
    }
    #sidebar-nav-container::-webkit-scrollbar {
        width: 4px;
    }
    #sidebar-nav-container::-webkit-scrollbar-track {
        background: transparent;
    }
    #sidebar-nav-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
    }
    #sidebar-nav-container::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Mobile logic: when sidebar is active/open */
    #mainSidebar.sidebar-mobile-open {
        transform: translateX(0);
    }
</style>
<!-- Sidebar Logo Section -->
<div class="px-3 pt-3 pb-0 flex justify-center flex-shrink-0">
<img alt="SMKN 4 MALANG NOC Logo" class="w-[210px] h-auto object-contain" src="{{ asset('asset/noc-smkn4.svg') }}"/>
</div>
<!-- Navigation Menu -->
<nav id="sidebar-nav-container" class="mt-1 flex-grow px-4 space-y-2 overflow-y-auto overflow-x-hidden">
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
<button type="button" id="btn-data-master" class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-white/5 transition-colors {{ request()->routeIs('locations.*') || request()->routeIs('categories.*') || request()->routeIs('users.*') || request()->routeIs('jurusan.*') || request()->routeIs('supplier.*') || request()->routeIs('kondisi.*') ? 'text-white font-medium' : 'text-gray-400 hover:text-white' }} border-none cursor-pointer bg-transparent text-left relative z-10">
<div class="flex items-center space-x-2 pointer-events-none">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<svg class="w-[24px] h-[24px] flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
<span>Data Master</span>
</div>
<span id="icon-data-master" class="material-symbols-outlined text-[18px] text-white transition-transform duration-300 pointer-events-none">expand_more</span>
</button>
<div id="sub-data-master" class="hidden flex-col gap-1 pl-12 pr-2 mt-1 overflow-hidden transition-all duration-300">
<a href="{{ route('users.index') }}" class="py-2 text-sm font-medium {{ request()->routeIs('users.*') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }} transition-colors">Data User</a>
<a href="{{ route('locations.index') }}" class="py-2 text-sm font-medium {{ request()->routeIs('locations.*') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }} transition-colors">Data Ruangan</a>
<a href="{{ route('jurusan.index') }}" class="py-2 text-sm font-medium {{ request()->routeIs('jurusan.*') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }} transition-colors">Data Jurusan</a>
<a href="{{ route('supplier.index') }}" class="py-2 text-sm font-medium {{ request()->routeIs('supplier.*') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }} transition-colors">Data Supplier</a>
<a href="{{ route('categories.index') }}" class="py-2 text-sm font-medium {{ request()->routeIs('categories.*') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }} transition-colors">Kategori Barang</a>
<a href="{{ route('kondisi.index') }}" class="py-2 text-sm font-medium {{ request()->routeIs('kondisi.*') ? 'text-white font-bold' : 'text-gray-400 hover:text-white' }} transition-colors">Kondisi Barang</a>
<a href="#" class="py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">Asal Barang</a>
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
<a class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('peminjaman.*') ? 'bg-[#3A3D5C] text-white font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}" href="{{ route('peminjaman.index') }}">
<div class="w-8 flex justify-center items-center flex-shrink-0">
<img src="{{ asset('asset/icon/data-peminjaman.svg') }}" alt="Data Peminjaman" class="w-7 h-7 flex-shrink-0 object-contain brightness-0 invert"/>
</div>
<span>Data Peminjaman</span>
</a>

</nav>

<!-- Logout Section -->
<div class="mt-auto p-4 border-t border-white/10 flex-shrink-0">
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
<script>
    function initSidebar() {
        const sidebar = document.getElementById('mainSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const btnDataMaster = document.getElementById('btn-data-master');
        const subDataMaster = document.getElementById('sub-data-master');
        const iconDataMaster = document.getElementById('icon-data-master');

        // Reset states for mobile on load
        if (window.innerWidth < 768) {
            if (sidebar) sidebar.classList.remove('sidebar-mobile-open');
            if (backdrop) backdrop.classList.add('hidden');
        }

        if (btnDataMaster && subDataMaster && iconDataMaster) {
            if (btnDataMaster.dataset.initialized) return;
            btnDataMaster.dataset.initialized = 'true';

            // Check if we are on a Data Master page
            const isDataMasterActive = {{ request()->routeIs('locations.*') || request()->routeIs('categories.*') || request()->routeIs('users.*') || request()->routeIs('jurusan.*') || request()->routeIs('supplier.*') || request()->routeIs('kondisi.*') ? 'true' : 'false' }};
            
            if (isDataMasterActive) {
                subDataMaster.classList.remove('hidden');
                subDataMaster.classList.add('flex');
                iconDataMaster.style.transform = 'rotate(180deg)';
            }

            btnDataMaster.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isHidden = subDataMaster.classList.contains('hidden');
                
                if (isHidden) {
                    subDataMaster.classList.remove('hidden');
                    subDataMaster.classList.add('flex');
                    iconDataMaster.style.transform = 'rotate(180deg)';
                } else {
                    subDataMaster.classList.add('hidden');
                    subDataMaster.classList.remove('flex');
                    iconDataMaster.style.transform = 'rotate(0deg)';
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initSidebar);
    document.addEventListener('turbo:load', initSidebar);
</script>
