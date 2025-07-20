<?php
/**
 * DatabaseLogger - Clase para Logging en Base de Datos
 * Frameworkito
 * 
 * Maneja el logging en base de datos con:
 * - Logging de actividades de usuario
 * - Auditoría completa
 * - Limpieza automática
 * - Búsqueda y filtrado
 */

namespace App\Helpers;

class DatabaseLogger {
    private array $config;
    private PDO $pdo;
    private string $table;
    private array $pendingLogs = [];
    private bool $batchMode = false;

    public function __construct(?PDO $pdo = null, array $config = []) {
        $this->config = $config ?: (config('logging.database') ?? []);
        $this->table = $this->config['table'] ?? 'activity_logs';

        // Obtener conexión PDO
        $this->pdo = $pdo ?: $this->getPdoConnection();

        // Registrar función de limpieza al final del script
        register_shutdown_function([$this, 'flush']);
    }

    /**
     * Obtener conexión PDO
     */
    private function getPdoConnection(): PDO {
        if (function_exists('getPDO')) {
            return getPDO();
        }

        throw new \Exception('No hay conexión PDO disponible para DatabaseLogger');
    }

    /**
     * Escribir log en base de datos
     */
    public function log(string $type, string $action, string $message, array $context = [], ?int $userId = null): void {
        if (!$this->shouldLog($type)) {
            return;
        }

        try {
            $logData = $this->prepareLogData($type, $action, $message, $context, $userId);

            if ($this->batchMode) {
                $this->pendingLogs[] = $logData;
            } else {
                $this->insertLog($logData);
            }
        } catch (\Exception $e) {
            $this->handleLoggingError($e, $type, $action, $message);
        }
    }

    /**
     * Verificar si se debe hacer log del tipo especificado
     */
    private function shouldLog(string $type): bool {
        if (!($this->config['enabled'] ?? true)) {
            return false;
        }

        $typeConfig = $this->config['log_types'][$type] ?? null;
        return $typeConfig && ($typeConfig['enabled'] ?? true);
    }

    /**
     * Preparar datos del log
     */
    private function prepareLogData(string $type, string $action, string $message, array $context, ?int $userId): array {
        // Detectar user_id automáticamente si no se proporciona
        if ($userId === null && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }

        // Preparar contexto base
        $baseContext = [];

        // Agregar información automática según configuración
        if ($this->config['auto_context']['include'] ?? []) {
            $autoContext = $this->config['auto_context']['include'];

            if ($autoContext['request_uri'] ?? true) {
                $baseContext['request_uri'] = $_SERVER['REQUEST_URI'] ?? 'cli';
            }

            if ($autoContext['http_method'] ?? true) {
                $baseContext['http_method'] = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
            }

            if ($autoContext['user_agent'] ?? true) {
                $baseContext['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'cli';
            }
        }

        // Combinar contexto
        $finalContext = array_merge($baseContext, $context);

        // Sanitizar datos sensibles
        $sanitizeRules = $this->config['auto_context']['sanitize'] ?? [];
        $finalContext = $this->sanitizeData($finalContext, $sanitizeRules);

        return [
            'user_id' => $userId,
            'type' => $type,
            'action' => $action,
            'message' => substr($message, 0, 1000), // Limitar mensaje
            'context' => json_encode($finalContext, JSON_UNESCAPED_UNICODE),
            'ip_address' => get_client_ip(),
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'cli', 0, 500),
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Sanitizar datos sensibles
     */
    private function sanitizeData(array $data, array $rules): array {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitizeData($value, $rules);
                continue;
            }

            $lowerKey = strtolower($key);
            foreach ($rules as $pattern => $replacement) {
                if (strpos($lowerKey, strtolower($pattern)) !== false) {
                    $data[$key] = $replacement;
                    break;
                }
            }
        }

        return $data;
    }

    /**
     * Insertar log en base de datos
     */
    private function insertLog(array $logData): void {
        $fields = array_keys($logData);
        $placeholders = ':' . implode(', :', $fields);
        $fieldsList = implode(', ', $fields);

        $sql = "INSERT INTO {$this->table} ({$fieldsList}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($logData);
    }

    /**
     * Manejar errores de logging
     */
    private function handleLoggingError(\Exception $e, string $type, string $action, string $message): void {
        // Log a archivo como fallback
        if (function_exists('file_log')) {
            file_log('error', 'Error en DatabaseLogger: ' . $e->getMessage(), [
                'original_type' => $type,
                'original_action' => $action,
                'original_message' => $message
            ]);
        }
    }

    /**
     * =============================================================================
     * BATCH OPERATIONS
     * =============================================================================
     */

