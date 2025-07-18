<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isDebug ? 'Error de Desarrollo' : 'Error del Servidor' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .error-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: none;
            border-radius: 15px;
        }

        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        .code-block {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .debug-section {
            margin-top: 2rem;
        }

        .debug-item {
            background: #e9ecef;
            padding: 0.5rem;
            margin: 0.2rem 0;
            border-radius: 3px;
            font-size: 0.85rem;
        }

        .btn-copy {
            font-size: 0.8rem;
        }

        .trace-section {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card error-card">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-exclamation-triangle error-icon"></i>
                        <h1 class="display-6 text-danger mb-3">
                            <?= $isDebug ? 'Error de Desarrollo' : 'Error del Servidor' ?>
                        </h1>

                        <div class="alert alert-danger text-start">
                            <strong><i class="fas fa-bug me-2"></i>Error:</strong>
                            <?= htmlspecialchars($e->getMessage()) ?>
                        </div>

                        <?php if ($isDebug): ?>
                            <div class="debug-section text-start">
                                <h5><i class="fas fa-info-circle me-2"></i>Información del Error</h5>
                                <div class="debug-item">
                                    <strong>Archivo:</strong> <?= htmlspecialchars($debugInfo['file']) ?>
                                </div>
                                <div class="debug-item">
                                    <strong>Línea:</strong> <?= $debugInfo['line'] ?>
                                </div>
                                <div class="debug-item">
                                    <strong>Tiempo:</strong> <?= $debugInfo['timestamp'] ?>
                                </div>
                                <div class="debug-item">
                                    <strong>Memoria:</strong> <?= $debugInfo['memory_usage'] ?>
                                </div>
                                <div class="debug-item">
                                    <strong>PHP:</strong> <?= $debugInfo['php_version'] ?>
                                </div>

                                <div class="mt-3">
                                    <h6><i class="fas fa-route me-2"></i>Request Info</h6>
                                    <div class="debug-item">
                                        <strong>URI:</strong> <?= htmlspecialchars($debugInfo['request_uri']) ?>
                                    </div>
                                    <div class="debug-item">
                                        <strong>Método:</strong> <?= htmlspecialchars($debugInfo['request_method']) ?>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <h6>
                                        <i class="fas fa-list me-2"></i>Stack Trace
                                        <button class="btn btn-sm btn-outline-secondary btn-copy ms-2" onclick="copyTrace()">
                                            <i class="fas fa-copy"></i> Copiar
                                        </button>
                                    </h6>
                                    <div class="trace-section">
                                        <pre class="code-block" id="stackTrace"><?= htmlspecialchars($debugInfo['trace']) ?></pre>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <h6><i class="fas fa-cogs me-2"></i>Sugerencias</h6>
                                    <div class="alert alert-info">
                                        <?php
                                        $suggestions = getSuggestions($e->getMessage());
                                        foreach ($suggestions as $suggestion): ?>
                                            <div><i class="fas fa-lightbulb me-2"></i><?= $suggestion ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="lead text-muted">Lo sentimos, ha ocurrido un error interno del servidor.</p>
                            <p class="text-muted">Por favor, inténtalo de nuevo más tarde.</p>
                        <?php endif; ?>

                        <div class="mt-4">
                            <a href="/" class="btn btn-primary me-2">
                                <i class="fas fa-home me-2"></i>Volver al Inicio
                            </a>
                            <?php if ($isDebug): ?>
                                <button class="btn btn-outline-secondary" onclick="location.reload()">
                                    <i class="fas fa-redo me-2"></i>Recargar
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyTrace() {
            const trace = document.getElementById('stackTrace');
            navigator.clipboard.writeText(trace.textContent).then(() => {
                const btn = document.querySelector('.btn-copy');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copiado';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success');

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-secondary');
                }, 2000);
            });
        }
    </script>
</body>

</html>