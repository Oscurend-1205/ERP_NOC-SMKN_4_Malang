@extends('layouts.app')

@section('title', 'Audit Trail')

@section('content')
<div class="space-y-6">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl flex items-center gap-3 border border-green-200">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl flex items-center gap-3 border border-red-200">
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header Utama --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-700 via-indigo-600 to-blue-800"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Audit Trail</h2>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('activity-log.export', request()->query()) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#1A1E35] hover:bg-[#2C3152] text-white font-bold rounded-xl transition-all shadow-sm text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>
    </div>

<!-- Filter Bar -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('activity-log.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
        <!-- Search -->
        <div class="lg:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi..."
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
        </div>
        <!-- User -->
        <select name="user_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
            <option value="">Semua User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
        <!-- Action -->
        <select name="action" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
            <option value="">Semua Aksi</option>
            @foreach($actions as $action)
                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
            @endforeach
        </select>
        <!-- Modul -->
        <select name="model_type" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
            <option value="">Semua Modul</option>
            @foreach($modelTypes as $key => $label)
                <option value="{{ $key }}" {{ request('model_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <!-- Submit -->
        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-[#3F51B5] text-white text-sm font-semibold rounded-lg hover:bg-[#3949AB] transition-colors">
                <span class="material-symbols-outlined text-[16px] align-middle mr-1">filter_list</span>Filter
            </button>
            <a href="{{ route('activity-log.index') }}" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-500 hover:bg-gray-50 transition-colors flex items-center">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
            </a>
        </div>
    </form>
</div>

<!-- Log Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Modul</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-5 text-sm text-gray-500 whitespace-nowrap">
                        <div>{{ $log->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="py-3.5 px-5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-[#3F51B5] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ substr($log->user->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-800">{{ $log->user->name ?? 'Sistem' }}</div>
                                <div class="text-xs text-gray-400">{{ $log->ip_address }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $log->action_color }}">
                            {{ $log->action_label }}
                        </span>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-md">{{ $log->model_name }}</span>
                    </td>
                    <td class="py-3.5 px-5 text-sm text-gray-600 max-w-xs truncate">{{ $log->description }}</td>
                    <td class="py-3.5 px-5 text-center">
                        <a href="{{ route('activity-log.show', $log->id) }}" class="p-1.5 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg inline-flex items-center justify-center transition-colors" title="Lihat Detail">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">history</span>
                        <p class="text-sm font-medium">Belum ada riwayat aktivitas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
