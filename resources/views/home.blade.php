<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WriteAnything adalah ruang kecil untuk pesan yang belum sempat disampaikan.">
    <title>WriteAnything | leave a little note</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">

    <style>
        :root {
            color-scheme: light;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #f7f3ec;
            color: #242320;
            font-family: 'DM Sans', sans-serif;
        }

        .display {
            font-family: 'Fraunces', serif;
        }

        .hand {
            font-family: 'Caveat', cursive;
        }

        .note {
            transition: transform .3s ease, box-shadow .3s ease;
            box-shadow: 0 18px 40px rgba(45, 39, 30, .1);
        }
        .note:hover {
            transform: rotate(0deg) translateY(-8px) !important;
            box-shadow: 0 24px 45px rgba(45, 39, 30, .16);
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .rise {
            animation: rise .8s both;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    {{-- Homepage berisi hero, contoh kartu, wall publik, filter, dan CTA untuk membuat kartu. --}}

    <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-7 lg:px-10">
        <a href="{{ route('home') }}" class="display text-xl font-semibold tracking-tight">
            write<span class="text-[#9d8ed0]">anything</span><span class="text-[#db9275]">.</span>
        </a>

        <div class="hidden gap-8 text-sm font-medium text-black/55 md:flex">
            <a href="#about" class="hover:text-black">about</a>
            <a href="#notes" class="hover:text-black">explore notes</a>
        </div>

        <div class="flex items-center gap-3 text-sm font-semibold">
            @auth
                <a href="{{ route('dashboard') }}" class="text-black/65 hover:text-black">dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button class="text-black/45 hover:text-black">keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden text-black/65 hover:text-black sm:block">masuk</a>
                <a href="{{ route('register') }}" class="rounded-full bg-[#242320] px-4 py-2.5 text-white transition hover:-translate-y-0.5">daftar</a>
            @endauth
            <a href="#write" class="rounded-full border border-black/15 bg-white/50 px-5 py-2.5 transition hover:-translate-y-0.5 hover:border-black/40">
                write a note <span class="ml-1">↗</span>
            </a>
        </div>
    </nav>

    <main>
        {{-- Hero Section --}}
        <section class="mx-auto grid max-w-6xl items-center gap-12 px-6 pb-24 pt-14 lg:grid-cols-[1.05fr_.95fr] lg:px-10 lg:pb-32 lg:pt-20">
            <div class="rise">
                <p class="mb-6 flex items-center gap-3 text-xs font-bold uppercase tracking-[.22em] text-black/45">
                    <span class="h-px w-8 bg-[#db9275]"></span> a quiet place on the internet
                </p>
                <h1 class="display max-w-xl text-6xl leading-[.95] tracking-[-.045em] sm:text-7xl lg:text-[6.8rem]">
                    write what <em class="hand font-medium text-[#9d8ed0]">you</em> can’t say.
                </h1>
                <p class="mt-8 max-w-md text-base leading-7 text-black/60">
                    Ruang kecil untuk pesan, kenangan, dan kata-kata yang ingin kamu titipkan kepada semesta. Tanpa nama. Tanpa tekanan.
                </p>
                <a href="#write" class="mt-10 inline-block rounded-full bg-[#242320] px-6 py-3.5 text-sm font-semibold text-white transition hover:-translate-y-1">
                    leave a note <span class="ml-2">↗</span>
                </a>
                <p class="mt-12 text-xs text-black/45">1,284 notes left by kind strangers</p>
            </div>

            {{-- Floating Cards --}}
            <div class="relative mx-auto h-[390px] w-full max-w-[500px] sm:h-[450px]">
                <div class="absolute left-[8%] top-[8%] h-64 w-56 rotate-[-9deg] bg-[#d9d5f2] p-7 shadow-xl">
                    <span class="hand text-2xl leading-tight">you made it this far. that counts for something.</span>
                    <p class="absolute bottom-7 text-[9px] uppercase tracking-[.18em] text-black/40">october / 23</p>
                </div>
                <div class="absolute bottom-[4%] right-[7%] h-72 w-64 rotate-[7deg] bg-[#f8dda0] p-8 shadow-xl">
                    <span class="hand text-[1.7rem] leading-tight">somewhere, someone is glad you exist.</span>
                    <p class="absolute bottom-8 text-[9px] uppercase tracking-[.18em] text-black/40">a little reminder</p>
                </div>
                <div class="absolute left-[24%] top-[22%] z-10 flex h-64 w-60 -rotate-[1deg] flex-col justify-between bg-white p-8 shadow-2xl">
                    <div>
                        <span class="mb-5 block text-[9px] font-bold uppercase tracking-[.2em] text-[#db9275]">from an almost stranger</span>
                        <span class="hand text-[2rem] leading-[.95]">i hope the next chapter is gentle with you.</span>
                    </div>
                    <p class="text-[9px] uppercase tracking-[.18em] text-black/40">writeanything / 001</p>
                </div>
            </div>
        </section>

        {{-- Highlight Notes Section --}}
        <section id="notes" class="border-y border-black/10 bg-[#eeeadf] px-6 py-20 lg:px-10 lg:py-28">
            <div class="mx-auto max-w-6xl">
                <div class="mb-12 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                    <div>
                        <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">the public wall</p>
                        <h2 class="display text-4xl tracking-[-.03em] sm:text-5xl">notes from nowhere.</h2>
                    </div>
                    <p class="max-w-xs text-sm leading-6 text-black/50">
                        A small collection of things people needed to put somewhere.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <article class="note flex min-h-[300px] rotate-[-2deg] flex-col justify-between bg-[#d9d5f2] p-8">
                        <div>
                            <p class="mb-7 text-[9px] font-bold uppercase tracking-[.2em] text-black/45">september 2008</p>
                            <p class="hand text-[2rem] leading-[1.05]">aku masih menyimpan caramu tertawa di kepala. semoga semesta menjagamu baik-baik.</p>
                        </div>
                        <p class="text-xs font-semibold text-black/45">— from planet kecil</p>
                    </article>

                    <article class="note flex min-h-[300px] rotate-[1.5deg] flex-col justify-between bg-[#f8dda0] p-8">
                        <div>
                            <p class="mb-7 text-[9px] font-bold uppercase tracking-[.2em] text-black/45">a quiet afternoon</p>
                            <p class="hand text-[2rem] leading-[1.05]">untuk seseorang yang pernah membuat hari senin terasa seperti minggu pagi.</p>
                        </div>
                        <p class="text-xs font-semibold text-black/45">— anonymous</p>
                    </article>

                    <article class="note flex min-h-[300px] rotate-[-1deg] flex-col justify-between bg-[#cfe2d4] p-8">
                        <div>
                            <p class="mb-7 text-[9px] font-bold uppercase tracking-[.2em] text-black/45">notes from 2016</p>
                            <p class="hand text-[2rem] leading-[1.05]">terima kasih sudah singgah. beberapa pertemuan memang tidak perlu selamanya untuk berarti.</p>
                        </div>
                        <p class="text-xs font-semibold text-black/45">— someone who remembers</p>
                    </article>
                </div>
            </div>
        </section>

        {{-- About Section --}}
        <section id="about" class="mx-auto flex max-w-6xl flex-col gap-8 px-6 py-20 sm:flex-row sm:items-center sm:justify-between lg:px-10">
            <p class="display text-3xl leading-tight sm:max-w-md">
                No profile. No performance.<br>
                <em class="hand text-[#9d8ed0]">Just words.</em>
            </p>
            <p class="max-w-sm text-sm leading-7 text-black/55">
                WriteAnything dibuat untuk hal-hal kecil yang terlalu berarti untuk dilupakan, tapi terlalu sulit untuk dikatakan langsung.
            </p>
        </section>

        {{-- Freshly Approved Section (Dynamic) --}}
        @if($cards->isNotEmpty())
            <section class="border-t border-black/10 px-6 py-20 lg:px-10">
                <div class="mx-auto max-w-6xl">
                    <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">freshly approved</p>
                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach ($cards as $card)
                            <article class="flex min-h-[260px] flex-col justify-between bg-[#d9d5f2] p-7">
                                <div>
                                    <p class="mb-6 text-[9px] font-bold uppercase tracking-[.2em] text-black/45">
                                        {{ $card->time_caption }}
                                    </p>
                                    <p class="hand text-[1.8rem] leading-tight">{{ $card->message }}</p>
                                </div>
                                <p class="text-xs font-semibold text-black/45">— {{ $card->signature }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- Explore / Interactive Wall Section --}}
        <section id="explore" class="border-t border-black/10 px-6 py-20 lg:px-10">
            <div class="mx-auto max-w-6xl">

                {{-- Filters --}}
                <div class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                    <div>
                        <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">interactive wall</p>
                        <h2 class="display text-4xl">temukan catatanmu.</h2>
                    </div>

                    <form method="GET" action="{{ route('home') }}" class="flex flex-wrap gap-2">
                        <select name="category" class="border border-black/15 bg-white/60 px-3 py-2 text-sm">
                            <option value="">semua kategori</option>
                            @php
                                $categories = ['Bucin', 'Patah Hati', 'Motivasi', 'Minta Maaf', 'Rahasia'];
                            @endphp
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(request('category') === $category)>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        <select name="sort" class="border border-black/15 bg-white/60 px-3 py-2 text-sm">
                            <option value="latest" @selected(request('sort', 'latest') === 'latest')>terbaru</option>
                            <option value="popular" @selected(request('sort') === 'popular')>terpopuler</option>
                            <option value="random" @selected(request('sort') === 'random')>acak</option>
                        </select>
                        <button class="bg-[#242320] px-4 py-2 text-sm font-semibold text-white">filter</button>
                    </form>
                </div>

                {{-- Dynamic Cards List --}}
                <div class="grid gap-6 md:grid-cols-3">
                    @forelse ($cards as $card)
                        @php
                            $backgroundClass = match ($card->tone) {
                                'butter' => 'bg-[#f8dda0]',
                                'mint' => 'bg-[#cfe2d4]',
                                'coral' => 'bg-[#f3cec2]',
                                default => 'bg-[#d9d5f2]',
                            };

                            $fontClass = match ($card->font_style) {
                                'typewriter' => 'font-mono text-lg',
                                'bold' => 'font-sans text-xl font-bold',
                                default => 'hand text-[1.8rem]',
                            };

                            $stickerSymbol = match ($card->sticker) {
                                'heart' => '♡',
                                'flower' => '✿',
                                default => '✦',
                            };
                        @endphp

                        <article class="relative flex min-h-[300px] flex-col justify-between p-7 {{ $backgroundClass }}">
                            <div>
                                <div class="mb-6 flex items-center justify-between text-[9px] font-bold uppercase tracking-[.18em] text-black/45">
                                    <span>{{ $card->category }}</span>
                                    @if ($card->is_pinned)
                                        <span class="text-[#a07826]">pinned ✦</span>
                                    @endif
                                </div>
                                <p class="{{ $fontClass }} leading-tight">
                                    {{ $card->message }}
                                    @if ($card->sticker)
                                        <span>{{ $stickerSymbol }}</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="mb-3 text-xs text-black/45">
                                    {{ $card->time_caption }} · — {{ $card->signature }}
                                </p>
                                <div class="flex items-center gap-3">
                                    <form method="POST" action="{{ route('cards.like', $card) }}">
                                        @csrf
                                        <button class="text-sm font-semibold">♡ {{ $card->likes_count }}</button>
                                    </form>

                                    <details class="text-xs">
                                        <summary class="cursor-pointer font-semibold">balas</summary>
                                        <form method="POST" action="{{ route('cards.comment', $card) }}" class="mt-2 flex gap-1">
                                            @csrf
                                            <input name="message" required maxlength="500" placeholder="tulis dukungan..." class="w-36 border border-black/15 bg-white/50 px-2 py-1">
                                            <button class="bg-black px-2 py-1 text-white">kirim</button>
                                        </form>
                                    </details>

                                    <details class="text-xs">
                                        <summary class="cursor-pointer text-black/45">laporkan</summary>
                                        <form method="POST" action="{{ route('cards.report', $card) }}" class="mt-2 flex gap-1">
                                            @csrf
                                            <input name="reason" required maxlength="120" placeholder="alasan" class="w-24 border border-black/15 bg-white/50 px-2 py-1">
                                            <button class="bg-[#a45f4d] px-2 py-1 text-white">kirim</button>
                                        </form>
                                    </details>
                                </div>

                                @if ($card->comments->isNotEmpty())
                                    <div class="mt-4 border-l border-black/20 pl-3 text-xs text-black/55">
                                        “{{ $card->comments->first()->message }}”
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-black/50">Belum ada kartu yang cocok dengan filter ini.</p>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- CTA Write Section --}}
        <section id="write" class="border-t border-black/10 px-6 py-20 lg:px-10">
            <div class="mx-auto max-w-2xl">
                <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-[#db9275]">your little note</p>
                <h2 class="display text-4xl">leave something kind.</h2>

                @auth
                    <a href="{{ route('dashboard') }}#new-card" class="mt-8 inline-block bg-[#242320] px-6 py-4 text-sm font-semibold text-white">
                        buat kartu di dashboard <span class="ml-2">↗</span>
                    </a>
                @else
                    <p class="mt-4 max-w-md text-sm leading-6 text-black/55">
                        Daftar atau masuk terlebih dahulu untuk membuat dan mengirim catatan.
                    </p>
                    <div class="mt-8 flex gap-3">
                        <a href="{{ route('register') }}" class="bg-[#242320] px-6 py-4 text-sm font-semibold text-white">
                            daftar untuk menulis
                        </a>
                        <a href="{{ route('login') }}" class="border border-black/15 px-6 py-4 text-sm font-semibold">
                            sudah punya akun
                        </a>
                    </div>
                @endauth
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="mx-auto flex max-w-6xl justify-between border-t border-black/10 px-6 py-8 text-xs text-black/40 lg:px-10">
        <span>© 2026 writeanything</span>
        <span>made for the unsaid.</span>
    </footer>

    {{-- Scripts --}}
    <script>
        async function exportWriteCard(card) {
            const canvas = await html2canvas(card, {
                backgroundColor: null,
                scale: 2,
                useCORS: true,
            });
            const link = document.createElement('a');
            link.download = 'writeanything-card.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        async function shareWriteCard(card) {
            const canvas = await html2canvas(card, {
                backgroundColor: null,
                scale: 2,
                useCORS: true,
            });
            canvas.toBlob(async (blob) => {
                const file = new File([blob], 'writeanything-card.png', { type: 'image/png' });

                if (navigator.share && (!navigator.canShare || navigator.canShare({ files: [file] }))) {
                    await navigator.share({
                        title: 'WriteAnything',
                        text: 'A little note from WriteAnything',
                        files: [file],
                    });
                } else {
                    await exportWriteCard(card);
                    alert('PNG sudah diunduh. Buka Instagram atau TikTok untuk mengunggahnya.');
                }
            }, 'image/png');
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('main article').forEach((card, index) => {
                card.id = card.id || `write-card-${index}`;

                const actions = document.createElement('div');
                actions.dataset.html2canvasIgnore = 'true';
                actions.className = 'mt-4 flex gap-2 text-xs';
                const downloadButton = document.createElement('button');
                downloadButton.type = 'button';
                downloadButton.className = 'underline';
                downloadButton.textContent = 'download PNG';
                downloadButton.addEventListener('click', () => exportWriteCard(card));

                const shareButton = document.createElement('button');
                shareButton.type = 'button';
                shareButton.className = 'underline';
                shareButton.textContent = 'share ke Instagram/TikTok';
                shareButton.addEventListener('click', () => shareWriteCard(card));

                actions.append(downloadButton, shareButton);

                card.append(actions);
            });
        });
    </script>
</body>
</html>
