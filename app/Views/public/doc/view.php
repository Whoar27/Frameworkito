<style>
    .markdown-body {
        max-width: 100%;
        margin: 2rem auto;
        padding: 2rem;
        background: var(--surface-primary, #fff);
        border-radius: 10px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.07);
    }

    .markdown-body h1,
    .markdown-body h2,
    .markdown-body h3 {
        margin-top: 2rem;
    }

    .markdown-body pre {
        background: #f6f8fa;
        padding: 1em;
        border-radius: 6px;
        overflow-x: auto;
    }

    .markdown-body code {
        background-color: #f0f0f0;
        padding: 0.2em 0.4em;
        border-radius: 4px;
        font-family: monospace;
    }

    /* Tablas responsivas con ParsedownExtra */
    .markdown-body table {
        width: 100%;
        background: var(--surface-secondary, #fafbfc);
        border-collapse: collapse;
        margin: 1rem 0;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .markdown-body th,
    .markdown-body td {
        padding: 0.75em 1em;
        border: 1px solid var(--border-primary, #e1e4e8);
        text-align: left;
        vertical-align: top;
        word-wrap: break-word;
        max-width: 300px;
    }

    .markdown-body th {
        background: var(--surface-hover, #f6f8fa);
        font-weight: 600;
        color: var(--text-primary, #24292e);
    }

    /* Contenedor responsivo para tablas */
    .markdown-body .table-responsive {
        overflow-x: auto;
        margin: 1rem 0;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Estilos para listas de definición (ParsedownExtra) */
    .markdown-body dl {
        margin: 1rem 0;
    }

    .markdown-body dt {
        font-weight: 600;
        color: var(--text-primary, #24292e);
        margin-top: 1rem;
    }

    .markdown-body dd {
        margin-left: 1rem;
        margin-bottom: 0.5rem;
        color: var(--text-secondary, #586069);
    }

    /* Estilos para notas al pie (ParsedownExtra) */
    .markdown-body .footnotes {
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-primary, #e1e4e8);
        font-size: 0.875rem;
        color: var(--text-secondary, #586069);
    }

    .markdown-body .footnotes ol {
        padding-left: 1rem;
    }

    .markdown-body .footnote-ref {
        text-decoration: none;
        font-weight: 600;
    }

    .markdown-body .footnote-backref {
        text-decoration: none;
        margin-left: 0.5rem;
    }

    .markdown-body a {
        color: var(--primary, #0d6efd);
        text-decoration: none;
    }

    .markdown-body a:hover {
        text-decoration: underline;
    }

    /* Estilos responsivos para móviles */
    @media (max-width: 768px) {
        .markdown-body {
            padding: 1rem;
        }

        .markdown-body table {
            font-size: 0.875rem;
            margin: 0.5rem 0;
        }

        .markdown-body th,
        .markdown-body td {
            padding: 0.5em 0.75em;
            max-width: 200px;
        }

        .markdown-body dl {
            margin: 0.5rem 0;
        }

        .markdown-body dt {
            margin-top: 0.75rem;
        }

        .markdown-body dd {
            margin-left: 0.5rem;
        }
    }
</style>

<div class="container py-4">
    <h1 class="mb-4"><?= htmlspecialchars($page_title ?? 'Guía') ?></h1>

    <div class="markdown-body">
        <?= $content ?>
    </div>

    <div class="text-center mt-5">
        <a href="/doc" class="btn btn-outline-secondary">
            ← Volver al índice
        </a>
    </div>
</div>