    /**
     * Activar modo batch
     */
    public function startBatch(): void {
        $this->batchMode = true;
        $this->pendingLogs = [];
    }

    /**
     * Escribir todos los logs pendientes
     */
    public function flush(): void {
        if (empty($this->pendingLogs)) {
            return;
        }

        try {
            $this->pdo->beginTransaction();

            foreach ($this->pendingLogs as $logData) {
                $this->insertLog($logData);
            }

            $this->pdo->commit();
            $this->pendingLogs = [];
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            $this->handleLoggingError($e, 'batch', 'flush', 'Error escribiendo logs en batch');
        }
    }

    /**
     * Terminar modo batch
     */
    public function endBatch(): void {
        $this->flush();
        $this->batchMode = false;
    }

    /**
     * =============================================================================
     * BÚSQUEDA Y CONSULTAS
     * =============================================================================
     */

    /**
     * Buscar logs por criterios
     */
    public function search(array $criteria = [], int $limit = 100, int $offset = 0): array {
        $where = [];
        $params = [];

        // Filtros disponibles
        if (!empty($criteria['user_id'])) {
            $where[] = 'user_id = :user_id';
            $params['user_id'] = $criteria['user_id'];
        }

        if (!empty($criteria['type'])) {
            $where[] = 'type = :type';
            $params['type'] = $criteria['type'];
        }

        if (!empty($criteria['action'])) {
            $where[] = 'action = :action';
            $params['action'] = $criteria['action'];
        }

        if (!empty($criteria['ip_address'])) {
            $where[] = 'ip_address = :ip_address';
            $params['ip_address'] = $criteria['ip_address'];
        }

        if (!empty($criteria['date_from'])) {
            $where[] = 'created_at >= :date_from';
            $params['date_from'] = $criteria['date_from'];
        }

        if (!empty($criteria['date_to'])) {
            $where[] = 'created_at <= :date_to';
            $params['date_to'] = $criteria['date_to'];
        }

        if (!empty($criteria['message_contains'])) {
            $where[] = 'message LIKE :message_contains';
            $params['message_contains'] = '%' . $criteria['message_contains'] . '%';
        }

        // Construir query
        $whereClause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        // Bind parámetros
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Decodificar contexto JSON
        foreach ($logs as &$log) {
            $log['context'] = json_decode($log['context'], true) ?? [];
        }

        return $logs;
    }

    /**
     * Contar logs por criterios
     */
    public function count(array $criteria = []): int {
        $where = [];
        $params = [];

        // Aplicar los mismos filtros que en search()
        if (!empty($criteria['user_id'])) {
            $where[] = 'user_id = :user_id';
            $params['user_id'] = $criteria['user_id'];
        }

        if (!empty($criteria['type'])) {
            $where[] = 'type = :type';
            $params['type'] = $criteria['type'];
        }

        if (!empty($criteria['action'])) {
            $where[] = 'action = :action';
            $params['action'] = $criteria['action'];
        }

        if (!empty($criteria['date_from'])) {
            $where[] = 'created_at >= :date_from';
            $params['date_from'] = $criteria['date_from'];
        }

        if (!empty($criteria['date_to'])) {
            $where[] = 'created_at <= :date_to';
            $params['date_to'] = $criteria['date_to'];
        }

        $whereClause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);
        $sql = "SELECT COUNT(*) FROM {$this->table} {$whereClause}";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Obtener logs de un usuario específico
     */
    public function getUserLogs(int $userId, int $limit = 50): array {
        return $this->search(['user_id' => $userId], $limit);
    }

    /**
     * Obtener logs por tipo
     */
    public function getLogsByType(string $type, int $limit = 100): array {
        return $this->search(['type' => $type], $limit);
    }

    /**
     * Obtener logs recientes
     */
    public function getRecentLogs(int $limit = 100): array {
        return $this->search([], $limit);
    }

    /**
     * =============================================================================
     * ESTADÍSTICAS
     * =============================================================================
     */

