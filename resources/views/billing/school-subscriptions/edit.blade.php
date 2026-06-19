@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-sm text-slate-500">
        <a href="{{ route('billing.school-subscriptions.index') }}" class="hover:text-indigo-600">Subscriptions</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">Edit Langganan Sekolah</span>
    </div>

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Edit Langganan: {{ $schoolSubscription->school?->name }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Ubah rincian status langganan sekolah.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6 md:p-8">
        <form action="{{ route('billing.school-subscriptions.update', $schoolSubscription->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- School (Disabled / Hidden option list) -->
                <div>
                    <label for="school_id" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Sekolah / Tenant</label>
                    <select id="school_id" name="school_id" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition bg-slate-50 dark:bg-slate-900 cursor-not-allowed" readonly>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ $schoolSubscription->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Plan -->
                <div>
                    <label for="subscription_plan_id" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Subscription Plan</label>
                    <select id="subscription_plan_id" name="subscription_plan_id" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ old('subscription_plan_id', $schoolSubscription->subscription_plan_id) == $plan->id ? 'selected' : '' }}>{{ $plan->name }} (IDR {{ number_format($plan->monthly_price, 0, ',', '.') }}/bln)</option>
                        @endforeach
                    </select>
                    @error('subscription_plan_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Status Langganan</label>
                    <select id="status" name="status" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                        <option value="trialing" {{ old('status', $schoolSubscription->status) == 'trialing' ? 'selected' : '' }}>Trialing</option>
                        <option value="active" {{ old('status', $schoolSubscription->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="past_due" {{ old('status', $schoolSubscription->status) == 'past_due' ? 'selected' : '' }}>Past Due</option>
                        <option value="suspended" {{ old('status', $schoolSubscription->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="canceled" {{ old('status', $schoolSubscription->status) == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        <option value="expired" {{ old('status', $schoolSubscription->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Starts At -->
                <div>
                    <label for="starts_at" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Mulai Langganan</label>
                    <input type="date" id="starts_at" name="starts_at" value="{{ old('starts_at', $schoolSubscription->starts_at ? $schoolSubscription->starts_at->format('Y-m-d') : '') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('starts_at')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trial Ends At -->
                <div>
                    <label for="trial_ends_at" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Berakhir Trial (Optional)</label>
                    <input type="date" id="trial_ends_at" name="trial_ends_at" value="{{ old('trial_ends_at', $schoolSubscription->trial_ends_at ? $schoolSubscription->trial_ends_at->format('Y-m-d') : '') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('trial_ends_at')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Period Starts At -->
                <div>
                    <label for="current_period_starts_at" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Mulai Siklus Tagihan</label>
                    <input type="date" id="current_period_starts_at" name="current_period_starts_at" value="{{ old('current_period_starts_at', $schoolSubscription->current_period_starts_at ? $schoolSubscription->current_period_starts_at->format('Y-m-d') : '') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('current_period_starts_at')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Period Ends At -->
                <div>
                    <label for="current_period_ends_at" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Akhir Siklus Tagihan</label>
                    <input type="date" id="current_period_ends_at" name="current_period_ends_at" value="{{ old('current_period_ends_at', $schoolSubscription->current_period_ends_at ? $schoolSubscription->current_period_ends_at->format('Y-m-d') : '') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('current_period_ends_at')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Metadata JSON -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="metadata_raw" class="block text-sm font-bold text-slate-700 dark:text-slate-350">Metadata Tambahan (JSON)</label>
                    <span class="text-xs text-slate-400">Optional JSON</span>
                </div>
                <textarea id="metadata_raw" name="metadata_raw" rows="3" class="w-full font-mono text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">{{ old('metadata_raw', json_encode($schoolSubscription->metadata, JSON_PRETTY_PRINT)) }}</textarea>
                @error('metadata')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-slate-100 dark:border-slate-800 pt-6">
                <a href="{{ route('billing.school-subscriptions.index') }}" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
