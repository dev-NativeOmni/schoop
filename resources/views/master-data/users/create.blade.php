@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Tambah User Baru</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Buat akun user baru untuk peran dan sekolah apa pun.</p>
    </div>

    <div class="max-w-xl rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="POST" action="{{ route('master-data.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15" required>
                @error('name') <p class="mt-1 text-sm text-red-650">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Username (Unique)</label>
                <input type="text" name="username" value="{{ old('username') }}" 
                       class="w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15" required>
                @error('username') <p class="mt-1 text-sm text-red-650">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Email (Unique)</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15" required>
                @error('email') <p class="mt-1 text-sm text-red-650">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Telepon / HP</label>
                <input type="text" name="phone" value="{{ old('phone') }}" 
                       class="w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15">
                @error('phone') <p class="mt-1 text-sm text-red-650">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
                <input type="password" name="password" 
                       class="w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15" required placeholder="Minimal 8 karakter">
                @error('password') <p class="mt-1 text-sm text-red-650">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Peran (Role)</label>
                <select name="role_id" id="role_select" onchange="handleRoleChange()"
                        class="w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15" required>
                    <option value="">Pilih Peran</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" data-name="{{ $role->name }}" @selected(old('role_id') == $role->id)>
                            {{ $role->label }}
                        </option>
                    @endforeach
                </select>
                @error('role_id') <p class="mt-1 text-sm text-red-650">{{ $message }}</p> @enderror
            </div>

            <div id="school_container">
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Sekolah</label>
                <select name="school_id" id="school_select"
                        class="w-full rounded-lg border border-slate-350 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15" required>
                    <option value="">Pilih Sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected(old('school_id') == $school->id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id') <p class="mt-1 text-sm text-red-650">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 py-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', true))
                       class="rounded border-slate-350 text-emerald-500 focus:ring-emerald-500/20 bg-slate-50/50 dark:bg-slate-850">
                <label for="is_active" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Status Aktif</label>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" 
                        class="rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 px-5 py-2.5 text-sm font-bold text-[#1F2937] hover:scale-[1.02] shadow-md shadow-emerald-600/10 hover:shadow-lg hover:shadow-emerald-600/15 transition-all focus:outline-none active:scale-[0.98]">
                    Simpan User
                </button>
                <a href="{{ route('master-data.users.index') }}" 
                   class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        function handleRoleChange() {
            const roleSelect = document.getElementById('role_select');
            const schoolContainer = document.getElementById('school_container');
            const schoolSelect = document.getElementById('school_select');
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            const roleName = selectedOption.getAttribute('data-name');
            
            if (roleName === 'super_admin') {
                schoolContainer.classList.add('hidden');
                schoolSelect.value = '';
                schoolSelect.removeAttribute('required');
            } else {
                schoolContainer.classList.remove('hidden');
                schoolSelect.setAttribute('required', 'required');
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            handleRoleChange();
        });
    </script>
@endsection
