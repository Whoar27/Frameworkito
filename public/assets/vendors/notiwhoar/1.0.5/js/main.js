/**
 * SISTEMA DE NOTIFICACIONES WHOAR (notiwhoar)
 */

// ============================
// CONFIGURACIÓN GLOBAL FALCON
// ============================

const ConfiguracionWhoar = {
    // Configuración de notificaciones toast
    toast: {
        duracionPorDefecto: 4000,
        posicionPorDefecto: 'superior-derecha',
        mostrarProgreso: true,
        permitirCerrar: false, // ← Cambiado a false por defecto
        animacionEntrada: 'deslizar',
        animacionSalida: 'deslizar'
    },

    // Configuración de modales
    modal: {
        cerrarConEscape: true,
        cerrarConOverlay: true,
        tamanoPorDefecto: 'mediano',
        animacion: true
    },

    // Iconos por tipo (Falcon compatible)
    iconos: {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle',
        question: 'fas fa-question-circle',
        loading: 'fas fa-spinner fa-spin'
    },

    // Posiciones disponibles
    posiciones: {
        'superior-derecha': 'top-right',
        'superior-izquierda': 'top-left',
        'superior-centro': 'top-center',
        'inferior-derecha': 'bottom-right',
        'inferior-izquierda': 'bottom-left',
        'inferior-centro': 'bottom-center',
        'centro': 'center'
    },

    // Colores Falcon
    colores: {
        primary: '#2c7be5',
        secondary: '#748194',
        success: '#00d27a',
        info: '#27bcfd',
        warning: '#f5803e',
        danger: '#e63757',
        light: '#f9fafd',
        dark: '#344050'
    }
};

// Variables globales
let contadorNotificaciones = 0;
let modalesActivos = [];
let notificacionesActivas = [];
let instanciasEspera = []; // Nueva variable para manejar instancias de espera

// ============================
// UTILIDADES GENERALES
// ============================

/**
 * Genera un ID único para elementos
 */
