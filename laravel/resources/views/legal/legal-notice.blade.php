<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aviso Legal - GymReservas</title>
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
        .info-row{display:flex;gap:0.5rem;margin-bottom:0.3rem;font-size:0.92rem;}
        .info-label{color:#9CA3AF;min-width:130px;flex-shrink:0;}
        .info-value{color:#E2E8F0;font-weight:500;}
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
    <div class="page-title"><i class="bi bi-file-earmark-text" style="-webkit-text-fill-color:#A78BFA;"></i> Aviso Legal</div>
    <p class="updated"><i class="bi bi-calendar3"></i> Última actualización: {{ date('d/m/Y') }}</p>

    <div class="section">
        <h2><i class="bi bi-building"></i> 1. Datos del Titular</h2>
        <div class="card">
            <div class="info-row"><span class="info-label">Denominación:</span><span class="info-value">GymReservas (Proyecto Académico)</span></div>
            <div class="info-row"><span class="info-label">Centro educativo:</span><span class="info-value">IES La Marisma, Huelva</span></div>
            <div class="info-row"><span class="info-label">Email de contacto:</span><span class="info-value"><a href="mailto:info@gymreservas.es" style="color:#A78BFA;">info@gymreservas.es</a></span></div>
            <div class="info-row"><span class="info-label">Dominio:</span><span class="info-value">ieslamarisma.net/proyectos/2026/joseangelaquino/gym-reservas/</span></div>
        </div>
    </div>

    <div class="section">
        <h2><i class="bi bi-info-circle"></i> 2. Objeto y Naturaleza del Servicio</h2>
        <p>GymReservas es una <strong>plataforma de gestión de reservas de clases de gimnasio</strong> desarrollada como proyecto académico de fin de ciclo. Permite a los usuarios registrados reservar clases, gestionar suscripciones y consultar su historial de pagos.</p>
        <p style="margin-top:0.75rem;padding:0.75rem 1rem;background:rgba(245,158,11,0.08);border-left:3px solid #F59E0B;border-radius:0 0.5rem 0.5rem 0;font-size:0.9rem;color:#FCD34D;">
            <i class="bi bi-exclamation-triangle"></i> <strong>Aviso:</strong> Este servicio es un proyecto académico y no tiene carácter comercial real. Los datos de pago son ficticios y no se realizan transacciones económicas reales.
        </p>
    </div>

    <div class="section">
        <h2><i class="bi bi-file-code"></i> 3. Propiedad Intelectual</h2>
        <p>El código fuente, diseño, logotipos y contenidos del Servicio son propiedad de sus autores (alumnos de IES La Marisma) salvo indicación en contrario. Queda prohibida su reproducción total o parcial sin autorización expresa.</p>
        <p style="margin-top:0.5rem;">Las tecnologías de terceros utilizadas (Laravel, Bootstrap Icons, SweetAlert2, DataTables, etc.) se rigen por sus respectivas licencias de código abierto.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-shield-exclamation"></i> 4. Exención de Responsabilidad</h2>
        <p>El titular no se hace responsable de:</p>
        <ul>
            <li>Interrupciones del servicio por causas técnicas o de mantenimiento</li>
            <li>Daños derivados del uso inadecuado del Servicio</li>
            <li>Contenidos de sitios web de terceros enlazados desde el Servicio</li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-globe"></i> 5. Legislación Aplicable y Jurisdicción</h2>
        <p>Este aviso legal se rige por la legislación española. Para cualquier controversia, las partes se someten a los Juzgados y Tribunales de <strong>Huelva (España)</strong>, renunciando a cualquier otro fuero que pudiera corresponderles.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-envelope"></i> 6. Contacto</h2>
        <p>Para cualquier consulta relacionada con este aviso legal, puedes contactarnos en: <a href="mailto:info@gymreservas.es" style="color:#A78BFA;">info@gymreservas.es</a></p>
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
