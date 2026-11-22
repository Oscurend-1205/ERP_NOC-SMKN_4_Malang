<?php $__env->startSection('title', 'Stok Opname'); ?>

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
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Stok Opname</h2>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="<?php echo e(route('stock-take.create')); ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#3F51B5] hover:bg-[#3949AB] text-white font-bold rounded-xl transition-all shadow-sm text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Buat Sesi Baru</span>
                </a>
            </div>
        </div>
    </div>

<!-- Status Filter -->
<div class="flex gap-2 mb-6 flex-wrap">
    <a href="<?php echo e(route('stock-take.index')); ?>" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors <?php echo e(!request('status') ? 'bg-[#1A1E35] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'); ?>">
        Semua
    </a>
    <?php $__currentLoopData = ['draft' => 'Draft', 'in_progress' => 'Berjalan', 'completed' => 'Selesai', 'approved' => 'Disetujui']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('stock-take.index', ['status' => $key])); ?>" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors <?php echo e(request('status') === $key ? 'bg-[#1A1E35] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'); ?>">
        <?php echo e($label); ?>

    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Lokasi</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Progress</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Tanggal</th>
                    <th class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $stockTakes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3.5 px-5">
                        <span class="text-sm font-bold text-[#3F51B5]"><?php echo e($st->code); ?></span>
                    </td>
                    <td class="py-3.5 px-5">
                        <div class="text-sm font-semibold text-gray-800"><?php echo e($st->title); ?></div>
                        <div class="text-xs text-gray-400 mt-0.5">oleh <?php echo e($st->startedByUser->name ?? '-'); ?></div>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <span class="text-sm text-gray-600"><?php echo e($st->location->name ?? 'Semua Lokasi'); ?></span>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold <?php echo e($st->status_color); ?>">
                            <?php echo e($st->status_label); ?>

                        </span>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <?php
                            $total = $st->total_items_count;
                            $checked = $st->checked_count;
                            $pct = $total > 0 ? round(($checked / $total) * 100) : 0;
                        ?>
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all <?php echo e($pct === 100 ? 'bg-green-500' : 'bg-[#3F51B5]'); ?>" style="width: <?php echo e($pct); ?>%"></div>
                            </div>
                            <span class="text-xs text-gray-500 font-medium"><?php echo e($checked); ?>/<?php echo e($total); ?></span>
                        </div>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <div class="text-sm text-gray-600"><?php echo e($st->created_at->format('d/m/Y')); ?></div>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="<?php echo e(route('stock-take.show', $st->id)); ?>" class="p-1.5 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg inline-flex items-center justify-center transition-colors" title="Lihat Detail">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </a>
                            <?php if($st->status === 'draft'): ?>
                            <form method="POST" action="<?php echo e(route('stock-take.destroy', $st->id)); ?>" onsubmit="return confirm('Hapus sesi ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg inline-flex items-center justify-center transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">fact_check</span>
                        <p class="text-sm font-medium">Belum ada sesi stok opname</p>
                        <a href="<?php echo e(route('stock-take.create')); ?>" class="text-sm text-[#3F51B5] font-semibold mt-1 inline-block hover:underline">Buat sesi pertama →</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($stockTakes->hasPages()): ?>
    <div class="px-5 py-4 border-t border-gray-100">
        <?php echo e($stockTakes->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\ERP NOC - SMKN 4 Malang\resources\views/stock-take/index.blade.php ENDPATH**/ ?>