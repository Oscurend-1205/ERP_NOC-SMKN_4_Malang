@php
    $currentHour = now()->format('H');
    $isOpen = ($currentHour >= 6 && $currentHour < 15);
    $isDark = false;

    if (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark') {
        $isDark = true;
    } elseif (isset($_SERVER['HTTP_X_THEME'])) {
        $isDark = $_SERVER['HTTP_X_THEME'] === 'dark';
    }

    // Generate 2 initials for profile fallback
    $userName = trim(Auth::user()->name ?? 'User');
    $nameParts = preg_split('/\s+/', $userName);
    $initials = '';

    if (count($nameParts) >= 2) {
        $initials = strtoupper(
            substr($nameParts[0], 0, 1) .
            substr($nameParts[1], 0, 1)
        );
    } else {
        $initials = strtoupper(substr($userName, 0, 2));
    }
@endphp

<!-- BEGIN: Header -->
<header
    class="flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-40 h-[68px] transition-colors duration-300"
    data-purpose="top-header"
    id="mainHeader"
>

    <!-- =========================================================
         LEFT SECTION
    ========================================================== -->
    <div class="flex items-center min-w-0">

        <!-- Mobile Menu -->
        <button
            onclick="toggleSidebar()"
            class="md:hidden mr-2 p-2 text-gray-600 hover:text-primary hover:bg-gray-100 rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30"
            aria-label="Toggle Menu"
        >
            <span class="material-symbols-outlined text-[22px]">menu</span>
        </button>

        <!-- Logo & Application Identity -->
        <div class="flex items-center flex-shrink-0">

            <!-- Logo -->
            <div
                class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center border border-blue-700 shadow-sm"
            >
                <span class="material-symbols-outlined text-white text-[21px]">
                    enterprise
                </span>
            </div>

            <!-- Application Name -->
            <div class="hidden sm:block ml-3 leading-none">
                <h2 class="text-[16px] font-bold text-gray-900 tracking-tight">
                    ERP NOC
                </h2>

                <p class="text-[11px] text-gray-500 font-medium mt-1 tracking-wide uppercase">
                    SMKN 4 MALANG
                </p>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-1.5 ml-4 text-xs text-gray-500" aria-label="Breadcrumb">
            <a
                href="{{ route('dashboard') }}"
                class="text-gray-500 hover:text-gray-900 font-medium transition-colors"
            >
                Beranda
            </a>

            <span class="material-symbols-outlined text-[17px] text-gray-400">
                chevron_right
            </span>

            <span
                class="text-gray-900 font-semibold"
                id="breadcrumbCurrent"
            >
                @if(request()->routeIs('dashboard'))
                    Dashboard
                @elseif(request()->routeIs('items.*'))
                    Data Barang
                @elseif(request()->routeIs('peminjaman.*'))
                    Data Peminjaman
                @elseif(request()->routeIs('procurements.*'))
                    Pengadaan Alat
                @elseif(request()->routeIs('perawatan.*'))
                    Data Perawatan
                @elseif(request()->routeIs('stock-take.*'))
                    Stok Opname
                @elseif(request()->routeIs('laporan.*'))
                    Laporan
                @elseif(request()->routeIs('qr.*'))
                    Stasiun QR
                @elseif(request()->routeIs('locations.*'))
                    Data Ruangan
                @elseif(request()->routeIs('categories.*'))
                    Kategori Barang
                @elseif(request()->routeIs('users.*'))
                    Data User
                @elseif(request()->routeIs('jurusan.*'))
                    Data Jurusan
                @elseif(request()->routeIs('supplier.*'))
                    Data Supplier
                @elseif(request()->routeIs('kondisi.*'))
                    Kondisi Barang
                @elseif(request()->routeIs('asal.*'))
                    Asal Barang
                @elseif(request()->routeIs('activity-log.*'))
                    Audit Trail
                @elseif(request()->routeIs('settings.*'))
                    Pengaturan
                @elseif(request()->routeIs('notifications.*'))
                    Notifikasi
                @elseif(request()->routeIs('profile.*'))
                    Profil
                @elseif(request()->routeIs('guide.*'))
                    Panduan Sistem
                @else
                    {{ Request::segment(1) ? ucfirst(str_replace('-', ' ', Request::segment(1))) : 'Dashboard' }}
                @endif
            </span>
        </nav>
    </div>


    <!-- =========================================================
         RIGHT SECTION
    ========================================================== -->
    <div class="flex items-center gap-1 md:gap-3 flex-shrink-0">

        <!-- =====================================================
             REALTIME CLOCK
        ====================================================== -->
        <div
            class="flex items-center h-11 px-4 gap-2 bg-gray-50 border border-gray-200 rounded-xl"
        >
            <span class="material-symbols-outlined text-gray-500 text-[18px]">
                schedule
            </span>

            <span
                class="realtime-clock-display text-[14px] font-mono font-semibold text-gray-700 tracking-wide"
            >
                00:00:00
            </span>

            <span
                class="operational-status font-bold text-[12px] {{ $isOpen ? 'text-emerald-500' : 'text-red-500' }}"
            >
                {{ $isOpen ? 'OPEN' : 'CLOSED' }}
            </span>
        </div>


        <!-- =====================================================
             THEME TOGGLE
        ====================================================== -->
        <button
            id="themeToggle"
            onclick="window.toggleTheme()"
            class="topbar-icon-btn w-10 h-10 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none"
            title="Ganti Tema"
            aria-label="Ganti Tema"
        >
            <span
                class="material-symbols-outlined text-[22px]"
                id="themeIcon"
            >
                dark_mode
            </span>
        </button>


        <!-- =====================================================
             NOTIFICATION
        ====================================================== -->
        <div
            class="relative"
            id="notificationDropdownContainer"
        >
            <button
                id="notificationBellBtn"
                type="button"
                class="topbar-icon-btn relative w-10 h-10 flex items-center justify-center text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none"
                title="Notifikasi"
                aria-label="Notifikasi"
            >
                <span class="material-symbols-outlined text-[22px]">
                    notifications
                </span>

                <span
                    id="notificationBadge"
                    class="hidden absolute top-1 right-1 min-w-[17px] h-[17px] px-1 bg-red-500 text-white text-[9px] font-bold rounded-full items-center justify-center border-2 border-white"
                >
                    0
                </span>
            </button>


            <!-- Notification Dropdown -->
            <div
                id="notificationDropdown"
                class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-xl border border-gray-200 z-[200] overflow-hidden animate-in fade-in slide-in-from-top-2"
            >

                <!-- Dropdown Header -->
                <div
                    class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between"
                >
                    <div class="flex items-center gap-2">

                        <div
                            class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center"
                        >
                            <span class="material-symbols-outlined text-blue-600 text-[18px]">
                                notifications
                            </span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-800 text-sm">
                                Notifikasi
                            </span>

                            <span
                                id="notificationCountPill"
                                class="hidden ml-2 text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full"
                            >
                                0 baru
                            </span>
                        </div>
                    </div>

                    <button
                        id="markAllReadBtn"
                        type="button"
                        class="text-xs text-primary hover:text-primary-dark font-semibold transition-colors focus:outline-none flex items-center gap-1 px-2 py-1 rounded-md hover:bg-gray-100"
                    >
                        <span class="material-symbols-outlined text-[14px]">
                            mark_email_read
                        </span>
                        Semua dibaca
                    </button>
                </div>


                <!-- Notification List -->
                <div
                    id="notificationList"
                    class="max-h-72 overflow-y-auto divide-y divide-gray-100"
                >
                    <div class="py-8 text-center text-gray-400 text-xs">
                        <span class="material-symbols-outlined text-3xl mb-1 text-gray-300 block">
                            notifications_off
                        </span>
                        Tidak ada notifikasi baru
                    </div>
                </div>


                <!-- Notification Footer -->
                <div class="p-3 bg-gray-50 border-t border-gray-200">
                    <a
                        href="{{ route('notifications.index') }}"
                        class="text-xs font-semibold text-primary hover:text-primary-dark transition-colors inline-flex items-center gap-1 justify-center w-full"
                    >
                        <span>Lihat Semua Notifikasi</span>

                        <span class="material-symbols-outlined text-[14px]">
                            arrow_forward
                        </span>
                    </a>
                </div>

            </div>
        </div>


        <!-- =====================================================
             USER PROFILE
        ====================================================== -->
        <div class="relative ml-1">

            <a
                href="{{ route('profile.index') }}"
                class="flex items-center gap-3 pl-2 pr-1 py-1 rounded-xl hover:bg-gray-50 transition-colors group"
                title="Lihat Profil Saya"
            >

                <!-- Avatar -->
                @if(Auth::user()->avatar)

                    <img
                        alt="User Profile"
                        src="{{ Storage::url(Auth::user()->avatar) }}"
                        class="w-11 h-11 rounded-full object-cover border-2 border-gray-200 group-hover:border-blue-400 transition-colors shadow-sm"
                    />

                @else

                    <!-- Initial Avatar -->
                    <div
                        class="w-11 h-11 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-[14px] border-2 border-blue-100 group-hover:border-blue-300 transition-colors shadow-sm"
                    >
                        {{ $initials }}
                    </div>

                @endif


                <!-- User Information -->
                <div class="min-w-0">

                    <p
                        class="text-[14px] font-semibold text-gray-800 leading-tight whitespace-nowrap"
                    >
                        {{ Auth::user()->name ?? 'Admin' }}
                    </p>

                    <span
                        class="inline-flex items-center gap-1.5 text-[11px] text-gray-500 mt-1"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-green-500"
                        ></span>

                        {{ Auth::user()->role ?? 'Admin' }}
                    </span>

                </div>

            </a>
        </div>

    </div>

