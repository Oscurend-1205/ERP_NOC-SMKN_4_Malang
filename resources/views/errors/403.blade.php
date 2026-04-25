<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-[Inter] min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <!-- Icon -->
        <div class="w-24 h-24 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
            <span class="material-symbols-outlined text-red-500" style="font-size: 48px;">lock</span>
        </div>

        <!-- Title -->
        <h1 class="text-6xl font-black text-gray-900 mb-2">403</h1>
        <h2 class="text-xl font-bold text-gray-700 mb-3">Akses Ditolak</h2>

        <!-- Description -->
        <p class="text-gray-500 text-sm mb-8 leading-relaxed">
            Anda tidak memiliki izin untuk mengakses halaman ini.
            Silakan hubungi Superadmin jika Anda memerlukan akses.
        </p>

        <!-- Actions -->
        <div class="flex items-center justify-center gap-3">
            <a href="javascript:history.back()" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-300 transition-colors">
                <span class="inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Kembali
                </span>
            </a>
            @php
                $homeUrl = url('/');
                if (Auth::check() && Auth::user()->role !== 'Superadmin') {
                    $homeUrl = route('items.index');
                }
            @endphp
            <a href="{{ $homeUrl }}" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition-colors shadow-sm">
                <span class="inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">home</span>
                    Beranda
                </span>
            </a>
        </div>

        <!-- Role Info -->
        @auth
        <div class="mt-8 p-3 bg-amber-50 border border-amber-200 rounded-lg">
            <p class="text-xs text-amber-700">
                <span class="font-bold">Role Anda:</span> {{ Auth::user()->role }}
                — Anda mencoba mengakses fitur yang berada di luar hak akses role Anda.
            </p>
        </div>
        @endauth
    </div>
</body>
</html>
