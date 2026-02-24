<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // Lokasi: app/Http/Controllers/Auth/AuthenticatedSessionController.php

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- TAMBAHKAN LOGIKA REDIRECT BERDASARKAN ROLE DI SINI ---
        $role = $request->user()->role;
        
        if ($role === 'superadmin') {
            return redirect()->intended(route('superadmin.dashboard'));
        } elseif ($role === 'guru') {
            return redirect()->intended(route('guru.dashboard'));
        } else {
            // Defaultnya ke murid
            return redirect()->intended(route('murid.dashboard'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
