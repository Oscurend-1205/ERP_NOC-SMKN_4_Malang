<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Manajemen Pengguna - Inventory System SMKN 4 Malang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://unpkg.com/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
    @vite(['resources/css/dashboard.css'])
    <style>
        html { zoom: 0.9; }
        /* Fix viewport height when zoomed */
        .min-h-screen { min-height: calc(100vh / 0.9) !important; }
        .h-screen { height: calc(100vh / 0.9) !important; }
        
        /* Consistent table header styling */
        table thead {
            background-color: #e5e7eb !important;
            border-bottom: 1px solid #d1d5db !important;
        }
        table thead th {
            color: #1f2937 !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 0.75rem 1rem !important;
        }
        /* Elegant minimalist table cells */
        table tbody td {
            padding: 0.5rem 1rem !important;
        }
    </style>
</head>
<body class="flex min-h-screen bg-[#F8FAFC]">

@include('partials.sidebar')

<!-- BEGIN: Main Content Area -->
<main class="flex-grow flex flex-col h-screen overflow-y-auto">
    @include('partials.topbar')

    <!-- BEGIN: Page Content -->
    <div id="pjax-content" class="p-4 md:p-10 pt-4 md:pt-6 space-y-6">
        @php
            $currentHour = now()->format('H');
            $isOpen = ($currentHour >= 6 && $currentHour < 15);
        @endphp
        <!-- Page Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-on-background">Manajemen Pengguna</h1>
                <p class="text-xs text-outline">Kelola data user, role, dan hak akses sistem.</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Realtime Monitor (Clock & Speed) -->
                <div class="bg-black border border-gray-600 px-4 py-1.5 rounded-lg text-white font-mono text-base flex items-center gap-4 shadow-inner">
                    <!-- Clock -->
                    <span id="realtime-clock-display" class="font-extrabold tracking-widest text-blue-400">00:00:00</span>
                    
                    <!-- Divider -->
                    <span class="text-gray-600 font-normal">|</span>
                    
                    <!-- Status Operasional -->
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-400/50 text-[16px]" data-icon="schedule">schedule</span>
                        <span id="operational-status" class="font-bold text-[13px] {{ $isOpen ? 'text-green-400' : 'text-red-400' }}">
                            {{ $isOpen ? 'open' : 'closed' }}
                        </span>
                    </div>
                </div>

                @if(Auth::user()->role === 'Superadmin')
                <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-primary text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-sm active:scale-95 text-sm">
                    <span class="material-symbols-outlined text-[20px]" data-icon="person_add">person_add</span>
                    Tambah User
                </button>
                @endif
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-low text-outline text-[10px] uppercase font-bold tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3">NO</th>
                            <th class="px-4 py-3">NAMA LENGKAP</th>
                            <th class="px-4 py-3">USERNAME</th>
                            <th class="px-4 py-3">EMAIL</th>
                            <th class="px-4 py-3">ROLE</th>
                            <th class="px-4 py-3 text-center">STATUS</th>
                            @if(Auth::user()->role === 'Superadmin')
                            <th class="px-4 py-3 text-right">AKSI</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-[11px]">
                        @forelse($users ?? [] as $index => $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-outline">{{ $users->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-[10px]">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                    <span class="font-bold text-on-surface">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $user->username ?? '-' }}</td>
                            <td class="px-4 py-3 text-primary">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[9px] font-bold uppercase">{{ $user->role }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($user->is_active)
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-md text-[9px] font-bold uppercase">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-md text-[9px] font-bold uppercase">Non-Aktif</span>
                                @endif
                            </td>
                            @if(Auth::user()->role === 'Superadmin')
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->username ?? '') }}', '{{ addslashes($user->email ?? '') }}', '{{ $user->role }}', {{ $user->is_active ? '1' : '0' }})" class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors">
                                        <span class="material-symbols-outlined !text-[18px]" data-icon="edit">edit</span>
                                    </button>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-error hover:bg-error-container/20 rounded transition-colors">
                                            <span class="material-symbols-outlined !text-[18px]" data-icon="delete">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-outline italic">Belum ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 py-3 bg-white border-t border-gray-50 flex items-center justify-between">
                @if(isset($users) && $users->count() > 0)
                <p class="text-[10px] text-outline italic">Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} dari {{ $users->total() }} entri</p>
                <div class="flex items-center gap-1">
                    {{ $users->links('pagination::tailwind') }}
                </div>
                @else
                <p class="text-[10px] text-outline italic">Menampilkan 0 entri</p>
                @endif
            </div>
        </div>

