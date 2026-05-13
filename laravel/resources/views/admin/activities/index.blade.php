@extends('admin.layouts.app')

@section('page-title', 'Actividades')

@section('content')

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div class="card-title" style="margin-bottom:0;"><i class="bi bi-activity"></i> Gestión de Actividades</div>
        <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Actividad
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table class="table" id="activitiesTable" style="width:100%">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Dificultad</th>
                    <th>Edad mín.</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                <tr>
                    <td><strong>{{ $activity->name }}</strong></td>
                    <td><code style="color:var(--secondary);font-size:0.85rem;">{{ $activity->slug }}</code></td>
                    <td>
                        @if($activity->difficulty === 'beginner')
                            <span class="badge badge-success">Principiante</span>
                        @elseif($activity->difficulty === 'intermediate')
                            <span class="badge badge-warning">Intermedio</span>
                        @else
                            <span class="badge badge-danger">Avanzado</span>
                        @endif
                    </td>
                    <td>{{ $activity->min_age }} años</td>
                    <td style="max-width:200px;color:var(--text-secondary);font-size:0.88rem;">{{ Str::limit($activity->description, 70) }}</td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-sm btn-secondary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.activities.delete', $activity->id) }}"
                                onsubmit="return adminConfirm('🗑️','Eliminar actividad','Se eliminarán también todos sus horarios asociados. Esta acción no se puede deshacer.',this,'modal-btn-danger','Eliminar')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#activitiesTable').DataTable({ columnDefs: [{ orderable: false, targets: 5 }] });
});
</script>
@endpush

@endsection
