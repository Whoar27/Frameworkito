<header>
    <h2>Vanilla Notifications</h2>
    <p>Una biblioteca ligera de notificaciones para JavaScript vanilla</p>
</header>

<div class="row">
    <div class="col-sm-6">
        <div class="card mb-2">
            <div class="card-header">
                <h4 class="card-title">Tipos de notificaciones</h4>
            </div>
            <div class="card-body">
                <div class="button-group">
                    <button onclick="showNotificationTypes('default');" class="btn btn-primary">Default</button>
                    <button onclick="showNotificationTypes('success');" class="btn btn-success">Success</button>
                    <button onclick="showNotificationTypes('danger');" class="btn btn-danger">Danger</button>
                    <button onclick="showNotificationTypes('info');" class="btn btn-info">Info</button>
                    <button onclick="showNotificationTypes('warning');" class="btn btn-warning">Warning</button>
                    <button onclick="showNotificationTypes('custom');" class="btn btn-dark">Custom</button>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header">
                <h4 class="card-title">Tipos de notificaciones con iconos</h4>
            </div>
            <div class="card-body">
                <div class="button-group">
                    <button onclick="showNotificationTypesWhitIcon('default');" class="btn btn-primary">Default</button>
                    <button onclick="showNotificationTypesWhitIcon('success');" class="btn btn-success">Success</button>
                    <button onclick="showNotificationTypesWhitIcon('danger');" class="btn btn-danger">Danger</button>
                    <button onclick="showNotificationTypesWhitIcon('info');" class="btn btn-info">Info</button>
                    <button onclick="showNotificationTypesWhitIcon('warning');" class="btn btn-warning">Warning</button>
                    <button onclick="showNotificationTypesWhitIcon('custom');" class="btn btn-dark">Custom</button>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header">
                <h4 class="card-title">Tipos de notificaciones sin borde izquierdo</h4>
            </div>
            <div class="card-body">
                <div class="button-group">
                    <button onclick="showNotificationTypesWhitoutBorder('default');" class="btn btn-primary">Default</button>
                    <button onclick="showNotificationTypesWhitoutBorder('success');" class="btn btn-success">Success</button>
                    <button onclick="showNotificationTypesWhitoutBorder('danger');" class="btn btn-danger">Danger</button>
                    <button onclick="showNotificationTypesWhitoutBorder('info');" class="btn btn-info">Info</button>
                    <button onclick="showNotificationTypesWhitoutBorder('warning');" class="btn btn-warning">Warning</button>
                    <button onclick="showNotificationTypesWhitoutBorder('custom');" class="btn btn-dark">Custom</button>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header">
                <h4 class="card-title">Posiciones</h4>
            </div>
            <div class="card-body">
                <div class="button-group">
                    <button onclick="showNotificationPosition('top-left');" class="btn btn-secondary">Top Left</button>
                    <button onclick="showNotificationPosition('top-right');" class="btn btn-secondary">Top Right</button>
                    <button onclick="showNotificationPosition('top-center');" class="btn btn-secondary">Top Center</button>
                    <button onclick="showNotificationPosition('bottom-left');" class="btn btn-secondary">Bottom Left</button>
                    <button onclick="showNotificationPosition('bottom-right');" class="btn btn-secondary">Bottom Right</button>
                    <button onclick="showNotificationPosition('bottom-center');" class="btn btn-secondary">Bottom Center</button>
                    <button onclick="showNotificationPosition('center');" class="btn btn-secondary">Center</button>
                    <button onclick="showNotificationPosition('top-full');" class="btn btn-secondary">Top Full</button>
                    <button onclick="showNotificationPosition('bottom-full');" class="btn btn-secondary">Bottom Full</button>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header">
                <h4 class="card-title">Animaciones</h4>
            </div>
            <div class="card-body">
                <div class="button-group">
                    <button onclick="showNotificationAnimation('fade');" class="btn btn-secondary">Fade</button>
                    <button onclick="showNotificationAnimation('slide-left');" class="btn btn-secondary">Slide Left</button>
                    <button onclick="showNotificationAnimation('slide-right');" class="btn btn-secondary">Slide Right</button>
                    <button onclick="showNotificationAnimation('slide-top');" class="btn btn-secondary">Slide Top</button>
                    <button onclick="showNotificationAnimation('slide-bottom');" class="btn btn-secondary">Slide Bottom</button>
                    <button onclick="showNotificationAnimation('bounce');" class="btn btn-secondary">Bounce</button>
                    <button onclick="showNotificationAnimation('zoom');" class="btn btn-secondary">Zoom</button>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header">
                <h4 class="card-title">Opciones</h4>
            </div>
            <div class="card-body">
                <div class="button-group">
                    <button onclick="showNotificationOption('with-close');" class="btn btn-secondary">Con botón de cierre</button>
                    <button onclick="showNotificationOption('without-close');" class="btn btn-secondary">Sin botón de cierre</button>
                    <button onclick="showNotificationOption('with-timer');" class="btn btn-secondary">Con temporizador</button>
                    <button onclick="showNotificationOption('without-timer');" class="btn btn-secondary">Sin temporizador</button>
                    <button onclick="showNotificationOption('pause-on-hover');" class="btn btn-secondary">Pausar al pasar el ratón</button>
                    <button onclick="showNotificationOption('touch-dismiss');" class="btn btn-secondary">Deslizar para cerrar</button>
                </div>
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-header">
                <h4 class="card-title">Acciones</h4>
            </div>
            <div class="card-body">
                <div class="button-group">
                    <button onclick="removeAllNotifications()" class="btn btn-danger">Eliminar todas</button>
                    <button onclick="removeOldestNotification()" class="btn btn-warning">Eliminar la más antigua</button>
                    <button onclick="removeNewestNotification()" class="btn btn-info">Eliminar la más reciente</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <!-- Mostrar codigo javascript -->
        <div class="card mb-2">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Código JavaScript</h4>
                <button id="copy-code-btn" class="btn btn-sm btn-outline-secondary" title="Copiar código">
                    <i class="fas fa-copy me-1"></i> Copiar
                </button>
            </div>
            <div class="card-body p-0">
                <div class="code-container">
                    <pre class="m-0"><code id="js-code" class="language-javascript">// El código aparecerá aquí cuando hagas clic en un botón
// Ejemplo: notify('success', 'Mensaje de éxito');</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>