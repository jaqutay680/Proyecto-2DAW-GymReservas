{{-- ═══════════════════════════════════════════════════════
     Shared client navbar + profile modal (con edición de perfil)
     Usage: @include('partials.client-nav', ['activePage' => 'dashboard'])
     Requires: $user (Eloquent User with birth_date cast)
═══════════════════════════════════════════════════════ --}}

{{-- SweetAlert2 (disponible en todas las páginas de cliente) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Profile modal CSS --}}
<style>
.profile-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.65); backdrop-filter: blur(6px);
    z-index: 9000; align-items: center; justify-content: center; padding: 1rem;
    overflow-y: auto;
}
.profile-modal-overlay.active { display: flex; }
.profile-modal-box {
    background: rgba(15,23,42,0.98); border: 1px solid rgba(255,255,255,0.12);
    border-radius: 1.5rem; padding: 2rem; max-width: 440px; width: 100%;
    box-shadow: 0 30px 60px rgba(0,0,0,0.6);
    animation: pmIn 0.22s ease-out;
    margin: auto;
}
@keyframes pmIn { from{opacity:0;transform:scale(0.94)} to{opacity:1;transform:scale(1)} }
.pm-avatar {
    width: 4rem; height: 4rem; border-radius: 50%;
    background: linear-gradient(135deg,#7C3AED,#EC4899);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 700; color: #fff;
    margin: 0 auto 1rem; flex-shrink: 0;
}
.pm-header { text-align: center; margin-bottom: 1.25rem; }
.pm-name { font-size: 1.2rem; font-weight: 700; }
.pm-role { font-size: 0.78rem; color: #9CA3AF; margin-top: 0.2rem; }
.pm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem; }
.pm-field { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 0.75rem; padding: 0.7rem 0.9rem; }
.pm-label { font-size: 0.7rem; color: #6B7280; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.2rem; }
.pm-value { font-size: 0.88rem; font-weight: 600; color: #F1F5F9; word-break: break-all; }
.pm-field.full { grid-column: 1 / -1; }

/* Botón editar toggle */
.pm-edit-toggle {
    width: 100%; padding: 0.5rem; border-radius: 0.75rem;
    border: 1px solid rgba(124,58,237,0.35);
    background: rgba(124,58,237,0.08); color: #A78BFA;
    font-size: 0.85rem; font-weight: 500; cursor: pointer;
    font-family: inherit; transition: all 0.2s; margin-bottom: 0.75rem;
}
.pm-edit-toggle:hover { background: rgba(124,58,237,0.18); }

/* Sección de edición */
.pm-edit-section {
    border-top: 1px solid rgba(255,255,255,0.08);
    padding-top: 1rem; margin-bottom: 0.75rem;
}
.pm-edit-field { margin-bottom: 0.65rem; }
.pm-edit-label {
    font-size: 0.72rem; color: #6B7280; text-transform: uppercase;
    letter-spacing: 0.05em; margin-bottom: 0.3rem; display: block;
}
.pm-input {
    width: 100%; padding: 0.5rem 0.75rem;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.14);
    color: #F1F5F9; border-radius: 0.5rem; font-size: 0.88rem; font-family: inherit;
    transition: border-color 0.2s;
}
.pm-input:focus { outline: none; border-color: rgba(124,58,237,0.55); }
.pm-edit-actions { display: flex; gap: 0.5rem; margin-top: 0.75rem; }
.pm-save-btn {
    flex: 1; padding: 0.55rem; border-radius: 0.5rem; border: none;
    background: linear-gradient(135deg,#7C3AED,#EC4899); color: #fff;
    font-weight: 700; font-size: 0.88rem; cursor: pointer; font-family: inherit;
    transition: opacity 0.2s;
}
.pm-save-btn:hover { opacity: 0.88; }
.pm-discard-btn {
    padding: 0.55rem 1rem; border-radius: 0.5rem;
    border: 1px solid rgba(255,255,255,0.12);
    background: rgba(255,255,255,0.05); color: #9CA3AF;
    font-size: 0.88rem; cursor: pointer; font-family: inherit;
}

/* Botón cerrar */
.pm-close {
    width: 100%; padding: 0.55rem; border-radius: 0.75rem;
    border: 1px solid rgba(255,255,255,0.12);
    background: rgba(255,255,255,0.05); color: #9CA3AF;
    font-size: 0.9rem; font-weight: 500; cursor: pointer;
    font-family: inherit; transition: all 0.2s;
}
.pm-close:hover { background: rgba(255,255,255,0.1); color: #fff; }

/* SweetAlert2 dark override para cliente */
.swal2-popup { font-family: 'Manrope', system-ui, sans-serif !important; }
</style>

{{-- NAVBAR --}}
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="logo">
        <img src="{{ asset('images/icons/icon-gym-logo.webp') }}" alt="Logo"
             style="width:1.7rem;height:1.7rem;object-fit:contain;border-radius:0.3rem;"
             onerror="this.style.display='none'">
        GYMRESERVAS
    </a>
    <button class="hamburger" onclick="document.getElementById('navLinks').classList.toggle('open')">
        <i class="bi bi-list"></i>
    </button>
    <div class="nav-links" id="navLinks">
        <a href="{{ route('dashboard') }}"        class="nav-link {{ ($activePage??'') === 'dashboard'      ? 'active' : '' }}"><i class="bi bi-house"></i> Clases</a>
        <a href="{{ route('my-reservations') }}"  class="nav-link {{ ($activePage??'') === 'reservations'   ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> Mis Reservas</a>
        <a href="{{ route('my-payments') }}"      class="nav-link {{ ($activePage??'') === 'payments'       ? 'active' : '' }}"><i class="bi bi-credit-card"></i> Mis Pagos</a>
        <a href="{{ route('my-subscriptions') }}" class="nav-link {{ ($activePage??'') === 'subscriptions'  ? 'active' : '' }}"><i class="bi bi-stars"></i> Mi Plan</a>
        @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ url('/admin') }}" class="btn-admin-link"><i class="bi bi-shield-lock"></i> Admin</a>
        @endif
        <button class="user-chip" onclick="openProfileModal()" title="Ver mi perfil" style="background:none;border:1px solid rgba(255,255,255,0.1);cursor:pointer;">
            <i class="bi bi-person-circle"></i>
            <span>{{ $user->name }}</span>
            <span class="plan-badge {{ $user->plan_type ?? 'free' }}">{{ strtoupper($user->plan_type ?? 'free') }}</span>
        </button>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf <button type="submit" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Salir</button>
        </form>
    </div>
</nav>

{{-- PROFILE MODAL --}}
<div class="profile-modal-overlay" id="profileModal" onclick="if(event.target===this)closeProfileModal()">
    <div class="profile-modal-box">
        <div class="pm-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="pm-header">
            <div class="pm-name">{{ $user->name }}</div>
            <div class="pm-role">
                @if($user->role === 'admin') <span style="color:#A78BFA;">Administrador</span>
                @else <span style="color:#9CA3AF;">Cliente</span>
                @endif
            </div>
        </div>

        {{-- Info de solo lectura --}}
        <div class="pm-grid" id="pmInfoGrid">
            <div class="pm-field full">
                <div class="pm-label"><i class="bi bi-envelope"></i> Email</div>
                <div class="pm-value">{{ $user->email }}</div>
            </div>
            <div class="pm-field">
                <div class="pm-label"><i class="bi bi-card-text"></i> DNI</div>
                <div class="pm-value">{{ $user->dni ?? '—' }}</div>
            </div>
            <div class="pm-field">
                <div class="pm-label"><i class="bi bi-cake2"></i> Edad</div>
                <div class="pm-value">
                    @if($user->birth_date) {{ $user->birth_date->age }} años @else —@endif
                </div>
            </div>
            <div class="pm-field">
                <div class="pm-label"><i class="bi bi-calendar"></i> Fecha nac.</div>
                <div class="pm-value">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '—' }}</div>
            </div>
            <div class="pm-field">
                <div class="pm-label"><i class="bi bi-star"></i> Plan</div>
                <div class="pm-value">
                    <span class="plan-badge {{ $user->plan_type ?? 'free' }}" style="font-size:0.75rem;padding:0.15rem 0.5rem;">
                        {{ strtoupper($user->plan_type ?? 'FREE') }}
                    </span>
                </div>
            </div>
            <div class="pm-field">
                <div class="pm-label"><i class="bi bi-shield-check"></i> Estado</div>
                <div class="pm-value">
                    @php $ms = $user->membership_status ?? 'active'; @endphp
                    <span style="color:{{ $ms === 'active' ? '#10B981' : ($ms === 'suspended' ? '#EF4444' : '#F59E0B') }};">
                        {{ $ms === 'active' ? 'Activo' : ($ms === 'suspended' ? 'Suspendido' : ucfirst($ms)) }}
                    </span>
                </div>
            </div>
            <div class="pm-field full">
                <div class="pm-label"><i class="bi bi-clock-history"></i> Miembro desde</div>
                <div class="pm-value">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d \d\e F \d\e Y') : '—' }}</div>
            </div>
        </div>

        {{-- Toggle editar perfil --}}
        <button class="pm-edit-toggle" id="editToggleBtn" onclick="toggleEditProfile()">
            <i class="bi bi-pencil-square"></i> Editar email o contraseña
        </button>

        {{-- Formulario de edición (oculto por defecto) --}}
        <div id="editProfileSection" style="display:none;" class="pm-edit-section">
            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf @method('PATCH')
                <div class="pm-edit-field">
                    <label class="pm-edit-label"><i class="bi bi-envelope"></i> Email</label>
                    <input type="email" name="email" value="{{ $user->email }}"
                           class="pm-input" autocomplete="email">
                    @error('email')<div style="color:#EF4444;font-size:0.78rem;margin-top:0.25rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
                <div class="pm-edit-field">
                    <label class="pm-edit-label"><i class="bi bi-lock"></i> Contraseña actual <span style="color:#EF4444">*</span></label>
                    <input type="password" name="current_password" class="pm-input"
                           placeholder="Obligatoria para guardar" autocomplete="current-password">
                    @error('current_password')<div style="color:#EF4444;font-size:0.78rem;margin-top:0.25rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
                <div class="pm-edit-field">
                    <label class="pm-edit-label"><i class="bi bi-lock-fill"></i> Nueva contraseña <small style="color:#6B7280;text-transform:none;">(opcional)</small></label>
                    <input type="password" name="new_password" class="pm-input"
                           placeholder="Mínimo 8 caracteres" autocomplete="new-password">
                    @error('new_password')<div style="color:#EF4444;font-size:0.78rem;margin-top:0.25rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                </div>
                <div class="pm-edit-field">
                    <label class="pm-edit-label"><i class="bi bi-lock-fill"></i> Confirmar nueva contraseña</label>
                    <input type="password" name="new_password_confirmation" class="pm-input"
                           placeholder="Repite la nueva contraseña" autocomplete="new-password">
                </div>
                <div class="pm-edit-actions">
                    <button type="submit" class="pm-save-btn"><i class="bi bi-check-lg"></i> Guardar cambios</button>
                    <button type="button" class="pm-discard-btn" onclick="toggleEditProfile()">Cancelar</button>
                </div>
            </form>
        </div>

        <button class="pm-close" onclick="closeProfileModal()"><i class="bi bi-x-lg"></i> Cerrar</button>
    </div>
