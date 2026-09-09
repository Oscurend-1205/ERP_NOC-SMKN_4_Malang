@extends('layouts.app')

@section('title', 'Detail Pengadaan ' . $procurement->code)

@section('content')
<!-- Header & Actions -->
<div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <a href="{{ route('procurements.index') }}" class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <span class="text-xs font-mono font-bold px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg">
                {{ $procurement->code }}
            </span>
            @php $badge = $procurement->status_badge; @endphp
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge['bg'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                <span>{{ $procurement->status_label }}</span>
            </span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $procurement->title }}</h2>
        <p class="text-xs text-gray-400 mt-0.5">
            Diajukan oleh <strong class="text-gray-600">{{ $procurement->user->name ?? 'User' }}</strong> 
            • Unit: <strong class="text-gray-600">{{ $procurement->jurusan->nama_jurusan ?? 'Pusat NOC' }}</strong>
            • Tanggal Buat: {{ $procurement->created_at->format('d M Y, H:i') }}
        </p>
    </div>

    <!-- Action Buttons Group -->
    <div class="flex items-center flex-wrap gap-2">
        <!-- Print / PDF -->
        <a href="{{ route('procurements.print', $procurement->id) }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white text-gray-700 border border-gray-200 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[17px] text-emerald-600">print</span>
            <span>Cetak Surat Usulan</span>
        </a>

        <!-- Submit Draft (If draft) -->
        @if($procurement->status === 'draft')
        <form action="{{ route('procurements.submit', $procurement->id) }}" method="POST" class="inline" onsubmit="return confirm('Kirim pengajuan ini sekarang untuk diverifikasi?')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#3F51B5] text-white text-xs font-bold rounded-xl hover:bg-[#3949AB] transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[17px]">send</span>
                <span>Kirim Verifikasi</span>
            </button>
        </form>
        @endif

        <!-- Edit Button (If draft or rejected) -->
        @if(in_array($procurement->status, ['draft', 'rejected']) || Auth::user()->isSuperadmin())
        <a href="{{ route('procurements.edit', $procurement->id) }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white text-gray-700 border border-gray-200 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[17px] text-amber-500">edit</span>
            <span>Edit Usulan</span>
        </a>
        @endif

        <!-- Superadmin Approval & Rejection Actions -->
        @if(Auth::user()->isSuperadmin() && $procurement->status === 'pending')
        <button type="button" onclick="openModal('rejectModal')" 
                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl hover:bg-rose-100 transition-colors">
            <span class="material-symbols-outlined text-[17px]">cancel</span>
            <span>Tolak</span>
        </button>
        <button type="button" onclick="openModal('approveModal')" 
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[17px]">check_circle</span>
            <span>Setujui Pengadaan</span>
        </button>
        @endif

        <!-- In Procurement / Completed Status Trigger for Superadmin & Admin -->
        @if((Auth::user()->isSuperadmin() || Auth::user()->isAdmin()) && $procurement->status === 'approved')
        <form action="{{ route('procurements.update-status', $procurement->id) }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="status" value="in_procurement">
            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-purple-600 text-white text-xs font-bold rounded-xl hover:bg-purple-700 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[17px]">local_shipping</span>
                <span>Mulai Proses Pengadaan (PO)</span>
            </button>
        </form>
        @endif

        @if((Auth::user()->isSuperadmin() || Auth::user()->isAdmin()) && $procurement->status === 'in_procurement')
        <form action="{{ route('procurements.update-status', $procurement->id) }}" method="POST" class="inline" onsubmit="return confirm('Tandai seluruh pengadaan ini telah selesai terealisasi?')">
            @csrf
            <input type="hidden" name="status" value="completed">
            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[17px]">task_alt</span>
                <span>Tandai Selesai / Terealisasi</span>
            </button>
        </form>
        @endif
    </div>
</div>

<!-- Alert Notifications -->
@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-xs font-semibold">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-800 text-xs font-semibold">
    <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
    <span>{{ session('error') }}</span>
