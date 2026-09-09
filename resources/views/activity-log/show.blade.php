@extends('layouts.app')

@section('title', 'Detail Audit Trail')

@section('content')
<div class="mb-6">
    <a href="{{ route('activity-log.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-[#3F51B5] transition-colors mb-3">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Kembali ke Audit Trail
    </a>
    <h2 class="text-2xl font-bold text-gray-800">Detail Aktivitas</h2>
    <p class="text-sm text-gray-500 mt-1">ID: #{{ $log->id }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Info Utama -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Informasi</h3>
            </div>
            <div class="p-6 space-y-4">
                <!-- User -->
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">User</div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-[#3F51B5] flex items-center justify-center text-white text-xs font-bold">
                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-800">{{ $log->user->name ?? 'Sistem' }}</div>
                            <div class="text-xs text-gray-400">{{ $log->user->role ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Waktu -->
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Waktu</div>
                    <div class="text-sm text-gray-800 font-medium">{{ $log->created_at->format('d F Y, H:i:s') }}</div>
                    <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                </div>

                <!-- Aksi -->
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Aksi</div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $log->action_color }}">
                        {{ $log->action_label }}
                    </span>
                </div>

                <!-- Modul -->
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Modul</div>
                    <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-md">{{ $log->model_name }}</span>
                    @if($log->model_id)
                        <span class="text-xs text-gray-400 ml-1">#{{ $log->model_id }}</span>
                    @endif
                </div>

                <!-- IP Address -->
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">IP Address</div>
                    <div class="text-sm text-gray-600 font-mono">{{ $log->ip_address ?? '-' }}</div>
                </div>

                <!-- User Agent -->
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Browser</div>
                    <div class="text-xs text-gray-500 break-all">{{ \Str::limit($log->user_agent, 100) ?? '-' }}</div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Deskripsi</div>
                    <div class="text-sm text-gray-700">{{ $log->description }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Perubahan Data (Diff) -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Perubahan Data</h3>
            </div>
            <div class="p-6">
                @php
                    $changes = $log->change_summary;
                    $oldValues = $log->old_values ?? [];
                    $newValues = $log->new_values ?? [];
                @endphp

                @if($log->action === 'created' && !empty($newValues))
                    <div class="mb-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-700">Data Baru</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-green-50">
                                <tr>
                                    <th class="py-3 px-4 text-xs font-bold text-green-700 uppercase tracking-wider w-1/3">Field</th>
                                    <th class="py-3 px-4 text-xs font-bold text-green-700 uppercase tracking-wider">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($newValues as $field => $value)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-4 text-sm font-medium text-gray-700">{{ $field }}</td>
                                    <td class="py-2.5 px-4 text-sm text-green-700 font-mono break-all">{{ is_array($value) ? json_encode($value) : $value }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($log->action === 'updated' && !empty($changes))
                    <div class="mb-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700">{{ count($changes) }} field diubah</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Field</th>
                                    <th class="py-3 px-4 text-xs font-bold text-red-600 uppercase tracking-wider w-[37.5%]">Sebelum</th>
                                    <th class="py-3 px-4 text-xs font-bold text-green-600 uppercase tracking-wider w-[37.5%]">Sesudah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($changes as $field => $change)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-4 text-sm font-medium text-gray-700">{{ $field }}</td>
                                    <td class="py-2.5 px-4 text-sm text-red-600 font-mono bg-red-50/50 break-all">
                                        {{ is_array($change['old']) ? json_encode($change['old']) : ($change['old'] ?? '-') }}
                                    </td>
                                    <td class="py-2.5 px-4 text-sm text-green-700 font-mono bg-green-50/50 break-all">
                                        {{ is_array($change['new']) ? json_encode($change['new']) : ($change['new'] ?? '-') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($log->action === 'deleted' && !empty($oldValues))
                    <div class="mb-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">Data Dihapus</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="py-3 px-4 text-xs font-bold text-red-700 uppercase tracking-wider w-1/3">Field</th>
                                    <th class="py-3 px-4 text-xs font-bold text-red-700 uppercase tracking-wider">Nilai Terakhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($oldValues as $field => $value)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-4 text-sm font-medium text-gray-700">{{ $field }}</td>
                                    <td class="py-2.5 px-4 text-sm text-red-600 font-mono break-all">{{ is_array($value) ? json_encode($value) : $value }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else
                    <div class="py-8 text-center text-gray-400">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">info</span>
                        <p class="text-sm font-medium">Tidak ada detail perubahan data</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
