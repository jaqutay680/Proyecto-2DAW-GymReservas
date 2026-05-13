<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Política de Privacidad - GymReservas</title>
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
    <div class="page-title"><i class="bi bi-shield-lock" style="-webkit-text-fill-color:#A78BFA;"></i> Política de Privacidad</div>
    <p class="updated"><i class="bi bi-calendar3"></i> Última actualización: {{ date('d/m/Y') }}</p>

    <div class="card">
        <p>En <strong>GymReservas</strong> (en adelante, "el Servicio"), nos comprometemos a proteger tu privacidad y a tratar tus datos personales de conformidad con el <strong>Reglamento (UE) 2016/679 (RGPD)</strong> y la <strong>Ley Orgánica 3/2018 (LOPDGDD)</strong>.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-person-badge"></i> 1. Responsable del Tratamiento</h2>
        <p>El responsable del tratamiento de los datos personales es el titular del proyecto académico <strong>GymReservas</strong>, desarrollado en el centro <strong>IES La Marisma</strong> (Huelva). Contacto: <a href="mailto:info@gymreservas.es" style="color:#A78BFA;">info@gymreservas.es</a></p>
    </div>

    <div class="section">
        <h2><i class="bi bi-collection"></i> 2. Datos que Recogemos</h2>
        <p>Recogemos los siguientes datos personales cuando te registras o usas el Servicio:</p>
        <ul>
            <li>Nombre completo y dirección de correo electrónico</li>
            <li>DNI (opcional, para verificación de identidad)</li>
            <li>Fecha de nacimiento (para validar requisitos de edad mínima por actividad)</li>
            <li>Historial de reservas y pagos vinculados a tu cuenta</li>
            <li>Dirección IP y datos de sesión (para seguridad y prevención de fraude)</li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-bullseye"></i> 3. Finalidad del Tratamiento</h2>
        <p>Tus datos se usan para:</p>
        <ul>
            <li>Gestionar tu cuenta de usuario y autenticarte en el Servicio</li>
            <li>Procesar y confirmar reservas de clases</li>
            <li>Gestionar el cobro de suscripciones y pagos</li>
            <li>Enviarte notificaciones relacionadas con tus reservas</li>
            <li>Cumplir con obligaciones legales y contables</li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-check2-shield"></i> 4. Base Jurídica</h2>
        <p>El tratamiento se basa en:</p>
        <ul>
            <li><strong>Ejecución de contrato</strong>: necesario para prestarte el Servicio que has solicitado.</li>
            <li><strong>Consentimiento</strong>: para comunicaciones opcionales.</li>
            <li><strong>Obligación legal</strong>: para cumplir con la normativa fiscal y mercantil aplicable.</li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-share"></i> 5. Cesión de Datos</h2>
        <p>No cedemos tus datos a terceros salvo obligación legal. Los proveedores de servicios técnicos (hosting, etc.) actúan como encargados del tratamiento bajo contrato conforme al RGPD.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-clock-history"></i> 6. Plazos de Conservación</h2>
        <p>Conservamos tus datos mientras mantengas una cuenta activa. Tras la baja, los datos se eliminan o anonomizan en un plazo máximo de <strong>5 años</strong>, salvo que la ley exija un período mayor.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-hand-index"></i> 7. Tus Derechos</h2>
        <p>Puedes ejercer en cualquier momento los derechos de:</p>
        <ul>
            <li><strong>Acceso, rectificación y supresión</strong> de tus datos</li>
            <li><strong>Portabilidad</strong> y <strong>oposición</strong> al tratamiento</li>
            <li><strong>Limitación</strong> del tratamiento en los casos previstos por la ley</li>
        </ul>
        <p style="margin-top:0.75rem;">Enviando un email a <a href="mailto:info@gymreservas.es" style="color:#A78BFA;">info@gymreservas.es</a> con copia de tu DNI. También puedes reclamar ante la <strong>Agencia Española de Protección de Datos (AEPD)</strong> en <a href="https://www.aepd.es" target="_blank" style="color:#A78BFA;">www.aepd.es</a>.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-lock"></i> 8. Seguridad</h2>
        <p>Aplicamos medidas técnicas y organizativas adecuadas (cifrado, control de acceso, copias de seguridad) para proteger tus datos frente a accesos no autorizados, pérdida o alteración.</p>
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
