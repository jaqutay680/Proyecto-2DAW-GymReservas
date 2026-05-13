<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pagos de {{ $user->name ?? 'Usuario' }} - Admin</title>
    <style>
        :root {
            --bg-dark: #0a0a0f;
            --bg-card: rgba(23, 31, 47, 0.85);
            --text-primary: #fff;
            --text-secondary: #9CA3AF;
            --success: #10B981;
            --error: #EF4444;
            --warning: #F59E0B;
            --primary: #7C3AED;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            padding: 2rem;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .user-card {
            background: var(--bg-card);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-card h2 {
            margin-bottom: 1rem;
        }

        .user-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            font-size: 0.95rem;
        }

        .user-info div {
            color: var(--text-secondary);
        }

        .user-info strong {
            color: var(--text-primary);
            display: block;
            margin-bottom: 0.25rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-card);
            border-radius: 1rem;
            overflow: hidden;
        }

        th,
        td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: left;
        }

        th {
            background: rgba(124, 58, 237, 0.2);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .status-paid {
            color: var(--success);
            font-weight: 600;
        }

        .status-pending {
            color: var(--warning);
            font-weight: 600;
        }

        .status-cancelled {
            color: var(--error);
            font-weight: 600;
        }

        .empty {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        @media (max-width:640px) {
            body {
                padding: 1rem;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>💳 Pagos de {{ e($user->name ?? 'Usuario') }}</h1>
            <div style="display:flex; gap:0.75rem;">
                <a href="{{ url('/admin') }}" class="btn">⬅️ Volver al Admin</a>
                <a href="{{ route('admin.payments') }}" class="btn" style="background:#10B981;">📊 Todos los pagos</a>
            </div>
        </div>

        <!-- 🔹 Info del usuario -->
        <div class="user-card">
            <h2>👤 Información</h2>
            <div class="user-info">
                <div><strong>Email</strong> {{ e($user->email ?? 'N/A') }}</div>
                <div><strong>Plan</strong> {{ ucfirst($user->plan_type ?? 'free') }}</div>
                <div><strong>Estado</strong> {{ ucfirst($user->membership_status ?? 'active') }}</div>
                <div><strong>DNI</strong> {{ $user->dni ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- 🔹 Tabla de pagos -->
        <h2 style="margin:2rem 0 1rem;">📜 Historial de pagos</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Importe</th>
                    <th>Estado</th>
                    <th>Próximo cobro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments ?? [] as $p)
                    <tr>
                        <td>{{ date('d/m/Y', strtotime($p->payment_date)) }}</td>
                        <td>Suscripción {{ ucfirst($p->plan_type) }}</td>
                        <td>{{ number_format($p->amount, 2) }} €</td>
                        <td class="status-{{ $p->status ?? 'pending' }}">{{ ucfirst($p->status ?? 'pending') }}</td>
                        <td>{{ $p->next_billing_date ? date('d/m/Y', strtotime($p->next_billing_date)) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">📭 Este usuario no tiene pagos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>