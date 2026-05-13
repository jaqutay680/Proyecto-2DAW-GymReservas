<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Reservas - GymReservas</title>
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

        /* ── SECCIONES ── */
        .section-title {
            font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;
            display: flex; align-items: center; gap: 0.5rem;
            padding-bottom: 0.6rem; border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        /* ── DATATABLES dark theme ── */
        .dt-wrap { background: var(--bg-card); backdrop-filter: blur(10px); border-radius: 1rem; margin-bottom: 2rem; overflow-x: auto; }
        .dt-wrap .dt-layout-row { padding: 0.75rem 1rem; }
        .dt-search input, .dt-length select {
            background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.12) !important;
            color: var(--text-primary) !important; border-radius: 0.5rem !important; padding: 0.35rem 0.6rem !important;
            font-family: 'Manrope', sans-serif !important; font-size: 0.85rem !important;
        }
        .dt-search label, .dt-length label, .dt-info { color: var(--text-secondary) !important; font-size: 0.82rem !important; }
        table.dataTable thead th, table.dataTable thead td {
            background: rgba(124,58,237,0.18) !important; color: var(--text-primary) !important;
            font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }
        table.dataTable tbody tr { background: transparent !important; }
        table.dataTable tbody tr td { border-bottom: 1px solid rgba(255,255,255,0.05) !important; color: var(--text-primary); }
        table.dataTable tbody tr:hover td { background: rgba(255,255,255,0.025) !important; }
        table.dataTable tbody tr:last-child td { border-bottom: none !important; }
        .dt-paging .dt-paging-button {
            background: rgba(255,255,255,0.06) !important; border: 1px solid rgba(255,255,255,0.1) !important;
            color: var(--text-secondary) !important; border-radius: 0.4rem !important; margin: 0 2px !important;
        }
        .dt-paging .dt-paging-button.current, .dt-paging .dt-paging-button:hover {
            background: rgba(124,58,237,0.35) !important; color: var(--text-primary) !important;
            border-color: rgba(124,58,237,0.5) !important;
        }
        table.dataTable { width: 100% !important; }

        /* ── BADGES ── */
        .badge {
            display: inline-block; padding: 0.2rem 0.6rem; border-radius: 1rem;
            font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
        }
        .badge-success { background: rgba(16,185,129,0.2); color: var(--success); }
        .badge-warning { background: rgba(245,158,11,0.2); color: var(--warning); }
        .badge-danger  { background: rgba(239,68,68,0.2);  color: var(--error); }
        .badge-muted   { background: rgba(148,163,184,0.15); color: var(--text-secondary); }

        /* ── BOTÓN CANCELAR ── */
        .btn-cancel {
            background: rgba(239,68,68,0.15); color: #F87171; border: 1px solid rgba(239,68,68,0.3);
            border-radius: 0.4rem; padding: 0.35rem 0.75rem; font-size: 0.82rem; font-weight: 500; cursor: pointer;
        }
        .btn-cancel:hover { background: rgba(239,68,68,0.25); }

        /* ── EMPTY ── */
        .empty { text-align: center; padding: 2.5rem; color: var(--text-secondary); font-size: 0.9rem; }
        .empty i { font-size: 2.5rem; opacity: 0.35; display: block; margin-bottom: 0.5rem; }

        /* ── TOAST ── */
        #toast-container {
            position: fixed; top: 4.5rem; right: 1.25rem; z-index: 9999;
            display: flex; flex-direction: column; gap: 0.5rem; pointer-events: none;
        }
        .toast {
            background: rgba(23,31,47,0.97); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.75rem; padding: 0.75rem 1.1rem;
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.88rem; font-weight: 500; color: var(--text-primary);
            min-width: 240px; max-width: 340px;
            backdrop-filter: blur(16px); pointer-events: all;
            animation: toastIn 0.3s ease-out;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .toast.success { border-left: 3px solid var(--success); }
        .toast.error   { border-left: 3px solid var(--error); }
        .toast.warning { border-left: 3px solid var(--warning); }
        @keyframes toastIn { from { opacity:0; transform: translateX(20px); } to { opacity:1; transform: translateX(0); } }

        /* ── CANCEL MODAL ── */
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px); z-index: 8000; align-items: center; justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: rgba(17,24,39,0.98); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 1.25rem; padding: 2rem; max-width: 400px; width: 90%;
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
            animation: modalIn 0.25s ease-out;
        }
        @keyframes modalIn { from { opacity:0; transform: scale(0.95); } to { opacity:1; transform: scale(1); } }
        .modal-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .modal-body  { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.5; }
        .modal-actions { display: flex; gap: 0.75rem; justify-content: flex-end; }
        .btn-modal-cancel {
            padding: 0.55rem 1.2rem; border-radius: 0.6rem; font-size: 0.9rem; font-weight: 600; cursor: pointer;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: var(--text-secondary);
        }
        .btn-modal-confirm {
            padding: 0.55rem 1.2rem; border-radius: 0.6rem; font-size: 0.9rem; font-weight: 600; cursor: pointer;
            background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.4); color: #F87171;
        }
        .btn-modal-confirm:hover { background: rgba(239,68,68,0.35); }

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

            /* Compact DataTables controls */
            .dt-wrap .dt-layout-row { padding: 0.5rem 0.75rem; }
            .dt-search label, .dt-length label { font-size: 0.78rem !important; }
        }

        /* ── MOBILE: hide less important table columns ── */
        @media (max-width: 600px) {
            /* Upcoming: hide "Fecha" (col 3) and "Sala" (col 5) */
            #upcomingTable th:nth-child(3), #upcomingTable td:nth-child(3),
            #upcomingTable th:nth-child(5), #upcomingTable td:nth-child(5) { display: none !important; }

            /* History: hide "Fecha" (col 3) and "Sala" (col 5) */
            #historyTable th:nth-child(3), #historyTable td:nth-child(3),
            #historyTable th:nth-child(5), #historyTable td:nth-child(5) { display: none !important; }

            /* Cancelled: hide "Sala" (col 4) */
            #cancelledTable th:nth-child(4), #cancelledTable td:nth-child(4) { display: none !important; }

            /* Smaller padding on mobile */
            table.dataTable tbody tr td,
            table.dataTable thead th { padding: 0.65rem 0.6rem !important; font-size: 0.82rem; }

            /* Wrap action button text */
            .btn-cancel { padding: 0.3rem 0.55rem; font-size: 0.78rem; }
        }
    </style>
