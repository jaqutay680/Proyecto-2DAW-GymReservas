<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();

            // 🔹 Validar membresía activa
            if (!in_array($user->membership_status, ['active', 'pending'])) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Tu cuenta está suspendida. Contacta con administración.');
            }

            // 🔹 Validar edad mínima
            if ($user->birth_date && $user->birth_date->age < 16) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Debes tener al menos 16 años para acceder.');
            }

            // 🔹 Redirección por rol
            if ($user->role === 'admin')
                return redirect()->to('/admin');
            if ($user->role === 'trainer')
                return redirect()->to('/trainer');

            return redirect()->intended(route('dashboard', false));

        } catch (\Throwable $e) {
            \Log::error('Login fallido: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al iniciar sesión. Inténtalo de nuevo.');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}