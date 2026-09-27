<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FriendshipController;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

// Wall publik: hanya kartu approved yang ditampilkan dan bisa difilter pengunjung.
Route::get('/', function (Request $request) {
    $cards = Schema::hasTable('cards')
        ? Card::with('comments')->where('status', 'approved')->orderByDesc('is_pinned')->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))->when($request->string('sort')->value() === 'popular', fn ($query) => $query->orderByDesc('likes_count'))->when($request->string('sort')->value() === 'random', fn ($query) => $query->inRandomOrder())->when($request->string('sort')->value() !== 'random' && $request->string('sort')->value() !== 'popular', fn ($query) => $query->latest())->get()
        : collect();

    return view('home', compact('cards'));
})->name('home');

// Halaman auth hanya dapat dibuka oleh pengunjung yang belum login.
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Interaksi publik boleh dilakukan guest maupun user login.
Route::post('/cards/{card}/like', [CardController::class, 'like'])->name('cards.like');
Route::post('/cards/{card}/comment', [CardController::class, 'comment'])->name('cards.comment');
Route::post('/cards/{card}/report', [CardController::class, 'report'])->name('cards.report');

// Semua route di bawah ini memerlukan session login.
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/friends', [FriendshipController::class, 'index'])->name('friends.index');
    Route::post('/friends/{user}', [FriendshipController::class, 'send'])->name('friends.send');
    Route::patch('/friendships/{friendship}/accept', [FriendshipController::class, 'accept'])->name('friendships.accept');
    Route::delete('/friendships/{friendship}', [FriendshipController::class, 'reject'])->name('friendships.reject');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    // Route admin tetap melakukan pengecekan role di AdminController.
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::patch('/users/{user}/toggle-ban', [AdminController::class, 'toggleBan'])->name('users.toggle-ban');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
        Route::get('/cards/{card}/edit', function (Card $card) {
            abort_unless(auth()->user()->role === 'admin', 403);
            return view('admin.edit-card', compact('card'));
        })->name('cards.edit');
        Route::patch('/cards/{card}/{status}', [AdminController::class, 'moderate'])->name('cards.moderate');
        Route::put('/cards/{card}', [AdminController::class, 'updateCard'])->name('cards.update');
        Route::delete('/cards/{card}', [AdminController::class, 'deleteCard'])->name('cards.destroy');
        Route::patch('/cards/{card}/pin', [AdminController::class, 'togglePin'])->name('cards.pin');
        Route::patch('/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('reports.resolve');
    });
});
