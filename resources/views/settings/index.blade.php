@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<!-- BEGIN: Page Title -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-slate-900">Pengaturan Sistem</h1>
    <p class="text-sm text-slate-500 mt-1">Konfigurasi tema dan pemulihan data sistem.</p>
</div>
<!-- END: Page Title -->

@if (session('error'))
<div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
    {{ session('error') }}
</div>
@endif

@if (session('success'))
<div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- BEGIN: Theme Settings -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                <i data-lucide="palette" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Tampilan Tema</h2>
                <p class="text-xs text-slate-500">Sesuaikan mode terang atau gelap sesuai kenyamanan Anda.</p>
            </div>
        </div>
        
        <div class="mt-4 flex-grow">
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="setTheme('light')" class="border-2 border-blue-500 bg-white rounded-xl p-4 flex flex-col items-center justify-center hover:bg-slate-50 transition-all theme-btn" id="btn-theme-light">
                    <i data-lucide="sun" class="w-8 h-8 text-amber-500 mb-2"></i>
                    <span class="text-sm font-medium text-slate-900">Terang (Light)</span>
                </button>
                <button type="button" onclick="setTheme('dark')" class="border-2 border-slate-200 bg-slate-800 rounded-xl p-4 flex flex-col items-center justify-center hover:bg-slate-700 transition-all theme-btn" id="btn-theme-dark">
                    <i data-lucide="moon" class="w-8 h-8 text-blue-300 mb-2"></i>
                    <span class="text-sm font-medium text-white">Gelap (Dark)</span>
                </button>
            </div>
        </div>
    </div>
    <!-- END: Theme Settings -->

    <!-- BEGIN: Reset System -->
    <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-6 flex flex-col relative overflow-hidden">
        <!-- Warning Background -->
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i data-lucide="alert-triangle" class="w-32 h-32 text-red-500"></i>
        </div>

        <div class="flex items-center mb-4 relative z-10">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 mr-4">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Reset Website</h2>
                <p class="text-xs text-slate-500">Kembalikan website seperti awal beserta seluruh datanya.</p>
            </div>
        </div>
        
        <div class="mt-4 flex-grow relative z-10 flex flex-col justify-center">
            <div class="bg-red-50 border border-red-100 rounded-lg p-4 mb-4">
                <p class="text-xs text-red-700 font-medium">
                    <strong class="font-bold">PERINGATAN KERAS!</strong><br>
                    Tindakan ini akan menghapus <b>SEMUA</b> data (Barang, Peminjaman, Kategori, User, dll) dan meresetnya kembali ke kondisi bawaan awal (Default/Dummy Data). Aksi ini <b>TIDAK DAPAT</b> dibatalkan!
                </p>
            </div>

            <form action="{{ route('settings.reset') }}" method="POST" onsubmit="return confirmReset(event)">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-xl shadow-sm shadow-red-200 flex items-center justify-center transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Reset Sistem Sekarang
                </button>
            </form>
        </div>
    </div>
    <!-- END: Reset System -->
</div>

<!-- Scripts moved inside content for PJAX compatibility -->
<script>
    // Initialize immediately for PJAX compatibility
    initTheme();

    // Theme Logic (Contoh penggunaan localStorage untuk mengatur tema)
    function initTheme() {
        const currentTheme = localStorage.getItem('theme') || 'light';
        updateThemeUI(currentTheme);
        applyThemeToBody(currentTheme);
    }

    function setTheme(theme) {
        localStorage.setItem('theme', theme);
        updateThemeUI(theme);
        applyThemeToBody(theme);
    }

    function updateThemeUI(theme) {
        const btnLight = document.getElementById('btn-theme-light');
        const btnDark = document.getElementById('btn-theme-dark');

        if (theme === 'dark') {
            btnDark.classList.remove('border-slate-200');
            btnDark.classList.add('border-blue-500');
            btnLight.classList.remove('border-blue-500');
            btnLight.classList.add('border-slate-200');
        } else {
            btnLight.classList.remove('border-slate-200');
            btnLight.classList.add('border-blue-500');
            btnDark.classList.remove('border-blue-500');
            btnDark.classList.add('border-slate-200');
        }
    }

    function applyThemeToBody(theme) {
        // Implementasi ini bergantung pada seberapa jauh proyek Anda mendukung dark mode Tailwind
        // Biasanya dengan menambahkan class 'dark' ke elemen <html>
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    // Konfirmasi Reset
    function confirmReset(e) {
        e.preventDefault();
        
        // Custom prompt/confirm untuk keamanan ekstra
        const confirmText = prompt("Ketik 'RESET' untuk mengonfirmasi penghapusan seluruh data sistem secara permanen:");
        
        if (confirmText === 'RESET') {
            e.target.submit();
        } else if (confirmText !== null) {
            alert('Konfirmasi dibatalkan atau kata yang diketik salah.');
        }
    }
</script>
@endsection
