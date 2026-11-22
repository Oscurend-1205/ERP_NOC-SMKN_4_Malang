<?php $__env->startSection('title', 'Data Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <?php if(session('success')): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-xl flex items-center gap-3 border border-green-200">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span class="font-medium text-sm"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-50 text-red-700 p-4 rounded-xl flex items-center gap-3 border border-red-200">
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span class="font-medium text-sm"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-xs relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-700 via-indigo-600 to-blue-800"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <!-- <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-[#3F51B5] text-[11px] font-mono font-semibold uppercase tracking-wider mb-2">
                    <span>SISTEM MANAJEMEN INVENTARIS</span>
                    <span class="w-1 h-1 rounded-full bg-indigo-400"></span>
                    <span>ASET NOC SMKN 4 MALANG</span>
                </div> -->
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Data Barang & Asset Tagging</h2>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <?php if(in_array(Auth::user()->role, ['Superadmin', 'Admin'])): ?>
                <a href="<?php echo e(route('items.barang-masuk')); ?>" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all text-xs border border-gray-200">
                    <span class="material-symbols-outlined text-[18px] text-gray-600">south_east</span>
                    <span>Barang Masuk</span>
                </a>
                <a href="<?php echo e(route('items.barang-keluar')); ?>" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all text-xs border border-gray-200">
                            <span class="material-symbols-outlined text-[18px] text-gray-600">north_west</span>
                            <span>Barang Keluar</span>
                        </a>
                        <button type="button" onclick="toggleAddBarangModal(true)" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#3F51B5] hover:bg-[#3949AB] text-white font-bold rounded-xl transition-all shadow-sm text-xs cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            <span>+ Registrasi Barang Baru</span>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5">
                
                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-xs space-y-1 relative overflow-hidden">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Total Katalog</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['total_katalog'] ?? 0)); ?></div>
                    <p class="text-[11px] text-gray-400">Model / Jenis terdaftar</p>
                </div>

                
                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-xs space-y-1 relative overflow-hidden">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Unit Fisik Real</span>
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">devices</span>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-indigo-700"><?php echo e(number_format($stats['total_unit'] ?? 0)); ?></div>
                    <p class="text-[11px] text-gray-400">Total akumulasi unit fisik</p>
                </div>

                
                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-xs space-y-1 relative overflow-hidden">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-700">Unit Tersedia</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-emerald-700"><?php echo e(number_format($stats['total_tersedia'] ?? 0)); ?></div>
                    <p class="text-[11px] text-emerald-600 font-medium">Siap dipinjam & digunakan</p>
                </div>

                
                <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-xs space-y-1 relative overflow-hidden">
                    <div class="flex items-center justify-between text-gray-500">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-700">Pinjam / Servis</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">published_with_changes</span>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-amber-700">
                        <?php echo e(number_format(($stats['total_dipinjam'] ?? 0) + ($stats['total_perawatan'] ?? 0))); ?>

                    </div>
                    <p class="text-[11px] text-amber-600 font-medium">
                        <?php echo e(number_format($stats['total_dipinjam'] ?? 0)); ?> Pinjam • <?php echo e(number_format($stats['total_perawatan'] ?? 0)); ?> Servis
                    </p>
                </div>

                
                <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-gray-900 to-indigo-950 p-4 rounded-2xl border border-gray-800 text-white shadow-xs space-y-1 relative overflow-hidden">
                    <div class="flex items-center justify-between text-gray-400">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-indigo-300">Valuasi Aset</span>
                        <div class="w-8 h-8 rounded-xl bg-white/10 text-emerald-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">payments</span>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-emerald-400 tracking-tight">
                        Rp <?php echo e(number_format($stats['total_valuasi'] ?? 0, 0, ',', '.')); ?>

                    </div>
                    <p class="text-[10px] text-gray-400">Total nilai inventaris terdaftar</p>
                </div>
            </div>

            
            <form id="filterForm" action="<?php echo e(route('items.index')); ?>" method="GET" class="bg-white p-4 rounded-2xl shadow-xs border border-gray-200 flex flex-wrap items-center gap-3">
                <div class="relative grow min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                    <input type="text" name="search" class="w-full pl-10 pr-4 py-2 bg-gray-50 focus:bg-white rounded-xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-[#3F51B5] focus:border-[#3F51B5] outline-none transition-all" placeholder="Cari nama barang, kode prefix, merek, atau model..." value="<?php echo e(request('search')); ?>">
                </div>
                <select name="category_id" class="px-3.5 py-2 bg-gray-50 focus:bg-white rounded-xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="location_id" class="px-3.5 py-2 bg-gray-50 focus:bg-white rounded-xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer">
                    <option value="">Semua Lokasi Ruangan</option>
                    <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($loc->id); ?>" <?php echo e(request('location_id') == $loc->id ? 'selected' : ''); ?>><?php echo e($loc->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="condition" class="px-3.5 py-2 bg-gray-50 focus:bg-white rounded-xl border border-gray-200 text-xs sm:text-sm focus:ring-2 focus:ring-[#3F51B5] outline-none cursor-pointer">
                    <option value="">Semua Kondisi Unit</option>
                    <option value="baik" <?php echo e(request('condition') == 'baik' ? 'selected' : ''); ?>>Baik</option>
                    <option value="rusak_ringan" <?php echo e(request('condition') == 'rusak_ringan' ? 'selected' : ''); ?>>Rusak Ringan</option>
                    <option value="rusak_berat" <?php echo e(request('condition') == 'rusak_berat' ? 'selected' : ''); ?>>Rusak Berat</option>
                    <option value="hilang" <?php echo e(request('condition') == 'hilang' ? 'selected' : ''); ?>>Hilang</option>
                </select>
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-900 transition-all text-xs shadow-2xs">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span>
                    <span>Terapkan Filter</span>
                </button>
                <?php if(request()->hasAny(['search', 'category_id', 'location_id', 'condition', 'status'])): ?>
                    <a href="<?php echo e(route('items.index')); ?>" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 transition-all text-xs border border-red-200">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                        <span>Reset Filter</span>
                    </a>
                <?php endif; ?>
            </form>

            
            <div id="tableContainer" class="bg-white rounded-2xl shadow-xs border border-gray-200 overflow-hidden relative">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs md:text-sm">
                        <thead class="bg-gray-100/80 border-b border-gray-200 text-gray-700">
                            <tr>
                                <th class="py-3.5 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider w-12 text-center">No</th>
                                <th class="py-3.5 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Kode Prefix</th>
                                <th class="py-3.5 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Identitas Barang</th>
                                <th class="py-3.5 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Kategori</th>
                                <th class="py-3.5 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider">Komposisi Kondisi Unit</th>
                                <th class="py-3.5 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">Est. Valuasi Aset</th>
                                <th class="py-3.5 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Total Unit</th>
                                <th class="py-3.5 px-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Aksi Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="py-3.5 px-4 text-xs text-gray-500 text-center font-mono"><?php echo e($items->firstItem() + $i); ?></td>
                                    <td class="py-3.5 px-4">
                                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 border border-gray-200 text-gray-800 font-mono font-bold text-xs">
                                            <span class="material-symbols-outlined text-gray-400 text-[14px]">tag</span>
                                            <span><?php echo e($item->prefix); ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <?php if($item->image): ?>
                                                <img src="<?php echo e(Storage::url($item->image)); ?>" alt="<?php echo e($item->name); ?>" class="w-10 h-10 rounded-lg object-cover border border-gray-200 shrink-0">
                                            <?php else: ?>
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center border border-gray-200 shrink-0">
                                                    <span class="material-symbols-outlined text-[20px]">devices</span>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="font-bold text-gray-900 text-xs sm:text-sm"><?php echo e($item->name); ?></div>
                                                <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-0.5">
                                                    <span>Merek: <strong class="text-gray-700"><?php echo e($item->brand ?? '-'); ?></strong></span>
                                                    <span>•</span>
                                                    <span>Model: <strong class="text-gray-700"><?php echo e($item->model ?? '-'); ?></strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-medium border border-blue-100">
                                            <?php echo e($item->category->name); ?>

                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex flex-wrap gap-1 text-[11px]">
                                            <?php if($item->total_baik > 0): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-medium border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <?php echo e($item->total_baik); ?> Baik
                                                </span>
                                            <?php endif; ?>
                                            <?php if($item->total_rusak_ringan > 0): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 font-medium border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    <?php echo e($item->total_rusak_ringan); ?> Rusak Rgn
                                                </span>
                                            <?php endif; ?>
                                            <?php if($item->total_rusak_berat > 0): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 font-medium border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    <?php echo e($item->total_rusak_berat); ?> Rusak Brt
                                                </span>
                                            <?php endif; ?>
                                            <?php if($item->total_hilang > 0): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 font-medium border border-gray-300">
                                                    <?php echo e($item->total_hilang); ?> Hilang
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono text-xs font-semibold text-gray-800">
                                        <?php if($item->total_value > 0): ?>
                                            Rp <?php echo e(number_format($item->total_value, 0, ',', '.')); ?>

                                        <?php else: ?>
                                            <span class="text-gray-400 font-sans text-[11px]">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex items-center justify-center min-w-8 px-2 py-1 bg-indigo-50 text-[#3F51B5] font-extrabold text-xs rounded-lg border border-indigo-100">
                                            <?php echo e($item->total_stock); ?>

                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="openUnitsModal('<?php echo e(addslashes($item->name)); ?>', '<?php echo e(addslashes($item->brand ?? '')); ?>', '<?php echo e(addslashes($item->model ?? '')); ?>', '<?php echo e($item->category_id); ?>', '<?php echo e(addslashes($item->sub_prefix ?? '')); ?>')" class="px-3 py-1.5 text-[#3F51B5] bg-indigo-50 hover:bg-[#3F51B5] hover:text-white rounded-lg transition-colors flex items-center gap-1.5 font-bold text-xs border border-indigo-100 shadow-2xs cursor-pointer" title="Lihat Rincian Unit Fisik">
                                                <span class="material-symbols-outlined text-[16px]">format_list_bulleted</span>
                                                <span>Rincian Unit</span>
                                            </button>
                                            <a href="<?php echo e(route('items.show', $item->id)); ?>" class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors border border-transparent" title="Lihat Detail Master">
                                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="py-20 text-center text-gray-400">
                                        <span class="material-symbols-outlined text-[56px] text-gray-300 mb-2">inventory_2</span>
                                        <div class="font-bold text-gray-700 text-sm">Tidak ada data barang terdaftar</div>
                                        <div class="text-xs text-gray-400 mt-1">Coba sesuaikan pencarian atau tambahkan barang baru ke inventaris.</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($items->hasPages()): ?>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        <?php echo e($items->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
        <!-- Modal Tambah Barang Baru -->
        <div id="addBarangModal" class="<?php echo e($errors->any() ? '' : 'hidden'); ?> fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="toggleAddBarangModal(false)"></div>
        
        <div class="relative w-full max-w-[1000px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh] font-sans">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-[#3F51B5] to-[#5C6BC0]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-[22px]">add_box</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Tambah Barang Baru</h2>
                        <p class="text-xs text-white/70 mt-0.5">Daftarkan barang elektronik baru ke inventaris</p>
                    </div>
                </div>
                <button onclick="toggleAddBarangModal(false)" class="text-white/70 hover:text-white transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form id="addBarangForm" action="<?php echo e(route('items.store')); ?>" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                <?php echo csrf_field(); ?>
                
                <!-- STEP 1 -->
                <div id="formStep1" class="flex flex-col flex-1 overflow-hidden">
                <div class="px-6 py-5 space-y-5 overflow-y-auto flex-1">

                    
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                        <div class="flex items-center gap-4">
                            <label class="flex items-center cursor-pointer gap-2">
                                <input type="radio" name="item_type" value="new" checked class="form-radio h-4 w-4 text-[#3F51B5] focus:ring-[#3F51B5] border-gray-300">
                                <span class="text-sm text-gray-700 font-medium">Barang Baru</span>
                            </label>
                            <label class="flex items-center cursor-pointer gap-2">
                                <input type="radio" name="item_type" value="existing" class="form-radio h-4 w-4 text-[#3F51B5] focus:ring-[#3F51B5] border-gray-300">
                                <span class="text-sm text-gray-700 font-medium">Barang Sudah Ada</span>
                            </label>
                        </div>
                    </div>

                    
                    <div id="existing_item_selector" class="hidden">
                        <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Pilih Barang yang Sudah Ada <span class="text-red-500">*</span></label>
                        <select id="existing_item_id" class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm cursor-pointer">
                            <option value="">-- Pilih Barang --</option>
                            <?php if(isset($existingItems)): ?>
                                <?php $__currentLoopData = $existingItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $existing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($existing->name); ?>" data-brand="<?php echo e($existing->brand); ?>" data-model="<?php echo e($existing->model); ?>" data-category="<?php echo e($existing->category_id); ?>" data-sub-prefix="<?php echo e($existing->sub_prefix); ?>">
                                        <?php echo e($existing->name); ?><?php echo e($existing->brand ? ' - '.$existing->brand : ''); ?><?php echo e($existing->model ? ' ('.$existing->model.')' : ''); ?><?php echo e($existing->sub_prefix ? ' ['.$existing->sub_prefix.']' : ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1.5">Merek, Model, dan Kategori akan terisi otomatis. Kode Inventaris di-generate berurutan.</p>
                    </div>

                    
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">inventory_2</span>
                            <h3 class="text-sm font-bold text-gray-800">Informasi Barang</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="block text-[13px] font-semibold text-gray-700">Nama Barang <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="Contoh: Access Point UniFi, Router MikroTik RB750Gr3" value="<?php echo e(old('name')); ?>" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm <?php echo e($errors->has('name') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'); ?>">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[13px] font-semibold text-gray-700">Kategori <span class="text-red-500">*</span></label>
                                    <button type="button" onclick="openQuickCategoryModal()" class="text-[11px] text-[#3F51B5] font-semibold hover:underline flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">add_circle</span> Kategori Baru
                                    </button>
                                </div>
                                <select name="category_id" id="addBarangCategoryId" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer <?php echo e($errors->has('category_id') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'); ?>">
                                    <option value="">Pilih Kategori</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id); ?>" data-prefix="<?php echo e($cat->prefix); ?>" <?php echo e(old('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?> (<?php echo e($cat->prefix); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <input type="hidden" name="sub_prefix" id="addBarangSubPrefix" maxlength="10" value="<?php echo e(old('sub_prefix')); ?>">

                            
                            <div class="space-y-1.5" id="codePreviewWrapper">
                                <label class="block text-[13px] font-semibold text-gray-700">Kode Inventaris</label>
                                <div class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] bg-gray-50 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-gray-400">tag</span>
                                    <code id="codePreviewText" class="font-mono font-bold text-indigo-600 tracking-wider">Pilih kategori terlebih dahulu</code>
                                </div>
                                <p class="text-[11px] text-gray-400">Otomatis di-generate. Format: PREFIX-[SUBPREFIX-]NOMOR</p>
                            </div>

                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Merek</label>
                                <input type="text" name="brand" placeholder="Contoh: Cisco, MikroTik, TP-Link" value="<?php echo e(old('brand')); ?>" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm <?php echo e($errors->has('brand') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'); ?>">
                                <?php $__errorArgs = ['brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Model</label>
                                <input type="text" name="model" placeholder="Contoh: RB750Gr3, EAP225, TL-SG1024D" value="<?php echo e(old('model')); ?>" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm <?php echo e($errors->has('model') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'); ?>">
                                <?php $__errorArgs = ['model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>


                        </div>
                    </div>

                    
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">location_on</span>
                            <h3 class="text-sm font-bold text-gray-800">Lokasi & Stok</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                                <select name="location_id" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer <?php echo e($errors->has('location_id') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'); ?>">
                                    <option value="">Pilih Lokasi</option>
                                    <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($loc->id); ?>" <?php echo e(old('location_id') == $loc->id ? 'selected' : ''); ?>><?php echo e($loc->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['location_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Jumlah Unit <span class="text-red-500">*</span></label>
                                <input type="number" name="quantity" required min="1" value="<?php echo e(old('quantity', 1)); ?>" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 shadow-sm <?php echo e($errors->has('quantity') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'); ?>">
                                <p class="text-[11px] text-gray-400">Setiap unit akan mendapat kode inventaris unik</p>
                                <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>



                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Status <span class="text-red-500">*</span></label>
                                <select name="status" required 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer <?php echo e($errors->has('status') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'); ?>">
                                    <option value="tersedia" <?php echo e(old('status', 'tersedia') == 'tersedia' ? 'selected' : ''); ?>>Tersedia</option>
                                    <option value="dipinjam" <?php echo e(old('status') == 'dipinjam' ? 'selected' : ''); ?>>Dipinjam</option>
                                    <option value="maintenance" <?php echo e(old('status') == 'maintenance' ? 'selected' : ''); ?>>Maintenance</option>
                                    <option value="dimusnahkan" <?php echo e(old('status') == 'dimusnahkan' ? 'selected' : ''); ?>>Dimusnahkan</option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">shopping_cart</span>
                            <h3 class="text-sm font-bold text-gray-800">Data Perolehan</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Supplier</label>
                                <select name="supplier_id" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Supplier</option>
                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sup->id); ?>" <?php echo e(old('supplier_id') == $sup->id ? 'selected' : ''); ?>><?php echo e($sup->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Asal Barang</label>
                                <select name="asal_barang_id" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Asal Barang</option>
                                    <?php $__currentLoopData = $asalBarangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($asal->id); ?>" <?php echo e(old('asal_barang_id') == $asal->id ? 'selected' : ''); ?>><?php echo e($asal->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Kondisi Barang (Master)</label>
                                <select name="kondisi_barang_id" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm cursor-pointer border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Kondisi</option>
                                    <?php $__currentLoopData = $kondisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($k->id); ?>" <?php echo e(old('kondisi_barang_id') == $k->id ? 'selected' : ''); ?>><?php echo e($k->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Tanggal Pembelian</label>
                                <input type="date" name="purchase_date" value="<?php echo e(old('purchase_date')); ?>" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 text-gray-700 bg-white shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <?php $__errorArgs = ['purchase_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="block text-[13px] font-semibold text-gray-700">Harga Beli (per unit)</label>
                                <div class="relative flex items-center rounded-lg border shadow-sm focus-within:ring-1 bg-white overflow-hidden border-gray-300 focus-within:ring-blue-500 focus-within:border-blue-500">
                                    <span class="bg-gray-50 px-3 py-2.5 text-[13px] text-gray-500 border-r border-gray-200 select-none font-semibold">Rp</span>
                                    <input type="text" name="purchase_price" id="purchase_price_input" placeholder="0" value="<?php echo e(old('purchase_price')); ?>" 
                                        class="w-full border-0 pl-3 pr-1 py-2.5 text-[13px] focus:ring-0 focus:outline-none placeholder-gray-400">
                                    <span class="text-[13px] text-gray-500 pr-3 select-none">,00</span>
                                </div>
                                <?php $__errorArgs = ['purchase_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">notes</span>
                            <h3 class="text-sm font-bold text-gray-800">Informasi Tambahan</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Catatan</label>
                                <textarea name="notes" placeholder="Catatan tambahan (opsional)" 
                                    class="w-full border rounded-lg px-3 py-2.5 text-[13px] focus:outline-none focus:ring-1 placeholder-gray-400 shadow-sm h-16 border-gray-300 focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="space-y-1.5">
                                <label class="block text-[13px] font-semibold text-gray-700">Foto Barang</label>
                                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" 
                                    class="w-full border rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 bg-white text-gray-700 shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[11px] text-gray-400">Format: JPG, PNG. Maks: 2MB</p>
                                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-red-500 text-xs mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Step 1 -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 mt-auto">
                    <button type="button" onclick="toggleAddBarangModal(false)" class="px-5 py-2.5 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="window.nextStep()" class="px-5 py-2.5 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm flex items-center gap-2">
                        Lanjut
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </button>
                </div>
                </div> <!-- END STEP 1 -->
                
                <!-- STEP 2 -->
                <div id="formStep2" class="hidden flex flex-col flex-1 overflow-hidden">
                    <div class="px-6 py-5 space-y-5 overflow-y-auto flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-[#3F51B5] text-[18px]">list_alt</span>
                            <h3 class="text-sm font-bold text-gray-800">Detail Serial Number & Kondisi Tiap Unit</h3>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Silakan isi serial number (opsional) dan kondisi untuk tiap unit barang yang ditambahkan.</p>
                        
                        <div id="dynamicUnitInputs" class="space-y-4">
                            <!-- Dynamic inputs inserted by JS -->
                        </div>
                    </div>
                    
                    <!-- Footer Step 2 -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between mt-auto">
                        <button type="button" onclick="window.prevStep()" class="px-5 py-2.5 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                            Kembali
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] transition-colors shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">save</span>
                            Simpan Barang
                        </button>
                    </div>
                </div> <!-- END STEP 2 -->
            </form>
        </div>
    </div>

    <!-- Modal QR Code Barang -->
    <div id="qrCodeModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop Blur -->
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeQrModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-[400px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col font-sans">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white">
                <div class="overflow-hidden">
                    <h2 class="text-lg font-bold text-gray-900">QR Code Barang</h2>
                    <p id="qrModalSubtitle" class="text-xs text-gray-500 mt-0.5 truncate max-w-[280px]">Generate QR Code</p>
                </div>
                <button onclick="closeQrModal()" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 flex-shrink-0">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Form Body -->
            <div class="px-6 py-8 flex flex-col items-center justify-center bg-gray-50/50">
                <!-- Wrapper id untuk div QR Code -->
                <div id="itemQrContainer" class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-4 flex items-center justify-center min-w-[232px] min-h-[232px]">
                    <!-- QR akan di-render di sini -->
                </div>
                <div id="qrCodeText" class="text-lg font-bold text-[#3F51B5] font-mono tracking-widest bg-[#E8EAF6] px-4 py-1.5 rounded-lg mb-2"></div>
                <p class="text-[11px] text-gray-500 text-center px-4 mt-2">Cetak atau download QR ini dan tempelkan pada fisik barang untuk discan saat peminjaman.</p>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-white border-t border-gray-100 flex items-center justify-center gap-3">
                <button type="button" onclick="downloadQrCode()" class="w-full flex justify-center items-center gap-2 px-5 py-2.5 text-[14px] font-bold text-white bg-[#3F51B5] rounded-xl hover:bg-[#3949AB] transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-[#3F51B5]">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                    Download QR Code
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Rincian Unit -->
    <div id="unitsModal" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeUnitsModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative w-full max-w-[1050px] bg-white rounded-2xl shadow-2xl flex flex-col font-sans max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white rounded-t-2xl">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar Unit Barang Spesifik</h2>
                    <p id="unitsModalSubtitle" class="text-xs text-gray-500 mt-0.5">Memuat...</p>
                </div>
                <button onclick="closeUnitsModal()" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 bg-gray-50">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Body -->
            <div class="px-5 py-5 overflow-y-auto bg-gray-50 flex-1 rounded-b-2xl">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200">
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider w-10 text-center">No</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Kode Spesifik</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Serial Number</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">Kondisi</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">Lokasi</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center">Tgl Beli</th>
                                <th class="py-2.5 px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider text-center w-28">Kelola</th>
                            </tr>
                        </thead>
                        <tbody id="unitsTableBody" class="divide-y divide-gray-100">
                            <tr><td colspan="8" class="text-center py-6 text-gray-400 text-sm">Pilih barang untuk melihat daftar unit</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Quick Add Kategori -->
    <div id="quickCategoryModal" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeQuickCategoryModal()"></div>
        <div class="relative w-full max-w-[420px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col font-sans">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Tambah Kategori Baru</h2>
                    <p class="text-[11px] text-gray-500 mt-0.5">Buat kategori dan prefix langsung dari sini</p>
                </div>
                <button onclick="closeQuickCategoryModal()" class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form id="quickCategoryForm" class="px-6 py-5 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="qc_name" required placeholder="Contoh: Router, Switch, Kabel" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-semibold text-gray-700">Prefix Kode <span class="text-red-500">*</span></label>
                    <input type="text" id="qc_prefix" required maxlength="10" placeholder="Contoh: RTR, SWT, KBL" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] font-mono font-bold uppercase focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm" style="text-transform:uppercase">
                    <p class="text-[11px] text-gray-400">Awalan kode barang (maks 10 karakter, akan otomatis kapital)</p>
                </div>
                <div id="qc_error" class="hidden bg-red-50 text-red-600 text-xs p-2.5 rounded-lg border border-red-200"></div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeQuickCategoryModal()" class="px-4 py-2 text-[13px] font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" id="qc_submit" class="px-4 py-2 text-[13px] font-bold text-white bg-[#3F51B5] rounded-lg hover:bg-[#3949AB] shadow-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">save</span> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Pass Blade-injected values to the external JS module
        window._itemsConfig = {
            unitsRoute: "<?php echo e(route('items.units')); ?>",
            nextCodeRoute: "<?php echo e(route('items.next-code')); ?>",
            quickCategoryRoute: "<?php echo e(route('items.quick-category')); ?>",
            csrfToken: "<?php echo e(csrf_token()); ?>",
            categoriesData: <?php echo json_encode($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'prefix' => $c->prefix, 'last_code_number' => $c->last_code_number])); ?>

        };
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/turbo-navigation.js', 'resources/js/items-page.js']); ?>
<?php $__env->stopPush(); ?>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\ERP NOC - SMKN 4 Malang\resources\views/items/index.blade.php ENDPATH**/ ?>