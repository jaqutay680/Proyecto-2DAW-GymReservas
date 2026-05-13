<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GymReservas - Inicio</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-dark: #0a0a0f;
            --bg-surface: #111827;
            --bg-card: rgba(23, 31, 47, 0.7);
            --primary-start: #7C3AED;
            --primary-end: #EC4899;
            --accent: #06B6D4;
            --text-primary: #FFFFFF;
            --text-secondary: #9CA3AF;
            --success: #10B981;
            --border-glow: rgba(124, 58, 237, 0.4);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Manrope', system-ui, sans-serif;
            background: radial-gradient(ellipse at top, rgba(124, 58, 237, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
                var(--bg-dark);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* 🔹 Fondos decorativos (NO bloquean clics) */
        body::before,
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
        }

        body::before {
            background-image: url('{{ asset('images/backgrounds/hero.webp') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: brightness(0.4) saturate(1.2);
        }

        body::after {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.2) 0%, transparent 50%),
                linear-gradient(225deg, rgba(236, 72, 153, 0.15) 0%, transparent 50%);
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(17, 24, 39, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            transition: var(--transition);
        }

        .navbar.scrolled {
            padding: 0.75rem 2rem;
            background: rgba(17, 24, 39, 0.95);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .logo {
            font-weight: 800;
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, var(--primary-start), var(--primary-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.65rem 1.35rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--primary-start), var(--primary-end));
            opacity: 0;
            transition: opacity 0.3s;
            z-index: -1;
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn-outline {
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
            background: transparent;
        }

        .btn-outline:hover {
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 10px 40px -10px rgba(124, 58, 237, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-start), var(--primary-end));
            color: white;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 50px -10px rgba(236, 72, 153, 0.6);
        }

        .hero {
            padding: 12rem 2rem 6rem;
            text-align: center;
            max-width: 72rem;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            border-radius: 2rem;
            background: rgba(124, 58, 237, 0.15);
            border: 1px solid rgba(124, 58, 237, 0.3);
            color: var(--accent);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.4);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(124, 58, 237, 0);
            }
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            background: linear-gradient(135deg, var(--primary-start), var(--accent), var(--primary-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }

        @keyframes gradient-shift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .hero p {
            color: var(--text-secondary);
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            max-width: 42rem;
            margin: 0 auto 2rem;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .plans {
            padding: 5rem 2rem;
            background: linear-gradient(180deg, transparent, rgba(23, 31, 47, 0.7), transparent);
            position: relative;
            z-index: 1;
        }

        .plans-header {
            text-align: center;
            max-width: 48rem;
            margin: 0 auto 3rem;
        }

        .plans-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .plans-header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 72rem;
            margin: 0 auto;
        }

        .plan-card {
            padding: 2rem;
            border-radius: 1.25rem;
            background: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            z-index: 1;
        }

        .plan-card.featured {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.3);
        }

        /* 🔹 Efecto decorativo SIN bloquear clics */
        .plan-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.1), transparent 40%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
            z-index: -1;
        }

        .plan-card:hover {
            transform: translateY(-4px);
            border-color: var(--border-glow);
        }

        .plan-card:hover::before {
            opacity: 1;
        }

        .plan-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .plan-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .plan-price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--accent);
        }

        .plan-price span {
            font-size: 0.9rem;
            font-weight: 400;
            color: var(--text-secondary);
        }

        .plan-features {
            list-style: none;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        .plan-features li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .plan-features li i {
            color: var(--success);
            margin-top: 0.2rem;
            flex-shrink: 0;
        }

        .plan-features li.unavailable {
            color: var(--text-secondary);
            opacity: 0.6;
        }

        .plan-features li.unavailable i {
            color: var(--text-secondary);
        }

        .plan-footer {
            margin-top: auto;
            position: relative;
            z-index: 10;
        }

        .plan-btn {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: var(--transition);
            position: relative;
            z-index: 15;
            cursor: pointer;
        }

        .plan-btn.primary {
            background: linear-gradient(135deg, var(--primary-start), var(--primary-end));
            color: white;
            border: none;
        }

        .plan-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.4);
        }

        .plan-btn.outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
        }

        .plan-btn.outline:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .plan-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(6, 182, 212, 0.15);
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .features {
            padding: 5rem 2rem;
            background: linear-gradient(180deg, transparent, rgba(23, 31, 47, 0.7), transparent);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            z-index: 1;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 72rem;
            margin: 0 auto;
        }

        .feature-card {
            padding: 2rem;
            border-radius: 1.25rem;
            background: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.1), transparent 40%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
            z-index: -1;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: var(--border-glow);
            box-shadow: 0 20px 40px -20px rgba(0, 0, 0, 0.5);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 3.5rem;
            height: 3.5rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1.5rem;
            filter: drop-shadow(0 4px 12px rgba(124, 58, 237, 0.3));
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text-primary);
        }

        .feature-card p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        footer {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.85rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            z-index: 1;
        }
        .footer-links {
            display: flex; flex-wrap: wrap; justify-content: center;
            gap: 0.25rem 1.25rem; margin-bottom: 0.75rem;
        }
        .footer-links a {
            color: #6B7280; text-decoration: none; font-size: 0.82rem;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: #A78BFA; }

        @media (max-width: 640px) {
            .navbar {
                padding: 0.7rem 1rem;
            }

            .nav-links {
                gap: 0.4rem;
            }

            /* Compact nav buttons — hide text icons, shrink padding */
            .nav-links .btn {
                padding: 0.45rem 0.85rem;
                font-size: 0.82rem;
                width: auto;
            }

            .nav-links .btn i {
                display: none;
            }

            .hero {
                padding: 10rem 1.5rem 4rem;
            }

            .hero-actions {
                flex-direction: column;
                align-items: center;
            }

            /* Full-width for hero/plan buttons only */
            .hero-actions .btn,
            .plan-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 420px) {
            .logo { font-size: 1rem; }
            .logo i { font-size: 1.3rem !important; }
        }
    </style>
</head>

<body>
    <nav class="navbar" id="navbar">
        <a href="{{ route('dashboard') }}" class="logo" style="text-decoration:none;">
            <img src="{{ asset('images/icons/icon-gym-logo.webp') }}" alt="Logo"
                 style="width:2rem;height:2rem;object-fit:contain;border-radius:0.3rem;"
                 onerror="this.style.display='none'">
            GYMRESERVAS
        </a>
        <div class="nav-links">
            @if(Route::has('login'))
                <a href="{{ route('login') }}" class="btn btn-outline"><i class="bi bi-box-arrow-in-right"></i> Acceder</a>
            @endif
            @if(Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Registrarse</a>
            @endif
        </div>
    </nav>

    <main class="hero">
        <div class="hero-badge"><i class="bi bi-stars"></i> Nueva plataforma de reservas</div>
        <h1>Reserva tu clase <br><span>en tiempo real</span></h1>
        <p>Gestión inteligente de horarios, control de aforo automático y una experiencia diseñada para moverte. Todo lo
            que necesitas para tu gimnasio, en una sola plataforma.</p>
        <div class="hero-actions">
            @if(Route::has('register'))
                <a href="{{ route('register') }}?plan=free" class="btn btn-primary"><i class="bi bi-rocket-takeoff"></i>
                    Comenzar gratis</a>
            @endif
            <a href="#plans" class="btn btn-outline"><i class="bi bi-list-check"></i> Ver planes</a>
        </div>
    </main>

    <section id="plans" class="plans">
        <div class="plans-header">
            <h2>Elige tu plan</h2>
            <p>Comienza gratis y actualiza cuando necesites más ventajas. Sin compromisos, cancela cuando quieras.</p>
        </div>
        <div class="plans-grid">
            <!-- Plan Free -->
            <div class="plan-card">
                <div class="plan-header">
                    <div class="plan-name"><i class="bi bi-gift"></i> Free</div>
                    <div class="plan-price">0€ <span>/ mes</span></div>
                </div>
                <ul class="plan-features">
                    <li><i class="bi bi-check-circle-fill"></i> 1 reserva de prueba</li>
                    <li><i class="bi bi-check-circle-fill"></i> Acceso a clases básicas</li>
                    <li><i class="bi bi-check-circle-fill"></i> App móvil incluida</li>
                    <li class="unavailable"><i class="bi bi-x-circle"></i> Reservas ilimitadas</li>
                    <li class="unavailable"><i class="bi bi-x-circle"></i> Prioridad en lista de espera</li>
                </ul>
                <div class="plan-footer">
                    <a href="{{ route('register') }}?plan=free" class="plan-btn outline">Empezar gratis</a>
                </div>
            </div>

            <!-- Plan Básico -->
            <div class="plan-card featured">
                <div class="plan-badge">Más popular</div>
                <div class="plan-header">
                    <div class="plan-name"><i class="bi bi-star-fill"></i> Básico</div>
                    <div class="plan-price">9.99€ <span>/ mes</span></div>
                </div>
                <ul class="plan-features">
                    <li><i class="bi bi-check-circle-fill"></i> 5 reservas por semana</li>
                    <li><i class="bi bi-check-circle-fill"></i> Acceso a todas las clases</li>
                    <li><i class="bi bi-check-circle-fill"></i> Cancelación hasta 24h antes</li>
                    <li><i class="bi bi-check-circle-fill"></i> Notificaciones push</li>
                    <li class="unavailable"><i class="bi bi-x-circle"></i> Reservas ilimitadas</li>
                </ul>
                <div class="plan-footer">
                    <a href="{{ route('register') }}?plan=basico" class="plan-btn primary">Elegir Básico</a>
                </div>
            </div>

            <!-- Plan Premium -->
            <div class="plan-card">
                <div class="plan-header">
                    <div class="plan-name"><i class="bi bi-lightning-fill"></i> Premium</div>
                    <div class="plan-price">19.99€ <span>/ mes</span></div>
                </div>
                <ul class="plan-features">
                    <li><i class="bi bi-check-circle-fill"></i> Reservas ilimitadas</li>
                    <li><i class="bi bi-check-circle-fill"></i> Acceso prioritario a clases</li>
                    <li><i class="bi bi-check-circle-fill"></i> Cancelación hasta 2h antes</li>
                    <li><i class="bi bi-check-circle-fill"></i> Estadísticas personales</li>
                    <li><i class="bi bi-check-circle-fill"></i> Soporte prioritario</li>
                </ul>
                <div class="plan-footer">
                    <a href="{{ route('register') }}?plan=premium" class="plan-btn outline">Elegir Premium</a>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-calendar-check"></i></div>
                <h3>Reservas instantáneas</h3>
                <p>Confirma tu plaza en segundos con validación de aforo en tiempo real. Sin esperas, sin
                    complicaciones.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                <h3>Política flexible</h3>
                <p>Cancela hasta 24h antes sin penalización. Gestión transparente y control total de tus reservas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-graph-up"></i></div>
                <h3>Panel de control</h3>
                <p>Estadísticas de ocupación, ingresos y asistencia en tiempo real. Toma decisiones basadas en datos.
                </p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-links">
            <a href="{{ route('legal.privacy') }}"><i class="bi bi-shield-lock"></i> Política de Privacidad</a>
            <span style="color:#374151;">·</span>
            <a href="{{ route('legal.cookies') }}"><i class="bi bi-cookie"></i> Cookies</a>
            <span style="color:#374151;">·</span>
            <a href="{{ route('legal.notice') }}"><i class="bi bi-file-earmark-text"></i> Aviso Legal</a>
            <span style="color:#374151;">·</span>
            <a href="{{ route('legal.terms') }}"><i class="bi bi-journal-check"></i> Términos de Servicio</a>
        </div>
        <p>© {{ date('Y') }} GymReservas &nbsp;·&nbsp; Proyecto académico IES La Marisma</p>
    </footer>

    <script>
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) navbar.classList.add('scrolled'); else navbar.classList.remove('scrolled');
        });
    </script>
</body>

</html>