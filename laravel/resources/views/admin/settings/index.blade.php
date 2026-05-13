@extends('admin.layouts.app')

@section('page-title', 'Configuración')

@section('content')

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PATCH')

    <!-- Información del gimnasio -->
    <div class="card">
        <div class="card-title"><i class="bi bi-building"></i> Información del Gimnasio</div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1rem;">
            <div class="form-group">
                <label>Nombre de la aplicación</label>
                <input type="text" name="settings[app_name]" class="form-control" value="{{ $settings['app_name']->value ?? 'GymReservas' }}">
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="settings[gym_phone]" class="form-control" value="{{ $settings['gym_phone']->value ?? '' }}">
            </div>
            <div class="form-group">
                <label>Email de contacto</label>
                <input type="email" name="settings[gym_email]" class="form-control" value="{{ $settings['gym_email']->value ?? '' }}">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="settings[gym_address]" class="form-control" value="{{ $settings['gym_address']->value ?? '' }}">
            </div>
            <div class="form-group">
                <label>Horario de apertura</label>
                <input type="text" name="settings[gym_hours]" class="form-control" value="{{ $settings['gym_hours']->value ?? '6:00 - 23:00' }}" placeholder="6:00 - 23:00">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <input type="text" name="settings[app_description]" class="form-control" value="{{ $settings['app_description']->value ?? '' }}">
            </div>
        </div>
    </div>

    <!-- Precios y límites -->
    <div class="card">
        <div class="card-title"><i class="bi bi-currency-euro"></i> Precios y Límites</div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:1rem;">
            <div class="form-group">
                <label>Precio plan Básico (€/mes)</label>
                <input type="number" step="0.01" name="settings[basico_price]" class="form-control" value="{{ $settings['basico_price']->value ?? '9.99' }}">
            </div>
            <div class="form-group">
                <label>Precio plan Premium (€/mes)</label>
                <input type="number" step="0.01" name="settings[premium_price]" class="form-control" value="{{ $settings['premium_price']->value ?? '19.99' }}">
            </div>
            <div class="form-group">
                <label>Reservas máximas (plan Free)</label>
                <input type="number" name="settings[max_free_trial_reservations]" class="form-control" value="{{ $settings['max_free_trial_reservations']->value ?? '1' }}">
            </div>
            <div class="form-group">
                <label>Reservas/semana (plan Básico)</label>
                <input type="number" name="settings[max_basico_reservations_week]" class="form-control" value="{{ $settings['max_basico_reservations_week']->value ?? '5' }}">
            </div>
            <div class="form-group">
                <label>Edad mínima de registro</label>
                <input type="number" name="settings[min_user_age]" class="form-control" value="{{ $settings['min_user_age']->value ?? '16' }}">
            </div>
        </div>
    </div>

    <div style="display:flex; gap:1rem;">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Configuración</button>
    </div>
</form>

@endsection
