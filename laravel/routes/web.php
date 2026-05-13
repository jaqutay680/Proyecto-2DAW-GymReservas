<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// 🔹 Debug temporal
if (env('APP_DEBUG', false)) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// ============================================================================
// 🔹 LANDING
// ============================================================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============================================================================
// 🔹 GOOGLE OAUTH
// ============================================================================
Route::get('/auth/google',          'App\Http\Controllers\Auth\GoogleController@redirect')->name('auth.google');
Route::get('/auth/google/callback', 'App\Http\Controllers\Auth\GoogleController@callback')->name('auth.google.callback');

// Completar perfil (solo usuarios OAuth nuevos, auth pero sin profile.complete)
Route::middleware(['auth'])->group(function () {
    Route::get('/completar-perfil',  'App\Http\Controllers\Auth\GoogleController@completeProfileForm')->name('profile.complete.form');
    Route::post('/completar-perfil', 'App\Http\Controllers\Auth\GoogleController@completeProfileStore')->name('profile.complete.store');
});

// ============================================================================
// 🔹 PÁGINAS LEGALES (públicas, sin auth)
// ============================================================================
Route::get('/privacidad',  fn() => view('legal.privacy'))->name('legal.privacy');
Route::get('/cookies',     fn() => view('legal.cookies'))->name('legal.cookies');
Route::get('/aviso-legal', fn() => view('legal.legal-notice'))->name('legal.notice');
Route::get('/terminos',    fn() => view('legal.terms'))->name('legal.terms');

// ============================================================================
// 🔹 DASHBOARD DE CLIENTES (ACTUALIZADO)
// ============================================================================
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin')
        return redirect('/admin');
    if ($user->role === 'trainer')
        return redirect('/trainer');

    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $today = strtolower($days[now()->dayOfWeek]);
    // If today is Sunday, fall back to Monday (gym closed / no Sunday schedule)
    $filterDay = ($today === 'sunday') ? 'monday' : $today;

    $weekStart = now()->startOfWeek()->format('Y-m-d H:i:s');
    $weekEnd   = now()->endOfWeek()->format('Y-m-d H:i:s');

    $schedules = DB::table('gym_schedules as s')
        ->join('gym_activities as a', 's.activity_id', '=', 'a.id')
        ->where('s.day_of_week', $filterDay)   // ← ONLY today, never tomorrow
        ->select(
            's.id', 's.activity_id', 's.day_of_week', 's.start_time', 's.end_time',
            's.room', 's.capacity', 's.created_at', 's.updated_at',
            'a.name as activity_name', 'a.min_age',
            DB::raw("(SELECT COUNT(*) FROM gym_reservations r
                      WHERE r.schedule_id = s.id
                        AND r.status      = 'confirmed'
                        AND r.created_at BETWEEN '$weekStart' AND '$weekEnd'
                     ) as reserved_count"),
            DB::raw("(SELECT COUNT(*) FROM gym_reservations r2
                      WHERE r2.schedule_id = s.id
                        AND r2.user_id     = {$user->id}
                        AND r2.status      = 'confirmed'
                        AND r2.created_at BETWEEN '$weekStart' AND '$weekEnd'
                     ) as user_reserved")
        )
        ->orderBy('s.start_time')
        ->get();

    // Hide classes the user doesn't meet the age requirement for
    $userAge   = ($user->birth_date) ? (int) $user->birth_date->age : 0;
    $schedules = $schedules->filter(fn($s) => ($s->min_age ?? 0) <= $userAge)->values();

    $myWeeklyReservations = DB::table('gym_reservations')
        ->where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->count();

    // 🔹 NUEVO: Límite de reservas según plan
    $weeklyLimit = match ($user->plan_type ?? 'free') {
        'premium' => 999,  // Ilimitado (práctico)
        'basico' => 5,
        default => 1,  // Free: 1 día de prueba
    };
    $weeklyLimitDisplay = match ($user->plan_type ?? 'free') {
        'premium' => '∞',  // ✅ Carácter Unicode directo (se muestra correctamente)
        'basico' => '5',
        default => '1',
    };

    // 🔹 NUEVO: Clases disponibles esta semana (total - reservadas)
    $totalClassesThisWeek = DB::table('gym_schedules')
        ->whereIn('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
        ->count();
    $reservedThisWeek = DB::table('gym_reservations')
        ->join('gym_schedules', 'gym_reservations.schedule_id', '=', 'gym_schedules.id')
        ->where('gym_reservations.status', 'confirmed')
        ->whereIn('gym_schedules.day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
        ->count();
    $availableClasses = max(0, $totalClassesThisWeek - $reservedThisWeek);

    return view('dashboard', [
        'user' => $user,
        'schedules' => $schedules,
        'myWeeklyReservations' => $myWeeklyReservations,
        'weeklyLimit' => $weeklyLimit,
        'weeklyLimitDisplay' => $weeklyLimitDisplay,
        'availableClasses' => $availableClasses,
        'activities' => DB::table('gym_activities')->get(),
        'days' => [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo'  // 🔹 Añadido para que el filtro funcione
        ],
        'selectedDay' => $filterDay,
        'selectedActivity' => 'all',
    ]);
})->middleware(['auth', 'profile.complete'])->name('dashboard');

