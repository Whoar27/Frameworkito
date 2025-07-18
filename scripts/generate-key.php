<?php
// Cargar autoload de Composer y variables de entorno
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Inicializar Dotenv para cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
/**
 * Generador de APP_KEY para AuthManager Base
 * 
 * Este script genera una clave segura de 32 caracteres
 * y la aplica automáticamente al archivo .env
 * 
 * Uso: php generate-key.php
 */

echo "\n===============================================\n";
echo "    GENERADOR DE APP_KEY - AuthManager Base\n";
echo "===============================================\n\n";

// Definir rutas necesarias para helpers y compatibilidad
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__ . '/../app');
}
// Incluir helpers globales
require_once APP_PATH . '/Helpers/Functions.php';

// Verificar que existe el archivo .env
$envFile = '.env';
if (!file_exists($envFile)) {
    echo "❌ Error: No se encontró el archivo .env\n";
    echo "💡 Tip: Copia .env.example a .env primero\n\n";
    echo "Ejecuta: copy .env.example .env (Windows) o cp .env.example .env (Linux/Mac)\n\n";
    exit(1);
}

// Generar nueva APP_KEY
function generateSecureKey($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

// Generar clave de 32 caracteres
$newKey = generateSecureKey(32);

echo "🔐 Nueva APP_KEY generada: {$newKey}\n\n";

// Leer contenido actual del .env
$envContent = file_get_contents($envFile);
if ($envContent === false) {
    echo "❌ Error: No se pudo leer el archivo .env\n\n";
    exit(1);
}

// Buscar y reemplazar la línea APP_KEY
$lines = explode("\n", $envContent);
$keyUpdated = false;
$keyLineFound = false;

for ($i = 0; $i < count($lines); $i++) {
    $line = trim($lines[$i]);
    
    // Buscar línea que contenga APP_KEY (incluso si está vacía o comentada)
    if (strpos($line, 'APP_KEY') !== false) {
        $keyLineFound = true;
        
        // Si la línea está comentada, descomentarla
        if (strpos($line, '#') === 0) {
            $lines[$i] = "APP_KEY={$newKey}";
        } else {
            // Reemplazar el valor actual
            $lines[$i] = "APP_KEY={$newKey}";
        }
        
        $keyUpdated = true;
        echo "✅ APP_KEY actualizada en línea " . ($i + 1) . "\n";
        break;
    }
}

// Si no se encontró la línea APP_KEY, agregarla
if (!$keyLineFound) {
    // Buscar la sección de configuración de aplicación
    $appSectionFound = false;
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], 'CONFIGURACIÓN DE LA APLICACIÓN') !== false) {
            $appSectionFound = true;
            continue;
        }
        
        if ($appSectionFound && strpos($lines[$i], 'APP_') !== false) {
            // Insertar después de la primera línea APP_*
            array_splice($lines, $i + 1, 0, "APP_KEY={$newKey}");
            $keyUpdated = true;
            echo "✅ APP_KEY agregada después de línea " . ($i + 1) . "\n";
            break;
        }
    }
    
    // Si no se encontró sección de APP, agregar al final de configuración de aplicación
    if (!$keyUpdated) {
        for ($i = 0; $i < count($lines); $i++) {
            if (strpos($lines[$i], 'APP_NAME') !== false) {
                array_splice($lines, $i + 1, 0, "APP_KEY={$newKey}");
                $keyUpdated = true;
                echo "✅ APP_KEY agregada después de APP_NAME\n";
                break;
            }
        }
    }
}

if (!$keyUpdated) {
    echo "⚠️  Advertencia: No se pudo encontrar dónde colocar APP_KEY\n";
    echo "🔧 Agregando al inicio del archivo...\n";
    array_unshift($lines, "APP_KEY={$newKey}");
    $keyUpdated = true;
}

// Guardar el archivo actualizado
$newEnvContent = implode("\n", $lines);
$result = file_put_contents($envFile, $newEnvContent);

if ($result === false) {
    echo "❌ Error: No se pudo escribir en el archivo .env\n";
    echo "💡 Verifica que tengas permisos de escritura\n\n";
    exit(1);
}

echo "\n🎉 ¡APP_KEY generada y aplicada exitosamente!\n\n";

// Mostrar información adicional
echo "📋 Información de la clave:\n";
echo "   - Longitud: " . strlen($newKey) . " caracteres\n";
echo "   - Algoritmo: Caracteres alfanuméricos seguros\n";
echo "   - Archivo: {$envFile}\n\n";

echo "🔒 Recomendaciones de seguridad:\n";
echo "   ✓ Esta clave es única para este proyecto\n";
echo "   ✓ No la compartas públicamente\n";
echo "   ✓ No la incluyas en control de versiones\n";
echo "   ✓ Genera una nueva para cada entorno (dev/prod)\n\n";

echo "🚀 Siguiente paso:\n";
echo "   Configura el resto de variables en .env\n";
echo "   (Base de datos, email, etc.)\n\n";

echo "===============================================\n\n";
?>