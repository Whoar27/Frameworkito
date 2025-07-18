<?php

/**
 * Script de Migraciones - AuthManager Base
 * 
 * Ejecuta migraciones de base de datos de forma automatizada
 * 
 * Uso:
 * php migrate.php               # Ejecutar todas las migraciones pendientes
 * php migrate.php --status      # Ver estado de migraciones
 * php migrate.php --rollback    # Rollback último batch
 * php migrate.php --fresh       # Drop todo y recrear
 */

// Cargar autoload de Composer y variables de entorno
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Definir rutas correctamente desde la raíz del proyecto
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', APP_PATH . '/Config');

// Inicializar Dotenv para cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Cargar configuraciones
require_once CONFIG_PATH . '/database.php';

class MigrationRunner {
    private PDO $pdo;
    private array $config;
    private string $migrationsPath;

    public function __construct() {
        $this->config = require CONFIG_PATH . '/database.php';
        $this->migrationsPath = ROOT_PATH . '/database/migrations';
        $this->connectDatabase();
    }

    /**
     * Conectar a la base de datos
     */
    private function connectDatabase(): void {
        try {
            $connection = $this->config['connections'][$this->config['default']];

            $dsn = "mysql:host={$connection['host']};port={$connection['port']};dbname={$connection['database']};charset={$connection['charset']}";

            $this->pdo = new PDO(
                $dsn,
                $connection['username'],
                $connection['password'],
                $connection['options'] ?? []
            );

            $this->output("✅ Conectado a la base de datos: {$connection['database']}", 'success');
        } catch (PDOException $e) {
            $this->output("❌ Error conectando a la base de datos: " . $e->getMessage(), 'error');
            exit(1);
        }
    }

    /**
     * Obtener archivos de migración del directorio
     */
    private function getMigrationFiles(): array {
        if (!is_dir($this->migrationsPath)) {
            $this->output("❌ Directorio de migraciones no encontrado: {$this->migrationsPath}", 'error');
            exit(1);
        }

        // Obtener todos los archivos .sql
        $files = glob($this->migrationsPath . '/*.sql');

        if (empty($files)) {
            $this->output("⚠️  No se encontraron archivos de migración en: {$this->migrationsPath}", 'warning');
            return [];
        }

        // Extraer solo nombres de archivo y ordenar
        $migrationFiles = [];
        foreach ($files as $file) {
            $migrationFiles[] = basename($file);
        }

        // Ordenar por nombre (el prefijo numérico garantiza orden correcto)
        sort($migrationFiles);

        return $migrationFiles;
    }

    /**
     * Validar nombre de archivo de migración
     */
    private function isValidMigrationFile(string $filename): bool {
        // Patrón: XXX_nombre_descriptivo.sql
        return preg_match('/^\d{3}_[a-zA-Z0-9_]+\.sql$/', $filename);
    }

    /**
     * Ejecutar todas las migraciones pendientes
     */
    public function migrate(): void {
        $this->output("🚀 Iniciando proceso de migraciones...", 'info');

        // Asegurar que existe tabla migrations
        $this->ensureMigrationsTable();

        // Obtener archivos de migración dinámicamente
        $migrationFiles = $this->getMigrationFiles();

        if (empty($migrationFiles)) {
            $this->output("ℹ️  No hay archivos de migración para procesar", 'info');
            return;
        }

        // Filtrar archivo 000 (la tabla migrations se crea automáticamente)
        $validFiles = [];
        foreach ($migrationFiles as $file) {
            // Saltar archivo 000_create_migrations_table.sql
            if ($file === '000_create_migrations_table.sql') {
                $this->output("⏭️  Saltando: {$file} (tabla migrations se crea automáticamente)", 'info');
                continue;
            }

            if ($this->isValidMigrationFile($file)) {
                $validFiles[] = $file;
            } else {
                $this->output("⚠️  Archivo ignorado (formato inválido): {$file}", 'warning');
            }
        }

        if (empty($validFiles)) {
            $this->output("❌ No se encontraron archivos de migración válidos", 'error');
            return;
        }

        $this->output("📂 Encontrados " . count($validFiles) . " archivos de migración válidos", 'info');

        // Obtener migraciones ejecutadas
        $executed = $this->getExecutedMigrations();

        // Obtener siguiente batch
        $nextBatch = $this->getNextBatch();

        $pendingCount = 0;

        foreach ($validFiles as $migration) {
            if (!in_array($migration, $executed)) {
                $this->executeMigration($migration, $nextBatch);
                $pendingCount++;
            } else {
                $this->output("⏭️  Saltando: {$migration} (ya ejecutada)", 'info');
            }
        }

        if ($pendingCount > 0) {
            $this->output("✅ {$pendingCount} migraciones ejecutadas exitosamente en batch {$nextBatch}", 'success');
        } else {
            $this->output("ℹ️  No hay migraciones pendientes", 'info');
        }
    }

