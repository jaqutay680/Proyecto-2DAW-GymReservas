<?php
// extract-vendor.php - Extrae vendor.zip y se autoelimina
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre style='font-family: monospace; background: #1e1e1e; color: #00ff00; padding: 20px;'>\n";
echo "=== 🚀 Extrayendo vendor/ ===\n\n";

$zipFile = __DIR__ . '/vendor.zip';
$extractPath = __DIR__;

// Verificar ZIP
if (!file_exists($zipFile)) {
    echo "❌ ERROR: vendor.zip no encontrado\n";
    echo "Ruta buscada: $zipFile\n";
    echo "\n💡 Sube vendor.zip a esta carpeta y recarga esta página.\n";
    exit(1);
}

$zipSize = round(filesize($zipFile) / 1024 / 1024, 2);
echo "✅ vendor.zip encontrado ($zipSize MB)\n";

// Verificar ZipArchive
if (!class_exists('ZipArchive')) {
    echo "❌ ERROR: Extensión ZipArchive no disponible\n";
    echo "\n💡 Contacta al administrador para habilitar ZipArchive en PHP 8.3\n";
    exit(1);
}

echo "✅ ZipArchive disponible\n\n";
echo "📦 Extrayendo archivos...\n";

$zip = new ZipArchive;
$res = $zip->open($zipFile);

if ($res === TRUE) {
    $fileCount = $zip->numFiles;
    echo "✅ ZIP abierto ($fileCount archivos)\n";
    
    $extractResult = $zip->extractTo($extractPath);
    $zip->close();
    
    if ($extractResult) {
        echo "✅ vendor/ extraído correctamente\n\n";
        
        // Verificaciones
        $checks = [
            'vendor/autoload.php',
            'vendor/myclabs/deep-copy/src/DeepCopy/deep_copy.php',
            'vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
        ];
        
        echo "🔍 Verificando archivos críticos:\n";
        $allOk = true;
        foreach ($checks as $file) {
            $fullPath = __DIR__ . '/' . $file;
            if (file_exists($fullPath)) {
                echo "  ✅ $file\n";
            } else {
                echo "  ❌ $file (FALTA)\n";
                $allOk = false;
            }
        }
        
        if ($allOk) {
            echo "\n🎉 ¡EXTRACCIÓN EXITOSA!\n\n";
            
            // Autoeliminación
            echo "🧹 Limpiando archivos temporales...\n";
            $deletedZip = unlink($zipFile);
            $deletedScript = @unlink(__FILE__);
            
            if ($deletedZip) echo "  ✅ vendor.zip eliminado\n";
            if ($deletedScript) echo "  ✅ extract-vendor.php eliminado\n";
            
            echo "\n✅ ¡Listo para usar!\n";
            echo "\n👉 Accede a tu app:\n";
            echo "https://ieslamarisma.net/proyectos/2026/joseangelaquino/laravel/gym-reservas/public/\n";
        } else {
            echo "\n⚠️ Algunos archivos faltan. Revisa permisos o espacio en disco.\n";
        }
    } else {
        echo "❌ ERROR al extraer: permisos insuficientes o disco lleno\n";
        echo "\n💡 Contacta al administrador del hosting\n";
    }
} else {
    echo "❌ ERROR al abrir ZIP (código: $res)\n";
    echo "\n💡 El archivo vendor.zip podría estar corrupto. Vuelve a comprimirlo.\n";
}

echo "\n</pre>";