<!-- Top Navigation Bar -->
<header class="h-12 border-b border-gray-200 fixed top-0 right-0 left-[200px] z-40 bg-white/80 backdrop-blur-md flex items-center justify-between px-4 transition-all duration-300">
    <!-- Search Area -->
    <div class="flex items-center h-full">
        <div class="flex items-center bg-gray-100/50 rounded-lg px-2.5 py-1.5 w-56 border border-gray-200 focus-within:border-primary focus-within:bg-white transition-all group">
            <span class="material-symbols-outlined text-outline text-[18px] flex items-center justify-center" data-icon="search" style="width: 20px; height: 20px;">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-[13px] w-full ml-1 p-0 leading-tight" placeholder="Cari barang..." type="text"/>
        </div>
    </div>

    <!-- Actions & Profile Area -->
    <div class="flex items-center h-full gap-2">
        <div class="flex items-center gap-1">
            <button class="p-1.5 text-outline hover:bg-gray-100 rounded-full transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px] leading-none" data-icon="notifications">notifications</span>
            </button>
            <button class="p-1.5 text-outline hover:bg-gray-100 rounded-full transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px] leading-none" data-icon="settings">settings</span>
            </button>
        </div>
        <div class="h-6 w-px bg-gray-200 mx-1"></div>
        <div class="flex items-center h-full gap-2 cursor-pointer">
            <div class="flex flex-col justify-center text-right">
                <p class="text-[11px] font-bold text-on-background leading-none mb-0.5">{{ Auth::user()->name }}</p>
                <p class="text-[9px] uppercase tracking-wider leading-none text-outline">{{ Auth::user()->role }}</p>
            </div>
            <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold border border-gray-200">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </div>
    </div>
</header>
