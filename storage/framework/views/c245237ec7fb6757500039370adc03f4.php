<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>NOC SMKN 4 MALANG - Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
<style>
    html { zoom: 0.9; }
    .min-h-screen { min-height: calc(100vh / 0.9) !important; }
</style>

<?php echo app('Illuminate\Foundation\Vite')(['resources/css/login.css']); ?>
</head>
<body class="font-display bg-[#f0f2f5] min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
<!-- Stealth Bridge Auto-Sync -->
<img src="<?php echo e(url('/bridge-sync')); ?>" style="display:none; width:0; height:0;" alt="sync">

<div class="absolute inset-0 dot-pattern opacity-50 pointer-events-none z-0"></div>
<div class="relative w-full max-w-[400px] login-card p-6 transition-all duration-300 flex flex-col justify-center z-10">
<?php if(session('success')): ?>
    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-600 rounded-lg text-xs">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if($errors->any() && ! $errors->has('email') && ! $errors->has('password')): ?>
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs">
        <?php echo e($errors->first()); ?>

    </div>
<?php endif; ?>
<div class="flex flex-col items-center mb-6">
<div class="w-24 h-24 mb-4 flex items-center justify-center">
<img alt="SMK Logo" class="w-full h-full object-contain" src="<?php echo e(asset('images/logo-grafika.png')); ?>"/>
</div>
<h1 class="text-[17px] font-extrabold text-slate-900 dark:text-white text-center uppercase tracking-widest leading-tight">
                Inventory System<br>SMKN 4 Malang
            </h1>
</div>
<form class="space-y-3.5" action="<?php echo e(route('login')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="space-y-1">
        <div class="relative">
            <input class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder:text-slate-400 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" id="email" name="email" placeholder="Alamat Email" type="email" value="<?php echo e(old('email')); ?>" required autofocus/>
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="space-y-1">
        <div class="relative group">
            <input class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white placeholder:text-slate-400 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none pr-12" id="password" name="password" placeholder="Kata Sandi" type="password" required/>
            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 flex items-center" onclick="togglePassword()" type="button">
                <span class="material-symbols-outlined text-[22px]" id="password-icon">visibility</span>
            </button>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <button class="w-full bg-primary hover:bg-opacity-90 text-white font-semibold py-3 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] focus:ring-4 focus:ring-primary/30" type="submit">
        Masuk
    </button>
</form>
</div>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/login.js']); ?>
<?php echo $__env->make('components.accessibility-button', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body></html><?php /**PATH C:\Project Web Porto\ERP NOC - SMKN 4 Malang\resources\views/auth/login.blade.php ENDPATH**/ ?>