<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    // Dashboard admin menggabungkan statistik, ranking, kartu, dan laporan.
    public function index(): View
    {
        $this->guard();
        return view('admin.index', [
            'users' => User::latest()->get(),
            'cards' => Card::with('user')->latest()->get(),
            'reports' => Report::with('card.user')->where('status', 'pending')->latest()->get(),
            'ranking' => User::withCount('cards')->orderByDesc('cards_count')->orderBy('name')->take(10)->get(),
            'stats' => [
                'users' => User::count(),
                'today' => User::whereDate('created_at', today())->count(),
                'cards' => Card::count(),
                'pending' => Card::where('status', 'pending')->count(),
                'reports' => Report::where('status', 'pending')->count(),
            ],
        ]);
    }

    public function toggleBan(User $user): RedirectResponse
    {
        // Ban tidak memiliki tanggal kedaluwarsa; hanya admin yang dapat membukanya kembali.
        $this->guard();
        abort_if($user->is(auth()->user()), 422, 'Admin tidak dapat memblokir dirinya sendiri.');
        $user->update(['is_banned' => ! $user->is_banned]);
        return back()->with('success', $user->is_banned ? 'User diblokir.' : 'Blokir user dibuka.');
    }

    public function deleteUser(User $user): RedirectResponse
    {
        $this->guard();
        abort_if($user->is(auth()->user()), 422, 'Admin tidak dapat menghapus dirinya sendiri.');
        $user->delete();
        return back()->with('success', 'User dihapus.');
    }

    public function moderate(Card $card, string $status): RedirectResponse
    {
        // Hanya dua status moderasi yang valid: approved atau rejected.
        $this->guard();
        abort_unless(in_array($status, ['approved', 'rejected'], true), 404);
        $card->update(['status' => $status]);
        return back()->with('success', $status === 'approved' ? 'Kartu dipublikasikan.' : 'Kartu ditolak.');
    }

    public function updateCard(Request $request, Card $card): RedirectResponse
    {
        $this->guard();
        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'time_caption' => ['required', 'string', 'max:120'],
            'signature' => ['required', 'string', 'max:120'],
        ]);
        $card->update($data);
        return redirect()->route('admin.index')->with('success', 'Catatan berhasil diedit.');
    }

    public function deleteCard(Card $card): RedirectResponse
    {
        $this->guard();
        $card->delete();
        return back()->with('success', 'Catatan dihapus dari sistem.');
    }

    public function togglePin(Card $card): RedirectResponse
    {
        // Pinned card diprioritaskan di wall publik sebagai pengumuman atau highlight.
        $this->guard();
        $card->update(['is_pinned' => ! $card->is_pinned]);
        return back()->with('success', $card->is_pinned ? 'Catatan dipasang sebagai pinned.' : 'Pinned card dilepas.');
    }

    public function resolveReport(Report $report): RedirectResponse
    {
        $this->guard();
        $report->update(['status' => 'resolved']);
        return back()->with('success', 'Laporan ditandai selesai.');
    }

    private function guard(): void
    {
        // Semua aksi admin ditolak jika session bukan role admin.
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }
}
