@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col flex-wrap gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">Notification Center</h2>
            <p class="text-sm text-slate-500">
                {{ $unreadCount }} notifikasi belum dibaca.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if (auth()->user()->hasRole(['super_admin', 'admin']))
                <a href="{{ route('notifications.announcements.create') }}"
                   class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Kirim Pengumuman
                </a>
            @endif

            <form method="POST" action="{{ route('notifications.mark-all-as-read') }}">
                @csrf
                <button type="submit"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    @php
                        $data = $notification->data;
                    @endphp

                    <tr class="border-t {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                        <td class="px-4 py-3">
                            @if ($notification->read_at)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    Dibaca
                                </span>
                            @else
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                    Baru
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900">
                                {{ $data['title'] ?? 'Notifikasi' }}
                            </div>
                            <div class="mt-1 line-clamp-1 text-xs text-slate-500">
                                {{ $data['body'] ?? '-' }}
                            </div>
                        </td>

                        <td class="px-4 py-3 text-slate-700">
                            {{ str_replace('_', ' ', $data['category'] ?? '-') }}
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $notification->created_at?->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('notifications.show', $notification->id) }}"
                                   class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">
                                    Detail
                                </a>

                                @if (! $notification->read_at)
                                    <form method="POST" action="{{ route('notifications.mark-as-read', $notification->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                            Dibaca
                                        </button>
                                    </form>
                                @endif

                                <form method="POST"
                                      action="{{ route('notifications.destroy', $notification->id) }}"
                                      onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg border border-red-300 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                            Belum ada notifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
@endsection