// 🔹 Filtros AJAX para dashboard (DEVUELVE JSON)
Route::get('/dashboard/filters', function () {
    $day      = request('day', strtolower(now()->format('l')));
    $activity = request('activity', 'all');

    $weekStart = now()->startOfWeek()->format('Y-m-d H:i:s');
    $weekEnd   = now()->endOfWeek()->format('Y-m-d H:i:s');

    $query = DB::table('gym_schedules as s')
        ->join('gym_activities as a', 's.activity_id', '=', 'a.id')
        ->where('s.day_of_week', $day)
        ->select(
            's.id', 's.activity_id', 's.day_of_week', 's.start_time', 's.end_time',
            's.room', 's.capacity', 's.created_at', 's.updated_at',
            'a.name as activity_name', 'a.min_age',
            DB::raw("(SELECT COUNT(*) FROM gym_reservations r
                      WHERE r.schedule_id = s.id
                        AND r.status      = 'confirmed'
                        AND r.created_at BETWEEN '$weekStart' AND '$weekEnd'
                     ) as reserved_count"),
            DB::raw("(SELECT COUNT(*) FROM gym_reservations r2
                      WHERE r2.schedule_id = s.id
                        AND r2.user_id     = " . ((int) Auth::id()) . "
                        AND r2.status      = 'confirmed'
                        AND r2.created_at BETWEEN '$weekStart' AND '$weekEnd'
                     ) as user_reserved")
        );

    if ($activity !== 'all')
        $query->where('s.activity_id', (int) $activity);

    $schedules = $query->orderBy('s.start_time')->get();

    // Filter by user age
    $authUser  = Auth::user();
    $userAge   = ($authUser && $authUser->birth_date) ? (int) $authUser->birth_date->age : 0;
    $schedules = $schedules->filter(fn($s) => ($s->min_age ?? 0) <= $userAge)->values();

    return response()->json([
        'html' => view('partials.class-list', ['schedules' => $schedules, 'selectedDay' => $day])->render()
    ]);
})->middleware(['auth', 'profile.complete'])->name('dashboard.filters');

