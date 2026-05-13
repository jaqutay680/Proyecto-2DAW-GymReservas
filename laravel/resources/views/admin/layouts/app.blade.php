<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Admin') - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

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

        body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg-dark); color: var(--text-primary); overflow-x: hidden; }

        .admin-container { display: flex; min-height: 100vh; }

        .sidebar {
            position: fixed; left: 0; top: 0; width: 280px; height: 100vh;
            background: var(--bg-darker); border-right: 1px solid var(--border-color);
            padding: 2rem 0; overflow-y: auto; z-index: 1000; transition: var(--transition);
        }

        .sidebar-header { padding: 0 1.5rem 2rem; border-bottom: 1px solid var(--border-color); margin-bottom: 2rem; }

        .sidebar-logo {
            display: flex; align-items: center; gap: 0.75rem; font-size: 1.35rem; font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none;
        }

        .sidebar-logo i { -webkit-text-fill-color: var(--primary); font-size: 1.5rem; }

        .sidebar-menu { list-style: none; }
        .sidebar-menu li { margin: 0.5rem 0; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.5rem;
            color: var(--text-secondary); text-decoration: none; transition: var(--transition);
            border-left: 3px solid transparent;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            color: var(--primary); background: rgba(124, 58, 237, 0.1); border-left-color: var(--primary);
        }
        .sidebar-menu i { font-size: 1.25rem; width: 1.5rem; }

        .main-content { margin-left: 280px; flex: 1; background: var(--bg-dark); }

        .topbar {
            background: var(--bg-card); border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-title { font-size: 1.5rem; font-weight: 600; }
        .topbar-actions { display: flex; align-items: center; gap: 1.5rem; }

        .btn-logout {
            display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.5rem; text-decoration: none; font-size: 0.9rem; cursor: pointer; transition: var(--transition);
        }
        .btn-logout:hover { background: rgba(239, 68, 68, 0.25); }

        .content { padding: 2rem; }

        .card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }

        .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .metric-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        .metric-info h3 { font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .metric-value { font-size: 2rem; font-weight: 700; color: var(--primary); }
        .metric-icon { font-size: 3rem; opacity: 0.2; }

        .table { width: 100%; border-collapse: collapse; background: var(--bg-card); border-radius: 0.75rem; overflow: hidden; }
        .table th { background: rgba(124, 58, 237, 0.1); padding: 1rem; text-align: left; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-color); }
        .table td { padding: 1rem; border-bottom: 1px solid var(--border-color); }
        .table tbody tr:hover { background: rgba(124, 58, 237, 0.05); }
        .table tbody tr:last-child td { border-bottom: none; }

        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .badge-success { background: rgba(16, 185, 129, 0.2); color: #10B981; }
        .badge-danger { background: rgba(239, 68, 68, 0.2); color: #EF4444; }
        .badge-warning { background: rgba(245, 158, 11, 0.2); color: #F59E0B; }
        .badge-info { background: rgba(6, 182, 212, 0.2); color: #06B6D4; }
        .badge-secondary { background: rgba(148, 163, 184, 0.2); color: #94A3B8; }

        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.25rem; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; text-decoration: none; transition: var(--transition); font-size: 0.9rem; font-family: inherit; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary { background: var(--bg-hover); color: var(--text-primary); }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }
        .btn-danger { background: rgba(239, 68, 68, 0.15); color: #F87171; }
        .btn-danger:hover { background: rgba(239, 68, 68, 0.25); }
        .btn-success { background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16,185,129,0.3); }
        .btn-success:hover { background: rgba(16, 185, 129, 0.25); }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }

        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; }
        .form-control { width: 100%; padding: 0.75rem; background: var(--bg-hover); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 0.5rem; font-family: inherit; transition: var(--transition); }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1); }
        select.form-control option { background: var(--bg-darker); color: var(--text-primary); }
        textarea.form-control { resize: vertical; min-height: 100px; }

        .alert { padding: 1rem 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
        .alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #10B981; }
        .alert-danger { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #EF4444; }

        .pagination { display: flex; gap: 0.25rem; justify-content: center; margin-top: 1rem; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.1); border-radius: 0.35rem; color: #fff; text-decoration: none; font-size: 0.9rem; }
        .pagination .active span { background: var(--primary); }

        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; border-right: none; border-bottom: 1px solid var(--border-color); padding: 1rem; }
            .main-content { margin-left: 0; }
            .topbar { flex-direction: column; gap: 1rem; text-align: center; }
            .sidebar-menu { display: flex; gap: 0.5rem; flex-wrap: wrap; }
            .sidebar-menu li { margin: 0; }
            .sidebar-menu a { flex-direction: column; padding: 0.5rem 0.75rem; font-size: 0.8rem; justify-content: center; }
            .sidebar-menu i { margin: 0; }
            .metrics-grid { grid-template-columns: 1fr; }
            .content { padding: 1rem; }
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-secondary); }

        /* ── DataTables dark theme ── */
        div.dataTables_wrapper { color: var(--text-primary); font-size: 0.875rem; }
        div.dataTables_length select,
        div.dataTables_filter input {
            background: var(--bg-hover) !important; color: var(--text-primary) !important;
            border: 1px solid var(--border-color) !important; border-radius: 0.4rem; padding: 0.35rem 0.6rem;
        }
        div.dataTables_length label, div.dataTables_filter label { color: var(--text-secondary); }
        div.dataTables_info { color: var(--text-secondary); font-size: 0.82rem; padding-top: 0.75rem; }
        div.dataTables_paginate { padding-top: 0.75rem; }
        div.dataTables_paginate .paginate_button {
            color: var(--text-primary) !important; background: var(--bg-hover) !important;
            border: 1px solid var(--border-color) !important; border-radius: 0.35rem !important;
            padding: 0.3rem 0.75rem !important; margin: 0 0.1rem !important; cursor: pointer;
        }
        div.dataTables_paginate .paginate_button.current,
        div.dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: var(--primary) !important; color: #fff !important; border-color: var(--primary) !important;
        }
        div.dataTables_paginate .paginate_button.disabled { opacity: 0.35 !important; cursor: not-allowed !important; }
        table.dataTable thead th { border-bottom: 1px solid var(--border-color) !important; cursor: pointer; }
        table.dataTable.no-footer { border-bottom: 1px solid var(--border-color) !important; }
        table.dataTable tbody tr:hover td { background: rgba(124,58,237,0.06) !important; }
        table.dataTable thead .sorting::before, table.dataTable thead .sorting::after,
        table.dataTable thead .sorting_asc::after, table.dataTable thead .sorting_desc::after { opacity: 0.5; }

        /* SweetAlert2 dark theme override */
        .swal2-popup { font-family: 'Inter', system-ui, sans-serif !important; }
        .swal2-title { font-size: 1.1rem !important; }
        .swal2-html-container { font-size: 0.9rem !important; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <img src="{{ asset('images/icons/icon-gym-logo.webp') }}" alt="Logo"
                         style="width:1.8rem;height:1.8rem;object-fit:contain;border-radius:0.3rem;-webkit-text-fill-color:initial;"
                         onerror="this.style.display='none'">
                    GYMRESERVAS
                </a>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a></li>
                <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Usuarios
                </a></li>
                <li><a href="{{ route('admin.activities.index') }}" class="{{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                    <i class="bi bi-activity"></i> Actividades
                </a></li>
                <li><a href="{{ route('admin.schedules.index') }}" class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Horarios
                </a></li>
                <li><a href="{{ route('admin.payments') }}" class="{{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i> Pagos
                </a></li>
                <li><a href="{{ route('admin.subscriptions') }}" class="{{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}">
                    <i class="bi bi-star-fill"></i> Suscripciones
                </a></li>
                <li><a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                </a></li>
                <li><a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> Configuración
                </a></li>
            </ul>
        </nav>

        <div class="main-content">
            <div class="topbar">
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-actions">
                    <span style="color: var(--text-secondary);">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </button>
                    </form>
                </div>
            </div>

            <div class="content">
                {{-- Flash messages via SweetAlert2 (ver scripts al final) --}}

                @yield('content')
            </div>
        </div>
    </div>
    <!-- jQuery + DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

    <script>
    // ── SweetAlert2: confirmación de acciones admin ──
    function adminConfirm(icon, title, msg, form, btnClass, btnText) {
        Swal.fire({
            title: title,
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: btnText || 'Confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: btnClass === 'modal-btn-danger' ? '#EF4444' : '#7C3AED',
            cancelButtonColor: '#475569',
            background: '#1E293B',
            color: '#F1F5F9',
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
        return false;
    }

    // ── Flash messages via SweetAlert2 ──
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success', title: '¡Éxito!', text: @json(session('success')),
            timer: 4000, showConfirmButton: false,
            toast: true, position: 'top-end',
            background: '#1E293B', color: '#F1F5F9', iconColor: '#10B981'
        });
        @endif
        @if(session('error'))
        Swal.fire({
            icon: 'error', title: 'Error', text: @json(session('error')),
            confirmButtonColor: '#7C3AED',
            background: '#1E293B', color: '#F1F5F9'
        });
        @endif
    });

    // ── DataTables defaults ──
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            lengthMenu: 'Mostrar _MENU_ registros',
            zeroRecords: 'No se encontraron resultados',
            info: 'Mostrando _START_–_END_ de _TOTAL_',
            infoEmpty: 'Sin registros',
            infoFiltered: '(filtrado de _MAX_ total)',
            search: 'Buscar:',
            paginate: { first:'«', last:'»', next:'›', previous:'‹' }
        },
        pageLength: 15,
        lengthMenu: [10, 15, 25, 50],
        order: []
    });
    </script>

    @stack('scripts')
</body>
</html>
