/**
 * Items Page - Modal & UI Logic
 * ERP NOC SMKN 4 Malang
 * 
 * Loaded via @vite() on the items index page.
 * Uses window._itemsConfig for Blade-injected values (route URLs, CSRF token).
 */

// ============================================
// GLOBAL MODAL FUNCTIONS
// ============================================

// --- Units Modal ---
window.openUnitsModal = async function(name, location_id, condition, status, prefix) {
    const modal = document.getElementById('unitsModal');
    if (!modal) return;
    
    const subtitle = document.getElementById('unitsModalSubtitle');
    const tbody = document.getElementById('unitsTableBody');
    if (!subtitle || !tbody) return;
    
    subtitle.textContent = `Menampilkan rincian unit unik untuk kelompok ${prefix} (${name})`;
    tbody.innerHTML = `<tr><td colspan="3" class="text-center py-10"><span class="material-symbols-outlined animate-spin text-[32px] text-gray-400 mb-2">progress_activity</span><div class="text-sm text-gray-500 font-medium">Memuat data unit...</div></td></tr>`;
    
    modal.classList.remove('hidden');

    try {
        const config = window._itemsConfig || {};
        const unitsUrl = config.unitsRoute || '/items/units';
        const url = `${unitsUrl}?name=${encodeURIComponent(name)}&location_id=${location_id}&condition=${condition}&status=${status}`;
        const res = await fetch(url);
        const data = await res.json();
        const csrfToken = config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';
        
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-8 text-gray-500 text-sm">Data unit tidak ditemukan.</td></tr>`;
            return;
        }

        data.forEach((unit, index) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition-colors';
            tr.innerHTML = `
                <td class="py-4 px-4 text-sm text-gray-500 text-center font-medium">${index + 1}</td>
                <td class="py-4 px-4">
                    <code class="text-[13px] bg-[#E8EAF6] text-[#3F51B5] px-2.5 py-1.5 rounded-md border border-indigo-100 font-mono font-bold tracking-wider shadow-sm">${unit.code}</code>
                </td>
                <td class="py-4 px-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="openQrModal('${unit.code}', '${unit.name}')" class="p-2 text-indigo-600 hover:text-white bg-indigo-50 hover:bg-indigo-600 rounded-lg transition-all shadow-sm border border-indigo-100 hover:border-transparent flex items-center gap-1.5 font-bold text-xs" title="Generate & Download QR Code">
                            <span class="material-symbols-outlined text-[16px]">qr_code</span> Cetak QR
                        </button>
                        <a href="/items/${unit.id}/edit" class="p-2 text-amber-600 hover:text-white bg-amber-50 hover:bg-amber-500 rounded-lg transition-all shadow-sm border border-amber-100 hover:border-transparent" title="Edit Unit Spesifik">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                        </a>
                        <form action="/items/${unit.id}" method="POST" onsubmit="return confirm('Yakin hapus unit ${unit.code} secara permanen?')" class="inline">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="p-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-500 rounded-lg transition-all shadow-sm border border-red-100 hover:border-transparent" title="Hapus Unit">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

    } catch(e) {
        console.error(e);
        tbody.innerHTML = `<tr><td colspan="3" class="text-center py-8 text-red-500 text-sm font-medium">Gagal mengambil data unit. Silakan coba lagi.</td></tr>`;
    }
};

window.closeUnitsModal = function() {
    const modal = document.getElementById('unitsModal');
    if (modal) modal.classList.add('hidden');
};

// --- QR Code Logic ---
var _currentItemCode = '';
var _currentItemName = '';

window.openQrModal = async function(code, name) {
    _currentItemCode = code;
    _currentItemName = name;
    const modal = document.getElementById('qrCodeModal');
    if (!modal) return;
    
    document.getElementById('qrModalSubtitle').textContent = name;
    document.getElementById('qrCodeText').textContent = code;
    modal.classList.remove('hidden');

    try {
        if (!window.QRCode) {
            await new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
                script.onload = resolve;
                script.onerror = () => reject(new Error('Gagal memuat pustaka QRCode'));
                document.head.appendChild(script);
            });
        }

        const container = document.getElementById('itemQrContainer');
        container.innerHTML = '';
        
        new QRCode(container, {
            text: code,
            width: 200,
            height: 200,
            colorDark: "#111827",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    } catch (error) {
        console.error(error);
        alert("Gagal memuat QR Code. Pastikan Anda terhubung dengan internet.");
    }
};

window.closeQrModal = function() {
    const modal = document.getElementById('qrCodeModal');
    if (modal) modal.classList.add('hidden');
};

window.downloadQrCode = function() {
    const container = document.getElementById('itemQrContainer');
    if (!container) return;
    const canvas = container.querySelector('canvas');
    const img = container.querySelector('img');
    
    let imageUrl = '';
    if (canvas) {
        imageUrl = canvas.toDataURL("image/png");
    } else if (img && img.src) {
        imageUrl = img.src;
    }
    
    if (imageUrl) {
        const link = document.createElement('a');
        link.href = imageUrl;
        const cleanName = _currentItemName.replace(/[^a-z0-9]/gi, '_').toLowerCase();
        link.download = `QR_${_currentItemCode}_${cleanName}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        alert('QR Code belum siap untuk diunduh.');
    }
};

// --- Add Barang Modal ---
window.toggleAddBarangModal = function(show) {
    const modal = document.getElementById('addBarangModal');
    if (modal) {
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
};

// Close modals on Escape key (register only once)
if (!window._itemsEscapeListenerRegistered) {
    window._itemsEscapeListenerRegistered = true;
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (typeof window.toggleAddBarangModal === 'function') window.toggleAddBarangModal(false);
            if (typeof window.closeQrModal === 'function') window.closeQrModal();
            if (typeof window.closeUnitsModal === 'function') window.closeUnitsModal();
        }
    });
}

