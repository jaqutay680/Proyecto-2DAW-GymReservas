<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Completa tu perfil - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-dark:#0a0a0f; --bg-card:rgba(23,31,47,0.9);
            --primary:#7C3AED; --primary-end:#EC4899; --accent:#06B6D4;
            --text:#fff; --text-dim:#9CA3AF; --error:#EF4444; --success:#10B981;
        }
        *{box-sizing:border-box;margin:0;padding:0;}
        html,body{min-height:100%;}
        body{
            font-family:'Manrope',system-ui,sans-serif; background:var(--bg-dark); color:var(--text);
            display:flex; align-items:center; justify-content:center; padding:1.5rem; min-height:100vh;
            position:relative;
        }
        body::before{
            content:''; position:fixed; inset:0; z-index:-1;
            background-image:url('{{ asset('images/backgrounds/auth.webp') }}');
            background-size:cover; background-position:center;
            filter:brightness(0.3) contrast(1.1);
        }
        body::after{
            content:''; position:fixed; inset:0; z-index:-1;
            background:radial-gradient(ellipse at top,rgba(124,58,237,0.25) 0%,transparent 60%),
                        radial-gradient(ellipse at bottom,rgba(236,72,153,0.2) 0%,transparent 60%);
            pointer-events:none;
        }
        .card{
            width:100%; max-width:26rem;
            background:var(--bg-card); backdrop-filter:blur(24px);
            border:1px solid rgba(255,255,255,0.1); border-radius:1.5rem;
            padding:2rem; box-shadow:0 30px 60px -20px rgba(0,0,0,0.8);
            position:relative; z-index:1;
            animation:slideUp 0.4s ease-out;
        }
        @keyframes slideUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .header{text-align:center; margin-bottom:1.75rem;}
        .logo{
            font-weight:800; font-size:1.2rem; margin-bottom:0.5rem;
            background:linear-gradient(135deg,var(--primary),var(--primary-end));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            display:flex; align-items:center; justify-content:center; gap:0.5rem;
        }
        .header h2{font-size:1.1rem; font-weight:700; margin-bottom:0.3rem;}
        .header p{color:var(--text-dim); font-size:0.85rem; line-height:1.5;}
        .step-badge{
            display:inline-flex; align-items:center; gap:0.4rem;
            background:rgba(124,58,237,0.15); border:1px solid rgba(124,58,237,0.3);
            color:#A78BFA; font-size:0.78rem; font-weight:600;
            padding:0.3rem 0.75rem; border-radius:2rem; margin-bottom:1rem;
        }
        .form-group{margin-bottom:1rem;}
        label{display:block; font-size:0.88rem; font-weight:500; margin-bottom:0.4rem; color:var(--text);}
        .form-control{
            width:100%; padding:0.75rem 1rem;
            background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.12);
            border-radius:0.6rem; color:var(--text); font-size:0.95rem; font-family:inherit;
            transition:border-color 0.2s;
        }
        .form-control:focus{outline:none; border-color:var(--accent); background:rgba(255,255,255,0.08);}
        .form-control.is-error{border-color:var(--error); background:rgba(239,68,68,0.05);}
        .error-text{color:var(--error); font-size:0.78rem; margin-top:0.3rem; display:flex; align-items:center; gap:0.3rem;}
        /* Plan cards */
        .plan-options{display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; margin-top:0.25rem;}
        .plan-option{position:relative;}
        .plan-option input[type=radio]{position:absolute; opacity:0; width:0; height:0;}
        .plan-label{
            display:block; padding:0.65rem 0.4rem; border-radius:0.6rem; cursor:pointer; text-align:center;
            border:1px solid rgba(255,255,255,0.1); background:rgba(255,255,255,0.03);
            transition:all 0.2s; font-size:0.8rem;
        }
        .plan-label .plan-name{font-weight:700; display:block; margin-bottom:0.15rem;}
        .plan-label .plan-price{font-size:0.72rem; color:var(--text-dim);}
        .plan-option input:checked + .plan-label{
            border-color:rgba(124,58,237,0.6); background:rgba(124,58,237,0.15); color:#A78BFA;
        }
        .plan-option input:checked + .plan-label .plan-price{color:#A78BFA;}
        .plan-label:hover{border-color:rgba(124,58,237,0.4); background:rgba(124,58,237,0.08);}
        /* Button */
        .btn{
            width:100%; padding:0.9rem; margin-top:1.25rem;
            background:linear-gradient(135deg,var(--primary),var(--primary-end));
            color:#fff; border:none; border-radius:0.75rem;
            font-weight:700; font-size:1rem; cursor:pointer; font-family:inherit;
            transition:all 0.2s; box-shadow:0 4px 20px rgba(124,58,237,0.4);
        }
        .btn:hover{transform:translateY(-2px); box-shadow:0 8px 30px rgba(236,72,153,0.5);}
        /* Logout link */
        .logout-link{
            display:block; text-align:center; margin-top:1rem;
            font-size:0.8rem; color:var(--text-dim); text-decoration:none;
        }
        .logout-link:hover{color:var(--error);}
        select option{background:#0a0a0f !important; color:#fff !important;}
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <div class="logo">
            <img src="{{ asset('images/icons/icon-gym-logo.webp') }}" alt="Logo"
                 style="width:1.6rem;height:1.6rem;object-fit:contain;border-radius:0.3rem;" onerror="this.style.display='none'">
            GYMRESERVAS
        </div>
        <div class="step-badge"><i class="bi bi-google"></i> Acceso con Google</div>
        <h2>¡Casi listo, {{ Auth::user()->name }}!</h2>
        <p>Para acceder necesitamos un par de datos más.<br>Solo se piden una vez.</p>
    </div>

    <form method="POST" action="{{ route('profile.complete.store') }}">
        @csrf

        {{-- DNI --}}
        <div class="form-group">
            <label for="dni"><i class="bi bi-card-text"></i> DNI <small style="color:var(--text-dim);font-weight:400;">(8 dígitos + letra)</small></label>
            <input id="dni" type="text" name="dni"
                   value="{{ $input['dni'] ?? '' }}"
                   maxlength="9" placeholder="Ej: 12345678Z"
                   class="form-control @error('dni') is-error @enderror"
                   oninput="this.value=this.value.toUpperCase()">
            @error('dni')<span class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>@enderror
        </div>

        {{-- Fecha de nacimiento --}}
        <div class="form-group">
            <label for="birth_date"><i class="bi bi-cake2"></i> Fecha de nacimiento</label>
            <input id="birth_date" type="date" name="birth_date"
                   value="{{ $input['birth_date'] ?? '' }}"
                   max="{{ date('Y-m-d', strtotime('-16 years')) }}"
                   class="form-control @error('birth_date') is-error @enderror">
            @error('birth_date')<span class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>@enderror
            <span id="ageWarn" style="display:none;font-size:0.78rem;color:var(--error);margin-top:0.3rem;">
                <i class="bi bi-exclamation-triangle"></i> Debes tener al menos 16 años.
            </span>
        </div>

        {{-- Plan --}}
        <div class="form-group">
            <label><i class="bi bi-stars"></i> Plan de suscripción</label>
            @php $selectedPlan = $input['plan_type'] ?? 'free'; @endphp
            <div class="plan-options">
                <div class="plan-option">
                    <input type="radio" name="plan_type" id="plan_free" value="free" @if($selectedPlan==='free') checked @endif>
                    <label class="plan-label" for="plan_free">
                        <span class="plan-name">Free</span>
                        <span class="plan-price">0 €/mes</span>
                    </label>
                </div>
                <div class="plan-option">
                    <input type="radio" name="plan_type" id="plan_basico" value="basico" @if($selectedPlan==='basico') checked @endif>
                    <label class="plan-label" for="plan_basico">
                        <span class="plan-name">Básico</span>
                        <span class="plan-price">9,99 €/mes</span>
                    </label>
                </div>
                <div class="plan-option">
                    <input type="radio" name="plan_type" id="plan_premium" value="premium" @if($selectedPlan==='premium') checked @endif>
                    <label class="plan-label" for="plan_premium">
                        <span class="plan-name">Premium</span>
                        <span class="plan-price">19,99 €/mes</span>
                    </label>
                </div>
            </div>
            @error('plan_type')<span class="error-text"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn"><i class="bi bi-check-circle"></i> Acceder a GymReservas</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-link" style="background:none;border:none;cursor:pointer;width:100%;">
            <i class="bi bi-box-arrow-right"></i> Salir y usar otra cuenta
        </button>
    </form>
</div>

<script>
document.getElementById('birth_date').addEventListener('change', function () {
    const warn = document.getElementById('ageWarn');
    const val  = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - val.getFullYear();
    const m = today.getMonth() - val.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < val.getDate())) age--;
    if (this.value && age < 16) {
        warn.style.display = 'block';
        this.classList.add('is-error');
    } else {
        warn.style.display = 'none';
        this.classList.remove('is-error');
    }
});
</script>
</body>
</html>
