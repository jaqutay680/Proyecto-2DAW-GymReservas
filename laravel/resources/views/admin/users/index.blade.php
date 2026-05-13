@extends('admin.layouts.app')

@section('page-title', 'Usuarios')

@section('content')

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
        <div class="card-title" style="margin-bottom:0;"><i class="bi bi-people-fill"></i> Gestión de Usuarios</div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table class="table" id="usersTable" style="width:100%">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>DNI</th>
                    <th>Plan</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td style="color:var(--text-secondary);">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'badge-warning' : ($user->role === 'entrenador' ? 'badge-info' : 'badge-secondary') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td><code style="font-size:0.82rem;background:var(--bg-hover);padding:0.2rem 0.4rem;border-radius:0.3rem;">{{ $user->dni ?? 'N/A' }}</code></td>
                    <td>
                        <span class="badge {{ $user->plan_type === 'premium' ? 'badge-info' : ($user->plan_type === 'basico' ? 'badge-success' : 'badge-secondary') }}">
                            {{ ucfirst($user->plan_type ?? 'free') }}
                        </span>
                    </td>
                    <td>
                        @if($user->membership_status === 'active')
                            <span class="badge badge-success">Activo</span>
                        @elseif($user->membership_status === 'suspended')
                            <span class="badge badge-danger">Suspendido</span>
                        @elseif($user->membership_status === 'expired')
                            <span class="badge badge-warning">Expirado</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($user->membership_status ?? '-') }}</span>
                        @endif
                    </td>
                    <td style="color:var(--text-secondary);font-size:0.85rem;" data-order="{{ $user->created_at }}">
                        {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-secondary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('admin.users.payments', $user->id) }}" class="btn btn-sm btn-secondary" title="Pagos"><i class="bi bi-credit-card"></i></a>
                            <a href="{{ route('admin.users.audit', $user->id) }}" class="btn btn-sm btn-secondary" title="Auditoría"><i class="bi bi-file-text"></i></a>
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
    $('#usersTable').DataTable({
        columnDefs: [{ orderable: false, targets: 7 }],
        order: [[6, 'desc']]
    });
});
</script>
@endpush

@endsection
