<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-dark: #0a0a0f;
            --bg-card: rgba(23, 31, 47, 0.85);
            --primary: #7C3AED;
            --accent: #06B6D4;
            --text: #fff;
            --text-dim: #9CA3AF;
            --error: #EF4444;
            --success: #10B981;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Manrope', system-ui, sans-serif;
            background: var(--bg-dark);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-y: auto;
        }
        body::before {
            content: ''; position: fixed; inset: 0; z-index: -1;
            background-image: url('{{ asset('images/backgrounds/auth.webp') }}');
            background-size: cover; background-position: center; background-attachment: fixed;
            filter: brightness(0.3) contrast(1.1);
        }
        body::after {
            content: ''; position: fixed; inset: 0; z-index: -1;
            background: radial-gradient(ellipse at top, rgba(124,58,237,0.25) 0%, transparent 60%),
                        radial-gradient(ellipse at bottom, rgba(236,72,153,0.2) 0%, transparent 60%);
            pointer-events: none;
        }
        .card {
            width: 100%; max-width: 28rem;
            background: var(--bg-card); backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 1.5rem;
            padding: 2rem; box-shadow: 0 30px 60px -20px rgba(0,0,0,0.8);
            position: relative; z-index: 1;
            animation: slideUp 0.5s ease-out;
            max-height: 95vh; overflow-y: auto;
        }
        @keyframes slideUp {
            from { opacity:0; transform:translateY(20px) }
            to   { opacity:1; transform:translateY(0) }
        }
        .header { text-align: center; margin-bottom: 1.5rem; }
        .logo {
            font-weight: 800; font-size: 1.3rem; margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary), #EC4899);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .header p { color: var(--text-dim); font-size: 0.9rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.9rem; margin-bottom: 0.4rem; color: var(--text); }
        .form-control {
            width: 100%; padding: 0.75rem;
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15);
            border-radius: 0.5rem; color: var(--text); font-size: 1rem; transition: var(--transition);
        }
        .form-control:focus {
            outline: none; border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(6,182,212,0.15); background: rgba(255,255,255,0.08);
        }
        .form-control.is-error { border-color: var(--error); background: rgba(239,68,68,0.05); }
        .error-text {
            color: var(--error); font-size: 0.8rem; margin-top: 0.3rem;
            display: flex; align-items: center; gap: 0.3rem;
        }
        .plan-select-wrapper { position: relative; }
        .plan-select-wrapper select {
            width: 100%; padding: 0.75rem 2.5rem 0.75rem 0.75rem;
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15);
            border-radius: 0.5rem; color: var(--text); font-size: 1rem; appearance: none; cursor: pointer;
        }
        .plan-select-wrapper::after {
            content: '▼'; position: absolute; right: 0.75rem; top: 50%;
            transform: translateY(-50%); color: var(--text-dim); pointer-events: none; font-size: 0.8rem;
        }
        .plan-price { font-size: 0.8rem; color: var(--accent); margin-top: 0.25rem; font-weight: 500; }
        .pwd-reqs {
            margin-top: 0.4rem; padding: 0.4rem;
            background: rgba(255,255,255,0.03); border-radius: 0.4rem;
            font-size: 0.75rem; color: var(--text-dim);
        }
        .pwd-reqs li { list-style: none; margin: 0.15rem 0; transition: color 0.2s; }
        .pwd-reqs li.valid   { color: var(--success); }
        .pwd-reqs li.invalid { color: var(--error); }
        select, select option { background: #0a0a0f !important; color: #fff !important; }
        .btn {
            width: 100%; padding: 0.85rem;
            background: linear-gradient(135deg, var(--primary), #EC4899);
            color: #fff; border: none; border-radius: 0.5rem;
            font-weight: 600; cursor: pointer; margin-top: 0.5rem; transition: var(--transition);
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 12px 40px -10px rgba(236,72,153,0.6); }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .footer { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-dim); }
        .footer a { color: var(--accent); text-decoration: none; }
        .footer-legal { text-align:center; margin-top:1rem; font-size:0.72rem; color:#4B5563; }
        .footer-legal a { color:#6B7280; text-decoration:none; }
        .footer-legal a:hover { color:#A78BFA; }
        @media (max-width:480px) { .card { padding: 1.5rem; } }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="logo">GYMRESERVAS</div>
            <p>Crea tu cuenta para empezar</p>
        </div>

        {{-- Error general --}}
        @error('general')
        <div style="background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#F87171;padding:0.75rem;border-radius:0.5rem;margin-bottom:1rem;font-size:0.88rem;">
            <i class="bi bi-exclamation-triangle"></i> {{ $message }}
        </div>
        @enderror

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            {{-- NOMBRE --}}
            <div class="form-group">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name"
                       value="{{ $input['name'] ?? '' }}"
                       class="form-control @error('name') is-error @enderror">
                @error('name')<span class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>@enderror
            </div>

            {{-- EMAIL --}}
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email"
                       value="{{ $input['email'] ?? '' }}"
                       class="form-control @error('email') is-error @enderror">
                @error('email')<span class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>@enderror
            </div>

            {{-- DNI --}}
            <div class="form-group">
                <label for="dni">DNI <small style="color:var(--text-dim);font-weight:400;">(8 dígitos + letra, ej: 12345678Z)</small></label>
                <input id="dni" type="text" name="dni"
                       value="{{ $input['dni'] ?? '' }}"
                       maxlength="9" placeholder="Ej: 12345678Z"
                       class="form-control @error('dni') is-error @enderror"
                       oninput="this.value=this.value.toUpperCase()">
                @error('dni')<span class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>@enderror
            </div>

            {{-- FECHA DE NACIMIENTO --}}
            <div class="form-group">
                <label for="birth_date">Fecha de nacimiento</label>
                <input id="birth_date" type="date" name="birth_date"
                       value="{{ $input['birth_date'] ?? '' }}"
                       max="{{ date('Y-m-d', strtotime('-16 years')) }}"
                       class="form-control @error('birth_date') is-error @enderror">
                @error('birth_date')<span class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>@enderror
                <span id="ageWarning" style="font-size:0.75rem;color:var(--error);margin-top:0.25rem;display:none;">
                    <i class="bi bi-exclamation-triangle"></i> Debes tener al menos 16 años para registrarte.
                </span>
            </div>

            {{-- CONTRASEÑA --}}
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-error @enderror"
                       autocomplete="new-password">
                @error('password')<span class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>@enderror
                <ul class="pwd-reqs">
                    <li id="req-length" class="invalid">8+ caracteres</li>
                    <li id="req-upper"  class="invalid">Al menos una mayúscula</li>
                    <li id="req-number" class="invalid">Al menos un número</li>
                </ul>
            </div>

            {{-- CONFIRMAR CONTRASEÑA --}}
            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-control" autocomplete="new-password">
            </div>

            {{-- PLAN --}}
            <div class="form-group">
                <label for="plan_type">Plan de suscripción</label>
                <div class="plan-select-wrapper">
                    <select id="plan_type" name="plan_type" onchange="updatePlanPrice()">
                        @php $selectedPlan = $input['plan_type'] ?? request('plan', 'free'); @endphp
                        <option value="free"    data-price="0"     @if($selectedPlan === 'free')    selected @endif>Free</option>
                        <option value="basico"  data-price="9.99"  @if($selectedPlan === 'basico')  selected @endif>Básico</option>
                        <option value="premium" data-price="19.99" @if($selectedPlan === 'premium') selected @endif>Premium</option>
                    </select>
                </div>
                <div class="plan-price" id="planPriceText"></div>
            </div>

            <button type="submit" class="btn" id="submitBtn">Registrarse</button>
        </form>

        <div class="footer">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Acceder</a>
        </div>
        <div class="footer-legal">
            Al registrarte aceptas nuestros
            <a href="{{ route('legal.terms') }}">Términos de Servicio</a> y la
            <a href="{{ route('legal.privacy') }}">Política de Privacidad</a>
        </div>
    </div>

    <script>
        function updatePlanPrice() {
            const select = document.getElementById('plan_type');
            const price = select.options[select.selectedIndex].getAttribute('data-price');
            document.getElementById('planPriceText').textContent = price + '€ / mes';
        }

        function validateAge() {
            const birthInput = document.getElementById('birth_date');
            const warning    = document.getElementById('ageWarning');
            if (!birthInput.value) return true;

            const birthDate = new Date(birthInput.value);
            const today     = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;

            if (age < 16) {
                warning.style.display = 'block';
                birthInput.classList.add('is-error');
                return false;
            }
            warning.style.display = 'none';
            birthInput.classList.remove('is-error');
            return true;
        }

        document.addEventListener('DOMContentLoaded', function () {
            updatePlanPrice();

            const pwd = document.getElementById('password');
            const reqs = {
                length: document.getElementById('req-length'),
                upper:  document.getElementById('req-upper'),
                number: document.getElementById('req-number')
            };

            if (pwd) {
                pwd.addEventListener('input', function () {
                    const v = pwd.value;
                    reqs.length.className = v.length >= 8    ? 'valid' : 'invalid';
                    reqs.upper.className  = /[A-Z]/.test(v)  ? 'valid' : 'invalid';
                    reqs.number.className = /[0-9]/.test(v)  ? 'valid' : 'invalid';
                });
            }

            const birthInput = document.getElementById('birth_date');
            if (birthInput) {
                birthInput.addEventListener('change', validateAge);
                if (birthInput.value) validateAge();
            }

            document.getElementById('registerForm').addEventListener('submit', function (e) {
                if (!validateAge()) {
                    e.preventDefault();
                    birthInput.focus();
                }
            });
        });
    </script>
</body>
</html>
