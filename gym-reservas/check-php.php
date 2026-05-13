<?php
// check-php.php - Diagnóstico de entorno PHP
$required = ['openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'curl', 'json', 'fileinfo', 'pdo_mysql'];
$missing = [];

foreach ($required as $ext) {
    if (!extension_loaded($ext)) {
        $missing[] = $ext;
    }
}

header('Content-Type: text/plain');
echo "=== Diagnóstico PHP ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "\nExtensiones requeridas:\n";
foreach ($required as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "  $status $ext\n";
}
if (!empty($missing)) {
    echo "\n❌ Faltan: " . implode(', ', $missing) . "\n";
    exit(1);
}
echo "\n✅ Todas las extensiones están cargadas\n";

// Probar conexión a BD básica
echo "\n=== Prueba de conexión MySQL ===\n";
$host = 'localhost';
$db = 'joseangelaquino';
$user = 'joseangelaquino';
$pass = '%W8yyp13vtn*NmOs';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a MySQL exitosa\n";
    echo "Server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}