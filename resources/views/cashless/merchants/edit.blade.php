@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="Edit Merchant">
        @include('cashless.merchants.form', ['merchant' => $merchant, 'assignedUserIds' => $assignedUserIds])
    </x-cashless.shell>
</div>
@endsection
