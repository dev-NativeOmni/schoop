@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Edit API Client" subtitle="{{ $client->name }}">
        @include('developer-portal.api-clients.form', ['action' => route('developer-portal.api-clients.update', $client), 'method' => 'PUT'])
    </x-developer-portal.shell>
</div>
@endsection
