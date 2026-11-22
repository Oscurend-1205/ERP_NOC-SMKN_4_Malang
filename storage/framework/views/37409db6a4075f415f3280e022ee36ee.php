<?php $__env->startSection('title', 'Data User'); ?>

<?php $__env->startSection('content'); ?>
<!-- BEGIN: Page Title & Action -->
<div class="flex justify-between items-start mb-6" data-purpose="page-title-section">
<div>
<h1 class="text-3xl font-bold text-slate-900">Data User</h1>
<p class="text-sm text-slate-500 mt-1">Kelola data user. <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 ml-1"><i data-lucide="users" class="w-3 h-3 mr-1"></i>Total: <?php echo e(number_format($totalUsers)); ?> user</span></p>
</div>
<?php if(auth()->user()->role === 'Superadmin'): ?>
    <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah User Baru
    </button>
<?php endif; ?>
</div>
<!-- END: Page Title & Action -->
<!-- BEGIN: Data Table Section -->
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" data-purpose="table-container">
<!-- Table Toolbar -->
<div class="p-4 border-b border-slate-100 flex items-center justify-between" data-purpose="table-toolbar">
<form action="<?php echo e(route('users.index')); ?>" method="GET" class="relative w-72">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
<i class="w-4 h-4" data-lucide="search"></i>
</span>
<input name="search" value="<?php echo e(request('search')); ?>" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari username atau nama..." type="text"/>
</form>

