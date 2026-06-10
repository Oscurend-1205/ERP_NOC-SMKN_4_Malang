
window.openEditKategoriModal = function(id, name, description) {
    document.getElementById('editKategoriForm').action = /kategori-barang/;
    document.getElementById('edit_kategori_name').value = name;
    document.getElementById('edit_kategori_description').value = description;
    document.getElementById('editKategoriModal').classList.remove('hidden');
};

window.openEditKondisiModal = function(id, name, labelColor, description) {
    document.getElementById('editKondisiForm').action = /kondisi-barang/;
    document.getElementById('edit_kondisi_name').value = name;
    document.getElementById('edit_kondisi_label_color').value = labelColor;
    document.getElementById('edit_kondisi_description').value = description;
    document.getElementById('editKondisiModal').classList.remove('hidden');
};

window.openEditAsalModal = function(id, name, description) {
    document.getElementById('editAsalForm').action = /asal-barang/;
    document.getElementById('edit_asal_name').value = name;
    document.getElementById('edit_asal_description').value = description;
    document.getElementById('editAsalModal').classList.remove('hidden');
};

window.openEditSupplierModal = function(id, name, pic, phone, email, address) {
    document.getElementById('editSupplierForm').action = /data-supplier/;
    document.getElementById('edit_supplier_name').value = name;
    document.getElementById('edit_supplier_pic').value = pic;
    document.getElementById('edit_supplier_phone').value = phone;
    document.getElementById('edit_supplier_email').value = email;
    document.getElementById('edit_supplier_address').value = address;
    document.getElementById('editSupplierModal').classList.remove('hidden');
};

window.openEditJurusanModal = function(id, name, description, isActive) {
    document.getElementById('editJurusanForm').action = /data-jurusan/;
    document.getElementById('edit_jurusan_name').value = name;
    document.getElementById('edit_jurusan_description').value = description;
    document.getElementById('edit_jurusan_is_active').value = isActive;
    document.getElementById('editJurusanModal').classList.remove('hidden');
};

window.openEditRuanganModal = function(id, code, name, penanggung_jawab, description) {
    document.getElementById('editRuanganForm').action = /locations/;
    document.getElementById('edit_code').value = code;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_penanggung_jawab').value = penanggung_jawab;
    document.getElementById('edit_description').value = description;
    document.getElementById('editRuanganModal').classList.remove('hidden');
};

window.openEditUserModal = function(id, name, username, email, role, isActive) {
    document.getElementById('editUserForm').action = /data-pengguna/;
    document.getElementById('edit_user_name').value = name;
    if(document.getElementById('edit_user_username')) document.getElementById('edit_user_username').value = username;
    if(document.getElementById('edit_user_email')) document.getElementById('edit_user_email').value = email;
    if(document.getElementById('edit_user_role')) document.getElementById('edit_user_role').value = role;
    if (document.getElementById('edit_user_is_active')) {
        document.getElementById('edit_user_is_active').checked = isActive === 1;
    }
    document.getElementById('editUserModal').classList.remove('hidden');
};