// ============================================================================
// 🔹 ÁREA DE CLIENTE
// ============================================================================
Route::middleware(['auth', 'profile.complete'])->group(function () {

    // 🔹 Usuario: Ver página de suscripciones
    Route::get('/my-subscriptions', function () {
        $user = Auth::user();
        $currentPlan  = $user->plan_type ?? 'free';
        $currentPrice = $currentPlan === 'premium' ? 19.99 : ($currentPlan === 'basico' ? 9.99 : 0);

        return view('my-subscriptions', compact('currentPlan', 'currentPrice', 'user'));
    })->name('my-subscriptions');

    // 🔹 Usuario: Cambiar plan de suscripción
    Route::patch('/user/plan', function (Request $request) {
        $validated = $request->validate([
            'plan_type' => 'required|in:free,basico,premium'
        ]);

        $user = Auth::user();
        $oldPlan = $user->plan_type;
        $newPlan = $validated['plan_type'];

        // Sin cambio real
        if ($oldPlan === $newPlan) {
            return back()->with('error', 'Ya tienes el plan ' . ucfirst($newPlan) . ' activo.');
        }

        // Actualizar plan en usuarios
        DB::table('gym_users')->where('id', $user->id)->update([
            'plan_type' => $newPlan,
            'updated_at' => now(),
        ]);

        // Cancelar cualquier suscripción activa previa (cambio de plan o baja)
        DB::table('gym_subscriptions')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled', 'updated_at' => now()]);

        // Si el nuevo plan es de pago, registrar el cobro y crear nueva suscripción
        if ($newPlan !== 'free') {
            $amount = $newPlan === 'premium' ? 19.99 : 9.99;
            DB::table('gym_payments')->insert([
                'user_id'      => $user->id,
                'amount'       => $amount,
                'currency'     => 'EUR',
                'payment_date' => now(),
                'status'       => 'paid',
                'plan_type'    => $newPlan,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            DB::table('gym_subscriptions')->insert([
                'user_id'           => $user->id,
                'plan_type'         => $newPlan,
                'status'            => 'active',
                'started_at'        => now(),
                'next_billing_date' => now()->addMonth()->format('Y-m-d'),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        return back()->with('success', 'Plan actualizado a ' . ucfirst($newPlan) . '.');
    })->name('user.plan.update');

    // 🔹 Usuario: Actualizar email y/o contraseña
    Route::patch('/user/profile', function (Request $request) {
        $user = Auth::user();

        // Sin withInput() → evita 502 por overflow de cookie en shared hosting
        $v = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email'            => 'required|email|max:180|unique:gym_users,email,' . $user->id,
            'current_password' => 'required',
            'new_password'     => 'nullable|min:8|confirmed',
        ], [
            'email.required'           => 'El email es obligatorio.',
            'email.email'              => 'Introduce un email válido.',
            'email.unique'             => 'Ese email ya está registrado en otra cuenta.',
            'current_password.required'=> 'Debes introducir tu contraseña actual.',
            'new_password.min'         => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed'   => 'Las contraseñas nuevas no coinciden.',
        ]);

        if ($v->fails()) {
            return back()->withErrors($v);
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual introducida no es correcta.']);
        }

        $data = ['email' => strtolower(trim($request->input('email'))), 'updated_at' => now()];
        if (!empty($request->input('new_password'))) {
            $data['password'] = Hash::make($request->input('new_password'));
        }

        DB::table('gym_users')->where('id', $user->id)->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    })->name('user.profile.update');

    Route::get('/my-reservations', function () {
        $user = Auth::user();
        $dayOffsets = ['monday'=>0,'tuesday'=>1,'wednesday'=>2,'thursday'=>3,'friday'=>4,'saturday'=>5,'sunday'=>6];
        $dayNames   = ['monday'=>'Lunes','tuesday'=>'Martes','wednesday'=>'Miércoles','thursday'=>'Jueves','friday'=>'Viernes','saturday'=>'Sábado','sunday'=>'Domingo'];
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek();

        $reservations = DB::table('gym_reservations as r')
            ->join('gym_schedules as s', 'r.schedule_id', '=', 's.id')
            ->join('gym_activities as a', 's.activity_id', '=', 'a.id')
            ->where('r.user_id', $user->id)
            ->whereIn('r.status', ['confirmed', 'cancelled'])
            ->select('r.*', 'a.name as activity_name', 's.day_of_week', 's.start_time', 's.end_time', 's.room', 's.id as schedule_id')
            ->orderByDesc('r.created_at')
            ->get();

        foreach ($reservations as $r) {
            $offset = $dayOffsets[$r->day_of_week] ?? 0;
            $ct = \Carbon\Carbon::createFromTimeString($r->start_time);
            $classDateTime = $startOfWeek->copy()->addDays($offset)->setHour($ct->hour)->setMinute($ct->minute)->setSecond(0);
            $r->is_past_class   = $classDateTime->isPast();
            $r->day_name        = $dayNames[$r->day_of_week] ?? ucfirst($r->day_of_week);
            $r->class_date_str  = $classDateTime->format('d/m/Y');
        }

        $upcoming    = $reservations->filter(fn($r) => $r->status === 'confirmed' && !$r->is_past_class)->values();
        $pastHistory = $reservations->filter(fn($r) => $r->status === 'confirmed' && $r->is_past_class)->take(30)->values();
        $cancelled   = $reservations->filter(fn($r) => $r->status === 'cancelled')->take(15)->values();

        return view('my-reservations', compact('upcoming', 'pastHistory', 'cancelled', 'user', 'dayNames'));
    })->name('my-reservations');

    Route::get('/my-payments', function () {
        $user = Auth::user();
        $payments = DB::table('gym_payments')->where('user_id', $user->id)->orderByDesc('payment_date')->get();
        return view('my-payments', compact('payments', 'user'));
    })->name('my-payments');

    Route::post('/reservations/{schedule}', 'App\Http\Controllers\ReservationController@store')->name('reservations.store');
    Route::delete('/reservations/{reservation}', 'App\Http\Controllers\ReservationController@destroy')->name('reservations.destroy');

    Route::post('/subscription/cancel', function () {
        $user = Auth::user();
        $sub = DB::table('gym_subscriptions')->where('user_id', $user->id)->where('status', 'active')->where('plan_type', '!=', 'free')->first();
        if (!$sub)
            return back()->with('error', 'No tienes suscripción activa.');
        DB::table('gym_subscriptions')->where('id', $sub->id)->update(['status' => 'cancelled', 'updated_at' => now()]);
        DB::table('gym_users')->where('id', $user->id)->update(['plan_type' => 'free', 'updated_at' => now()]);
        return back()->with('success', 'Suscripción cancelada.');
    })->name('subscription.cancel');
});

// ============================================================================
// 🔹 PANEL DE ADMIN MEJORADO
// ============================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', 'App\Http\Controllers\AdminController@index')->name('admin.dashboard');

    // Usuarios
    Route::get('/users', 'App\Http\Controllers\AdminController@usersIndex')->name('admin.users.index');
    Route::get('/users/create', 'App\Http\Controllers\AdminController@usersCreate')->name('admin.users.create');
    Route::post('/users', 'App\Http\Controllers\AdminController@usersStore')->name('admin.users.store');
    Route::get('/users/{id}/edit', 'App\Http\Controllers\AdminController@usersEdit')->name('admin.users.edit');
    Route::patch('/users/{id}', 'App\Http\Controllers\AdminController@usersUpdate')->name('admin.users.update');
    Route::get('/users/{id}/payments', 'App\Http\Controllers\AdminController@usersPayments')->name('admin.users.payments');
    Route::get('/users/{id}/audit', 'App\Http\Controllers\AdminController@usersAudit')->name('admin.users.audit');

    // Actividades
    Route::get('/activities', 'App\Http\Controllers\AdminController@activitiesIndex')->name('admin.activities.index');
    Route::get('/activities/create', 'App\Http\Controllers\AdminController@activitiesCreate')->name('admin.activities.create');
    Route::post('/activities', 'App\Http\Controllers\AdminController@activitiesStore')->name('admin.activities.store');
    Route::get('/activities/{id}/edit', 'App\Http\Controllers\AdminController@activitiesEdit')->name('admin.activities.edit');
    Route::patch('/activities/{id}', 'App\Http\Controllers\AdminController@activitiesUpdate')->name('admin.activities.update');
    Route::delete('/activities/{id}', 'App\Http\Controllers\AdminController@activitiesDelete')->name('admin.activities.delete');

    // Horarios
    Route::get('/schedules', 'App\Http\Controllers\AdminController@schedulesIndex')->name('admin.schedules.index');
    Route::get('/schedules/create', 'App\Http\Controllers\AdminController@schedulesCreate')->name('admin.schedules.create');
    Route::post('/schedules', 'App\Http\Controllers\AdminController@schedulesStore')->name('admin.schedules.store');
    Route::get('/schedules/{id}/edit', 'App\Http\Controllers\AdminController@schedulesEdit')->name('admin.schedules.edit');
    Route::patch('/schedules/{id}', 'App\Http\Controllers\AdminController@schedulesUpdate')->name('admin.schedules.update');
    Route::delete('/schedules/{id}', 'App\Http\Controllers\AdminController@schedulesDelete')->name('admin.schedules.delete');

    // Pagos
    Route::get('/payments', 'App\Http\Controllers\AdminController@payments')->name('admin.payments');
    Route::post('/payments/generate', 'App\Http\Controllers\AdminController@generatePayments')->name('admin.payments.generate');

    // Suscripciones
    Route::get('/subscriptions', 'App\Http\Controllers\AdminController@subscriptions')->name('admin.subscriptions');
    Route::post('/subscriptions/renew', 'App\Http\Controllers\AdminController@renewSubscriptions')->name('admin.subscriptions.renew');

    // Reportes
    Route::get('/reports', 'App\Http\Controllers\AdminController@reports')->name('admin.reports');

    // Configuración
    Route::get('/settings', 'App\Http\Controllers\AdminController@settings')->name('admin.settings');
    Route::patch('/settings', 'App\Http\Controllers\AdminController@settingsUpdate')->name('admin.settings.update');
});

// ============================================================================
// 🔹 AUTH (Breeze) - ¡ESTO ES CRÍTICO!
// ============================================================================
require __DIR__ . '/auth.php';