</header>
<!-- END: Header -->


<!-- =============================================================
     THEME SCRIPT
============================================================== -->
<script>
    // Apply saved theme on load
    (function() {
        const savedTheme = document.cookie.replace(
            /(?:(?:^|.*;\s*)erp-noc-theme\s*\=\s*([^;]*).*$)|^.*$/,
            '$1'
        );

        if (savedTheme === 'dark') {
            setTimeout(function() {
                if (window.toggleTheme) {
                    window.toggleTheme();
                }
            }, 100);
        }
    })();
</script>


<!-- =============================================================
     REALTIME CLOCK
============================================================== -->
<script>
    function updateClock() {

        const now = new Date();

        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');

        document
            .querySelectorAll('.realtime-clock-display')
            .forEach(el => {
                el.textContent = h + ':' + m + ':' + s;
            });


        const hour = now.getHours();

        document
            .querySelectorAll('.operational-status')
            .forEach(statusEl => {

                if (hour >= 6 && hour < 15) {

                    statusEl.textContent = 'OPEN';

                    statusEl.className =
                        'operational-status font-bold text-[12px] text-emerald-500';

                } else {

                    statusEl.textContent = 'CLOSED';

                    statusEl.className =
                        'operational-status font-bold text-[12px] text-red-500';
                }
            });
    }

    updateClock();

    setInterval(updateClock, 1000);