    /**
     * Mostrar estado de migraciones
     */
    public function status(): void {
        $this->output("📊 Estado de Migraciones:", 'info');
        $this->output(str_repeat("-", 70), 'info');

        // Obtener archivos dinámicamente
        $migrationFiles = $this->getMigrationFiles();
        $executed = $this->getExecutedMigrations();

        if (empty($migrationFiles)) {
            $this->output("ℹ️  No se encontraron archivos de migración", 'info');
            return;
        }

        foreach ($migrationFiles as $migration) {
            if (!$this->isValidMigrationFile($migration)) {
                $status = "❌ FORMATO INVÁLIDO";
                $color = 'error';
            } elseif (in_array($migration, $executed)) {
                $status = "✅ EJECUTADA";
                $color = 'success';
            } else {
                $status = "⏳ PENDIENTE";
                $color = 'warning';
            }

            $this->output(sprintf("%-50s %s", $migration, $status), $color);
        }

        // Verificar archivos ejecutados que ya no existen
        $missingFiles = array_diff($executed, $migrationFiles);
        if (!empty($missingFiles)) {
            $this->output("\n⚠️  Archivos ejecutados pero no encontrados en directorio:", 'warning');
            foreach ($missingFiles as $missing) {
                $this->output("   - {$missing}", 'warning');
            }
        }

        $validFiles = array_filter($migrationFiles, [$this, 'isValidMigrationFile']);
        $executedValidCount = count(array_intersect($executed, $validFiles));
        $totalValidCount = count($validFiles);
        $pendingCount = $totalValidCount - $executedValidCount;

        $this->output(str_repeat("-", 70), 'info');
        $this->output("Total válidas: {$totalValidCount} | Ejecutadas: {$executedValidCount} | Pendientes: {$pendingCount}", 'info');
    }

    /**
     * Rollback último batch
     */
    public function rollback(): void {
        $this->output("🔄 Iniciando rollback...", 'warning');

        $lastBatch = $this->getLastBatch();
        if (!$lastBatch) {
            $this->output("ℹ️  No hay migraciones para revertir", 'info');
            return;
        }

        $migrationsToRollback = $this->getMigrationsByBatch($lastBatch);

        $this->output("⚠️  Se revertirán " . count($migrationsToRollback) . " migraciones del batch {$lastBatch}:", 'warning');
        foreach ($migrationsToRollback as $migration) {
            $this->output("  - {$migration}", 'warning');
        }

        if (!$this->confirm("¿Continuar con el rollback? [y/N]: ")) {
            $this->output("❌ Rollback cancelado", 'info');
            return;
        }

        // Ejecutar rollback (por ahora solo eliminar registros)
        // En un sistema más avanzado, aquí ejecutarías los scripts de rollback
        foreach ($migrationsToRollback as $migration) {
            $this->removeFromMigrationsTable($migration);
            $this->output("🔄 Revertida: {$migration}", 'success');
        }

        $this->output("✅ Rollback completado", 'success');
    }

    /**
     * Recrear base de datos desde cero
     */
    public function fresh(): void {
        $this->output("⚠️  ATENCIÓN: Esto eliminará TODAS las tablas y datos", 'error');

        if (!$this->confirm("¿Estás seguro? [y/N]: ")) {
            $this->output("❌ Operación cancelada", 'info');
            return;
        }

        $this->output("🗑️  Eliminando todas las tablas...", 'warning');
        $this->dropAllTables();

        $this->output("🚀 Ejecutando todas las migraciones...", 'info');
        $this->migrate();
    }

