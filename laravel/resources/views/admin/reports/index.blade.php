@extends('admin.layouts.app')

@section('page-title', 'Reportes')

@section('content')

@php
$totalUsers = \DB::table('gym_users')->where('role','cliente')->count();
$activeUsers = \DB::table('gym_users')->where('role','cliente')->where('membership_status','active')->count();
$totalRevenue = \DB::table('gym_payments')->where('status','paid')->sum('amount');
$monthRevenue = \DB::table('gym_payments')->where('status','paid')->whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount');
$totalReservations = \DB::table('gym_reservations')->where('status','confirmed')->count();
$planDist = \DB::table('gym_users')->where('role','cliente')->selectRaw('plan_type, COUNT(*) as count')->groupBy('plan_type')->get()->keyBy('plan_type');
@endphp

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-info">
            <h3>Total Usuarios</h3>
            <div class="metric-value">{{ $totalUsers }}</div>
            <div style="color:var(--text-secondary);font-size:0.85rem;margin-top:0.25rem;">{{ $activeUsers }} activos</div>
        </div>
        <div class="metric-icon"><i class="bi bi-people-fill"></i></div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <h3>Ingresos Este Mes</h3>
            <div class="metric-value">{{ number_format($monthRevenue, 2) }}€</div>
            <div style="color:var(--text-secondary);font-size:0.85rem;margin-top:0.25rem;">Total: {{ number_format($totalRevenue, 2) }}€</div>
        </div>
        <div class="metric-icon"><i class="bi bi-currency-euro"></i></div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <h3>Reservas Confirmadas</h3>
            <div class="metric-value">{{ $totalReservations }}</div>
        </div>
        <div class="metric-icon"><i class="bi bi-calendar-check"></i></div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <!-- Distribución de planes -->
    <div class="card">
        <div class="card-title"><i class="bi bi-pie-chart"></i> Distribución de Planes</div>
        @php $planLabels = ['free'=>'Free','basico'=>'Básico','premium'=>'Premium']; @endphp
        @foreach($planLabels as $key => $label)
        @php $count = $planDist[$key]->count ?? 0; $pct = $totalUsers > 0 ? round($count/$totalUsers*100) : 0; @endphp
        <div style="margin-bottom:1rem;">
            <div style="display:flex; justify-content:space-between; margin-bottom:0.35rem; font-size:0.9rem;">
                <span>{{ $label }}</span>
                <span style="color:var(--text-secondary);">{{ $count }} usuarios ({{ $pct }}%)</span>
            </div>
            <div style="background:var(--bg-hover); border-radius:9999px; height:8px;">
                <div style="background:var(--primary); width:{{ $pct }}%; height:8px; border-radius:9999px; transition:width 0.5s;"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagos por estado -->
    <div class="card">
        <div class="card-title"><i class="bi bi-credit-card"></i> Estado de Pagos</div>
        @php
        $paymentStats = \DB::table('gym_payments')->selectRaw('status, COUNT(*) as count, SUM(amount) as total')->groupBy('status')->get()->keyBy('status');
        @endphp
        @foreach(['paid'=>['Pagados','badge-success'],'pending'=>['Pendientes','badge-warning'],'cancelled'=>['Cancelados','badge-danger']] as $status => [$label, $badgeClass])
        @php $stat = $paymentStats[$status] ?? null; @endphp
        <div style="display:flex; justify-content:space-between; align-items:center; padding:0.75rem 0; border-bottom:1px solid var(--border-color);">
            <span class="badge {{ $badgeClass }}">{{ $label }}</span>
            <span>{{ $stat ? $stat->count : 0 }} pagos</span>
            <span style="color:var(--text-secondary);">{{ $stat ? number_format($stat->total, 2) : '0.00' }}€</span>
        </div>
        @endforeach
    </div>
</div>

<!-- Últimas reservas -->
<div class="card">
    <div class="card-title"><i class="bi bi-clock-history"></i> Últimas 10 Reservas</div>
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr><th>Usuario</th><th>Actividad</th><th>Día</th><th>Hora</th><th>Estado</th><th>Fecha</th></tr>
            </thead>
            <tbody>
                @php
                $latestReservations = \DB::table('gym_reservations as r')
                    ->join('gym_users as u','r.user_id','=','u.id')
                    ->join('gym_schedules as s','r.schedule_id','=','s.id')
                    ->join('gym_activities as a','s.activity_id','=','a.id')
                    ->select('r.status','r.created_at','u.name as user_name','a.name as activity_name','s.day_of_week','s.start_time')
                    ->orderByDesc('r.created_at')->limit(10)->get();
                $dayEs = ['monday'=>'Lunes','tuesday'=>'Martes','wednesday'=>'Miércoles','thursday'=>'Jueves','friday'=>'Viernes','saturday'=>'Sábado','sunday'=>'Domingo'];
                @endphp
                @foreach($latestReservations as $r)
                <tr>
                    <td>{{ $r->user_name }}</td>
                    <td>{{ $r->activity_name }}</td>
                    <td>{{ $dayEs[$r->day_of_week] ?? $r->day_of_week }}</td>
                    <td>{{ substr($r->start_time,0,5) }}</td>
                    <td>
                        <span class="badge {{ $r->status === 'confirmed' ? 'badge-success' : ($r->status === 'cancelled' ? 'badge-danger' : 'badge-warning') }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td style="color:var(--text-secondary);font-size:0.85rem;">{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
