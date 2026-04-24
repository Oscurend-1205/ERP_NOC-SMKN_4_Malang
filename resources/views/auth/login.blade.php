<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NOC SMKN 4 MALANG - Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>

@vite(['resources/css/login.css'])
</head>
<body class="font-display bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
<div class="absolute inset-0 dot-pattern opacity-50 pointer-events-none"></div>
<div class="relative w-full max-w-[400px] login-card p-6 transition-all duration-300 flex flex-col justify-center">
@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-600 rounded-lg text-xs">
        {{ session('success') }}
    </div>
@endif
@if($errors->any() && ! $errors->has('email') && ! $errors->has('password'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs">
        {{ $errors->first() }}
    </div>
@endif
<div class="flex flex-col items-center mb-6">
<div class="w-24 h-24 mb-4 flex items-center justify-center">
<img alt="SMK Logo" class="w-full h-full object-contain" src="{{ asset('images/logo-grafika.png') }}"/>
</div>
<h1 class="text-[17px] font-extrabold text-slate-900 dark:text-white text-center uppercase tracking-widest leading-tight">
                Inventory System<br>SMKN 4 Malang
            </h1>
</div>
<form class="space-y-3.5" action="{{ route('login') }}" method="POST">
    @csrf
    <div class="space-y-1">
        <div class="relative">
            <input class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder:text-slate-400 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" id="email" name="email" placeholder="Alamat Email" type="email" value="{{ old('email') }}" required autofocus/>
        </div>
        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <div class="relative group">
            <input class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder:text-slate-400 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none pr-12" id="password" name="password" placeholder="Kata Sandi" type="password" required/>
            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 flex items-center" onclick="togglePassword()" type="button">
                <span class="material-icons-round text-[22px]" id="password-icon">visibility</span>
            </button>
        </div>
        @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-slate-600 dark:text-slate-400">
                Ingat Saya
            </label>
        </div>
    </div>
    <button class="w-full bg-primary hover:bg-opacity-90 text-white font-semibold py-3 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] focus:ring-4 focus:ring-primary/30" type="submit">
        Masuk
    </button>
</form>
</div>
<div class="fixed bottom-6 right-6">
<button class="p-3 rounded-full bg-white dark:bg-slate-800 shadow-lg text-slate-600 dark:text-slate-300 hover:text-primary transition-colors" onclick="document.documentElement.classList.toggle('dark')">
<span class="material-icons-round">dark_mode</span>
</button>
</div>
@vite(['resources/js/login.js'])
</body></html>