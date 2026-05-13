<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ViewErrorBag;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Validación DNI español (mod-23).
     * Misma lógica que AdminController::dniRule() pero autónoma aquí.
     */
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

    /**
     * Devuelve la vista de registro con errores y valores previos.
     * Sin redirect ni withInput() → evita 502 por overflow de cookie de sesión.
     */
    private function backWithErrors(array $errors, array $input): \Illuminate\Http\Response
    {
        $bag = new ViewErrorBag;
        $bag->put('default', new \Illuminate\Support\MessageBag($errors));

        return response()->view('auth.register', ['input' => $input])
            ->withHeaders([])
            // Inyectamos el error bag directamente en la vista
            ->setContent(
                view('auth.register', ['input' => $input, 'errors' => $bag])->render()
            );
    }

    public function store(Request $request)
    {
        try {
            // Valores limpios que se devolverán al formulario si hay error
            $input = $request->except(['password', 'password_confirmation']);

            // ── Validación completa via Validator::make ──
            // Sin withInput() → evita 502 por overflow de cookie en shared hosting
            $v = Validator::make($request->all(), [
                'name'                  => 'required|string|min:2|max:100',
                'email'                 => 'required|email|max:180|unique:gym_users,email',
                'dni'                   => ['required', 'string', 'max:20', 'unique:gym_users,dni', $this->dniRule()],
                'birth_date'            => 'required|date|before:' . now()->subYears(16)->format('Y-m-d'),
                'password'              => 'required|string|min:8|regex:/[A-Z]/|regex:/[0-9]/|confirmed',
                'plan_type'             => 'required|in:free,basico,premium',
            ], [
                'name.required'         => 'El nombre es obligatorio.',
                'name.min'              => 'El nombre debe tener al menos 2 caracteres.',
                'email.required'        => 'El correo electrónico es obligatorio.',
                'email.email'           => 'Introduce un correo electrónico válido.',
                'email.unique'          => 'Ese correo ya está asociado a una cuenta.',
                'dni.required'          => 'El DNI es obligatorio.',
                'dni.unique'            => 'Ese DNI ya está registrado en otra cuenta.',
                'birth_date.required'   => 'La fecha de nacimiento es obligatoria.',
                'birth_date.before'     => 'Debes tener al menos 16 años para registrarte.',
                'password.required'     => 'La contraseña es obligatoria.',
                'password.min'          => 'La contraseña debe tener al menos 8 caracteres.',
                'password.regex'        => 'La contraseña debe contener al menos una mayúscula y un número.',
                'password.confirmed'    => 'Las contraseñas no coinciden.',
                'plan_type.required'    => 'Selecciona un plan.',
            ]);

            if ($v->fails()) {
                $viewErrorBag = new ViewErrorBag;
                $viewErrorBag->put('default', $v->errors());
                return response(
                    view('auth.register', ['input' => $input, 'errors' => $viewErrorBag])->render()
                );
            }

            // ── Crear usuario ──
            $userId = DB::table('gym_users')->insertGetId([
                'name'              => trim($request->input('name')),
                'email'             => strtolower(trim($request->input('email'))),
                'password'          => Hash::make($request->input('password')),
                'dni'               => strtoupper(trim($request->input('dni'))),
                'birth_date'        => $request->input('birth_date'),
                'plan_type'         => $request->input('plan_type', 'free'),
                'wallet_balance'    => 0,
                'role'              => 'cliente',
                'membership_status' => 'active',
                'free_trial_used'   => 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // ── Pago y suscripción si plan de pago ──
            $plan = $request->input('plan_type', 'free');
            if (in_array($plan, ['basico', 'premium'])) {
                $amount = $plan === 'premium' ? 19.99 : 9.99;

                DB::table('gym_payments')->insert([
                    'user_id'      => $userId,
                    'amount'       => $amount,
                    'currency'     => 'EUR',
                    'payment_date' => now(),
                    'status'       => 'paid',
                    'plan_type'    => $plan,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                DB::table('gym_subscriptions')->insert([
                    'user_id'           => $userId,
                    'plan_type'         => $plan,
                    'status'            => 'active',
                    'started_at'        => now(),
                    'next_billing_date' => now()->addMonth()->format('Y-m-d'),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                Log::info("Pago y suscripción creados al registrar usuario ID: $userId, Plan: $plan");
            }

            // ── Login y redirección ──
            Auth::loginUsingId($userId);
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', '¡Bienvenido a GymReservas!');

        } catch (\Throwable $e) {
            Log::error('Error en registro: ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            $viewErrorBag = new ViewErrorBag;
            $viewErrorBag->put('default', new \Illuminate\Support\MessageBag([
                'general' => 'Error al procesar el registro. Inténtalo de nuevo.'
            ]));
            $input = $request->except(['password', 'password_confirmation']);
            return response(
                view('auth.register', ['input' => $input, 'errors' => $viewErrorBag])->render()
            );
        }
    }
}
