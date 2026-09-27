<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    // Membuat kartu baru; hanya user login aktif yang dapat mengakses method ini.
    public function store(Request $request): RedirectResponse
    {
        abort_if($request->user()->is_banned, 403, 'Akun ini sedang diblokir.');
        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'time_caption' => ['required', 'string', 'max:120'],
            'signature' => ['required', 'string', 'max:120'],
            'tone' => ['required', 'in:lavender,butter,mint,coral'],
            'background' => ['required', 'in:paper,vintage,night,pastel'],
            'font_style' => ['required', 'in:hand,typewriter,bold'],
            'sticker' => ['nullable', 'in:heart,flower,star'],
            'category' => ['required', 'in:Bucin,Patah Hati,Motivasi,Minta Maaf,Rahasia'],
        ]);

        // Sensor dijalankan sebelum teks disimpan dan masuk ke tahap moderasi admin.
        $data['message'] = $this->censor($data['message']);
        $request->user()->cards()->create($data);
        return back()->with('success', 'Kartu berhasil dikirim dan menunggu moderasi.');
    }

    public function like(Request $request, Card $card): RedirectResponse
    {
        // ID kartu yang sudah di-like disimpan di session agar satu browser tidak spam like.
        $liked = $request->session()->get('liked_cards', []);
        if (! in_array($card->id, $liked, true)) {
            $card->increment('likes_count');
            $liked[] = $card->id;
            $request->session()->put('liked_cards', $liked);
        }
        return back();
    }

    public function comment(Request $request, Card $card): RedirectResponse
    {
        // user_id boleh null karena komentar memang dapat dibuat secara anonim.
        $data = $request->validate(['message' => ['required', 'string', 'max:500']]);
        Comment::create(['card_id' => $card->id, 'user_id' => $request->user()?->id, 'message' => $this->censor($data['message'])]);
        return back()->with('success', 'Balasanmu sudah ditempelkan di kartu.');
    }

    public function report(Request $request, Card $card): RedirectResponse
    {
        // Laporan masuk ke dashboard admin dengan status pending.
        $data = $request->validate(['reason' => ['required', 'string', 'max:120']]);
        Report::create(['card_id' => $card->id, 'user_id' => $request->user()?->id, 'reason' => $data['reason']]);
        return back()->with('success', 'Laporan diterima dan akan ditinjau admin.');
    }

    public function destroy(Card $card): RedirectResponse
    {
        abort_unless($card->user_id === auth()->id(), 403);
        $card->delete();
        return back()->with('success', 'Kartu dihapus dari koleksimu.');
    }

    private function censor(string $message): string
    {
        // Daftar ini dapat dipindahkan ke config atau database jika ingin dikelola admin.
        return preg_replace('/\b(bodoh|bangsat|brengsek|tolol|anjing)\b/iu', '***', $message) ?? $message;
    }
}
