@extends('admin.layouts.app')

@section('page-title', 'Nuevo Usuario')

@section('content')

<div class="card" style="max-width:700px;">
    <div class="card-title"><i class="bi bi-person-plus"></i> Crear Nuevo Usuario</div>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            {{-- NOMBRE --}}
            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="name" class="form-control" value="{{ $input['name'] ?? '' }}">
                @error('name')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            {{-- EMAIL --}}
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $input['email'] ?? '' }}">
                @error('email')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            {{-- CONTRASEÑA --}}
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" autocomplete="new-password">
                @error('password')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            {{-- DNI --}}
            <div class="form-group">
                <label>DNI <small style="color:var(--text-secondary);font-weight:400;">(8 dígitos + letra, ej: 12345678Z)</small></label>
                <input type="text" name="dni" class="form-control" value="{{ $input['dni'] ?? '' }}" placeholder="Ej: 12345678Z">
                @error('dni')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            {{-- FECHA DE NACIMIENTO --}}
            <div class="form-group">
                <label>Fecha de nacimiento</label>
                <input type="date" name="birth_date" class="form-control" value="{{ $input['birth_date'] ?? '' }}">
                @error('birth_date')<small style="color:#EF4444;display:block;margin-top:0.3rem;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</small>@enderror
            </div>

            {{-- PLAN --}}
            <div class="form-group">
                <label>Plan</label>
                <select name="plan_type" class="form-control">
                    <option value="free"    @if(($input['plan_type'] ?? 'free') === 'free')    selected @endif>Free</option>
                    <option value="basico"  @if(($input['plan_type'] ?? '') === 'basico')  selected @endif>Básico</option>
                    <option value="premium" @if(($input['plan_type'] ?? '') === 'premium') selected @endif>Premium</option>
                </select>
            </div>

            {{-- ROL --}}
            <div class="form-group">
                <label>Rol</label>
                <select name="role" class="form-control">
                    <option value="cliente" @if(($input['role'] ?? 'cliente') === 'cliente') selected @endif>Cliente</option>
                    <option value="admin"   @if(($input['role'] ?? '') === 'admin')          selected @endif>Administrador</option>
                </select>
            </div>

            {{-- ESTADO MEMBRESÍA --}}
            <div class="form-group">
                <label>Estado membresía</label>
                <select name="membership_status" class="form-control">
                    <option value="active"    @if(($input['membership_status'] ?? 'active') === 'active')    selected @endif>Activo</option>
                    <option value="pending"   @if(($input['membership_status'] ?? '') === 'pending')   selected @endif>Pendiente</option>
                    <option value="suspended" @if(($input['membership_status'] ?? '') === 'suspended') selected @endif>Suspendido</option>
                    <option value="expired"   @if(($input['membership_status'] ?? '') === 'expired')   selected @endif>Expirado</option>
                </select>
            </div>
        </div>

        <div style="display:flex; gap:1rem; margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Crear Usuario</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </form>
</div>

@endsection
