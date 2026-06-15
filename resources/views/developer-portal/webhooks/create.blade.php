@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Webhook Baru" subtitle="Daftarkan endpoint delivery partner.">
        @include('developer-portal.webhooks.form', ['action' => route('developer-portal.webhooks.store'), 'method' => 'POST'])
    </x-developer-portal.shell>
</div>
@endsection
