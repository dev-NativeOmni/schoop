@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <x-saas-ops.shell title="Support Tickets" subtitle="Pusat bantuan teknis, keluhan operasional sekolah, dan resolusi tiket.">
        
        {{-- Header Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Total Tiket:</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-black text-xs">
                    {{ $tickets->total() }} Tiket
                </span>
            </div>
            <a href="{{ route('saas-ops.support-tickets.create') }}" class="btn-natural-primary text-xs px-4 py-2.5 shadow-sm inline-flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Buat Tiket Baru</span>
            </a>
        </div>

        {{-- Tickets Table Card --}}
        <div class="card-natural overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200/80 dark:border-slate-800 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-extrabold text-[11px]">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Nomor Tiket</th>
                            <th scope="col" class="px-5 py-3.5">Subjek Kendala</th>
                            <th scope="col" class="px-5 py-3.5">Nama Sekolah</th>
                            <th scope="col" class="px-5 py-3.5">Prioritas</th>
                            <th scope="col" class="px-5 py-3.5">Status</th>
                            <th scope="col" class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/40 transition-colors">
                                <td class="px-5 py-4">
                                    <a href="{{ route('saas-ops.support-tickets.show', $ticket) }}" class="font-mono font-bold text-slate-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 text-xs block">
                                        {{ $ticket->ticket_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-bold text-slate-900 dark:text-white text-sm block">
                                        {{ $ticket->subject }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $ticket->school?->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $priority = strtolower($ticket->priority ?? 'medium');
                                        $priorityStyles = match($priority) {
                                            'high', 'critical' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                            'medium' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                            default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border uppercase {{ $priorityStyles }}">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $status = strtolower($ticket->status ?? 'open');
                                        $statusStyles = match($status) {
                                            'resolved', 'closed' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'in_progress' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-800/60',
                                            default => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black border uppercase tracking-wider {{ $statusStyles }}">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('saas-ops.support-tickets.show', $ticket) }}" class="btn-natural-secondary text-xs px-3 py-1.5 shadow-2xs">
                                        <span>Detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                    <p class="font-bold text-sm text-slate-600 dark:text-slate-300">Belum ada tiket bantuan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>

    </x-saas-ops.shell>
</div>
@endsection