</div>

<script>
function openProfileModal()  { document.getElementById('profileModal').classList.add('active'); }
function closeProfileModal() { document.getElementById('profileModal').classList.remove('active'); }
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeProfileModal(); });

function toggleEditProfile() {
    const sec = document.getElementById('editProfileSection');
    const btn = document.getElementById('editToggleBtn');
    const visible = sec.style.display !== 'none';
    sec.style.display = visible ? 'none' : 'block';
    btn.innerHTML = visible
        ? '<i class="bi bi-pencil-square"></i> Editar email o contraseña'
        : '<i class="bi bi-x-lg"></i> Cancelar edición';
}

// ── Flash messages via SweetAlert2 ──
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
    Swal.fire({
        icon: 'success', title: '¡Éxito!', text: @json(session('success')),
        timer: 3500, showConfirmButton: false,
        toast: true, position: 'top-end',
        background: 'rgba(15,23,42,0.97)', color: '#fff', iconColor: '#10B981'
    });
    @endif
    @if(session('error'))
    Swal.fire({
        icon: 'error', title: 'Error', text: @json(session('error')),
        confirmButtonColor: '#7C3AED',
        background: 'rgba(15,23,42,0.97)', color: '#fff'
    });
    @endif
    @if($errors->isNotEmpty())
    {{-- Errores de validación del formulario de perfil: reabrir el panel --}}
    toggleEditProfile();
    openProfileModal();
    Swal.fire({
        icon: 'error', title: 'Error al guardar',
        text: @json($errors->first()),
        confirmButtonColor: '#7C3AED',
        background: 'rgba(15,23,42,0.97)', color: '#fff'
    });
    @endif
});
</script>
