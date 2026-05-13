@extends('admin.layouts.app')

@section('page-title', 'Horarios')

@section('content')

@php
$dayNames = ['monday'=>'Lunes','tuesday'=>'Martes','wednesday'=>'Miércoles','thursday'=>'Jueves','friday'=>'Viernes','saturday'=>'Sábado','sunday'=>'Domingo'];
@endphp

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div class="card-title" style="margin-bottom:0;"><i class="bi bi-calendar-check"></i> Gestión de Horarios</div>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Horario
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table class="table" id="schedulesTable" style="width:100%">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora</th>
                    <th>Actividad</th>
                    <th>Sala</th>
                    <th>Aforo</th>
                    <th>Reservas (semana)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $s)
                @php
                    $reserved  = $s->reserved_count ?? 0;
                    $isFull    = $reserved >= $s->capacity;
                    $isSoon    = !$isFull && ($s->capacity - $reserved) <= 3;
                @endphp
                <tr>
                    <td><span class="badge badge-info">{{ $dayNames[$s->day_of_week] ?? $s->day_of_week }}</span></td>
                    <td>{{ substr($s->start_time,0,5) }} – {{ substr($s->end_time,0,5) }}</td>
                    <td><strong>{{ $s->activity_name }}</strong></td>
                    <td>{{ $s->room }}</td>
                    <td>{{ $s->capacity }}</td>
                    <td>
                        <span class="badge {{ $isFull ? 'badge-danger' : ($isSoon ? 'badge-warning' : 'badge-success') }}">
                            {{ $reserved }}/{{ $s->capacity }}
                            @if($isFull) — Completo @elseif($isSoon) — Casi lleno @endif
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('admin.schedules.edit', $s->id) }}" class="btn btn-sm btn-secondary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.schedules.delete', $s->id) }}"
                                onsubmit="return adminConfirm('🗑️','Eliminar horario','Se cancelarán también todas las reservas asociadas a este horario.',this,'modal-btn-danger','Eliminar')">
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
    $('#schedulesTable').DataTable({ columnDefs: [{ orderable: false, targets: 6 }] });
});
</script>
@endpush

@endsection
