@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl bg-white p-8 shadow-sm">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold">Login</h1>
            <p class="mt-2 text-sm text-slate-500">
                HafizPlus School Platform
            </p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="login" class="mb-2 block text-sm font-semibold">
                    Email atau Username
                </label>
                <input
                    id="login"
                    name="login"
                    type="text"
                    value="{{ old('login') }}"
                    required
                    autofocus
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-slate-900 focus:outline-none"
                >
                @error('login')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-slate-900 focus:outline-none"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    value="1"
                    class="rounded border-slate-300"
                >
                <label for="remember" class="text-sm text-slate-600">
                    Ingat saya
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700"
            >
                Masuk
            </button>
        </form>
    </div>
@endsection
