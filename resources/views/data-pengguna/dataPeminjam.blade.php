@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
    <!-- BEGIN: Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Peminjaman</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh riwayat peminjaman aset.</p>
        </div>
        <div class="flex items-center gap-3">

            @if(in_array(Auth::user()->role, ['Superadmin', 'Admin']))
            <a href="{{ route('qr.admin') }}" class="flex items-center justify-center gap-2 px-6 py-2.5 bg-[#3F51B5] text-white font-semibold rounded-lg hover:bg-[#3949AB] transition-all shadow-sm active:scale-95 text-sm border-none cursor-pointer no-underline">
                <span class="material-symbols-outlined text-[20px]">add</span>
                TAMBAH PEMINJAMAN
            </a>
            @endif
        </div>
    </div>

    <!-- BEGIN: Bento Layout Grid -->
    <div class="grid grid-cols-12 gap-6 mb-6">
        <!-- Filter Controls -->
        <div class="col-span-12 lg:col-span-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-4">
            <form action="{{ route('peminjaman.index') }}" method="GET" class="flex flex-col gap-4 w-full">
                <!-- Row 1: Jurusan & Button -->
                <div class="flex flex-col md:flex-row items-start md:items-end gap-3 w-full">
                    <div class="space-y-1.5 flex-grow w-full">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">FILTER JURUSAN</label>
                        <div class="relative">
                            <select name="jurusan" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 cursor-pointer text-gray-600 appearance-none">
                                <option value="Semua Jurusan">Semua Jurusan</option>
                                <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ - Teknik Komputer Jaringan</option>
                                <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>RPL - Rekayasa Perangkat Lunak</option>
                                <option value="MM" {{ request('jurusan') == 'MM' ? 'selected' : '' }}>MM - Multimedia</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[20px]">expand_more</span>
                        </div>
                    </div>
                    <button type="submit" class="bg-[#3F51B5] hover:bg-[#303F9F] text-white px-6 py-2 rounded-xl flex items-center justify-center gap-2 transition-all w-full md:w-auto text-sm font-bold shadow-sm active:scale-95 border-none cursor-pointer h-[38px]">
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        Terapkan
                    </button>
                    @if(request()->hasAny(['jurusan', 'start_date', 'end_date']))
                        <a href="{{ route('peminjaman.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2 rounded-xl flex items-center justify-center gap-2 transition-all w-full md:w-auto text-sm font-bold shadow-sm active:scale-95 border-none cursor-pointer h-[38px]">
                            Reset
                        </a>
                    @endif
                </div>

                <!-- Row 2: Rentang Tanggal (Below Row 1) -->
                <div class="space-y-1.5 pt-3 border-t border-gray-50">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest">RENTANG TANGGAL</label>
                    <div class="flex items-center gap-2 w-full md:max-w-md">
                        <div class="relative flex-1">
                            <input name="start_date" value="{{ request('start_date') }}" class="w-full pl-3 pr-2 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 text-gray-600" type="date" placeholder="dd/mm/yyyy"/>
                        </div>
                        <span class="text-gray-400 font-bold text-[10px] shrink-0 px-0.5">s/d</span>
                        <div class="relative flex-1">
                            <input name="end_date" value="{{ request('end_date') }}" class="w-full pl-3 pr-2 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all bg-gray-50 text-gray-600" type="date" placeholder="dd/mm/yyyy"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Metrics Card -->
        <div class="col-span-12 lg:col-span-4 bg-white rounded-2xl border border-gray-100 p-6 flex items-start justify-between shadow-sm hover:shadow-md transition-all relative overflow-hidden before:absolute before:top-0 before:left-0 before:right-0 before:h-[3px] before:bg-[#3F51B5]">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Dipinjam</h3>
                <div class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalDipinjam ?? 0 }}</div>
                <div class="text-xs text-green-500 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    Aktif Hari Ini
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#EDE7F6] text-[#3F51B5] flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">inventory</span>
            </div>
        </div>
    </div>

    <!-- BEGIN: Data Table Container -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">ID PINJAM</th>
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">PEMINJAM</th>
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">JURUSAN</th>
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">NAMA BARANG</th>
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center whitespace-nowrap">TGL PINJAM</th>
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center whitespace-nowrap">TGL KEMBALI</th>
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center whitespace-nowrap">STATUS</th>
                        @if(in_array(Auth::user()->role, ['Superadmin', 'Admin']))
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center whitespace-nowrap">KEMBALIKAN</th>
                        @endif
                        <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center whitespace-nowrap" style="min-width: 100px;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjamans as $pinjam)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4 font-bold text-[#3F51B5] text-xs whitespace-nowrap">PJ-{{ str_pad($pinjam->id_pinjam, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-4 font-bold text-gray-800 text-sm whitespace-nowrap">{{ $pinjam->nama_peminjam }}</td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded border border-blue-100">{{ $pinjam->kelas ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 font-medium max-w-[200px] truncate" title="{{ $pinjam->item->name ?? $pinjam->item_code }}">{{ $pinjam->item->name ?? $pinjam->item_code }}</td>
                        <td class="px-5 py-4 text-xs text-gray-500 font-medium text-center whitespace-nowrap">{{ $pinjam->waktu_pinjam ? $pinjam->waktu_pinjam->format('d M Y') : '-' }}</td>
                        <td class="px-5 py-4 text-xs text-gray-500 font-medium text-center whitespace-nowrap">{{ $pinjam->waktu_kembali ? $pinjam->waktu_kembali->format('d M Y') : '-' }}</td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            @if($pinjam->status == 'dipinjam')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Dipinjam
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Kembali
                                </span>
                                @if($pinjam->kondisi_saat_kembali)
                                    @php
                                        $kClass = match($pinjam->kondisi_saat_kembali) {
                                            'baik' => 'bg-green-100 text-green-700',
                                            'rusak_ringan' => 'bg-yellow-100 text-yellow-700',
                                            'rusak_berat' => 'bg-red-100 text-red-700',
                                            'hilang' => 'bg-gray-100 text-gray-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold {{ $kClass }} ml-1">
                                        {{ ucfirst(str_replace('_', ' ', $pinjam->kondisi_saat_kembali)) }}
                                    </span>
                                @endif
                            @endif
                        </td>
                        {{-- Kolom Kembalikan --}}
                        @if(in_array(Auth::user()->role, ['Superadmin', 'Admin']))
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            @if($pinjam->status == 'dipinjam')
                                <button type="button" onclick="openReturnModal({{ $pinjam->id_pinjam }}, '{{ $pinjam->item->name ?? $pinjam->item_code }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500 text-white hover:bg-green-600 rounded-lg transition-all shadow-sm active:scale-95 border-none cursor-pointer">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                    <span class="text-[11px] font-bold tracking-wide">KEMBALIKAN</span>
                                </button>
                            @else
                                <span class="text-gray-300 text-xs italic">—</span>
                            @endif
                        </td>
                        @endif
                        {{-- Kolom Aksi (Detail & Hapus) --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            @php
                                $detailData = [
                                    "id_pinjam" => "PJ-" . str_pad($pinjam->id_pinjam, 4, "0", STR_PAD_LEFT),
                                    "nama_peminjam" => $pinjam->nama_peminjam,
                                    "kelas" => $pinjam->kelas ?? "-",
                                    "item_name" => $pinjam->item->name ?? $pinjam->item_code,
                                    "item_code" => $pinjam->item_code,
                                    "waktu_pinjam" => $pinjam->waktu_pinjam ? \Carbon\Carbon::parse($pinjam->waktu_pinjam)->format("d M Y H:i") : "-",
                                    "waktu_kembali" => $pinjam->waktu_kembali ? \Carbon\Carbon::parse($pinjam->waktu_kembali)->format("d M Y H:i") : "-",
                                    "status" => $pinjam->status,
                                    "kondisi_saat_kembali" => $pinjam->kondisi_saat_kembali ? ucfirst(str_replace("_", " ", $pinjam->kondisi_saat_kembali)) : "-",
                                    "keterangan_kembali" => $pinjam->keterangan_kembali ?? "-",
                                    "catatan" => $pinjam->catatan ?? "-",
                                    "foto_kembali" => $pinjam->foto_kembali ? asset("storage/" . $pinjam->foto_kembali) : null
                                ];
                            @endphp
                            <div class="flex items-center justify-center gap-1">
                                <button type="button" data-detail="{{ json_encode($detailData) }}" onclick="openDetailModal(JSON.parse(this.getAttribute('data-detail')))" title="Detail Data" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                                @if(in_array(Auth::user()->role, ['Superadmin', 'Admin']))
                                <form action="{{ route('peminjaman.destroy', $pinjam->id_pinjam) }}" method="POST" data-confirm="Yakin ingin menghapus data peminjaman ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Data" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border-none bg-transparent cursor-pointer">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination & Info -->
        <div class="px-6 py-4 flex items-center justify-between bg-gray-50 border-t border-gray-100">
            <p class="text-xs text-gray-500 font-bold">Menampilkan {{ $peminjamans->firstItem() ?? 0 }}-{{ $peminjamans->lastItem() ?? 0 }} dari {{ $peminjamans->total() }} data</p>
            <div class="flex items-center gap-1.5">
                {{ $peminjamans->links('pagination::tailwind') }}
            </div>
        </div>
    </div>



    <script>
        function openReturnModal(idPinjam, itemName) {
            document.getElementById('returnItemName').textContent = itemName;
            document.getElementById('returnForm').action = '/data-peminjaman/' + idPinjam + '/return';
            // Reset form
            document.getElementById('returnForm').reset();
            document.getElementById('fotoPlaceholder').classList.remove('hidden');
            document.getElementById('fotoPreviewWrapper').classList.add('hidden');
            document.getElementById('returnModal').classList.remove('hidden');
        }
        function closeReturnModal() {
            document.getElementById('returnModal').classList.add('hidden');
        }

        function openDetailModal(data) {
            document.getElementById('detailIdPinjam').textContent = data.id_pinjam;
            document.getElementById('detailNama').textContent = data.nama_peminjam;
            document.getElementById('detailKelas').textContent = data.kelas;
            document.getElementById('detailItemName').textContent = data.item_name;
            document.getElementById('detailItemCode').textContent = data.item_code;
            document.getElementById('detailCatatan').textContent = data.catatan;
            document.getElementById('detailWaktuPinjam').textContent = data.waktu_pinjam;
            document.getElementById('detailWaktuKembali').textContent = data.waktu_kembali;
            
            const statusWrapper = document.getElementById('detailStatusWrapper');
            if (data.status === 'dipinjam') {
                statusWrapper.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Dipinjam</span>';
                document.getElementById('detailPengembalianWrapper').classList.add('hidden');
            } else {
                statusWrapper.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Dikembalikan</span>';
                document.getElementById('detailPengembalianWrapper').classList.remove('hidden');
                
                // Color formatting for kondisi
                const kondisiVal = data.kondisi_saat_kembali;
                let kClass = 'bg-gray-100 text-gray-700 border-gray-200';
                if(kondisiVal.toLowerCase() === 'baik') kClass = 'bg-green-100 text-green-700 border-green-200';
                else if(kondisiVal.toLowerCase() === 'rusak ringan') kClass = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                else if(kondisiVal.toLowerCase() === 'rusak berat') kClass = 'bg-red-100 text-red-700 border-red-200';
                
                const kElem = document.getElementById('detailKondisiKembali');
                kElem.className = `text-xs font-bold mt-0.5 inline-block px-2.5 py-1 rounded border ${kClass}`;
                kElem.textContent = kondisiVal;

                document.getElementById('detailKeteranganKembali').textContent = data.keterangan_kembali;
                
                const fotoWrapper = document.getElementById('detailFotoKembaliWrapper');
                const fotoImg = document.getElementById('detailFotoKembali');
                if (data.foto_kembali) {
                    fotoImg.src = data.foto_kembali;
                    fotoWrapper.classList.remove('hidden');
                } else {
                    fotoWrapper.classList.add('hidden');
                }
            }

            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
        function previewReturnPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('fotoPreview').src = e.target.result;
                    document.getElementById('fotoPlaceholder').classList.add('hidden');
                    document.getElementById('fotoPreviewWrapper').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

    </script>
@endsection

@push('page-modals')
    {{-- Return Modal with Kondisi, Keterangan, and Foto --}}
    <div id="returnModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeReturnModal()"></div>
        <div class="relative w-full max-w-[480px] bg-white rounded-2xl shadow-2xl flex flex-col font-sans max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Pengembalian Barang</h2>
                    <p class="text-xs text-gray-500 mt-0.5" id="returnItemName">-</p>
                </div>
                <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 bg-gray-50">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form id="returnForm" method="POST" enctype="multipart/form-data" class="px-6 py-5 overflow-y-auto bg-gray-50 flex-1 rounded-b-2xl space-y-4">
                @csrf

                {{-- Kondisi Barang --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Kondisi Barang Saat Dikembalikan <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-green-400 transition-all">
                            <input type="radio" name="kondisi_saat_kembali" value="baik" checked class="text-green-600 focus:ring-green-500">
                            <div>
                                <span class="text-sm font-semibold text-gray-800">Baik</span>
                                <p class="text-[10px] text-gray-400">Tidak ada kerusakan</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-yellow-400 transition-all">
                            <input type="radio" name="kondisi_saat_kembali" value="rusak_ringan" class="text-yellow-600 focus:ring-yellow-500">
                            <div>
                                <span class="text-sm font-semibold text-gray-800">Rusak Ringan</span>
                                <p class="text-[10px] text-gray-400">Kerusakan kecil, masih bisa digunakan</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-red-400 transition-all">
                            <input type="radio" name="kondisi_saat_kembali" value="rusak_berat" class="text-red-600 focus:ring-red-500">
                            <div>
                                <span class="text-sm font-semibold text-gray-800">Rusak Berat</span>
                                <p class="text-[10px] text-gray-400">Tidak bisa digunakan, perlu perbaikan</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-gray-400 transition-all">
                            <input type="radio" name="kondisi_saat_kembali" value="hilang" class="text-gray-600 focus:ring-gray-500">
                            <div>
                                <span class="text-sm font-semibold text-gray-800">Hilang</span>
                                <p class="text-[10px] text-gray-400">Barang tidak ditemukan</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Keterangan Pengembalian</label>
                    <textarea name="keterangan_kembali" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none bg-white text-gray-700 resize-none" placeholder="Tambahkan keterangan kondisi fisik, catatan kerusakan, dll..."></textarea>
                    <p class="text-[10px] text-gray-400 mt-1">Opsional. Jelaskan detail kondisi barang saat dikembalikan.</p>
                </div>

                {{-- Foto (Opsional) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Foto Kondisi Barang <span class="text-gray-400 text-[10px]">(opsional)</span></label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center cursor-pointer hover:border-[#3F51B5] transition-all bg-white" onclick="document.getElementById('fotoKembali').click()">
                        <input type="file" name="foto_kembali" id="fotoKembali" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewReturnPhoto(this)">
                        <div id="fotoPlaceholder">
                            <span class="material-symbols-outlined text-3xl text-gray-400">add_a_photo</span>
                            <p class="text-xs text-gray-500 mt-1">Klik untuk upload foto</p>
                            <p class="text-[10px] text-gray-400">JPG, PNG. Maks 2MB</p>
                        </div>
                        <div id="fotoPreviewWrapper" class="hidden">
                            <img id="fotoPreview" src="#" alt="Preview" class="max-h-32 mx-auto rounded-lg object-contain shadow-sm border border-gray-200">
                            <p class="text-[10px] text-blue-600 font-semibold mt-2">Klik untuk ganti foto</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeReturnModal()" class="px-5 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-all text-sm border border-gray-200">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all shadow-sm active:scale-95 text-sm">Konfirmasi Pengembalian</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="detailModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl flex flex-col font-sans max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Detail Peminjaman</h2>
                    <p class="text-xs text-gray-500 mt-0.5" id="detailIdPinjam">-</p>
                </div>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 bg-gray-50 border-none cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <div class="px-6 py-5 overflow-y-auto bg-gray-50 flex-1 rounded-b-2xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Informasi Peminjam</h3>
                        <div>
                            <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Nama Peminjam</p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5" id="detailNama">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Jurusan / Kelas</p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5"><span id="detailKelas" class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-bold rounded border border-blue-100">-</span></p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Catatan</p>
                            <p class="text-sm font-medium text-gray-700 mt-0.5" id="detailCatatan">-</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Informasi Barang</h3>
                        <div>
                            <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Nama Barang</p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5" id="detailItemName">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Kode Barang</p>
                            <p class="text-sm font-mono font-semibold text-gray-600 mt-0.5" id="detailItemCode">-</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Status</p>
                            <div class="mt-1" id="detailStatusWrapper"></div>
                        </div>
                    </div>

                    <div class="space-y-4 md:col-span-2">
                        <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Waktu Peminjaman</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Waktu Pinjam</p>
                                <p class="text-sm font-semibold text-gray-900 mt-0.5 flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-amber-500">schedule</span> <span id="detailWaktuPinjam">-</span></p>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Waktu Kembali</p>
                                <p class="text-sm font-semibold text-gray-900 mt-0.5 flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-green-500">task_alt</span> <span id="detailWaktuKembali">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 md:col-span-2 hidden" id="detailPengembalianWrapper">
                        <h3 class="text-sm font-bold text-gray-800 border-b pb-2 mt-2">Data Pengembalian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Kondisi Saat Kembali</p>
                                <p class="text-sm font-semibold text-gray-900 mt-0.5 inline-block px-2 py-0.5 bg-gray-100 rounded text-gray-800 border border-gray-200" id="detailKondisiKembali">-</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider">Keterangan</p>
                                <p class="text-sm font-medium text-gray-700 mt-0.5 bg-white p-2 rounded border border-gray-200" id="detailKeteranganKembali">-</p>
                            </div>
                            <div class="md:col-span-2" id="detailFotoKembaliWrapper">
                                <p class="text-[11px] text-gray-500 uppercase font-bold tracking-wider mb-2">Foto Saat Dikembalikan</p>
                                <img id="detailFotoKembali" src="" alt="Foto Pengembalian" class="max-h-48 rounded-lg border border-gray-200 shadow-sm object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-white flex justify-end">
                <button type="button" onclick="closeDetailModal()" class="px-6 py-2.5 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-900 transition-all shadow-sm active:scale-95 text-sm border-none cursor-pointer">Tutup</button>
            </div>
        </div>
    </div>
@endpush

