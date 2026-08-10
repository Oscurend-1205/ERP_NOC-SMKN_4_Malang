const customAlert = {
    show: function (options) {
        const existingAlert = document.getElementById('custom-alert-overlay');
        if (existingAlert) existingAlert.remove();

        const {
            title = 'Informasi',
            message = '',
            type = 'info',
            confirmText = 'OK',
            cancelText = 'Batal',
            onConfirm = null,
            onCancel = null,
        } = options;
        let iconHtml = '';
        let iconBgColor = '';
        let iconTextColor = '';
        let confirmBtnClass = '';

        switch (type) {
            case 'success':
                iconHtml = '<i data-lucide="check-circle" class="w-8 h-8"></i>';
                iconBgColor = 'bg-green-100';
                iconTextColor = 'text-green-600';
                confirmBtnClass = 'bg-green-600 hover:bg-green-700 focus:ring-green-500';
                break;
            case 'error':
                iconHtml = '<i data-lucide="x-circle" class="w-8 h-8"></i>';
                iconBgColor = 'bg-red-100';
                iconTextColor = 'text-red-600';
                confirmBtnClass = 'bg-red-600 hover:bg-red-700 focus:ring-red-500';
                break;
            case 'warning':
            case 'confirm':
                iconHtml = '<i data-lucide="alert-triangle" class="w-8 h-8"></i>';
                iconBgColor = 'bg-amber-100';
                iconTextColor = 'text-amber-600';
                confirmBtnClass = 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500';
                break;
            case 'loading':
                iconHtml = '<i data-lucide="loader-2" class="w-8 h-8 animate-spin"></i>';
                iconBgColor = 'bg-blue-100';
                iconTextColor = 'text-blue-600';
                break;
            default:
                iconHtml = '<i data-lucide="info" class="w-8 h-8"></i>';
                iconBgColor = 'bg-blue-100';
                iconTextColor = 'text-blue-600';
                confirmBtnClass = 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500';
                break;
        }
        const overlay = document.createElement('div');
        overlay.id = 'custom-alert-overlay';
        overlay.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0';
        
        const isConfirm = type === 'confirm';
        const isLoading = type === 'loading';

        overlay.innerHTML = `
            <div id="custom-alert-modal" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform scale-95 opacity-0 transition-all duration-300">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full ${iconBgColor} ${iconTextColor} mb-4">
                    ${iconHtml}
                </div>
                
                <!-- Content -->
                <h3 class="text-xl font-bold text-slate-900 mb-2">${title}</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">${message}</p>
                
                <!-- Actions -->
                ${!isLoading ? `
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-center gap-3 w-full">
                        ${isConfirm ? `<button id="custom-alert-cancel" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-colors">${cancelText}</button>` : ''}
                        <button id="custom-alert-confirm" class="w-full sm:w-auto px-5 py-2.5 text-white text-sm font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors ${confirmBtnClass}">${confirmText}</button>
                    </div>
                ` : ''}
            </div>
        `;

        document.body.appendChild(overlay);
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons({ root: overlay });
        }
        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            const modal = document.getElementById('custom-alert-modal');
            modal.classList.remove('scale-95', 'opacity-0');
            modal.classList.add('scale-100', 'opacity-100');
        });

        const closeAlert = () => {
            overlay.classList.add('opacity-0');
            const modal = document.getElementById('custom-alert-modal');
            modal.classList.remove('scale-100', 'opacity-100');
            modal.classList.add('scale-95', 'opacity-0');
            setTimeout(() => overlay.remove(), 300);
        };

        if (!isLoading) {
            document.getElementById('custom-alert-confirm').addEventListener('click', () => {
                closeAlert();
                if (onConfirm) onConfirm();
            });

            if (isConfirm) {
                document.getElementById('custom-alert-cancel').addEventListener('click', () => {
                    closeAlert();
                    if (onCancel) onCancel();
                });
            }
        }
        return closeAlert;
    }
};

window.customAlert = customAlert;
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.hasAttribute('data-confirm')) {
        e.preventDefault();
        const msg = form.getAttribute('data-confirm');
        customAlert.show({
            title: 'Konfirmasi',
            message: msg,
            type: 'confirm',
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal',
            onConfirm: () => {
            if (form.hasAttribute('data-ajax-delete')) {
                const url = form.getAttribute('action');
                const formData = new FormData(form);
                
                // Show loading state
                const closeLoading = customAlert.show({
                    title: 'Memproses...',
                    message: 'Mohon tunggu sebentar',
                    type: 'loading'
                });

                fetch(url, {
                    method: 'POST', // Laravel DELETE is spoofed with _method
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    closeLoading();
                    if (data.success) {
                        // Success notification
                        customAlert.show({
                            title: 'Berhasil',
                            message: data.message,
                            type: 'success'
                        });
                        
                        // Find the row and remove it with animation
                        const row = form.closest('tr');
                        if (row) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';
                            setTimeout(async () => {
                                row.remove();
                                
                                // Refresh table content to update row numbers, pagination, and totals
                                try {
                                    const refreshResponse = await fetch(window.location.href, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'text/html'
                                        }
                                    });
                                    if (refreshResponse.ok) {
                                        const html = await refreshResponse.text();
                                        const parser = new DOMParser();
                                        const doc = parser.parseFromString(html, 'text/html');

                                        // Update total count in header if exists
                                        const newTotal = doc.querySelector('[data-purpose="page-title-section"] p');
                                        const currentTotal = document.querySelector('[data-purpose="page-title-section"] p');
                                        if (newTotal && currentTotal) {
                                            currentTotal.innerHTML = newTotal.innerHTML;
                                        }

                                        // Update table body
                                        const newTableBody = doc.querySelector('table tbody');
                                        const currentTableBody = document.querySelector('table tbody');
                                        if (newTableBody && currentTableBody) {
                                            currentTableBody.innerHTML = newTableBody.innerHTML;
                                        }

                                        // Update pagination
                                        const newPagination = doc.querySelector('[data-purpose="table-pagination"]');
                                        const currentPagination = document.querySelector('[data-purpose="table-pagination"]');
                                        if (newPagination && currentPagination) {
                                            currentPagination.innerHTML = newPagination.innerHTML;
                                        }

                                        // Re-init lucide icons
                                        if (typeof lucide !== 'undefined' && lucide.createIcons) {
                                            lucide.createIcons();
                                        }
                                    }
                                } catch (refreshError) {
                                    console.error('Error refreshing table content:', refreshError);
                                }
                            }, 300);
                        }
                    } else {
                        customAlert.show({
                            title: 'Gagal',
                            message: data.message || 'Terjadi kesalahan saat menghapus data.',
                            type: 'error'
                        });
                    }
                })
                .catch(error => {
                    closeLoading();
                    customAlert.show({
                        title: 'Error',
                        message: 'Terjadi kesalahan koneksi atau sistem.',
                        type: 'error'
                    });
                    console.error('Delete Error:', error);
                });
            } else {
                form.removeAttribute('data-confirm');
                const btn = form.querySelector('[type="submit"]');
                if (btn) btn.click();
                else form.submit();
            }
        }
        });
    }
});

