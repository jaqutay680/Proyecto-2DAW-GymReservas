@extends('admin.layouts.app')

@section('page-title', 'Editar Actividad')

@section('content')

<div class="card" style="max-width:700px;">
    <div class="card-title"><i class="bi bi-pencil"></i> Editar: {{ $activity->name }}</div>

    <form method="POST" action="{{ route('admin.activities.update', $activity->id) }}">
        @csrf @method('PATCH')

        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $activity->name) }}" required>
            @error('name')<span style="color:var(--danger);font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="description" class="form-control">{{ old('description', $activity->description) }}</textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="form-group">
                <label>Dificultad</label>
                <select name="difficulty" class="form-control">
                    <option value="beginner" {{ old('difficulty', $activity->difficulty) == 'beginner' ? 'selected' : '' }}>Principiante</option>
                    <option value="intermediate" {{ old('difficulty', $activity->difficulty) == 'intermediate' ? 'selected' : '' }}>Intermedio</option>
                    <option value="advanced" {{ old('difficulty', $activity->difficulty) == 'advanced' ? 'selected' : '' }}>Avanzado</option>
                </select>
            </div>
            <div class="form-group">
                <label>Edad mínima</label>
                <input type="number" name="min_age" class="form-control" value="{{ old('min_age', $activity->min_age) }}" min="0" max="99">
            </div>
        </div>

        <div style="display:flex; gap:1rem; margin-top:1rem;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Cambios</button>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </form>
</div>

@endsection
