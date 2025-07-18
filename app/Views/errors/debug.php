<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Debug - AuthManager Base</title>

    <!-- Bootstrap 5 CSS -->
    <link href="<?= asset('assets/vendors/bootstrap/5.3.7/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= asset('assets/vendors/font-awesome/6.7.2/css/all.css') ?>" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .debug-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .debug-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .debug-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .debug-content {
            padding: 2rem;
        }

        .error-details {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0.5rem;
        }

        .stack-trace {
            background: #212529;
            color: #00ff00;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .system-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .info-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #007bff;
        }

        .context-section {
            margin: 2rem 0;
            padding: 1rem;
            background: #fff3cd;
            border-radius: 0.5rem;
            border: 1px solid #ffeaa7;
        }

        .help-section {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <div class="debug-container">
        <div class="debug-card">
            <!-- Header -->
            <div class="debug-header">
                <i class="fas fa-bug fa-3x mb-3"></i>
                <h1 class="h2 mb-2">Error de Debug</h1>
                <p class="mb-0">Información detallada del error para desarrollo</p>
            </div>

            <!-- Content -->
            <div class="debug-content">
                <!-- Error Details -->
                <div class="error-details">
                    <h4><i class="fas fa-exclamation-triangle text-danger me-2"></i>Detalles del Error</h4>
                    <p><strong>Mensaje:</strong> <?= htmlspecialchars($error['message'] ?? 'Error desconocido') ?></p>
                    <p><strong>Archivo:</strong> <?= htmlspecialchars($error['file'] ?? 'Desconocido') ?></p>
                    <p><strong>Línea:</strong> <?= htmlspecialchars($error['line'] ?? 'Desconocida') ?></p>
                    <p><strong>Tipo:</strong> <?= htmlspecialchars($error['type'] ?? 'Error') ?></p>
                    <p><strong>Tiempo:</strong> <?= date('Y-m-d H:i:s') ?></p>
                </div>

                <!-- Context Information -->
                <?php if (!empty($error['context'])): ?>
                    <div class="context-section">
                        <h5><i class="fas fa-info-circle me-2"></i>Contexto del Error</h5>
                        <pre><?= htmlspecialchars(print_r($error['context'], true)) ?></pre>
                    </div>
                <?php endif; ?>

                <!-- Stack Trace -->
                <?php if (!empty($error['trace'])): ?>
                    <div class="mb-4">
                        <h5><i class="fas fa-list me-2"></i>Stack Trace</h5>
                        <div class="stack-trace">
                            <?= htmlspecialchars($error['trace']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- System Information -->
                <div class="system-info">
                    <div class="info-card">
                        <h6><i class="fas fa-server me-2"></i>PHP</h6>
                        <p class="mb-0"><?= PHP_VERSION ?></p>
                    </div>
                    <div class="info-card">
                        <h6><i class="fas fa-memory me-2"></i>Memoria</h6>
                        <p class="mb-0"><?= round(memory_get_usage(true) / 1024 / 1024, 2) ?> MB</p>
                    </div>
                    <div class="info-card">
                        <h6><i class="fas fa-clock me-2"></i>Tiempo</h6>
                        <p class="mb-0"><?= number_format((microtime(true) - ($_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true))) * 1000, 2) ?> ms</p>
                    </div>
                    <div class="info-card">
                        <h6><i class="fas fa-globe me-2"></i>IP</h6>
                        <p class="mb-0"><?= $_SERVER['REMOTE_ADDR'] ?? 'Desconocida' ?></p>
                    </div>
                </div>

                <!-- Request Information -->
                <div class="context-section">
                    <h5><i class="fas fa-exchange-alt me-2"></i>Información de la Petición</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Método:</strong> <?= $_SERVER['REQUEST_METHOD'] ?? 'GET' ?></p>
                            <p><strong>URI:</strong> <?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/') ?></p>
                            <p><strong>User Agent:</strong> <?= htmlspecialchars(substr($_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido', 0, 100)) ?>...</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Referer:</strong> <?= htmlspecialchars($_SERVER['HTTP_REFERER'] ?? 'Directo') ?></p>
                            <p><strong>Host:</strong> <?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'localhost') ?></p>
                            <p><strong>Protocolo:</strong> <?= $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1' ?></p>
                        </div>
                    </div>
                </div>

                <!-- POST/GET Data -->
                <?php if (!empty($_POST) || !empty($_GET)): ?>
                    <div class="context-section">
                        <h5><i class="fas fa-database me-2"></i>Datos de la Petición</h5>

                        <?php if (!empty($_GET)): ?>
                            <h6>GET Parameters:</h6>
                            <pre><?= htmlspecialchars(print_r($_GET, true)) ?></pre>
                        <?php endif; ?>

                        <?php if (!empty($_POST)): ?>
                            <h6>POST Parameters:</h6>
                            <pre><?= htmlspecialchars(print_r(array_diff_key($_POST, ['password' => '', '_token' => '']), true)) ?></pre>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Session Data -->
                <?php if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION)): ?>
                    <div class="context-section">
                        <h5><i class="fas fa-user-circle me-2"></i>Datos de Sesión</h5>
                        <pre><?= htmlspecialchars(print_r(array_diff_key($_SESSION, ['password' => '', 'token' => '']), true)) ?></pre>
                    </div>
                <?php endif; ?>

                <!-- Help Section -->
                <div class="help-section">
                    <h5><i class="fas fa-question-circle me-2"></i>¿Cómo resolver este error?</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Pasos recomendados:</h6>
                            <ol>
                                <li>Revisa el archivo y línea mencionados</li>
                                <li>Verifica la sintaxis PHP</li>
                                <li>Comprueba que existan todos los archivos</li>
                                <li>Revisa los logs del servidor</li>
                                <li>Verifica la configuración de la base de datos</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h6>Recursos útiles:</h6>
                            <ul>
                                <li><a href="https://www.php.net/manual/es/" target="_blank">Manual de PHP</a></li>
                                <li><a href="https://github.com" target="_blank">Buscar en GitHub</a></li>
                                <li><a href="https://stackoverflow.com" target="_blank">Stack Overflow</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center mt-4">
                    <a href="javascript:history.back()" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <a href="/" class="btn btn-primary me-2">
                        <i class="fas fa-home me-2"></i>Ir al Inicio
                    </a>
                    <button class="btn btn-info" onclick="location.reload()">
                        <i class="fas fa-redo me-2"></i>Recargar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="<?= asset('assets/vendors/bootstrap/5.3.7/js/bootstrap.bundle.min.js') ?>"></script>
    
    <script>
        // Auto-scroll to error details on load
        document.addEventListener('DOMContentLoaded', function() {
            const errorDetails = document.querySelector('.error-details');
            if (errorDetails) {
                errorDetails.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });

        // Copy error details to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Error copiado al portapapeles');
            });
        }

        // Add copy buttons if needed
        const stackTrace = document.querySelector('.stack-trace');
        if (stackTrace) {
            const copyBtn = document.createElement('button');
            copyBtn.className = 'btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2';
            copyBtn.innerHTML = '<i class="fas fa-copy"></i>';
            copyBtn.onclick = () => copyToClipboard(stackTrace.textContent);
            stackTrace.style.position = 'relative';
            stackTrace.appendChild(copyBtn);
        }

        console.log('🐛 Debug page loaded');
        console.log('📍 Error details available in the page');
    </script>
</body>

</html>