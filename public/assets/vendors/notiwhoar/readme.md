# Guía Avanzada de Notiwhoar

**Notiwhoar** es la librería de notificaciones y modales incluida en Frameworkito, diseñada para ofrecer una experiencia de usuario moderna, flexible y personalizable tanto en el website como en el panel de administración.

---

## Características principales

| Tipo              | Función principal     | Personalizable | Callbacks | Animación | Progreso | Cancelable | Entrada de usuario |
|-------------------|----------------------|:-------------:|:---------:|:---------:|:--------:|:----------:|:------------------:|
| Toast             | Mensajes rápidos     |      ✔️       |    ✔️     |    ✔️     |   ✔️     |    ✔️      |         -          |
| Modal             | Ventanas emergentes  |      ✔️       |    ✔️     |    ✔️     |    -     |    ✔️      |         ✔️         |
| Confirmación      | Aceptar/Cancelar     |      ✔️       |    ✔️     |    ✔️     |    -     |    ✔️      |         -          |
| Espera/Progreso   | Barra de espera      |      ✔️       |    ✔️     |    ✔️     |   ✔️     |    ✔️      |         -          |
| Entrada de texto  | Input/Select/Textarea|      ✔️       |    ✔️     |    ✔️     |    -     |    ✔️      |         ✔️         |

---

## Ejemplos de uso

### Toast básico
```js
exito('¡Guardado correctamente!');
error('Ocurrió un error', { posicion: 'inferior-centro', permitirCerrar: true });
```

### Confirmación
```js
confirmacion({
  tipo: 'warning',
  titulo: '¿Eliminar?',
  contenido: 'Esta acción es irreversible.'
}).then(respuesta => {
  if (respuesta) exito('Eliminado');
});
```

### Entrada de usuario
```js
solicitarEntrada({
  titulo: 'Ingrese email',
  tipo: 'email',
  requerido: true
}).then(valor => {
  if (valor) exito('Email: ' + valor);
});
```

### Modal personalizado
```js
modal({
  titulo: 'Acerca de',
  contenido: '<b>Frameworkito</b> es genial',
  html: true
});
```

### Notificación de espera con progreso
```js
notificacionEspera({
  mensaje: 'Subiendo archivo...',
  tiempo: 8000,
  mostrarProgreso: true,
  onComplete: () => exito('¡Subida finalizada!')
});
```

---

## Opciones y parámetros principales

| Opción         | Descripción                                      | Valores posibles / Ejemplo           |
|----------------|--------------------------------------------------|--------------------------------------|
| tipo           | Tipo de notificación/modal                       | 'success', 'error', 'info', 'warning'|
| mensaje        | Texto a mostrar                                  | '¡Listo!'                            |
| posicion       | Ubicación del toast                              | 'superior-derecha', 'inferior-centro', etc. |
| duracion       | Tiempo en ms antes de autocerrar                 | 4000                                 |
| permitirCerrar | Mostrar botón para cerrar                        | true/false                           |
| mostrarProgreso| Barra de progreso visible                        | true/false                           |
| icono          | Ícono FontAwesome personalizado                  | 'fas fa-check-circle'                |
| onCerrar       | Callback al cerrar                               | function                             |
| onMostrar      | Callback al mostrar                              | function                             |
| onComplete     | Callback al completar espera                     | function                             |
| cancelable     | Permitir cancelar (espera, modales)              | true/false                           |
| botones        | Botones personalizados en modales                | [{texto, tipo, icono, accion}]       |
| html           | Permitir HTML en el contenido                    | true/false                           |
| requerido      | Entrada obligatoria (inputs)                     | true/false                           |

Consulta el archivo JS para ver todas las opciones avanzadas y ejemplos de integración.

---

## Consejos y buenas prácticas
- Usa notiwhoar para mejorar la UX en formularios, acciones críticas o feedback rápido.
- Personaliza colores, iconos y animaciones para adaptarlo a tu marca.
- Aprovecha los callbacks para flujos complejos o asincrónicos.
- Consulta el código fuente para extender o adaptar la librería según tus necesidades.

---

¿Tienes dudas, sugerencias o quieres compartir ejemplos? ¡Contribuye al proyecto o abre un issue!