</script>


<!-- =============================================================
     NOTIFICATION DROPDOWN & POLLING
============================================================== -->
<script>
    (function() {

        const bellBtn = document.getElementById('notificationBellBtn');
        const dropdown = document.getElementById('notificationDropdown');
        const container = document.getElementById('notificationDropdownContainer');
        const badge = document.getElementById('notificationBadge');
        const countPill = document.getElementById('notificationCountPill');
        const list = document.getElementById('notificationList');
        const markAllBtn = document.getElementById('markAllReadBtn');

        const csrfToken =
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');


        if (!bellBtn || !dropdown) return;


        // Open / close notification dropdown
        bellBtn.addEventListener('click', function(e) {

            e.stopPropagation();

            dropdown.classList.toggle('hidden');

        });


        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {

            if (container && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }

        });


        // Fetch notifications
        function fetchNotifications() {

            fetch("{{ route('notifications.unread') }}", {

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }

            })

            .then(res => res.json())

            .then(data => {

                // Badge
                if (data.count > 0) {

                    badge.textContent =
                        data.count > 99 ? '99+' : data.count;

                    badge.classList.remove('hidden');
                    badge.classList.add('flex');

                    countPill.textContent =
                        data.count + ' baru';

                    countPill.classList.remove('hidden');

                } else {

                    badge.classList.add('hidden');
                    badge.classList.remove('flex');

                    countPill.classList.add('hidden');
                }


                // Notification list
                if (
                    data.notifications &&
                    data.notifications.length > 0
                ) {

                    let html = '';

                    data.notifications.forEach(item => {

                        const iconBg =
                            item.type === 'danger'
                                ? 'bg-red-100 text-red-600'
                                : item.type === 'warning'
                                    ? 'bg-amber-100 text-amber-600'
                                    : item.type === 'success'
                                        ? 'bg-emerald-100 text-emerald-600'
                                        : 'bg-blue-100 text-blue-600';


                        html += `
                            <a
                                href="${item.action_url ? item.action_url : '#'}"
                                data-id="${item.id}"
                                class="notif-item block px-4 py-3 hover:bg-gray-50 transition-colors text-left group border-b border-gray-100 last:border-0"
                            >

                                <div class="flex items-start gap-3">

                                    <div class="w-8 h-8 rounded-lg ${iconBg} flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="material-symbols-outlined text-[18px]">
                                            ${item.icon || 'notifications'}
                                        </span>
                                    </div>

                                    <div class="flex-1 min-w-0">

                                        <div class="flex items-center justify-between">

                                            <p class="text-xs font-semibold text-gray-800 truncate group-hover:text-primary transition-colors">
                                                ${item.title}
                                            </p>

                                            <span class="text-[10px] text-gray-400 flex-shrink-0 ml-2">
                                                ${item.time_ago}
                                            </span>

                                        </div>

                                        <p class="text-[11px] text-gray-500 line-clamp-2 mt-0.5">
                                            ${item.message}
                                        </p>

                                    </div>

                                </div>

                            </a>
                        `;
                    });


                    list.innerHTML = html;


                    // Mark notification as read
                    list
                        .querySelectorAll('.notif-item')
                        .forEach(el => {

                            el.addEventListener('click', function() {

                                const id =
                                    this.getAttribute('data-id');

                                if (id) {

                                    fetch(
                                        '/notifications/' + id + '/read',
                                        {
                                            method: 'POST',

                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken,
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/json'
                                            }
                                        }
                                    )
                                    .then(() => fetchNotifications());
                                }

                            });

                        });

                } else {

                    list.innerHTML = `
                        <div class="py-8 text-center text-gray-400 text-xs">
                            <span class="material-symbols-outlined text-3xl mb-1 text-gray-300 block">
                                notifications_off
                            </span>
                            Tidak ada notifikasi baru
                        </div>
                    `;
                }

            })

            .catch(err =>
                console.debug('Notif poll error:', err)
            );
        }


        // Mark all notifications as read
        if (markAllBtn) {

            markAllBtn.addEventListener('click', function() {

                fetch(
                    "{{ route('notifications.mark-all-read') }}",
                    {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }
                )
                .then(res => res.json())
                .then(() => fetchNotifications());

            });

        }


        // Initial fetch
        fetchNotifications();

        // Poll every 30 seconds
        setInterval(fetchNotifications, 30000);

    })();
