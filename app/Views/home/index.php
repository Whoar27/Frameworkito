<?php

/**
 * Datos de ejemplo para el Dashboard
 * Estas variables se pueden usar temporalmente hasta conectar con la base de datos
 */

// Información del usuario actual (normalmente vendría de la sesión)
$user = [
    'id' => 1,
    'username' => 'Administrator',
    'email' => 'admin@authmanager.com',
    'role' => 'Administrador',
    'avatar' => '/assets/img/default-avatar.png',
    'last_login' => '2025-07-16 14:30:00',
    'status' => 'active'
];

// Estadísticas del sistema
$stats = [
    // Usuarios
    'total_users' => 1247,
    'new_users_this_month' => 89,
    'active_users_today' => 156,
    'verified_users' => 1180,

    // Sesiones
    'active_sessions' => 23,
    'total_sessions_today' => 342,
    'last_activity' => 2, // minutos
    'avg_session_duration' => '24m',

    // Seguridad
    'security_events_today' => 0,
    'failed_logins_today' => 3,
    'blocked_ips_count' => 12,
    'security_alerts' => 1,

    // Sistema
    'uptime' => '99.97%',
    'server_load' => '2.1',
    'db_version' => 'MySQL 8.0.35',
    'php_version' => PHP_VERSION,

    // Almacenamiento
    'storage_used' => 2.4, // GB
    'storage_total' => 10, // GB
    'storage_percentage' => 24,
    'backup_size' => '850MB',

    // Fechas importantes
    'last_backup' => '2025-07-16 02:00:00',
    'next_maintenance' => '2025-07-20 03:00:00',
    'license_expires' => '2025-12-31',

    // Performance
    'avg_response_time' => 120, // ms
    'db_queries_today' => 15420,
    'cache_hit_ratio' => 94.2, // %
    'error_rate' => 0.01 // %
];

// Actividad reciente del sistema
$recent_activity = [
    [
        'id' => 1,
        'user_id' => 1,
        'username' => 'Administrator',
        'email' => 'admin@authmanager.com',
        'action' => 'user_login',
        'message' => 'Inicio de sesión exitoso',
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Google Chrome, Windows 10',
        'type' => 'success',
        'created_at' => '2025-07-16 14:30:15',
        'context' => [
            'method' => 'email_password',
            'remember_me' => true
        ]
    ],
    [
        'id' => 2,
        'user_id' => 15,
        'username' => 'JuanPerez',
        'email' => 'juan.perez@empresa.com',
        'action' => 'password_changed',
        'message' => 'Contraseña actualizada desde perfil',
        'ip_address' => '203.0.113.45',
        'user_agent' => 'Safari, iPhone',
        'type' => 'info',
        'created_at' => '2025-07-16 13:45:32',
        'context' => [
            'initiated_by' => 'user',
            'source' => 'profile_settings'
        ]
    ],
    [
        'id' => 3,
        'user_id' => null,
        'username' => 'Sistema',
        'email' => 'system@authmanager.com',
        'action' => 'backup_completed',
        'message' => 'Backup automático completado exitosamente',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Frameworkito Cron v1.0',
        'type' => 'success',
        'created_at' => '2025-07-16 02:00:00',
        'context' => [
            'backup_size' => '850MB',
            'duration' => '12 minutes',
            'location' => '/backups/20250716_020000.sql.gz'
        ]
    ],
    [
        'id' => 4,
        'user_id' => 32,
        'username' => 'MariaGomez',
        'email' => 'maria.gomez@empresa.com',
        'action' => 'failed_login',
        'message' => 'Intento de inicio de sesión fallido - contraseña incorrecta',
        'ip_address' => '198.51.100.23',
        'user_agent' => 'Mozilla Firefox, Ubuntu',
        'type' => 'warning',
        'created_at' => '2025-07-16 12:15:45',
        'context' => [
            'attempts_count' => 2,
            'account_locked' => false,
            'reason' => 'invalid_password'
        ]
    ],
    [
        'id' => 5,
        'user_id' => 8,
        'username' => 'CarlosRuiz',
        'email' => 'carlos.ruiz@empresa.com',
        'action' => 'user_registered',
        'message' => 'Nueva cuenta de usuario creada',
        'ip_address' => '203.0.113.78',
        'user_agent' => 'Google Chrome, macOS',
        'type' => 'success',
        'created_at' => '2025-07-16 11:30:22',
        'context' => [
            'registration_method' => 'email_verification',
            'role_assigned' => 'user',
            'email_verified' => false
        ]
    ],
    [
        'id' => 6,
        'user_id' => 1,
        'username' => 'Administrator',
        'email' => 'admin@authmanager.com',
        'action' => 'settings_updated',
        'message' => 'Configuración del sistema actualizada',
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Google Chrome, Windows 10',
        'type' => 'info',
        'created_at' => '2025-07-16 10:45:18',
        'context' => [
            'section' => 'email_settings',
            'changes' => ['smtp_host', 'smtp_port'],
            'previous_values' => 'encrypted'
        ]
    ],
    [
        'id' => 7,
        'user_id' => null,
        'username' => 'Sistema',
        'email' => 'system@authmanager.com',
        'action' => 'security_scan',
        'message' => 'Escaneo de seguridad automático completado',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Frameworkito Security Scanner v1.0',
        'type' => 'info',
        'created_at' => '2025-07-16 09:00:00',
        'context' => [
            'threats_detected' => 0,
            'files_scanned' => 1247,
            'duration' => '3 minutes',
            'last_scan' => '2025-07-15 09:00:00'
        ]
    ],
    [
        'id' => 8,
        'user_id' => 45,
        'username' => 'LauraVasquez',
        'email' => 'laura.vasquez@empresa.com',
        'action' => 'password_reset_requested',
        'message' => 'Solicitud de recuperación de contraseña',
        'ip_address' => '203.0.113.156',
        'user_agent' => 'Safari, iPad',
        'type' => 'info',
        'created_at' => '2025-07-16 08:22:33',
        'context' => [
            'reset_token_sent' => true,
            'email_delivered' => true,
            'expires_at' => '2025-07-16 09:22:33'
        ]
    ]
];

