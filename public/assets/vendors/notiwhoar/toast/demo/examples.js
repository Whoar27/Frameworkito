/**
 * Ejemplos de notificaciones para la demo
 */

// Objeto con ejemplos de notificaciones
const Examples = {
    // Tipos de notificaciones
    types: {
        default: {
            message: "Esta es una notificación de tipo default"
        },
        success: {
            message: "La operación se ha completado correctamente"
        },
        danger: {
            message: "Ha ocurrido un error durante la operación"
        },
        info: {
            message: "Esta es una notificación informativa"
        },
        warning: {
            message: "Tenga precaución al realizar esta acción"
        },
        custom: {
            message: "Esta es una notificación personalizada de tipo custom"
        }
    },

    // Tipos de notificaciones con iconos
    typesWithIcon: {
        default: {
            // title: "Default",
            message: "Notificación con icono xs",
            dismiss: {
                showTypeIcon: true,
                iconSize: 'xs'
            }
        },
        success: {
            // title: "Success",
            message: "Notificación con icono sm",
            dismiss: {
                showTypeIcon: true,
                iconSize: 'sm'
            }
        },
        danger: {
            // title: "Danger",
            message: "Notificación con icono md",
            dismiss: {
                showTypeIcon: true,
                iconSize: 'md'
            }
        },
        info: {
            // title: "Info",
            message: "Notificación con icono lg",
            dismiss: {
                showTypeIcon: true,
                iconSize: 'lg'
            }
        },
        warning: {
            // title: "Warning",
            message: "Notificación con icono",
            dismiss: {
                showTypeIcon: true,
                iconSize: 'md'
            }
        },
        custom: {
            // title: "Custom",
            message: "Notificación con icono",
            dismiss: {
                showTypeIcon: true,
                iconSize: 'md'
            }
        }
    },

    // Tipos de notificaciones sin border-left
    typesWithoutBorderLeft: {
        default: {
            message: "Esta es una notificación de tipo default",
            backgroundColor: '#ffff',
            colorText: '#232E3C',
            iconColor: '#007bff',
            icon: `<svg viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM4.99 10.32c-.35 0-.64-.17-.64-.49 0-.38.38-.69.64-.89.25-.27.27-.77.28-1.12.03-1.43.39-2.32 1.17-2.69.15-.49.59-.82 1.16-.82s1.01.33 1.16.82c.78.37 1.14 1.26 1.17 2.69.01.35.03.85.28 1.12.26.2.64.51.64.89 0 .32-.29.49-.64.49H4.99zM8 11.82c-.64 0-1.05-.45-1.09-1.01h2.18c-.04.56-.45 1.01-1.09 1.01z"></path>
            </svg>
            `
        },
        success: {
            message: "La operación se ha completado correctamente",
            backgroundColor: '#d4edda',
            colorText: '#155724',
            iconColor: '#28a745',
            icon: `<svg viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
            </svg>
            `
        },
        danger: {
            message: "Ha ocurrido un error durante la operación",
            backgroundColor: '#f8d7da',
            colorText: '#721c24',
            iconColor: '#dc3545',
            icon: `<svg viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
            </svg>
            `
        },
        info: {
            message: "Esta es una notificación informativa",
            backgroundColor: '#d1ecf1',
            colorText: '#0c5460',
            iconColor: '#17a2b8',
            icon: `<svg viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8ZM8 7C8.27614 7 8.5 7.22386 8.5 7.5V11C8.5 11.2761 8.27614 11.5 8 11.5C7.72386 11.5 7.5 11.2761 7.5 11V7.5C7.5 7.22386 7.72386 7 8 7ZM8.375 5.25C8.58211 5.02289 8.56406 4.66406 8.33695 4.45695C8.10984 4.24984 7.75101 4.26789 7.5439 4.495L7.53594 4.50352C7.32883 4.73063 7.34688 5.08946 7.57399 5.29657C7.8011 5.50368 8.15993 5.48563 8.36704 5.25852L8.375 5.25Z"></path>
            </svg>
            `
        },
        warning: {
            message: "Tenga precaución al realizar esta acción",
            backgroundColor: '#fff3cd',
            colorText: '#856404',
            iconColor: '#ffc107',
            icon: `<svg viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 5zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
            </svg>
            `
        },
        custom: {
            message: "Esta es una notificación personalizada de tipo custom",
            backgroundColor: '#f5f5f5',
            colorText: '#232E3C',
            iconColor: '#7b68ee',
            icon: `<svg viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm0 2.5c.15 0 .3.08.38.22l1.35 2.78 3.07.45c.17.02.31.14.37.3s.02.34-.1.46l-2.22 2.17.52 3.07c.03.17-.04.34-.17.44-.13.1-.3.11-.44.03L8 11.2l-2.75 1.45c-.15.08-.32.07-.44-.03-.13-.1-.2-.27-.17-.44l.52-3.07L2.94 6.94c-.12-.12-.16-.3-.1-.46s.2-.28.37-.3l3.07-.45L7.62 2.95c.08-.16.23-.24.38-.24z"></path>
            </svg>
            `
        }
    },

    // Posiciones de notificaciones
    positions: {
        topLeft: {
            title: "Top Left",
            message: "Notificación en la esquina superior izquierda"
        },
        topRight: {
            title: "Top Right",
            message: "Notificación en la esquina superior derecha"
        },
        topCenter: {
            title: "Top Center",
            message: "Notificación en la parte superior central"
        },
        bottomLeft: {
            title: "Bottom Left",
            message: "Notificación en la esquina inferior izquierda"
        },
        bottomRight: {
            title: "Bottom Right",
            message: "Notificación en la esquina inferior derecha"
        },
        bottomCenter: {
            title: "Bottom Center",
            message: "Notificación en la parte inferior central"
        },
        center: {
            title: "Center",
            message: "Notificación en el centro de la pantalla"
        },
        topFull: {
            title: "Top Full",
            message: "Notificación en la parte superior de la pantalla",
            animationIn: ["notification-slide-in-top"],
            animationOut: ["notification-fade-out"]
        },
        bottomFull: {
            title: "Bottom Full",
            message: "Notificación en la parte inferior de la pantalla",
            animationIn: ["notification-slide-in-bottom"],
            animationOut: ["notification-fade-out"]
        },
    },

    // Animaciones
    animations: {
        fade: {
            title: "Fade",
            message: "Notificación con animación de desvanecimiento",
            animationIn: ["notification-fade-in"],
            animationOut: ["notification-fade-out"]
        },
        slideLeft: {
            title: "Slide Left",
            message: "Notificación con animación de deslizamiento desde la izquierda",
            animationIn: ["notification-slide-in-left"],
            animationOut: ["notification-fade-out"]
        },
        slideRight: {
            title: "Slide Right",
            message: "Notificación con animación de deslizamiento desde la derecha",
            animationIn: ["notification-slide-in-right"],
            animationOut: ["notification-fade-out"]
        },
        slideTop: {
            title: "Slide Top",
            message: "Notificación con animación de deslizamiento desde arriba",
            animationIn: ["notification-slide-in-top"],
            animationOut: ["notification-fade-out"]
        },
        slideBottom: {
            title: "Slide Bottom",
            message: "Notificación con animación de deslizamiento desde abajo",
            animationIn: ["notification-slide-in-bottom"],
            animationOut: ["notification-fade-out"]
        },
        bounce: {
            title: "Bounce",
            message: "Notificación con animación de rebote",
            animationIn: ["notification-bounce-in"],
            animationOut: ["notification-fade-out"]
        },
        zoom: {
            title: "Zoom",
            message: "Notificación con animación de zoom",
            animationIn: ["notification-zoom-in"],
            animationOut: ["notification-fade-out"]
        }
    },

    // Opciones
    options: {
        withClose: {
            title: "Con botón de cierre",
            message: "Esta notificación tiene un botón para cerrarla",
            dismiss: {
                showIcon: true,
                pauseOnHover: false,
            }
        },
        withoutClose: {
            title: "Sin botón de cierre",
            message: "Esta notificación no tiene botón para cerrarla",
            dismiss: {
                showIcon: false,
                pauseOnHover: false,
            }
        },
        withTimer: {
            title: "Con temporizador",
            message: "Esta notificación muestra un temporizador visual",
            dismiss: {
                duration: 5000,
                showIcon: true,
                onScreen: true,
                pauseOnHover: false,
            }
        },
        withoutTimer: {
            title: "Sin temporizador",
            message: "Esta notificación no muestra un temporizador visual",
            dismiss: {
                duration: 5000,
                onScreen: false,
                showIcon: true,
                pauseOnHover: false,
            }
        },
        pauseOnHover: {
            title: "Pausar al pasar el ratón",
            message: "Esta notificación se pausa al pasar el ratón por encima",
            dismiss: {
                duration: 5000,
                onScreen: true,
                pauseOnHover: true,
                showIcon: true
            }
        },
        touchDismiss: {
            title: "Deslizar para cerrar",
            message: "Desliza esta notificación para cerrarla (en dispositivos táctiles)",
            dismiss: {
                duration: 5000,
                onScreen: true,
                showIcon: true,
                touch: true,
                pauseOnHover: false,
            }
        }
    }
};