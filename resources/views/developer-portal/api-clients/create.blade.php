@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Client Baru" subtitle="Buat credential eksternal untuk partner atau integrasi tenant.">
        @include('developer-portal.api-clients.form', ['action' => route('developer-portal.api-clients.store'), 'method' => 'POST'])
    </x-developer-portal.shell>
</div>
@endsection
