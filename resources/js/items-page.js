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
window.openUnitsModal = async function(name, brand, model, category_id, sub_prefix) {
    const modal = document.getElementById('unitsModal');
    if (!modal) return;
    
    const subtitle = document.getElementById('unitsModalSubtitle');
    const tbody = document.getElementById('unitsTableBody');
    if (!subtitle || !tbody) return;
    
    subtitle.textContent = `Menampilkan rincian unit untuk ${name} (${brand} ${model})`;
    tbody.innerHTML = `<tr><td colspan="8" class="text-center py-10"><span class="material-symbols-outlined animate-spin text-[32px] text-gray-400 mb-2">progress_activity</span><div class="text-sm text-gray-500 font-medium">Memuat data unit...</div></td></tr>`;
    
    modal.classList.remove('hidden');

    try {
        const config = window._itemsConfig || {};
        const unitsUrl = config.unitsRoute || '/items/units';
        const url = `${unitsUrl}?name=${encodeURIComponent(name)}&brand=${encodeURIComponent(brand)}&model=${encodeURIComponent(model)}&category_id=${encodeURIComponent(category_id)}&sub_prefix=${encodeURIComponent(sub_prefix || '')}`;
        const res = await fetch(url);
        const data = await res.json();
        const csrfToken = config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';
        
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-8 text-gray-500 text-sm">Data unit tidak ditemukan.</td></tr>`;
            return;
        }

        data.forEach((unit, index) => {
            // Condition badge
            const condLabels = { baik: 'Baik', rusak_ringan: 'Rusak Ringan', rusak_berat: 'Rusak Berat', hilang: 'Hilang' };
            const condColors = { baik: 'bg-green-50 text-green-700 border-green-200', rusak_ringan: 'bg-amber-50 text-amber-700 border-amber-200', rusak_berat: 'bg-red-50 text-red-700 border-red-200', hilang: 'bg-gray-50 text-gray-600 border-gray-200' };
            const condLabel = condLabels[unit.condition] || unit.condition || '-';
            const condClass = condColors[unit.condition] || 'bg-gray-50 text-gray-600 border-gray-200';

            // Status badge
            const statusLabels = { tersedia: 'Tersedia', dipinjam: 'Dipinjam', maintenance: 'Maintenance', dimusnahkan: 'Dimusnahkan' };
            const statusColors = { tersedia: 'bg-green-50 text-green-700 border-green-200', dipinjam: 'bg-blue-50 text-blue-700 border-blue-200', maintenance: 'bg-orange-50 text-orange-700 border-orange-200', dimusnahkan: 'bg-red-50 text-red-700 border-red-200' };
            const statusLabel = statusLabels[unit.status] || unit.status || '-';
            const statusClass = statusColors[unit.status] || 'bg-gray-50 text-gray-600 border-gray-200';

            // Purchase date
            const purchaseDate = unit.purchase_date ? new Date(unit.purchase_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

            // Location
            const locationName = (unit.location && unit.location.name) ? unit.location.name : '-';

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-blue-50/40 transition-colors';
            tr.innerHTML = `
                <td class="py-2.5 px-3 text-xs text-gray-400 text-center font-semibold">${index + 1}</td>
                <td class="py-2.5 px-3">
                    <div class="flex items-center gap-1.5">
                        <code class="text-[11px] bg-indigo-50 text-indigo-700 px-2 py-1 rounded border border-indigo-100 font-mono font-bold tracking-wide">${unit.code}</code>
                        ${unit.sub_prefix ? `<span class="text-[9px] font-bold text-indigo-500 bg-indigo-50/70 px-1.5 py-0.5 rounded border border-indigo-100">${unit.sub_prefix}</span>` : ''}
                    </div>
                </td>
                <td class="py-2.5 px-3 text-[11px] text-gray-500 font-mono tracking-wide">${unit.serial_number || '<span class="text-gray-300">—</span>'}</td>
                <td class="py-2.5 px-3 text-center"><span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold border ${condClass}">${condLabel}</span></td>
                <td class="py-2.5 px-3 text-center"><span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold border ${statusClass}">${statusLabel}</span></td>
                <td class="py-2.5 px-3 text-center text-[11px] text-gray-600 font-medium">${locationName}</td>
                <td class="py-2.5 px-3 text-[11px] text-gray-500 text-center">${purchaseDate}</td>
                <td class="py-2.5 px-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <button type="button" onclick="openQrModal('${unit.code}', '${unit.name}')" class="w-7 h-7 flex items-center justify-center text-indigo-500 hover:text-white bg-indigo-50 hover:bg-indigo-600 rounded-md transition-all border border-indigo-100 hover:border-indigo-600" title="QR Code">
                            <span class="material-symbols-outlined text-[14px]">qr_code_2</span>
                        </button>
                        <a href="/items/${unit.id}/edit" class="w-7 h-7 flex items-center justify-center text-amber-500 hover:text-white bg-amber-50 hover:bg-amber-500 rounded-md transition-all border border-amber-100 hover:border-amber-500" title="Edit">
                            <span class="material-symbols-outlined text-[14px]">edit</span>
                        </a>
                        <form action="/items/${unit.id}" method="POST" onsubmit="return confirm('Yakin hapus unit ${unit.code}?')" class="inline">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="w-7 h-7 flex items-center justify-center text-red-400 hover:text-white bg-red-50 hover:bg-red-500 rounded-md transition-all border border-red-100 hover:border-red-500" title="Hapus">
                                <span class="material-symbols-outlined text-[14px]">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

    } catch(e) {
        console.error(e);
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-8 text-red-500 text-sm font-medium">Gagal mengambil data unit. Silakan coba lagi.</td></tr>`;
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

// --- Quick Category Modal ---
window.openQuickCategoryModal = function() {
    document.getElementById('qc_name').value = '';
    document.getElementById('qc_prefix').value = '';
    document.getElementById('qc_error').classList.add('hidden');
    document.getElementById('quickCategoryModal').classList.remove('hidden');
    document.getElementById('qc_name').focus();
};

window.closeQuickCategoryModal = function() {
    document.getElementById('quickCategoryModal').classList.add('hidden');
};

// --- Code Preview Logic ---
function updateCodePreview() {
    const select = document.getElementById('addBarangCategoryId');
    const subPrefixInput = document.getElementById('addBarangSubPrefix');
    const previewText = document.getElementById('codePreviewText');
    const quantityInput = document.querySelector('input[name="quantity"]');
    if (!select || !previewText) return;

    const categoryId = select.value;
    if (!categoryId) {
        previewText.textContent = 'Pilih kategori terlebih dahulu';
        previewText.className = 'font-mono font-bold text-gray-400 tracking-wider';
        return;
    }

    const config = window._itemsConfig || {};
    const categories = config.categoriesData || [];
    const cat = categories.find(c => c.id == categoryId);

    if (!cat || !cat.prefix) {
        previewText.textContent = 'Kategori tidak memiliki prefix';
        previewText.className = 'font-mono font-bold text-red-400 tracking-wider';
        return;
    }

    const nextNumber = (cat.last_code_number || 0) + 1;
    const subPrefix = subPrefixInput ? subPrefixInput.value.trim().toUpperCase() : '';
    const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;

    let code = cat.prefix;
    if (subPrefix) {
        code += '-' + subPrefix;
    }
    code += '-' + String(nextNumber).padStart(4, '0');

    if (quantity > 1) {
        let lastCode = cat.prefix;
        if (subPrefix) {
            lastCode += '-' + subPrefix;
        }
        lastCode += '-' + String(nextNumber + quantity - 1).padStart(4, '0');
        previewText.textContent = code + '  ...  ' + lastCode;
    } else {
        previewText.textContent = code;
    }
    previewText.className = 'font-mono font-bold text-indigo-600 tracking-wider';
}

// Close modals on Escape key (register only once)
if (!window._itemsEscapeListenerRegistered) {
    window._itemsEscapeListenerRegistered = true;
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (typeof window.toggleAddBarangModal === 'function') window.toggleAddBarangModal(false);
            if (typeof window.closeQrModal === 'function') window.closeQrModal();
            if (typeof window.closeUnitsModal === 'function') window.closeUnitsModal();
            if (typeof window.closeQuickCategoryModal === 'function') window.closeQuickCategoryModal();
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
    const inputSubPrefix = document.getElementById('addBarangSubPrefix');

    // --- Auto-derive sub_prefix from brand ---
    function deriveSubPrefix(brand) {
        if (!brand || !brand.trim()) return '';
        const clean = brand.trim();
        // If brand has multiple words, take first letter of each (up to 3)
        const words = clean.split(/[\s-]+/).filter(w => w.length > 0);
        if (words.length >= 2) {
            return words.slice(0, 3).map(w => w[0].toUpperCase()).join('');
        }
        // Single word: first 3 chars uppercase
        return clean.substring(0, 3).toUpperCase();
    }

    const brandInput = document.querySelector('input[name="brand"]');
    if (brandInput && inputSubPrefix) {
        brandInput.addEventListener('input', function() {
            // Only auto-derive in "new" mode
            const itemType = document.querySelector('input[name="item_type"]:checked');
            if (!itemType || itemType.value === 'new') {
                inputSubPrefix.value = deriveSubPrefix(brandInput.value);
                updateCodePreview();
            }
        });
    }

    // --- Code preview on category change ---
    const categorySelect = document.getElementById('addBarangCategoryId');
    if (categorySelect) {
        categorySelect.addEventListener('change', updateCodePreview);
        // Trigger on init if already selected
        if (categorySelect.value) updateCodePreview();
    }

    // --- Update code preview when quantity changes ---
    const quantityInput = document.querySelector('input[name="quantity"]');
    if (quantityInput) {
        quantityInput.addEventListener('input', updateCodePreview);
    }

    // --- Quick Category Form Submit ---
    const qcForm = document.getElementById('quickCategoryForm');
    if (qcForm) {
        qcForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const config = window._itemsConfig || {};
            const name = document.getElementById('qc_name').value.trim();
            const prefix = document.getElementById('qc_prefix').value.trim().toUpperCase();
            const errDiv = document.getElementById('qc_error');
            const submitBtn = document.getElementById('qc_submit');

            errDiv.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span> Menyimpan...';

            try {
                const res = await fetch(config.quickCategoryRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name, prefix }),
                });

                const data = await res.json();

                if (!res.ok) {
                    const errMsg = data.errors
                        ? Object.values(data.errors).flat().join(', ')
                        : (data.message || 'Gagal menyimpan kategori');
                    throw new Error(errMsg);
                }

                // Add new option to category dropdown
                const select = document.getElementById('addBarangCategoryId');
                if (select) {
                    const opt = new Option(data.category.name, data.category.id, true, true);
                    opt.dataset.prefix = data.category.prefix;
                    select.add(opt);
                }

                // Update categoriesData cache
                if (config.categoriesData) {
                    config.categoriesData.push({
                        id: data.category.id,
                        name: data.category.name,
                        prefix: data.category.prefix,
                        last_code_number: 0,
                    });
                }

                // Update preview
                updateCodePreview();

                // Close modal
                window.closeQuickCategoryModal();

            } catch (err) {
                errDiv.textContent = err.message;
                errDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">save</span> Simpan Kategori';
            }
        });
    }

    // --- Toggle Item Type Logic ---
    const itemTypeRadios = document.querySelectorAll('input[name="item_type"]');
    const existingItemSelector = document.getElementById('existing_item_selector');
    const existingItemSelect = document.getElementById('existing_item_id');
    const inputName = document.querySelector('input[name="name"]');
    const inputBrand = document.querySelector('input[name="brand"]');
    const inputModel = document.querySelector('input[name="model"]');
    const selectCategory = document.querySelector('select[name="category_id"]');
    // inputSubPrefix already declared at top of initItemsPage()

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
            if (inputSubPrefix) inputSubPrefix.value = '';
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
            if (inputSubPrefix) inputSubPrefix.value = '';
        }
        updateCodePreview();
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
                if (inputSubPrefix) {
                    inputSubPrefix.value = selectedOption.dataset.subPrefix || '';
                }
                selectCategory.dispatchEvent(new Event('change'));
            } else {
                inputName.value = '';
                inputBrand.value = '';
                inputModel.value = '';
                selectCategory.value = '';
                if (inputSubPrefix) inputSubPrefix.value = '';
            }
            updateCodePreview();
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