// Notificaciones del sistema
$notifications = [
    [
        'id' => 1,
        'title' => 'Backup completado',
        'message' => 'El backup automático se completó exitosamente',
        'type' => 'success',
        'icon' => 'fas fa-check-circle',
        'created_at' => '2025-07-16 02:00:00',
        'read' => false,
        'url' => '/system/backups'
    ],
    [
        'id' => 2,
        'title' => 'Nuevo usuario registrado',
        'message' => 'CarlosRuiz se registró en el sistema',
        'type' => 'info',
        'icon' => 'fas fa-user-plus',
        'created_at' => '2025-07-16 11:30:22',
        'read' => false,
        'url' => '/users/view/8'
    ],
    [
        'id' => 3,
        'title' => 'Intento de acceso fallido',
        'message' => 'Múltiples intentos fallidos desde IP 198.51.100.23',
        'type' => 'warning',
        'icon' => 'fas fa-exclamation-triangle',
        'created_at' => '2025-07-16 12:15:45',
        'read' => false,
        'url' => '/security/logs?filter=failed_login'
    ],
    [
        'id' => 4,
        'title' => 'Actualización disponible',
        'message' => 'Nueva versión v1.1.0 disponible para descarga',
        'type' => 'info',
        'icon' => 'fas fa-download',
        'created_at' => '2025-07-15 16:00:00',
        'read' => true,
        'url' => '/system/updates'
    ]
];

// Métricas adicionales para gráficos (si decides implementarlos)
$metrics = [
    'daily_users' => [
        'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        'data' => [45, 52, 48, 61, 55, 28, 32]
    ],
    'monthly_registrations' => [
        'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul'],
        'data' => [28, 35, 42, 38, 51, 47, 89]
    ],
    'security_events' => [
        'labels' => ['Login exitoso', 'Login fallido', 'Cambio contraseña', 'Logout', 'Otros'],
        'data' => [245, 12, 18, 156, 23]
    ]
];

