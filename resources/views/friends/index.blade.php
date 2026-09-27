@extends('layouts.app', ['title' => 'Teman | WriteAnything'])

@section('content')
    {{-- Halaman ini mengelompokkan hubungan menjadi teman aktif, request masuk, request keluar, dan saran. --}}
    <main class="mx-auto max-w-6xl px-6 pb-24 pt-10 lg:px-10">

        {{-- Header Section --}}
        <div class="mb-12 border-b border-black/10 pb-10">
            <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
                your circle
            </p>
            <h1 class="display text-5xl">teman-temanmu.</h1>
            <p class="mt-3 max-w-md text-sm leading-6 text-black/55">
                Bangun lingkar kecil untuk saling menemukan dan memberi dukungan.
            </p>
        </div>

        {{-- Active Friends Section --}}
        <section class="mb-16">
            <div class="mb-6 flex items-end justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
                        connected
                    </p>
                    <h2 class="display text-3xl">teman kamu.</h2>
                </div>
                <span class="text-sm text-black/45">{{ $friendships->count() }} teman</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($friendships as $friendship)
                    @php($friend = $friendship->requester_id === auth()->id() ? $friendship->addressee : $friendship->requester)
                    <div class="flex items-center justify-between border border-black/10 bg-white/50 p-5">
                        <div>
                            <p class="font-semibold">{{ $friend->name }}</p>
                            <p class="text-sm text-black/45">
                                <span aria-hidden="true">@</span>{{ $friend->username }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('friendships.reject', $friendship) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs text-black/45 underline">hapus</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-black/50">Belum ada teman. Cari seseorang untuk memulai.</p>
                @endforelse
            </div>
        </section>

        {{-- Requests Grid (Incoming & Outgoing) --}}
        <div class="grid gap-16 lg:grid-cols-2">

            {{-- Incoming Requests --}}
            <section>
                <div class="mb-6">
                    <p class="mb-2 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
                        incoming
                    </p>
                    <h2 class="display text-3xl">permintaan masuk.</h2>
                </div>
                <div class="space-y-3">
                    @forelse($incoming as $friendship)
                        <div class="flex items-center justify-between border border-black/10 bg-[#f8dda0] p-5">
                            <div>
                                <p class="font-semibold">{{ $friendship->requester->name }}</p>
                                <p class="text-sm text-black/45">
                                    <span aria-hidden="true">@</span>{{ $friendship->requester->username }}
                                </p>
                            </div>
                            <div class="flex gap-3">
                                <form method="POST" action="{{ route('friendships.accept', $friendship) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="font-semibold underline">terima</button>
                                </form>
                                <form method="POST" action="{{ route('friendships.reject', $friendship) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-black/50 underline">tolak</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-black/50">Tidak ada permintaan baru.</p>
                    @endforelse
                </div>
            </section>

            {{-- Outgoing Requests --}}
            <section>
                <div class="mb-6">
                    <p class="mb-2 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
                        outgoing
                    </p>
                    <h2 class="display text-3xl">menunggu jawaban.</h2>
                </div>
                <div class="space-y-3">
                    @forelse($outgoing as $friendship)
                        <div class="flex items-center justify-between border border-black/10 bg-white/50 p-5">
                            <div>
                                <p class="font-semibold">{{ $friendship->addressee->name }}</p>
                                <p class="text-sm text-black/45">
                                    <span aria-hidden="true">@</span>{{ $friendship->addressee->username }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('friendships.reject', $friendship) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs text-black/45 underline">batalkan</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-black/50">Tidak ada request terkirim.</p>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- Suggestions / Discover Section --}}
        <section class="mt-16">
            <div class="mb-6">
                <p class="mb-2 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">
                    discover
                </p>
                <h2 class="display text-3xl">mungkin kamu kenal.</h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @forelse($suggestions as $user)
                    <div class="border border-black/10 bg-[#d9d5f2] p-5">
                        <p class="font-semibold">{{ $user->name }}</p>
                        <p class="mb-5 text-sm text-black/45">
                            <span aria-hidden="true">@</span>{{ $user->username }}
                        </p>
                        <form method="POST" action="{{ route('friends.send', $user) }}">
                            @csrf
                            <button class="text-sm font-semibold underline">
                                kirim permintaan ↗
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-black/50">Belum ada user lain untuk ditemukan.</p>
                @endforelse
            </div>
        </section>

    </main>
@endsection
