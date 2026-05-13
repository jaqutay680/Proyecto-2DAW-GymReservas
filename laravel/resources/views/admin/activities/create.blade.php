@extends('admin.layouts.app')

@section('page-title', 'Nueva Actividad')

@section('content')

<div class="card" style="max-width:700px;">
    <div class="card-title"><i class="bi bi-plus-circle"></i> Nueva Actividad</div>

    <form method="POST" action="{{ route('admin.activities.store') }}">
        @csrf

        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="CrossFit Intenso">
            @error('name')<span style="color:var(--danger);font-size:0.85rem;">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="description" class="form-control" placeholder="Descripción de la actividad...">{{ old('description') }}</textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="form-group">
                <label>Dificultad</label>
                <select name="difficulty" class="form-control">
                    <option value="beginner" {{ old('difficulty') == 'beginner' ? 'selected' : '' }}>Principiante</option>
                    <option value="intermediate" {{ old('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermedio</option>
                    <option value="advanced" {{ old('difficulty') == 'advanced' ? 'selected' : '' }}>Avanzado</option>
                </select>
            </div>
            <div class="form-group">
                <label>Edad mínima</label>
                <input type="number" name="min_age" class="form-control" value="{{ old('min_age', 16) }}" min="0" max="99">
            </div>
        </div>

        <div style="display:flex; gap:1rem; margin-top:1rem;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar</button>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </form>
</div>

@endsection