// Estado del sistema en tiempo real
$system_status = [
    'database' => [
        'status' => 'online',
        'response_time' => 45, // ms
        'connections' => 8,
        'max_connections' => 100
    ],
    'cache' => [
        'status' => 'online',
        'hit_rate' => 94.2, // %
        'memory_usage' => 67.3, // %
        'keys_count' => 15420
    ],
    'email' => [
        'status' => 'online',
        'queue_size' => 3,
        'sent_today' => 47,
        'failed_today' => 0
    ],
    'storage' => [
        'status' => 'online',
        'used_space' => 24, // %
        'free_space' => '7.6GB',
        'total_space' => '10GB'
    ]
];

// Configuración rápida para el breadcrumb
$breadcrumb = 'Dashboard';
$title = 'Panel de Administración';
?>

<?php
/**
 * VISTA DASHBOARD - PANEL DE ADMINISTRACIÓN
 * Vista principal del dashboard con estadísticas, actividad reciente y métricas del sistema
 */

// Incluir datos de ejemplo (en producción esto vendría del controlador)
// include_once __DIR__ . '/data/dashboard_data.php';
?>

<!-- Header del Dashboard -->
<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="page-title mb-2">Panel de Administración</h1>
            <p class="text-muted mb-0">
                Bienvenido de vuelta, <strong><?= $user['username'] ?></strong>.
                Último acceso: <?= date('d/m/Y H:i', strtotime($user['last_login'])) ?>
            </p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="dashboard-actions">
                <button class="btn btn-outline-primary btn-sm me-2" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i>
                    Actualizar
                </button>
                <button class="btn btn-primary btn-sm" onclick="generateReport()">
                    <i class="fas fa-chart-line"></i>
                    Generar Reporte
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Principales -->
<div class="row mb-4">
    <!-- Total Usuarios -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-content">
                        <div class="stat-number text-primary"><?= number_format($stats['total_users']) ?></div>
                        <div class="stat-label text-muted">Total Usuarios</div>
                        <div class="stat-change text-success mt-2">
                            <i class="fas fa-arrow-up"></i>
                            +<?= $stats['new_users_this_month'] ?> este mes
                        </div>
                    </div>
                    <div class="stat-icon">
                        <div class="icon-wrapper bg-primary-light">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuarios Activos -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-content">
                        <div class="stat-number text-success"><?= number_format($stats['active_users_today']) ?></div>
                        <div class="stat-label text-muted">Usuarios Activos Hoy</div>
                        <div class="stat-change text-success mt-2">
                            <i class="fas fa-arrow-up"></i>
                            +12% vs ayer
                        </div>
                    </div>
                    <div class="stat-icon">
                        <div class="icon-wrapper bg-success-light">
                            <i class="fas fa-user-check text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sesiones Activas -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-content">
                        <div class="stat-number text-info"><?= number_format($stats['active_sessions']) ?></div>
                        <div class="stat-label text-muted">Sesiones Activas</div>
                        <div class="stat-change text-muted mt-2">
                            <i class="fas fa-clock"></i>
                            Duración promedio: <?= $stats['avg_session_duration'] ?>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <div class="icon-wrapper bg-info-light">
                            <i class="fas fa-desktop text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Eventos de Seguridad -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="stat-content">
                        <div class="stat-number <?= $stats['security_events_today'] > 0 ? 'text-warning' : 'text-success' ?>">
                            <?= number_format($stats['security_events_today']) ?>
                        </div>
                        <div class="stat-label text-muted">Eventos de Seguridad</div>
                        <div class="stat-change <?= $stats['failed_logins_today'] > 0 ? 'text-warning' : 'text-success' ?> mt-2">
                            <i class="fas fa-shield-alt"></i>
                            <?= $stats['failed_logins_today'] ?> intentos fallidos
                        </div>
                    </div>
                    <div class="stat-icon">
                        <div class="icon-wrapper <?= $stats['security_events_today'] > 0 ? 'bg-warning-light' : 'bg-success-light' ?>">
                            <i class="fas fa-shield-alt <?= $stats['security_events_today'] > 0 ? 'text-warning' : 'text-success' ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Principal -->
