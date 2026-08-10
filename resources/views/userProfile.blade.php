@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<!-- BEGIN: Page Content -->
<div data-purpose="account-settings">
    <!-- BEGIN: Page Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Pengaturan Akun</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola profil, keamanan, dan preferensi akun Anda.</p>
        </div>
    </div>
    <!-- END: Page Header -->

    <!-- BEGIN: Informasi Profil Card -->
    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6" data-purpose="profile-section">
        <div class="p-4 border-b border-slate-100 flex items-center">
            <h3 class="text-lg font-bold text-slate-900 flex items-center">
                <i data-lucide="user" class="w-5 h-5 mr-2 text-blue-600"></i> Informasi Profil
            </h3>
        </div>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Profile Picture Section -->
                    <div class="flex flex-col items-center space-y-4 md:w-1/4">
                        <div class="w-32 h-32 rounded-full flex items-center justify-center bg-blue-600 text-white shadow-sm relative overflow-hidden group">
                            @if(auth()->user()->avatar)
                                <img id="avatar-preview" src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="Profile Picture" />
                            @else
                                <span id="avatar-initial" class="text-4xl font-bold uppercase tracking-wider">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                                <img id="avatar-preview" src="#" class="w-full h-full object-cover hidden" alt="Profile Picture" />
                            @endif
                            <!-- Overlay for hover -->
                            <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                                <i data-lucide="camera" class="w-8 h-8 text-white"></i>
                            </div>
                        </div>
                        <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/jpeg,image/png,image/gif" onchange="previewAvatar(event)">
                        <button type="button" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors" onclick="document.getElementById('avatar-input').click()">Ubah Foto Profil</button>
                        <p class="text-[10px] text-slate-500 text-center">Format: JPG, PNG. Maksimal 2MB.</p>
                        @error('avatar')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Fields -->
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                            <input name="name" class="w-full px-4 py-2 border @error('name') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm text-slate-900" type="text" value="{{ old('name', auth()->user()->name ?? '') }}"/>
                            @error('name')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Username / NISN</label>
                            <input class="w-full px-4 py-2 border border-slate-200 bg-slate-50 text-slate-500 rounded-lg cursor-not-allowed text-sm" disabled type="text" value="{{ auth()->user()->username ?? '' }}"/>
                            <p class="text-[10px] text-slate-500">Username tidak dapat diubah.</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-semibold text-slate-700">Jabatan / Role</label>
                            <input class="w-full px-4 py-2 border border-slate-200 bg-slate-50 text-slate-500 rounded-lg cursor-not-allowed text-sm" disabled type="text" value="{{ auth()->user()->role ?? '' }}"/>
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700">Kelas / Jurusan / Email</label>
                            <input name="email" class="w-full px-4 py-2 border @error('email') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm text-slate-900" type="text" value="{{ old('email', auth()->user()->email ?? '') }}"/>
                            @error('email')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer / Actions -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm flex items-center shadow-sm transition-all">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan Profil
                </button>
            </div>
        </form>
    </section>
    <!-- END: Informasi Profil Card -->

    <!-- BEGIN: Keamanan Card -->
    <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6" data-purpose="security-section">
        <div class="p-4 border-b border-slate-100 flex items-center">
            <h3 class="text-lg font-bold text-slate-900 flex items-center">
                <i data-lucide="shield-check" class="w-5 h-5 mr-2 text-green-600"></i> Keamanan Password
            </h3>
        </div>
        
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="space-y-4 max-w-2xl">
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <label class="w-full md:w-1/3 text-sm font-semibold text-slate-700">Password Saat Ini</label>
                        <div class="w-full md:w-2/3">
                            <input name="current_password" class="w-full px-4 py-2 border @error('current_password') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm text-slate-900 placeholder-slate-400 transition-all" type="password" placeholder="Masukkan password saat ini"/>
                            @error('current_password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <label class="w-full md:w-1/3 text-sm font-semibold text-slate-700">Password Baru</label>
                        <div class="w-full md:w-2/3">
                            <input name="new_password" class="w-full px-4 py-2 border @error('new_password') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm text-slate-900 placeholder-slate-400 transition-all" type="password" placeholder="Minimal 8 karakter"/>
                            @error('new_password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <label class="w-full md:w-1/3 text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                        <div class="w-full md:w-2/3">
                            <input name="new_password_confirmation" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm text-slate-900 placeholder-slate-400 transition-all" type="password" placeholder="Ketik ulang password baru"/>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer / Actions -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm flex items-center shadow-sm transition-all">
                    <i data-lucide="key" class="w-4 h-4 mr-2"></i> Ubah Password
                </button>
            </div>
        </form>
    </section>
    <!-- END: Keamanan Card -->
</div>
<!-- END: Page Content -->

@push('scripts')
<script>
    function previewAvatar(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const initial = document.getElementById('avatar-initial');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                
                if (initial) {
                    initial.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection