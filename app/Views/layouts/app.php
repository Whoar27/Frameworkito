<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel de Administración - AuthManager Base">
    <meta name="author" content="AuthManager Team">
    <title><?= $title ?? 'Panel de Administración' ?> - AuthManager</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('assets/img/favicon.ico') ?>">

    <!-- CSS NotiWhoar -->
    <link href="<?= asset('assets/vendors/notiwhoar/1.0.5/css/styles.css') ?>" rel="stylesheet">
    <script>
        (function() {
            try {
                var theme = localStorage.getItem('admin-theme') || 'light';
                if (theme === 'system') {
                    var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    theme = prefersDark ? 'dark' : 'light';
                }
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>
    <!-- Bootstrap 5 CSS -->
    <link href="<?= asset('assets/vendors/bootstrap/5.3.7/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?= asset('assets/vendors/font-awesome/6.7.2/css/all.css') ?>" rel="stylesheet">

    <!-- CSS de la aplicación -->
    <link href="<?= asset('assets/css/app-v1.0.5.css') ?>" rel="stylesheet">

    <!-- Variables CSS para temas -->
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --navbar-height: 70px;
            --transition-speed: 0.3s;
        }
    </style>
</head>

<body class="admin-layout">
    <!-- Overlay para móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Logo -->
        <div class="sidebar-header">
            <div class="logo-container">
                <img src="<?= asset('assets/img/logo.svg') ?>" alt="Logo" class="logo-img">
                <span class="logo-text">AuthManager</span>
            </div>
            <button class="sidebar-toggle desktop-toggle" id="sidebarToggle">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <!-- Navegación -->
        <nav class="sidebar-nav">
            <div class="nav-wrapper">
                <!-- Dashboard -->
                <div class="nav-item">
                    <a href="/dashboard" class="nav-link active">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>

                <!-- Usuarios -->
                <div class="nav-item has-submenu">
                    <a href="#" class="nav-link submenu-toggle">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="nav-text">Usuarios</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <div class="submenu-item">
                            <a href="/users" class="submenu-link">
                                <i class="fas fa-list submenu-icon"></i>
                                <span class="submenu-text">Lista de Usuarios</span>
                            </a>
                        </div>
                        <div class="submenu-item">
                            <a href="/users/create" class="submenu-link">
                                <i class="fas fa-plus submenu-icon"></i>
                                <span class="submenu-text">Nuevo Usuario</span>
                            </a>
                        </div>
                        <div class="submenu-item has-submenu">
                            <a href="#" class="submenu-link submenu-toggle">
                                <i class="fas fa-cog submenu-icon"></i>
                                <span class="submenu-text">Configuración</span>
                                <i class="fas fa-chevron-right submenu-arrow"></i>
                            </a>
                            <div class="submenu level-3">
                                <div class="submenu-item">
                                    <a href="/users/roles" class="submenu-link">
                                        <i class="fas fa-user-tag submenu-icon"></i>
                                        <span class="submenu-text">Roles</span>
                                    </a>
                                </div>
                                <div class="submenu-item">
                                    <a href="/users/permissions" class="submenu-link">
                                        <i class="fas fa-key submenu-icon"></i>
                                        <span class="submenu-text">Permisos</span>
                                    </a>
                                </div>
                                <div class="submenu-item">
                                    <a href="/users/settings" class="submenu-link">
                                        <i class="fas fa-sliders-h submenu-icon"></i>
                                        <span class="submenu-text">Configuración</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seguridad -->
                <div class="nav-item has-submenu">
                    <a href="#" class="nav-link submenu-toggle">
                        <i class="fas fa-shield-alt nav-icon"></i>
                        <span class="nav-text">Seguridad</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <div class="submenu-item">
                            <a href="/security/logs" class="submenu-link">
                                <i class="fas fa-file-alt submenu-icon"></i>
                                <span class="submenu-text">Logs de Actividad</span>
                            </a>
                        </div>
                        <div class="submenu-item">
                            <a href="/security/sessions" class="submenu-link">
                                <i class="fas fa-clock submenu-icon"></i>
                                <span class="submenu-text">Sesiones Activas</span>
                            </a>
                        </div>
                        <div class="submenu-item has-submenu">
                            <a href="#" class="submenu-link submenu-toggle">
                                <i class="fas fa-lock submenu-icon"></i>
                                <span class="submenu-text">Autenticación</span>
                                <i class="fas fa-chevron-right submenu-arrow"></i>
                            </a>
                            <div class="submenu level-3">
                                <div class="submenu-item">
                                    <a href="/security/2fa" class="submenu-link">
                                        <i class="fas fa-mobile-alt submenu-icon"></i>
                                        <span class="submenu-text">2FA</span>
                                    </a>
                                </div>
                                <div class="submenu-item">
                                    <a href="/security/passwords" class="submenu-link">
                                        <i class="fas fa-key submenu-icon"></i>
                                        <span class="submenu-text">Políticas</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sistema -->
                <div class="nav-item has-submenu">
                    <a href="#" class="nav-link submenu-toggle">
                        <i class="fas fa-cogs nav-icon"></i>
                        <span class="nav-text">Sistema</span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <div class="submenu">
                        <div class="submenu-item">
                            <a href="/system/settings" class="submenu-link">
                                <i class="fas fa-sliders-h submenu-icon"></i>
                                <span class="submenu-text">Configuración</span>
                            </a>
                        </div>
                        <div class="submenu-item">
                            <a href="/system/backup" class="submenu-link">
                                <i class="fas fa-download submenu-icon"></i>
                                <span class="submenu-text">Respaldos</span>
                            </a>
                        </div>
                        <div class="submenu-item has-submenu">
                            <a href="#" class="submenu-link submenu-toggle">
                                <i class="fas fa-tools submenu-icon"></i>
                                <span class="submenu-text">Herramientas</span>
                                <i class="fas fa-chevron-right submenu-arrow"></i>
                            </a>
                            <div class="submenu level-3">
                                <div class="submenu-item">
                                    <a href="/system/maintenance" class="submenu-link">
                                        <i class="fas fa-wrench submenu-icon"></i>
                                        <span class="submenu-text">Mantenimiento</span>
                                    </a>
                                </div>
                                <div class="submenu-item">
                                    <a href="/system/cache" class="submenu-link">
                                        <i class="fas fa-server submenu-icon"></i>
                                        <span class="submenu-text">Caché</span>
                                    </a>
                                </div>
                                <div class="submenu-item">
                                    <a href="/system/database" class="submenu-link">
                                        <i class="fas fa-database submenu-icon"></i>
                                        <span class="submenu-text">Base de Datos</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reportes -->
                <div class="nav-item">
                    <a href="/reports" class="nav-link">
                        <i class="fas fa-chart-bar nav-icon"></i>
                        <span class="nav-text">Reportes</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Usuario Info -->
        <div class="sidebar-footer">
            <div class="user-info">
                <img src="<?= asset('assets/img/default-avatar.png') ?>" alt="Avatar" class="user-avatar">
                <div class="user-details">
                    <span class="user-name">Willian Hoyos</span>
                    <span class="user-role">Administrador</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="navbar-container">
                <!-- Left side -->
                <div class="navbar-left">
                    <button class="sidebar-toggle mobile-toggle" id="sidebarToggleMobile">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb-container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i></a></li>
                                <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                                    <?php foreach ($breadcrumbs as $index => $crumb): ?>
                                        <?php if ($index === count($breadcrumbs) - 1): ?>
                                            <li class="breadcrumb-item active" aria-current="page"><?= $crumb['title'] ?></li>
                                        <?php else: ?>
                                            <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= $crumb['title'] ?></a></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Right side -->
                <div class="navbar-right">
                    <!-- Search -->
                    <div class="search-container">
                        <div class="search-wrapper">
                            <input type="text" class="search-input" placeholder="Buscar...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>

                    <!-- Theme Toggle -->
                    <div class="dropdown theme-dropdown" id="themeDropdown">
                        <button class="theme-toggle dropdown-toggle dropdown-navbar" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Cambiar tema">
                            <i class="fas fa-moon" id="themeDropdownIcon"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item theme-option" href="#" data-theme-value="light"><i class="fas fa-sun"></i> Claro</a></li>
                            <li><a class="dropdown-item theme-option" href="#" data-theme-value="dark"><i class="fas fa-moon"></i> Oscuro</a></li>
                            <li><a class="dropdown-item theme-option" href="#" data-theme-value="system"><i class="fa fa-adjust"></i> Automático</a></li>
                        </ul>
                    </div>

                    <!-- Notifications -->
                    <div class="dropdown notifications-dropdown">
                        <button class="nav-btn dropdown-toggle dropdown-navbar" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-header">
                                <h6>Notificaciones</h6>
                                <span class="badge bg-primary">3 nuevas</span>
                            </div>
                            <div class="dropdown-body">
                                <a href="#" class="dropdown-item">
                                    <div class="notification-item">
                                        <i class="fas fa-user text-primary"></i>
                                        <div class="notification-content">
                                            <p>Nuevo usuario registrado</p>
                                            <small>Hace 5 minutos</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="notification-item">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        <div class="notification-content">
                                            <p>Intento de acceso fallido</p>
                                            <small>Hace 15 minutos</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="notification-item">
                                        <i class="fas fa-server text-success"></i>
                                        <div class="notification-content">
                                            <p>Backup completado</p>
                                            <small>Hace 1 hora</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-footer">
                                <a href="/notifications" class="btn btn-sm btn-outline-primary w-100">Ver todas</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown user-dropdown">
                        <button class="user-menu-btn dropdown-toggle dropdown-navbar" data-bs-toggle="dropdown">
                            <img src="<?= asset('assets/img/default-avatar.png') ?>" alt="Avatar" class="user-avatar-small">
                            <span class="user-name-small">Willian Hoyos</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-header">
                                <h6>Willian Hoyos</h6>
                                <small>willianhoyos@gmail.com</small>
                            </div>
                            <!-- <div class="dropdown-divider"></div> -->
                            <a href="/profile" class="dropdown-item mt-2">
                                <i class="fas fa-user"></i>
                                Mi Perfil
                            </a>
                            <a href="/settings" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                Configuración
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0);" class="dropdown-item text-danger" onclick="fncLogout()">
                                <i class="fas fa-sign-out-alt"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-content">
            <div class="content-wrapper">
                <?= $content ?? '' ?>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="<?= asset('assets/vendors/bootstrap/5.3.7/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- JavaScript de la aplicación -->
    <script src="<?= asset('assets/js/app-v1.0.5.js') ?>"></script>

    <!-- JS NotiWhoar -->
    <script src="<?= asset('assets/vendors/notiwhoar/1.0.5/js/main.js') ?>"></script>

    <!-- Variables PHP para JavaScript -->
    <script>
        window.AppConfig = {
            baseUrl: <?= json_encode(base_url()) ?>,
            csrfToken: <?= json_encode(csrf_token()) ?>,
            user: {
                id: null,
                username: <?= json_encode('Willian Hoyos') ?>,
                role: <?= json_encode('Administrador') ?>
            }
        };
    </script>
</body>

</html>