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
    public function store(LoginRequest $request): RedirectResponse
    {
        // Proses autentikasi user
        $request->authenticate();

        // Regenerasi session ID untuk mencegah serangan session fixation
        $request->session()->regenerate();

        $user = $request->user();
        
        // Pengecekan role pengguna dan arahkan (redirect) ke dashboard yang sesuai
        if ($user->hasRole('superadmin')) {
            return redirect()->intended(route('superadmin.dashboard', absolute: false));
        } elseif ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } else {
            // Jika user tidak memiliki role khusus (karyawan biasa)
            return redirect()->intended(route('user.dashboard', absolute: false));
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