</div>
@endif

<!-- Status Progress Pipeline / Stepper -->
<div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Alur Status Pengadaan (Procurement Pipeline)</h4>
    
    @php
        $steps = [
            'draft' => ['label' => 'Draf Usulan', 'icon' => 'edit_document'],
            'pending' => ['label' => 'Menunggu Verifikasi', 'icon' => 'hourglass_empty'],
            'approved' => ['label' => 'Disetujui', 'icon' => 'verified'],
            'in_procurement' => ['label' => 'Proses Pengadaan', 'icon' => 'local_shipping'],
            'completed' => ['label' => 'Selesai / Tiba', 'icon' => 'task_alt'],
        ];

        $statusIndex = [
            'draft' => 0,
            'pending' => 1,
            'approved' => 2,
            'in_procurement' => 3,
            'completed' => 4,
            'rejected' => 1,
        ];
        $currentIdx = $statusIndex[$procurement->status] ?? 0;
    @endphp

    @if($procurement->status === 'rejected')
    <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3">
        <span class="material-symbols-outlined text-rose-600 text-[24px] flex-shrink-0">cancel</span>
        <div>
            <h5 class="text-sm font-bold text-rose-900">Usulan Pengadaan Ini Ditolak</h5>
            <p class="text-xs text-rose-700 mt-0.5">
                Ditolak oleh: <strong>{{ $procurement->approver->name ?? 'Superadmin' }}</strong> 
                pada {{ $procurement->approved_at ? $procurement->approved_at->format('d M Y H:i') : '-' }}
            </p>
            <div class="mt-2 p-2.5 bg-white/80 rounded-lg border border-rose-200 text-xs text-rose-900 font-medium">
                <strong>Alasan Penolakan:</strong> {{ $procurement->rejection_reason }}
            </div>
        </div>
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        @foreach($steps as $key => $step)
            @php
                $stepIdx = $statusIndex[$key];
                $isPassed = $currentIdx >= $stepIdx;
                $isCurrent = $procurement->status === $key;
            @endphp
            <div class="flex flex-col items-center text-center p-2.5 rounded-xl transition-all {{ $isCurrent ? 'bg-[#3F51B5]/5 border border-[#3F51B5]/30' : '' }}">
                <div class="w-9 h-9 rounded-full flex items-center justify-center mb-1.5 transition-all
                    {{ $isCurrent ? 'bg-[#3F51B5] text-white shadow-md' : ($isPassed ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-400') }}">
                    <span class="material-symbols-outlined text-[18px]">
                        {{ $isPassed && !$isCurrent ? 'done' : $step['icon'] }}
                    </span>
                </div>
                <span class="text-xs font-semibold {{ $isCurrent ? 'text-[#3F51B5]' : ($isPassed ? 'text-gray-800' : 'text-gray-400') }}">
                    {{ $step['label'] }}
                </span>
            </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Main Content Grid: 2 Columns -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    <!-- Left Column: Items Table & Notes (2 Cols) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Table Rincian Barang -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#3F51B5] text-[20px]">list_alt</span>
                    <h3 class="text-base font-bold text-gray-800">Daftar Barang yang Diajukan</h3>
                </div>
                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-lg">
                    {{ $procurement->items->count() }} Item
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase">No</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase">Nama & Spesifikasi</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase text-center">Kategori</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase text-center">Qty</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase text-right">Est. Harga Satuan</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase text-right">Subtotal</th>
                            @if(in_array($procurement->status, ['approved', 'in_procurement', 'completed']))
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase text-center">Penerimaan</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($procurement->items as $idx => $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-4 text-xs font-bold text-gray-400">
                                {{ $idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-xs font-bold text-gray-800">{{ $item->item_name }}</div>
                                @if($item->specification)
                                <div class="text-[11px] text-gray-500 mt-0.5">{{ $item->specification }}</div>
                                @endif
                                @if($item->reference_url)
                                <a href="{{ $item->reference_url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-blue-600 hover:underline mt-1">
                                    <span class="material-symbols-outlined text-[12px]">open_in_new</span>
                                    <span>Lihat Referensi Toko</span>
                                </a>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-xs font-medium text-gray-600 px-2 py-0.5 bg-gray-100 rounded-md">
                                    {{ $item->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-xs font-bold text-gray-800">{{ $item->quantity }}</span>
                                <span class="text-[11px] text-gray-500">{{ $item->unit }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-xs text-gray-700">
                                {{ $item->formatted_unit_price }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-xs font-bold text-gray-900">
                                {{ $item->formatted_subtotal }}
                            </td>

                            <!-- Penerimaan Status & Tombol Aksi Penerimaan -->
                            @if(in_array($procurement->status, ['approved', 'in_procurement', 'completed']))
                            <td class="py-3.5 px-4 text-center">
                                @if($item->status === 'received')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="material-symbols-outlined text-[13px]">check</span>
                                        <span>Diterima ({{ $item->received_quantity }}/{{ $item->quantity }})</span>
                                    </span>
                                @elseif($item->received_quantity > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        <span>Sebagian ({{ $item->received_quantity }}/{{ $item->quantity }})</span>
                                    </span>
                                @else
                                    <span class="text-[10px] text-gray-400 font-medium">Belum Tiba</span>
                                @endif

                                @if((Auth::user()->isSuperadmin() || Auth::user()->isAdmin()) && in_array($procurement->status, ['approved', 'in_procurement']) && $item->received_quantity < $item->quantity)
                                <button type="button" onclick="openReceiveModal({{ $item->id }}, '{{ addslashes($item->item_name) }}', {{ $item->quantity - $item->received_quantity }}, '{{ $item->unit }}')"
                                        class="mt-1 block mx-auto px-2 py-1 text-[10px] font-bold bg-[#3F51B5] text-white hover:bg-[#3949AB] rounded-md transition-colors shadow-xs">
                                    Terima Barang
                                </button>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-100">
                        <tr>
                            <td colspan="{{ in_array($procurement->status, ['approved', 'in_procurement', 'completed']) ? 5 : 5 }}" class="py-3.5 px-4 text-xs font-bold text-gray-700 text-right uppercase">
                                Total Estimasi Biaya:
                            </td>
                            <td class="py-3.5 px-4 text-right text-sm font-black font-mono text-[#3F51B5]">
                                {{ $procurement->formatted_total_cost }}
                            </td>
                            @if(in_array($procurement->status, ['approved', 'in_procurement', 'completed']))
                            <td></td>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Justifikasi Kebutuhan -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
            <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                <span class="material-symbols-outlined text-[#3F51B5] text-[20px]">assignment</span>
                <h4 class="text-sm font-bold text-gray-800">Latar Belakang & Justifikasi Kebutuhan</h4>
            </div>
            <p class="text-xs text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $procurement->justification ?: 'Tidak ada catatan justifikasi kebutuhan khusus yang disertakan.' }}
            </p>
        </div>

        <!-- Catatan Tambahan / Log Persetujuan -->
        @if($procurement->notes)
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
            <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                <span class="material-symbols-outlined text-[#3F51B5] text-[20px]">notes</span>
                <h4 class="text-sm font-bold text-gray-800">Catatan & Riwayat Catatan</h4>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-700 font-mono whitespace-pre-line">
                {{ $procurement->notes }}
            </div>
        </div>
        @endif

    </div>

    <!-- Right Column: Meta Info & Summary Card (1 Col) -->
    <div class="space-y-6">
        
        <!-- Summary Card Anggaran -->
        <div class="bg-gradient-to-br from-[#1A1E35] to-[#252C4D] text-white p-6 rounded-2xl shadow-sm space-y-4">
            <span class="text-xs font-semibold text-gray-300 uppercase tracking-wider">Perkiraan Anggaran Total</span>
            <h3 class="text-3xl font-black font-mono text-emerald-400">
                {{ $procurement->formatted_total_cost }}
            </h3>
            <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs text-gray-300">
                <span>Total Kuantitas:</span>
                <span class="font-bold text-white">{{ $procurement->items->sum('quantity') }} Unit/Pcs</span>
            </div>
            <div class="flex items-center justify-between text-xs text-gray-300">
                <span>Jumlah Macam Barang:</span>
                <span class="font-bold text-white">{{ $procurement->items->count() }} Jenis</span>
            </div>
        </div>

        <!-- Detail Informasi Pengajuan -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4 text-xs">
            <h4 class="font-bold text-gray-800 uppercase tracking-wider pb-2 border-b border-gray-100">
                Informasi Pengajuan
            </h4>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Nomor Registrasi:</span>
                    <span class="font-mono font-bold text-gray-800">{{ $procurement->code }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Pemohon:</span>
                    <span class="font-semibold text-gray-800">{{ $procurement->user->name ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Jurusan / Divisi:</span>
                    <span class="font-semibold text-gray-800">{{ $procurement->jurusan->nama_jurusan ?? 'Pusat NOC' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Prioritas:</span>
                    @php $pb = $procurement->priority_badge; @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $pb['class'] }}">
                        {{ $pb['label'] }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Target Kebutuhan:</span>
                    <span class="font-semibold text-gray-800">
                        {{ $procurement->target_date ? $procurement->target_date->format('d M Y') : 'Fleksibel' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Tanggal Diajukan:</span>
                    <span class="font-semibold text-gray-800">{{ $procurement->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            @if($procurement->approved_by)
            <div class="pt-3 border-t border-gray-100 space-y-2 bg-blue-50/50 p-3 rounded-xl">
                <span class="text-[11px] font-bold text-[#3F51B5] uppercase">Persetujuan Superadmin</span>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Oleh:</span>
                    <span class="font-semibold text-gray-800">{{ $procurement->approver->name ?? 'Superadmin' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Waktu:</span>
                    <span class="font-semibold text-gray-800">{{ $procurement->approved_at ? $procurement->approved_at->format('d M Y H:i') : '-' }}</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Dokumen Lampiran Proposal -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-3">
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider pb-2 border-b border-gray-100">
                Dokumen Lampiran
            </h4>

            @if($procurement->attachment)
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-200/80 flex items-center justify-between">
                <div class="flex items-center gap-2 overflow-hidden">
                    <span class="material-symbols-outlined text-rose-500 text-[24px]">description</span>
                    <div class="truncate">
                        <p class="text-xs font-bold text-gray-800 truncate">Proposal / Penawaran</p>
                        <p class="text-[10px] text-gray-400">File pendukung</p>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $procurement->attachment) }}" target="_blank"
                   class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Download / Buka Dokumen">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                </a>
            </div>
            @else
            <p class="text-xs text-gray-400 italic">Tidak ada file lampiran yang diunggah.</p>
            @endif
        </div>

    </div>
</div>

<!-- MODAL 1: Approve Modal (Superadmin) -->
<div id="approveModal" class="fixed inset-0 bg-gray-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-[24px]">verified</span>
            </div>
            <div>
                <h4 class="text-base font-bold text-gray-800">Setujui Usulan Pengadaan</h4>
                <p class="text-xs text-gray-500">Konfirmasi persetujuan pengadaan alat</p>
            </div>
        </div>

        <form action="{{ route('procurements.approve', $procurement->id) }}" method="POST" class="space-y-4">
            @csrf
            <p class="text-xs text-gray-600 leading-relaxed">
                Anda akan menyetujui pengadaan <strong>{{ $procurement->code }}</strong> dengan total estimasi biaya <strong>{{ $procurement->formatted_total_cost }}</strong>.
            </p>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Persetujuan (Opsional)</label>
                <textarea name="approval_notes" rows="3" placeholder="Contoh: Disetujui dengan pagu anggaran lab TKJ 2026..."
                          class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('approveModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-sm">
                    Konfirmasi Setujui
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: Reject Modal (Superadmin) -->
<div id="rejectModal" class="fixed inset-0 bg-gray-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-[24px]">cancel</span>
            </div>
            <div>
                <h4 class="text-base font-bold text-gray-800">Tolak Usulan Pengadaan</h4>
                <p class="text-xs text-gray-500">Berikan alasan penolakan yang jelas</p>
            </div>
        </div>

        <form action="{{ route('procurements.reject', $procurement->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">
                    Alasan Penolakan <span class="text-rose-500">*</span>
                </label>
                <textarea name="rejection_reason" rows="3" required placeholder="Jelaskan alasan penolakan, misal anggaran melebihi kuota atau spesifikasi perlu direvisi..."
                          class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('rejectModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors shadow-sm">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 3: Receive Item & Auto-Inventory Modal -->
<div id="receiveModal" class="fixed inset-0 bg-gray-900/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#3F51B5] flex items-center justify-center font-bold">
                <span class="material-symbols-outlined text-[24px]">inventory_2</span>
            </div>
            <div>
                <h4 class="text-base font-bold text-gray-800">Penerimaan Barang Pengadaan</h4>
                <p class="text-xs text-gray-500" id="receiveItemTitle">Catat barang yang telah tiba</p>
            </div>
        </div>

        <form action="{{ route('procurements.receive-item', $procurement->id) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="procurement_item_id" id="receiveItemId">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Jumlah Diterima</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="received_qty" id="receiveQtyInput" min="1" required
                               class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl font-bold text-center focus:outline-none focus:ring-1 focus:ring-[#3F51B5]">
                        <span id="receiveUnitText" class="text-xs font-medium text-gray-500">Unit</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kondisi Fisik</label>
                    <select name="condition" class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#3F51B5]">
                        <option value="Baik" selected>Baik (Baru)</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                    </select>
                </div>
            </div>

            <!-- Opsi Konversi Otomatis ke Inventaris Barang -->
            <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-xl space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="convert_to_inventory" value="1" id="convertCheckbox" checked onchange="toggleLocationField(this.checked)"
                           class="w-4 h-4 rounded text-[#3F51B5] focus:ring-[#3F51B5]">
                    <span class="text-xs font-bold text-gray-800">Otomatis catat ke Data Barang (Inventaris Aktif)</span>
                </label>
                <p class="text-[11px] text-gray-500 ml-6">
                    Sistem akan membuat nomor kode aset unik baru dan mencatat mutasi Barang Masuk (Pengadaan).
                </p>

                <div id="locationFieldContainer" class="ml-6 space-y-1">
                    <label class="block text-[11px] font-bold text-gray-700">Pilih Ruangan / Lokasi Penempatan <span class="text-red-500">*</span></label>
                    <select name="location_id" id="locationSelect" class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#3F51B5]">
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('receiveModal')" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-[#3F51B5] hover:bg-[#3949AB] rounded-xl transition-colors shadow-sm">
                    Simpan Penerimaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.remove('hidden');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('hidden');
    }

    function openReceiveModal(itemId, itemName, remainingQty, unit) {
        document.getElementById('receiveItemId').value = itemId;
        document.getElementById('receiveItemTitle').textContent = itemName;
        document.getElementById('receiveQtyInput').value = remainingQty;
        document.getElementById('receiveQtyInput').max = remainingQty;
        document.getElementById('receiveUnitText').textContent = unit;
        openModal('receiveModal');
    }

    function toggleLocationField(isChecked) {
        const container = document.getElementById('locationFieldContainer');
        const select = document.getElementById('locationSelect');
        if (isChecked) {
            container.classList.remove('hidden');
            select.required = true;
        } else {
            container.classList.add('hidden');
            select.required = false;
        }
    }
</script>
@endsection
