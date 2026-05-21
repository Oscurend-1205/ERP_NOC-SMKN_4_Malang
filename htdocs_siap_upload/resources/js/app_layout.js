/**
 * Global App Layout Logic - ERP NOC SMKN 4 Malang
 * Separated from resources/views/layouts/app.blade.php
 */

window.toggleSidebar = function() {
    const sidebar = document.getElementById('mainSidebar') || document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop') || document.getElementById('sidebarOverlay');
    
    if (sidebar) {
        sidebar.classList.toggle('sidebar-closed');
        const isClosed = sidebar.classList.contains('sidebar-closed');
        
        // Handle backdrop visibility on mobile
        if (backdrop) {
            if (isClosed) {
                backdrop.classList.add('hidden');
            } else {
                if (window.innerWidth < 768) {
                    backdrop.classList.remove('hidden');
                }
            }
        }
        
        // Save state
        localStorage.setItem('sidebarState', isClosed ? 'closed' : 'open');
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