    /**
     * Asegurar que existe tabla migrations
     */
    private function ensureMigrationsTable(): void {
        // Verificar si ya existe la tabla
        try {
            $stmt = $this->pdo->query("SHOW TABLES LIKE 'migrations'");
            if ($stmt->rowCount() > 0) {
                return; // Ya existe
            }
        } catch (PDOException $e) {
            // Tabla no existe, continuar con creación
        }

        try {
            // Crear tabla migrations directamente (sin archivo)
            $sql = "
                CREATE TABLE `migrations` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `batch` int(11) NOT NULL,
                    `executed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `migration` (`migration`),
                    KEY `batch` (`batch`),
                    KEY `executed_at` (`executed_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";

            $this->pdo->exec($sql);
            $this->output("✅ Tabla migrations creada", 'success');
        } catch (PDOException $e) {
            $this->output("❌ Error creando tabla migrations: " . $e->getMessage(), 'error');
            exit(1);
        }
    }

    /**
     * Ejecutar una migración específica
     */
    private function executeMigration(string $migration, int $batch): void {
        $filePath = $this->migrationsPath . '/' . $migration;

        if (!file_exists($filePath)) {
            $this->output("❌ Archivo no encontrado: {$migration}", 'error');
            return;
        }

        try {
            $this->output("🔄 Ejecutando: {$migration}...", 'info');

            // Leer y ejecutar SQL
            $sql = file_get_contents($filePath);
            $this->pdo->exec($sql);

            // Registrar en tabla migrations
            $stmt = $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
            $stmt->execute([$migration, $batch]);

            $this->output("✅ Completada: {$migration}", 'success');
        } catch (PDOException $e) {
            $this->output("❌ Error ejecutando {$migration}: " . $e->getMessage(), 'error');
            exit(1);
        }
    }

    /**
     * Obtener migraciones ejecutadas
     */
    private function getExecutedMigrations(): array {
        try {
            $stmt = $this->pdo->query("SELECT migration FROM migrations ORDER BY id");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            return []; // Tabla migrations no existe aún
        }
    }

    /**
     * Obtener siguiente número de batch
     */
    private function getNextBatch(): int {
        try {
            $stmt = $this->pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['max_batch'] ?? 0) + 1;
        } catch (PDOException $e) {
            return 1;
        }
    }

    /**
     * Obtener último batch
     */
    private function getLastBatch(): ?int {
        try {
            $stmt = $this->pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['max_batch'] ?? null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Obtener migraciones de un batch específico
     */
    private function getMigrationsByBatch(int $batch): array {
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations WHERE batch = ? ORDER BY id DESC");
        $stmt->execute([$batch]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Eliminar migración de la tabla
     */
    private function removeFromMigrationsTable(string $migration): void {
        $stmt = $this->pdo->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$migration]);
    }

    /**
     * Eliminar todas las tablas
     */
    private function dropAllTables(): void {
        // Desactivar foreign key checks
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        // Obtener todas las tablas
        $stmt = $this->pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Eliminar cada tabla
        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE IF EXISTS `{$table}`");
            $this->output("🗑️  Tabla eliminada: {$table}", 'warning');
        }

        // Reactivar foreign key checks
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    /**
     * Pedir confirmación al usuario
     */
    private function confirm(string $message): bool {
        echo $message;
        $handle = fopen("php://stdin", "r");
        $input = trim(fgets($handle));
        fclose($handle);
        return strtolower($input) === 'y';
    }

    /**
     * Mostrar mensaje con color
     */
    private function output(string $message, string $type = 'info'): void {
        $colors = [
            'info' => "\033[36m",     // Cyan
            'success' => "\033[32m",  // Green
            'warning' => "\033[33m",  // Yellow
            'error' => "\033[31m",    // Red
            'reset' => "\033[0m"      // Reset
        ];

        $color = $colors[$type] ?? $colors['info'];
        echo $color . $message . $colors['reset'] . PHP_EOL;
    }
}

// Ejecutar script
try {
    $runner = new MigrationRunner();

    $command = $argv[1] ?? 'migrate';

    switch ($command) {
        case '--status':
        case 'status':
            $runner->status();
            break;

        case '--rollback':
        case 'rollback':
            $runner->rollback();
            break;

        case '--fresh':
        case 'fresh':
            $runner->fresh();
            break;

        case 'migrate':
        default:
            $runner->migrate();
            break;
    }
} catch (Exception $e) {
    echo "\033[31m❌ Error: " . $e->getMessage() . "\033[0m" . PHP_EOL;
    exit(1);
}
