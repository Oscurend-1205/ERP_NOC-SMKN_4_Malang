# Panduan Desain dan Standar Implementasi ERP NOC - SMKN 4 Malang

Dokumen ini berfungsi sebagai acuan standar untuk menjaga konsistensi desain antarmuka (UI/UX) dan spesifikasi konten pada setiap fitur yang ada di dalam proyek web ERP NOC - SMKN 4 Malang. Semua pengembang **wajib** mengikuti aturan yang tertulis di sini ketika menambah atau memodifikasi fitur.

---

## 1. Tipografi dan Warna (Color Palette)

### Tipografi
*   **Font Utama:** `Inter`, sans-serif.
*   Digunakan di seluruh elemen UI untuk memberikan kesan profesional dan modern.

### Palet Warna (Tailwind CSS)
*   **Warna Primer (Aksi & Tombol Utama):** Blue (`bg-blue-600` untuk default, `bg-blue-700` untuk hover).
*   **Warna Teks Utama:** Slate (`text-slate-900` untuk judul/heading utama, `text-slate-600` untuk teks konten reguler, `text-slate-500` untuk label/sub-teks).
*   **Warna Background Aplikasi:** `bg-slate-50` atau `#F8FAFC`.
*   **Warna Tabel & Card:** `bg-white` dengan border `border-slate-200` dan bayangan `shadow-sm`.
*   **Warna Status:** 
    *   Sukses/Aktif: `bg-green-50 text-green-700 border-green-100` (dengan dot `bg-green-500`).
    *   Gagal/Non-Aktif: `bg-red-50 text-red-600 border-red-100` (dengan dot `bg-red-500`).
*   **Aksi Hapus/Destructive:** `text-red-400 hover:text-red-600`.

---

## 2. Struktur Tabel (Data Master & Lainnya)

Semua tabel yang menampilkan daftar data (seperti Data User, Data Ruangan, Data Jurusan) harus mematuhi struktur DOM dan *class* Tailwind berikut:

### Pembungkus Tabel (Container)
Gunakan `section` dengan *rounded corners* dan efek *shadow*:
```html
<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Toolbar Tabel -->
    <!-- Wrapper Tabel -->
</section>
```

### Toolbar Tabel (Pencarian, Filter, Export)
*   **Padding & Border:** `p-4 border-b border-slate-100 flex items-center justify-between`
*   **Input Pencarian:** Dibungkus dalam div `relative w-72`. Ikon pencarian (*Lucide search*) berada di kiri (absolute). Input menggunakan fokus `focus:ring-blue-500`.
*   **Tombol Aksi Tambahan (Filter/Export):** `px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50`.

### Tabel dan Header (Thead)
Untuk memastikan tabel responsif dan tidak berantakan di layar kecil:
*   **Tag Table:** `<table class="w-full text-left border-collapse whitespace-nowrap">` (Gunakan `whitespace-nowrap` agar konten tidak terpotong ke bawah secara paksa).
*   **Thead:** `<thead class="bg-slate-50/50">`
*   **Th (Kolom):** `px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider`

### Baris Data (Tbody & Tr)
*   **Tbody:** `<tbody class="divide-y divide-slate-100">`
*   **Tr (Hover Effect ZEBRA):** 
    *   Baris Ganjil: `<tr class="table-row-hover transition-colors">`
    *   Baris Genap: `<tr class="bg-slate-50/30 table-row-hover transition-colors">`
*   **Td (Sel Data):** `px-6 py-4 text-sm text-slate-600` (Untuk teks yang ditekankan gunakan `text-slate-900 font-medium`).

### Ikon Aksi (Edit/Hapus)
Gunakan *Lucide Icons* ukuran `w-4 h-4`:
```html
<div class="flex justify-center space-x-3">
    <button class="text-slate-500 hover:text-slate-700"><i data-lucide="pencil" class="w-4 h-4"></i></button>
    <button class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
</div>
```

---

## 3. Ikonografi
*   Proyek ini menggunakan **Lucide Icons** (`https://unpkg.com/lucide@latest`).
*   **Inisialisasi:** Pastikan memanggil script inisialisasi di akhir halaman menggunakan `@push('scripts')`:
    ```html
    @push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
      });
    </script>
    @endpush
    ```

---

## 4. Spesifikasi Konten dan Tata Letak (Layout)

### Header Halaman
Header di atas tabel memuat Judul, Sub-judul, dan Tombol Aksi Utama (seperti "Tambah Data"):
```html
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Judul Halaman</h1>
        <p class="text-sm text-slate-500 mt-1">Deskripsi singkat halaman.</p>
    </div>
    <button class="bg-[#3B82F6] hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition-all">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Data
    </button>
</div>
```

### Role-Based Access Control (RBAC) pada Tampilan
*   **Superadmin:** Aksi destruktif (Hapus), Edit data krusial, dan penambahan Master Data (Tambah User, Ruangan, dll) **wajib** dibungkus dengan pengecekan _role_:
    ```blade
    @if(auth()->user()->role === 'Superadmin')
        <!-- Tombol Tambah / Kolom Aksi -->
    @endif
    ```

### Sidebar & Navigasi
*   Ketika menambahkan rute baru di `web.php`, pastikan menu terkait di `sidebar.blade.php` memiliki kondisi `active` yang tepat.
*   Gunakan fungsi bawaan Laravel `request()->routeIs('nama_rute.*')` untuk memberikan kelas _highlight_ pada menu sidebar.

---

**Catatan Akhir:** Jika Anda perlu mengimplementasikan halaman baru, _copy-paste_ struktur dari `resources/views/data-master/dataUser.blade.php` sebagai kerangka awal (boilerplate) agar konsistensi desain secara otomatis terpenuhi.
