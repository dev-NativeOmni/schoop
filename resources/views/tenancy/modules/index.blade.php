@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900">Tenant Modules</h1>
        <p class="text-sm text-slate-500 mt-1">Aktifkan atau nonaktifkan fitur/module spesifik sekolah aktif saat ini.</p>
    </div>

    <!-- Modules Grid -->
    <div class="grid gap-6 sm:grid-cols-2">
        @forelse($modules as $module)
            @php $isAllowedByPlan = in_array($module->module_key, $allowedModules ?? [], true); @endphp
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between hover:border-slate-300 transition-all">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">{{ $module->module_name }}</h3>
                        
                        @if(!$isAllowedByPlan)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-600"></span>
                                Terkunci Plan
                            </span>
                        @elseif($module->is_enabled)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-xs text-slate-400">Key: <code class="bg-slate-50 px-1.5 py-0.5 rounded text-slate-600 font-mono text-[10px]">{{ $module->module_key }}</code></p>
                    <p class="text-sm text-slate-500 mt-2">
                        @if($isAllowedByPlan)
                            Aktifkan modul ini untuk memberikan akses fitur {{ $module->module_name }} kepada guru, santri, dan orang tua.
                        @else
                            Modul ini tidak termasuk dalam subscription plan sekolah aktif.
                        @endif
                    </p>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-4">
                    @if($isAllowedByPlan)
                        <form action="{{ route('tenancy.modules.update', $module) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_enabled" value="{{ $module->is_enabled ? '0' : '1' }}">
                            
                            @if($module->is_enabled)
                                <button type="submit" class="w-full rounded-xl border border-red-200 bg-red-50 py-2.5 text-xs font-bold text-red-700 hover:bg-red-100 transition-colors">
                                    Nonaktifkan Modul
                                </button>
                            @else
                                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-slate-900 to-indigo-950 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:scale-[1.01] active:scale-[0.99] hover:shadow-indigo-900/5">
                                    Aktifkan Modul
                                </button>
                            @endif
                        </form>
                    @else
                        <button type="button" disabled class="w-full rounded-xl border border-slate-200 bg-slate-100 py-2.5 text-xs font-bold text-slate-400">
                            Upgrade Plan untuk Mengaktifkan
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-2 rounded-3xl border border-dashed border-slate-200 p-12 text-center text-slate-500">
                <p class="font-bold">Tidak ada modul terdaftar.</p>
                <p class="text-xs text-slate-400 mt-1">Harap jalankan seeder modul atau hubungi super admin.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
