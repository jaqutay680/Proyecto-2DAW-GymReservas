<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * 🔹 Admin: Ver lista de suscripciones
     */
    public function index()
    {
        // Verificar que sea admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        // Consulta simple y segura
        $subscriptions = DB::table('gym_subscriptions as s')
            ->join('gym_users as u', 's.user_id', '=', 'u.id')
            ->select(
                's.id',
                's.user_id',
                's.plan_type',
                's.status',
                's.started_at',
                's.next_billing_date',
                'u.name',
                'u.email'
            )
            ->orderByDesc('s.next_billing_date')
            ->paginate(20);

        return view('admin.subscriptions', compact('subscriptions'));
    }

    /**
     * 🔹 Admin: Renovar suscripciones vencidas (COBRO POR ANIVERSARIO)
     */
    public function renewSubscriptions()
    {
        // Solo admin
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        try {
            $today = date('Y-m-d');
            $count = 0;
            $errors = 0;

            // Buscar suscripciones activas con fecha de renovación vencida
            $subscriptions = DB::table('gym_subscriptions')
                ->where('status', 'active')
                ->where('plan_type', '!=', 'free')
                ->where('next_billing_date', '<=', $today)
                ->get();

            foreach ($subscriptions as $sub) {
                try {
                    // Determinar precio
                    $amount = ($sub->plan_type === 'premium') ? 19.99 : 9.99;

                    // Crear registro de pago (simulado como pagado)
                    DB::table('gym_payments')->insert([
                        'user_id' => $sub->user_id,
                        'amount' => $amount,
                        'currency' => 'EUR',
                        'payment_date' => date('Y-m-d H:i:s'),
                        'status' => 'paid',
                        'plan_type' => $sub->plan_type,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    // Actualizar próxima fecha: +1 mes desde hoy
                    DB::table('gym_subscriptions')
                        ->where('id', $sub->id)
                        ->update([
                            'next_billing_date' => date('Y-m-d', strtotime('+1 month')),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                    $count++;

                } catch (\Throwable $e) {
                    $errors++;
                    \Log::error('Error renovando suscripción ' . $sub->id . ': ' . $e->getMessage());
                }
            }

            $message = "✅ {$count} suscripciones renovadas.";
            if ($errors > 0) {
                $message .= " ⚠️ {$errors} errores.";
            }

            return back()->with('success', $message);

        } catch (\Throwable $e) {
            \Log::error('Error en renewSubscriptions: ' . $e->getMessage());
            return back()->with('error', 'Error al renovar: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 Usuario: Cancelar su suscripción
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();

        // Buscar suscripción activa de pago
        $subscription = DB::table('gym_subscriptions')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('plan_type', '!=', 'free')
            ->first();

        if (!$subscription) {
            return back()->with('error', 'No tienes una suscripción activa que cancelar.');
        }

        try {
            // Cancelar suscripción
            DB::table('gym_subscriptions')
                ->where('id', $subscription->id)
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            // Cambiar usuario a plan free
            DB::table('gym_users')
                ->where('id', $user->id)
                ->update([
                    'plan_type' => 'free',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            // Registrar en historial de pagos
            DB::table('gym_payments')->insert([
                'user_id' => $user->id,
                'amount' => 0,
                'currency' => 'EUR',
                'payment_date' => date('Y-m-d H:i:s'),
                'status' => 'cancelled',
                'plan_type' => $subscription->plan_type,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return back()->with('success', '✅ Suscripción cancelada. Tu plan ha cambiado a FREE.');

        } catch (\Throwable $e) {
            \Log::error('Error cancelando suscripción: ' . $e->getMessage());
            return back()->with('error', 'Error al cancelar: ' . $e->getMessage());
        }
    }
}