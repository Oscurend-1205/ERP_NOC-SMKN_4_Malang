<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Laporan Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-[40px]">check_circle</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Laporan Terkirim!</h1>
        <p class="text-sm text-gray-500 mb-6">Terima kasih. Laporan perbaikan Anda telah berhasil disubmit dan akan segera diverifikasi oleh tim kami.</p>
        
        <button onclick="window.close()" class="w-full py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Tutup Halaman</button>
    </div>
    
    <p class="mt-6 text-xs text-gray-400 font-medium text-center">&copy; {{ date('Y') }} ERP NOC SMKN 4 Malang.</p>
</body>
</html>
