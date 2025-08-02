# 2. Controladores (`app/Controllers`)

Este documento contiene un resumen técnico y detallado de los controladores principales del proyecto **Frameworkito**. Incluye propósito, estructura, funciones clave, recomendaciones y advertencias para cada uno.

---

## 1. `BaseController.php`
**Propósito:**
Clase base para todos los controladores. Centraliza métodos y utilidades comunes como renderizado de vistas, manejo de errores, validación, respuestas JSON, manejo de inputs, redirecciones y logging.

**Estructura y funciones clave:**
- `view($view, $data = [], $layout = 'app')`: Renderiza vistas con layouts, inyectando datos globales y del usuario autenticado si existe.
- `redirect($url, $statusCode = 302)`, `redirectWith($url, $message, $type = 'info')`, `back($fallback = '/')`: Métodos para redirección estándar y con mensajes.
- `validate($data, $rules, $messages = [], $fallback = '/')`: Valida datos usando reglas y mensajes personalizados, integrando con el validador de la app.
- Utilidades para manejo de request: `isAjax()`, `input($key = null, $default = null)`, `has($key)`, `all()`, `only($keys)`, `except($keys)`, `isMethod($method)`.
- `user()`, `isAuthenticated()`: Obtención y verificación de usuario autenticado (placeholders para integración con Auth).
- `log($level, $message, $context = [])`, `handleError($e, $fallbackMessage = 'Ha ocurrido un error')`: Logging y manejo centralizado de errores, con soporte para logs en archivos y pantalla según entorno.
- `getRequestInfo()`: Obtiene información relevante de la request para debugging y profiling.

**Advertencias y recomendaciones:**
- Todos los controladores deben heredar de esta clase para mantener DRY y estandarizar respuestas.
- Aprovecha el manejo centralizado de errores y logs para facilitar el mantenimiento.

---

## 2. `AuthController.php`
**Propósito:**
Controlador para toda la lógica de autenticación de usuarios, basado en Delight-im/Auth.

**Estructura y funciones clave:**
- Formularios y procesamiento de login, registro, logout.
- Recuperación y reseteo de contraseña (con tokens y validación).
- Verificación de email: incluye página de verificación, procesamiento, endpoint AJAX y reenvío de enlace.
- Integración con servicios de email para confirmaciones y recuperación.
- Logging de eventos de autenticación y redirecciones según resultados.
- Validación robusta de inputs y manejo de errores con mensajes amigables.

**Advertencias y recomendaciones:**
- Explica el flujo de autenticación, integración de formularios y rutas.
- Documenta cómo personalizar la lógica de login/registro y agregar nuevas validaciones o eventos.
- Usa logs para monitorear intentos de acceso y fallos de autenticación.

---

## 3. `HomeController.php`
**Propósito:**
Controlador de páginas principales del sistema (inicio, info, testing, utilidades para desarrolladores).

**Estructura y funciones clave:**
- `index()`: Página de inicio con bienvenida y estado del sistema (features, status, etc).
- `info()`: Página de información del sistema, rendimiento y configuración.
- `test()`, `phpinfo()`: Herramientas de testing y debug (solo en modo debug), útiles para desarrolladores.
- Métodos privados para obtener estado del sistema, features, permisos de archivos, y pruebas automáticas de sesión, logging, validación y utilidades.

**Advertencias y recomendaciones:**
- Documenta las páginas de información y testing, su utilidad para desarrollo y diagnóstico.
- Asegúrate de deshabilitar funciones de testing en producción.

---

## 4. `ProfileController.php`
**Propósito:**
Controlador para la gestión del perfil de usuario.

**Estructura y funciones clave:**
- `index()`: Página principal de perfil con mensaje de bienvenida y logging de acceso.

**Advertencias y recomendaciones:**
- Explica cómo se puede extender este controlador para agregar edición de perfil, cambio de contraseña, avatar, etc.
- Usa el logging de accesos para monitorear actividad en perfiles.

---

## 5. `PublicController.php`
**Propósito:**
Maneja las rutas públicas accesibles sin autenticación (landing, about, contacto, FAQ, privacidad, términos, status API, readme, verificación de DB, uptime).

**Estructura y funciones clave:**
- `landing()`: Landing page pública, muestra features y estadísticas según tipo de app.
- `about()`, `contact()`, `contactSubmit()`, `faq()`, `privacy()`, `terms()`: Páginas informativas y formularios públicos para usuarios y visitantes.
- `status()`: Endpoint público para verificar el estado del sistema (API health check), útil para monitoreo externo.
- `readme()`: Página para mostrar el README/documentación de la app.
- Métodos privados para verificación de conexión a base de datos y cálculo de uptime del sistema.

**Advertencias y recomendaciones:**
- Lista todas las rutas públicas y su propósito.
- Explica cómo agregar nuevas páginas públicas y cómo se integra la página de documentación.
- Usa el endpoint de status para monitoreo y alertas.

---

Todos los controladores deben heredar de `BaseController` para mantener consistencia, seguridad y aprovechar utilidades comunes.
