<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - ERP NOC SMKN 4 Malang</title>
    <meta name="description" content="Sistem ERP untuk pengelolaan barang elektronik laboratorium NOC SMKN 4 Malang">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ============================================
           CSS Design System - ERP NOC SMKN 4 Malang
           ============================================ */

        :root {
            /* Primary Colors - Hijau NOC */
            --primary: #00a86b;
            --primary-dark: #008c59;
            --primary-light: #e6f7f0;
            --primary-glow: rgba(0, 168, 107, 0.15);

            /* Accent */
            --accent: #1a1a2e;
            --accent-light: #16213e;

            /* Neutral */
            --bg-body: #f0f2f5;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            --bg-sidebar-hover: #f1f5f9;
            --bg-sidebar-active: #e2e8f0;

            /* Text */
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --text-sidebar: #cbd5e1;
            --text-sidebar-active: #00a86b;

            /* Status Colors */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;

            /* Borders & Shadows */
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05);

            /* Layout */
            --sidebar-width: 200px;
            --sidebar-collapsed: 64px;
            --topbar-height: 48px;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;

            /* Transitions */
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* ============================================
           SIDEBAR
           ============================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-sidebar);
            z-index: 1000;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 16px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border-color);
            min-height: 64px;
        }

        .sidebar-brand img {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: contain;
            background: white;
            padding: 4px;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            overflow: hidden;
        }

        .sidebar-brand-text h1 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.02em;
            line-height: 1.3;
        }

        .sidebar-brand-text span {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .sidebar-section-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            padding: 16px 14px 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 2px;
            position: relative;
        }

        .sidebar-link:hover {
            background: var(--bg-sidebar-hover);
            color: #fff;
        }

        .sidebar-link.active {
            background: var(--bg-sidebar-active);
            color: var(--text-sidebar-active);
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 24px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-link i {
            font-size: 18px;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-footer-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-muted);
            font-size: 12px;
        }

        .sidebar-footer-info .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* ============================================
           TOPBAR
           ============================================ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 999;
            transition: var(--transition);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: var(--text-primary);
            cursor: pointer;
            padding: 6px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }

        .topbar-toggle:hover {
            background: var(--bg-body);
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-btn {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 18px;
            position: relative;
        }

        .topbar-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .topbar-btn .badge-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid var(--bg-card);
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            transition: var(--transition);
        }

        .content-wrapper {
            padding: 16px;
        }

        /* ============================================
           CARDS
           ============================================ */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .card-body {
            padding: 24px;
        }

        /* ============================================
           STAT CARDS
           ============================================ */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            padding: 24px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--stat-color, var(--primary));
            opacity: 0;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card:hover::after {
            opacity: 1;
        }

        .stat-info h3 {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .stat-info .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .stat-info .stat-sub {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        /* ============================================
           TABLE
           ============================================ */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-secondary);
            background: var(--bg-body);
            border-bottom: 1px solid var(--border-color);
        }

        table td {
            padding: 14px 16px;
            font-size: 14px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        table tbody tr {
            transition: var(--transition);
        }

        table tbody tr:hover {
            background: var(--primary-light);
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ============================================
           BADGES
           ============================================ */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-secondary { background: #f1f5f9; color: #475569; }

        /* ============================================
           BUTTONS
           ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            line-height: 1.4;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(0, 168, 107, 0.3);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--bg-body);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 12px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            justify-content: center;
            border-radius: var(--radius-sm);
        }

        /* ============================================
           FORMS
           ============================================ */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: inherit;
            color: var(--text-primary);
            background: var(--bg-card);
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 0 24px;
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 4px;
        }

        /* ============================================
           ALERTS
           ============================================ */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================================
           PAGE HEADER
           ============================================ */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .page-header p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        /* ============================================
           PAGINATION
           ============================================ */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px 0 4px;
        }

        .pagination-wrapper nav span,
        .pagination-wrapper nav a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            margin: 0 2px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        /* ============================================
           EMPTY STATE
           ============================================ */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.4;
        }

        .empty-state p {
            font-size: 15px;
            font-weight: 500;
        }

        /* ============================================
           FILTER BAR
           ============================================ */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .filter-bar .form-control {
            width: auto;
            min-width: 160px;
            padding: 8px 12px;
            font-size: 13px;
        }

        .search-input {
            position: relative;
        }

        .search-input input {
            padding-left: 38px;
            min-width: 240px;
        }

        .search-input i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
        }

        /* ============================================
           SIDEBAR OVERLAY (MOBILE)
           ============================================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.active {
            opacity: 1;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .topbar {
                left: 0;
            }

            .topbar-toggle {
                display: flex;
            }

            .main-content {
                margin-left: 0;
            }

            .stat-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 640px) {
            .content-wrapper {
                padding: 16px;
            }

            .stat-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-bar .form-control,
            .search-input input {
                width: 100%;
                min-width: unset;
            }
        }

        /* ============================================
           ANIMATION UTILITIES
           ============================================ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up {
            animation: fadeInUp 0.4s ease forwards;
        }

        .fade-in-up:nth-child(1) { animation-delay: 0.05s; }
        .fade-in-up:nth-child(2) { animation-delay: 0.1s; }
        .fade-in-up:nth-child(3) { animation-delay: 0.15s; }
        .fade-in-up:nth-child(4) { animation-delay: 0.2s; }
        .fade-in-up:nth-child(5) { animation-delay: 0.25s; }
        .fade-in-up:nth-child(6) { animation-delay: 0.3s; }

        /* ============================================
           UTILITIES
           ============================================ */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-muted { color: var(--text-muted); }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .gap-2 { gap: 8px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .flex-wrap { flex-wrap: wrap; }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }

        @media (max-width: 640px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    {{-- Sidebar Overlay (Mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand" style="background: var(--primary, #3b3fbd); margin: 8px; padding: 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            <img src="{{ asset('images/Logo-NOC.jpeg') }}" alt="Logo NOC" style="width: 44px; height: 44px; object-fit: contain; border-radius: 50%; border: 2px solid rgba(255,255,255,0.2);">
            <div class="sidebar-brand-text">
                <h1 style="font-size: 13px; font-weight: 800; color: #ffffff; margin: 0; text-transform: uppercase; line-height: 1.2;">Inventory System</h1>
                <span style="font-size: 10px; color: rgba(255,255,255,0.7); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">SMKN 4 Malang</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section-title">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Manajemen Data</div>

            <a href="{{ route('items.index') }}" class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                <i class="bi bi-cpu"></i>
                <span>Barang Elektronik</span>
            </a>

            @if(Auth::user()->role === 'Superadmin')
            <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Kategori</span>
            </a>

            <a href="{{ route('locations.index') }}" class="sidebar-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i>
                <span>Lokasi Lab</span>
            </a>
            @endif

            <div class="sidebar-section-title">Aktivitas</div>

            <a href="{{ route('movements.index') }}" class="sidebar-link {{ request()->routeIs('movements.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i>
                <span>Mutasi Barang</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-footer-info">
                <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div>
                    <div style="color: #fff; font-weight: 600; font-size: 13px;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 11px;">
                        <span style="display: inline-block; padding: 1px 6px; border-radius: 4px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background: rgba(0,168,107,0.2); color: #6ee7b7;">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link w-full" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; cursor: pointer;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="topbar-toggle" onclick="toggleSidebar()" id="toggleBtn">
                <i class="bi bi-list"></i>
            </button>
            <h2 class="topbar-title">@yield('title', 'Dashboard')</h2>
        </div>
        <div class="topbar-right">
            <button class="topbar-btn" title="Notifikasi">
                <i class="bi bi-bell"></i>
            </button>
            <button class="topbar-btn" title="Pengaturan">
                <i class="bi bi-gear"></i>
            </button>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="main-content">
        <div class="content-wrapper">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @vite(['resources/js/app_layout.js'])
    @stack('scripts')
</body>
</html>
