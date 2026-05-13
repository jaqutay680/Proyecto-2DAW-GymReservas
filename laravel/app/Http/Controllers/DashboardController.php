<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard principal con filtros
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedDay = $request->input('day', strtolower(now()->format('l')));
        $selectedActivity = $request->input('activity', 'all');
        $days = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado'
        ];

        $schedules = $this->getSchedules($selectedDay, $selectedActivity);
        $activities = DB::table('gym_activities')->get();

        // 🔹 Contar reservas del usuario esta semana
        $myWeeklyReservations = DB::table('gym_reservations')
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return view('dashboard', [
            'user' => $user,
            'schedules' => $schedules,
            'days' => $days,
            'activities' => $activities,
            'selectedDay' => $selectedDay,
            'selectedActivity' => $selectedActivity,
            'myWeeklyReservations' => $myWeeklyReservations,
        ]);
    }

    /**
     * 🔹 Endpoint AJAX para filtros sin recargar página
     */
    public function filterClasses(Request $request)
    {
        $day       = $request->input('day', strtolower(now()->format('l')));
        $activity  = $request->input('activity', 'all');
        $schedules = $this->getSchedules($day, $activity);

        // Filter by authenticated user's age
        $user    = Auth::user();
        $userAge = ($user && $user->birth_date) ? (int) $user->birth_date->age : 0;
        $schedules = $schedules->filter(fn($s) => ($s->min_age ?? 0) <= $userAge)->values();

        $html = view('partials.class-list', ['schedules' => $schedules, 'selectedDay' => $day])->render();
        return response()->json(['html' => $html]);
    }

    /**
     * 🔹 Vista: Mis Reservas
     */
    public function myReservations()
    {
        $reservations = DB::table('gym_reservations as r')
            ->join('gym_schedules as s', 'r.schedule_id', '=', 's.id')
            ->join('gym_activities as a', 's.activity_id', '=', 'a.id')
            ->where('r.user_id', Auth::id())
            ->where('r.status', 'confirmed')
            ->orderBy('s.start_time')
            ->select('r.*', 'a.name as activity_name', 's.day_of_week', 's.start_time', 's.end_time', 's.room', 's.capacity')
            ->get();

        return view('my-reservations', compact('reservations'));
    }

    /**
     * 🔹 Vista: Mis Pagos (extracto mensual)
     */
    public function myPayments()
    {
        $payments = DB::table('gym_payments')
            ->where('user_id', Auth::id())
            ->orderByDesc('payment_date')
            ->get();

        return view('my-payments', compact('payments'));
    }

    /**
     * 🔹 Método privado: Obtener horarios con filtros y conteo de reservas (semana actual)
     */
    private function getSchedules($day, $activity)
    {
        // Scope reservations to current week only (Mon–Sun)
        $weekStart = now()->startOfWeek()->format('Y-m-d H:i:s');
        $weekEnd   = now()->endOfWeek()->format('Y-m-d H:i:s');

        $query = DB::table('gym_schedules as s')
            ->join('gym_activities as a', 's.activity_id', '=', 'a.id')
            ->select(
                's.id', 's.activity_id', 's.day_of_week', 's.start_time', 's.end_time',
                's.room', 's.capacity', 's.created_at', 's.updated_at',
                'a.name as activity_name', 'a.min_age',
                DB::raw("(SELECT COUNT(*) FROM gym_reservations r
                          WHERE r.schedule_id = s.id
                            AND r.status      = 'confirmed'
                            AND r.created_at BETWEEN '$weekStart' AND '$weekEnd'
                         ) as reserved_count")
            )
            ->where('s.day_of_week', $day);

        if ($activity !== 'all') {
            $query->where('s.activity_id', (int) $activity);
        }

        return $query->orderBy('s.start_time')->get();
    }
}