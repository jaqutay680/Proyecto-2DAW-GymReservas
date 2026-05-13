<?php
// 🔹 Solo mostrar errores si APP_DEBUG=true en .env
if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
while (ob_get_level())
    ob_end_clean();

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    // Mantenimiento
    if (file_exists($maintenance = __DIR__ . '/../laravel/storage/framework/maintenance.php')) {
        require $maintenance;
    }

    // Autoload
    require __DIR__ . '/../laravel/vendor/autoload.php';

    // Bootstrap
    $app = require_once __DIR__ . '/../laravel/bootstrap/app.php';

    // Handle request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);

} catch (Throwable $e) {
    $debug = getenv('APP_DEBUG') === 'true';
    if ($debug) {
        echo "<pre style='background:#0a0a0a;color:#0f0;padding:20px;font-family:monospace;font-size:13px;'>";
        echo "🚨 ERROR: " . htmlspecialchars($e->getMessage()) . "\n";
        echo "📁 " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "\n";
        echo "\n" . htmlspecialchars($e->getTraceAsString());
        echo "</pre>";
    } else {
        if (!headers_sent()) http_response_code(500);
        echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Error</title>
        <style>body{font-family:system-ui;background:#0a0a0f;color:#fff;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
        .card{background:rgba(23,31,47,0.9);padding:2rem;border-radius:1rem;text-align:center;max-width:400px}
        h1{color:#EF4444}</style></head><body><div class='card'>
        <h1>⚠️ Error</h1><p>Inténtalo más tarde.</p>
        <a href='/proyectos/2026/joseangelaquino/gym-reservas/' style='display:inline-block;margin-top:1rem;padding:0.5rem 1rem;background:#7C3AED;color:#fff;text-decoration:none;border-radius:0.5rem'>Volver</a>
        </div></body></html>";
    }
    exit;
}