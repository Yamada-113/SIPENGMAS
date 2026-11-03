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
    public function create(): View
    {
        return view('auth.login');
    }
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();
        if ($user->role === 'Dosen') {
            return redirect()->route('dosen.dashboard');
        } elseif ($user->role === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'Wakil Dekan 3') {
            return redirect()->route('wakil_dekan3.dashboard');
        } elseif ($user->role === 'Dekan') {
            return redirect()->route('dekan.dashboard');
        } elseif ($user->role === 'Kepala Pusat 1' || $user->role === 'Kepala Pusat 2' || $user->role === 'Kepala Pusat 3' || $user->role === 'Kepala Pusat 4' || $user->role === 'Kepala Pusat 5') {
            return redirect()->route('kepala_pusat.dashboard');
        } elseif ($user->role === 'Wakil Rektor 3') {
            return redirect()->route('wakil_rektor.dashboard');
        }
        return redirect()->intended(route('dosen.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
