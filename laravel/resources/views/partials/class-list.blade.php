@php
use Carbon\Carbon;
$dayOffsets  = ['monday'=>0,'tuesday'=>1,'wednesday'=>2,'thursday'=>3,'friday'=>4,'saturday'=>5,'sunday'=>6];
// Always use the day that was actually requested, not the schedule's stored day_of_week.
// This prevents wrong DB entries (e.g. thursday in a wednesday filter) from breaking the display.
$_filterDay  = $selectedDay ?? null;   // passed from dashboard / AJAX endpoint
$_startOfWeek = Carbon::now()->startOfWeek();
@endphp

@forelse($schedules ?? [] as $schedule)
@php
    $reserved     = $schedule->reserved_count ?? 0;
    $userReserved = ($schedule->user_reserved ?? 0) > 0;
    $available    = $schedule->capacity - $reserved;
    $isFull       = $available <= 0;
    $isSoon       = !$isFull && $available <= 3;

    // Use $selectedDay if available, otherwise fall back to the schedule's own day_of_week
    $dayKey        = $_filterDay ?? $schedule->day_of_week;
    $offset        = $dayOffsets[$dayKey] ?? ($dayOffsets[$schedule->day_of_week] ?? 0);
    $ct            = Carbon::createFromTimeString($schedule->start_time);
    $classDateTime = $_startOfWeek->copy()->addDays($offset)
                        ->setHour($ct->hour)->setMinute($ct->minute)->setSecond(0);
    $isPast        = $classDateTime->isPast();
    $minsLeft      = Carbon::now()->diffInMinutes($classDateTime, false); // positive = future
    $isAlmostTime  = !$isPast && $minsLeft <= 30;

    $isDanger  = $isPast || $isFull;
    $isWarning = !$isDanger && ($isAlmostTime || $isSoon);

    // Verde tiene máxima prioridad: el usuario ya tiene reserva confirmada
    $cardClass = $userReserved ? 'card-success' : ($isDanger ? 'card-danger' : ($isWarning ? 'card-warning' : ''));
@endphp

<div class="activity-card {{ $cardClass }}">
    <div class="activity-info">
        <h3>{{ $schedule->activity_name ?? 'Clase' }}</h3>
        <p>
            {{ Carbon::parse($schedule->start_time)->format('H:i') }} –
            {{ Carbon::parse($schedule->end_time)->format('H:i') }}
            &nbsp;•&nbsp; {{ $schedule->room }}
            &nbsp;•&nbsp;
            @if($isFull)
                <span style="color:var(--error);font-weight:600;">Completa ({{ $reserved }}/{{ $schedule->capacity }})</span>
            @elseif($isSoon)
                <span style="color:var(--warning);font-weight:600;">Quedan {{ $available }} plaza{{ $available == 1 ? '' : 's' }}</span>
            @else
                <span style="color:var(--success);">{{ $available }} libres de {{ $schedule->capacity }}</span>
            @endif
        </p>
        @if($userReserved)
            <p style="font-size:0.8rem;color:#10B981;margin-top:0.25rem;font-weight:600;"><i class="bi bi-check-circle-fill"></i> Tu reserva está confirmada</p>
        @elseif($isPast)
            <p style="font-size:0.8rem;color:var(--error);margin-top:0.25rem;"><i class="bi bi-clock-history"></i> Clase finalizada esta semana</p>
        @elseif($isAlmostTime)
            <p style="font-size:0.8rem;color:var(--warning);margin-top:0.25rem;"><i class="bi bi-hourglass-split"></i> Empieza en {{ round($minsLeft) }} min</p>
        @endif
    </div>

    @if($userReserved)
        <span class="btn-reserved">
            <i class="bi bi-check-circle-fill"></i> Reservado
        </span>
    @elseif($isDanger)
        <span style="padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.85rem;font-weight:600;
              background:rgba(239,68,68,0.15);color:#F87171;border:1px solid rgba(239,68,68,0.25);">
            <i class="bi bi-{{ $isPast ? 'clock-history' : 'x-circle' }}"></i>
            {{ $isPast ? 'Finalizada' : 'Completa' }}
        </span>
    @else
        <button type="button" class="btn-reserve"
            onclick="openReserveModal({{ $schedule->id }}, '{{ addslashes($schedule->activity_name) }}', '{{ Carbon::parse($schedule->start_time)->format('H:i') }}')">
            <i class="bi bi-calendar-plus"></i> Reservar
        </button>
    @endif
</div>

@empty
<div class="activity-card" style="justify-content:center;text-align:center;color:var(--text-secondary);padding:2rem;">
    <i class="bi bi-calendar-x" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.4;"></i>
    <p>No hay clases para este día.</p>
</div>
@endforelse