// --- Format Rupiah Helper ---
window._formatRupiah = function(value) {
    if (!value) return '';
    let clean = value.toString().replace(/\D/g, '');
    return clean.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

// ============================================
// PAGE INITIALIZATION
// ============================================
function initItemsPage() {
    // Guard: prevent duplicate init
    var pjaxContent = document.getElementById('pjax-content');
    if (pjaxContent && pjaxContent.dataset.itemsInitialized === 'true') return;
    if (pjaxContent) pjaxContent.dataset.itemsInitialized = 'true';

    const priceInput = document.getElementById('purchase_price_input');
    const addForm = document.getElementById('addBarangForm');

    // --- Toggle Item Type Logic ---
    const itemTypeRadios = document.querySelectorAll('input[name="item_type"]');
    const existingItemSelector = document.getElementById('existing_item_selector');
    const existingItemSelect = document.getElementById('existing_item_id');
    const inputName = document.querySelector('input[name="name"]');
    const inputBrand = document.querySelector('input[name="brand"]');
    const inputModel = document.querySelector('input[name="model"]');
    const selectCategory = document.querySelector('select[name="category_id"]');

    function toggleItemType() {
        if (!existingItemSelector || !inputName || !inputBrand || !inputModel || !selectCategory) return;
        const type = document.querySelector('input[name="item_type"]:checked');
        if (!type) return;
        if (type.value === 'existing') {
            existingItemSelector.classList.remove('hidden');
            var nameParent = inputName.closest('.space-y-1\\.5') || inputName.closest('.space-y-1');
            if (nameParent) nameParent.classList.add('hidden');
            
            inputBrand.readOnly = true;
            inputModel.readOnly = true;
            inputBrand.classList.add('bg-gray-100', 'cursor-not-allowed');
            inputModel.classList.add('bg-gray-100', 'cursor-not-allowed');
            selectCategory.classList.add('bg-gray-100', 'pointer-events-none');
            
            inputName.value = '';
            inputBrand.value = '';
            inputModel.value = '';
            selectCategory.value = '';
            if (existingItemSelect) existingItemSelect.value = '';
        } else {
            existingItemSelector.classList.add('hidden');
            var nameParent = inputName.closest('.space-y-1\\.5') || inputName.closest('.space-y-1');
            if (nameParent) nameParent.classList.remove('hidden');
            
            inputBrand.readOnly = false;
            inputModel.readOnly = false;
            inputBrand.classList.remove('bg-gray-100', 'cursor-not-allowed');
            inputModel.classList.remove('bg-gray-100', 'cursor-not-allowed');
            selectCategory.classList.remove('bg-gray-100', 'pointer-events-none');
            
            inputName.value = '';
            inputBrand.value = '';
            inputModel.value = '';
            selectCategory.value = '';
        }
    }

    if (itemTypeRadios.length > 0) {
        itemTypeRadios.forEach(radio => radio.addEventListener('change', toggleItemType));
    }

    if (existingItemSelect && inputName && inputBrand && inputModel && selectCategory) {
        existingItemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                inputName.value = selectedOption.value;
                inputBrand.value = selectedOption.dataset.brand || '';
                inputModel.value = selectedOption.dataset.model || '';
                selectCategory.value = selectedOption.dataset.category || '';
            } else {
                inputName.value = '';
                inputBrand.value = '';
                inputModel.value = '';
                selectCategory.value = '';
            }
        });
    }

    // --- Price formatting ---
    if (priceInput) {
        if (priceInput.value) {
            priceInput.value = window._formatRupiah(priceInput.value);
        }
        priceInput.addEventListener('input', function(e) {
            e.target.value = window._formatRupiah(e.target.value);
        });
    }

    if (addForm && priceInput) {
        addForm.addEventListener('submit', function(e) {
            priceInput.value = priceInput.value.replace(/\./g, '');
        });
    }

    // --- Filter Form & Table AJAX ---
    const form = document.getElementById('filterForm');
    const tableContainer = document.getElementById('tableContainer');

    if (form && tableContainer) {
        let debounceTimer;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchFilteredData();
        });

        form.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', fetchFilteredData);
        });

        const searchInput = form.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchFilteredData, 500);
            });
        }

        document.addEventListener('click', function(e) {
            const pageLink = e.target.closest('#tableContainer a[href*="page="]');
            if (pageLink) {
                e.preventDefault();
                fetchFilteredData(pageLink.href);
            }
        });

        function fetchFilteredData(url) {
            if (!url) {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                url = `${form.action}?${params.toString()}`;
            }

            tableContainer.style.opacity = '0.5';
            tableContainer.style.pointerEvents = 'none';
            window.history.pushState({}, '', url);

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableContainer = doc.getElementById('tableContainer');
                if (newTableContainer) {
                    tableContainer.innerHTML = newTableContainer.innerHTML;
                }
            })
            .catch(error => console.error('Error fetching data:', error))
            .finally(() => {
                tableContainer.style.opacity = '1';
                tableContainer.style.pointerEvents = 'auto';
            });
        }
    }
}

// Run initialization - module scripts are deferred, so DOM is ready when this runs
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initItemsPage);
} else {
    // DOM already loaded (module scripts are deferred), init immediately
    initItemsPage();
}
// Run after PJAX navigation (if PJAX is ever used)
document.addEventListener('pjax:complete', initItemsPage);

console.log('[ERP NOC] Items page module loaded');
