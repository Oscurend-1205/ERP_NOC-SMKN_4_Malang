<?php $__env->startSection('title', 'Data Master Jurusan'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Title and Actions -->
<div class="flex justify-between items-start mb-6" data-purpose="page-title-section">
<div>
<h1 class="text-3xl font-bold text-slate-900">Data Master Jurusan</h1>
<p class="text-sm text-slate-500 mt-1">Kelola daftar program keahlian dan jurusan sekolah.</p>
</div>
    <?php if(auth()->user()->role === 'Superadmin'): ?>
    <button onclick="document.getElementById('addJurusanModal').classList.remove('hidden')" class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Jurusan
    </button>
<?php endif; ?>
</div>
<!-- BEGIN: Table Container Card -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-purpose="table-container">
<!-- Table Toolbar -->
<div class="p-4 border-b border-slate-100 flex items-center justify-between" data-purpose="table-toolbar">
<form method="GET" action="<?php echo e(route('jurusan.index')); ?>" class="relative w-72">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
<i class="w-4 h-4" data-lucide="search"></i>
</span>
<input name="search" value="<?php echo e(request('search')); ?>" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari kode atau nama jurusan..." type="text"/>
</form>

</div>
<!-- The Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse whitespace-nowrap" id="jurusan-table">
<thead class="bg-slate-50/50">
<tr>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode Jurusan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Jurusan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kepala Jurusan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterangan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
<?php if(auth()->user()->role === 'Superadmin'): ?>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
<?php endif; ?>
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
<?php $__empty_1 = true; $__currentLoopData = $jurusans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr class="<?php echo e($loop->even ? 'bg-slate-50/30' : ''); ?> table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600"><?php echo e($loop->iteration + $jurusans->firstItem() - 1); ?></td>
<td class="px-6 py-4 text-sm font-medium text-slate-900"><?php echo e($jurusan->kode_jurusan ?? '-'); ?></td>
<td class="px-6 py-4 text-sm font-medium text-slate-900"><?php echo e($jurusan->name); ?></td>
<td class="px-6 py-4 text-sm text-slate-600"><?php echo e($jurusan->kepala_jurusan ?? '-'); ?></td>
<td class="px-6 py-4 text-sm text-slate-600"><?php echo e($jurusan->description ?? '-'); ?></td>
<td class="px-6 py-4 text-center">
<?php if($jurusan->is_active): ?>
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span> Aktif
    </span>
<?php else: ?>
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-200">
        <span class="w-2 h-2 bg-slate-400 rounded-full mr-1.5"></span> Nonaktif
    </span>
<?php endif; ?>
</td>
<?php if(auth()->user()->role === 'Superadmin'): ?>
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<button onclick="openEditJurusanModal(<?php echo e($jurusan->id); ?>, '<?php echo e(addslashes($jurusan->kode_jurusan ?? '')); ?>', '<?php echo e(addslashes($jurusan->name)); ?>', '<?php echo e(addslashes($jurusan->kepala_jurusan ?? '')); ?>', '<?php echo e(addslashes($jurusan->description ?? '')); ?>', <?php echo e($jurusan->is_active ? '1' : '0'); ?>)" class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<form action="<?php echo e(route('jurusan.destroy', $jurusan->id)); ?>" method="POST" data-confirm="Yakin ingin menghapus jurusan ini?" data-ajax-delete="true" class="inline">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button type="submit" class="text-red-400 hover:text-red-600 border-none bg-transparent cursor-pointer"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</form>
</div>
</td>
<?php endif; ?>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="<?php echo e(auth()->user()->role === 'Superadmin' ? 7 : 6); ?>" class="px-6 py-10 text-center text-slate-500">
        <div class="flex flex-col items-center justify-center">
            <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mb-3"></i>
            <p class="text-sm font-medium">Belum ada data jurusan</p>
            <p class="text-xs mt-1 text-slate-400">Data jurusan yang ditambahkan akan muncul di sini</p>
        </div>
    </td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
<!-- Table Pagination -->
<div class="px-6 py-4 border-t border-slate-100" data-purpose="table-pagination">
    <?php echo e($jurusans->links()); ?>

</div>
</section>
<!-- END: Table Container Card -->


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function openEditJurusanModal(id, kode, name, kepala, description, isActive) {
        document.getElementById('editJurusanForm').action = `/data-jurusan/${id}`;
        document.getElementById('edit_jurusan_kode').value = kode;
        document.getElementById('edit_jurusan_name').value = name;
        document.getElementById('edit_jurusan_kepala').value = kepala;
        document.getElementById('edit_jurusan_description').value = description;
        document.getElementById('edit_jurusan_is_active').value = isActive;
        document.getElementById('editJurusanModal').classList.remove('hidden');
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('page-modals'); ?>
<!-- Modal Tambah Jurusan -->
<div id="addJurusanModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addJurusanModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Tambah Jurusan</h2>
            <button type="button" onclick="document.getElementById('addJurusanModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="<?php echo e(route('jurusan.store')); ?>" method="POST" class="flex flex-col flex-1 overflow-hidden">
            <?php echo csrf_field(); ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Kode Jurusan</label>
                    <input type="text" name="kode_jurusan" required placeholder="Contoh: X-TKJ-5" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Jurusan</label>
                    <input type="text" name="name" required placeholder="Contoh: X TKJ 5" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Kepala Jurusan</label>
                    <input type="text" name="kepala_jurusan" placeholder="Nama Kepala Jurusan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea name="description" rows="3" placeholder="Penjelasan atau detail jurusan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Status</label>
                    <select name="is_active" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addJurusanModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Jurusan -->
<div id="editJurusanModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editJurusanModal').classList.add('hidden')"></div>
    
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Edit Jurusan</h2>
            <button type="button" onclick="document.getElementById('editJurusanModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editJurusanForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Kode Jurusan</label>
                    <input type="text" id="edit_jurusan_kode" name="kode_jurusan" required placeholder="Contoh: X-TKJ-5" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Jurusan</label>
                    <input type="text" id="edit_jurusan_name" name="name" required placeholder="Contoh: X TKJ 5" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Kepala Jurusan</label>
                    <input type="text" id="edit_jurusan_kepala" name="kepala_jurusan" placeholder="Nama Kepala Jurusan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Keterangan</label>
                    <textarea id="edit_jurusan_description" name="description" rows="3" placeholder="Penjelasan atau detail jurusan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Status</label>
                    <select id="edit_jurusan_is_active" name="is_active" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editJurusanModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\ERP NOC - SMKN 4 Malang\resources\views/data-master/dataJurusan.blade.php ENDPATH**/ ?>