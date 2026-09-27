@extends('layouts.app', ['title' => 'Daftar | WriteAnything'])

@section('content')
    <main class="mx-auto max-w-md px-6 pb-24 pt-12">
        <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
            make a little space
        </p>

        <h1 class="display text-5xl">buat akun.</h1>

        <p class="mt-4 text-sm leading-6 text-black/55">
            Satu ruang privat untuk semua kata yang ingin kamu simpan.
        </p>

        <form method="POST" action="{{ route('register') }}" class="mt-10 space-y-5">
            @csrf

            <label class="block text-sm font-semibold">
                Nama
                <input name="name" value="{{ old('name') }}" required
                       class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
            </label>

            <label class="block text-sm font-semibold">
                Username
                <input name="username" value="{{ old('username') }}" required
                       class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
            </label>

            <label class="block text-sm font-semibold">
                Email
                <input name="email" type="email" value="{{ old('email') }}" required
                       class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
            </label>

            <label class="block text-sm font-semibold">
                Password
                <input name="password" type="password" required
                       class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
            </label>

            <label class="block text-sm font-semibold">
                Ulangi password
                <input name="password_confirmation" type="password" required
                       class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
            </label>

            <button class="w-full bg-[#242320] py-4 text-sm font-semibold text-white">
                buat akun
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-black/55">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold underline">Masuk</a>
        </p>
    </main>
@endsection
