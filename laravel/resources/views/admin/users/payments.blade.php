@extends('admin.layouts.app')

@section('page-title', 'Pagos de ' . $user->name)

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>
</div>

<div class="card">
    <div class="card-title"><i class="bi bi-person"></i> {{ $user->name }}</div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; font-size:0.9rem;">
        <div><span style="color:var(--text-secondary);">Email</span><br><strong>{{ $user->email }}</strong></div>
        <div><span style="color:var(--text-secondary);">Plan</span><br><strong>{{ ucfirst($user->plan_type ?? 'free') }}</strong></div>
        <div><span style="color:var(--text-secondary);">Estado</span><br><strong>{{ ucfirst($user->membership_status ?? 'active') }}</strong></div>
        <div><span style="color:var(--text-secondary);">DNI</span><br><strong>{{ $user->dni ?? 'N/A' }}</strong></div>
    </div>
</div>

<div class="card">
    <div class="card-title"><i class="bi bi-credit-card"></i> Historial de Pagos</div>
    <div style="overflow-x:auto;">
        <table class="table" id="userPaymentsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Plan</th>
                    <th>Importe</th>
                    <th>Estado</th>
                    <th>Próximo cobro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($p->payment_date)) }}</td>
                    <td>{{ ucfirst($p->plan_type) }}</td>
                    <td>{{ number_format($p->amount, 2) }}€</td>
                    <td>
                        <span class="badge {{ $p->status === 'paid' ? 'badge-success' : ($p->status === 'cancelled' ? 'badge-danger' : 'badge-warning') }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td style="color:var(--text-secondary);">{{ $p->next_billing_date ? date('d/m/Y', strtotime($p->next_billing_date)) : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-secondary);">Sin pagos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@push('scripts')
<script>$(document).ready(function(){ $('#userPaymentsTable').DataTable({ order:[[0,'desc']] }); });</script>
@endpush

@endsection
