@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="Tambah Merchant">
        @include('cashless.merchants.form', ['merchant' => null, 'assignedUserIds' => []])
    </x-cashless.shell>
</div>
@endsection
