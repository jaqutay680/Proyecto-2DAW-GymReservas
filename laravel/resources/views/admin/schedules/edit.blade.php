@extends('admin.layouts.app')

@section('page-title', 'Editar Horario')

@section('content')

<div class="card" style="max-width:700px;">
    <div class="card-title"><i class="bi bi-pencil"></i> Editar Horario</div>

    <form method="POST" action="{{ route('admin.schedules.update', $schedule->id) }}">
        @csrf @method('PATCH')

        <div class="form-group">
            <label>Actividad *</label>
            <select name="activity_id" class="form-control" required>
                @foreach($activities as $act)
                    <option value="{{ $act->id }}" {{ old('activity_id', $schedule->activity_id) == $act->id ? 'selected' : '' }}>{{ $act->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Día de la semana *</label>
            <select name="day_of_week" class="form-control" required>
                @foreach(['monday'=>'Lunes','tuesday'=>'Martes','wednesday'=>'Miércoles','thursday'=>'Jueves','friday'=>'Viernes','saturday'=>'Sábado','sunday'=>'Domingo'] as $key => $label)
                    <option value="{{ $key }}" {{ old('day_of_week', $schedule->day_of_week) == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="form-group">
                <label>Hora inicio *</label>
                <input type="time" name="start_time" class="form-control" value="{{ old('start_time', substr($schedule->start_time,0,5)) }}" required>
            </div>
            <div class="form-group">
                <label>Hora fin *</label>
                <input type="time" name="end_time" class="form-control" value="{{ old('end_time', substr($schedule->end_time,0,5)) }}" required>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="form-group">
                <label>Sala *</label>
                <input type="text" name="room" class="form-control" value="{{ old('room', $schedule->room) }}" required>
            </div>
            <div class="form-group">
                <label>Aforo máximo *</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $schedule->capacity) }}" min="1" max="200" required>
            </div>
        </div>

        <div style="display:flex; gap:1rem; margin-top:1rem;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Cambios</button>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </form>
</div>

@endsection
