@extends('admin.layouts.app')

@section('page-title', 'Editar Usuario')

@section('content')

<div class="card">
    <div class="card-title" style="margin-bottom: 1.5rem;">
        <i class="bi bi-pencil"></i> Editar Usuario: {{ $user->name }}
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PATCH')

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <!-- NOMBRE -->
            <div class="form-group">
                <label for="name">Nombre Completo</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
                @error('name')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}">
                @error('email')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            <!-- DNI -->
            <div class="form-group">
                <label for="dni">DNI <small style="color:var(--text-secondary);font-weight:400;">(8 dígitos + letra, ej: 12345678Z)</small></label>
                <input type="text" id="dni" name="dni" class="form-control" value="{{ $user->dni ?? '' }}" placeholder="Ej: 12345678Z">
                @error('dni')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            <!-- FECHA DE NACIMIENTO -->
            <div class="form-group">
                <label for="birth_date">Fecha de Nacimiento</label>
                <input type="date" id="birth_date" name="birth_date" class="form-control" value="{{ $user->birth_date ?? '' }}">
                @error('birth_date')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            <!-- PLAN -->
            <div class="form-group">
                <label for="plan_type">Plan de Suscripción</label>
                <select id="plan_type" name="plan_type" class="form-control">
                    <option value="free" @if($user->plan_type === 'free') selected @endif>Free</option>
                    <option value="basico" @if($user->plan_type === 'basico') selected @endif>Básico</option>
                    <option value="premium" @if($user->plan_type === 'premium') selected @endif>Premium</option>
                </select>
            </div>

            <!-- ESTADO MEMBRESÍA -->
            <div class="form-group">
                <label for="membership_status">Estado de Membresía</label>
                <select id="membership_status" name="membership_status" class="form-control">
                    <option value="active" @if($user->membership_status === 'active') selected @endif>Activo</option>
                    <option value="pending" @if($user->membership_status === 'pending') selected @endif>Pendiente</option>
                    <option value="suspended" @if($user->membership_status === 'suspended') selected @endif>Suspendido</option>
                    <option value="expired" @if($user->membership_status === 'expired') selected @endif>Expirado</option>
                </select>
            </div>

            <!-- ROL -->
            <div class="form-group">
                <label for="role">Rol del Sistema</label>
                <select id="role" name="role" class="form-control">
                    <option value="cliente" @if($user->role === 'cliente') selected @endif>Cliente</option>
                    <option value="admin"   @if($user->role === 'admin')   selected @endif>Administrador</option>
                </select>
            </div>

            <!-- SALDO CARTERA -->
            <div class="form-group">
                <label for="wallet_balance">Saldo de Cartera (€)</label>
                <input type="number" id="wallet_balance" name="wallet_balance" class="form-control" step="0.01" value="{{ $user->wallet_balance ?? 0 }}">
            </div>

            <!-- PRUEBA GRATUITA USADA -->
            <div class="form-group">
                <label for="free_trial_used">Prueba Gratuita</label>
                <select id="free_trial_used" name="free_trial_used" class="form-control">
                    <option value="0" @if(!$user->free_trial_used) selected @endif>No utilizada</option>
                    <option value="1" @if($user->free_trial_used) selected @endif>Utilizada</option>
                </select>
            </div>
        </div>

        <!-- INFORMACIÓN ADICIONAL -->
        <div style="margin-top: 2rem; padding: 1rem; background: rgba(124, 58, 237, 0.05); border-radius: 0.5rem; border-left: 3px solid var(--primary);">
            <h4 style="margin-bottom: 1rem;">📊 Información del Usuario</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; font-size: 0.9rem;">
                <div>
                    <strong>Creado:</strong><br>
                    {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') : '-' }}
                </div>
                <div>
                    <strong>Actualizado:</strong><br>
                    {{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') : '-' }}
                </div>
                <div>
                    <strong>Total Reservas:</strong><br>
                    {{ \DB::table('gym_reservations')->where('user_id', $user->id)->count() }}
                </div>
                <div>
                    <strong>Pagos Realizados:</strong><br>
                    {{ number_format(\DB::table('gym_payments')->where('user_id', $user->id)->sum('amount'), 2) }}€
                </div>
            </div>
        </div>

        <!-- BOTONES DE ACCIÓN -->
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Guardar Cambios
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </form>
</div>

<!-- HISTORIAL DE CAMBIOS -->
@if($auditLogs && count($auditLogs) > 0)
<div class="card" style="margin-top: 2rem;">
    <div class="card-title">
        <i class="bi bi-clock-history"></i> Últimos Cambios
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Acción</th>
                <th>Administrador</th>
                <th>Cambios</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auditLogs as $log)
            <tr>
                <td style="font-size: 0.9rem;">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $log->action_type)) }}</td>
                <td>
                    @php
                    $admin = \DB::table('gym_users')->find($log->user_id);
                    @endphp
                    {{ $admin->name ?? 'Sistema' }}
                </td>
                <td>
                    @if($log->new_values)
                    @php
                    $changes = json_decode($log->new_values, true);
                    $oldValues = json_decode($log->old_values, true);
                    @endphp
                    <small style="color: var(--text-secondary);">
                        @foreach($changes as $key => $newValue)
                        <div><strong>{{ $key }}:</strong> {{ $oldValues[$key] ?? '-' }} → {{ $newValue }}</div>
                        @endforeach
                    </small>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection