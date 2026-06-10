@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
<!-- BEGIN: Page Title & Action -->
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Data Supplier</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data supplier untuk NOC SMKN 4 Malang.</p>
    </div>
    @if(auth()->user()->role === 'Superadmin')
    <button onclick="document.getElementById('addSupplierModal').classList.remove('hidden')" class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Supplier
    </button>
    @endif
</div>
<!-- END: Page Title & Action -->

<!-- BEGIN: Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="factory" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Supplier</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $suppliers->total() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mr-4 flex-shrink-0">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Supplier Aktif</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $suppliers->total() }}</p>
        </div>
    </div>
</div>
<!-- END: Statistics Cards -->

<!-- BEGIN: Data Table Section -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Table Toolbar -->
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <div class="relative w-72">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <i class="w-4 h-4" data-lucide="search"></i>
            </span>
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari nama supplier atau PIC...">
        </div>
        <div class="flex items-center space-x-3">
            <button class="flex items-center px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
            </button>
            <button class="flex items-center px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i> Export
            </button>
        </div>
    </div>

    <!-- The Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Supplier</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">PIC</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Telepon/WA</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Alamat</th>
                    @if(auth()->user()->role === 'Superadmin')
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($suppliers as $supplier)
                <tr class="{{ $loop->even ? 'bg-slate-50/30' : '' }} table-row-hover transition-colors">
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $loop->iteration + $suppliers->firstItem() - 1 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $supplier->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $supplier->pic ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        @if($supplier->phone)
                        <div class="flex items-center gap-2">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-green-500"></i> {{ $supplier->phone }}
                        </div>
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $supplier->email ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-[200px]">{{ $supplier->address ?? '-' }}</td>
                    @if(auth()->user()->role === 'Superadmin')
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <button onclick="openEditSupplierModal({{ $supplier->id }}, '{{ addslashes($supplier->name) }}', '{{ addslashes($supplier->pic ?? '') }}', '{{ addslashes($supplier->phone ?? '') }}', '{{ addslashes($supplier->email ?? '') }}', '{{ addslashes($supplier->address ?? '') }}')" class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus supplier ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 border-none bg-transparent cursor-pointer"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'Superadmin' ? 7 : 6 }}" class="px-6 py-10 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mb-3"></i>
                            <p class="text-sm font-medium">Belum ada data supplier</p>
                            <p class="text-xs mt-1 text-slate-400">Data supplier yang ditambahkan akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Table Pagination -->
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $suppliers->links() }}
    </div>
</section>
<!-- END: Data Table Section -->

<!-- Modal Tambah Supplier -->
<div id="addSupplierModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <!-- Backdrop Blur -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addSupplierModal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Tambah Supplier</h2>
            <button onclick="document.getElementById('addSupplierModal').classList.add('hidden')" type="button" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Form Body -->
        <form action="{{ route('supplier.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Supplier</label>
                    <input type="text" name="name" required placeholder="Masukkan nama supplier" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">PIC</label>
                    <input type="text" name="pic" placeholder="Nama kontak person" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">No. Telepon/WA</label>
                    <input type="text" name="phone" placeholder="Contoh: 081234567890" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Email</label>
                    <input type="email" name="email" placeholder="email@contoh.com" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Alamat</label>
                    <textarea name="address" rows="3" placeholder="Alamat lengkap supplier" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addSupplierModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<!-- Modal Edit Supplier -->
<div id="editSupplierModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editSupplierModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Edit Supplier</h2>
            <button type="button" onclick="document.getElementById('editSupplierModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editSupplierForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Supplier</label>
                    <input type="text" id="edit_supplier_name" name="name" required placeholder="Masukkan nama supplier" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">PIC</label>
                    <input type="text" id="edit_supplier_pic" name="pic" placeholder="Nama kontak person" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">No. Telepon/WA</label>
                    <input type="text" id="edit_supplier_phone" name="phone" placeholder="Contoh: 081234567890" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Email</label>
                    <input type="email" id="edit_supplier_email" name="email" placeholder="email@contoh.com" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Alamat</label>
                    <textarea id="edit_supplier_address" name="address" rows="3" placeholder="Alamat lengkap supplier" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editSupplierModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditSupplierModal(id, name, pic, phone, email, address) {
        document.getElementById('editSupplierForm').action = `/data-supplier/${id}`;
        document.getElementById('edit_supplier_name').value = name;
        document.getElementById('edit_supplier_pic').value = pic;
        document.getElementById('edit_supplier_phone').value = phone;
        document.getElementById('edit_supplier_email').value = email;
        document.getElementById('edit_supplier_address').value = address;
        document.getElementById('editSupplierModal').classList.remove('hidden');
    }
</script>
@endpush