# Documentación de la librería Notiwhoar

Notiwhoar es una librería de notificaciones y modales para aplicaciones web, construida con JavaScript vanilla. Proporciona funcionalidades para mostrar toasts, modales, confirmaciones, entradas de usuario y notificaciones de espera con progreso.

---

## Configuración global

- `ConfiguracionWhoar`: Objeto con configuraciones por defecto para toasts y modales, incluyendo duración, posición, animaciones e iconos.

## Funciones principales

### notificacion(tipo, mensaje, opciones)
Muestra una notificación tipo toast con el tipo y mensaje especificados. Opcionalmente se pueden pasar opciones para personalizar la notificación.

**Ejemplo:**
```js
notificacion("exito", "Operación realizada con éxito", { duracion: 3000, posicion: "top-right" });
```

### confirmacion(opciones)
Muestra un modal de confirmación con opciones para personalizar el tipo, icono, título, contenido y botones.

**Ejemplo:**
```js
confirmacion({
  tipo: "advertencia",
  titulo: "¿Estás seguro?",
  contenido: "Esta acción no se puede deshacer.",
  onConfirm: () => console.log("Confirmado")
});
```

### modal(opciones)
Crea un modal genérico con título, contenido, tamaño y botones personalizados.

**Ejemplo:**
```js
modal({
  titulo: "Información",
  contenido: "Contenido del modal",
  botones: [
    { texto: "Cerrar", clase: "btn-secondary", cerrar: true }
  ]
});
```

### notificacionEspera(opciones)
Muestra una notificación de espera con progreso y tiempo, que puede ser modal o toast según la posición.

**Ejemplo:**
```js
notificacionEspera({
  tiempo: 5000,
  mensaje: "Procesando...",
  cancelable: true,
  onCancel: () => console.log("Cancelado")
});
```

### solicitarEntrada(opciones)
Muestra un modal para solicitar entrada de usuario con validación y diferentes tipos de campos.

**Ejemplo:**
```js
solicitarEntrada({
  titulo: "Ingrese su nombre",
  tipo: "text",
  placeholder: "Nombre",
  validar: valor => valor.length > 0 || "Debe ingresar un nombre"
}).then(valor => {
  if (valor !== null) {
    console.log("Nombre ingresado:", valor);
  } else {
    console.log("Entrada cancelada");
  }
});
```

---

## Tabla resumen de funciones

| Tipo               | Función principal | Personalizable | Callbacks           | Animación | Progreso | Cancelable | Entrada de usuario |
|--------------------|-------------------|---------------|---------------------|-----------|----------|------------|--------------------|
| Notificación       | notificacion      | Sí            | onClose, onClick    | Sí        | Opcional | No         | No                 |
| Confirmación       | confirmacion     | Sí            | onConfirm, onCancel | Sí        | No       | No         | No                 |
| Modal genérico     | modal            | Sí            | onClose             | Sí        | No       | Opcional   | No                 |
| Notificación espera| notificacionEspera| Sí            | onCancel, onComplete| Sí        | Sí       | Sí         | No                 |
| Entrada de usuario | solicitarEntrada  | Sí            | onConfirm, onCancel | Sí        | No       | Sí         | Sí                 |

## Tabla de opciones y parámetros

| Opción           | Tipo       | Descripción                                      | Valores posibles / Ejemplo                      |
|------------------|------------|-------------------------------------------------|------------------------------------------------|
| tipo             | string     | Tipo de notificación o modal                     | "exito", "error", "advertencia", "informacion" |
| mensaje          | string     | Texto a mostrar en la notificación               | "Operación exitosa"                           |
| opciones         | object     | Opciones para personalizar la notificación       | { duracion: 3000, posicion: "top-right" }    |
| titulo           | string     | Título del modal o confirmación                   | "Confirmar acción"                            |
| contenido        | string     | Contenido HTML o texto del modal                  | "¿Desea continuar?"                           |
| botones          | array      | Botones personalizados para modales              | [{ texto: "Aceptar", clase: "btn-primary" }]|
| tiempo           | number     | Duración en ms para notificaciones de espera     | 5000                                           |
| cancelable       | boolean    | Indica si la notificación de espera puede cancelarse | true                                          |
| validar          | function   | Función para validar entrada de usuario           | valor => valor.length > 0 || "Error"          |
| placeholder      | string     | Texto placeholder para entrada de usuario         | "Ingrese su nombre"                           |

---

## Uso alternativo con funciones abreviadas

Para mayor comodidad, existen funciones abreviadas para mostrar notificaciones de tipos específicos, que internamente llaman a `notificacion` con el tipo correspondiente:

- `exito(mensaje, opciones)`
- `error(mensaje, opciones)`
- `advertencia(mensaje, opciones)`
- `informacion(mensaje, opciones)`

**Ejemplo:**
```js
exito("Operación exitosa");
error("Ocurrió un error");
advertencia("Advertencia importante");
informacion("Información relevante");
```

Estas funciones son equivalentes a:

```js
notificacion("exito", "Operación exitosa");
notificacion("error", "Ocurrió un error");
notificacion("advertencia", "Advertencia importante");
notificacion("informacion", "Información relevante");
```

Se pueden usar indistintamente según preferencia o contexto.

---

## Nota para usuarios novatos

Se recomienda usar la función `notificacion(tipo, mensaje, opciones)` como función principal para mostrar notificaciones, ya que permite pasar dinámicamente el tipo y mensaje, especialmente útil en respuestas AJAX o situaciones donde el tipo no es fijo.

Las funciones abreviadas son atajos para casos comunes y mejoran la legibilidad, pero no son obligatorias.

Para modales, confirmaciones y entradas, usar las funciones `confirmacion`, `modal` y `solicitarEntrada` respectivamente, con sus opciones y callbacks para controlar el flujo.

---

Esta documentación está diseñada para ser clara y completa para usuarios principiantes y avanzados, facilitando el uso correcto y efectivo de la librería notiwhoar.
