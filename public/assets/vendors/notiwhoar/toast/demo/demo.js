/**
 * Script principal para la demo de Vanilla Notifications
 */
// Al inicio de tu demo.js o en el HTML
if (typeof browser === 'undefined' && typeof chrome === 'undefined') {
    // Código solo para móviles sin extensiones
    console.log('Ejecutando en móvil sin extensiones');
}

// Función para mostrar el código JavaScript en el área js-code
function showCodeSnippet(code) {
    const codeElement = document.getElementById('js-code');
    if (codeElement) {
        // Actualizamos el contenido del código
        codeElement.textContent = code;

        // Forzamos el resaltado de sintaxis con Prism.js
        if (window.Prism) {
            // Primero eliminamos la clase 'language-none' si existe
            codeElement.className = 'language-javascript';
            // Luego aplicamos el resaltado
            Prism.highlightElement(codeElement);
        }
    }
}

// Funciones globales para usar con onclick
function showNotificationTypes(type) {
    const notification = { ...Examples.types[type] };
    const code = `notify('${type}', '${notification.message.replace(/'/g, "\\'")}');`;
    showCodeSnippet(code);
    notify(type, notification.message);
}

function showNotificationTypesWhitIcon(type) {
    const notification = { ...Examples.typesWithIcon[type] };
    const options = {
        title: notification.title,
        dismiss: notification.dismiss
    };
    const code = `notify('${type}', '${notification.message.replace(/'/g, "\\'")}', ${JSON.stringify(options, null, 2)});`;
    showCodeSnippet(code);
    notify(type, notification.message, options);
}

function showNotificationTypesWhitoutBorder(type) {
    const notification = { ...Examples.typesWithoutBorderLeft[type] };

    // Primero mostramos la notificación normal
    const notificationElement = notify(type, notification.message, {
        backgroundColor: '#ffff',
        colorText: '#232E3C',
        icon: notification.icon,
        iconColor: notification.iconColor,
        dismiss: {
            borderLeft: false,
            duration: 3000,
            showTypeIcon: true,
            iconSize: 'sm'
        },
        onShow: function () {
            // Después de que se muestre la notificación, buscamos el elemento y le quitamos el borde
            const notifications = document.querySelectorAll('.notification');
            if (notifications.length > 0) {
                const latestNotification = notifications[notifications.length - 1];
                latestNotification.classList.add('notification-no-border');

                // Aseguramos que el borde izquierdo esté oculto
                latestNotification.style.borderLeft = 'none';
            }
        }
    });

    // Generamos el código para mostrar
    const code = `notify('${type}', '${notification.message.replace(/'/g, "\\'")}', {
    backgroundColor: '#ffff',
    colorText: '#232E3C',
    dismiss: {
        borderLeft: false,
        duration: 3000,
        showTypeIcon: true,
        iconSize: 'sm'
    }
});`;

    showCodeSnippet(code);
}

function showNotificationPosition(position) {
    let positionKey = position;
    if (position == 'top-left') positionKey = 'topLeft';
    if (position == 'top-right') positionKey = 'topRight';
    if (position == 'top-center') positionKey = 'topCenter';
    if (position == 'bottom-left') positionKey = 'bottomLeft';
    if (position == 'bottom-right') positionKey = 'bottomRight';
    if (position == 'bottom-center') positionKey = 'bottomCenter';
    if (position == 'center') positionKey = 'center';
    if (position == 'top-full') positionKey = 'topFull';
    if (position == 'bottom-full') positionKey = 'bottomFull';

    const posiciones = { ...Examples.positions[positionKey] };
    const options = {
        title: posiciones.title,
        position: position,
        dismiss: {
            duration: 3000
        },
        animationIn: posiciones.animationIn || [],
        animationOut: posiciones.animationOut || []
    };

    const code = `notify('info', '${posiciones.message.replace(/'/g, "\\'")}', ${JSON.stringify(options, null, 2)});`;
    showCodeSnippet(code);

    notify('info', posiciones.message, options);
}

function showNotificationAnimation(animation) {
    let animationType = animation;
    if (animation == 'fade') animationType = 'fade';
    if (animation == 'slide-left') animationType = 'slideLeft';
    if (animation == 'slide-right') animationType = 'slideRight';
    if (animation == 'slide-top') animationType = 'slideTop';
    if (animation == 'slide-bottom') animationType = 'slideBottom';
    if (animation == 'bounce') animationType = 'bounce';
    if (animation == 'zoom') animationType = 'zoom';

    const animationData = Examples.animations[animationType];
    const options = {
        title: animationData.title,
        animationIn: animationData.animationIn,
        animationOut: animationData.animationOut
    };

    const code = `notify('info', '${animationData.message.replace(/'/g, "\\'")}', ${JSON.stringify(options, null, 2)});`;
    showCodeSnippet(code);

    notify('info', animationData.message, options);
}

function showNotificationOption(option) {
    let optionKey = option;
    if (option == 'with-close') optionKey = 'withClose';
    if (option == 'without-close') optionKey = 'withoutClose';
    if (option == 'with-timer') optionKey = 'withTimer';
    if (option == 'without-timer') optionKey = 'withoutTimer';
    if (option == 'pause-on-hover') optionKey = 'pauseOnHover';
    if (option == 'touch-dismiss') optionKey = 'touchDismiss';

    const optionData = Examples.options[optionKey];
    const options = {
        title: optionData.title,
        dismiss: optionData.dismiss
    };

    const code = `notify('info', '${optionData.message.replace(/'/g, "\\'")}', ${JSON.stringify(options, null, 2)});`;
    showCodeSnippet(code);

    notify('info', optionData.message, options);
}

// Función para copiar el código al portapapeles
function copyToClipboard() {
    const codeElement = document.getElementById('js-code');
    if (codeElement) {
        const code = codeElement.textContent;
        navigator.clipboard.writeText(code).then(() => {
            // Mostrar notificación de éxito
            const copyBtn = document.getElementById('copy-code-btn');
            if (copyBtn) {
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="fas fa-check me-1"></i> ¡Copiado!';
                copyBtn.classList.remove('btn-outline-secondary');
                copyBtn.classList.add('btn-success');

                // Restaurar el botón después de 2 segundos
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.classList.remove('btn-success');
                    copyBtn.classList.add('btn-outline-secondary');
                }, 2000);
            }
        }).catch(err => {
            console.error('Error al copiar el código: ', err);
        });
    }
}

// Agregar evento click al botón de copiar
document.addEventListener('DOMContentLoaded', function () {
    const copyBtn = document.getElementById('copy-code-btn');
    if (copyBtn) {
        copyBtn.addEventListener('click', copyToClipboard);
    }
});
