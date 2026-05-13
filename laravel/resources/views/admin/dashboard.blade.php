<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #7C3AED;
            --primary-dark: #6D28D9;
            --secondary: #06B6D4;
            --danger: #EF4444;
            --success: #10B981;
            --warning: #F59E0B;
            --bg-dark: #0F172A;
            --bg-darker: #020617;
            --bg-card: #1E293B;
            --bg-hover: #334155;
            --text-primary: #F1F5F9;
            --text-secondary: #94A3B8;
            --border-color: #334155;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* LAYOUT PRINCIPAL */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: var(--bg-darker);
            border-right: 1px solid var(--border-color);
            padding: 2rem 0;
            overflow-y: auto;
            z-index: 1000;
            transition: var(--transition);
        }

        .sidebar-header {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.35rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .sidebar-logo i {
            -webkit-text-fill-color: var(--primary);
            font-size: 1.5rem;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: var(--primary);
            background: rgba(124, 58, 237, 0.1);
            border-left-color: var(--primary);
        }

        .sidebar-menu i {
            font-size: 1.25rem;
            width: 1.5rem;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 280px;
            flex: 1;
            background: var(--bg-dark);
        }

        .topbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.15);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.25);
        }

        .content {
            padding: 2rem;
        }

        /* CARDS */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* GRID MÉTRICAS */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .metric-info h3 {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .metric-icon {
            font-size: 3rem;
            opacity: 0.2;
        }

        /* TABLA */
        .table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-card);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .table th {
            background: rgba(124, 58, 237, 0.1);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background: rgba(124, 58, 237, 0.05);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #10B981;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #EF4444;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #F59E0B;
        }

        .badge-info {
            background: rgba(6, 182, 212, 0.2);
            color: #06B6D4;
        }

        /* BOTONES */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #F87171;
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.25);
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
        }

        /* FORMULARIOS */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            background: var(--bg-hover);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 0.5rem;
            font-family: inherit;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        /* ALERTS */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10B981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #EF4444;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                padding: 1rem;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .sidebar-menu {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .sidebar-menu li {
                margin: 0;
            }

            .sidebar-menu a {
                flex-direction: column;
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
                justify-content: center;
            }

            .sidebar-menu i {
                margin: 0;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 1rem;
            }
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <i class="bi bi-bar-chart-line"></i> GYMRESERVAS
                </a>
            </div>

            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a></li>

                <li><a href="{{ route('admin.users.index') }}" class="@if(request()->routeIs('admin.users.*')) active @endif">
                    <i class="bi bi-people-fill"></i> Usuarios
                </a></li>

                <li><a href="{{ route('admin.activities.index') }}" class="@if(request()->routeIs('admin.activities.*')) active @endif">
                    <i class="bi bi-dumbbell"></i> Actividades
                </a></li>

                <li><a href="{{ route('admin.schedules.index') }}" class="@if(request()->routeIs('admin.schedules.*')) active @endif">
                    <i class="bi bi-calendar-check"></i> Horarios
                </a></li>

                <li><a href="{{ route('admin.payments') }}" class="@if(request()->routeIs('admin.payments')) active @endif">
                    <i class="bi bi-credit-card"></i> Pagos
                </a></li>

                <li><a href="{{ route('admin.subscriptions') }}" class="@if(request()->routeIs('admin.subscriptions')) active @endif">
                    <i class="bi bi-star-fill"></i> Suscripciones
                </a></li>

                <li><a href="{{ route('admin.reports') }}" class="@if(request()->routeIs('admin.reports')) active @endif">
                    <i class="bi bi-file-earmark-pdf"></i> Reportes
                </a></li>

                <li><a href="{{ route('admin.settings') }}" class="@if(request()->routeIs('admin.settings')) active @endif">
                    <i class="bi bi-gear-fill"></i> Configuración
                </a></li>
            </ul>
        </nav>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- TOPBAR -->
            <div class="topbar">
                <div class="topbar-title">
                    @yield('page-title', 'Dashboard')
                </div>
                <div class="topbar-actions">
                    <span class="text-secondary">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </button>
                    </form>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="content">
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Marcar link activo según ruta
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>