<div class="row">
    <!-- Columna Izquierda -->
    <div class="col-xl-8 mb-4">
        <!-- Actividad Reciente -->
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history text-primary me-2"></i>
                        Actividad Reciente
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="loadMoreActivity()">
                            <i class="fas fa-plus"></i>
                            Ver más
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="activity-timeline">
                    <?php foreach (array_slice($recent_activity, 0, 6) as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon <?= $activity['type'] ?>">
                                <?php
                                $icons = [
                                    'success' => 'fas fa-check-circle',
                                    'info' => 'fas fa-info-circle',
                                    'warning' => 'fas fa-exclamation-triangle',
                                    'error' => 'fas fa-times-circle'
                                ];
                                ?>
                                <i class="<?= $icons[$activity['type']] ?? 'fas fa-circle' ?>"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-header">
                                    <div class="activity-user">
                                        <strong><?= htmlspecialchars($activity['username']) ?></strong>
                                        <span class="text-muted">
                                            (<?= htmlspecialchars($activity['email']) ?>)
                                        </span>
                                    </div>
                                    <div class="activity-time text-muted">
                                        <?= date('H:i', strtotime($activity['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="activity-message">
                                    <?= htmlspecialchars($activity['message']) ?>
                                </div>
                                <div class="activity-meta">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= htmlspecialchars($activity['ip_address']) ?>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-globe me-1"></i>
                                        <?= htmlspecialchars(explode(',', $activity['user_agent'])[0]) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($recent_activity) === 0): ?>
                    <div class="empty-state py-5 text-center">
                        <i class="fas fa-history text-muted fa-3x mb-3"></i>
                        <h6 class="text-muted">No hay actividad reciente</h6>
                        <p class="text-muted mb-0">La actividad del sistema aparecerá aquí cuando ocurra.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Columna Derecha -->
    <div class="col-xl-4">
        <!-- Estado del Sistema -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-server text-primary me-2"></i>
                    Estado del Sistema
                </h5>
            </div>
            <div class="card-body">
                <!-- Base de Datos -->
                <div class="system-status-item mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="status-label">
                            <i class="fas fa-database me-2"></i>
                            Base de Datos
                        </div>
                        <span class="badge bg-success-light text-success">Online</span>
                    </div>
                    <div class="status-details">
                        <small class="text-muted">
                            Respuesta: <?= $system_status['database']['response_time'] ?>ms •
                            Conexiones: <?= $system_status['database']['connections'] ?>/<?= $system_status['database']['max_connections'] ?>
                        </small>
                    </div>
                </div>

                <!-- Caché -->
                <div class="system-status-item mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="status-label">
                            <i class="fas fa-layer-group me-2"></i>
                            Sistema de Caché
                        </div>
                        <span class="badge bg-success-light text-success">Online</span>
                    </div>
                    <div class="status-details">
                        <small class="text-muted">
                            Hit Rate: <?= $system_status['cache']['hit_rate'] ?>% •
                            Memoria: <?= $system_status['cache']['memory_usage'] ?>%
                        </small>
                    </div>
                </div>

                <!-- Email -->
                <div class="system-status-item mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="status-label">
                            <i class="fas fa-envelope me-2"></i>
                            Sistema de Email
                        </div>
                        <span class="badge bg-success-light text-success">Online</span>
                    </div>
                    <div class="status-details">
                        <small class="text-muted">
                            Cola: <?= $system_status['email']['queue_size'] ?> •
                            Enviados hoy: <?= $system_status['email']['sent_today'] ?>
                        </small>
                    </div>
                </div>

                <!-- Almacenamiento -->
                <div class="system-status-item">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="status-label">
                            <i class="fas fa-hdd me-2"></i>
                            Almacenamiento
                        </div>
                        <span class="badge bg-success-light text-success">
                            <?= $stats['storage_percentage'] ?>%
                        </span>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-primary"
                            style="width: <?= $stats['storage_percentage'] ?>%"></div>
                    </div>
                    <div class="status-details">
                        <small class="text-muted">
                            Usado: <?= $stats['storage_used'] ?>GB de <?= $stats['storage_total'] ?>GB
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas Rápidas -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tachometer-alt text-primary me-2"></i>
                    Métricas Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="metric-item mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Tiempo de Respuesta</span>
                        <span class="metric-value text-success"><?= $stats['avg_response_time'] ?>ms</span>
                    </div>
                </div>

                <div class="metric-item mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Consultas BD Hoy</span>
                        <span class="metric-value"><?= number_format($stats['db_queries_today']) ?></span>
                    </div>
                </div>

                <div class="metric-item mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Hit Rate Caché</span>
                        <span class="metric-value text-success"><?= $stats['cache_hit_ratio'] ?>%</span>
                    </div>
                </div>

                <div class="metric-item mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Tasa de Error</span>
                        <span class="metric-value text-success"><?= $stats['error_rate'] ?>%</span>
                    </div>
                </div>

                <div class="metric-item">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="metric-label">Uptime</span>
                        <span class="metric-value text-success"><?= $stats['uptime'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card border-0">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt text-primary me-2"></i>
                    Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="createBackup()">
                        <i class="fas fa-download me-2"></i>
                        Crear Backup
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="clearCache()">
                        <i class="fas fa-broom me-2"></i>
                        Limpiar Caché
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="viewLogs()">
                        <i class="fas fa-file-alt me-2"></i>
                        Ver Logs
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="systemMaintenance()">
                        <i class="fas fa-wrench me-2"></i>
                        Mantenimiento
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información Adicional del Sistema -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header bg-transparent border-0 py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="info-item">
                            <div class="info-label text-muted">Versión PHP</div>
                            <div class="info-value"><?= $stats['php_version'] ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-item">
                            <div class="info-label text-muted">Base de Datos</div>
                            <div class="info-value"><?= $stats['db_version'] ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-item">
                            <div class="info-label text-muted">Último Backup</div>
                            <div class="info-value"><?= date('d/m/Y H:i', strtotime($stats['last_backup'])) ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="info-item">
                            <div class="info-label text-muted">Próximo Mantenimiento</div>
                            <div class="info-value"><?= date('d/m/Y H:i', strtotime($stats['next_maintenance'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<pre>
    <?php
    // print_r($_SESSION);
    ?>
</pre>

<!-- JavaScript específico del dashboard -->
<script>
    // Funciones para las acciones rápidas
    function generateReport() {
        Whoar.notificacionEspera({
            titulo: 'Generando reporte',
            mensaje: 'Por favor espere mientras se genera el reporte del sistema...',
            tiempo: 3000,
            onComplete: () => {
                Whoar.exito('Reporte generado exitosamente');
                // Aquí iría la lógica para descargar o mostrar el reporte
            }
        });
    }

    function createBackup() {
        Whoar.confirmacion({
            titulo: 'Crear Backup',
            contenido: '¿Está seguro de que desea crear un backup completo del sistema?',
            tipo: 'info'
        }).then(confirmed => {
            if (confirmed) {
                Whoar.notificacionEspera({
                    titulo: 'Creando backup',
                    mensaje: 'Generando backup completo del sistema...',
                    tiempo: 8000,
                    onComplete: () => {
                        Whoar.exito('Backup creado exitosamente');
                        // Aquí se actualizarían las estadísticas
                    }
                });
            }
        });
    }

    function clearCache() {
        Whoar.confirmacion({
            titulo: 'Limpiar Caché',
            contenido: '¿Desea limpiar todo el caché del sistema?',
            tipo: 'warning'
        }).then(confirmed => {
            if (confirmed) {
                Whoar.notificacionEspera({
                    titulo: 'Limpiando caché',
                    mensaje: 'Eliminando archivos de caché...',
                    tiempo: 2000,
                    onComplete: () => {
                        Whoar.exito('Caché limpiado exitosamente');
                        // Actualizar métricas de caché
                    }
                });
            }
        });
    }

    function viewLogs() {
        window.location.href = '/security/logs';
    }

    function systemMaintenance() {
        window.location.href = '/system/maintenance';
    }

    function loadMoreActivity() {
        Whoar.informacion('Cargando más actividad...', {
            duracion: 2000
        });
        // Aquí se implementaría la carga AJAX de más actividad
    }

    // Auto-actualización cada 30 segundos
    setInterval(() => {
        // Aquí se implementaría la actualización automática de estadísticas
        // console.log('Auto-updating dashboard stats...');
    }, 30000);

    // Inicialización cuando la página esté lista
    document.addEventListener('DOMContentLoaded', function() {
        // Animación de entrada para las tarjetas estadísticas
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('fade-in');
            }, index * 100);
        });
    });
</script>