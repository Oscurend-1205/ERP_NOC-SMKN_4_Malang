<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NOC Inventory - Asal Barang</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed-dim": "#c4c5dd",
                        "primary-container": "#0061ff",
                        "on-primary": "#ffffff",
                        "on-tertiary-container": "#eef3ff",
                        "on-tertiary-fixed": "#0b1c30",
                        "tertiary-fixed-dim": "#b7c8e1",
                        "surface-dim": "#d9dadd",
                        "surface-container-low": "#f2f3f6",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#424656",
                        "inverse-surface": "#2e3133",
                        "on-primary-fixed-variant": "#003ea8",
                        "surface-tint": "#0052dc",
                        "surface": "#f8f9fc",
                        "outline": "#737687",
                        "inverse-on-surface": "#f0f1f4",
                        "on-secondary-fixed": "#181a2c",
                        "surface-container-lowest": "#ffffff",
                        "secondary": "#5b5d72",
                        "secondary-fixed": "#e0e0fa",
                        "secondary-container": "#e0e0fa",
                        "background": "#f8f9fc",
                        "outline-variant": "#c2c6d9",
                        "primary": "#004bca",
                        "on-error-container": "#93000a",
                        "on-background": "#191c1e",
                        "surface-container-highest": "#e1e2e5",
                        "primary-fixed-dim": "#b4c5ff",
                        "surface-container-high": "#e7e8eb",
                        "primary-fixed": "#dbe1ff",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed": "#d3e4fe",
                        "surface-container": "#edeef1",
                        "on-tertiary": "#ffffff",
                        "tertiary": "#48586d",
                        "on-secondary-container": "#616378",
                        "on-primary-fixed": "#00174b",
                        "on-tertiary-fixed-variant": "#38485d",
                        "inverse-primary": "#b4c5ff",
                        "error-container": "#ffdad6",
                        "on-secondary-fixed-variant": "#444559",
                        "surface-bright": "#f8f9fc",
                        "tertiary-container": "#607087",
                        "error": "#ba1a1a",
                        "on-primary-container": "#f1f2ff",
                        "on-error": "#ffffff",
                        "surface-variant": "#e1e2e5"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "stack-md": "16px",
                        "stack-lg": "32px",
                        "base": "4px",
                        "sidebar-width": "260px",
                        "gutter": "16px",
                        "stack-sm": "8px",
                        "container-padding": "24px"
                    },
                    "fontFamily": {
                        "headline-sm": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-lg": ["Inter"],
                        "label-sm": ["Inter"],
                        "body-md": ["Inter"],
                        "label-md": ["Inter"]
                    },
                    "fontSize": {
                        "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-sm": ["11px", {"lineHeight": "14px", "fontWeight": "500"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #444559;
            border-radius: 10px;
        }
        .table-row-hover:hover {
            background-color: rgba(0, 97, 255, 0.02);
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface flex min-h-screen">
<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-full w-[260px] bg-on-secondary-fixed flex flex-col z-50 overflow-hidden">
<div class="px-6 py-8 flex items-center gap-3">
<div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center font-headline-sm text-on-secondary-fixed">
<span class="font-bold">NOC</span>
</div>
<div>
<h1 class="font-headline-sm text-white leading-tight">NOC Inventory</h1>
<p class="text-[10px] text-secondary-fixed-dim tracking-wider uppercase">SMKN 4 MALANG</p>
</div>
</div>
<nav class="flex-1 px-3 space-y-1 overflow-y-auto custom-scrollbar">
<a class="flex items-center gap-3 px-4 py-3 text-secondary-fixed-dim hover:bg-on-secondary-fixed-variant hover:text-white transition-colors rounded-lg" href="#">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-body-md">Beranda</span>
</a>
<!-- Data Master Group -->
<div class="pt-2">
<div class="px-4 py-2 text-[11px] font-bold text-secondary-fixed-dim uppercase tracking-widest opacity-50">Master Data</div>
<div class="space-y-1">
<a class="flex items-center gap-3 px-4 py-2 text-secondary-fixed-dim hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined text-[18px]" data-icon="person">person</span>
<span class="font-body-md">Data User</span>
</a>
<a class="flex items-center gap-3 px-4 py-2 text-secondary-fixed-dim hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined text-[18px]" data-icon="meeting_room">meeting_room</span>
<span class="font-body-md">Data Ruangan</span>
</a>
<a class="flex items-center gap-3 px-4 py-2 text-secondary-fixed-dim hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined text-[18px]" data-icon="school">school</span>
<span class="font-body-md">Data Jurusan</span>
</a>
<a class="flex items-center gap-3 px-4 py-2 text-secondary-fixed-dim hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined text-[18px]" data-icon="local_shipping">local_shipping</span>
<span class="font-body-md">Data Supplier</span>
</a>
<a class="flex items-center gap-3 px-4 py-2 text-secondary-fixed-dim hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined text-[18px]" data-icon="category">category</span>
<span class="font-body-md">Kategori Barang</span>
</a>
<a class="flex items-center gap-3 px-4 py-2 text-secondary-fixed-dim hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined text-[18px]" data-icon="build">build</span>
<span class="font-body-md">Kondisi Barang</span>
</a>
<!-- Active State for Asal Barang -->
<a class="flex items-center gap-3 px-4 py-3 text-white border-l-4 border-primary bg-on-secondary-fixed-variant rounded-r-lg" href="#">
<span class="material-symbols-outlined" data-icon="database">database</span>
<span class="font-body-md font-semibold">Asal Barang</span>
</a>
</div>
</div>
<a class="flex items-center gap-3 px-4 py-3 text-secondary-fixed-dim hover:bg-on-secondary-fixed-variant hover:text-white transition-colors rounded-lg mt-2" href="#">
<span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
<span class="font-body-md">Data Barang</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-secondary-fixed-dim hover:bg-on-secondary-fixed-variant hover:text-white transition-colors rounded-lg" href="#">
<span class="material-symbols-outlined" data-icon="description">description</span>
<span class="font-body-md">Laporan</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-secondary-fixed-dim hover:bg-on-secondary-fixed-variant hover:text-white transition-colors rounded-lg" href="#">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
<span class="font-body-md">Pengaturan</span>
</a>
</nav>
<div class="p-4 border-t border-white/5">
<a class="flex items-center gap-3 px-4 py-3 text-secondary-fixed-dim hover:text-error transition-colors" href="#">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-body-md">Logout</span>
</a>
</div>
</aside>
<!-- TopAppBar & Content Wrapper -->
<div class="flex-1 ml-[260px] flex flex-col min-h-screen">
<!-- TopAppBar -->
<header class="h-16 bg-white flex justify-between items-center px-8 border-b border-outline-variant sticky top-0 z-40">
<div>
<h2 class="font-headline-sm text-on-surface font-bold">Admin</h2>
<p class="text-[12px] text-on-surface-variant">Welcome back, Administrator</p>
</div>
<div class="flex items-center gap-6">
<div class="flex items-center bg-inverse-surface text-white px-3 py-1.5 rounded-lg gap-3">
<span class="text-[12px] font-mono tracking-widest">00:00:00</span>
<span class="bg-error px-2 py-0.5 text-[10px] font-bold rounded uppercase">CLOSED</span>
</div>
<div class="flex items-center gap-4">
<button class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors relative">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-white"></span>
</button>
<button class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors">
<span class="material-symbols-outlined" data-icon="help">help</span>
</button>
<div class="h-8 w-px bg-outline-variant"></div>
<div class="flex items-center gap-3 cursor-pointer group">
<div class="text-right">
<p class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors">Administrator</p>
<p class="text-[11px] text-on-surface-variant">Admin NOC</p>
</div>
<div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center overflow-hidden border border-outline-variant">
<img alt="Admin" class="w-full h-full object-cover" data-alt="A professional studio portrait of a corporate male administrator in his 30s, featuring clean-cut features and a friendly expression. The background is a soft, out-of-focus modern office environment with professional cool-toned lighting and a minimalist aesthetic that conveys trust and technical expertise." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAc0g7KzqFcFDfa808Pi8Uqc1K4zORM6yU7loIvPWZi2hA9-65sWUKBTAbgCbrS76OnJ8gVIqowzCvuxG0YAecrLnBdz5l7fpzTc0N4J88afSu2U4DqzT59PGbtrKB4gEzuIt2zO5pY35c7Op6PkztOq_syeB9862N1veWdzwf88Da3srVu-rzNfpevc0qXKjWBQUMvdZbYmUWoc_5S39Eh-ktAnXhRW0KYsyUCJX3JwaqK5mOTdmTLyaL0jM57CPJ9gDpm2Z8vNik"/>
</div>
</div>
</div>
</div>
</header>
<!-- Main Content -->
<main class="p-8 flex-1 max-w-[1600px]">
<div class="mb-8">
<h1 class="font-headline-md text-on-surface mb-2">Asal Barang</h1>
<nav class="flex text-sm text-on-surface-variant gap-2 items-center">
<span class="hover:text-primary cursor-pointer">Data Master</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-primary font-medium">Asal Barang</span>
</nav>
</div>
<div class="grid grid-cols-1 gap-8">
<!-- Form Card -->
<section class="bg-white rounded-xl border border-outline-variant p-6 shadow-[0px_4px_12px_rgba(26,28,46,0.05)]">
<div class="flex items-center gap-3 mb-6">
<div class="w-1.5 h-6 bg-primary rounded-full"></div>
<h3 class="font-headline-sm">Tambah Asal Baru</h3>
</div>
<form class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
<div class="md:col-span-4 space-y-2">
<label class="block text-[12px] font-bold text-on-surface-variant uppercase tracking-wide">Nama Asal</label>
<input class="w-full h-11 px-4 rounded-lg border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none bg-surface" placeholder="e.g., Dana BOS 2024" type="text"/>
</div>
<div class="md:col-span-6 space-y-2">
<label class="block text-[12px] font-bold text-on-surface-variant uppercase tracking-wide">Keterangan</label>
<input class="w-full h-11 px-4 rounded-lg border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none bg-surface" placeholder="Masukkan keterangan detail..." type="text"/>
</div>
<div class="md:col-span-2">
<button class="w-full h-11 bg-primary text-white font-bold rounded-lg flex items-center justify-center gap-2 hover:bg-primary-container transition-all active:scale-95 shadow-lg shadow-primary/20" type="submit">
<span class="material-symbols-outlined text-[18px]" data-icon="save">save</span>
<span>Simpan Data</span>
</button>
</div>
</form>
</section>
<!-- Table Card -->
<section class="bg-white rounded-xl border border-outline-variant overflow-hidden shadow-[0px_4px_12px_rgba(26,28,46,0.05)]">
<div class="p-6 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-4">
<div class="flex items-center gap-3">
<button class="flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg hover:bg-surface transition-colors text-sm font-medium">
<span class="material-symbols-outlined text-green-600" data-icon="description">description</span>
                                Excel
                            </button>
<button class="flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg hover:bg-surface transition-colors text-sm font-medium">
<span class="material-symbols-outlined text-red-600" data-icon="picture_as_pdf">picture_as_pdf</span>
                                PDF
                            </button>
</div>
<div class="relative max-w-sm w-full">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant" data-icon="search">search</span>
<input class="w-full pl-10 pr-4 py-2 bg-surface rounded-lg border border-outline-variant focus:border-primary transition-all text-sm outline-none" placeholder="Cari data..." type="text"/>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-surface text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">
<tr>
<th class="px-6 py-4 w-16">NO</th>
<th class="px-6 py-4">Nama Asal Barang</th>
<th class="px-6 py-4">Keterangan</th>
<th class="px-6 py-4">Status</th>
<th class="px-6 py-4 text-center">Action</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<!-- Row 1 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-on-surface-variant">1</td>
<td class="px-6 py-4 font-bold text-on-surface">Dana BOS 2024</td>
<td class="px-6 py-4 text-on-surface-variant">Pengadaan rutin tahunan dari dana BOS</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            Aktif
                                        </span>
</td>
<td class="px-6 py-4">
<div class="flex items-center justify-center gap-3">
<button class="w-8 h-8 flex items-center justify-center text-primary-container hover:bg-primary/10 rounded transition-colors" title="Edit">
<span class="material-symbols-outlined text-[18px]" data-icon="edit">edit</span>
</button>
<button class="w-8 h-8 flex items-center justify-center text-error hover:bg-error/10 rounded transition-colors" title="Delete">
<span class="material-symbols-outlined text-[18px]" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-on-surface-variant">2</td>
<td class="px-6 py-4 font-bold text-on-surface">Hibah Pemprov Jatim</td>
<td class="px-6 py-4 text-on-surface-variant">Bantuan peralatan praktek jurusan TKO</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            Aktif
                                        </span>
</td>
<td class="px-6 py-4">
<div class="flex items-center justify-center gap-3">
<button class="w-8 h-8 flex items-center justify-center text-primary-container hover:bg-primary/10 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]" data-icon="edit">edit</span>
</button>
<button class="w-8 h-8 flex items-center justify-center text-error hover:bg-error/10 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-on-surface-variant">3</td>
<td class="px-6 py-4 font-bold text-on-surface">Komite Sekolah 23/24</td>
<td class="px-6 py-4 text-on-surface-variant">Sumbangan fasilitas penunjang KBM</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            Aktif
                                        </span>
</td>
<td class="px-6 py-4">
<div class="flex items-center justify-center gap-3">
<button class="w-8 h-8 flex items-center justify-center text-primary-container hover:bg-primary/10 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]" data-icon="edit">edit</span>
</button>
<button class="w-8 h-8 flex items-center justify-center text-error hover:bg-error/10 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
<!-- Row 4 -->
<tr class="table-row-hover transition-colors">
<td class="px-6 py-4 text-on-surface-variant">4</td>
<td class="px-6 py-4 font-bold text-on-surface">Dana BPOPP 2023</td>
<td class="px-6 py-4 text-on-surface-variant">Biaya Penunjang Operasional Penyelenggaraan Pendidikan</td>
<td class="px-6 py-4">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            Aktif
                                        </span>
</td>
<td class="px-6 py-4">
<div class="flex items-center justify-center gap-3">
<button class="w-8 h-8 flex items-center justify-center text-primary-container hover:bg-primary/10 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]" data-icon="edit">edit</span>
</button>
<button class="w-8 h-8 flex items-center justify-center text-error hover:bg-error/10 rounded transition-colors">
<span class="material-symbols-outlined text-[18px]" data-icon="delete">delete</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<div class="p-6 bg-surface flex flex-col md:flex-row items-center justify-between gap-4">
<p class="text-sm text-on-surface-variant">Showing 1 to 10 of 45 entries</p>
<div class="flex gap-2">
<button class="px-3 py-1.5 border border-outline-variant rounded hover:bg-white text-sm font-medium text-on-surface-variant transition-colors disabled:opacity-50" disabled="">Prev</button>
<button class="px-3 py-1.5 bg-primary text-white rounded text-sm font-bold shadow-sm">1</button>
<button class="px-3 py-1.5 border border-outline-variant rounded hover:bg-white text-sm font-medium text-on-surface-variant transition-colors">2</button>
<button class="px-3 py-1.5 border border-outline-variant rounded hover:bg-white text-sm font-medium text-on-surface-variant transition-colors">3</button>
<span class="px-2 self-center text-on-surface-variant">...</span>
<button class="px-3 py-1.5 border border-outline-variant rounded hover:bg-white text-sm font-medium text-on-surface-variant transition-colors">Next</button>
</div>
</div>
</section>
</div>
</main>
<!-- Footer -->
<footer class="p-6 text-center text-[12px] text-on-surface-variant">
            © 2024 SMKN 4 MALANG. All rights reserved. Managed by NOC Team.
        </footer>
</div>
<script>
        // Simple interactive effects
        document.querySelectorAll('.table-row-hover').forEach(row => {
            row.addEventListener('click', () => {
                // Future implementation: selection or navigation
            });
        });

        // Search highlight simulation
        const searchInput = document.querySelector('input[placeholder="Cari data..."]');
        if(searchInput) {
            searchInput.addEventListener('focus', () => {
                searchInput.parentElement.classList.add('ring-2', 'ring-primary/20');
            });
            searchInput.addEventListener('blur', () => {
                searchInput.parentElement.classList.remove('ring-2', 'ring-primary/20');
            });
        }
    </script>
</body></html>