@extends('layouts.app', ['title' => 'Masuk | WriteAnything'])

@section('content')
    <main class="mx-auto max-w-md px-6 pb-24 pt-12">
        <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
            welcome back
        </p>

        <h1 class="display text-5xl">masuk lagi.</h1>

        <p class="mt-4 text-sm leading-6 text-black/55">
            Lanjutkan menulis hal-hal yang ingin kamu titipkan.
        </p>

        <form method="POST" action="{{ route('login') }}" class="mt-10 space-y-5">
            @csrf

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

            <label class="flex items-center gap-2 text-sm text-black/60">
                <input type="checkbox" name="remember">
                Ingat saya
            </label>

            <button class="w-full bg-[#242320] py-4 text-sm font-semibold text-white">
                masuk
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-black/55">
            Belum punya akun? <a href="{{ route('register') }}" class="font-semibold underline">Daftar</a>
        </p>
    </main>
@endsection