    /**
     * Obtener estadísticas generales
     */
    public function getStats(): array {
        $stats = [
            'total_logs' => 0,
            'logs_by_type' => [],
            'logs_by_action' => [],
            'unique_users' => 0,
            'logs_today' => 0,
            'logs_this_week' => 0,
            'logs_this_month' => 0
        ];

        try {
            // Total de logs
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}");
            $stats['total_logs'] = $stmt->fetchColumn();

            // Logs por tipo
            $stmt = $this->pdo->query("SELECT type, COUNT(*) as count FROM {$this->table} GROUP BY type ORDER BY count DESC");
            $stats['logs_by_type'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            // Logs por acción
            $stmt = $this->pdo->query("SELECT action, COUNT(*) as count FROM {$this->table} GROUP BY action ORDER BY count DESC LIMIT 20");
            $stats['logs_by_action'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            // Usuarios únicos
            $stmt = $this->pdo->query("SELECT COUNT(DISTINCT user_id) FROM {$this->table} WHERE user_id IS NOT NULL");
            $stats['unique_users'] = $stmt->fetchColumn();

            // Logs por período
            $today = date('Y-m-d');
            $thisWeek = date('Y-m-d', strtotime('monday this week'));
            $thisMonth = date('Y-m-01');

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} WHERE DATE(created_at) = ?");
            $stmt->execute([$today]);
            $stats['logs_today'] = $stmt->fetchColumn();

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} WHERE DATE(created_at) >= ?");
            $stmt->execute([$thisWeek]);
            $stats['logs_this_week'] = $stmt->fetchColumn();

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->table} WHERE DATE(created_at) >= ?");
            $stmt->execute([$thisMonth]);
            $stats['logs_this_month'] = $stmt->fetchColumn();
        } catch (\Exception $e) {
            $this->handleLoggingError($e, 'stats', 'get_stats', 'Error obteniendo estadísticas');
        }

        return $stats;
    }

    /**
     * Obtener actividad por día
     */
    public function getDailyActivity(int $days = 30): array {
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM {$this->table} 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(created_at) 
                ORDER BY date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$days]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * =============================================================================
     * LIMPIEZA Y MANTENIMIENTO
     * =============================================================================
     */

    /**
     * Limpiar logs antiguos
     */
    public function cleanup(): int {
        $cleanupConfig = $this->config['cleanup'] ?? [];

        if (!($cleanupConfig['enabled'] ?? true)) {
            return 0;
        }

        $retainDays = $cleanupConfig['retain_days'] ?? 90;
        $batchSize = $cleanupConfig['batch_size'] ?? 1000;

        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$retainDays} days"));

        $totalDeleted = 0;

        do {
            $sql = "DELETE FROM {$this->table} WHERE created_at < ? LIMIT {$batchSize}";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cutoffDate]);

            $deleted = $stmt->rowCount();
            $totalDeleted += $deleted;

            // Pausa pequeña para no sobrecargar la BD
            if ($deleted > 0) {
                usleep(100000); // 0.1 segundo
            }
        } while ($deleted > 0);

        if ($totalDeleted > 0) {
            file_log('info', "Limpieza de logs: {$totalDeleted} registros eliminados", [
                'cutoff_date' => $cutoffDate,
                'retain_days' => $retainDays
            ]);
        }

        return $totalDeleted;
    }

    /**
     * Optimizar tabla de logs
     */
    public function optimize(): void {
        try {
            // Optimizar tabla (MySQL)
            $this->pdo->exec("OPTIMIZE TABLE {$this->table}");

            file_log('info', 'Tabla de logs optimizada', ['table' => $this->table]);
        } catch (\Exception $e) {
            $this->handleLoggingError($e, 'maintenance', 'optimize', 'Error optimizando tabla');
        }
    }

    /**
     * Verificar e instalar índices
     */
    public function ensureIndexes(): void {
        $indexes = $this->config['indexes'] ?? [];

        foreach ($indexes as $indexName => $columns) {
            try {
                $columnsList = is_array($columns) ? implode(', ', $columns) : $columns;
                $sql = "CREATE INDEX IF NOT EXISTS {$indexName} ON {$this->table} ({$columnsList})";
                $this->pdo->exec($sql);
            } catch (\Exception $e) {
                // Índice probablemente ya existe, ignorar
            }
        }
    }

    /**
     * Obtener información de la tabla
     */
    public function getTableInfo(): array {
        try {
            // Obtener información de la tabla
            $stmt = $this->pdo->query("DESCRIBE {$this->table}");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Obtener tamaño de la tabla
            $stmt = $this->pdo->prepare("
                SELECT table_rows, data_length, index_length
                FROM information_schema.tables 
                WHERE table_schema = DATABASE() AND table_name = ?
            ");
            $stmt->execute([$this->table]);
            $tableStats = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'table_name' => $this->table,
                'columns' => $columns,
                'estimated_rows' => $tableStats['table_rows'] ?? 0,
                'data_size' => format_bytes($tableStats['data_length'] ?? 0),
                'index_size' => format_bytes($tableStats['index_length'] ?? 0),
                'total_size' => format_bytes(($tableStats['data_length'] ?? 0) + ($tableStats['index_length'] ?? 0))
            ];
        } catch (\Exception $e) {
            return [
                'table_name' => $this->table,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Destructor - asegurar que se escriban los logs pendientes
     */
    public function __destruct() {
        $this->flush();
    }
}
