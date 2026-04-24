<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NOC SMKN 4 MALANG - Setel Kata Sandi</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
@vite(['resources/css/password_setup.css', 'resources/js/password_setup.js'])
</head>
<body class="font-display bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
<div class="absolute inset-0 dot-pattern opacity-50 pointer-events-none"></div>
<div class="relative w-full max-w-[420px] bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-slate-100 dark:border-slate-700 p-6 transition-all duration-300 flex flex-col justify-center">

<div class="flex flex-col items-center mb-4">
    <div class="w-16 h-16 mb-3 flex items-center justify-center">
        <img alt="SMK Logo" class="w-full h-full object-contain" src="{{ asset('images/logo-grafika.png') }}"/>
    </div>
    <h1 class="text-lg font-bold text-slate-900 dark:text-white text-center uppercase tracking-wider">
        SETEL KATA SANDI ADMIN
    </h1>
    <p class="text-xs text-slate-500 mt-1">Perbarui kata sandi untuk akun administrator</p>
</div>

<form class="space-y-3.5" action="{{ url('setup-password') }}" method="POST">
    @csrf
    <div class="space-y-1">
        <label for="email" class="text-xs font-semibold text-slate-600 dark:text-slate-400 ml-1">Email Admin</label>
        <div class="relative">
            <input class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder:text-slate-400 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" id="email" name="email" placeholder="admin@noc.smkn4malang.sch.id" type="email" value="{{ old('email', 'admin@noc.smkn4malang.sch.id') }}" required/>
        </div>
        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-1">
        <label for="password" class="text-xs font-semibold text-slate-600 dark:text-slate-400 ml-1">Kata Sandi Baru</label>
        <div class="relative group">
            <input class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder:text-slate-400 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none pr-12" id="password" name="password" placeholder="Minimal 8 karakter" type="password" required/>
            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 flex items-center" onclick="togglePassword('password', 'password-icon-1')" type="button">
                <span class="material-icons-round text-[22px]" id="password-icon-1">visibility</span>
            </button>
        </div>
        @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-1">
        <label for="password_confirmation" class="text-xs font-semibold text-slate-600 dark:text-slate-400 ml-1">Konfirmasi Kata Sandi</label>
        <div class="relative group">
            <input class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder:text-slate-400 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none pr-12" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" type="password" required/>
            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 flex items-center" onclick="togglePassword('password_confirmation', 'password-icon-2')" type="button">
                <span class="material-icons-round text-[22px]" id="password-icon-2">visibility</span>
            </button>
        </div>
    </div>

    <button class="w-full bg-primary hover:bg-opacity-90 text-white font-semibold py-3 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] focus:ring-4 focus:ring-primary/30 mt-2" type="submit">
        Simpan Kata Sandi
    </button>
</form>

<div class="mt-6 text-center">
    <a href="{{ route('login') }}" class="text-xs text-primary font-semibold hover:underline">
        <i class="material-icons-round text-xs align-middle">arrow_back</i> Kembali ke Login
    </a>
</div>

</div>



</body></html>
