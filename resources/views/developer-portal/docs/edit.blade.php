@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Edit Dokumentasi" subtitle="{{ $page->title }}">
        @include('developer-portal.docs.form', ['action' => route('developer-portal.docs.update', $page), 'method' => 'PUT'])
    </x-developer-portal.shell>
</div>
@endsection
