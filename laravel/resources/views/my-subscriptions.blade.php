<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Plan - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            font-family: inherit; cursor: pointer; transition: var(--transition);
            font-size: 0.8rem; color: var(--text-secondary); white-space: nowrap;
        }
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
        .container { max-width: 52rem; margin: 0 auto; padding: 5.5rem 1.5rem 2rem; }
        .page-header { margin-bottom: 1.75rem; }
        .page-header h1 { font-size: 1.6rem; font-weight: 700; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.5rem; }
        .page-header p { color: var(--text-secondary); font-size: 0.9rem; }

        /* ── CARDS ── */
        .card {
            background: var(--bg-card); border: 1px solid rgba(255,255,255,0.09);
            border-radius: 1.25rem; padding: 1.75rem 2rem; margin-bottom: 1.5rem; backdrop-filter: blur(10px);
        }
        .card-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.07); margin-bottom: 1.25rem;
            flex-wrap: wrap; gap: 0.75rem;
        }
        .plan-name { font-size: 1.35rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .plan-price { font-size: 2rem; font-weight: 800; color: var(--accent); }
        .plan-price span { font-size: 0.85rem; font-weight: 400; color: var(--text-secondary); }
        .status-pill {
            padding: 0.3rem 0.8rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            background: rgba(16,185,129,0.2); color: var(--success);
        }
        .features { list-style: none; margin-bottom: 1.5rem; }
        .features li { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.65rem; font-size: 0.92rem; color: var(--text-secondary); }
        .features li.yes i { color: var(--success); }
        .features li.no  { opacity: 0.55; }
        .features li.no i { color: var(--text-secondary); }

        /* ── CAMBIAR PLAN ── */
        .change-section {
            background: rgba(124,58,237,0.08); border: 1px solid rgba(124,58,237,0.25);
            border-radius: 1rem; padding: 1.5rem;
        }
        .change-section h3 { font-size: 1rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .change-form { display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end; }
        .change-form label { font-size: 0.82rem; color: var(--text-secondary); display: block; margin-bottom: 0.35rem; }
        .change-form select {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.18);
            color: var(--text-primary); padding: 0.55rem 0.9rem; border-radius: 0.5rem; font-size: 0.9rem;
            min-width: 160px;
        }
        .change-form select:focus { outline: none; border-color: rgba(124,58,237,0.6); }
        .btn-change {
            background: var(--accent); color: #0a0a0f; border: none;
            padding: 0.55rem 1.25rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; cursor: pointer;
        }
        .btn-change:hover { opacity: 0.9; }
        .price-preview { font-size: 0.82rem; color: var(--text-secondary); margin-top: 0.6rem; }
        .price-preview strong { color: var(--accent); }

        /* ── ALERTAS ── */
        .alert { border-radius: 0.75rem; padding: 0.85rem 1rem; margin-bottom: 1.5rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
        .alert-success { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: var(--success); }
        .alert-error   { background: rgba(239,68,68,0.15);  border: 1px solid rgba(239,68,68,0.3);  color: var(--error); }

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
            .card { padding: 1.25rem; }
            .card-header { flex-direction: column; }
        }
        select option { background: #0a0a0f !important; color: #fff !important; }
    </style>
</head>
<body>
    @include('partials.client-nav', ['activePage' => 'subscriptions'])

    <main class="container">
        <div class="page-header">
            <h1><i class="bi bi-stars"></i> Mi Plan</h1>
            <p>Gestiona tu suscripción y conoce las ventajas de cada opción.</p>
        </div>

        {{-- Flash messages manejados por client-nav (SweetAlert2) --}}

        {{-- Plan actual --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="plan-name">
                        @if($currentPlan === 'premium') <i class="bi bi-lightning-fill" style="color:#EC4899;"></i>
                        @elseif($currentPlan === 'basico') <i class="bi bi-star-fill" style="color:#60A5FA;"></i>
                        @else <i class="bi bi-gift" style="color:#94A3B8;"></i>
                        @endif
                        {{ ucfirst($currentPlan) }}
                    </div>
                    <div class="plan-price">{{ number_format($currentPrice, 2) }}€ <span>/ mes</span></div>
                </div>
                <span class="status-pill"><i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Activo</span>
            </div>

            <ul class="features">
                @if($currentPlan === 'premium')
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Reservas ilimitadas cada semana</li>
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Acceso prioritario a todas las clases</li>
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Cancelación hasta 1 hora antes</li>
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Historial completo de asistencias</li>
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Soporte prioritario</li>
                @elseif($currentPlan === 'basico')
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> 5 reservas por semana</li>
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Acceso a todas las clases</li>
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Cancelación hasta 2 horas antes</li>
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Historial de asistencias</li>
                    <li class="no"><i class="bi bi-x-circle"></i> Reservas ilimitadas</li>
                @else
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> 1 reserva de prueba gratuita</li>
                    <li class="yes"><i class="bi bi-check-circle-fill"></i> Acceso a clases disponibles</li>
                    <li class="no"><i class="bi bi-x-circle"></i> Reservas semanales (necesitas plan Básico)</li>
                    <li class="no"><i class="bi bi-x-circle"></i> Acceso prioritario (necesitas Premium)</li>
                    <li class="no"><i class="bi bi-x-circle"></i> Soporte prioritario (necesitas Premium)</li>
                @endif
            </ul>

            {{-- Cambiar de plan --}}
            <div class="change-section">
                <h3><i class="bi bi-arrow-repeat"></i> Cambiar de plan</h3>
                <form method="POST" action="{{ route('user.plan.update') }}" id="changePlanForm">
                    @csrf @method('PATCH')
                    <div class="change-form">
                        <div>
                            <label for="new_plan">Nuevo plan:</label>
                            <select name="plan_type" id="new_plan" onchange="updatePricePreview()">
                                <option value="free"    {{ $currentPlan === 'free'    ? 'selected' : '' }}>Free — 0 €/mes</option>
                                <option value="basico"  {{ $currentPlan === 'basico'  ? 'selected' : '' }}>Básico — 9,99 €/mes</option>
                                <option value="premium" {{ $currentPlan === 'premium' ? 'selected' : '' }}>Premium — 19,99 €/mes</option>
                            </select>
                        </div>
                        <button type="button" class="btn-change" onclick="confirmChangePlan()"><i class="bi bi-check-circle"></i> Confirmar</button>
                    </div>
                    <div class="price-preview" id="pricePreview"></div>
                </form>
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        const prices = { free: 0, basico: 9.99, premium: 19.99 };
        const currentPlan = '{{ $currentPlan }}';

        function updatePricePreview() {
            const sel = document.getElementById('new_plan').value;
            const p = document.getElementById('pricePreview');
            if (sel === currentPlan) {
                p.innerHTML = 'Ya tienes este plan seleccionado.';
                return;
            }
            const nextDate = new Date(); nextDate.setMonth(nextDate.getMonth() + 1);
            const fd = nextDate.toLocaleDateString('es-ES', { day:'2-digit', month:'2-digit', year:'numeric' });
            p.innerHTML = sel === 'free'
                ? 'Tu suscripción se cancelará al final del periodo actual.'
                : `Próximo cobro: <strong>${prices[sel].toFixed(2)}€</strong> el ${fd}`;
        }

        function confirmChangePlan() {
            const sel = document.getElementById('new_plan').value;
            const form = document.getElementById('changePlanForm');

            if (sel === currentPlan) {
                Swal.fire({
                    icon: 'info', title: 'Sin cambios',
                    text: 'Ya tienes este plan activo.',
                    confirmButtonColor: '#06B6D4',
                    background: 'rgba(15,23,42,0.98)', color: '#fff'
                });
                return;
            }

            const cfg = sel === 'free'
                ? { title: '¿Cambiar a Free?', text: 'Perderás las ventajas de tu plan actual. La suscripción quedará cancelada.', icon: 'warning', confirmButtonColor: '#EF4444', confirmButtonText: 'Sí, cambiar a Free' }
                : { title: `¿Cambiar a ${sel.charAt(0).toUpperCase()+sel.slice(1)}?`, text: `Se cobrará ${prices[sel].toFixed(2)} € en la próxima fecha de facturación.`, icon: 'question', confirmButtonColor: '#06B6D4', confirmButtonText: 'Confirmar cambio' };

            Swal.fire({
                ...cfg,
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                cancelButtonColor: '#374151',
                background: 'rgba(15,23,42,0.98)',
                color: '#fff',
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        }

        document.addEventListener('DOMContentLoaded', updatePricePreview);
    </script>
</body>
</html>
