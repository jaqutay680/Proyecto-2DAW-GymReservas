@extends('admin.layouts.app')

@section('page-title', 'Pagos')

@section('content')

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div class="card-title" style="margin-bottom:0;">
            <i class="bi bi-credit-card"></i> Historial de Pagos
        </div>
        <form method="POST" action="{{ route('admin.payments.generate') }}" id="genPayForm">
            @csrf
            <button type="button" class="btn btn-success"
                onclick="adminConfirm('💳','Generar pagos del mes','¿Generar los pagos pendientes para este mes? Esta acción no se puede deshacer.',document.getElementById('genPayForm'),'modal-btn-confirm','Generar')">
                <i class="bi bi-lightning-charge"></i> Generar mes actual
            </button>
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table class="table" id="paymentsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Plan</th>
                    <th>Importe</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments ?? [] as $p)
                <tr>
                    <td style="color:var(--text-secondary);font-size:0.9rem;">
                        {{ isset($p->payment_date) ? date('d/m/Y', strtotime($p->payment_date)) : '-' }}
                    </td>
                    <td><strong>{{ $p->name ?? 'N/A' }}</strong></td>
                    <td style="color:var(--text-secondary);">{{ $p->email ?? '-' }}</td>
                    <td>
                        <span class="badge badge-info">{{ ucfirst($p->plan_type ?? '-') }}</span>
                    </td>
                    <td><strong>{{ isset($p->amount) ? number_format($p->amount, 2).' €' : '0.00 €' }}</strong></td>
                    <td>
                        @php $st = $p->status ?? 'pending'; @endphp
                        <span class="badge {{ $st === 'paid' ? 'badge-success' : ($st === 'cancelled' ? 'badge-danger' : 'badge-warning') }}">
                            {{ $st === 'paid' ? 'Pagado' : ($st === 'cancelled' ? 'Cancelado' : 'Pendiente') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@push('scripts')
<script>$(document).ready(function(){
    $('#paymentsTable').DataTable({
        order:[[0,'desc']],
        language:{ emptyTable:'No hay pagos registrados aún', zeroRecords:'No se encontraron pagos' }
    });
});</script>
@endpush

@endsection
