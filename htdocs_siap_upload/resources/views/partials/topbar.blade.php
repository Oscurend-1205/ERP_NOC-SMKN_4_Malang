@php
    $currentHour = now()->format('H');
    $isOpen = ($currentHour >= 6 && $currentHour < 15);
@endphp
<!-- BEGIN: Header -->
<header class="flex items-center justify-between px-4 md:px-10 py-3 md:py-4 bg-white shadow-sm sticky top-0 z-10" data-purpose="top-header">
<div class="flex items-center gap-3">
    <div>
        <h2 class="text-base md:text-xl font-bold text-[#111827]">{{ Auth::user()->role ?? 'Super Admin' }}</h2>
        <p class="text-[10px] md:text-xs text-gray-500 mt-0.5">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</p>
    </div>
</div>
<div class="flex items-center space-x-3 md:space-x-4">
    <!-- Realtime Clock & Status -->
    <div class="hidden md:flex bg-[#0B0E37] border border-gray-700 px-4 py-1.5 rounded-lg text-white font-mono text-sm items-center gap-3 shadow-inner">
        <span id="realtime-clock-display" class="font-extrabold tracking-widest text-blue-400">00:00:00</span>
        <span class="text-gray-600 font-normal">|</span>
        <span id="operational-status" class="font-bold text-[12px] {{ $isOpen ? 'text-green-400' : 'text-red-400' }}">
            {{ $isOpen ? 'OPEN' : 'CLOSED' }}
        </span>
    </div>

    <!-- Notification Bell -->
    <div class="relative cursor-pointer">
        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-600 border-2 border-white"></span>
    </div>

    <!-- User Profile -->
    <div class="cursor-pointer">
        <img alt="User Profile" class="w-10 h-10 rounded-full bg-gray-200 object-cover" src="{{ asset('asset/superadmin-icon.svg') }}"/>
    </div>
</div>
</header>
<!-- END: Header -->