</div>
<!-- The Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse whitespace-nowrap" id="user-table">
<thead class="bg-slate-50/50">
<tr>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">NISN/NUPTK</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jabatan</th>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
<?php if(auth()->user()->role === 'Superadmin'): ?>
<th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
<?php endif; ?>
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
<?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr class="<?php echo e($loop->iteration % 2 === 0 ? 'bg-slate-50/30' : ''); ?> table-row-hover transition-colors">
<td class="px-6 py-4 text-sm text-slate-600"><?php echo e($users->firstItem() + $index); ?></td>
<td class="px-6 py-4 text-sm font-medium text-slate-900">
<div class="flex items-center gap-3">
<?php
    $initials = collect(explode(' ', $user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
    $colors = ['bg-blue-600', 'bg-slate-400', 'bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500'];
    $color = $colors[strlen($user->name) % count($colors)];
?>
<div class="w-9 h-9 rounded-full <?php echo e($color); ?> text-white flex items-center justify-center font-bold text-xs uppercase"><?php echo e($initials); ?></div>
<span class="font-bold text-gray-900"><?php echo e($user->name); ?></span>
<?php if(auth()->id() === $user->id): ?>
<span class="bg-gray-100 text-gray-500 text-[9px] px-1.5 py-0.5 rounded ml-2 border border-gray-200">Anda</span>
<?php endif; ?>
</div>
</td>
<td class="px-6 py-4 text-sm text-slate-600"><?php echo e($user->username ?? '-'); ?></td>
<td class="px-6 py-4 text-sm text-slate-600"><?php echo e($user->email ?? '-'); ?></td>
<td class="px-6 py-4 text-sm font-medium text-blue-700">
    <?php echo e($user->role); ?>

    <?php if($user->jurusan): ?>
        <span class="block text-[11px] text-gray-500 font-normal">(<?php echo e($user->jurusan->kode_jurusan); ?>)</span>
    <?php endif; ?>
</td>
<td class="px-6 py-4">
<?php if($user->is_active): ?>
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
<span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
  Aktif
</span>
<?php else: ?>
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
<span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
  Non-Aktif
</span>
<?php endif; ?>
</td>
<?php if(auth()->user()->role === 'Superadmin'): ?>
<td class="px-6 py-4 text-center">
<div class="flex justify-center space-x-3">
<?php if(!in_array($user->username, ['superadmin', 'admin'])): ?>
<button onclick="openEditUserModal(<?php echo e($user->id); ?>, '<?php echo e(addslashes($user->name)); ?>', '<?php echo e(addslashes($user->username ?? '')); ?>', '<?php echo e(addslashes($user->email ?? '')); ?>', '<?php echo e($user->role); ?>', <?php echo e($user->is_active ? 1 : 0); ?>, '<?php echo e($user->jurusan_id ?? ''); ?>')" class="text-slate-500 hover:text-slate-700"><i class="w-4 h-4" data-lucide="pencil"></i></button>
<form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" data-confirm="Yakin ingin menghapus user ini?" data-ajax-delete="true" class="inline">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button type="submit" class="text-red-400 hover:text-red-600 border-none bg-transparent cursor-pointer"><i class="w-4 h-4" data-lucide="trash-2"></i></button>
</form>
<?php else: ?>
<span class="text-xs text-slate-400 italic px-2 py-1 bg-slate-100 rounded-md" title="Akun paten tidak dapat diubah">Paten</span>
<?php endif; ?>
</div>
</td>
<?php endif; ?>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
<td colspan="7" class="px-6 py-8 text-center text-slate-500">Belum ada data user.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
<!-- Table Pagination -->
<div class="px-6 py-4 border-t border-slate-100" data-purpose="table-pagination">
    <?php echo e($users->links()); ?>

</div>
</section>
<!-- END: Data Table Section -->


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function openEditUserModal(id, name, username, email, role, isActive, jurusanId) {
        document.getElementById('editUserForm').action = `/data-pengguna/${id}`;
        document.getElementById('edit_user_name').value = name;
        document.getElementById('edit_user_username').value = username;
        document.getElementById('edit_user_email').value = email;
        document.getElementById('edit_user_role').value = role;
        const jurusanSelect = document.getElementById('edit_user_jurusan_id');
        if (jurusanSelect) jurusanSelect.value = jurusanId || '';
        document.getElementById('edit_user_is_active').checked = (isActive == 1);
        document.getElementById('editUserModal').classList.remove('hidden');
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('page-modals'); ?>
<!-- Modal Tambah User -->
<div id="addUserModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <!-- Backdrop Blur -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addUserModal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Tambah User Baru</h2>
            <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Form Body -->
        <form action="<?php echo e(route('users.store')); ?>" method="POST" class="flex flex-col flex-1 overflow-hidden">
            <?php echo csrf_field(); ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                
                <!-- Nama Lengkap -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Masukkan nama lengkap" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- NISN/NUPTK -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">NISN/NUPTK/Username</label>
                    <input type="text" name="username" required placeholder="Masukkan NISN, NUPTK, atau username" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Email (Opsional)</label>
                    <input type="email" name="email" placeholder="user@smkn4malang.sch.id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- Jurusan -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Jurusan (Jika Role Jurusan)</label>
                    <select name="jurusan_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white">
                        <option value="">Pilih Jurusan</option>
                        <?php $__currentLoopData = $jurusans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($jurusan->id); ?>"><?php echo e($jurusan->kode_jurusan); ?> - <?php echo e($jurusan->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Jabatan -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Jabatan / Role</label>
                    <select name="role" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white">
                        <option value="" disabled selected hidden>Pilih Jabatan</option>
                        <option value="Superadmin">Superadmin</option>
                        <option value="Admin">Admin</option>
                        <option value="Jurusan">Jurusan</option>
                        <option value="Siswa">Siswa</option>
                        <option value="Guru">Guru</option>
                    </select>
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center justify-between pt-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Status Aktif</label>
                        <span class="text-xs text-slate-500">Tentukan apakah user ini aktif dan dapat login.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" class="sr-only peer" checked value="1">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editUserModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <!-- Backdrop Blur -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editUserModal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
            <h2 class="text-lg font-bold text-slate-900">Edit User</h2>
            <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Form Body -->
        <form id="editUserForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                
                <!-- Nama Lengkap -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" id="edit_user_name" name="name" required placeholder="Masukkan nama lengkap" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- NISN/NUPTK -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">NISN/NUPTK/Username</label>
                    <input type="text" id="edit_user_username" name="username" required placeholder="Masukkan NISN, NUPTK, atau username" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Email (Opsional)</label>
                    <input type="email" id="edit_user_email" name="email" placeholder="user@smkn4malang.sch.id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                </div>

                <!-- Jurusan -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Jurusan (Jika Role Jurusan)</label>
                    <select id="edit_user_jurusan_id" name="jurusan_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white">
                        <option value="">Pilih Jurusan</option>
                        <?php $__currentLoopData = $jurusans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($jurusan->id); ?>"><?php echo e($jurusan->kode_jurusan); ?> - <?php echo e($jurusan->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Jabatan -->
                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-slate-700">Jabatan / Role</label>
                    <select id="edit_user_role" name="role" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 bg-white">
                        <option value="" disabled selected hidden>Pilih Jabatan</option>
                        <option value="Superadmin">Superadmin</option>
                        <option value="Admin">Admin</option>
                        <option value="Jurusan">Jurusan</option>
                        <option value="Siswa">Siswa</option>
                        <option value="Guru">Guru</option>
                    </select>
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center justify-between pt-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Status Aktif</label>
                        <span class="text-xs text-slate-500">Tentukan apakah user ini aktif dan dapat login.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="edit_user_is_active" name="is_active" class="sr-only peer" value="1">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project Web Porto\ERP NOC - SMKN 4 Malang\resources\views/data-master/dataUser.blade.php ENDPATH**/ ?>