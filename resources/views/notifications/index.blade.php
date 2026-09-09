@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Notifikasi</h2>
        <p class="text-sm text-gray-500 mt-1">
            {{ $unreadCount > 0 ? $unreadCount . ' notifikasi belum dibaca' : 'Semua notifikasi sudah dibaca' }}
        </p>
    </div>
    <div class="flex gap-2">
        @if($unreadCount > 0)
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#3F51B5] text-white text-sm font-semibold rounded-xl hover:bg-[#3949AB] transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">done_all</span>
                Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>
</div>

<!-- Filter Tabs -->
<div class="flex gap-2 mb-6">
    <a href="{{ route('notifications.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ !request('filter') ? 'bg-[#1A1E35] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        Semua
    </a>
    <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('filter') === 'unread' ? 'bg-[#1A1E35] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        Belum Dibaca
        @if($unreadCount > 0)
            <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full">{{ $unreadCount }}</span>
        @endif
    </a>
</div>

<!-- Notification List -->
<div class="space-y-2">
    @forelse($notifications as $notif)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-all {{ !$notif->is_read ? 'border-l-4 border-l-[#3F51B5]' : '' }}">
        <div class="flex items-start gap-4">
            <!-- Icon -->
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ match($notif->type) { 'success' => 'bg-green-100 text-green-600', 'warning' => 'bg-yellow-100 text-yellow-600', 'danger' => 'bg-red-100 text-red-600', default => 'bg-blue-100 text-blue-600' } }}">
                <span class="material-symbols-outlined text-[22px]">{{ $notif->icon }}</span>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 {{ !$notif->is_read ? '' : 'font-semibold' }}">{{ $notif->title }}</h4>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $notif->message }}</p>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0">
                        @if(!$notif->is_read)
                            <span class="w-2 h-2 rounded-full bg-[#3F51B5]" title="Belum dibaca"></span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                    @if($notif->action_url)
                        <a href="{{ route('notifications.mark-read', $notif->id) }}" class="text-xs font-semibold text-[#3F51B5] hover:text-[#3949AB] transition-colors">
                            Lihat Detail →
                        </a>
                    @elseif(!$notif->is_read)
                        <form method="POST" action="{{ route('notifications.mark-read', $notif->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-gray-400 hover:text-[#3F51B5] transition-colors">
                                Tandai Dibaca
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-gray-300 hover:text-red-500 transition-colors">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
        <span class="material-symbols-outlined text-[64px] text-gray-200 mb-3">notifications_off</span>
        <p class="text-sm font-medium text-gray-400">Tidak ada notifikasi</p>
    </div>
    @endforelse
</div>

@if($notifications->hasPages())
<div class="mt-6">
    {{ $notifications->links() }}
</div>
@endif
@endsection
