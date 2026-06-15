@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6"><x-cashless.shell title="Tambah Produk">@include('cashless.products.form', ['product' => null])</x-cashless.shell></div>
@endsection
