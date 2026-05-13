<?php
// test-permisos.php
$logFile = __DIR__ . '/storage/logs/laravel.log';

echo "<pre>\n=== Test de permisos ===\n";
echo "Ruta del log: $logFile\n\n";

if (!file_exists($logFile)) {
    echo "❌ El archivo laravel.log NO existe\n";
    echo "💡 Crea un archivo vacío llamado 'laravel.log' en storage/logs/\n";
    exit(1);
}

echo "✅ El archivo existe\n";
echo "Permisos actuales: " . substr(sprintf('%o', fileperms($logFile)), -4) . "\n";

// Intentar escribir
$testMessage = "[" . date('Y-m-d H:i:s') . "] Test de escritura exitoso\n";
$result = file_put_contents($logFile, $testMessage, FILE_APPEND | LOCK_EX);

if ($result !== false) {
    echo "✅ Escritura exitosa en laravel.log\n";
    echo "\n🎉 ¡Los permisos están bien configurados!\n";
    echo "Ahora prueba cargar la app:\n";
    echo "https://ieslamarisma.net/proyectos/2026/joseangelaquino/laravel/gym-reservas/public/\n";
} else {
    echo "❌ ERROR: No se puede escribir en laravel.log\n";
    echo "\n💡 Solución: Cambia permisos de storage/ a 775 o 777 (recursivo)\n";
}