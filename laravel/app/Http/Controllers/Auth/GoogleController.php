<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ViewErrorBag;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // ── Redirige al login de Google ──────────────────────────────────────────
    public function redirect()
    {
        // Stateless: no guarda el state en la sesión-cookie (evita overflow y
        // problemas SameSite en hosting compartido con SESSION_DRIVER=cookie)
        return Socialite::driver('google')->stateless()->redirect();
    }

    // ── Google llama aquí tras autenticar ────────────────────────────────────
    public function callback()
    {
        // Dar suficiente tiempo para la petición HTTP a Google (intercambio de token)
        set_time_limit(60);

        try {
            // stateless: no verifica el state en sesión (consistente con redirect())
            // Guzzle con timeout corto para fallar rápido si Google no responde
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();
        } catch (\Throwable $e) {
            Log::error('Google OAuth callback error', [
                'class'   => get_class($e),
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', 'No se pudo conectar con Google. Inténtalo de nuevo.');
        }

        // Buscar usuario existente por google_id primero, luego por email
        $user = DB::table('gym_users')
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', strtolower($googleUser->getEmail()))
            ->first();

        if ($user) {
            // Si existía por email pero sin google_id, vinculamos la cuenta
            if (!$user->google_id) {
                DB::table('gym_users')
                    ->where('id', $user->id)
                    ->update(['google_id' => $googleUser->getId(), 'updated_at' => now()]);
            }
        } else {
            // Usuario nuevo: crear cuenta mínima, perfil pendiente de completar
            $userId = DB::table('gym_users')->insertGetId([
                'name'              => $googleUser->getName(),
                'email'             => strtolower($googleUser->getEmail()),
                'google_id'         => $googleUser->getId(),
                'password'          => bcrypt(\Illuminate\Support\Str::random(32)), // password aleatorio, no se usará
                'role'              => 'cliente',
                'plan_type'         => 'free',
                'membership_status' => 'active',
                'wallet_balance'    => 0,
                'free_trial_used'   => 0,
                'profile_completed' => 0,  // ← obliga a completar perfil
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            $user = DB::table('gym_users')->find($userId);
        }

        // Login
        Auth::loginUsingId($user->id);

        // Si el perfil no está completo, redirigir a completar-perfil
        if (!($user->profile_completed ?? 1)) {
            return redirect()->route('profile.complete.form');
        }

        return redirect()->route('dashboard');
    }

    // ── Formulario: completar perfil (DNI + fecha + plan) ────────────────────
    public function completeProfileForm()
    {
        return view('auth.complete-profile', ['input' => []]);
    }

    // ── Guardar perfil completo ───────────────────────────────────────────────
    public function completeProfileStore(Request $request)
    {
        $user  = Auth::user();
        $input = $request->only(['dni', 'birth_date', 'plan_type']);

        $v = Validator::make($request->all(), [
            'dni'        => ['required', 'string', 'max:9', 'unique:gym_users,dni,' . $user->id, $this->dniRule()],
            'birth_date' => 'required|date|before:' . now()->subYears(16)->format('Y-m-d'),
            'plan_type'  => 'required|in:free,basico,premium',
        ], [
            'dni.required'       => 'El DNI es obligatorio.',
            'dni.unique'         => 'Ese DNI ya está registrado en otra cuenta.',
            'birth_date.required'=> 'La fecha de nacimiento es obligatoria.',
            'birth_date.before'  => 'Debes tener al menos 16 años.',
            'plan_type.required' => 'Selecciona un plan.',
        ]);

        if ($v->fails()) {
            // Sin redirect/withInput → no toca la cookie de sesión
            $bag = new ViewErrorBag;
            $bag->put('default', $v->errors());
            return response(view('auth.complete-profile', ['input' => $input, 'errors' => $bag])->render());
        }

        $plan = $request->input('plan_type');

        // Actualizar usuario
        DB::table('gym_users')->where('id', $user->id)->update([
            'dni'               => strtoupper(trim($request->input('dni'))),
            'birth_date'        => $request->input('birth_date'),
            'plan_type'         => $plan,
            'profile_completed' => 1,
            'updated_at'        => now(),
        ]);

        // Si elige plan de pago, registrar pago y suscripción
        if (in_array($plan, ['basico', 'premium'])) {
            $amount = $plan === 'premium' ? 19.99 : 9.99;

            DB::table('gym_payments')->insert([
                'user_id'      => $user->id,
                'amount'       => $amount,
                'currency'     => 'EUR',
                'payment_date' => now(),
                'status'       => 'paid',
                'plan_type'    => $plan,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            DB::table('gym_subscriptions')->insert([
                'user_id'           => $user->id,
                'plan_type'         => $plan,
                'status'            => 'active',
                'started_at'        => now(),
                'next_billing_date' => now()->addMonth()->format('Y-m-d'),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        return redirect()->route('dashboard')->with('success', '¡Bienvenido a GymReservas!');
    }

    // ── Validación DNI español (mod-23) ──────────────────────────────────────
    private function dniRule(): \Closure
    {
        return function ($attr, $val, $fail) {
            if (empty($val)) return;
            $val = strtoupper(trim($val));
            if (!preg_match('/^[0-9]{8}[A-Z]$/', $val)) {
                $fail('El DNI debe tener 8 dígitos seguidos de una letra (ej: 12345678Z).');
                return;
            }
            $letters  = 'TRWAGMYFPDXBNJZSQVHLCKE';
            $num      = (int) substr($val, 0, 8);
            $expected = $letters[$num % 23];
            if (substr($val, 8, 1) !== $expected) {
                $fail('DNI inválido: la letra no corresponde al número.');
            }
        };
    }
}
