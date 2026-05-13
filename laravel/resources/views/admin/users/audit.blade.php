@extends('admin.layouts.app')

@section('page-title', 'Auditoría de ' . $user->name)

@section('content')

<div style="margin-bottom:1.5rem;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="card">
    <div class="card-title"><i class="bi bi-clock-history"></i> Historial de cambios — {{ $user->name }}</div>
    <div style="overflow-x:auto;">
        <table class="table" id="auditTable" style="width:100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Acción</th>
                    <th>Administrador</th>
                    <th>Cambios</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditLogs as $log)
                <tr>
                    <td style="font-size:0.85rem; color:var(--text-secondary);">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $log->action_type)) }}</td>
                    <td>
                        @php $admin = \DB::table('gym_users')->find($log->user_id); @endphp
                        {{ $admin->name ?? 'Sistema' }}
                    </td>
                    <td>
                        @if($log->new_values)
                        @php
                            $newValues = json_decode($log->new_values, true) ?? [];
                            $oldValues = json_decode($log->old_values, true) ?? [];
                        @endphp
                        <small style="color:var(--text-secondary);">
                            @foreach($newValues as $key => $newVal)
                            <div><strong>{{ $key }}:</strong> {{ $oldValues[$key] ?? '-' }} → {{ $newVal }}</div>
                            @endforeach
                        </small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text-secondary);">Sin registros de auditoría.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@push('scripts')
<script>$(document).ready(function(){ $('#auditTable').DataTable({ order:[[0,'desc']], columnDefs:[{orderable:false,targets:3}] }); });</script>
@endpush

@endsection
