<?php
// verify-vendor.php - Verifica que vendor/ está completo
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre style='font-family: monospace; background: #0f0f1a; color: #00ff94; padding: 20px;'>\n";
echo "=== 🔍 Verificando vendor/ ===\n\n";

$checks = [
    'vendor/autoload.php' => 'Autoload principal',
    'vendor/composer/autoload_real.php' => 'Composer autoloader',
    'vendor/laravel/framework/src/Illuminate/Foundation/Application.php' => 'Laravel core',
    'vendor/myclabs/deep-copy/src/DeepCopy/deep_copy.php' => 'DeepCopy (crítico)',
    'vendor/symfony/http-foundation/Request.php' => 'Symfony HTTP',
    'vendor/vlucas/phpdotenv/src/Dotenv.php' => 'PHP-Dotenv',
    'vendor/inertiajs/inertia-laravel/src/Middleware.php' => 'Inertia (Vue)',
];

$allOk = true;
foreach ($checks as $file => $desc) {
    $path = __DIR__ . '/../' . $file;
    if (file_exists($path)) {
        $size = round(filesize($path) / 1024, 1);
        echo "✅ $desc\n   └─ $file ($size KB)\n";
    } else {
        echo "❌ $desc\n   └─ $file (FALTA)\n";
        $allOk = false;
    }
    echo "\n";
}

if ($allOk) {
    echo "🎉 ¡vendor/ parece completo!\n\n";
    echo "👉 Ahora prueba cargar la app:\n";
    echo "https://ieslamarisma.net/proyectos/2026/joseangelaquino/laravel/gym-reservas/public/\n";
} else {
    echo "⚠️ Faltan archivos críticos. vendor/ está incompleto.\n";
    echo "\n💡 Solución: Vuelve a subir vendor/ usando el método ZIP.\n";
}

echo "\n</pre>";