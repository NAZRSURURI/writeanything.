@extends('layouts.app', ['title' => 'Dashboard | WriteAnything'])

@section('content')
    {{-- Dashboard hanya dapat dibuka user login dan menjadi tempat editor kartu serta arsip pribadi. --}}
    <main class="mx-auto max-w-6xl px-6 pb-24 pt-10 lg:px-10">

        {{-- Header Section --}}
        <div class="flex flex-col justify-between gap-6 border-b border-black/10 pb-10 sm:flex-row sm:items-end">
            <div>
                <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
                    your private corner
                </p>
                <h1 class="display text-5xl">hello, {{ auth()->user()->name }}.</h1>
                <p class="mt-3 text-sm text-black/55">
                    Rancang catatanmu sebelum dititipkan ke wall.
                </p>
            </div>
            <a href="#new-card" class="rounded-full bg-[#242320] px-5 py-3 text-sm font-semibold text-white">
                buat kartu baru <span class="ml-2">↗</span>
            </a>
        </div>

        {{-- Form New Card --}}
        <section id="new-card" class="mt-10 max-w-3xl">
            <h2 class="display text-3xl">new little note</h2>

            <form method="POST" action="{{ route('cards.store') }}" class="mt-6 space-y-5">
                @csrf

                <label class="block text-sm font-semibold">
                    Isi pesan
                    <textarea name="message" required maxlength="1000" rows="5" placeholder="aku ingin bilang..."
                              class="mt-2 w-full resize-none border border-black/15 bg-white/60 p-4 hand text-2xl outline-none focus:border-[#9d8ed0]">{{ old('message') }}</textarea>
                </label>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block text-sm font-semibold">
                        Keterangan waktu
                        <input name="time_caption" value="{{ old('time_caption') }}" required placeholder="september 2008"
                               class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                    </label>
                    <label class="block text-sm font-semibold">
                        Nama pengirim / karakter
                        <input name="signature" value="{{ old('signature') }}" required placeholder="from planet"
                               class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                    </label>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block text-sm font-semibold">
                        Kategori
                        <select name="category" class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                            <option>Bucin</option>
                            <option>Patah Hati</option>
                            <option>Motivasi</option>
                            <option>Minta Maaf</option>
                            <option selected>Rahasia</option>
                        </select>
                    </label>
                    <label class="block text-sm font-semibold">
                        Stiker sudut
                        <select name="sticker" class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                            <option value="">Tanpa stiker</option>
                            <option value="heart">Hati retak</option>
                            <option value="flower">Bunga</option>
                            <option value="star">Bintang</option>
                        </select>
                    </label>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block text-sm font-semibold">
                        Latar belakang
                        <select name="background" class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                            <option value="paper">Kertas minimalis</option>
                            <option value="vintage">Vintage hangat</option>
                            <option value="night">Langit malam</option>
                            <option value="pastel">Pastel lembut</option>
                        </select>
                    </label>
                    <label class="block text-sm font-semibold">
                        Gaya huruf
                        <select name="font_style" class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                            <option value="hand">Tulisan tangan</option>
                            <option value="typewriter">Mesin ketik</option>
                            <option value="bold">Modern tebal</option>
                        </select>
                    </label>
                </div>

                <label class="block text-sm font-semibold">
                    Warna kartu
                    <select name="tone" class="mt-2 w-full border border-black/15 bg-white/60 p-3 font-normal outline-none focus:border-[#9d8ed0]">
                        <option value="lavender">Lavender</option>
                        <option value="butter">Butter</option>
                        <option value="mint">Mint</option>
                        <option value="coral">Coral</option>
                    </select>
                </label>

                <button class="bg-[#242320] px-6 py-4 text-sm font-semibold text-white">
                    kirim untuk moderasi <span class="ml-2">↗</span>
                </button>
            </form>
        </section>

        {{-- Archive Section --}}
        <section class="mt-20">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
                        your archive
                    </p>
                    <h2 class="display text-4xl">kartu yang pernah kamu buat.</h2>
                </div>
                <span class="text-sm text-black/45">{{ $cards->count() }} kartu</span>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @forelse($cards as $card)
                    <article class="flex min-h-[260px] flex-col justify-between p-7 {{ $card->tone === 'butter' ? 'bg-[#f8dda0]' : ($card->tone === 'mint' ? 'bg-[#cfe2d4]' : ($card->tone === 'coral' ? 'bg-[#f3cec2]' : 'bg-[#d9d5f2]')) }}">
                        <div>
                            <div class="mb-6 flex items-center justify-between">
                                <span class="text-[9px] font-bold uppercase tracking-[.2em] text-black/45">
                                    {{ $card->category }} · {{ $card->time_caption }}
                                </span>
                                <span class="text-[10px] font-semibold uppercase text-black/40">
                                    {{ $card->status }}
                                </span>
                            </div>
                            <p class="{{ $card->font_style === 'typewriter' ? 'font-mono text-lg' : ($card->font_style === 'bold' ? 'font-sans text-xl font-bold' : 'hand text-[1.8rem]') }} leading-tight">
                                {{ $card->message }}
                                @if($card->sticker)
                                    <span>{{ $card->sticker === 'heart' ? '♡' : ($card->sticker === 'flower' ? '✿' : '✦') }}</span>
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center justify-between text-xs text-black/45">
                            <span>— {{ $card->signature }} · {{ $card->likes_count }} likes</span>
                            <form method="POST" action="{{ route('cards.destroy', $card) }}">
                                @csrf
                                @method('DELETE')
                                <button class="underline">hapus</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-black/50">Belum ada kartu. Mungkin ini waktunya menulis yang pertama.</p>
                @endforelse
            </div>
        </section>

    </main>
@endsection
