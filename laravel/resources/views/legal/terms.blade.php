<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Términos de Servicio - GymReservas</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root{--bg-dark:#0a0a0f;--primary-start:#7C3AED;--primary-end:#EC4899;--accent:#06B6D4;--text-primary:#fff;--text-secondary:#9CA3AF;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Manrope',system-ui,sans-serif;background:var(--bg-dark);color:var(--text-primary);min-height:100vh;line-height:1.7;}
        body::before{content:'';position:fixed;inset:0;z-index:-1;
            background:radial-gradient(ellipse at top,rgba(124,58,237,0.12) 0%,transparent 55%),
                       radial-gradient(ellipse at bottom right,rgba(236,72,153,0.08) 0%,transparent 55%),
                       var(--bg-dark);}
        nav{position:fixed;top:0;width:100%;background:rgba(17,24,39,0.92);backdrop-filter:blur(20px);
            border-bottom:1px solid rgba(255,255,255,0.08);padding:0.85rem 2rem;
            display:flex;justify-content:space-between;align-items:center;z-index:200;}
        .logo{font-weight:800;font-size:1.2rem;text-decoration:none;display:flex;align-items:center;gap:0.5rem;
            background:linear-gradient(135deg,var(--primary-start),var(--primary-end));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;}
        .back-btn{color:var(--text-secondary);text-decoration:none;font-size:0.875rem;
            padding:0.4rem 0.75rem;border-radius:0.5rem;border:1px solid rgba(255,255,255,0.1);
            transition:all 0.2s;display:flex;align-items:center;gap:0.4rem;}
        .back-btn:hover{color:var(--text-primary);background:rgba(255,255,255,0.06);}
        .container{max-width:780px;margin:0 auto;padding:6rem 1.5rem 3rem;}
        .page-title{font-size:2rem;font-weight:800;margin-bottom:0.5rem;
            background:linear-gradient(135deg,var(--primary-start),var(--primary-end));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;}
        .updated{color:var(--text-secondary);font-size:0.85rem;margin-bottom:2.5rem;}
        .section{margin-bottom:2rem;}
        h2{font-size:1.1rem;font-weight:700;color:#A78BFA;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem;}
        p,li{color:#CBD5E1;font-size:0.95rem;}
        ul{padding-left:1.25rem;margin-top:0.5rem;}
        li{margin-bottom:0.35rem;}
        .card{background:rgba(23,31,47,0.7);border:1px solid rgba(255,255,255,0.07);border-radius:1rem;padding:1.5rem;margin-bottom:1rem;}
        .plan-table{width:100%;border-collapse:collapse;margin-top:0.75rem;font-size:0.88rem;}
        .plan-table th{background:rgba(124,58,237,0.2);color:#E2E8F0;padding:0.6rem 0.9rem;text-align:left;font-weight:600;}
        .plan-table td{padding:0.6rem 0.9rem;border-bottom:1px solid rgba(255,255,255,0.05);color:#CBD5E1;vertical-align:top;}
        .plan-table tr:last-child td{border-bottom:none;}
        footer{text-align:center;padding:1.5rem;color:#6B7280;font-size:0.8rem;border-top:1px solid rgba(255,255,255,0.05);margin-top:2rem;}
        footer a{color:#9CA3AF;text-decoration:none;margin:0 0.5rem;}
        footer a:hover{color:#A78BFA;}
    </style>
</head>
<body>
<nav>
    <a href="{{ route('home') }}" class="logo">
        <img src="{{ asset('images/icons/icon-gym-logo.webp') }}" alt="Logo"
             style="width:1.7rem;height:1.7rem;object-fit:contain;border-radius:0.3rem;" onerror="this.style.display='none'">
        GYMRESERVAS
    </a>
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</nav>

<main class="container">
    <div class="page-title"><i class="bi bi-journal-check" style="-webkit-text-fill-color:#A78BFA;"></i> Términos de Servicio</div>
    <p class="updated"><i class="bi bi-calendar3"></i> Última actualización: {{ date('d/m/Y') }}</p>

    <div class="card">
        <p>Al registrarte y utilizar <strong>GymReservas</strong>, aceptas quedar vinculado por estos Términos de Servicio. Si no estás de acuerdo con alguno de ellos, no debes utilizar el Servicio.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-person-check"></i> 1. Registro y Cuenta de Usuario</h2>
        <ul>
            <li>Para usar el Servicio debes tener al menos <strong>16 años</strong>.</li>
            <li>Debes proporcionar datos verídicos durante el registro.</li>
            <li>Eres responsable de mantener la confidencialidad de tus credenciales de acceso.</li>
            <li>No puedes crear cuentas con datos falsos ni suplantar la identidad de otra persona.</li>
            <li>Cada persona solo puede tener una cuenta activa.</li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-calendar-check"></i> 2. Reservas de Clases</h2>
        <ul>
            <li>Las reservas quedan confirmadas en el momento de su creación, sujeto a disponibilidad de aforo.</li>
            <li>No se puede reservar una clase que ya haya comenzado.</li>
            <li>No se puede reservar la misma clase dos veces en la misma semana.</li>
            <li>El número máximo de reservas semanales depende de tu plan de suscripción.</li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-x-circle"></i> 3. Cancelaciones</h2>
        <p>Los plazos mínimos de cancelación según plan son:</p>
        <table class="plan-table">
            <thead><tr><th>Plan</th><th>Plazo mínimo de cancelación</th></tr></thead>
            <tbody>
                <tr><td><span style="color:#94A3B8;font-weight:600;">Free</span></td><td>Sin restricción de tiempo (hasta el inicio de la clase)</td></tr>
                <tr><td><span style="color:#60A5FA;font-weight:600;">Básico</span></td><td>Mínimo 2 horas antes del inicio</td></tr>
                <tr><td><span style="color:#EC4899;font-weight:600;">Premium</span></td><td>Mínimo 1 hora antes del inicio</td></tr>
            </tbody>
        </table>
        <p style="margin-top:0.75rem;">No se puede cancelar una clase que ya haya comenzado.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-stars"></i> 4. Planes y Pagos</h2>
        <table class="plan-table">
            <thead><tr><th>Plan</th><th>Precio</th><th>Reservas / semana</th></tr></thead>
            <tbody>
                <tr><td><strong>Free</strong></td><td>0 €/mes</td><td>1 (día de prueba, no renovable)</td></tr>
                <tr><td><strong>Básico</strong></td><td>9,99 €/mes</td><td>5</td></tr>
                <tr><td><strong>Premium</strong></td><td>19,99 €/mes</td><td>Ilimitadas</td></tr>
            </tbody>
        </table>
        <ul style="margin-top:0.75rem;">
            <li>El cobro es mensual y se realiza en el momento del cambio de plan.</li>
            <li>Puedes cambiar o cancelar tu plan en cualquier momento desde "Mi Plan".</li>
            <li>No se realizan reembolsos por el mes en curso.</li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-ban"></i> 5. Uso Prohibido</h2>
        <p>Está prohibido:</p>
        <ul>
            <li>Usar el Servicio con fines fraudulentos o ilegales</li>
            <li>Intentar acceder a datos de otros usuarios</li>
            <li>Realizar ataques o pruebas de penetración sin autorización expresa</li>
            <li>Automatizar reservas de forma masiva o mediante bots</li>
            <li>Facilitar tus credenciales a terceros para que usen el Servicio</li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-power"></i> 6. Suspensión y Baja</h2>
        <p>El administrador podrá suspender o eliminar cuentas que incumplan estos términos sin previo aviso. El usuario puede solicitar la baja de su cuenta en cualquier momento contactando con el soporte.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-pencil-square"></i> 7. Modificaciones de los Términos</h2>
        <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Los cambios se comunicarán mediante aviso en el Servicio. El uso continuado del Servicio tras la publicación de cambios implica su aceptación.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-envelope"></i> 8. Contacto</h2>
        <p>Para cualquier consulta sobre estos términos: <a href="mailto:info@gymreservas.es" style="color:#A78BFA;">info@gymreservas.es</a></p>
    </div>
</main>

<footer>
    <a href="{{ route('legal.privacy') }}">Privacidad</a> ·
    <a href="{{ route('legal.cookies') }}">Cookies</a> ·
    <a href="{{ route('legal.notice') }}">Aviso Legal</a> ·
    <a href="{{ route('legal.terms') }}">Términos</a>
    <br><span style="display:block;margin-top:0.4rem;">© {{ date('Y') }} GymReservas · Proyecto académico IES La Marisma</span>
</footer>
</body>
</html>
