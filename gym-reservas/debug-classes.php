<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Schedule;

echo "<pre style='background:#0a0a0f; color:#00ff94; padding:20px; font-family:monospace;'>\n";
echo "=== 🔍 Debug de Clases ===\n\n";

// Día actual
$englishDays = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
$today = strtolower($englishDays[now()->dayOfWeek]);
$tomorrow = strtolower($englishDays[now()->addDay()->dayOfWeek]);

echo "📅 Hoy (servidor): " . now()->format('l') . " ($today)\n";
echo "📅 Mañana: " . now()->addDay()->format('l') . " ($tomorrow)\n\n";

// Verificar modelo
if (!class_exists('App\Models\Schedule')) {
    echo "❌ ERROR: El modelo App\Models\Schedule NO existe\n";
    echo "💡 Crea el archivo app/Models/Schedule.php\n";
    exit;
}
echo "✅ Modelo Schedule existe\n\n";

// Verificar tabla
try {
    $count = \DB::table('gym_schedules')->count();
    echo "✅ Tabla gym_schedules existe ($count registros)\n\n";
    
    // Mostrar días existentes en la BD
    $daysInDb = \DB::table('gym_schedules')->distinct()->pluck('day_of_week');
    echo "📋 Días en la BD: [" . implode(', ', $daysInDb->toArray()) . "]\n\n";
    
    // Consulta real
    $results = \App\Models\Schedule::with(['activity'])
        ->whereIn('day_of_week', [$today, $tomorrow])
        ->orderBy('start_time')
        ->get();
    
    echo "🔍 Resultados para [$today, $tomorrow]: {$results->count()} clases\n";
    foreach ($results as $r) {
        echo "  • {$r->activity->name ?? 'N/A'} - {$r->day_of_week} {$r->start_time}-{$r->end_time} ({$r->room})\n";
    }
    
} catch (\Throwable $e) {
    echo "❌ ERROR en consulta: " . $e->getMessage() . "\n";
    echo "   File: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n</pre>";