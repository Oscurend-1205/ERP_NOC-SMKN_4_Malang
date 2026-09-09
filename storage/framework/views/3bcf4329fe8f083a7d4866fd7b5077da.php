<!-- BEGIN: Sidebar Backdrop (Mobile Only) -->
<!-- NOTE: backdrop-blur dihapus untuk menghilangkan rendering artifact sisa overlay -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/50 z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

<!-- BEGIN: Sidebar -->
<aside id="mainSidebar" class="fixed inset-y-0 left-0 h-screen transform -translate-x-full md:translate-x-0 transition-transform duration-300 w-64 bg-[#1A1E35] text-white flex flex-col flex-shrink-0 z-50" data-purpose="sidebar">
<style>
    /* Custom Scrollbar for Sidebar */
    #sidebar-nav-container {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        scrollbar-gutter: stable;
    }
    #sidebar-nav-container::-webkit-scrollbar {
        width: 4px;
    }
    #sidebar-nav-container::-webkit-scrollbar-track {
        background: transparent;
    }
    #sidebar-nav-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
    }
    #sidebar-nav-container::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Mobile logic: when sidebar is active/open */
    #mainSidebar.sidebar-mobile-open {
        transform: translateX(0);
    }

    /* Sidebar menu item base */
    .sidebar-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none !important;
        font-size: 14px;
        font-weight: 500;
        color: #8E93A8;
        margin-bottom: 2px;
    }
    .sidebar-menu-item:hover {
        background: rgba(255,255,255,0.06);
        color: #C8CCDB;
    }
    .sidebar-menu-item.active {
        background: #2C3152;
        color: #FFFFFF;
    }
    .sidebar-menu-item .menu-icon {
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .sidebar-menu-item .menu-icon svg,
    .sidebar-menu-item .menu-icon .material-symbols-outlined {
        color: #8E93A8;
        font-size: 22px;
    }
    .sidebar-menu-item.active .menu-icon svg,
    .sidebar-menu-item.active .menu-icon .material-symbols-outlined {
        color: #FFFFFF;
    }
    .sidebar-menu-item:hover .menu-icon svg,
    .sidebar-menu-item:hover .menu-icon .material-symbols-outlined {
        color: #C8CCDB;
    }

    /* Submenu items */
    .sidebar-sub-item {
        display: block;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 500;
        color: #8E93A8;
        text-decoration: none !important;
        transition: color 0.2s ease;
        border-radius: 6px;
    }
    .sidebar-sub-item:hover {
        color: #FFFFFF;
    }
    .sidebar-sub-item.active {
        color: #FFFFFF;
        font-weight: 600;
    }
</style>

<!-- Sidebar Logo -->
<div class="px-3 pt-3 pb-0 flex justify-center flex-shrink-0">
<img alt="SMKN 4 MALANG NOC Logo" class="w-[210px] h-auto object-contain" src="<?php echo e(asset('asset/noc-smkn4.svg')); ?>"/>
</div>

<!-- Navigation Menu -->
<nav id="sidebar-nav-container" class="mt-3 flex-grow px-3 space-y-0.5 overflow-y-auto overflow-x-hidden">

    <!-- 1. Beranda -->
    <a data-no-pjax="true" class="sidebar-menu-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">home</span>
        </div>
        <span>Beranda</span>
    </a>

    <!-- 2. Data Master (Superadmin Only) -->
    <?php if(Auth::user()->role === 'Superadmin'): ?>
    <div class="flex flex-col">
        <button type="button" id="btn-data-master" class="sidebar-menu-item w-full justify-between border-none cursor-pointer bg-transparent text-left relative z-10 <?php echo e(request()->routeIs('locations.*') || request()->routeIs('categories.*') || request()->routeIs('users.*') || request()->routeIs('jurusan.*') || request()->routeIs('supplier.*') || request()->routeIs('kondisi.*') || request()->routeIs('asal.*') ? 'active' : ''); ?>">
            <div class="flex items-center gap-3 pointer-events-none">
                <div class="menu-icon">
                    <span class="material-symbols-outlined">database</span>
                </div>
                <span>Data Master</span>
            </div>
            <span id="icon-data-master" class="material-symbols-outlined text-[18px] transition-transform duration-300 pointer-events-none" style="color:#8E93A8">chevron_right</span>
        </button>
        <div id="sub-data-master" class="hidden flex-col gap-0.5 pl-[46px] pr-2 mt-1 overflow-hidden transition-all duration-300">
            <a href="<?php echo e(route('users.index')); ?>" class="sidebar-sub-item <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">Data User</a>
            <a href="<?php echo e(route('locations.index')); ?>" class="sidebar-sub-item <?php echo e(request()->routeIs('locations.*') ? 'active' : ''); ?>">Data Ruangan</a>
            <a href="<?php echo e(route('jurusan.index')); ?>" class="sidebar-sub-item <?php echo e(request()->routeIs('jurusan.*') ? 'active' : ''); ?>">Data Jurusan</a>
            <a href="<?php echo e(route('supplier.index')); ?>" class="sidebar-sub-item <?php echo e(request()->routeIs('supplier.*') ? 'active' : ''); ?>">Data Supplier</a>
            <a href="<?php echo e(route('categories.index')); ?>" class="sidebar-sub-item <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">Kategori Barang</a>
            <a href="<?php echo e(route('kondisi.index')); ?>" class="sidebar-sub-item <?php echo e(request()->routeIs('kondisi.*') ? 'active' : ''); ?>">Kondisi Barang</a>
            <a href="<?php echo e(route('asal.index')); ?>" class="sidebar-sub-item <?php echo e(request()->routeIs('asal.*') ? 'active' : ''); ?>">Asal Barang</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- 3. Input Pinjaman -->
    <?php if(in_array(Auth::user()->role, ['Superadmin', 'Admin'])): ?>
    <a data-no-pjax="true" class="sidebar-menu-item <?php echo e(request()->routeIs('qr.*') ? 'active' : ''); ?>" href="<?php echo e(route('qr.admin')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">edit</span>
        </div>
        <span>Input Pinjaman</span>
    </a>
    <?php endif; ?>

    <!-- 4. Data Barang -->
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('items.*') ? 'active' : ''); ?>" href="<?php echo e(route('items.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">inventory_2</span>
        </div>
        <span>Data Barang</span>
    </a>

    <!-- 5. Data Peminjaman -->
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('peminjaman.*') ? 'active' : ''); ?>" href="<?php echo e(route('peminjaman.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">assignment</span>
        </div>
        <span>Data Peminjaman</span>
    </a>

    <!-- 6. Pengadaan Alat -->
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('procurements.*') ? 'active' : ''); ?>" href="<?php echo e(route('procurements.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">shopping_cart</span>
        </div>
        <span>Pengadaan Alat</span>
    </a>

    <!-- 7. Data Perawatan -->
    <?php if(in_array(Auth::user()->role, ['Superadmin', 'Admin'])): ?>
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('perawatan.*') ? 'active' : ''); ?>" href="<?php echo e(route('perawatan.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">build</span>
        </div>
        <span>Data Perawatan</span>
    </a>

    <!-- 8. Stok Opname -->
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('stock-take.*') ? 'active' : ''); ?>" href="<?php echo e(route('stock-take.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">fact_check</span>
        </div>
        <span>Stok Opname</span>
    </a>

    <!-- 9. Laporan -->
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('laporan.*') ? 'active' : ''); ?>" href="<?php echo e(route('laporan.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">description</span>
        </div>
        <span>Laporan</span>
    </a>
    <?php endif; ?>

    <!-- 10. Panduan Sistem (Semua Role) -->
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('guide.*') ? 'active' : ''); ?>" href="<?php echo e(route('guide.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">menu_book</span>
        </div>
        <span>Panduan Sistem</span>
    </a>

</nav>

<!-- Footer: Setting + Keluar -->
<div class="mt-auto px-3 pb-3 pt-2 border-t border-white/10 flex-shrink-0 space-y-0.5">
    <!-- Audit Trail (Superadmin Only) -->
    <?php if(Auth::user()->role === 'Superadmin'): ?>
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('activity-log.*') ? 'active' : ''); ?>" href="<?php echo e(route('activity-log.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">history</span>
        </div>
        <span>Audit Trail</span>
    </a>
    <?php endif; ?>

    <!-- Setting (Superadmin Only) -->
    <?php if(Auth::user()->role === 'Superadmin'): ?>
    <a class="sidebar-menu-item <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>" href="<?php echo e(route('settings.index')); ?>">
        <div class="menu-icon">
            <span class="material-symbols-outlined">settings</span>
        </div>
        <span>Setting</span>
    </a>
    <?php endif; ?>

    <!-- Keluar -->
    <form action="<?php echo e(route('logout')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="sidebar-menu-item w-full border-none cursor-pointer bg-transparent text-left">
            <div class="menu-icon">
                <span class="material-symbols-outlined">logout</span>
            </div>
            <span>Keluar</span>
        </button>
    </form>
</div>
</aside>
<!-- END: Sidebar -->

<script>
    function initSidebar() {
        const sidebar = document.getElementById('mainSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const btnDataMaster = document.getElementById('btn-data-master');
        const subDataMaster = document.getElementById('sub-data-master');
        const iconDataMaster = document.getElementById('icon-data-master');

        // Reset states for mobile on load
        if (window.innerWidth < 768) {
            if (sidebar) sidebar.classList.remove('sidebar-mobile-open');
            if (backdrop) backdrop.classList.add('hidden');
        }

        if (btnDataMaster && subDataMaster && iconDataMaster) {
            if (btnDataMaster.dataset.initialized) return;
            btnDataMaster.dataset.initialized = 'true';

            // Check if we are on a Data Master page
            const isDataMasterActive = <?php echo e(request()->routeIs('locations.*') || request()->routeIs('categories.*') || request()->routeIs('users.*') || request()->routeIs('jurusan.*') || request()->routeIs('supplier.*') || request()->routeIs('kondisi.*') || request()->routeIs('asal.*') ? 'true' : 'false'); ?>;
            
            if (isDataMasterActive) {
                subDataMaster.classList.remove('hidden');
                subDataMaster.classList.add('flex');
                iconDataMaster.style.transform = 'rotate(90deg)';
            }

            btnDataMaster.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isHidden = subDataMaster.classList.contains('hidden');
                
                if (isHidden) {
                    subDataMaster.classList.remove('hidden');
                    subDataMaster.classList.add('flex');
                    iconDataMaster.style.transform = 'rotate(90deg)';
                } else {
                    subDataMaster.classList.add('hidden');
                    subDataMaster.classList.remove('flex');
                    iconDataMaster.style.transform = 'rotate(0deg)';
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initSidebar);
    document.addEventListener('turbo:load', initSidebar);
    document.addEventListener('pjax:complete', initSidebar);
</script>
<?php /**PATH C:\Project Web Porto\ERP NOC - SMKN 4 Malang\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>