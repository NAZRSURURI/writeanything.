<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        abort_if(auth()->user()->is_banned, 403, 'Akun ini sedang diblokir.');
        return view('dashboard', ['cards' => auth()->user()->cards()->latest()->get()]);
    }
}
