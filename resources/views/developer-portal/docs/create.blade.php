@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Dokumentasi Baru" subtitle="Tambah halaman dokumentasi API.">
        @include('developer-portal.docs.form', ['action' => route('developer-portal.docs.store'), 'method' => 'POST'])
    </x-developer-portal.shell>
</div>
@endsection
