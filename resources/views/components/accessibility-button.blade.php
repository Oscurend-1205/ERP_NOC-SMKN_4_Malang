@auth
<!-- BEGIN: Floating Draggable Pintasan Cepat ERP Widget -->
<div id="erpQuickWidgetContainer" 
     class="fixed z-[9999] w-12 h-12 select-none touch-none" 
     style="bottom: 24px; right: 24px;">
    
    <!-- Floating Draggable Trigger Button (Exact 48x48px circle) -->
    <div class="relative w-12 h-12">
        <button id="erpQuickTriggerBtn" 
                type="button"
                class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-600 text-white shadow-xl hover:shadow-2xl shadow-blue-600/35 flex items-center justify-center cursor-grab active:cursor-grabbing focus:outline-none ring-4 ring-white/90 group"
                aria-label="Pintasan Cepat ERP"
                title="Pintasan Cepat ERP (Klik untuk buka menu, Tahan & Geser untuk memindahkan)">
            
            <!-- Pulse ring effect (only when menu is closed) -->
            <span id="erpQuickPulseRing" class="absolute inset-0 rounded-full bg-blue-400 opacity-30 animate-ping pointer-events-none"></span>
            
            <span id="erpQuickBtnIcon" class="material-symbols-outlined text-[24px] pointer-events-none transition-transform duration-200">touch_app</span>
            
            <!-- Tiny Drag Grip Indicator -->
            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-indigo-950 border-2 border-white text-[9px] text-indigo-100 shadow-sm pointer-events-none">
                <span class="material-symbols-outlined text-[10px]">drag_indicator</span>
            </span>
        </button>

        <!-- Hover Tooltip (hanya muncul jika tidak sedang drag dan menu tertutup) -->
        <div id="erpQuickTooltip" class="absolute bottom-full right-0 mb-2 hidden sm:group-hover:flex flex-col items-end pointer-events-none transition-all duration-150">
            <div class="bg-gray-900/90 backdrop-blur-sm text-white text-[11px] font-semibold py-1 px-2.5 rounded-lg whitespace-nowrap shadow-lg flex items-center gap-1.5 border border-white/10">
                <span class="material-symbols-outlined text-[13px] text-amber-300">bolt</span>
                <span>Pintasan Cepat ERP</span>
                <span class="text-[9px] text-gray-400 border-l border-gray-700 pl-1.5">Geser untuk pindah</span>
            </div>
        </div>
    </div>

    <!-- Popup Menu Modal (Speed Dial Card) -->
    <div id="erpQuickMenuModal" 
         class="hidden absolute z-[10000] w-[310px] sm:w-[330px] bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 transition-all duration-200 transform origin-bottom-right scale-95 opacity-0"
         style="bottom: 56px; right: 0;">
        
        <!-- Header Popup -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[18px]">touch_app</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900">Pintasan Cepat ERP</h4>
                    <p class="text-[10px] text-gray-400">Akses kilat fitur laboratorium</p>
                </div>
            </div>
            <button type="button" id="erpQuickCloseBtn" class="w-7 h-7 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>

        <!-- Shortcuts Grid (2 Columns) -->
        <div class="grid grid-cols-2 gap-2">
            <!-- 1. Tambah / Data Barang -->
            <a href="{{ route('items.index') }}" class="group p-2.5 rounded-xl bg-gray-50/80 hover:bg-blue-50 hover:text-blue-700 border border-transparent hover:border-blue-200 transition-all flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">add_box</span>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-gray-800 group-hover:text-blue-700 truncate">Barang</div>
                    <div class="text-[10px] text-gray-400 truncate">Input & Katalog</div>
                </div>
            </a>

            <!-- 2. Pengadaan Alat -->
            <a href="{{ route('procurements.index') }}" class="group p-2.5 rounded-xl bg-gray-50/80 hover:bg-amber-50 hover:text-amber-700 border border-transparent hover:border-amber-200 transition-all flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-gray-800 group-hover:text-amber-700 truncate">Pengadaan</div>
                    <div class="text-[10px] text-gray-400 truncate">Usulan & Status</div>
                </div>
            </a>

            <!-- 3. Panel QR Pinjam -->
            @if(in_array(Auth::user()->role, ['Superadmin', 'Admin']))
            <a href="{{ route('qr.admin') }}" class="group p-2.5 rounded-xl bg-gray-50/80 hover:bg-indigo-50 hover:text-indigo-700 border border-transparent hover:border-indigo-200 transition-all flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-gray-800 group-hover:text-indigo-700 truncate">QR Pinjam</div>
                    <div class="text-[10px] text-gray-400 truncate">Scanner & Kode</div>
                </div>
            </a>
            @endif

            <!-- 4. Peminjaman -->
            <a href="{{ route('peminjaman.index') }}" class="group p-2.5 rounded-xl bg-gray-50/80 hover:bg-emerald-50 hover:text-emerald-700 border border-transparent hover:border-emerald-200 transition-all flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-gray-800 group-hover:text-emerald-700 truncate">Peminjaman</div>
                    <div class="text-[10px] text-gray-400 truncate">Sirkulasi Alat</div>
                </div>
            </a>

            <!-- 5. Stok Opname -->
            <a href="{{ route('stock-take.index') }}" class="group p-2.5 rounded-xl bg-gray-50/80 hover:bg-teal-50 hover:text-teal-700 border border-transparent hover:border-teal-200 transition-all flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">fact_check</span>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-gray-800 group-hover:text-teal-700 truncate">Stok Opname</div>
                    <div class="text-[10px] text-gray-400 truncate">Audit Fisik</div>
                </div>
            </a>

            <!-- 6. Audit Trail (Superadmin) / Laporan -->
            @if(Auth::user()->role === 'Superadmin')
            <a href="{{ route('activity-log.index') }}" class="group p-2.5 rounded-xl bg-gray-50/80 hover:bg-purple-50 hover:text-purple-700 border border-transparent hover:border-purple-200 transition-all flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">history</span>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-gray-800 group-hover:text-purple-700 truncate">Audit Trail</div>
                    <div class="text-[10px] text-gray-400 truncate">Log Aktivitas</div>
                </div>
            </a>
            @else
            <a href="{{ route('laporan.index') }}" class="group p-2.5 rounded-xl bg-gray-50/80 hover:bg-purple-50 hover:text-purple-700 border border-transparent hover:border-purple-200 transition-all flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">description</span>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-gray-800 group-hover:text-purple-700 truncate">Laporan</div>
                    <div class="text-[10px] text-gray-400 truncate">Rekap Data</div>
                </div>
            </a>
            @endif
        </div>

        <!-- Footer Hint Drag & Reset Posisi -->
        <div class="mt-3 pt-2.5 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400">
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[12px] text-indigo-500">drag_indicator</span>
                Tahan & geser untuk memindahkan
            </span>
            <button type="button" id="erpQuickResetPosBtn" class="text-gray-400 hover:text-blue-600 underline text-[9px] cursor-pointer" title="Kembalikan tombol ke pojok kanan bawah">
                Reset Posisi
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    function initDraggableQuickMenu() {
        const container = document.getElementById('erpQuickWidgetContainer');
        const triggerBtn = document.getElementById('erpQuickTriggerBtn');
        const menuModal = document.getElementById('erpQuickMenuModal');
        const closeBtn = document.getElementById('erpQuickCloseBtn');
        const resetBtn = document.getElementById('erpQuickResetPosBtn');
        const icon = document.getElementById('erpQuickBtnIcon');
        const pulseRing = document.getElementById('erpQuickPulseRing');
        const tooltip = document.getElementById('erpQuickTooltip');

        if (!container || !triggerBtn || !menuModal) return;
        if (triggerBtn.dataset.dragInitialized) return;
        triggerBtn.dataset.dragInitialized = 'true';

        const BTN_SIZE = 48;
        const MARGIN = 12;

        let isDragging = false;
        let hasMoved = false;
        let grabOffsetX = 0;
        let grabOffsetY = 0;
        let startPointerX = 0;
        let startPointerY = 0;
        let isMenuOpen = false;

        // 1. Muat posisi tersimpan jika valid
        const savedLeft = localStorage.getItem('erp_quick_pos_left');
        const savedTop = localStorage.getItem('erp_quick_pos_top');

        if (savedLeft !== null && savedTop !== null) {
            const leftNum = parseInt(savedLeft, 10);
            const topNum = parseInt(savedTop, 10);
            const maxLeft = window.innerWidth - BTN_SIZE - MARGIN;
            const maxTop = window.innerHeight - BTN_SIZE - MARGIN;

            if (!isNaN(leftNum) && !isNaN(topNum) && leftNum >= MARGIN && leftNum <= maxLeft && topNum >= MARGIN && topNum <= maxTop) {
                container.style.left = leftNum + 'px';
                container.style.top = topNum + 'px';
                container.style.right = 'auto';
                container.style.bottom = 'auto';
                repositionMenu(leftNum, topNum);
            } else {
                // Posisi tersimpan di luar batas layar -> reset
                localStorage.removeItem('erp_quick_pos_left');
                localStorage.removeItem('erp_quick_pos_top');
            }
        }

        // 2. Reposisi arah popup menu secara adaptif terhadap posisi tombol di layar
        function repositionMenu(left, top) {
            const menuWidth = 330;
            const menuHeight = 290;
            const winWidth = window.innerWidth;

            // Jika tombol berada di sisi kanan layar, buka menu ke arah kiri tombol
            if (left + menuWidth > winWidth - 10) {
                menuModal.style.left = 'auto';
                menuModal.style.right = '0px';
                menuModal.classList.remove('origin-bottom-left', 'origin-top-left');
                menuModal.classList.add('origin-bottom-right');
            } else {
                // Jika tombol di sisi kiri layar, buka menu ke arah kanan tombol
                menuModal.style.left = '0px';
                menuModal.style.right = 'auto';
                menuModal.classList.remove('origin-bottom-right', 'origin-top-right');
                menuModal.classList.add('origin-bottom-left');
            }

            // Jika tombol berada di bagian atas layar, popup membuka ke bawah
            if (top < menuHeight + 20) {
                menuModal.style.top = (BTN_SIZE + 8) + 'px';
                menuModal.style.bottom = 'auto';
            } else {
                // Jika di bawah, popup membuka ke atas
                menuModal.style.top = 'auto';
                menuModal.style.bottom = (BTN_SIZE + 8) + 'px';
            }
        }

        // 3. Toggle Menu
        function toggleMenu(open) {
            isMenuOpen = (typeof open === 'boolean') ? open : !isMenuOpen;
            if (isMenuOpen) {
                const rect = container.getBoundingClientRect();
                repositionMenu(rect.left, rect.top);

                menuModal.classList.remove('hidden');
                requestAnimationFrame(() => {
                    menuModal.classList.remove('scale-95', 'opacity-0');
                    menuModal.classList.add('scale-100', 'opacity-100');
                });
                if (icon) icon.textContent = 'close';
                if (pulseRing) pulseRing.classList.add('hidden');
                if (tooltip) tooltip.classList.add('!hidden');
            } else {
                menuModal.classList.remove('scale-100', 'opacity-100');
                menuModal.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    if (!isMenuOpen) menuModal.classList.add('hidden');
                }, 150);
                if (icon) icon.textContent = 'touch_app';
                if (pulseRing) pulseRing.classList.remove('hidden');
                if (tooltip) tooltip.classList.remove('!hidden');
            }
        }

        // 4. Pointer Drag Events (Unifikasi Mouse & Touchscreen yang presisi tanpa loncat)
        function onPointerDown(e) {
            if (e.button !== undefined && e.button !== 0) return; // Hanya klik kiri

            const rect = container.getBoundingClientRect();
            
            // Simpan offset kursor relatif terhadap sudut tombol saat pertama kali ditekan
            grabOffsetX = e.clientX - rect.left;
            grabOffsetY = e.clientY - rect.top;

            startPointerX = e.clientX;
            startPointerY = e.clientY;
            hasMoved = false;
            isDragging = true;

            // Tangkap pointer agar tracking tetap mulus walau kursor bergerak cepat
            try {
                triggerBtn.setPointerCapture(e.pointerId);
            } catch (err) {}

            triggerBtn.classList.remove('cursor-grab');
            triggerBtn.classList.add('cursor-grabbing');

            // Nonaktifkan transisi CSS selama drag agar tidak ada jeda/lag di belakang kursor
            container.style.transition = 'none';

            window.addEventListener('pointermove', onPointerMove, { passive: false });
            window.addEventListener('pointerup', onPointerUp);
            window.addEventListener('pointercancel', onPointerUp);
        }

        function onPointerMove(e) {
            if (!isDragging) return;

            const dist = Math.hypot(e.clientX - startPointerX, e.clientY - startPointerY);
            if (dist > 5) {
                hasMoved = true;
                if (isMenuOpen) toggleMenu(false);
                if (e.cancelable) e.preventDefault();
            }

            if (hasMoved) {
                // Hitung posisi baru berdasar posisi kursor dikurangi grab offset (TIDAK AKAN LONCAT)
                let newLeft = e.clientX - grabOffsetX;
                let newTop = e.clientY - grabOffsetY;

                // Batasan layar (viewport clamp)
                const minX = MARGIN;
                const maxX = window.innerWidth - BTN_SIZE - MARGIN;
                const minY = MARGIN;
                const maxY = window.innerHeight - BTN_SIZE - MARGIN;

                newLeft = Math.max(minX, Math.min(maxX, newLeft));
                newTop = Math.max(minY, Math.min(maxY, newTop));

                container.style.left = newLeft + 'px';
                container.style.top = newTop + 'px';
                container.style.right = 'auto';
                container.style.bottom = 'auto';
            }
        }

        function onPointerUp(e) {
            if (!isDragging) return;
            isDragging = false;

            try {
                triggerBtn.releasePointerCapture(e.pointerId);
            } catch (err) {}

            triggerBtn.classList.remove('cursor-grabbing');
            triggerBtn.classList.add('cursor-grab');

            window.removeEventListener('pointermove', onPointerMove);
            window.removeEventListener('pointerup', onPointerUp);
            window.removeEventListener('pointercancel', onPointerUp);

            if (hasMoved) {
                // Simpan posisi baru ke localStorage
                const rect = container.getBoundingClientRect();
                localStorage.setItem('erp_quick_pos_left', Math.round(rect.left));
                localStorage.setItem('erp_quick_pos_top', Math.round(rect.top));
                repositionMenu(rect.left, rect.top);
            } else {
                // Klik tanpa drag -> buka / tutup menu
                toggleMenu();
            }
        }

        triggerBtn.addEventListener('pointerdown', onPointerDown);

        // Tombol Close di modal
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMenu(false);
            });
        }

        // Tombol Reset Posisi
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                localStorage.removeItem('erp_quick_pos_left');
                localStorage.removeItem('erp_quick_pos_top');
                container.style.left = 'auto';
                container.style.top = 'auto';
                container.style.right = '24px';
                container.style.bottom = '24px';
                repositionMenu(window.innerWidth - 80, window.innerHeight - 80);
                toggleMenu(false);
            });
        }

        // Tutup jika klik di luar
        document.addEventListener('click', function(e) {
            if (isMenuOpen && !container.contains(e.target)) {
                toggleMenu(false);
            }
        });

        // Tutup dengan Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMenuOpen) {
                toggleMenu(false);
            }
        });

        // Tangani window resize
        window.addEventListener('resize', function() {
            const rect = container.getBoundingClientRect();
            const maxX = window.innerWidth - BTN_SIZE - MARGIN;
            const maxY = window.innerHeight - BTN_SIZE - MARGIN;

            if (rect.left > maxX || rect.top > maxY) {
                const adjLeft = Math.min(rect.left, Math.max(MARGIN, maxX));
                const adjTop = Math.min(rect.top, Math.max(MARGIN, maxY));
                container.style.left = adjLeft + 'px';
                container.style.top = adjTop + 'px';
                repositionMenu(adjLeft, adjTop);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDraggableQuickMenu);
    } else {
        initDraggableQuickMenu();
    }
    document.addEventListener('turbo:load', initDraggableQuickMenu);
    document.addEventListener('pjax:complete', initDraggableQuickMenu);
})();
</script>
<!-- END: Floating Draggable Pintasan Cepat ERP Widget -->
@endauth