</head>
<body>
    <!-- Toast container -->
    <div id="toast-container"></div>

    <!-- Cancel modal -->
    <div class="modal-overlay" id="cancelModal">
        <div class="modal-box">
            <div class="modal-title"><i class="bi bi-x-circle" style="color:#F87171;"></i> Cancelar reserva</div>
            <div class="modal-body" id="cancelModalBody">¿Estás seguro de que quieres cancelar esta reserva?</div>
            <div class="modal-actions">
                <button class="btn-modal-cancel" onclick="closeCancelModal()">No, mantener</button>
                <button class="btn-modal-confirm" id="cancelConfirmBtn">Sí, cancelar</button>
            </div>
        </div>
    </div>
    <!-- Hidden cancel form -->
    <form id="cancelForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    @include('partials.client-nav', ['activePage' => 'reservations'])

    <main class="container">

        {{-- ── PRÓXIMAS RESERVAS ── --}}
        <div class="section-title" style="color:#10B981;">
            <i class="bi bi-calendar-check-fill"></i> Próximas clases
            <span style="margin-left:auto;font-size:0.85rem;font-weight:600;background:rgba(16,185,129,0.15);color:#10B981;padding:0.2rem 0.6rem;border-radius:1rem;">{{ count($upcoming) }}</span>
        </div>
        <div class="dt-wrap">
            <table id="upcomingTable">
                <thead>
                    <tr>
                        <th>Clase</th><th>Día</th><th>Fecha</th><th>Horario</th><th>Sala</th><th>Estado</th><th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcoming as $r)
                    <tr>
                        <td><strong>{{ $r->activity_name }}</strong></td>
                        <td>{{ $r->day_name }}</td>
                        <td data-order="{{ $r->class_date_str }}" style="color:var(--text-secondary);font-size:0.88rem;">{{ $r->class_date_str }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}</td>
                        <td style="color:var(--text-secondary);">{{ $r->room }}</td>
                        <td><span class="badge badge-success"><i class="bi bi-check-circle"></i> Confirmada</span></td>
                        <td>
                            <button type="button" class="btn-cancel"
                                onclick="openCancelModal({{ $r->id }}, '{{ addslashes($r->activity_name) }}')">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ── HISTORIAL (clases pasadas esta semana) ── --}}
        @if(count($pastHistory) > 0)
        <div class="section-title" style="color:var(--text-secondary);margin-top:0.5rem;">
            <i class="bi bi-clock-history"></i> Historial de esta semana
            <span style="margin-left:auto;font-size:0.85rem;font-weight:600;background:rgba(148,163,184,0.12);color:var(--text-secondary);padding:0.2rem 0.6rem;border-radius:1rem;">{{ count($pastHistory) }}</span>
        </div>
        <div class="dt-wrap">
            <table id="historyTable">
                <thead>
                    <tr><th>Clase</th><th>Día</th><th>Fecha</th><th>Horario</th><th>Sala</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @foreach($pastHistory as $r)
                    <tr style="opacity:0.7;">
                        <td><strong>{{ $r->activity_name }}</strong></td>
                        <td>{{ $r->day_name }}</td>
                        <td style="color:var(--text-secondary);font-size:0.88rem;">{{ $r->class_date_str }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}</td>
                        <td style="color:var(--text-secondary);">{{ $r->room }}</td>
                        <td><span class="badge badge-muted"><i class="bi bi-check2-all"></i> Asistida</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- ── CANCELADAS ── --}}
        @if(count($cancelled) > 0)
        <div class="section-title" style="color:#F87171;margin-top:0.5rem;">
            <i class="bi bi-x-circle"></i> Canceladas recientes
            <span style="margin-left:auto;font-size:0.85rem;font-weight:600;background:rgba(239,68,68,0.12);color:#F87171;padding:0.2rem 0.6rem;border-radius:1rem;">{{ count($cancelled) }}</span>
        </div>
        <div class="dt-wrap">
            <table id="cancelledTable">
                <thead>
                    <tr><th>Clase</th><th>Día</th><th>Horario</th><th>Sala</th><th>Estado</th><th>Cancelada</th></tr>
                </thead>
                <tbody>
                    @foreach($cancelled as $r)
                    <tr style="opacity:0.65;">
                        <td><strong>{{ $r->activity_name }}</strong></td>
                        <td>{{ $r->day_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}</td>
                        <td style="color:var(--text-secondary);">{{ $r->room }}</td>
                        <td><span class="badge badge-danger"><i class="bi bi-x-circle"></i> Cancelada</span></td>
                        <td style="color:var(--text-secondary);font-size:0.85rem;">
                            {{ $r->cancelled_at ? \Carbon\Carbon::parse($r->cancelled_at)->format('d/m/Y H:i') : \Carbon\Carbon::parse($r->updated_at)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </main>

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script>
        // ── DataTables ──
        $.fn.dataTable.defaults.language = {
            emptyTable:"No hay reservas en esta sección",search:"Buscar:",lengthMenu:"Mostrar _MENU_ filas",
            info:"Mostrando _START_ a _END_ de _TOTAL_",infoEmpty:"Sin resultados",
            infoFiltered:"(filtrado de _MAX_)",paginate:{first:"«",previous:"‹",next:"›",last:"»"},
            zeroRecords:"No se encontraron resultados"
        };
        $(document).ready(function(){
            $('#upcomingTable').DataTable({ columnDefs:[{orderable:false,targets:6}], pageLength:10 });
            @if(count($pastHistory) > 0)
            $('#historyTable').DataTable({ pageLength:10 });
            @endif
            @if(count($cancelled) > 0)
            $('#cancelledTable').DataTable({ order:[[5,'desc']], pageLength:10 });
            @endif
        });

        // ── Cancel modal ──
        function openCancelModal(reservationId, activityName) {
            document.getElementById('cancelModalBody').textContent =
                '¿Estás seguro de que quieres cancelar la reserva de "' + activityName + '"?';
            document.getElementById('cancelConfirmBtn').onclick = function() {
                var form = document.getElementById('cancelForm');
                form.action = '{{ url("/reservations") }}/' + reservationId;
                form.submit();
            };
            document.getElementById('cancelModal').classList.add('active');
        }
        function closeCancelModal() {
            document.getElementById('cancelModal').classList.remove('active');
        }
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) closeCancelModal();
        });

        // Flash messages manejados globalmente por client-nav (SweetAlert2)
    </script>
</body>
</html>
