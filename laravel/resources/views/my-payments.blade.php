<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Pagos - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <style>
        :root {
            --bg-dark: #0a0a0f;
            --bg-card: rgba(23, 31, 47, 0.85);
            --primary-start: #7C3AED;
            --primary-end: #EC4899;
            --accent: #06B6D4;
            --text-primary: #fff;
            --text-secondary: #9CA3AF;
            --success: #10B981;
            --warning: #F59E0B;
            --error: #EF4444;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Manrope', system-ui, sans-serif; background: var(--bg-dark); color: var(--text-primary); min-height: 100vh; }
        body::before {
            content: ''; position: fixed; inset: 0; z-index: -1;
            background-image: url('{{ asset('images/backgrounds/auth.webp') }}');
            background-size: cover; background-position: center;
            filter: brightness(0.35) saturate(1.1);
        }
        body::after {
            content: ''; position: fixed; inset: 0; z-index: -1; pointer-events: none;
            background: radial-gradient(ellipse at top, rgba(124,58,237,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse at bottom right, rgba(236,72,153,0.1) 0%, transparent 60%);
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed; top: 0; width: 100%;
            background: rgba(17,24,39,0.92); backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 0.85rem 2rem; display: flex; justify-content: space-between; align-items: center; z-index: 200;
        }
        .logo {
            font-weight: 800; font-size: 1.25rem; text-decoration: none;
            display: flex; align-items: center; gap: 0.6rem;
            background: linear-gradient(135deg, var(--primary-start), var(--primary-end));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .logo i { -webkit-text-fill-color: var(--primary-start); }
        .nav-links { display: flex; align-items: center; gap: 0.5rem; flex-wrap: nowrap; }
        .nav-link {
            color: var(--text-secondary); text-decoration: none; font-size: 0.875rem; font-weight: 500;
            padding: 0.4rem 0.7rem; border-radius: 0.5rem; transition: var(--transition); white-space: nowrap;
        }
        .nav-link:hover, .nav-link.active { color: var(--text-primary); background: rgba(255,255,255,0.06); }
        .user-chip {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.3rem 0.75rem; border-radius: 2rem;
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
            font-size: 0.8rem; color: var(--text-secondary); white-space: nowrap;
            font-family: inherit; cursor: pointer; transition: var(--transition);
        }
        .user-chip:hover { background: rgba(255,255,255,0.09); border-color: rgba(124,58,237,0.4); }
        .plan-badge {
            padding: 0.15rem 0.5rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        }
        .plan-badge.free    { background: rgba(100,116,139,0.25); color: #94A3B8; }
        .plan-badge.basico  { background: rgba(59,130,246,0.2);   color: #60A5FA; }
        .plan-badge.premium { background: rgba(236,72,153,0.2);   color: #EC4899; }
        .btn-logout {
            padding: 0.4rem 0.85rem; background: rgba(239,68,68,0.15); color: #F87171;
            border: 1px solid rgba(239,68,68,0.3); border-radius: 0.5rem;
            font-size: 0.85rem; font-weight: 500; cursor: pointer; white-space: nowrap;
        }
        .hamburger {
            display: none; background: none; border: 1px solid rgba(255,255,255,0.15);
            color: var(--text-primary); font-size: 1.25rem; cursor: pointer;
            padding: 0.35rem 0.6rem; border-radius: 0.4rem;
        }

        /* ── LAYOUT ── */
        .container { max-width: 72rem; margin: 0 auto; padding: 5.5rem 1.5rem 2rem; }
        .page-header { margin-bottom: 1.75rem; }
        .page-header h1 { font-size: 1.6rem; font-weight: 700; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.5rem; }
        .page-header p { color: var(--text-secondary); font-size: 0.9rem; }

        /* ── RESUMEN ── */
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .summary-card {
            background: var(--bg-card); border: 1px solid rgba(255,255,255,0.08); border-radius: 1rem;
            padding: 1.25rem 1.5rem; backdrop-filter: blur(10px);
        }
        .summary-label { font-size: 0.78rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem; }
        .summary-value { font-size: 1.6rem; font-weight: 700; }

        /* ── TABLA + DataTables dark theme ── */
        .table-wrap { background: var(--bg-card); backdrop-filter: blur(10px); border-radius: 1rem; overflow: hidden; }
        .table-wrap .dt-layout-row { padding: 0.75rem 1rem; }
        .dt-search input, .dt-length select {
            background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.12) !important;
            color: var(--text-primary) !important; border-radius: 0.5rem !important; padding: 0.35rem 0.6rem !important;
            font-family: 'Manrope', sans-serif !important; font-size: 0.85rem !important;
        }
        .dt-search label, .dt-length label, .dt-info { color: var(--text-secondary) !important; font-size: 0.82rem !important; }
        table.dataTable { width: 100% !important; border-collapse: collapse !important; min-width: 480px; }
        table.dataTable thead th { background: rgba(124,58,237,0.18) !important; color: var(--text-primary) !important; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        table.dataTable tbody tr { background: transparent !important; }
        table.dataTable tbody tr td { padding: 0.9rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.05) !important; color: var(--text-primary); }
        table.dataTable tbody tr:hover td { background: rgba(255,255,255,0.025) !important; }
        table.dataTable tbody tr:last-child td { border-bottom: none !important; }
        .dt-paging .dt-paging-button { background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--text-secondary) !important; border-radius: 0.4rem !important; margin: 0 2px !important; }
        .dt-paging .dt-paging-button.current, .dt-paging .dt-paging-button:hover { background: rgba(124,58,237,0.35) !important; color: var(--text-primary) !important; border-color: rgba(124,58,237,0.5) !important; }

        .badge {
            display: inline-block; padding: 0.2rem 0.6rem; border-radius: 1rem;
            font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
        }
        .badge-success { background: rgba(16,185,129,0.2); color: var(--success); }
        .badge-warning { background: rgba(245,158,11,0.2); color: var(--warning); }
        .badge-danger  { background: rgba(239,68,68,0.2);  color: var(--error); }
        .badge-info    { background: rgba(6,182,212,0.2);  color: var(--accent); }

        .empty { text-align: center; padding: 2.5rem; color: var(--text-secondary); font-size: 0.9rem; }
        .empty i { font-size: 2.5rem; opacity: 0.35; display: block; margin-bottom: 0.5rem; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .hamburger { display: block; }
            .nav-links {
                display: none; flex-direction: column; align-items: flex-start;
                position: fixed; top: 57px; left: 0; right: 0;
                background: rgba(10,10,20,0.97); backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(255,255,255,0.08);
                padding: 1rem 1.5rem; gap: 0.4rem; z-index: 199; max-height: calc(100vh - 57px); overflow-y: auto;
            }
            .nav-links.open { display: flex; }
            .nav-links .user-chip { margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.07); padding-top: 0.75rem; }
            .navbar { padding: 0.85rem 1rem; }
            .container { padding: 5rem 1rem 1.5rem; }
        }
        select option { background: #0a0a0f !important; color: #fff !important; }

        /* ── TOAST ── */
        #toast-container { position: fixed; top: 4.5rem; right: 1.25rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.5rem; pointer-events: none; }
        .toast { background: rgba(23,31,47,0.97); border: 1px solid rgba(255,255,255,0.12); border-radius: 0.75rem; padding: 0.75rem 1.1rem; display: flex; align-items: center; gap: 0.6rem; font-size: 0.88rem; font-weight: 500; color: var(--text-primary); min-width: 240px; max-width: 340px; backdrop-filter: blur(16px); pointer-events: all; animation: toastIn 0.3s ease-out; box-shadow: 0 8px 32px rgba(0,0,0,0.4); }
        .toast.success { border-left: 3px solid var(--success); }
        .toast.error   { border-left: 3px solid var(--error); }
        @keyframes toastIn { from { opacity:0; transform: translateX(20px); } to { opacity:1; transform: translateX(0); } }
    </style>
</head>
<body>
    <div id="toast-container"></div>
    @include('partials.client-nav', ['activePage' => 'payments'])

    <main class="container">
        <div class="page-header">
            <h1><i class="bi bi-credit-card"></i> Mis Pagos</h1>
            <p>Historial completo de tus pagos y suscripciones.</p>
        </div>

        {{-- Resumen rápido --}}
        @php
            $totalPaid    = $payments->where('status', 'paid')->sum('amount');
            $totalPending = $payments->where('status', 'pending')->count();
        @endphp
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Total pagado</div>
                <div class="summary-value" style="color:var(--success);">{{ number_format($totalPaid, 2) }} €</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Pendientes</div>
                <div class="summary-value" style="color:var(--warning);">{{ $totalPending }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Plan actual</div>
                <div class="summary-value" style="font-size:1.1rem;margin-top:0.25rem;">
                    <span class="plan-badge {{ $user->plan_type ?? 'free' }}" style="font-size:0.9rem;padding:0.3rem 0.8rem;">
                        {{ strtoupper($user->plan_type ?? 'FREE') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="table-wrap">
            <table id="paymentsTable">
                <thead>
                    <tr>
                        <th>Fecha</th><th>Concepto</th><th>Importe</th><th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $p)
                    <tr>
                        <td style="color:var(--text-secondary);font-size:0.88rem;">{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                        <td>Suscripción <span class="badge badge-info">{{ ucfirst($p->plan_type) }}</span></td>
                        <td><strong>{{ number_format($p->amount, 2) }} €</strong></td>
                        <td>
                            @if($p->status === 'paid')
                                <span class="badge badge-success"><i class="bi bi-check-circle"></i> Pagado</span>
                            @elseif($p->status === 'pending')
                                <span class="badge badge-warning"><i class="bi bi-clock"></i> Pendiente</span>
                            @else
                                <span class="badge badge-danger"><i class="bi bi-x-circle"></i> Cancelado</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script>
        $.fn.dataTable.defaults.language = {
            emptyTable:"No hay pagos registrados",search:"Buscar:",lengthMenu:"Mostrar _MENU_ filas",
            info:"Mostrando _START_ a _END_ de _TOTAL_ pagos",infoEmpty:"Sin pagos",
            infoFiltered:"(filtrado de _MAX_)",paginate:{first:"«",previous:"‹",next:"›",last:"»"}
        };
        $(document).ready(function(){
            $('#paymentsTable').DataTable({
                order:[[0,'desc']],
                language:{ emptyTable:'No hay pagos registrados', zeroRecords:'No se encontraron pagos' }
            });
        });

        // Flash messages manejados globalmente por client-nav (SweetAlert2)
    </script>
</body>
</html>
