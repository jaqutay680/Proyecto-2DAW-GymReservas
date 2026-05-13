@extends('admin.layouts.app')

@section('page-title', 'Suscripciones')

@section('content')

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div class="card-title" style="margin-bottom:0;">
            <i class="bi bi-star-fill"></i> Gestión de Suscripciones
        </div>
        <form method="POST" action="{{ route('admin.subscriptions.renew') }}" id="renewSubsForm">
            @csrf
            <button type="button" class="btn btn-success"
                onclick="adminConfirm('🔄','Renovar suscripciones','Se cobrarán todas las suscripciones con fecha de renovación vencida.',document.getElementById('renewSubsForm'),'modal-btn-confirm','Renovar')">
                <i class="bi bi-arrow-repeat"></i> Renovar vencidas
            </button>
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table class="table" id="subsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Plan</th>
                    <th>Estado</th>
                    <th>Próximo cobro</th>
                    <th>¿Cobrar?</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions ?? [] as $s)
                @php $dueToday = isset($s->next_billing_date) && strtotime($s->next_billing_date) <= time(); @endphp
                <tr>
                    <td><strong>{{ $s->name ?? 'N/A' }}</strong></td>
                    <td style="color:var(--text-secondary);">{{ $s->email ?? '-' }}</td>
                    <td>
                        <span class="badge badge-info">{{ ucfirst($s->plan_type ?? '-') }}</span>
                    </td>
                    <td>
                        <span class="badge {{ ($s->status ?? '') === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ ($s->status ?? '') === 'active' ? 'Activo' : 'Cancelado' }}
                        </span>
                    </td>
                    <td style="color:var(--text-secondary);">
                        {{ isset($s->next_billing_date) ? date('d/m/Y', strtotime($s->next_billing_date)) : '-' }}
                    </td>
                    <td>
                        @if($dueToday)
                            <span class="badge badge-warning"><i class="bi bi-exclamation-circle"></i> Sí</span>
                        @else
                            <span style="color:var(--text-secondary);">No</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:2.5rem;color:var(--text-secondary);">
                        <i class="bi bi-inbox" style="font-size:2.5rem;opacity:0.4;display:block;margin-bottom:0.75rem;"></i>
                        No hay suscripciones registradas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@push('scripts')
<script>$(document).ready(function(){ $('#subsTable').DataTable({ columnDefs:[{orderable:false,targets:5}] }); });</script>
@endpush

@endsection
