{{-- ═══════════════════════════════════════════════════════
     Pie de página compartido — se incluye en todas las vistas de cliente
     Usage: @include('partials.footer')
═══════════════════════════════════════════════════════ --}}
<footer style="
    margin-top:3rem;
    border-top:1px solid rgba(255,255,255,0.06);
    padding:1.75rem 1.5rem 1.25rem;
    text-align:center;
    font-family:'Manrope',system-ui,sans-serif;
    color:#6B7280;
    font-size:0.8rem;
    position:relative;
    z-index:1;
">
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:0.25rem 1.25rem;margin-bottom:0.75rem;">
        <a href="{{ route('legal.privacy') }}"
           style="color:#9CA3AF;text-decoration:none;transition:color 0.2s;"
           onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color='#9CA3AF'">
            <i class="bi bi-shield-lock"></i> Política de Privacidad
        </a>
        <span style="color:#374151;">·</span>
        <a href="{{ route('legal.cookies') }}"
           style="color:#9CA3AF;text-decoration:none;transition:color 0.2s;"
           onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color='#9CA3AF'">
            <i class="bi bi-cookie"></i> Política de Cookies
        </a>
        <span style="color:#374151;">·</span>
        <a href="{{ route('legal.notice') }}"
           style="color:#9CA3AF;text-decoration:none;transition:color 0.2s;"
           onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color='#9CA3AF'">
            <i class="bi bi-file-earmark-text"></i> Aviso Legal
        </a>
        <span style="color:#374151;">·</span>
        <a href="{{ route('legal.terms') }}"
           style="color:#9CA3AF;text-decoration:none;transition:color 0.2s;"
           onmouseover="this.style.color='#A78BFA'" onmouseout="this.style.color='#9CA3AF'">
            <i class="bi bi-journal-check"></i> Términos de Servicio
        </a>
    </div>
    <p>© {{ date('Y') }} GymReservas &nbsp;·&nbsp; Proyecto académico IES La Marisma</p>
</footer>