function generarId() {
    return `whoar-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
}

/**
 * Obtiene o crea el contenedor de notificaciones
 */
function obtenerContenedorNotificaciones(posicion = 'superior-derecha') {
    const idContenedor = `contenedor-notificaciones-${posicion}`;
    let contenedor = document.getElementById(idContenedor);

    if (!contenedor) {
        contenedor = document.createElement('div');
        contenedor.id = idContenedor;
        contenedor.className = `contenedor-notificaciones-toast posicion-${posicion}`;

        // Establecer posición CSS
        const estilosContenedor = {
            'superior-derecha': { top: '24px', right: '24px' },
            'superior-izquierda': { top: '24px', left: '24px' },
            'superior-centro': { top: '24px', left: '50%', transform: 'translateX(-50%)' },
            'inferior-derecha': { bottom: '24px', right: '24px' },
            'inferior-izquierda': { bottom: '24px', left: '24px' },
            'inferior-centro': { bottom: '24px', left: '50%', transform: 'translateX(-50%)' },
            'centro': { top: '50%', left: '50%', transform: 'translate(-50%, -50%)' }
        };

        const estilos = estilosContenedor[posicion] || estilosContenedor['superior-derecha'];
        Object.assign(contenedor.style, {
            position: 'fixed',
            zIndex: '10000',
            maxWidth: '400px',
            pointerEvents: 'none',
            ...estilos
        });

        document.body.appendChild(contenedor);
    }

    return contenedor;
}

/**
 * Sanitiza texto para prevenir XSS
 */
function sanitizarTexto(texto) {
    const elemento = document.createElement('div');
    elemento.textContent = texto;
    return elemento.innerHTML;
}

/**
 * Valida el tipo de notificación
 */
function validarTipo(tipo) {
    const tiposValidos = ['success', 'error', 'warning', 'info'];
    return tiposValidos.includes(tipo) ? tipo : 'info';
}

// ============================
// FUNCIÓN PRINCIPAL: NOTIFICACION
// ============================

/**
 * Función principal para mostrar notificaciones
 * @param {string} tipo - Tipo de notificación (success, error, warning, info)
 * @param {string} mensaje - Mensaje a mostrar
 * @param {Object} opciones - Opciones adicionales
 */
function notificacion(tipo, mensaje, opciones = {}) {
    // Validar parámetros
    if (!mensaje) {
        console.warn('Whoar: El mensaje es requerido para mostrar una notificación');
        return null;
    }

    tipo = validarTipo(tipo);

    // Configuración por defecto
    const configuracion = {
        duracion: opciones.duracion || ConfiguracionWhoar.toast.duracionPorDefecto,
        posicion: opciones.posicion || ConfiguracionWhoar.toast.posicionPorDefecto,
        mostrarProgreso: opciones.mostrarProgreso !== undefined ? opciones.mostrarProgreso : ConfiguracionWhoar.toast.mostrarProgreso,
        permitirCerrar: opciones.permitirCerrar !== undefined ? opciones.permitirCerrar : ConfiguracionWhoar.toast.permitirCerrar,
        icono: opciones.icono || ConfiguracionWhoar.iconos[tipo],
        autoCerrar: opciones.autoCerrar !== false,
        onCerrar: opciones.onCerrar || null,
        onMostrar: opciones.onMostrar || null
    };

    // Crear elemento de notificación
    const notificacionElemento = crearElementoNotificacion(tipo, mensaje, configuracion);

    // Obtener contenedor
    const contenedor = obtenerContenedorNotificaciones(configuracion.posicion);

    // Agregar al contenedor
    contenedor.appendChild(notificacionElemento);

    // Agregar a la lista de notificaciones activas
    const instanciaNotificacion = {
        id: notificacionElemento.id,
        elemento: notificacionElemento,
        tipo: tipo,
        configuracion: configuracion,
        tiempoCreacion: Date.now()
    };

    // Cerrar todas las notificaciones toast activas antes de mostrar la nueva
    notificacionesActivas.forEach(notificacion => {
        if (notificacion.elemento.classList.contains('notificacion-toast')) {
            cerrarNotificacion(notificacion.id);
        }
    });

    notificacionesActivas.push(instanciaNotificacion);

    // Guardar referencia de la instancia en el elemento para el auto-cierre
    notificacionElemento.instanciaWhoar = instanciaNotificacion;

    // Mostrar con animación
    setTimeout(() => {
        notificacionElemento.classList.add('mostrar');
        if (configuracion.onMostrar) {
            configuracion.onMostrar(instanciaNotificacion);
        }
    }, 100);

    // Configurar auto-cierre
    if (configuracion.autoCerrar && configuracion.duracion > 0) {
        configurarAutoCierre(instanciaNotificacion);
    }

    return instanciaNotificacion;
}

/**
 * Crea el elemento HTML de la notificación
 */
function crearElementoNotificacion(tipo, mensaje, configuracion) {
    const id = generarId();
    const elemento = document.createElement('div');
    elemento.id = id;
    elemento.className = `notificacion-toast ${tipo}`;

    // Estructura HTML
    elemento.innerHTML = `
        <div class="contenido-notificacion">
            <div class="icono-notificacion">
                <i class="${configuracion.icono}"></i>
            </div>
            <div class="mensaje-notificacion">${sanitizarTexto(mensaje)}</div>
            ${configuracion.permitirCerrar ? `
                <button class="boton-cerrar-notificacion" onclick="cerrarNotificacion('${id}')">
                    <i class="fas fa-times"></i>
                </button>
            ` : ''}
        </div>
        ${configuracion.mostrarProgreso ? `
            <div class="barra-progreso-notificacion">
                <div class="progreso-notificacion" id="progreso-${id}"></div>
            </div>
        ` : ''}
    `;

    // Configurar eventos de hover y click
    if (configuracion.autoCerrar && configuracion.duracion > 0) {
        elemento.addEventListener('mouseenter', () => pausarProgreso(id));
        elemento.addEventListener('mouseleave', () => reanudarProgreso(id));
    }

    // Configurar click para cerrar
    elemento.addEventListener('click', (e) => {
        // Solo cerrar si no se hizo clic en el botón de cerrar (si existe)
        if (!e.target.closest('.boton-cerrar-notificacion')) {
            cerrarNotificacion(id);
        }
    });

    // Cambiar cursor para indicar que es clickeable
    elemento.style.cursor = 'pointer';

    return elemento;
}

/**
 * Configura el auto-cierre de la notificación
 */
function configurarAutoCierre(instancia) {
    const { id, configuracion } = instancia;
    const barraProgreso = document.getElementById(`progreso-${id}`);

    if (!barraProgreso) return;

    let tiempoInicio = Date.now();
    let tiempoPausado = 0;

    // Guardar referencias para poder pausar/reanudar
    instancia.controlProgreso = {
        tiempoInicio,
        tiempoPausado,
        pausado: false,
        intervalo: null
    };

    const actualizarProgreso = () => {
        if (instancia.controlProgreso.pausado) return;

        const tiempoTranscurrido = Date.now() - instancia.controlProgreso.tiempoInicio - instancia.controlProgreso.tiempoPausado;
        const porcentaje = Math.max(0, 100 - (tiempoTranscurrido / configuracion.duracion) * 100);

        if (barraProgreso) {
            barraProgreso.style.width = porcentaje + '%';
        }

        if (porcentaje <= 0) {
            clearInterval(instancia.controlProgreso.intervalo);
            cerrarNotificacion(id);
        }
    };

    // Iniciar animación del progreso
    instancia.controlProgreso.intervalo = setInterval(actualizarProgreso, 50);

    // Auto-cerrar después del tiempo especificado
    instancia.timeoutAutoCierre = setTimeout(() => {
        if (!instancia.controlProgreso.pausado) {
            cerrarNotificacion(id);
        }
    }, configuracion.duracion);
}

/**
 * Pausa el progreso de una notificación
 */
function pausarProgreso(id) {
    const elemento = document.getElementById(id);
    const instancia = elemento ? elemento.instanciaWhoar : notificacionesActivas.find(n => n.id === id);

    if (instancia && instancia.controlProgreso && !instancia.controlProgreso.pausado) {
        instancia.controlProgreso.pausado = true;
        instancia.controlProgreso.tiempoPausa = Date.now();

        // Limpiar timeout de auto-cierre
        if (instancia.timeoutAutoCierre) {
            clearTimeout(instancia.timeoutAutoCierre);
            instancia.timeoutAutoCierre = null;
        }
    }
}

/**
 * Reanuda el progreso de una notificación
 */
function reanudarProgreso(id) {
    const elemento = document.getElementById(id);
    const instancia = elemento ? elemento.instanciaWhoar : notificacionesActivas.find(n => n.id === id);

    if (instancia && instancia.controlProgreso && instancia.controlProgreso.pausado) {
        // Calcular tiempo que estuvo pausado
        const tiempoPausadoAhora = Date.now() - instancia.controlProgreso.tiempoPausa;
        instancia.controlProgreso.tiempoPausado += tiempoPausadoAhora;
        instancia.controlProgreso.pausado = false;

        // Recalcular tiempo restante
        const tiempoTranscurrido = Date.now() - instancia.controlProgreso.tiempoInicio - instancia.controlProgreso.tiempoPausado;
        const tiempoRestante = instancia.configuracion.duracion - tiempoTranscurrido;

        if (tiempoRestante > 0) {
            // Reestablecer timeout con el tiempo restante
            instancia.timeoutAutoCierre = setTimeout(() => {
                cerrarNotificacion(id);
            }, tiempoRestante);
        } else {
            // Si ya pasó el tiempo, cerrar inmediatamente
            cerrarNotificacion(id);
        }
    }
}

/**
 * Cierra una notificación específica
 */
function cerrarNotificacion(id) {
    const instancia = notificacionesActivas.find(n => n.id === id);
    if (!instancia) return;

    const { elemento, configuracion } = instancia;

    // Limpiar timeouts e intervalos
    if (instancia.timeoutAutoCierre) {
        clearTimeout(instancia.timeoutAutoCierre);
    }

    if (instancia.controlProgreso && instancia.controlProgreso.intervalo) {
        clearInterval(instancia.controlProgreso.intervalo);
    }

    // Animar salida
    elemento.classList.remove('mostrar');
    elemento.classList.add('ocultar');

    // Remover después de la animación
    setTimeout(() => {
        if (elemento.parentNode) {
            elemento.parentNode.removeChild(elemento);
        }

        // Remover de la lista de notificaciones activas
        const indice = notificacionesActivas.findIndex(n => n.id === id);
        if (indice > -1) {
            notificacionesActivas.splice(indice, 1);
        }

        // Ejecutar callback de cierre
        if (configuracion.onCerrar) {
            configuracion.onCerrar(instancia);
        }
    }, 400);
}

// ============================
// SISTEMA DE CONFIRMACIONES
// ============================

/**
 * Función principal para confirmaciones
 * @param {Object} opciones - Configuración de la confirmación
 * @returns {Promise<boolean>} - Resuelve con true si confirma, false si cancela
 */
function confirmacion(opciones = {}) {
    return new Promise((resolve) => {
        // Configuración por defecto
        const config = {
            tipo: opciones.tipo || 'info',
            icono: opciones.icono || ConfiguracionWhoar.iconos[opciones.tipo || 'question'],
            titulo: opciones.titulo || '¿Confirmar acción?',
            contenido: opciones.contenido || opciones.texto || '¿Estás seguro de que quieres continuar?',
            html: opciones.html || false,

            // Configuración de botones
            activarBtnConfirmar: opciones.activarBtnConfirmar !== false,
            textoBtnConfirmar: opciones.textoBtnConfirmar || 'Confirmar',
            iconoBtnConfirmar: opciones.iconoBtnConfirmar || 'fas fa-check',

            activarBtnCancelar: opciones.activarBtnCancelar !== false,
            textoBtnCancelar: opciones.textoBtnCancelar || 'Cancelar',
            iconoBtnCancelar: opciones.iconoBtnCancelar || 'fas fa-times',

            // Botón de denegar (opcional)
            activarBtnDenegar: opciones.activarBtnDenegar || false,
            textoBtnDenegar: opciones.textoBtnDenegar || 'No guardar',
            iconoBtnDenegar: opciones.iconoBtnDenegar || 'fas fa-ban',

            // Configuración del modal
            cerrarConEscape: opciones.cerrarConEscape !== false,
            cerrarConOverlay: opciones.cerrarConOverlay !== false,
            tamano: opciones.tamano || 'mediano',

            // Callbacks
            onConfirmar: opciones.onConfirmar || null,
            onCancelar: opciones.onCancelar || null,
            onDenegar: opciones.onDenegar || null
        };

        // Crear modal de confirmación
        const modalConfirmacion = crearModalConfirmacion(config, resolve);

        // Mostrar modal
        mostrarModal(modalConfirmacion);
    });
}

/**
 * Crea el modal de confirmación
 */
function crearModalConfirmacion(config, resolver) {
    const id = generarId();

    // Crear overlay
    const overlay = document.createElement('div');
    overlay.className = 'overlay-modal';
    overlay.id = id;

    // Crear contenedor del modal
    const contenedor = document.createElement('div');
    contenedor.className = `contenedor-modal ${config.tamano}`;

    // Crear estructura HTML
    contenedor.innerHTML = `
        <div class="encabezado-modal">
            <h3 class="titulo-modal">
                <i class="${config.icono}"></i>
                ${config.titulo}
            </h3>
            <button class="boton-cerrar-modal" onclick="cerrarModalConfirmacion('${id}', false)">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cuerpo-modal">
            ${config.html ? config.contenido : `<p>${sanitizarTexto(config.contenido)}</p>`}
        </div>
        <div class="pie-modal">
            ${config.activarBtnCancelar ? `
                <button class="boton-modal secundario" onclick="cerrarModalConfirmacion('${id}', 'cancelar')">
                    <i class="${config.iconoBtnCancelar}"></i>
                    ${config.textoBtnCancelar}
                </button>
            ` : ''}
            ${config.activarBtnDenegar ? `
                <button class="boton-modal peligro" onclick="cerrarModalConfirmacion('${id}', 'denegar')">
                    <i class="${config.iconoBtnDenegar}"></i>
                    ${config.textoBtnDenegar}
                </button>
            ` : ''}
            ${config.activarBtnConfirmar ? `
                <button class="boton-modal primario" onclick="cerrarModalConfirmacion('${id}', true)">
                    <i class="${config.iconoBtnConfirmar}"></i>
                    ${config.textoBtnConfirmar}
                </button>
            ` : ''}
        </div>
    `;

    overlay.appendChild(contenedor);

    // Configurar eventos
    if (config.cerrarConOverlay) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                cerrarModalConfirmacion(id, false);
            }
        });
    }

    if (config.cerrarConEscape) {
        const manejarEscape = (e) => {
            if (e.key === 'Escape') {
                cerrarModalConfirmacion(id, false);
                document.removeEventListener('keydown', manejarEscape);
            }
        };
        document.addEventListener('keydown', manejarEscape);
        overlay.dataset.escapeHandler = 'true';
    }

    // Guardar configuración y resolver en el elemento
    overlay.dataset.config = JSON.stringify(config);
    overlay.resolver = resolver;

    return overlay;
}

/**
 * Cierra un modal de confirmación
 */
function cerrarModalConfirmacion(id, resultado) {
    const modal = document.getElementById(id);
    if (!modal) return;

    const config = JSON.parse(modal.dataset.config);
    const resolver = modal.resolver;

    // Ejecutar callbacks específicos
    if (resultado === true && config.onConfirmar) {
        config.onConfirmar();
    } else if (resultado === 'cancelar' && config.onCancelar) {
        config.onCancelar();
    } else if (resultado === 'denegar' && config.onDenegar) {
        config.onDenegar();
    }

    // Animar salida y remover
    modal.classList.remove('mostrar');

    setTimeout(() => {
        if (modal.parentNode) {
            modal.parentNode.removeChild(modal);
        }
        document.body.classList.remove('sin-scroll');

        // Remover de la lista de modales activos
        const indice = modalesActivos.findIndex(m => m.id === id);
        if (indice > -1) {
            modalesActivos.splice(indice, 1);
        }
    }, 300);

    // Resolver la promesa
    if (resultado === 'denegar') {
        resolver('denegar');
    } else {
        resolver(resultado === true);
    }
}

// ============================
// SISTEMA DE MODALES GENERALES
// ============================

/**
 * Función principal para modales
 * @param {Object} opciones - Configuración del modal
 * @returns {Object} - Instancia del modal
 */
function modal(opciones = {}) {
    const config = {
        titulo: opciones.titulo || 'Modal',
        contenido: opciones.contenido || '',
        html: opciones.html || false,
        tamano: opciones.tamano || 'mediano',

        // Configuración de comportamiento
        cerrarConEscape: opciones.cerrarConEscape !== false,
        cerrarConOverlay: opciones.cerrarConOverlay !== false,
        mostrarBtnCerrar: opciones.mostrarBtnCerrar !== false,

        // Botones personalizados
        botones: opciones.botones || [],

        // Callbacks
        onAbrir: opciones.onAbrir || null,
        onCerrar: opciones.onCerrar || null
    };

    const modalElemento = crearModalGeneral(config);
    mostrarModal(modalElemento);

    return {
        id: modalElemento.id,
        elemento: modalElemento,
        cerrar: () => cerrarModal(modalElemento.id),
        actualizar: (nuevoContenido) => actualizarContenidoModal(modalElemento.id, nuevoContenido)
    };
}

/**
 * Crea un modal general
 */
function crearModalGeneral(config) {
    const id = generarId();

    // Crear overlay
    const overlay = document.createElement('div');
    overlay.className = 'overlay-modal';
    overlay.id = id;

    // Crear contenedor del modal
    const contenedor = document.createElement('div');
    contenedor.className = `contenedor-modal ${config.tamano}`;

    // Crear botones personalizados
    let botonesHTML = '';
    if (config.botones.length > 0) {
        botonesHTML = config.botones.map(boton => {
            const tipo = boton.tipo || 'secundario';
            const accion = boton.accion === 'cerrar' ? `cerrarModal('${id}')` :
                typeof boton.accion === 'string' ? boton.accion :
                    `ejecutarAccionBoton('${id}', ${config.botones.indexOf(boton)})`;

            return `
                <button class="boton-modal ${tipo}" onclick="${accion}">
                    ${boton.icono ? `<i class="${boton.icono}"></i>` : ''}
                    ${boton.texto}
                </button>
            `;
        }).join('');
    }

    // Crear estructura HTML
    contenedor.innerHTML = `
        <div class="encabezado-modal">
            <h3 class="titulo-modal">
                ${config.titulo}
            </h3>
            ${config.mostrarBtnCerrar ? `
                <button class="boton-cerrar-modal" onclick="cerrarModal('${id}')">
                    <i class="fas fa-times"></i>
                </button>
            ` : ''}
        </div>
        <div class="cuerpo-modal" id="cuerpo-${id}">
            ${config.html ? config.html : sanitizarTexto(config.contenido)}
        </div>
        ${config.botones.length > 0 ? `
            <div class="pie-modal">
                ${botonesHTML}
            </div>
        ` : ''}
    `;

    overlay.appendChild(contenedor);

    // Configurar eventos
    if (config.cerrarConOverlay) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                cerrarModal(id);
            }
        });
    }

    if (config.cerrarConEscape) {
        const manejarEscape = (e) => {
            if (e.key === 'Escape') {
                cerrarModal(id);
                document.removeEventListener('keydown', manejarEscape);
            }
        };
        document.addEventListener('keydown', manejarEscape);
        overlay.dataset.escapeHandler = 'true';
    }

    // Guardar configuración
    overlay.dataset.config = JSON.stringify(config);

    return overlay;
}

/**
 * Muestra un modal
 */
function mostrarModal(modalElemento) {
    document.body.appendChild(modalElemento);
    document.body.classList.add('sin-scroll');

    // Agregar a la lista de modales activos
    modalesActivos.push({
        id: modalElemento.id,
        elemento: modalElemento
    });

    // Mostrar con animación
    setTimeout(() => {
        modalElemento.classList.add('mostrar');

        // Ejecutar callback de apertura
        const config = JSON.parse(modalElemento.dataset.config);
        if (config.onAbrir) {
            config.onAbrir(modalElemento);
        }
    }, 10);
}

/**
 * Cierra un modal
 */
function cerrarModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;

    const config = JSON.parse(modal.dataset.config);

    // Ejecutar callback de cierre
    if (config.onCerrar) {
        config.onCerrar(modal);
    }

    // Animar salida
    modal.classList.remove('mostrar');

    setTimeout(() => {
        if (modal.parentNode) {
            modal.parentNode.removeChild(modal);
        }
        document.body.classList.remove('sin-scroll');

        // Remover de la lista de modales activos
        const indice = modalesActivos.findIndex(m => m.id === id);
        if (indice > -1) {
            modalesActivos.splice(indice, 1);
        }
    }, 300);
}

/**
 * Actualiza el contenido de un modal
 */
function actualizarContenidoModal(id, nuevoContenido) {
    const cuerpoModal = document.getElementById(`cuerpo-${id}`);
    if (cuerpoModal) {
        cuerpoModal.innerHTML = nuevoContenido;
    }
}

/**
 * Ejecuta la acción de un botón personalizado
 */
function ejecutarAccionBoton(modalId, indiceBoton) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const config = JSON.parse(modal.dataset.config);
    const boton = config.botones[indiceBoton];

    if (boton && typeof boton.accion === 'function') {
        boton.accion(modal);
    }
}

// ============================
// NOTIFICACIONES DE ESPERA - CORREGIDAS
// ============================

/**
 * Notificación con tiempo de espera y progreso
 * @param {Object} opciones - Configuración de la notificación de espera
 * @returns {Object} - Instancia de la notificación
 */
function notificacionEspera(opciones = {}) {
    const config = {
        titulo: opciones.titulo || 'Por favor, espere...',
        mensaje: opciones.mensaje || 'Procesando...',
        tiempo: opciones.tiempo || 5000,
        mostrarProgreso: opciones.mostrarProgreso !== false,
        cancelable: opciones.cancelable || false,
        tipo: opciones.tipo || 'info',
        icono: opciones.icono || 'fas fa-clock',
        posicion: opciones.posicion || 'centro',
        onComplete: opciones.onComplete || null,
        onCancel: opciones.onCancel || null,
        onTick: opciones.onTick || null
    };

    // Si la posición es centro, usar modal; sino, usar toast
    if (config.posicion === 'centro') {
        return crearModalEspera(config);
    } else {
        return crearToastEspera(config);
    }
}

/**
 * Crea un modal de espera - CORREGIDO
 */
function crearModalEspera(config) {
    const id = generarId();

    const overlay = document.createElement('div');
    overlay.className = 'overlay-modal';
    overlay.id = id;

    const contenedor = document.createElement('div');
    contenedor.className = 'contenedor-modal pequeno';

    let tiempoRestante = config.tiempo / 1000;

    contenedor.innerHTML = `
        <div class="cuerpo-modal" style="text-align: center; padding: 32px;">
            <h3 class="titulo-modal" style="display: inline-flex; align-items: center; justify-content: center; position: relative; margin-left: 24px;">
                <i class="${config.icono}" style="position: absolute; left: -24px; margin-right:0.5em;"></i> <span style="margin-left:.5em;">${config.titulo || 'Procesando'}</span>
            </h3>
            <p style="margin-top:16px; margin-bottom: 16px; color: var(--texto-primario); font-size: 16px;">${config.mensaje}</p>
            ${config.mostrarProgreso ? `
                <div style="background: #f1f5f9; border-radius: 8px; height: 8px; margin: 16px 0; overflow: hidden;">
                    <div id="barra-progreso-${id}" style="background: var(--gradiente-info); height: 100%; width: 100%; transition: width 0.1s linear;"></div>
                </div>
                <p id="tiempo-restante-${id}" style="color: var(--texto-secundario); margin: 8px 0;">Tiempo restante: ${tiempoRestante}s</p>
            ` : ''}
            ${config.cancelable ? `
                <button class="boton-modal secundario" onclick="cancelarEspera('${id}')" style="margin-top: 16px;">
                    <i class="fas fa-times"></i>
                    Cancelar
                </button>
            ` : ''}
        </div>
    `;

    overlay.appendChild(contenedor);
    document.body.appendChild(overlay);
    document.body.classList.add('sin-scroll');

    setTimeout(() => overlay.classList.add('mostrar'), 10);

    // Configurar progreso - CORREGIDO
    const instancia = {
        id: id,
        elemento: overlay,
        config: config,
        tiempoInicio: Date.now(),
        cancelado: false,
        completado: false
    };

    // Agregar a lista de instancias de espera
    instanciasEspera.push(instancia);

    if (config.mostrarProgreso) {
        configurarProgresoEspera(instancia);
    }

    // Auto-completar - CORREGIDO
    instancia.timeout = setTimeout(() => {
        completarEspera(id);
    }, config.tiempo);

    return instancia;
}

/**
 * Crea un toast de espera - CORREGIDO
 */
function crearToastEspera(config) {
    const id = generarId();
    let tiempoRestante = config.tiempo / 1000;

    const elemento = document.createElement('div');
    elemento.id = id;
    elemento.className = `notificacion-toast ${config.tipo}`;

    elemento.innerHTML = `
        <div class="contenido-notificacion">
            <div class="icono-notificacion">
                <i class="${config.icono}"></i>
            </div>
            <div class="mensaje-notificacion">
                ${config.mensaje}
                ${config.mostrarProgreso ? `<br><small id="tiempo-${id}">Tiempo restante: ${tiempoRestante}s</small>` : ''}
            </div>
            ${config.cancelable ? `
                <button class="boton-cerrar-notificacion" onclick="cancelarEspera('${id}')">
                    <i class="fas fa-times"></i>
                </button>
            ` : ''}
        </div>
        ${config.mostrarProgreso ? `
            <div class="barra-progreso-notificacion">
                <div class="progreso-notificacion" id="progreso-espera-${id}"></div>
            </div>
        ` : ''}
    `;

    const contenedor = obtenerContenedorNotificaciones(config.posicion);
    contenedor.appendChild(elemento);

    setTimeout(() => elemento.classList.add('mostrar'), 100);

    const instancia = {
        id: id,
        elemento: elemento,
        config: config,
        tiempoInicio: Date.now(),
        cancelado: false,
        completado: false
    };

    // Agregar a lista de instancias de espera
    instanciasEspera.push(instancia);

    if (config.mostrarProgreso) {
        configurarProgresoEspera(instancia);
    }

    instancia.timeout = setTimeout(() => {
        completarEspera(id);
    }, config.tiempo);

    return instancia;
}

/**
 * Configura el progreso de la notificación de espera - CORREGIDO
 */
function configurarProgresoEspera(instancia) {
    const { id, config } = instancia;
    const barraProgreso = document.getElementById(`progreso-espera-${id}`) || document.getElementById(`barra-progreso-${id}`);
    const textoTiempo = document.getElementById(`tiempo-${id}`) || document.getElementById(`tiempo-restante-${id}`);

    const intervalo = setInterval(() => {
        if (instancia.cancelado || instancia.completado) {
            clearInterval(intervalo);
            return;
        }

        const tiempoTranscurrido = Date.now() - instancia.tiempoInicio;
        const porcentaje = Math.max(0, 100 - (tiempoTranscurrido / config.tiempo) * 100);
        const tiempoRestante = Math.max(0, Math.ceil((config.tiempo - tiempoTranscurrido) / 1000));

        if (barraProgreso) {
            barraProgreso.style.width = porcentaje + '%';
        }

        if (textoTiempo) {
            textoTiempo.textContent = `Tiempo restante: ${tiempoRestante}s`;
        }

        // Ejecutar callback de tick
        if (config.onTick) {
            config.onTick(tiempoRestante, porcentaje);
        }

        if (porcentaje <= 0) {
            clearInterval(intervalo);
        }
    }, 100);

    instancia.intervalo = intervalo;
}

/**
 * Completa una notificación de espera - CORREGIDO
 */
function completarEspera(id) {
    const instanciaIndex = instanciasEspera.findIndex(i => i.id === id);
    if (instanciaIndex === -1) return;

    const instancia = instanciasEspera[instanciaIndex];
    const elemento = document.getElementById(id);

    if (!elemento || instancia.completado || instancia.cancelado) return;

    // Marcar como completado
    instancia.completado = true;

    // Limpiar intervalos
    if (instancia.intervalo) {
        clearInterval(instancia.intervalo);
    }

    if (instancia.timeout) {
        clearTimeout(instancia.timeout);
    }

    // Ejecutar callback de completado ANTES de remover el elemento
    if (instancia.config.onComplete) {
        try {
            instancia.config.onComplete();
        } catch (error) {
            console.error('Error ejecutando onComplete:', error);
        }
    }

    // Remover elemento
    if (elemento.classList.contains('overlay-modal')) {
        // Es un modal
        elemento.classList.remove('mostrar');
        setTimeout(() => {
            if (elemento.parentNode) {
                elemento.parentNode.removeChild(elemento);
                document.body.classList.remove('sin-scroll');
            }
        }, 300);
    } else {
        // Es un toast
        elemento.classList.remove('mostrar');
        elemento.classList.add('ocultar');
        setTimeout(() => {
            if (elemento.parentNode) {
                elemento.parentNode.removeChild(elemento);
            }
        }, 400);
    }

    // Remover de la lista de instancias de espera
    instanciasEspera.splice(instanciaIndex, 1);
}

/**
 * Cancela una notificación de espera - CORREGIDO
 */
function cancelarEspera(id) {
    const instanciaIndex = instanciasEspera.findIndex(i => i.id === id);
    if (instanciaIndex === -1) return;

    const instancia = instanciasEspera[instanciaIndex];
    const elemento = document.getElementById(id);

    if (!elemento || instancia.cancelado || instancia.completado) return;

    // Marcar como cancelado
    instancia.cancelado = true;

    // Limpiar intervalos
    if (instancia.intervalo) {
        clearInterval(instancia.intervalo);
    }

    if (instancia.timeout) {
        clearTimeout(instancia.timeout);
    }

    // Ejecutar callback de cancelación
    if (instancia.config.onCancel) {
        try {
            instancia.config.onCancel();
        } catch (error) {
            console.error('Error ejecutando onCancel:', error);
        }
    }

    // Remover elemento
    if (elemento.classList.contains('overlay-modal')) {
        elemento.classList.remove('mostrar');
        setTimeout(() => {
            if (elemento.parentNode) {
                elemento.parentNode.removeChild(elemento);
                document.body.classList.remove('sin-scroll');
            }
        }, 300);
    } else {
        elemento.classList.remove('mostrar');
        elemento.classList.add('ocultar');
        setTimeout(() => {
            if (elemento.parentNode) {
                elemento.parentNode.removeChild(elemento);
            }
        }, 400);
    }

    // Remover de la lista de instancias de espera
    instanciasEspera.splice(instanciaIndex, 1);
}

// ============================
// SISTEMA DE ENTRADAS DE USUARIO
// ============================

/**
 * Solicita entrada de texto al usuario
 * @param {Object} opciones - Configuración de la entrada
 * @returns {Promise<string|null>} - Resuelve con el valor ingresado o null si cancela
 */
function solicitarEntrada(opciones = {}) {
    return new Promise((resolve) => {
        const config = {
            titulo: opciones.titulo || 'Ingrese información',
            mensaje: opciones.mensaje || 'Por favor, ingrese el valor solicitado:',
            tipo: opciones.tipo || 'text', // text, email, password, number, textarea, select
            placeholder: opciones.placeholder || '',
            valorInicial: opciones.valorInicial || '',
            requerido: opciones.requerido !== false,
            validacion: opciones.validacion || null,
            opciones: opciones.opciones || [], // Para select
            icono: opciones.icono || 'fas fa-edit',

            // Configuración de botones
            textoBtnConfirmar: opciones.textoBtnConfirmar || 'Aceptar',
            textoBtnCancelar: opciones.textoBtnCancelar || 'Cancelar'
        };

        const modalEntrada = crearModalEntrada(config, resolve);
        mostrarModal(modalEntrada);
    });
}

/**
 * Crea el modal de entrada
 */
function crearModalEntrada(config, resolver) {
    const id = generarId();

    const overlay = document.createElement('div');
    overlay.className = 'overlay-modal';
    overlay.id = id;

    const contenedor = document.createElement('div');
    contenedor.className = 'contenedor-modal mediano';

    // Crear campo de entrada según el tipo
    let campoEntrada = '';

    switch (config.tipo) {
        case 'textarea':
            campoEntrada = `
                <textarea 
                    id="entrada-${id}" 
                    placeholder="${config.placeholder}"
                    style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit; resize: vertical; min-height: 100px;"
                    ${config.requerido ? 'required' : ''}
                >${config.valorInicial}</textarea>
            `;
            break;

        case 'select':
            const opciones = config.opciones.map(opcion => {
                const valor = typeof opcion === 'object' ? opcion.valor : opcion;
                const texto = typeof opcion === 'object' ? opcion.texto : opcion;
                const seleccionado = valor === config.valorInicial ? 'selected' : '';
                return `<option value="${valor}" ${seleccionado}>${texto}</option>`;
            }).join('');

            campoEntrada = `
                <select 
                    id="entrada-${id}"
                    style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit;"
                    ${config.requerido ? 'required' : ''}
                >
                    ${config.placeholder ? `<option value="">${config.placeholder}</option>` : ''}
                    ${opciones}
                </select>
            `;
            break;

        default:
            campoEntrada = `
                <input 
                    type="${config.tipo}" 
                    id="entrada-${id}" 
                    placeholder="${config.placeholder}"
                    value="${config.valorInicial}"
                    style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-family: inherit;"
                    ${config.requerido ? 'required' : ''}
                />
            `;
    }

    contenedor.innerHTML = `
        <div class="encabezado-modal">
            <h3 class="titulo-modal">
                <i class="${config.icono}"></i>
                ${config.titulo}
            </h3>
            <button class="boton-cerrar-modal" onclick="cerrarModalEntrada('${id}', null)">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cuerpo-modal">
            <p style="margin-bottom: 16px; color: var(--texto-secundario);">${config.mensaje}</p>
            ${campoEntrada}
            <div id="error-${id}" style="color: #ef4444; margin-top: 8px; font-size: 14px; display: none;"></div>
        </div>
        <div class="pie-modal">
            <button class="boton-modal secundario" onclick="cerrarModalEntrada('${id}', null)">
                <i class="fas fa-times"></i>
                ${config.textoBtnCancelar}
            </button>
            <button class="boton-modal primario" onclick="confirmarEntrada('${id}')">
                <i class="fas fa-check"></i>
                ${config.textoBtnConfirmar}
            </button>
        </div>
    `;

    overlay.appendChild(contenedor);

    // Configurar eventos
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            cerrarModalEntrada(id, null);
        }
    });

    const manejarEscape = (e) => {
        if (e.key === 'Escape') {
            cerrarModalEntrada(id, null);
            document.removeEventListener('keydown', manejarEscape);
        }
    };
    document.addEventListener('keydown', manejarEscape);

    // Guardar configuración y resolver
    overlay.dataset.config = JSON.stringify(config);
    overlay.resolver = resolver;

    return overlay;
}

/**
 * Confirma la entrada del usuario
 */
function confirmarEntrada(id) {
    const modal = document.getElementById(id);
    if (!modal) return;

    const config = JSON.parse(modal.dataset.config);
    const campo = document.getElementById(`entrada-${id}`);
    const errorDiv = document.getElementById(`error-${id}`);

    const valor = campo.value.trim();

    // Validar campo requerido
    if (config.requerido && !valor) {
        mostrarErrorEntrada(errorDiv, 'Este campo es requerido');
        campo.focus();
        return;
    }

    // Validación personalizada
    if (config.validacion && typeof config.validacion === 'function') {
        const resultadoValidacion = config.validacion(valor);
        if (resultadoValidacion !== true) {
            mostrarErrorEntrada(errorDiv, resultadoValidacion);
            campo.focus();
            return;
        }
    }

    // Validaciones específicas por tipo
    if (config.tipo === 'email' && valor) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(valor)) {
            mostrarErrorEntrada(errorDiv, 'Por favor, ingrese un email válido');
            campo.focus();
            return;
        }
    }

    cerrarModalEntrada(id, valor);
}

/**
 * Muestra un error de validación
 */
function mostrarErrorEntrada(errorDiv, mensaje) {
    errorDiv.textContent = mensaje;
    errorDiv.style.display = 'block';

    // Ocultar error después de 5 segundos
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}

/**
 * Cierra el modal de entrada
 */
function cerrarModalEntrada(id, valor) {
    const modal = document.getElementById(id);
    if (!modal) return;

    const resolver = modal.resolver;

    modal.classList.remove('mostrar');

    setTimeout(() => {
        if (modal.parentNode) {
            modal.parentNode.removeChild(modal);
        }
        document.body.classList.remove('sin-scroll');
    }, 300);

    resolver(valor);
}

// ============================
// FUNCIONES DE CONVENIENCIA
// ============================

/**
 * Shortcuts para tipos específicos
 */
function exito(mensaje, opciones = {}) {
    return notificacion('success', mensaje, { ...opciones, icono: opciones.icono || ConfiguracionWhoar.iconos.success });
}

function error(mensaje, opciones = {}) {
    return notificacion('error', mensaje, { ...opciones, icono: opciones.icono || ConfiguracionWhoar.iconos.error });
}

function advertencia(mensaje, opciones = {}) {
    return notificacion('warning', mensaje, { ...opciones, icono: opciones.icono || ConfiguracionWhoar.iconos.warning });
}

function informacion(mensaje, opciones = {}) {
    return notificacion('info', mensaje, { ...opciones, icono: opciones.icono || ConfiguracionWhoar.iconos.info });
}

/**
 * Cierra todas las notificaciones
 */
function cerrarTodas() {
    notificacionesActivas.forEach((instancia) => {
        cerrarNotificacion(instancia.id);
    });
}

/**
 * Obtiene el número de notificaciones activas
 */
function contarNotificacionesActivas() {
    return notificacionesActivas.length;
}

/**
 * Limpia todos los contenedores de notificaciones
 */
function limpiarContenedores() {
    Object.values(ConfiguracionWhoar.posiciones).forEach(posicion => {
        const contenedor = document.getElementById(`contenedor-notificaciones-${posicion}`);
        if (contenedor) {
            contenedor.remove();
        }
    });
}

/**
 * Configurar posición por defecto
 */
function configurarPosicionPorDefecto(nuevaPosicion) {
    if (ConfiguracionWhoar.posiciones[nuevaPosicion]) {
        ConfiguracionWhoar.toast.posicionPorDefecto = nuevaPosicion;
    }
}

/**
 * Configurar duración por defecto
 */
function configurarDuracionPorDefecto(nuevaDuracion) {
    if (nuevaDuracion > 0) {
        ConfiguracionWhoar.toast.duracionPorDefecto = nuevaDuracion;
    }
}

// ============================
// INICIALIZACIÓN Y VERIFICACIÓN
// ============================

/**
 * Inicialización automática cuando el DOM esté listo
 */
document.addEventListener('DOMContentLoaded', function () {
    // Verificar dependencias
    if (typeof window === 'undefined') {
        console.error('Whoar: Entorno de navegador requerido');
        return;
    }

    // Crear estilos base si no existen
    if (!document.getElementById('whoar-styles')) {
        const estilosBase = document.createElement('style');
        estilosBase.id = 'whoar-styles';
        estilosBase.textContent = `
            .sin-scroll { overflow: hidden !important; }
            .contenedor-notificaciones-toast { pointer-events: none; }
            .notificacion-toast { pointer-events: auto; }
        `;
        document.head.appendChild(estilosBase);
    }
});

// ============================
// EXPORTACIÓN GLOBAL
// ============================

// Exportar funciones para uso global
window.Whoar = {
    // Funciones principales
    notificacion,
    confirmacion,
    modal,
    notificacionEspera,
    solicitarEntrada,

    // Shortcuts
    exito,
    error,
    advertencia,
    informacion,

    // Gestión
    cerrarNotificacion,
    cerrarTodas,
    contarNotificacionesActivas,
    limpiarContenedores,

    // Configuración
    configuracion: ConfiguracionWhoar,
    configurarPosicionPorDefecto,
    configurarDuracionPorDefecto,

    // Funciones de espera específicas
    completarEspera,
    cancelarEspera
};

// Compatibilidad con sistemas AMD/CommonJS
if (typeof define === 'function' && define.amd) {
    define([], function () { return window.Whoar; });
} else if (typeof module !== 'undefined' && module.exports) {
    module.exports = window.Whoar;
}