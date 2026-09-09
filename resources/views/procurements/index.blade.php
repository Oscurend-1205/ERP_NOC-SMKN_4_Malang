@extends('layouts.app')

@section('title', 'Pengajuan Pengadaan Alat')

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
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Pengajuan Pengadaan Alat</h2>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('procurements.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#3F51B5] hover:bg-[#3949AB] text-white font-bold rounded-xl transition-all shadow-sm text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Buat Pengajuan Baru</span>
                </a>
            </div>
        </div>
    </div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1: Total Pengajuan -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pengajuan</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($kpi['total']) }}</h3>
            <p class="text-xs text-gray-500 mt-0.5">Keseluruhan usulan tercatat</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[24px]">folder_open</span>
        </div>
    </div>

    <!-- Card 2: Menunggu Persetujuan -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider">Menunggu Persetujuan</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($kpi['pending']) }}</h3>
            <p class="text-xs text-amber-600 mt-0.5">Est. Rp {{ number_format($kpi['pending_cost'], 0, ',', '.') }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[24px]">pending_actions</span>
        </div>
    </div>

    <!-- Card 3: Disetujui / Proses -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wider">Disetujui / Proses</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($kpi['approved']) }}</h3>
            <p class="text-xs text-indigo-600 mt-0.5">Dalam tahap pengadaan PO</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[24px]">verified</span>
        </div>
    </div>

    <!-- Card 4: Anggaran Disetujui -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Anggaran Lolos</p>
            <h3 class="text-xl font-bold text-gray-800 mt-1 truncate max-w-[150px]" title="Rp {{ number_format($kpi['total_cost'], 0, ',', '.') }}">
                Rp {{ number_format($kpi['total_cost'] / 1000000, 1, ',', '.') }} Jt
            </h3>
            <p class="text-xs text-emerald-600 mt-0.5">{{ number_format($kpi['completed']) }} pengadaan selesai</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-[24px]">payments</span>
        </div>
    </div>
</div>

<!-- Filters & Search Bar -->
<div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6 space-y-4">
    <!-- Status Tabs -->
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 border-b border-gray-100">
        <a href="{{ route('procurements.index', request()->except('status', 'page')) }}" 
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ !request('status') ? 'bg-[#1A1E35] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Semua Status
        </a>
        @foreach([
            'pending' => 'Menunggu Approval',
            'approved' => 'Disetujui',
            'in_procurement' => 'Proses Pengadaan',
            'completed' => 'Selesai / Terealisasi',
            'rejected' => 'Ditolak',
            'draft' => 'Draf'
        ] as $key => $label)
        <a href="{{ route('procurements.index', array_merge(request()->except('page'), ['status' => $key])) }}" 
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ request('status') === $key ? 'bg-[#1A1E35] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <!-- Dropdowns & Search Form -->
    <form method="GET" action="{{ route('procurements.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <!-- Search Input -->
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau judul usulan..."
                   class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F51B5]/30 focus:border-[#3F51B5] transition-all">
        </div>

        <!-- Priority Filter -->
        <div>
            <select name="priority" onchange="this.form.submit()" class="w-full py-2 px-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F51B5]/30 focus:border-[#3F51B5]">
                <option value="">Semua Prioritas</option>
                <option value="mendesak" {{ request('priority') === 'mendesak' ? 'selected' : '' }}>🚨 Mendesak</option>
                <option value="tinggi" {{ request('priority') === 'tinggi' ? 'selected' : '' }}>⚡ Tinggi</option>
                <option value="sedang" {{ request('priority') === 'sedang' ? 'selected' : '' }}>🔹 Sedang</option>
                <option value="rendah" {{ request('priority') === 'rendah' ? 'selected' : '' }}>⚪ Rendah</option>
            </select>
        </div>

        <!-- Jurusan Filter (Only if more than 1) -->
        <div>
            <select name="jurusan_id" onchange="this.form.submit()" class="w-full py-2 px-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F51B5]/30 focus:border-[#3F51B5]">
                <option value="">Semua Jurusan / Unit</option>
                @foreach($jurusans as $j)
                <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                    {{ $j->nama_jurusan }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Submit & Reset Button -->
        <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 py-2 px-3 bg-[#1A1E35] text-white text-xs font-semibold rounded-xl hover:bg-opacity-90 transition-colors flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[16px]">filter_list</span>
                <span>Terapkan</span>
            </button>
            @if(request()->hasAny(['search', 'priority', 'jurusan_id', 'status']))
            <a href="{{ route('procurements.index') }}" class="p-2 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors" title="Reset Filter">
                <span class="material-symbols-outlined text-[18px]">refresh</span>
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode & Judul</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pemohon / Unit</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Prioritas</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Rincian & Estimasi</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Target</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    <th class="py-3.5 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($procurements as $p)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <!-- Kode & Judul -->
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold font-mono px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md border border-blue-100">
                                {{ $p->code }}
                            </span>
                            @if($p->attachment)
                            <span class="material-symbols-outlined text-[16px] text-gray-400" title="Ada Lampiran Dokumen">attach_file</span>
                            @endif
                        </div>
                        <a href="{{ route('procurements.show', $p->id) }}" class="text-sm font-semibold text-gray-800 hover:text-[#3F51B5] transition-colors mt-1 block">
                            {{ $p->title }}
                        </a>
                    </td>

                    <!-- Pemohon / Unit -->
                    <td class="py-4 px-4">
                        <div class="text-xs font-semibold text-gray-800">{{ $p->user->name ?? 'User' }}</div>
                        <div class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">school</span>
                            <span>{{ $p->jurusan->nama_jurusan ?? 'Pusat NOC' }}</span>
                        </div>
                    </td>

                    <!-- Prioritas -->
                    <td class="py-4 px-4 text-center">
                        @php $pb = $p->priority_badge; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $pb['class'] }}">
                            {{ $pb['label'] }}
                        </span>
                    </td>

                    <!-- Rincian & Estimasi Biaya -->
                    <td class="py-4 px-4 text-right">
                        <div class="text-sm font-bold text-gray-900 font-mono">
                            {{ $p->formatted_total_cost }}
                        </div>
                        <div class="text-[11px] text-gray-500 mt-0.5">
                            {{ $p->items->count() }} item alat diajukan
                        </div>
                    </td>

                    <!-- Target Tanggal -->
                    <td class="py-4 px-4 text-center">
                        @if($p->target_date)
                        <div class="text-xs font-medium text-gray-700">{{ $p->target_date->format('d M Y') }}</div>
                        <div class="text-[10px] text-gray-400">{{ $p->target_date->diffForHumans() }}</div>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>

                    <!-- Status Badge -->
                    <td class="py-4 px-4 text-center">
                        @php $badge = $p->status_badge; @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge['bg'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                            <span>{{ $p->status_label }}</span>
                        </span>
                    </td>

                    <!-- Aksi -->
                    <td class="py-4 px-4 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <!-- View Detail -->
                            <a href="{{ route('procurements.show', $p->id) }}" 
                               class="p-1.5 text-gray-500 hover:text-[#3F51B5] hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail & Tindakan">
                                <span class="material-symbols-outlined text-[19px]">visibility</span>
                            </a>

                            <!-- Print / PDF -->
                            <a href="{{ route('procurements.print', $p->id) }}" target="_blank"
                               class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Cetak Surat Usulan (PDF)">
                                <span class="material-symbols-outlined text-[19px]">print</span>
                            </a>

                            <!-- Edit (Draft or Rejected only) -->
                            @if(in_array($p->status, ['draft', 'rejected']) || Auth::user()->isSuperadmin())
                            <a href="{{ route('procurements.edit', $p->id) }}" 
                               class="p-1.5 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Usulan">
                                <span class="material-symbols-outlined text-[19px]">edit</span>
                            </a>
                            @endif

                            <!-- Delete (Draft or Rejected only) -->
                            @if(in_array($p->status, ['draft', 'rejected']) || Auth::user()->isSuperadmin())
                            <form action="{{ route('procurements.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan {{ $p->code }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Usulan">
                                    <span class="material-symbols-outlined text-[19px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-14 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <span class="material-symbols-outlined text-[52px] mb-2 opacity-30 text-gray-400">shopping_bag</span>
                            <p class="text-base font-semibold text-gray-600">Tidak ada data pengajuan pengadaan</p>
                            <p class="text-xs text-gray-400 max-w-sm mt-1">Belum ada usulan pengadaan alat yang sesuai dengan filter pencarian Anda.</p>
                            <a href="{{ route('procurements.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-[#3F51B5] text-white text-xs font-semibold rounded-xl hover:bg-[#3949AB] transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">add_circle</span>
                                <span>Buat Pengajuan Sekarang</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($procurements->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $procurements->links() }}
    </div>
    @endif
</div>
@endsection
