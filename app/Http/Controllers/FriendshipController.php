<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FriendshipController extends Controller
{
    // Menyiapkan tiga kelompok data: teman aktif, request masuk, dan request keluar.
    public function index(Request $request): View
    {
        $this->ensureActive();
        $userId = $request->user()->id;
        $friendships = Friendship::with(['requester', 'addressee'])->where('status', 'accepted')->where(function ($query) use ($userId) {
            $query->where('requester_id', $userId)->orWhere('addressee_id', $userId);
        })->latest()->get();
        $incoming = Friendship::with('requester')->where('addressee_id', $userId)->where('status', 'pending')->latest()->get();
        $outgoing = Friendship::with('addressee')->where('requester_id', $userId)->where('status', 'pending')->latest()->get();
        $connectedIds = Friendship::where(function ($query) use ($userId) {
            $query->where('requester_id', $userId)->orWhere('addressee_id', $userId);
        })->pluck('requester_id')->merge(Friendship::where(function ($query) use ($userId) {
            $query->where('requester_id', $userId)->orWhere('addressee_id', $userId);
        })->pluck('addressee_id'))->push($userId)->unique();
        $suggestions = User::whereNotIn('id', $connectedIds)->where('is_banned', false)->latest()->take(12)->get();

        return view('friends.index', compact('friendships', 'incoming', 'outgoing', 'suggestions'));
    }

    public function send(Request $request, User $user): RedirectResponse
    {
        // Satu hubungan hanya boleh memiliki satu record, apa pun arah request-nya.
        $this->ensureActive();
        abort_if($user->is($request->user()) || $user->is_banned, 422, 'User tidak dapat ditambahkan.');
        $existing = Friendship::where(function ($query) use ($request, $user) {
            $query->where('requester_id', $request->user()->id)->where('addressee_id', $user->id);
        })->orWhere(function ($query) use ($request, $user) {
            $query->where('requester_id', $user->id)->where('addressee_id', $request->user()->id);
        })->first();
        if ($existing) {
            return back()->with('success', 'Permintaan pertemanan sudah ada.');
        }
        Friendship::create(['requester_id' => $request->user()->id, 'addressee_id' => $user->id]);
        return back()->with('success', 'Permintaan pertemanan terkirim.');
    }

    public function accept(Request $request, Friendship $friendship): RedirectResponse
    {
        // Hanya pemilik request masuk yang boleh menerima permintaan tersebut.
        abort_unless($friendship->addressee_id === $request->user()->id && $friendship->status === 'pending', 403);
        $friendship->update(['status' => 'accepted']);
        return back()->with('success', 'Sekarang kalian berteman.');
    }

    public function reject(Request $request, Friendship $friendship): RedirectResponse
    {
        // Reject juga dipakai untuk membatalkan request keluar atau menghapus teman.
        abort_unless(in_array($request->user()->id, [$friendship->requester_id, $friendship->addressee_id], true), 403);
        $friendship->delete();
        return back()->with('success', 'Permintaan pertemanan dihapus.');
    }

    private function ensureActive(): void
    {
        // Akun banned tidak boleh menggunakan fitur sosial.
        abort_if(auth()->user()->is_banned, 403, 'Akun ini sedang diblokir.');
    }
}
