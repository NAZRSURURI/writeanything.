<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    // Menampilkan form pendaftaran user baru.
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'username' => ['required', 'string', 'alpha_dash', 'max:40', 'unique:users,username'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Password akan di-hash otomatis oleh cast model User.
        $user = User::create($data);
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Selamat datang di WriteAnything.');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);

        // Auth::attempt membandingkan email dan password dengan data database.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
        }

        // User yang diban tidak boleh membuat session login baru.
        if (Auth::user()->is_banned) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun ini sedang diblokir.']);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        // Invalidasi session mencegah session lama dipakai kembali setelah logout.
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
