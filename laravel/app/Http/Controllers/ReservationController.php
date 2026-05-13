<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function store(Request $request, $scheduleId)
    {
        try {
            $user = Auth::user();

            // 🔹 Validación 1: Membresía activa
            if (!in_array($user->membership_status, ['active', 'pending'])) {
                return back()->with('error', 'Tu membresía no está activa. Contacta con administración.');
            }

            // 🔹 Validación 2: Edad mínima (16 años)
            if (!$user->birth_date || $user->birth_date->age < 16) {
                return back()->with('error', 'Debes tener al menos 16 años para reservar clases.');
            }

            // 🔹 Validación 3: Obtener horario y verificar que la clase no ha pasado
            $schedule = DB::table('gym_schedules')->find($scheduleId);
            if (!$schedule)
                return back()->with('error', 'La clase seleccionada no existe.');

            // 🔹 Validación 3b: Edad mínima de la actividad
            $activity = DB::table('gym_activities')->find($schedule->activity_id);
            if ($activity && ($activity->min_age ?? 0) > 0) {
                $userAge = ($user->birth_date) ? (int) $user->birth_date->age : 0;
                if ($userAge < $activity->min_age) {
                    return back()->with('error',
                        "Esta actividad ({$activity->name}) requiere tener al menos {$activity->min_age} años. " .
                        "Tu edad ({$userAge} años) no cumple el requisito."
                    );
                }
            }

            $dayOffsets  = ['monday'=>0,'tuesday'=>1,'wednesday'=>2,'thursday'=>3,'friday'=>4,'saturday'=>5,'sunday'=>6];
            $startOfWeek = now()->startOfWeek();
            $offset      = $dayOffsets[$schedule->day_of_week] ?? 0;
            $ct          = \Carbon\Carbon::createFromTimeString($schedule->start_time);
            $classDateTime = $startOfWeek->copy()->addDays($offset)->setHour($ct->hour)->setMinute($ct->minute)->setSecond(0);

            if ($classDateTime->isPast()) {
                return back()->with('error', 'Esta clase ya ha comenzado esta semana. Los cupos se liberarán automáticamente para la próxima semana.');
            }

            // Aforo: solo contar reservas de la semana actual (cupos se reinician cada semana)
            $reservationsCount = DB::table('gym_reservations')
                ->where('schedule_id', $scheduleId)
                ->where('status', 'confirmed')
                ->whereBetween('created_at', [$startOfWeek, now()->endOfWeek()])
                ->count();

            if ($reservationsCount >= $schedule->capacity) {
                return back()->with('error', 'Lo sentimos, esta clase está completa para esta semana.');
            }

            // 🔹 Validación 4: Límites según plan
            $weeklyLimit = $user->getWeeklyLimit();
            $isPremium = $user->hasUnlimitedReservations();

            if (!$isPremium) {
                // Contar reservas de esta semana (lunes a domingo)
                $startOfWeek = now()->startOfWeek();
                $endOfWeek = now()->endOfWeek();

                $weeklyReservations = DB::table('gym_reservations')
                    ->where('user_id', $user->id)
                    ->where('status', 'confirmed')
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->count();

                // Para plan FREE: solo 1 reserva TOTAL (día de prueba)
                if ($user->plan_type === 'free') {
                    if ($user->free_trial_used) {
                        return back()->with('error', 'Has agotado tu día de prueba gratuito. Actualiza a un plan de pago para seguir reservando.');
                    }
                    // Si es su primera reserva free, marcar como usada
                }

                if ($weeklyReservations >= $weeklyLimit) {
                    $msg = $user->plan_type === 'basico'
                        ? 'Has alcanzado el límite de 5 reservas semanales de tu plan Básico.'
                        : 'Has agotado tu día de prueba gratuito.';
                    return back()->with('error', $msg);
                }
            }

            // 🔹 Validación 5: Evitar duplicados
            $existing = DB::table('gym_reservations')
                ->where('user_id', $user->id)
                ->where('schedule_id', $scheduleId)
                ->where('status', 'confirmed')
                ->first();
            if ($existing) {
                return back()->with('error', 'Ya tienes una reserva para esta clase.');
            }

            // 🔹 Crear reserva
            DB::transaction(function () use ($user, $scheduleId) {
                DB::table('gym_reservations')->insert([
                    'user_id' => $user->id,
                    'schedule_id' => $scheduleId,
                    'status' => 'confirmed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 🔹 Marcar día de prueba como usado para plan FREE
                if ($user->plan_type === 'free' && !$user->free_trial_used) {
                    DB::table('gym_users')->where('id', $user->id)->update(['free_trial_used' => 1]);
                }
            });

            return back()->with('success', '✅ ¡Reserva confirmada! Te esperamos en la clase.');

        } catch (\Throwable $e) {
            \Log::error('Reservation error: ' . $e->getMessage());
            return back()->with('error', 'Error al procesar la reserva. Inténtalo de nuevo.');
        }
    }

    public function destroy(Request $request, $reservationId)
    {
        try {
            $user = Auth::user();
            $reservation = DB::table('gym_reservations')->find($reservationId);

            if (!$reservation)
                return back()->with('error', 'La reserva no existe.');
            if ($reservation->user_id !== $user->id)
                return back()->with('error', 'No tienes permiso para cancelar esta reserva.');
            if ($reservation->status !== 'confirmed')
                return back()->with('error', 'Esta reserva ya no está activa.');

            // Verificar que la clase no ha comenzado ya esta semana
            $schedule = DB::table('gym_schedules')->find($reservation->schedule_id);
            if ($schedule) {
                $dayOffsets    = ['monday'=>0,'tuesday'=>1,'wednesday'=>2,'thursday'=>3,'friday'=>4,'saturday'=>5,'sunday'=>6];
                $startOfWeek   = now()->startOfWeek();
                $offset        = $dayOffsets[$schedule->day_of_week] ?? 0;
                $ct            = \Carbon\Carbon::createFromTimeString($schedule->start_time);
                $classDateTime = $startOfWeek->copy()->addDays($offset)->setHour($ct->hour)->setMinute($ct->minute)->setSecond(0);

                if ($classDateTime->isPast()) {
                    return back()->with('error', 'No puedes cancelar una clase que ya ha comenzado.');
                }

                // ── Límite de cancelación según plan ──
                // Premium: hasta 1h antes | Básico: hasta 2h antes | Free: sin restricción de tiempo
                $cancelLimitMinutes = match($user->plan_type ?? 'free') {
                    'premium' => 60,
                    'basico'  => 120,
                    default   => 0,
                };

                if ($cancelLimitMinutes > 0) {
                    $minutesLeft = now()->diffInMinutes($classDateTime, false);
                    if ($minutesLeft < $cancelLimitMinutes) {
                        $limitText = $cancelLimitMinutes === 60 ? '1 hora' : '2 horas';
                        return back()->with('error',
                            "Con el plan " . ucfirst($user->plan_type) . " solo puedes cancelar hasta {$limitText} antes del inicio. " .
                            "La clase comienza en " . round($minutesLeft) . " minutos."
                        );
                    }
                }
            }

            DB::table('gym_reservations')
                ->where('id', $reservationId)
                ->update(['status' => 'cancelled', 'cancelled_at' => now(), 'updated_at' => now()]);

            return back()->with('success', '✅ Reserva cancelada correctamente.');

        } catch (\Throwable $e) {
            \Log::error('Cancel reservation error: ' . $e->getMessage());
            return back()->with('error', 'Error al cancelar la reserva.');
        }
    }
}