<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WriteAnything' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">

    <style>
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
    </style>
</head>
<body>
    {{-- Navbar berubah berdasarkan status login: guest melihat masuk/daftar, user melihat dashboard/teman. --}}
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-7 lg:px-10">
        <a href="{{ route('home') }}" class="display text-xl font-semibold">
            write<span class="text-[#9d8ed0]">anything</span><span class="text-[#db9275]">.</span>
        </a>

        <div class="flex items-center gap-3 text-sm">
            @auth
                <a href="{{ route('dashboard') }}" class="font-semibold">dashboard</a>
                <a href="{{ route('friends.index') }}" class="font-semibold">teman</a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="rounded-full bg-[#242320] px-4 py-2 text-white">admin</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-black/50 hover:text-black">keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-black/60 hover:text-black">masuk</a>
                <a href="{{ route('register') }}" class="rounded-full bg-[#242320] px-4 py-2 text-white">daftar</a>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div class="mx-auto max-w-6xl px-6">
            <div class="border border-[#94bca0] bg-[#e4f0e6] px-4 py-3 text-sm text-[#42624b]">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto max-w-6xl px-6">
            <div class="border border-[#d99886] bg-[#f9e8e2] px-4 py-3 text-sm text-[#8c4d3e]">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    {{-- Setiap halaman turunan mengisi area utama melalui section content. --}}
    @yield('content')

    {{-- html2canvas mengubah elemen kartu menjadi PNG; Web Share API membuka share sheet perangkat. --}}
    <script>
        async function exportWriteCard(card) {
            const canvas = await html2canvas(card, {
                backgroundColor: null,
                scale: 2,
                useCORS: true
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
                useCORS: true
            });

            canvas.toBlob(async (blob) => {
                const file = new File([blob], 'writeanything-card.png', { type: 'image/png' });

                if (navigator.share && (!navigator.canShare || navigator.canShare({ files: [file] }))) {
                    await navigator.share({
                        title: 'WriteAnything',
                        text: 'A little note from WriteAnything',
                        files: [file]
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
                actions.innerHTML = `
                    <button type="button" class="underline" data-export>download PNG</button>
                    <button type="button" class="underline" data-share>share ke Instagram/TikTok</button>
                `;

                actions.querySelector('[data-export]').addEventListener('click', () => exportWriteCard(card));
                actions.querySelector('[data-share]').addEventListener('click', () => shareWriteCard(card));

                card.append(actions);
            });
        });
    </script>
</body>
</html>
