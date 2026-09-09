/**
 * Global App Layout Logic - ERP NOC SMKN 4 Malang
 * Separated from resources/views/layouts/app.blade.php
 */

window.toggleSidebar = function() {
    const sidebar = document.getElementById('mainSidebar') || document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop') || document.getElementById('sidebarOverlay');
    
    // Only allow toggle on mobile (width < 768px)
    if (sidebar && window.innerWidth < 768) {
        sidebar.classList.toggle('sidebar-mobile-open');
        
        if (backdrop) {
            backdrop.classList.toggle('hidden');
        }
    }
}

// Theme toggle with smooth transition
window.toggleTheme = function() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    
    // Add transitional class for smooth animation
    html.classList.add('theme-transitioning');
    
    if (isDark) {
        html.classList.remove('dark');
        html.style.colorScheme = 'light';
    } else {
        html.classList.add('dark');
        html.style.colorScheme = 'dark';
    }
    
    // Update theme icon
    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.textContent = isDark ? 'dark_mode' : 'light_mode';
    }
    
    // Update cookie
    document.cookie = 'erp-noc-theme=' + (isDark ? 'light' : 'dark') + ';path=/;max-age=31536000';
    
    // Remove transitional class after animation completes
    setTimeout(function() {
        html.classList.remove('theme-transitioning');
    }, 400);
    
    // Dispatch custom event
    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { isDark: !isDark } }));
}

// Auto-hide alerts after 4 seconds
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    });
});

// Breadcrumb is now handled by Blade directive in topbar.blade.php
// This function is kept for backward compatibility but no longer auto-runs
function updateBreadcrumb() {
    const current = document.getElementById('breadcrumbCurrent');
    if (!current) return;
    const segment = window.location.pathname.split('/')[1] || 'Dashboard';
    const labels = {
        'dashboard': 'Dashboard',
        'items': 'Data Barang',
        'peminjaman': 'Data Peminjaman',
        'procurements': 'Pengadaan Alat',
        'stock-take': 'Stok Opname',
        'laporan': 'Laporan',
        'guide': 'Panduan Sistem',
        'qr': 'Stasiun QR',
        'profile': 'Profil',
        'settings': 'Pengaturan',
        'activity-log': 'Audit Trail',
    };
    current.textContent = labels[segment] || segment.charAt(0).toUpperCase() + segment.slice(1);
}
// Removed auto-run to let Blade handle breadcrumb dynamically

// Smooth scroll for anchor links
document.addEventListener('click', function(e) {
    const anchor = e.target.closest('a[href^="#"]');
    if (anchor) {
        e.preventDefault();
        const target = document.querySelector(anchor.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});

// Add keyboard shortcut: Ctrl+K for search
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('headerSearch');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
});
