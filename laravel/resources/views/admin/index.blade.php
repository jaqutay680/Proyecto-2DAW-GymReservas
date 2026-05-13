@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<!-- MÉTRICAS PRINCIPALES -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-info">
            <h3>Usuarios Activos</h3>
            <div class="metric-value">{{ $metrics['active_users'] ?? 0 }}</div>
        </div>
        <div class="metric-icon">
            <i class="bi bi-people-fill"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-info">
            <h3>Ingresos Este Mes</h3>
            <div class="metric-value">{{ number_format($metrics['total_revenue_month'] ?? 0, 2) }}€</div>
        </div>
        <div class="metric-icon">
            <i class="bi bi-currency-euro"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-info">
            <h3>Reservas Hoy</h3>
            <div class="metric-value">{{ $metrics['total_reservations_today'] ?? 0 }}</div>
        </div>
        <div class="metric-icon">
            <i class="bi bi-calendar-check"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-info">
            <h3>Esta Semana</h3>
            <div class="metric-value">{{ $metrics['reservations_this_week'] ?? 0 }}</div>
        </div>
        <div class="metric-icon">
            <i class="bi bi-bar-chart"></i>
        </div>
    </div>
</div>

<!-- SECCIÓN DE GRÁFICOS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- GRÁFICO DE DISTRIBUCIÓN DE PLANES -->
    <div class="card">
        <div class="card-title">
            <i class="bi bi-pie-chart"></i> Distribución de Planes
        </div>
        <canvas id="chartPlans" style="max-height: 300px;"></canvas>
    </div>

    <!-- GRÁFICO DE RESERVAS POR DÍA -->
    <div class="card">
        <div class="card-title">
            <i class="bi bi-graph-up"></i> Reservas Esta Semana
        </div>
        <canvas id="chartReservations" style="max-height: 300px;"></canvas>
    </div>
</div>

<!-- USUARIOS RECIENTES -->
<div class="card">
    <div class="card-title">
        <i class="bi bi-person-plus"></i> Usuarios Recientes
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Plan</th>
                <th>Estado</th>
                <th>Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentUsers ?? [] as $user)
            <tr>
                <td><strong>{{ $user->name }}</strong></td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge badge-info">
                        {{ ucfirst($user->plan_type ?? 'free') }}
                    </span>
                </td>
                <td>
                    @if($user->membership_status === 'active')
                        <span class="badge badge-success">Activo</span>
                    @elseif($user->membership_status === 'suspended')
                        <span class="badge badge-danger">Suspendido</span>
                    @else
                        <span class="badge badge-warning">{{ ucfirst($user->membership_status) }}</span>
                    @endif
                </td>
                <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') : '-' }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- PAGOS RECIENTES -->
<div class="card">
    <div class="card-title">
        <i class="bi bi-receipt"></i> Últimos Pagos
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Plan</th>
                <th>Importe</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentPayments ?? [] as $payment)
            <tr>
                <td><strong>{{ $payment->user_name ?? 'N/A' }}</strong></td>
                <td>{{ ucfirst($payment->plan_type ?? 'N/A') }}</td>
                <td>{{ number_format($payment->amount, 2) }}€</td>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                <td>
                    @if($payment->status === 'paid')
                        <span class="badge badge-success">Pagado</span>
                    @elseif($payment->status === 'pending')
                        <span class="badge badge-warning">Pendiente</span>
                    @else
                        <span class="badge badge-danger">Cancelado</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- SCRIPTS PARA GRÁFICOS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Distribución de Planes
    const ctxPlans = document.getElementById('chartPlans').getContext('2d');
    new Chart(ctxPlans, {
        type: 'doughnut',
        data: {
            labels: ['Free', 'Básico', 'Premium'],
            datasets: [{
                data: [
                    {{ $planDistribution['free'] ?? 0 }},
                    {{ $planDistribution['basico'] ?? 0 }},
                    {{ $planDistribution['premium'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(148, 163, 184, 0.5)',
                    'rgba(59, 130, 246, 0.5)',
                    'rgba(124, 58, 237, 0.5)'
                ],
                borderColor: [
                    'rgba(148, 163, 184, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(124, 58, 237, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: { color: '#F1F5F9', font: { size: 12 } }
                }
            }
        }
    });

    // Gráfico de Reservas por Día
    const ctxReservations = document.getElementById('chartReservations').getContext('2d');
    const reservationsByDay = @json($reservationsByDay ?? []);
    
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    const dayLabels = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    const values = days.map(day => reservationsByDay[day] ?? 0);

    new Chart(ctxReservations, {
        type: 'bar',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Reservas',
                data: values,
                backgroundColor: 'rgba(124, 58, 237, 0.5)',
                borderColor: 'rgba(124, 58, 237, 1)',
                borderWidth: 2,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: undefined,
            plugins: {
                legend: {
                    labels: { color: '#F1F5F9', font: { size: 12 } }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                    ticks: { color: '#94A3B8' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94A3B8' }
                }
            }
        }
    });
</script>

@endsection