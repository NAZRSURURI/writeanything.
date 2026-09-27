@extends('layouts.app', ['title' => 'Edit Catatan | WriteAnything'])

@section('content')
    <main class="mx-auto max-w-2xl px-6 pb-24 pt-10">

        {{-- Link Kembali --}}
        <a href="{{ route('admin.index') }}" class="text-sm text-black/50 underline">
            ← kembali ke admin
        </a>

        {{-- Header Section --}}
        <p class="mb-3 mt-12 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
            content desk
        </p>
        <h1 class="display text-5xl">edit catatan.</h1>

        {{-- Form Edit --}}
        <form method="POST" action="{{ route('admin.cards.update', $card) }}" class="mt-10 space-y-5">
            @csrf
            @method('PUT')

            <label class="block text-sm font-semibold">
                Isi pesan
                <textarea name="message" required rows="6"
                          class="mt-2 w-full resize-none border border-black/15 bg-white/60 p-4 hand text-2xl outline-none focus:border-[#9d8ed0]">{{ old('message', $card->message) }}</textarea>
            </label>

            <div class="grid gap-5 sm:grid-cols-2">
                <label class="block text-sm font-semibold">
                    Keterangan waktu
                    <input name="time_caption" value="{{ old('time_caption', $card->time_caption) }}" required
                           class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                </label>

                <label class="block text-sm font-semibold">
                    Nama pengirim / karakter
                    <input name="signature" value="{{ old('signature', $card->signature) }}" required
                           class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                </label>
            </div>

            <button class="bg-[#242320] px-6 py-4 text-sm font-semibold text-white">
                simpan perubahan
            </button>
        </form>

    </main>
@endsection
