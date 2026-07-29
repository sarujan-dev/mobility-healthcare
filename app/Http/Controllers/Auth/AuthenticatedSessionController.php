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

        // Check doctor approval status
        if ($user->role === 'doctor' && $user->status === 'pending') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->withErrors([
                'email' => 'Your account is pending admin approval.',
            ]);
        }

        if ($user->status === 'blocked') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->withErrors([
                'email' => 'Your account has been blocked. Contact admin.',
            ]);
        }

        return match($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'doctor' => redirect('/doctor/dashboard'),
            'patient' => redirect('/patient/dashboard'),
            default => redirect('/'),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
