@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('tenancy.memberships.index') }}" class="rounded-xl border border-slate-200 p-2 text-slate-500 hover:bg-slate-50 transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900">Ubah Anggota</h1>
            <p class="text-xs text-slate-400 mt-0.5">Edit keanggotaan <strong class="text-slate-650">{{ $membership->user->name }}</strong> di <strong class="text-slate-605">{{ $school->name }}</strong></p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('tenancy.memberships.update', $membership) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- User Info (Disabled) -->
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">User</label>
                <input type="text" disabled value="{{ $membership->user->name }} ({{ $membership->user->email }})" class="block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500 cursor-not-allowed">
            </div>

            <!-- Role Select -->
            <div class="space-y-2">
                <label for="role_id" class="text-sm font-bold text-slate-700">Role di Sekolah ini</label>
                <select id="role_id" name="role_id" required class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="" disabled>Pilih Role...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $membership->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="text-xs font-semibold text-red-650 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Select -->
            <div class="space-y-2">
                <label for="membership_status" class="text-sm font-bold text-slate-700">Status Keanggotaan</label>
                <select id="membership_status" name="membership_status" required class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="active" {{ old('membership_status', $membership->membership_status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('membership_status', $membership->membership_status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('membership_status')
                    <p class="text-xs font-semibold text-red-650 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Joined At -->
            <div class="space-y-2">
                <label for="joined_at" class="text-sm font-bold text-slate-700">Tanggal Bergabung</label>
                <input type="date" id="joined_at" name="joined_at" value="{{ old('joined_at', $membership->joined_at ? $membership->joined_at->format('Y-m-d') : '') }}" required class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                @error('joined_at')
                    <p class="text-xs font-semibold text-red-650 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Set as Default -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default', $membership->is_default) ? 'checked' : '' }} class="h-4.5 w-4.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_default" class="text-sm font-bold text-slate-700">Jadikan Sekolah Default untuk User ini</label>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] hover:shadow-indigo-900/10">
                    Perbarui Anggota
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
