<section class="container py-5">
    <h1 class="mb-4">📚 Documentación del Proyecto</h1>

    <p class="lead">
        Bienvenido a la documentación oficial de
        <strong><?= htmlspecialchars($_ENV['APP_NAME'] ?? 'tu proyecto') ?></strong>.<br>
        Aquí encontrarás guías prácticas, referencias técnicas y recursos útiles para sacarle el máximo provecho a este <strong>mini-framework</strong>.
    </p>

    <hr class="my-5">

    <h2 class="h4 mb-4">Índice de secciones disponibles</h2>

    <?php foreach ($docs as $categoria => $contenido) { ?>
        <?php if (!is_array($contenido)) continue; ?>

        <h3 class="mt-5"><?= htmlspecialchars($categoria) ?></h3>

        <?php
        $isSubgroup = false;
        $subkeys = array_keys($contenido);

        // Detecta si contiene subgrupos reales, por ejemplo: 'Otros' => [...], 'Avanzado' => [...], etc.
        foreach ($contenido as $maybeGroup) {
            if (is_array($maybeGroup) && isset($maybeGroup[0]['name'], $maybeGroup[0]['url'])) {
                $isSubgroup = true;
                break;
            }
        }

        // Si no hay subgrupos, tratamos como lista directa
        if (!$isSubgroup) { ?>
            <div class="row g-3 mb-4">
                <?php foreach ($contenido as $item) { ?>
                    <?php if (isset($item['url'], $item['name'])) { ?>
                        <div class="col-md-4">
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="btn btn-outline-primary w-100 text-start">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

        <?php } else { ?>
            <?php foreach ($contenido as $subtitulo => $items) { ?>
                <?php if (!is_array($items)) continue; ?>

                <?php
                // Solo mostramos el subtítulo si hay más de un subgrupo o el nombre no es 'Otros'
                $mostrarTitulo = (count($subkeys) > 1 || strtolower($subtitulo) !== 'otros');
                ?>

                <?php if ($mostrarTitulo) { ?>
                    <h4 class="mt-3"><?= htmlspecialchars($subtitulo) ?></h4>
                <?php } ?>

                <div class="row g-3 mb-4">
                    <?php foreach ($items as $item) { ?>
                        <?php if (isset($item['url'], $item['name'])) { ?>
                            <div class="col-md-4">
                                <a href="<?= htmlspecialchars($item['url']) ?>" class="btn btn-outline-primary w-100 text-start">
                                    <?= htmlspecialchars($item['name']) ?>
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>

    <hr class="my-5">

    <p class="text-muted small">
        ¿Falta alguna guía? Puedes contribuir o enviar sugerencias desde el repositorio del proyecto.
    </p>
</section>