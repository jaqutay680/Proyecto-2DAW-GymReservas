<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trazabilidad de {{ $user->name }} - Admin</title>
    <style>
        :root {
            --bg-dark: #0a0a0f;
            --bg-card: rgba(23, 31, 47, 0.85);
            --text-primary: #fff;
            --text-secondary: #9CA3AF;
            --success: #10B981;
            --error: #EF4444;
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
            max-width: 1000px;
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
            font-size: 0.9rem;
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

        .json-diff {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem;
            border-radius: 0.35rem;
            font-family: monospace;
            font-size: 0.8rem;
            max-height: 100px;
            overflow: auto;
        }

        .json-old {
            color: var(--error);
        }

        .json-new {
            color: var(--success);
        }

        .empty {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📜 Trazabilidad de {{ e($user->name) }}</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn">⬅️ Volver</a>
        </div>

        <div class="user-card">
            <strong>Email:</strong> {{ e($user->email) }}<br>
            <strong>Plan:</strong> {{ ucfirst($user->plan_type ?? 'free') }} |
            <strong>Estado:</strong> {{ ucfirst($user->membership_status ?? 'active') }}
        </div>

        <h2 style="margin:2rem 0 1rem;">🕐 Historial de Cambios</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Acción</th>
                    <th>Campo</th>
                    <th>Antes</th>
                    <th>Después</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditLogs as $log)
                    <tr>
                        <td>{{ date('d/m/Y H:i', strtotime($log->created_at)) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $log->action_type)) }}</td>
                        <td>
                            @php $old = json_decode($log->old_values ?? '{}', true);
                            $new = json_decode($log->new_values ?? '{}', true); @endphp
                            @foreach(array_keys($new) as $field)
                                <div><strong>{{ $field }}:</strong></div>
                            @endforeach
                        </td>
                        <td class="json-old">
                            @if($log->old_values)
                                @foreach(json_decode($log->old_values, true) as $k => $v)
                                    <div>{{ $k }}: {{ is_string($v) ? e($v) : $v }}</div>
                                @endforeach
                            @else
                                <em>-</em>
                            @endif
                        </td>
                        <td class="json-new">
                            @if($log->new_values)
                                @foreach(json_decode($log->new_values, true) as $k => $v)
                                    <div>{{ $k }}: {{ is_string($v) ? e($v) : $v }}</div>
                                @endforeach
                            @else
                                <em>-</em>
                            @endif
                        </td>
                        <td>{{ $log->ip_address ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">📭 No hay cambios registrados para este usuario.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $auditLogs->links() }}
    </div>
</body>

</html>