<!-- Modal Tambah User (100% Exact Match) -->
<div id="addUserModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <!-- Backdrop Blur -->
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('addUserModal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white">
            <h2 class="text-[18px] font-bold text-gray-900">Tambah User Baru</h2>
            <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Form Body -->
        <form action="{{ route('users.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                
                <!-- Nama Lengkap -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Masukkan nama lengkap" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Username -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Username</label>
                    <input type="text" name="username" required placeholder="Masukkan username" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Email</label>
                    <input type="email" name="email" required placeholder="email@contoh.com" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Role -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Role</label>
                    <select name="role" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm appearance-none cursor-pointer" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%234b5563%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;">
                        <option value="" disabled selected hidden>Pilih Role</option>
                        <option value="Superadmin">Superadmin</option>
                        <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                        <option value="User">User</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Password</label>
                    <input type="password" name="password" required placeholder="........" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 tracking-widest text-lg h-9">
                </div>

                <!-- Status Akun Toggle -->
                <div class="flex items-center justify-between pt-1">
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700">Status Akun</label>
                        <p class="text-[13px] text-gray-500 mt-0.5">Aktifkan user ini agar dapat login.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#005bbf]"></div>
                    </label>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-5 py-2 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-[13px] font-bold text-white bg-primary rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editUserModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <!-- Backdrop Blur -->
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editUserModal').classList.add('hidden')"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-[450px] bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] font-sans">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white">
            <h2 class="text-[18px] font-bold text-gray-900">Edit User</h2>
            <button onclick="document.getElementById('editUserModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Form Body -->
        <form id="editUserForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4 overflow-y-auto">
                
                <!-- Nama Lengkap -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" id="edit_user_name" name="name" required placeholder="Masukkan nama lengkap" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Username -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Username</label>
                    <input type="text" id="edit_user_username" name="username" required placeholder="Masukkan username" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Email</label>
                    <input type="email" id="edit_user_email" name="email" required placeholder="email@contoh.com" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                </div>

                <!-- Role -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Role</label>
                    <select id="edit_user_role" name="role" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-gray-700 bg-white shadow-sm appearance-none cursor-pointer" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%234b5563%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;">
                        <option value="" disabled selected hidden>Pilih Role</option>
                        <option value="Superadmin">Superadmin</option>
                        <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                        <option value="User">User</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label class="block text-[13px] font-bold text-gray-700">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 tracking-widest text-lg h-9">
                </div>

                <!-- Status Akun Toggle -->
                <div class="flex items-center justify-between pt-1">
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700">Status Akun</label>
                        <p class="text-[13px] text-gray-500 mt-0.5">Aktifkan user ini agar dapat login.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="edit_user_is_active" name="is_active" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#005bbf]"></div>
                    </label>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 mt-auto">
                <button type="button" onclick="document.getElementById('editUserModal').classList.add('hidden')" class="px-5 py-2 text-[13px] font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-[13px] font-bold text-white bg-primary rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditUserModal(id, name, username, email, role, isActive) {
        document.getElementById('editUserForm').action = `/data-pengguna/${id}`;
        document.getElementById('edit_user_name').value = name;
        document.getElementById('edit_user_username').value = username;
        document.getElementById('edit_user_email').value = email;
        document.getElementById('edit_user_role').value = role;
        document.getElementById('edit_user_is_active').checked = isActive === 1;
        document.getElementById('editUserModal').classList.remove('hidden');
    }
</script>
    </div>
</main>
<!-- END PJAX CONTENT -->
@vite(['resources/js/dashboard.js'])
@vite(['resources/js/turbo-navigation.js'])

<script>
    document.addEventListener('DOMContentLoaded', initAjaxModals);
    document.addEventListener('pjax:complete', initAjaxModals);
    document.addEventListener('turbo:load', initAjaxModals);

    function initAjaxModals() {
        const modals = document.querySelectorAll('div[id$="Modal"]');
        modals.forEach(modal => {
            const form = modal.querySelector('form');
            if (!form || form.dataset.ajaxInitialized) return;
            
            if (form.method.toUpperCase() === 'GET' || form.dataset.noAjax) return;
            
            form.dataset.ajaxInitialized = 'true';

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Simpan';
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...</span>';
                    submitBtn.disabled = true;
                }

                form.querySelectorAll('.validation-error').forEach(el => el.remove());
                form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500'));

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: form.method || 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (response.status === 422) {
                        const data = await response.json();
                        for (const field in data.errors) {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                                const errorEl = document.createElement('p');
                                errorEl.className = 'validation-error text-red-500 text-xs mt-1 font-medium';
                                errorEl.textContent = data.errors[field][0];
                                input.parentNode.appendChild(errorEl);
                            }
                        }
                    } else if (response.ok) {
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newTable = doc.querySelector('table tbody');
                        const currentTable = document.querySelector('table tbody');
                        if (newTable && currentTable) {
                            currentTable.innerHTML = newTable.innerHTML;
                        }

                        const newPagination = doc.querySelector('.px-4.py-3.bg-white.border-t');
                        const currentPagination = document.querySelector('.px-4.py-3.bg-white.border-t');
                        if (newPagination && currentPagination) {
                            currentPagination.innerHTML = newPagination.innerHTML;
                        }

                        // Handle flash messages
                        const newFlashes = Array.from(doc.querySelectorAll('#pjax-content > .bg-green-50, #pjax-content > .bg-red-50'));
                        const pjaxContent = document.getElementById('pjax-content');
                        
                        if (pjaxContent) {
                            pjaxContent.querySelectorAll(':scope > .bg-green-50, :scope > .bg-red-50').forEach(el => el.remove());
                            newFlashes.reverse().forEach(flash => {
                                pjaxContent.insertBefore(flash, pjaxContent.firstChild);
                            });
                        }

                        modal.classList.add('hidden');
                        form.reset();
                    } else {
                        alert('Terjadi kesalahan sistem. Status: ' + response.status);
                    }
                } catch (error) {
                    console.error(error);
                    alert('Gagal terhubung ke server.');
                } finally {
                    if (submitBtn) {
                        submitBtn.innerHTML = originalBtnText;
                        submitBtn.disabled = false;
                    }
                }
            });
        });
    }
</script>

@include('components.accessibility-button')
</body>
</html>