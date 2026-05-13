<?php
// public/test-plan.php (BORRAR DESPUÉS DE PROBAR)
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<pre style='background:#111;color:#0f0;padding:20px;font-family:monospace;'>";
echo "🔍 Test de parámetros de plan\n\n";

// Simular request con parámetro plan
$_GET['plan'] = 'basico';
$request = Illuminate\Http\Request::capture();

echo "✅ Parámetro 'plan' recibido: " . ($request->get('plan') ?? 'NULO') . "\n";

// Verificar vista de registro
try {
    $view = view('auth.register', ['plan' => $request->get('plan')])->render();
    echo "✅ Vista auth.register carga correctamente\n";

    // Buscar si el option está seleccionado
    if (strpos($view, 'value="basico" selected') !== false || strpos($view, "value='basico' selected") !== false) {
        echo "✅ Plan 'basico' aparece como seleccionado en el HTML\n";
    } else {
        echo "❌ Plan 'basico' NO aparece como seleccionado en el HTML\n";
        // Mostrar fragmento del select para diagnóstico
        if (preg_match('/<select[^>]*name="plan_type"[^>]*>.*?<\/select>/s', $view, $matches)) {
            echo "📋 Fragmento del select:\n" . htmlspecialchars(substr($matches[0], 0, 500)) . "...\n";
        }
    }
} catch (Throwable $e) {
    echo "❌ Error al renderizar vista: " . $e->getMessage() . "\n";
    echo "📁 " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n</pre>";