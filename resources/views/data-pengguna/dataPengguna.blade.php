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
</head>
<body class="bg-background font-body-md text-on-background antialiased notranslate" translate="no">

@include('partials.sidebar')

<!-- Main Content Wrapper -->
<div class="ml-[200px] min-h-screen">
    @include('partials.topbar')

    <!-- Main Canvas -->
    <main class="p-4 pt-16 space-y-6">
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
                                    <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors">
                                        <span class="material-symbols-outlined !text-[18px]" data-icon="edit">edit</span>
                                    </button>
                                    <button class="p-1 text-error hover:bg-error-container/20 rounded transition-colors">
                                        <span class="material-symbols-outlined !text-[18px]" data-icon="delete">delete</span>
                                    </button>
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
    </main>
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
</div>

@vite(['resources/js/dashboard.js'])
</body>
</html>