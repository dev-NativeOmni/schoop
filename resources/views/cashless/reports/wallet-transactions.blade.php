@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6"><x-cashless.shell title="Mutasi Wallet">@include('cashless.reports._transactions-table', ['transactions' => $transactions]){{ $transactions->links() }}</x-cashless.shell></div>
@endsection
