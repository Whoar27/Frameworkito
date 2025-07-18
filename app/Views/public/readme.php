<?php
// app/Views/public/readme.php
// Vista pública para visualizar el README.md renderizado como HTML

// RUTA ABSOLUTA al README.md
$readmePath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'README.md';
$parsedownPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'Parsedown.php';

$html = '';

if (file_exists($readmePath)) {
    // Incluir Parsedown
    if (file_exists($parsedownPath)) {
        require_once $parsedownPath;
        $markdown = file_get_contents($readmePath);
        $Parsedown = new Parsedown();
        $html = $Parsedown->text($markdown);
    } else {
        $html = '<div class="alert alert-danger">No se encontró la librería Parsedown.</div>';
    }
} else {
    $html = '<div class="alert alert-danger">No se encontró el archivo README.md.</div>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista del README.md</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/vendors/bootstrap/5.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/guest-v1.0.5.css">
    <style>
        .markdown-body {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--surface-primary, #fff);
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.07);
        }
        .markdown-body h1, .markdown-body h2, .markdown-body h3 {
            margin-top: 2rem;
        }
        .markdown-body pre {
            background: #f6f8fa;
            padding: 1em;
            border-radius: 6px;
            overflow-x: auto;
        }
        .markdown-body table {
            width: 100%;
            background: #fafbfc;
        }
        .markdown-body th, .markdown-body td {
            padding: 0.5em 1em;
            border: 1px solid #e1e4e8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-4 text-center">README.md (Vista pública)</h1>
        <div class="markdown-body">
            <?= $html ?>
        </div>
        <div class="text-center mt-4">
            <a href="/" class="btn btn-secondary">Volver al inicio</a>
        </div>
    </div>
</body>
</html>
