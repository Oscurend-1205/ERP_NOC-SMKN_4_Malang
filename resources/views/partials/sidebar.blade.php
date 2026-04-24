<!-- Sidebar Navigation -->
<aside class="w-[200px] h-screen fixed left-0 top-0 border-r border-gray-200 bg-white flex flex-col p-3 gap-2 z-50">
    <!-- Brand Box -->
    <div class="mx-1 my-2 px-3 py-4 bg-blue-600 rounded-xl flex items-center gap-3 shadow-sm">
        <img src="{{ asset('images/Logo-NOC.jpeg') }}" alt="Logo NOC" class="w-11 h-11 object-contain rounded-full border-2 border-white/20">
        <div>
            <h1 class="text-[13px] font-black text-white tracking-tight leading-tight uppercase">Inventory System</h1>
            <p class="text-[9px] text-blue-100 uppercase font-bold tracking-wider">SMKN 4 Malang</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 flex flex-col gap-1">
        <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg transition-colors" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined text-[20px]" data-icon="dashboard">dashboard</span>
            <span class="font-medium text-sm">Dasborard</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('users.index') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg transition-colors" href="{{ route('users.index') }}">
            <span class="material-symbols-outlined text-[20px]" data-icon="group">group</span>
            <span class="font-medium text-sm">Data Pengguna</span>
        </a>

        <!-- Data Master Expandable -->
        <div class="flex flex-col">
            <button id="btn-data-master" class="w-full flex items-center justify-between gap-3 px-3 py-1.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors border-none cursor-pointer bg-transparent">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]" data-icon="database">database</span>
                    <span class="font-medium text-sm">Data Master</span>
                </div>
                <span id="icon-data-master" class="material-symbols-outlined text-[18px] transition-transform duration-300" data-icon="expand_more">expand_more</span>
            </button>
            <div id="sub-data-master" class="hidden flex-col gap-1 pl-10 mt-1 overflow-hidden transition-all duration-300">
                <a href="{{ route('locations.index') }}" class="py-1.5 text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors">Data Ruangan</a>
                <a href="#" class="py-1.5 text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors">Data Supplier</a>
                <a href="#" class="py-1.5 text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors">Data Jurusan</a>
                <a href="{{ route('categories.index') }}" class="py-1.5 text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors">Data Kategori Barang</a>
                <a href="#" class="py-1.5 text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors">Data Kondisi Barang</a>
                <a href="#" class="py-1.5 text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors">Manajemen Asal Barang</a>
            </div>
        </div>

        <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('items.*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }} rounded-lg transition-colors" href="{{ route('items.index') }}">
            <span class="material-symbols-outlined text-[20px]" data-icon="inventory_2">inventory_2</span>
            <span class="font-medium text-sm">Data Barang</span>
        </a>
    </nav>

    <!-- Logout Section -->
    <div class="mt-auto border-t border-gray-100 pt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-1.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors border-none cursor-pointer bg-transparent">
                <span class="material-symbols-outlined text-[20px]" data-icon="logout">logout</span>
                <span class="font-medium text-sm">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<script>
     function initMasterMenu() {
         const btnMaster = document.getElementById('btn-data-master');
         const subMaster = document.getElementById('sub-data-master');
         const iconMaster = document.getElementById('icon-data-master');
 
         if (btnMaster && subMaster) {
             // Remove existing listener to prevent double attachment
             btnMaster.onclick = function() {
                 const isHidden = subMaster.classList.contains('hidden');
                 
                 if (isHidden) {
                     subMaster.classList.remove('hidden');
                     subMaster.classList.add('flex');
                     iconMaster.style.transform = 'rotate(180deg)';
                 } else {
                     subMaster.classList.add('hidden');
                     subMaster.classList.remove('flex');
                     iconMaster.style.transform = 'rotate(0deg)';
                 }
             };
             
             // Auto expand if current route is within master data
             const currentPath = window.location.pathname;
             if (currentPath.includes('categories') || currentPath.includes('locations')) {
                 subMaster.classList.remove('hidden');
                 subMaster.classList.add('flex');
                 iconMaster.style.transform = 'rotate(180deg)';
             }
         }
     }

     document.addEventListener('DOMContentLoaded', initMasterMenu);
     document.addEventListener('turbo:load', initMasterMenu);
 </script>
