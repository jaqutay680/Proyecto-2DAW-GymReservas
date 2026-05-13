<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-dark:#0a0a0f; --bg-surface:#111827; --bg-card:rgba(23,31,47,0.88);
            --primary-start:#7C3AED; --primary-end:#EC4899; --accent:#06B6D4;
            --text-primary:#FFFFFF; --text-secondary:#9CA3AF;
            --success:#10B981; --error:#EF4444; --warning:#F59E0B;
            --border-glow:rgba(124,58,237,0.4);
            --transition:all 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Manrope',system-ui,sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;}
        body::before{content:'';position:fixed;inset:0;z-index:-1;
            background-image:url('{{ asset('images/backgrounds/auth.webp') }}');
            background-size:cover;background-position:center;background-attachment:fixed;filter:brightness(0.32) saturate(1.1);}
        body::after{content:'';position:fixed;inset:0;z-index:-1;pointer-events:none;
            background:radial-gradient(ellipse at top,rgba(124,58,237,0.15) 0%,transparent 60%),
                       radial-gradient(ellipse at bottom right,rgba(236,72,153,0.1) 0%,transparent 60%);}

        /* ── NAVBAR ── */
        .navbar{position:fixed;top:0;width:100%;background:rgba(17,24,39,0.92);backdrop-filter:blur(20px);
            border-bottom:1px solid rgba(255,255,255,0.08);padding:0.8rem 1.75rem;
            display:flex;justify-content:space-between;align-items:center;z-index:200;}
        .logo{font-weight:800;font-size:1.2rem;text-decoration:none;display:flex;align-items:center;gap:0.55rem;
            background:linear-gradient(135deg,var(--primary-start),var(--primary-end));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;}
        .logo i{-webkit-text-fill-color:var(--primary-start);}
        .nav-links{display:flex;align-items:center;gap:0.4rem;}
        .nav-link{color:var(--text-secondary);text-decoration:none;font-size:0.85rem;font-weight:500;
            padding:0.38rem 0.65rem;border-radius:0.5rem;transition:var(--transition);white-space:nowrap;}
        .nav-link:hover,.nav-link.active{color:var(--text-primary);background:rgba(255,255,255,0.06);}
        .user-chip{display:flex;align-items:center;gap:0.45rem;padding:0.28rem 0.7rem;border-radius:2rem;
            background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);
            font-size:0.78rem;color:var(--text-secondary);white-space:nowrap;
            font-family:inherit;cursor:pointer;transition:var(--transition);}
        .user-chip:hover{background:rgba(255,255,255,0.09);border-color:rgba(124,58,237,0.4);}
        .plan-badge{padding:0.12rem 0.45rem;border-radius:1rem;font-size:0.68rem;font-weight:700;text-transform:uppercase;}
        .plan-badge.free{background:rgba(100,116,139,0.25);color:#94A3B8;}
        .plan-badge.basico{background:rgba(59,130,246,0.2);color:#60A5FA;}
        .plan-badge.premium{background:rgba(236,72,153,0.2);color:#EC4899;}
        .btn-logout{padding:0.38rem 0.8rem;background:rgba(239,68,68,0.15);color:#F87171;
            border:1px solid rgba(239,68,68,0.3);border-radius:0.5rem;font-size:0.82rem;font-weight:500;cursor:pointer;white-space:nowrap;}
        .btn-admin-link{padding:0.38rem 0.8rem;background:rgba(124,58,237,0.2);color:#A78BFA;
            border:1px solid rgba(124,58,237,0.4);border-radius:0.5rem;font-size:0.82rem;font-weight:500;
            text-decoration:none;white-space:nowrap;}
        .hamburger{display:none;background:none;border:1px solid rgba(255,255,255,0.15);
            color:var(--text-primary);font-size:1.2rem;cursor:pointer;padding:0.32rem 0.55rem;border-radius:0.4rem;}

        /* ── MAIN ── */
        .dashboard-main{padding:4.5rem 1.5rem 2rem;max-width:72rem;margin:0 auto;position:relative;z-index:1;}
        .welcome-card{background:var(--bg-card);border:1px solid rgba(255,255,255,0.09);border-radius:1rem;
            padding:1.25rem 1.5rem;margin-bottom:1.5rem;backdrop-filter:blur(10px);}
        .welcome-card h1{font-size:1.35rem;font-weight:700;margin-bottom:0.25rem;}
        .welcome-card p{color:var(--text-secondary);font-size:0.88rem;}

        /* ── FILTROS ── */
        .filter-bar{display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1.5rem;
            background:var(--bg-card);padding:0.9rem 1.1rem;border-radius:0.85rem;
            border:1px solid rgba(255,255,255,0.07);backdrop-filter:blur(10px);align-items:flex-end;}
        .filter-group{display:flex;flex-direction:column;gap:0.3rem;flex:1;min-width:140px;}
        .filter-group label{font-size:0.75rem;color:var(--text-secondary);font-weight:500;}
        .filter-select{background:rgba(255,255,255,0.05)!important;color:var(--text-primary)!important;
            border:1px solid rgba(255,255,255,0.18);border-radius:0.45rem;padding:0.5rem 0.75rem;font-size:0.88rem;
            outline:none;transition:var(--transition);width:100%;}
        .filter-select:focus{border-color:var(--accent);}
        .filter-btn{background:linear-gradient(135deg,var(--primary-start),var(--primary-end));color:white;border:none;
            padding:0.5rem 1.25rem;border-radius:0.45rem;font-weight:600;cursor:pointer;
            transition:var(--transition);white-space:nowrap;font-size:0.88rem;}
        .filter-btn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(124,58,237,0.35);}
        .filter-btn:disabled{opacity:0.7;cursor:wait;transform:none;}

        /* ── STATS ── */
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0.85rem;margin-bottom:1.75rem;}
        .stat-card{background:var(--bg-card);border:1px solid rgba(255,255,255,0.07);border-radius:0.85rem;
            padding:1rem 1.25rem;text-align:center;transition:var(--transition);backdrop-filter:blur(10px);}
        .stat-card:hover{border-color:var(--border-glow);}
        .stat-card .value{font-size:1.7rem;font-weight:700;background:linear-gradient(135deg,var(--primary-start),var(--accent));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;}
        .stat-card .label{color:var(--text-secondary);font-size:0.8rem;margin-top:0.25rem;}

        /* ── SECCIÓN ── */
        .section-title{font-size:1.15rem;font-weight:700;margin:1.75rem 0 1rem;display:flex;align-items:center;gap:0.5rem;}
        .section-title::before{content:'';width:4px;height:18px;border-radius:2px;
            background:linear-gradient(135deg,var(--primary-start),var(--primary-end));}

        /* ── ACTIVITY CARDS ── */
        .activities-list{display:grid;gap:0.75rem;}
        .activity-card{background:var(--bg-card);border:1px solid rgba(255,255,255,0.08);border-radius:0.85rem;
            padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;
            transition:var(--transition);position:relative;overflow:hidden;backdrop-filter:blur(10px);gap:1rem;}
        .activity-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;
            background:linear-gradient(135deg,var(--primary-start),var(--primary-end));opacity:0;transition:opacity 0.3s;}
        .activity-card:hover{transform:translateX(3px);border-color:var(--border-glow);}
        .activity-card:hover::before{opacity:1;}
        .activity-card.card-danger{border-color:rgba(239,68,68,0.35);background:rgba(239,68,68,0.06);}
        .activity-card.card-danger::before{background:#EF4444;opacity:1;}
        .activity-card.card-warning{border-color:rgba(245,158,11,0.35);background:rgba(245,158,11,0.06);}
        .activity-card.card-warning::before{background:#F59E0B;opacity:1;}
        .activity-card.card-success{border-color:rgba(16,185,129,0.45);background:rgba(16,185,129,0.07);}
        .activity-card.card-success::before{background:#10B981;opacity:1;}
        .activity-card.card-success:hover{border-color:rgba(16,185,129,0.65);}
        .btn-reserved{padding:0.5rem 1rem;background:rgba(16,185,129,0.18);color:#10B981;
            border:1px solid rgba(16,185,129,0.4);border-radius:0.5rem;font-size:0.85rem;
            font-weight:600;cursor:default;display:flex;align-items:center;gap:0.4rem;white-space:nowrap;}
        .activity-info h3{font-weight:600;margin-bottom:0.25rem;font-size:1rem;}
        .activity-info p{color:var(--text-secondary);font-size:0.85rem;}
        .btn-reserve{padding:0.5rem 1rem;background:linear-gradient(135deg,var(--primary-start),var(--primary-end));
            color:white;font-weight:600;border:none;border-radius:0.45rem;cursor:pointer;
            font-size:0.85rem;transition:var(--transition);box-shadow:0 3px 12px rgba(124,58,237,0.3);white-space:nowrap;}
        .btn-reserve:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(236,72,153,0.45);}

        /* ── MODAL ── */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.72);backdrop-filter:blur(5px);
            display:flex;align-items:center;justify-content:center;z-index:9999;padding:1rem;animation:fadeIn 0.15s ease;}
        @keyframes fadeIn{from{opacity:0;}to{opacity:1;}}
        .modal-box{background:#1E293B;border:1px solid rgba(255,255,255,0.1);border-radius:1rem;
            padding:2rem;max-width:380px;width:100%;text-align:center;animation:scaleIn 0.18s ease;}
        @keyframes scaleIn{from{transform:scale(0.92);opacity:0;}to{transform:scale(1);opacity:1;}}
        .modal-icon{font-size:2.25rem;margin-bottom:0.6rem;}
        .modal-title{font-size:1.05rem;font-weight:700;margin-bottom:0.4rem;}
        .modal-msg{color:var(--text-secondary);font-size:0.875rem;margin-bottom:1.4rem;line-height:1.5;}
        .modal-actions{display:flex;gap:0.65rem;justify-content:center;}
        .modal-btn{padding:0.55rem 1.4rem;border-radius:0.5rem;font-weight:600;font-size:0.875rem;cursor:pointer;border:none;}
        .modal-btn-cancel{background:rgba(255,255,255,0.08);color:var(--text-primary);border:1px solid rgba(255,255,255,0.15);}
        .modal-btn-confirm{background:linear-gradient(135deg,var(--primary-start),var(--primary-end));color:#fff;}

        /* ── TOASTS ── */
        .toast-wrap{position:fixed;bottom:1.25rem;right:1.25rem;z-index:9998;display:flex;flex-direction:column;gap:0.5rem;pointer-events:none;}
        .toast{background:#1E293B;border:1px solid rgba(255,255,255,0.1);border-radius:0.75rem;
            padding:0.75rem 1rem;color:#fff;font-weight:500;font-size:0.875rem;
            display:flex;align-items:center;gap:0.5rem;opacity:0;transform:translateX(1.5rem);
            transition:all 0.3s ease;max-width:320px;pointer-events:auto;
            box-shadow:0 8px 30px rgba(0,0,0,0.35);}
        .toast.show{opacity:1;transform:translateX(0);}
        .toast.toast-success{border-left:3px solid var(--success);}
        .toast.toast-error{border-left:3px solid var(--error);}
        .toast.toast-warning{border-left:3px solid var(--warning);}

        /* ── RESPONSIVE ── */
        @media(max-width:768px){
            .hamburger{display:block;}
            .nav-links{display:none;flex-direction:column;align-items:flex-start;
                position:fixed;top:56px;left:0;right:0;background:rgba(10,10,20,0.97);
                backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.07);
                padding:0.85rem 1.25rem;gap:0.35rem;z-index:199;max-height:calc(100vh - 56px);overflow-y:auto;}
            .nav-links.open{display:flex;}
            .nav-links .user-chip{margin-top:0.4rem;padding-top:0.65rem;border-top:1px solid rgba(255,255,255,0.07);}
            .navbar{padding:0.75rem 1rem;}
            .dashboard-main{padding:4rem 0.9rem 1.5rem;}
            .activity-card{flex-direction:column;align-items:flex-start;}
            .activity-card .btn-reserve, .activity-card span{align-self:stretch;text-align:center;}
            .filter-bar{gap:0.5rem;}
            .filter-group{min-width:100%;}
            .filter-btn{width:100%;}
            .stats-grid{grid-template-columns:repeat(2,1fr);}
        }
        select option{background:#0a0a0f!important;color:#fff!important;}
    </style>
</head>
<body>
    @include('partials.client-nav', ['activePage' => 'dashboard'])

    <main class="dashboard-main">
        <!-- Bienvenida -->
        <div class="welcome-card">
            <h1>Bienvenido, {{ $user->name }} 👋</h1>
            <p>
                Plan: <strong style="text-transform:uppercase;color:var(--accent);">{{ $user->plan_type ?? 'free' }}</strong>
                &nbsp;|&nbsp; Reservas esta semana: <strong style="color:var(--success);">{{ $myWeeklyReservations ?? 0 }}/{{ $weeklyLimitDisplay }}</strong>
            </p>
        </div>

        <!-- Filtros AJAX -->
        <form id="filterForm" class="filter-bar" data-action="{{ route('dashboard.filters') }}">
            <div class="filter-group">
                <label for="day"><i class="bi bi-calendar"></i> Día</label>
                <select name="day" id="day" class="filter-select">
                    @foreach($days ?? [] as $key => $label)
                        <option value="{{ $key }}" {{ ($selectedDay ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="activity"><i class="bi bi-activity"></i> Actividad</label>
                <select name="activity" id="activity" class="filter-select">
                    <option value="all">Todas</option>
                    @foreach($activities ?? [] as $act)
                        <option value="{{ $act->id }}" {{ ($selectedActivity ?? '') == $act->id ? 'selected' : '' }}>{{ $act->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="filter-btn" id="filterBtn"><i class="bi bi-search"></i> Filtrar</button>
        </form>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="value">{{ $myWeeklyReservations ?? 0 }}</div>
                <div class="label">Reservas esta semana</div>
            </div>
            <div class="stat-card">
                <div class="value">{{ $weeklyLimitDisplay }}</div>
                <div class="label">Máximo semanal</div>
            </div>
            <div class="stat-card">
                @if($availableClasses > 0)
                    <div class="value">{{ $availableClasses }}</div>
                    <div class="label">Clases disponibles</div>
                @else
                    <div class="value" style="color:var(--warning);font-size:1.1rem;"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="label" style="font-size:0.75rem;">Sin plazas disponibles</div>
                @endif
            </div>
        </div>

        <!-- Lista de clases -->
        @php
            $displayDay = $selectedDay ?? 'monday';
            if ($displayDay === 'sunday') $displayDay = 'monday';
            $dayNamesEs = ['monday'=>'Lunes','tuesday'=>'Martes','wednesday'=>'Miércoles','thursday'=>'Jueves','friday'=>'Viernes','saturday'=>'Sábado'];
            $displayDayEs = $dayNamesEs[$displayDay] ?? 'Lunes';
        @endphp
        <h2 class="section-title" id="sectionTitle"><i class="bi bi-calendar-week"></i> Clases — {{ $displayDayEs }}</h2>

        <div class="activities-list" id="classListContainer">
            @include('partials.class-list', ['selectedDay' => $selectedDay])
        </div>
    </main>

    @include('partials.footer')

    <!-- ── MODAL DE RESERVA ── -->
    <div id="reserveModal" style="display:none;" class="modal-overlay" onclick="if(event.target===this)closeReserveModal()">
        <div class="modal-box">
            <div class="modal-icon">🏋️</div>
            <div class="modal-title" id="reserveModalTitle">Confirmar reserva</div>
            <div class="modal-msg" id="reserveModalMsg"></div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" onclick="closeReserveModal()">Cancelar</button>
                <button class="modal-btn modal-btn-confirm" onclick="submitReserve()"><i class="bi bi-calendar-plus"></i> Reservar</button>
            </div>
        </div>
    </div>
    <form id="reserveForm" method="POST" style="display:none;">@csrf</form>

    <!-- ── TOASTS ── -->
    <div class="toast-wrap" id="toastWrap"></div>

    <script>
    // ── Modal de reserva ──
    const _reserveBase = '{{ url("/reservations") }}';
    let _reserveUrl = '';
    function openReserveModal(scheduleId, name, time) {
        _reserveUrl = _reserveBase + '/' + scheduleId;
        document.getElementById('reserveModalTitle').textContent = name;
        document.getElementById('reserveModalMsg').textContent = 'Confirmar reserva para las ' + time + '. Podrás cancelarla antes de que empiece.';
        document.getElementById('reserveModal').style.display = 'flex';
    }
    function closeReserveModal() { document.getElementById('reserveModal').style.display = 'none'; }
    function submitReserve() {
        closeReserveModal();
        const form = document.getElementById('reserveForm');
        form.action = _reserveUrl;
        form.method = 'POST';
        form.submit();
    }

    // ── AJAX filtros ──
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('filterBtn');
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Cargando…'; btn.disabled = true;
        const params = new URLSearchParams(new FormData(this));
        fetch(this.dataset.action + '?' + params)
            .then(r => { if(!r.ok) throw new Error(); return r.json(); })
            .then(data => {
                document.getElementById('classListContainer').innerHTML = data.html;
                let day = document.getElementById('day').value;
                if (day === 'sunday') day = 'monday';
                const names = {monday:'Lunes',tuesday:'Martes',wednesday:'Miércoles',thursday:'Jueves',friday:'Viernes',saturday:'Sábado'};
                document.getElementById('sectionTitle').innerHTML = '<i class="bi bi-calendar-week"></i> Clases — ' + (names[day] || '');
                history.pushState({}, '', '?' + params);
            })
            .catch(() => Swal.fire({ icon:'error', title:'Error', text:'No se pudieron cargar las clases. Recarga la página.', toast:true, position:'top-end', timer:4000, showConfirmButton:false, background:'rgba(15,23,42,0.97)', color:'#fff' }))
            .finally(() => { btn.innerHTML = '<i class="bi bi-search"></i> Filtrar'; btn.disabled = false; });
    });
    </script>
</body>
</html>
