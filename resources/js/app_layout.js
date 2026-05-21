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
