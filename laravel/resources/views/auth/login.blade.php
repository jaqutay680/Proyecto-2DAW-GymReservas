<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0a0a0f;
            --bg-surface: #111827;
            --bg-card: rgba(23, 31, 47, 0.85);
            --primary-start: #7C3AED;
            --primary-end: #EC4899;
            --accent: #06B6D4;
            --text-primary: #FFFFFF;
            --text-secondary: #9CA3AF;
            --error: #EF4444;
            --border-glow: rgba(124, 58, 237, 0.4);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            overflow: auto;
        }

        body {
            font-family: 'Manrope', system-ui, sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-y: auto;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background-image: url('{{ asset('images/backgrounds/auth.webp') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: brightness(0.3) contrast(1.1);
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background: radial-gradient(ellipse at top, rgba(124, 58, 237, 0.25) 0%, transparent 60%),
                radial-gradient(ellipse at bottom, rgba(236, 72, 153, 0.2) 0%, transparent 60%);
            pointer-events: none;
        }

        .login-card {
            width: 100%;
            max-width: 24rem;
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.8);
            position: relative;
            z-index: 1;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            font-weight: 800;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, var(--primary-start), var(--primary-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.6rem;
            color: var(--text-primary);
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1.1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            color: var(--text-primary);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
            background: rgba(255, 255, 255, 0.08);
        }

        .form-control.error {
            border-color: var(--error);
            background: rgba(239, 68, 68, 0.05);
        }

        .error-text {
            color: var(--error);
            font-size: 0.85rem;
            margin-top: 0.4rem;
            display: block;
        }

        .btn-primary {
            width: 100%;
            padding: 0.95rem;
            background: linear-gradient(135deg, var(--primary-start), var(--primary-end));
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px -10px rgba(236, 72, 153, 0.6);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .login-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            color: var(--primary-end);
        }

        .alert {
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--error);
        }

        .divider {
            display: flex; align-items: center; gap: 0.75rem;
            margin: 1.25rem 0; color: var(--text-secondary); font-size: 0.8rem;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.08);
        }

        .btn-google {
            width: 100%; padding: 0.85rem; display: flex; align-items: center;
            justify-content: center; gap: 0.65rem;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.75rem; color: var(--text-primary); font-size: 0.95rem;
            font-weight: 600; cursor: pointer; text-decoration: none;
            transition: var(--transition); font-family: 'Manrope', system-ui, sans-serif;
        }
        .btn-google:hover {
            background: rgba(255,255,255,0.11); border-color: rgba(255,255,255,0.22);
            transform: translateY(-1px);
        }
        .btn-google svg { flex-shrink: 0; }

        @media (max-width:640px) {
            body { align-items: flex-start; padding-top: 1.5rem; }
            .login-card { padding: 2rem 1.5rem; }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-header">
            <div class="logo">
                <img src="{{ asset('images/icons/icon-gym-logo.webp') }}" alt="Logo"
                     style="width:1.75rem;height:1.75rem;object-fit:contain;border-radius:0.3rem;"
                     onerror="this.style.display='none'">
                GYMRESERVAS
            </div>
            <p>Accede a tu cuenta para reservar clases</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-control @error('email') error @enderror" required autofocus autocomplete="email"
                    placeholder="tu@email.com">
                @error('email') <span class="error-text">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password"
                    class="form-control @error('password') error @enderror" required autocomplete="current-password"
                    placeholder="••••••••">
                @error('password') <span class="error-text">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-primary">Acceder</button>
        </form>

        <div class="divider">o</div>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            Continuar con Google
        </a>

        <div class="login-footer">¿No tienes cuenta? <a href="{{ route('register') }}">Registrarse</a></div>
    </div>
</body>

</html>