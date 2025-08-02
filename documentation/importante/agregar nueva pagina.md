## 🌐 Guía para Agregar una Nueva Página Pública en tu Proyecto

Esta guía explica paso a paso cómo agregar una nueva **página pública** en tu proyecto con **Frameworkito**. En este ejemplo, crearemos la página `/about`.

---

## 1. Registrar la ruta en el archivo de rutas

Para que la nueva vista `/about` esté disponible en la web, primero debemos registrar la ruta correspondiente en el archivo de rutas principal.

📁 **Archivo de rutas principal:**
```
app/Routes/web.php
```

### ➕ Paso a paso para registrar la nueva ruta pública

1. Abre el archivo `app/Routes/web.php`.
2. Agrega la siguiente línea:

```php
$get('/about', 'PublicController@about');
```

### 🧠 ¿Qué hace este archivo?

Este archivo define todas las rutas **web** disponibles en tu proyecto, indicando qué controlador y método se deben ejecutar cuando un usuario accede a una URL determinada.

El framework usa un enrutador personalizado que te permite registrar rutas de forma sencilla con los métodos `$get()`, `$post()`, `$put()`, etc., similares a los de otros frameworks modernos.

---

## 2. Usar el controlador adecuado

Las vistas públicas deben manejarse en un controlador específico para mantener la organización del proyecto. Para este propósito, usaremos el controlador llamado:

```
app/Controllers/PublicController.php
```

Si el archivo no existe, créalo con el siguiente contenido base:

```php
<?php

namespace App\Controllers;

class PublicController extends BaseController {
    /* Aquí se deben crear los métodos para publicar vistas públicas */
}
```

> ⚠️ Este controlador extiende `BaseController`, lo que asegura acceso a utilidades como render de `vistas`, `logging`, `manejo de errores`, etc.

### Crear el método público en el controlador

Ahora dentro de `PublicController`, vamos a crear el método que mostrará la vista `/about`. Este método debe seguir una estructura clara: `registrar logs`, `preparar los datos`, `renderizar la vista` y `capturar errores`.

Agrega lo siguiente:

```php
public function about(): void {
    try {
        $this->log('info', 'Acceso a página About');

        // Datos de ejemplo para la vista (puedes personalizar según tus necesidades)
        $data = [
            'page_title' => 'Acerca de - ' . ($this->config['app']['name'] ?? 'Tu Proyecto'),
            'meta_description' => 'Conoce más sobre nuestro proyecto y las tecnologías que utilizamos.'
        ];

        $this->view('public/about', $data, 'guest');
    } catch (\Exception $e) {
        $this->handleError($e, 'Error al cargar la página Acerca de');
    }
}
```

### 🔍 ¿Qué hace cada parte?

- `log('info', ...)`: registra que el usuario accedió a esta ruta, útil para auditoría o analítica.
- `$data`: es un arreglo con variables de ejemplo que se enviarán a la vista.
- `view('public/about', $data, 'guest')`:
  - `'public/about'`: indica la plantilla `app/Views/public/about.php`.
  - `$data`: variables pasadas a la vista.
  - `'guest'`: layout que se usará (usualmente `layouts/guest.php` o similar).
- `catch`: captura errores inesperados y los maneja correctamente.

### 🧠 ¿Qué hace este archivo?

Este archivo representa el controlador que maneja las páginas públicas de tu proyecto. Los controladores son responsables de procesar las peticiones HTTP, preparar los datos necesarios y renderizar las vistas correspondientes.

El `PublicController` específicamente se encarga de todas las páginas que no requieren autenticación, manteniendo una separación clara entre funcionalidades públicas y privadas.

---

## 3. Crear la vista correspondiente

Ahora que el controlador ya tiene el método `about()`, el siguiente paso es crear la vista que se mostrará cuando el usuario acceda a `/about`.

📁 **Ubicación esperada:**
```
app/Views/public/about.php
```

> Si el directorio `public/` aún no existe dentro de `Views/`, créalo manualmente.

### ✍️ Contenido de ejemplo para `about.php`:

```php
<section class="container py-5">
    <h1 class="mb-4"><?= $page_title ?? 'Sobre nosotros' ?></h1>
    <p><?= $meta_description ?? 'Información sobre el proyecto.' ?></p>

    <hr class="my-5">

    <h2>Tecnologías utilizadas (ejemplo)</h2>
    <ul>
        <li>PHP 8.1+</li>
        <li>Bootstrap 5</li>
        <li>Componentes JavaScript ligeros</li>
        <li>Middleware para autenticación y seguridad</li>
    </ul>

    <h2>Más información</h2>
    <p>Aquí puedes agregar cualquier contenido específico de tu proyecto.</p>
</section>
```

### 🧠 ¿Qué hace este archivo?

Este archivo representa el contenido HTML específico para la página pública `about`. Es una vista simple que será insertada automáticamente dentro del layout `guest`, sin necesidad de incluir encabezados o pie de página manualmente.

Gracias al sistema de renderizado de **Frameworkito**, esta vista:

- No necesita definir el layout ni invocar `$this->layout()`.
- Puede usar como variables todos los datos pasados a través del arreglo `$data` en el controlador.
- También puede acceder a variables globales como `$user`, `$app_name`, `$app_debug`, etc., que el sistema pasa automáticamente.

---

## 4. Probar en el navegador

Después de haber registrado la ruta, creado el controlador y la vista, es momento de verificar que la nueva página pública esté funcionando correctamente.

### 🧪 Pasos para probar la vista

1. Abre tu navegador web.
2. Escribe en la barra de direcciones: `http://tu-dominio.local/about`
3. Presiona **Enter**.

### ✅ Resultado esperado

Deberías ver una página con el siguiente contenido:

- Un título como "Acerca de" o "Sobre nosotros".
- Texto explicativo sobre el sistema o la organización.
- El diseño visual debe coincidir con el layout `guest` (cabecera, estilos, pie de página, etc.).

Si configuraste correctamente todo lo anterior, la página se mostrará sin errores.

Imagen de referencia:
<img src="../../assets/img/doc/about-preview.png" alt="Vista previa" style="max-width: 100%; height: auto; border: solid 1px rgba(107, 107, 107, .4); border-radius:10px;">

### 🧠 ¿Y si aparece un error?

Aquí algunas posibles causas:

| Mensaje o síntoma | Posible causa | Solución |
|-------------------|----------------|----------|
| Página en blanco  | Error interno no visible | Habilita `APP_DEBUG=true` en el archivo `.env` |
| Error 404         | Ruta no registrada | Verifica que agregaste `$get('/about', 'PublicController@about');` |
| Error 500         | Faltan variables en la vista o error de sintaxis | Verifica tu controlador y la vista |
| Layout no cargado | Nombre de layout incorrecto | Asegúrate de pasar `'guest'` como tercer parámetro en `$this->view()` |

### 🔍 ¿Qué vamos a comprobar?

- Que la ruta `/about` carga correctamente en el navegador.
- Que el contenido de la vista `about.php` se renderiza dentro del layout configurado (`guest`).
- Que no se requiere autenticación para acceder a esta página.

---

Con esto ya tienes una nueva página pública funcional dentro de tu proyecto 🎉