</script>


<!-- =============================================================
     HEADER SCROLL EFFECT
============================================================== -->
<script>
    let lastScrollY = 0;

    const header = document.getElementById('mainHeader');

    window.addEventListener('scroll', function() {

        if (window.scrollY > 10) {

            header.classList.add('shadow-sm');

            header.style.borderBottomColor =
                'rgba(0,0,0,0.06)';

        } else {

            header.classList.remove('shadow-sm');

            header.style.borderBottomColor = '';
        }

        lastScrollY = window.scrollY;
    });
</script>


<!-- =============================================================
     HEADER STYLES
============================================================== -->
<style>

    @keyframes slideInFromTop {

        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    .animate-in {
        animation: slideInFromTop 0.18s ease-out;
    }


    .fade-in {
        animation: fadeIn 0.18s ease-out;
    }


    .slide-in-from-top-2 {
        animation: slideInFromTop 0.18s ease-out;
    }


    /* Topbar icon */
    .topbar-icon-btn {
        transition:
            background-color 0.2s ease,
            color 0.2s ease,
            transform 0.15s ease;
    }


    .topbar-icon-btn:hover {
        transform: translateY(-1px);
    }


    /* Dark mode transition */
    body.transitioning,
    body.transitioning * {

        transition:
            background-color 0.3s ease,
            color 0.3s ease,
            border-color 0.3s ease,
            box-shadow 0.3s ease !important;
    }


    /* Responsive */
    @media (max-width: 640px) {

        #mainHeader {

            padding-left: 12px;
            padding-right: 12px;
            height: 58px;
        }

    }


    /* Print */
    @media print {

        #mainHeader {
            display: none !important;
        }

    }

</style>