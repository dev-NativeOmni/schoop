@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Edit Webhook" subtitle="{{ $webhook->name }}">
        @include('developer-portal.webhooks.form', ['action' => route('developer-portal.webhooks.update', $webhook), 'method' => 'PUT'])
    </x-developer-portal.shell>
</div>
@endsection
