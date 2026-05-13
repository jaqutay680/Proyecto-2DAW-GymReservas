<?php
// gym-reservas/diag.php (BORRAR DESPUÉS DE PROBAR)
echo "<pre style='background:#0a0a0f;color:#0f0;padding:20px;font-family:monospace;font-size:13px;'>";
echo "🔍 Diagnóstico de despliegue - GymReservas\n\n";

// 1. Verificar estructura de carpetas
$laravelPath = __DIR__ . '/../laravel';
echo "📁 Laravel path: $laravelPath\n";
echo "✅ Existe: " . (file_exists($laravelPath) ? 'SÍ' : 'NO') . "\n";
echo "✅ autoload.php: " . (file_exists($laravelPath . '/vendor/autoload.php') ? 'SÍ' : 'NO') . "\n";
echo "✅ .env: " . (file_exists($laravelPath . '/.env') ? 'SÍ' : 'NO') . "\n\n";

// 2. Verificar APP_URL
if (file_exists($laravelPath . '/.env')) {
    $env = file_get_contents($laravelPath . '/.env');
    if (preg_match('/^APP_URL=(.+)/m', $env, $m)) {
        echo "🔗 APP_URL: " . trim($m[1]) . "\n\n";
    }
}

// 3. Verificar permisos de escritura
$writable = [
    'storage' => is_writable($laravelPath . '/storage'),
    'cache' => is_writable($laravelPath . '/bootstrap/cache'),
];
echo "🔐 Permisos de escritura:\n";
foreach ($writable as $dir => $ok) {
    echo "   $dir: " . ($ok ? '✅ OK' : '❌ NO ESCRIBIBLE') . "\n";
}

echo "\n✅ Si ves esto, la estructura básica está accesible.\n";
echo "🔗 <a href='/proyectos/2026/joseangelaquino/gym-reservas/' style='color:#60A5FA;text-decoration:none'>← Volver al inicio</a>\n";
echo "</pre>";