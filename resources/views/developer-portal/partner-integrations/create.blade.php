@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Integrasi Baru" subtitle="Daftarkan partner, provider, dan konfigurasi teknisnya.">
        @include('developer-portal.partner-integrations.form', ['action' => route('developer-portal.partner-integrations.store'), 'method' => 'POST'])
    </x-developer-portal.shell>
</div>
@endsection
