@php
    $currentHour = now()->format('H');
    $isOpen = ($currentHour >= 6 && $currentHour < 15);
@endphp
<!-- BEGIN: Header -->
<header class="flex items-center justify-between px-4 md:px-10 py-3 md:py-4 bg-white shadow-sm sticky top-0 z-40" data-purpose="top-header">
<div class="flex items-center gap-3">
    <!-- Hamburger Menu (Mobile Only) -->
    <button onclick="toggleSidebar()" class="md:hidden p-2 -ml-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
        <span class="material-symbols-outlined text-[24px]">menu</span>
    </button>
    <div>
        <h2 class="text-base md:text-xl font-bold text-[#111827]">{{ Auth::user()->role ?? 'Super Admin' }}</h2>
        <p class="text-[10px] md:text-xs text-gray-500 mt-0.5">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</p>
    </div>
</div>
<div class="flex items-center space-x-3 md:space-x-4">
    <!-- Realtime Clock & Status -->
    <div class="flex bg-[#0B0E37] border border-gray-700 px-2 md:px-4 py-1.5 rounded-lg text-white font-mono text-[10px] md:text-sm items-center gap-2 md:gap-3 shadow-inner">
        <span class="realtime-clock-display font-extrabold tracking-widest text-blue-400">00:00:00</span>
        <span class="text-gray-600 font-normal hidden md:inline">|</span>
        <span class="operational-status font-bold text-[10px] md:text-[12px] {{ $isOpen ? 'text-green-400' : 'text-red-400' }}">
            {{ $isOpen ? 'OPEN' : 'CLOSED' }}
        </span>
    </div>

    <!-- Notification Bell -->
    {{-- <div class="relative cursor-pointer">
        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-600 border-2 border-white"></span>
    </div> --}}

    <!-- User Profile -->
    <a href="{{ route('profile.index') }}" class="cursor-pointer block hover:opacity-80 transition-opacity" title="Lihat Profil Saya">
        @if(Auth::user()->avatar)
            <img alt="User Profile" class="w-10 h-10 rounded-full bg-gray-200 object-cover border-2 border-transparent hover:border-blue-400 transition-colors" src="{{ Storage::url(Auth::user()->avatar) }}"/>
        @else
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold border-2 border-transparent hover:border-blue-400 transition-colors">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
        @endif
    </a>
</div>
</header>
<!-- END: Header -->

<script>
    // Realtime Clock - Attached directly to topbar to ensure it runs anywhere topbar is included
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        
        document.querySelectorAll('.realtime-clock-display').forEach(el => {
            el.textContent = h + ':' + m + ':' + s;
        });

        const hour = now.getHours();
        document.querySelectorAll('.operational-status').forEach(statusEl => {
            if (hour >= 6 && hour < 15) {
                statusEl.textContent = 'OPEN';
                statusEl.className = 'operational-status font-bold text-[10px] md:text-[12px] text-green-400';
            } else {
                statusEl.textContent = 'CLOSED';
                statusEl.className = 'operational-status font-bold text-[10px] md:text-[12px] text-red-400';
            }
        });
    }
    // Initialize immediately
    updateClock();
    // Update every second
    setInterval(updateClock, 1000);
</script>
