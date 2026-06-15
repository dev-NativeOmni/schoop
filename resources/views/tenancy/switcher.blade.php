@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="text-center space-y-3">
        <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Pilih Sekolah</h1>
        <p class="text-base text-slate-500 max-w-lg mx-auto">Silakan pilih sekolah aktif yang ingin Anda kelola dan pantau progresnya saat ini.</p>
    </div>

    <!-- Active School Display -->
    @php
        $activeSchoolId = app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();
    @endphp

    <div class="grid gap-6 sm:grid-cols-2">
        @foreach($schools as $school)
            @php
                $isActive = $school->id === $activeSchoolId;
            @endphp
            <div class="relative rounded-3xl border {{ $isActive ? 'border-[#84cc16] bg-[#84cc16]/5 ring-2 ring-[#84cc16]/20' : 'border-slate-200 bg-white hover:border-slate-300' }} p-6 shadow-sm transition-all hover:shadow-md flex flex-col justify-between">
                <div>
                    <!-- Badge status -->
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                            {{ strtoupper($school->tenant_code ?? 'SCHOOL') }}
                        </span>

                        @if($isActive)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Aktif Saat Ini
                            </span>
                        @endif
                    </div>

                    <div class="mt-4">
                        <h3 class="text-xl font-bold text-slate-900">{{ $school->name }}</h3>
                        <p class="text-sm text-slate-400 mt-1">{{ $school->address ?? 'Alamat belum diset.' }}</p>
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-100/80 pt-4 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Status: <strong class="text-slate-600 capitalize">{{ $school->tenant_status }}</strong></span>
                    
                    @if($isActive)
                        <button disabled class="rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold text-slate-400 cursor-not-allowed">
                            Terpilih
                        </button>
                    @else
                        <form action="{{ route('tenancy.switch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="school_id" value="{{ $school->id }}">
                            <button type="submit" class="rounded-xl bg-gradient-to-r from-slate-900 to-indigo-950 px-4 py-2 text-xs font-bold text-white shadow-md transition-all hover:scale-[1.03] active:scale-[0.97] hover:shadow-indigo-900/10">
                                Pilih Sekolah
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
