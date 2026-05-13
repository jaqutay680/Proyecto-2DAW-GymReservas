<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * 🔹 Dashboard principal mejorado
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        // Métricas principales
        $metrics = [
            'total_users' => DB::table('gym_users')->where('role', 'cliente')->count(),
            'active_users' => DB::table('gym_users')->where('role', 'cliente')->where('membership_status', 'active')->count(),
            'suspended_users' => DB::table('gym_users')->where('role', 'cliente')->where('membership_status', 'suspended')->count(),
            'total_reservations_today' => DB::table('gym_reservations')->whereDate('created_at', today())->where('status', 'confirmed')->count(),
            'total_revenue_month' => DB::table('gym_payments')->whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->where('status', 'paid')->sum('amount') ?? 0,
            'reservations_this_week' => DB::table('gym_reservations')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', 'confirmed')->count(),
        ];

        // Distribución de planes
        $planDistribution = DB::table('gym_users')
            ->where('role', 'cliente')
            ->selectRaw('plan_type, COUNT(*) as count')
            ->groupBy('plan_type')
            ->pluck('count', 'plan_type')
            ->toArray();

        // Reservas por día de la semana
        $reservationsByDay = DB::table('gym_reservations')
            ->join('gym_schedules', 'gym_reservations.schedule_id', '=', 'gym_schedules.id')
            ->where('gym_reservations.status', 'confirmed')
            ->whereBetween('gym_reservations.created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->selectRaw('gym_schedules.day_of_week, COUNT(*) as count')
            ->groupBy('gym_schedules.day_of_week')
            ->pluck('count', 'gym_schedules.day_of_week')
            ->toArray();

        // Últimos usuarios
        $recentUsers = DB::table('gym_users')
            ->where('role', 'cliente')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Últimos pagos
        $recentPayments = DB::table('gym_payments')
            ->join('gym_users', 'gym_payments.user_id', '=', 'gym_users.id')
            ->select('gym_payments.*', 'gym_users.name as user_name', 'gym_users.email')
            ->orderByDesc('gym_payments.payment_date')
            ->limit(5)
            ->get();

        return view('admin.index', compact(
            'metrics',
            'planDistribution',
            'reservationsByDay',
            'recentUsers',
            'recentPayments'
        ));
    }

    /**
     * 🔹 Crear usuario — formulario
     */
    public function usersCreate()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('admin.users.create');
    }

    /**
     * Devuelve la closure de validación del DNI español (mod-23).
     * Reutilizable en usersStore y usersUpdate.
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
     * 🔹 Crear usuario — guardar
     */
    public function usersStore(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        // Sin withInput() → evita 502 por overflow de cookie en shared hosting
        $v = Validator::make($request->all(), [
            'name'              => 'required|string|min:2|max:255',
            'email'             => 'required|email|max:180|unique:gym_users,email',
            'password'          => 'required|string|min:8',
            'role'              => 'required|in:cliente,admin',
            'plan_type'         => 'required|in:free,basico,premium',
            'membership_status' => 'required|in:active,pending,suspended,expired',
            'dni'               => ['nullable', 'string', 'max:20', 'unique:gym_users,dni', $this->dniRule()],
            'birth_date'        => 'nullable|date|before:today',
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'name.min'          => 'El nombre debe tener al menos 2 caracteres.',
            'email.required'    => 'El email es obligatorio.',
            'email.email'       => 'Introduce un email válido.',
            'email.unique'      => 'Ese email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'dni.unique'        => 'Ese DNI ya está registrado en otra cuenta.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ]);

        if ($v->fails()) {
            $viewErrorBag = new \Illuminate\Support\ViewErrorBag;
            $viewErrorBag->put('default', $v->errors());
            // Devolvemos los valores sin contraseña para rellenar el formulario
            $input = $request->except(['password']);
            return view('admin.users.create')
                ->with('errors', $viewErrorBag)
                ->with('input', $input);
        }

        DB::table('gym_users')->insert([
            'name'              => trim($request->input('name')),
            'email'             => strtolower(trim($request->input('email'))),
            'password'          => bcrypt($request->input('password')),
            'role'              => $request->input('role'),
            'plan_type'         => $request->input('plan_type'),
            'membership_status' => $request->input('membership_status'),
            'dni'               => $request->filled('dni') ? strtoupper(trim($request->input('dni'))) : null,
            'birth_date'        => $request->input('birth_date') ?: null,
            'wallet_balance'    => 0,
            'free_trial_used'   => 0,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * 🔹 Listar usuarios con búsqueda y filtros
     */
    public function usersIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        $users = DB::table('gym_users')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * 🔹 Editar usuario
     */
    public function usersEdit($userId)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        $user = DB::table('gym_users')->find($userId);
        if (!$user)
            abort(404);

        $auditLogs = DB::table('gym_audit_log')
            ->where('target_id', $userId)
            ->where('target_type', 'gym_users')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.users.edit', compact('user', 'auditLogs'));
    }

    /**
     * 🔹 Actualizar usuario
     */
    public function usersUpdate(Request $request, $userId)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        // ── Validación servidor (sin withInput para evitar 502 en cookie session) ──
        $v = Validator::make($request->all(), [
            'name'              => 'required|string|min:2|max:100',
            'email'             => 'required|email|max:180|unique:gym_users,email,' . $userId,
            'dni'               => ['nullable', 'string', 'max:20', $this->dniRule()],
            'birth_date'        => 'nullable|date|before:today',
            'plan_type'         => 'required|in:free,basico,premium',
            'membership_status' => 'required|in:active,pending,suspended,expired',
            'role'              => 'required|in:cliente,admin',
            'wallet_balance'    => 'nullable|numeric|min:0|max:99999',
            'free_trial_used'   => 'nullable|in:0,1',
        ], [
            'name.required'          => 'El nombre es obligatorio.',
            'name.min'               => 'El nombre debe tener al menos 2 caracteres.',
            'email.required'         => 'El email es obligatorio.',
            'email.email'            => 'El email no tiene un formato válido.',
            'email.unique'           => 'Ese email ya está registrado en otra cuenta.',
            'birth_date.before'      => 'La fecha de nacimiento debe ser anterior a hoy.',
            'wallet_balance.numeric' => 'El saldo debe ser un número.',
            'wallet_balance.min'     => 'El saldo no puede ser negativo.',
        ]);

        if ($v->fails()) {
            // Devolvemos la vista DIRECTAMENTE (sin redirect) para que los errores
            // nunca toquen la cookie de sesión → evita 502 en shared hosting con SESSION_DRIVER=cookie
            $user = DB::table('gym_users')->find($userId);
            if (!$user) return back()->with('error', 'Usuario no encontrado.');

            $auditLogs = DB::table('gym_audit_log')
                ->where('target_id', $userId)
                ->where('target_type', 'gym_users')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();

            $viewErrorBag = new \Illuminate\Support\ViewErrorBag;
            $viewErrorBag->put('default', $v->errors());

            return view('admin.users.edit', compact('user', 'auditLogs'))
                ->with('errors', $viewErrorBag);
        }

        try {
            $user = DB::table('gym_users')->find($userId);
            if (!$user)
                return back()->with('error', 'Usuario no encontrado.');

            $updateData = ['updated_at' => now()];
            $changes = [];

            // Nombre
            $newName = trim($request->input('name'));
            if ($user->name !== $newName) {
                $changes['name'] = ['old' => $user->name, 'new' => $newName];
                $updateData['name'] = $newName;
            }

            // Email
            $newEmail = strtolower(trim($request->input('email')));
            if ($user->email !== $newEmail) {
                $changes['email'] = ['old' => $user->email, 'new' => $newEmail];
                $updateData['email'] = $newEmail;
            }

            // DNI
            $newDni = $request->filled('dni') ? strtoupper(trim($request->input('dni'))) : null;
            if ($user->dni !== $newDni) {
                $changes['dni'] = ['old' => $user->dni, 'new' => $newDni];
                $updateData['dni'] = $newDni;
            }

            // Fecha de nacimiento
            if ($request->filled('birth_date')) {
                $newBirth = $request->input('birth_date');
                if ($user->birth_date !== $newBirth) {
                    $changes['birth_date'] = ['old' => $user->birth_date, 'new' => $newBirth];
                    $updateData['birth_date'] = $newBirth;
                }
            }

            // Plan
            if ($request->filled('plan_type')) {
                $newPlan = $request->input('plan_type');
                if ($user->plan_type !== $newPlan) {
                    $changes['plan_type'] = ['old' => $user->plan_type, 'new' => $newPlan];
                    $updateData['plan_type'] = $newPlan;
                }
            }

            // Estado membresía
            if ($request->filled('membership_status')) {
                $newStatus = $request->input('membership_status');
                if ($user->membership_status !== $newStatus) {
                    $changes['membership_status'] = ['old' => $user->membership_status, 'new' => $newStatus];
                    $updateData['membership_status'] = $newStatus;
                }
            }

            // Rol
            if ($request->filled('role')) {
                $newRole = $request->input('role');
                if ($user->role !== $newRole) {
                    $changes['role'] = ['old' => $user->role, 'new' => $newRole];
                    $updateData['role'] = $newRole;
                }
            }

            // Saldo cartera
            if ($request->filled('wallet_balance')) {
                $newBalance = floatval($request->input('wallet_balance'));
                if ($user->wallet_balance != $newBalance) {
                    $changes['wallet_balance'] = ['old' => $user->wallet_balance, 'new' => $newBalance];
                    $updateData['wallet_balance'] = $newBalance;
                }
            }

            // Prueba gratuita
            if ($request->has('free_trial_used')) {
                $newTrial = intval($request->input('free_trial_used'));
                if ($user->free_trial_used != $newTrial) {
                    $changes['free_trial_used'] = ['old' => $user->free_trial_used, 'new' => $newTrial];
                    $updateData['free_trial_used'] = $newTrial;
                }
            }

            // Guardar cambios
            if (count($updateData) > 1) {
                DB::table('gym_users')->where('id', $userId)->update($updateData);

                // Registrar en auditoría
                if (!empty($changes)) {
                    DB::table('gym_audit_log')->insert([
                        'user_id' => Auth::id(),
                        'action_type' => 'user_update',
                        'target_id' => $userId,
                        'target_type' => 'gym_users',
                        'old_values' => json_encode(array_map(fn($c) => $c['old'], $changes)),
                        'new_values' => json_encode(array_map(fn($c) => $c['new'], $changes)),
                        'ip_address' => $request->ip(),
                        'created_at' => now(),
                    ]);
                }
            }

            return back()->with('success', '✅ Usuario actualizado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Admin update: ' . $e->getMessage());
            return back()->with('error', '⚠️ Error: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 Ver pagos de un usuario
     */
    public function usersPayments($userId)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        $user = DB::table('gym_users')->find($userId);
        if (!$user)
            abort(404);

        $payments = DB::table('gym_payments')
            ->where('user_id', $userId)
            ->orderByDesc('payment_date')
            ->get();

        return view('admin.users.payments', compact('user', 'payments'));
    }

    /**
     * 🔹 Ver auditoría de un usuario
     */
    public function usersAudit($userId)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        $user = DB::table('gym_users')->find($userId);
        if (!$user)
            abort(404);

        $auditLogs = DB::table('gym_audit_log')
            ->where('target_id', $userId)
            ->where('target_type', 'gym_users')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.audit', compact('user', 'auditLogs'));
    }

    /**
     * 🔹 Actividades Index
     */
    public function activitiesIndex()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $activities = DB::table('gym_activities')->orderBy('name')->get();
        return view('admin.activities.index', compact('activities'));
    }

    public function activitiesCreate()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('admin.activities.create');
    }

    public function activitiesStore(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'difficulty'  => 'required|in:beginner,intermediate,advanced',
            'min_age'     => 'required|integer|min:0|max:99',
        ]);

        $slug = \Illuminate\Support\Str::slug($validated['name']);
        $baseSlug = $slug;
        $i = 1;
        while (DB::table('gym_activities')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        DB::table('gym_activities')->insert([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'difficulty'  => $validated['difficulty'],
            'min_age'     => $validated['min_age'],
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.activities.index')->with('success', 'Actividad creada correctamente.');
    }

    public function activitiesEdit($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $activity = DB::table('gym_activities')->find($id);
        if (!$activity) abort(404);
        return view('admin.activities.edit', compact('activity'));
    }

    public function activitiesUpdate(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'difficulty'  => 'required|in:beginner,intermediate,advanced',
            'min_age'     => 'required|integer|min:0|max:99',
        ]);

        DB::table('gym_activities')->where('id', $id)->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'difficulty'  => $validated['difficulty'],
            'min_age'     => $validated['min_age'],
            'updated_at'  => now(),
        ]);

        return back()->with('success', 'Actividad actualizada correctamente.');
    }

    public function activitiesDelete($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('gym_activities')->where('id', $id)->delete();
        return redirect()->route('admin.activities.index')->with('success', 'Actividad eliminada.');
    }

    /**
     * 🔹 Horarios Index
     */
    public function schedulesIndex()
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $schedules = DB::table('gym_schedules')
            ->join('gym_activities', 'gym_schedules.activity_id', '=', 'gym_activities.id')
            ->select('gym_schedules.*', 'gym_activities.name as activity_name')
            ->orderByRaw("FIELD(gym_schedules.day_of_week,'monday','tuesday','wednesday','thursday','friday','saturday','sunday')")
            ->orderBy('gym_schedules.start_time')
            ->get();

        // Precomputar conteo de reservas actuales (semana actual) para evitar N+1
        $scheduleIds = $schedules->pluck('id');
        $reservationCounts = DB::table('gym_reservations')
            ->whereIn('schedule_id', $scheduleIds)
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->selectRaw('schedule_id, COUNT(*) as total')
            ->groupBy('schedule_id')
            ->pluck('total', 'schedule_id');

        foreach ($schedules as $s) {
            $s->reserved_count = $reservationCounts[$s->id] ?? 0;
        }

        return view('admin.schedules.index', compact('schedules'));
    }

    public function schedulesCreate()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $activities = DB::table('gym_activities')->orderBy('name')->get();
        return view('admin.schedules.create', compact('activities'));
    }

    public function schedulesStore(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'activity_id' => 'required|exists:gym_activities,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room'        => 'required|string|max:50',
            'capacity'    => 'required|integer|min:1|max:200',
        ]);

        DB::table('gym_schedules')->insert([
            'activity_id' => $validated['activity_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time'  => $validated['start_time'],
            'end_time'    => $validated['end_time'],
            'room'        => $validated['room'],
            'capacity'    => $validated['capacity'],
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Horario creado correctamente.');
    }

    public function schedulesEdit($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $schedule = DB::table('gym_schedules')->find($id);
        if (!$schedule) abort(404);
        $activities = DB::table('gym_activities')->orderBy('name')->get();
        return view('admin.schedules.edit', compact('schedule', 'activities'));
    }

    public function schedulesUpdate(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'activity_id' => 'required|exists:gym_activities,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'room'        => 'required|string|max:50',
            'capacity'    => 'required|integer|min:1|max:200',
        ]);

        DB::table('gym_schedules')->where('id', $id)->update([
            'activity_id' => $validated['activity_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time'  => $validated['start_time'],
            'end_time'    => $validated['end_time'],
            'room'        => $validated['room'],
            'capacity'    => $validated['capacity'],
            'updated_at'  => now(),
        ]);

        return back()->with('success', 'Horario actualizado correctamente.');
    }

    public function schedulesDelete($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        DB::table('gym_schedules')->where('id', $id)->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Horario eliminado.');
    }

    /**
     * 🔹 Pagos
     */
    public function payments()
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $payments = DB::table('gym_payments')
            ->join('gym_users', 'gym_payments.user_id', '=', 'gym_users.id')
            ->select('gym_payments.*', 'gym_users.name', 'gym_users.email', 'gym_users.plan_type')
            ->orderByDesc('gym_payments.payment_date')
            ->get();

        return view('admin.payments', compact('payments'));
    }

    public function generatePayments(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $subscribers = DB::table('gym_subscriptions')
            ->join('gym_users', 'gym_subscriptions.user_id', '=', 'gym_users.id')
            ->where('gym_subscriptions.status', 'active')
            ->whereIn('gym_subscriptions.plan_type', ['basico', 'premium'])
            ->select('gym_subscriptions.*', 'gym_users.name')
            ->get();

        $generated = 0;
        foreach ($subscribers as $sub) {
            $alreadyPaid = DB::table('gym_payments')
                ->where('user_id', $sub->user_id)
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->exists();

            if (!$alreadyPaid) {
                $amount = $sub->plan_type === 'premium' ? 19.99 : 9.99;
                DB::table('gym_payments')->insert([
                    'user_id'      => $sub->user_id,
                    'amount'       => $amount,
                    'currency'     => 'EUR',
                    'payment_date' => now()->format('Y-m-d'),
                    'status'       => 'paid',
                    'plan_type'    => $sub->plan_type,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                $generated++;
            }
        }

        return back()->with('success', "Generados {$generated} pagos para este mes.");
    }

    /**
     * 🔹 Suscripciones
     */
    public function subscriptions()
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $subscriptions = DB::table('gym_subscriptions')
            ->join('gym_users', 'gym_subscriptions.user_id', '=', 'gym_users.id')
            ->select('gym_subscriptions.*', 'gym_users.name', 'gym_users.email')
            ->orderByDesc('gym_subscriptions.next_billing_date')
            ->get();

        return view('admin.subscriptions', compact('subscriptions'));
    }

    public function renewSubscriptions(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $expired = DB::table('gym_subscriptions')
            ->where('status', 'active')
            ->where('next_billing_date', '<', now()->format('Y-m-d'))
            ->get();

        $renewed = 0;
        foreach ($expired as $sub) {
            DB::table('gym_subscriptions')->where('id', $sub->id)->update([
                'next_billing_date' => now()->addMonth()->format('Y-m-d'),
                'updated_at'        => now(),
            ]);
            $renewed++;
        }

        return back()->with('success', "Renovadas {$renewed} suscripciones.");
    }

    /**
     * 🔹 Reportes
     */
    public function reports()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('admin.reports.index');
    }

    /**
     * 🔹 Configuración
     */
    public function settings()
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $settings = DB::table('gym_settings')->get()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            DB::table('gym_settings')
                ->where('key', $key)
                ->update(['value' => $value, 'updated_at' => now()]);
        }

        return back()->with('success', 'Configuración guardada correctamente.');
    }
}
