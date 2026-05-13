<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Política de Cookies - GymReservas</title>
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
        table{width:100%;border-collapse:collapse;margin-top:0.75rem;font-size:0.88rem;}
        th{background:rgba(124,58,237,0.2);color:#E2E8F0;padding:0.6rem 0.9rem;text-align:left;font-weight:600;}
        td{padding:0.6rem 0.9rem;border-bottom:1px solid rgba(255,255,255,0.05);color:#CBD5E1;vertical-align:top;}
        tr:last-child td{border-bottom:none;}
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
    <div class="page-title"><i class="bi bi-cookie" style="-webkit-text-fill-color:#A78BFA;"></i> Política de Cookies</div>
    <p class="updated"><i class="bi bi-calendar3"></i> Última actualización: {{ date('d/m/Y') }}</p>

    <div class="card">
        <p>Esta política explica qué son las cookies, qué tipos utilizamos en <strong>GymReservas</strong> y cómo puedes gestionarlas, de conformidad con la <strong>Ley 34/2002 (LSSI)</strong> y el <strong>RGPD</strong>.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-question-circle"></i> 1. ¿Qué son las Cookies?</h2>
        <p>Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas un sitio web. Permiten recordar tus preferencias, mantener tu sesión iniciada y analizar cómo se usa el servicio.</p>
    </div>

    <div class="section">
        <h2><i class="bi bi-list-check"></i> 2. Cookies que Utilizamos</h2>
        <table>
            <thead>
                <tr><th>Cookie</th><th>Tipo</th><th>Finalidad</th><th>Duración</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><code style="color:#06B6D4;">gymreservas_session</code></td>
                    <td>Técnica / Esencial</td>
                    <td>Mantiene la sesión del usuario autenticado. Sin ella, no es posible usar el Servicio.</td>
                    <td>Sesión (se borra al cerrar el navegador) o hasta 7 días si has iniciado sesión</td>
                </tr>
                <tr>
                    <td><code style="color:#06B6D4;">XSRF-TOKEN</code></td>
                    <td>Técnica / Seguridad</td>
                    <td>Protege los formularios contra ataques CSRF (falsificación de solicitudes entre sitios).</td>
                    <td>2 horas</td>
                </tr>
                <tr>
                    <td><code style="color:#06B6D4;">remember_web_*</code></td>
                    <td>Funcional</td>
                    <td>Recuerda tu sesión para no tener que iniciar sesión en cada visita.</td>
                    <td>7 días</td>
                </tr>
            </tbody>
        </table>
        <p style="margin-top:0.75rem;font-size:0.85rem;color:var(--text-secondary);">
            <i class="bi bi-info-circle"></i> GymReservas <strong>no utiliza cookies de publicidad ni de seguimiento de terceros</strong>. No compartimos datos de cookies con redes publicitarias.
        </p>
    </div>

    <div class="section">
        <h2><i class="bi bi-toggle-on"></i> 3. Gestión de Cookies</h2>
        <p>Puedes configurar tu navegador para bloquear o eliminar cookies. Ten en cuenta que bloquear las cookies esenciales impedirá el correcto funcionamiento del Servicio (no podrás iniciar sesión).</p>
        <ul style="margin-top:0.75rem;">
            <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" style="color:#A78BFA;">Google Chrome</a></li>
            <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web-rastrear-preferencias" target="_blank" style="color:#A78BFA;">Mozilla Firefox</a></li>
            <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" style="color:#A78BFA;">Apple Safari</a></li>
            <li><a href="https://support.microsoft.com/es-es/topic/eliminar-y-administrar-cookies-168dab11-0753-043d-7c16-ede5947fc64d" target="_blank" style="color:#A78BFA;">Microsoft Edge</a></li>
        </ul>
    </div>

    <div class="section">
        <h2><i class="bi bi-arrow-repeat"></i> 4. Actualizaciones de Esta Política</h2>
        <p>Podemos actualizar esta política cuando sea necesario. Te recomendamos revisarla periódicamente. Los cambios significativos se comunicarán mediante aviso en el Servicio.</p>
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
