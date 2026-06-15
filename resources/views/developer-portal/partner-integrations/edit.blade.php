@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Edit Partner Integration" subtitle="{{ $integration->name }}">
        @include('developer-portal.partner-integrations.form', ['action' => route('developer-portal.partner-integrations.update', $integration), 'method' => 'PUT'])
    </x-developer-portal.shell>
</div>
@endsection
