# Documentación Técnica: Helpers (`app/Helpers`)

Este documento describe en detalle los archivos helper principales del sistema Frameworkito, su propósito, estructura, funciones clave, consideraciones de seguridad y recomendaciones de uso. Está orientado a desarrolladores que deseen mantener o extender el núcleo del sistema.

---

## 1. Redirect.php

**Propósito:**
Gestiona redirecciones HTTP seguras y flexibles, soportando URLs internas y externas, mensajes flash, códigos de estado, AJAX y utilidades de debugging.

**Funciones clave:**
- `to($url, $statusCode)`: Redirecciona a una URL validada.
- `with($url, $message, $type, $statusCode)`: Redirecciona con mensaje flash.
- Métodos rápidos: `withSuccess`, `withError`, `withWarning`, `withInfo`.
- Redirecciones por ruta: `home`, `login`, `dashboard`, `afterLogout`, `intended`.
- Redirección hacia atrás: `back`, `backWith`.
- Redirección AJAX: `ajax`.
- Validación: `validateUrl`, `isSafeUrl`, `isSystemRoute`.
- Utilidades: `refresh`, `metaRefresh`, `getDebugInfo`, `debug`.

**Seguridad:**
- Todas las URLs se validan y normalizan.
- Previene open redirects verificando dominios/rutas permitidas.
- Logging de todas las redirecciones.

**Recomendaciones:**
- Usar siempre `validateUrl` para URLs de usuario.
- Usar mensajes flash para feedback.
- Preferir rutas nombradas para mantenibilidad.
- Usar `debug()` solo en desarrollo.

---

## 2. Session.php

**Propósito:**
Centraliza la gestión segura de sesiones: inicio, configuración, datos flash/temporales, tokens CSRF y validaciones de seguridad.

**Funciones clave:**
- `start()`: Inicializa la sesión con configuración segura.
- `configureSession()`: Ajusta parámetros de PHP (cookie, samesite, httponly, etc).
- Validaciones: `validateSession()`, `validateIpAddress()`, `checkSessionExpiry()`.
- CRUD: `get`, `set`, `has`, `remove`, `pull`, `all`, `clear`, `destroy`, `regenerate`.
- Flash: `flash`, `getFlash`, `hasFlash`, `getAllFlash`, `success`, `error`, `warning`, `info`.
- Temporales: `setTemp`, `getTemp`, `cleanupTemp`.
- CSRF: `generateCsrfToken`, `getCsrfToken`, `verifyCsrfToken`.
- Info: `getId`, `isActive`, `getInfo`, `getStats`, `formatDuration`.
- Middleware: `middleware()` para limpieza y logging.

**Seguridad:**
- Valida IP y expira sesión.
- Cookies seguras y regeneración de ID.
- Integración CSRF.
- Logging de actividad sospechosa.

**Recomendaciones:**
- Llamar siempre a `Session::start()` antes de usar datos de sesión.
- Usar datos flash/temp para mensajes de una sola vez.
- Usar métodos CSRF en formularios y acciones sensibles.

---

## 3. UserAgentParser.php

**Propósito:**
Parsea el user agent para extraer información de dispositivo, navegador y sistema operativo para analítica, logging o UI adaptativa.

**Funciones clave:**
- `parseDevice($userAgent)`: Resumen legible de dispositivo.
- `getBrowser($userAgent)`: Detecta navegador.
- `getOperatingSystem($userAgent)`: Detecta sistema operativo.
- `getDeviceType($userAgent)`: Clasifica el dispositivo.
- `getDetailedInfo($userAgent)`: Array asociativo detallado.

**Seguridad:**
- Sin impacto directo, pero útil para logging y detección de fraude.

**Recomendaciones:**
- Usar para analítica, lógica adaptativa o logging.
- Manejar user agents desconocidos/vacíos.

---

## 4. Utils.php

**Propósito:**
Utilidades generales para manipulación de strings, validación, formateo, archivos y diagnóstico del sistema.

**Funciones clave:**
- Strings: `slug`, `truncate`, `truncateWords`, `title`, `camelCase`, `snakeCase`, `randomString`, `sanitize`.
- Validación: `isValidEmail`, `isValidUrl`, `isValidIp`, `isValidDate`, `isValidPhone`, `isAlpha`, `isNumeric`, `isAlphaNumeric`.
- Formato: `formatNumber`, `formatBytes`, `formatPercentage`, `formatCurrency`, `formatDate`, `timeAgo`.
- Fecha/hora: `isDateInRange`, `getAge`.
- Archivos: `getFileExtension`, `generateUniqueFilename`, `isImage`, `isDocument`, `fileToBase64`.
- Arrays: `arrayGet`.
- Logging: `log` con contexto automático.
- Testing/diagnóstico: `generateTestData`, `checkSystemRequirements`, `systemDiagnostic`.

**Seguridad:**
- Métodos de sanitización y validación ayudan a prevenir XSS y problemas de integridad.
- Logging con contexto de archivo/línea.

**Recomendaciones:**
- Validar y sanitizar todo input de usuario.
- Usar helpers de formato para UI consistente.
- Usar diagnósticos para debugging y monitoreo.

---

## 5. Validator.php

**Propósito:**
Sistema flexible y extensible de validación de datos con reglas, mensajes personalizados, validaciones anidadas y soporte para arrays.

**Funciones clave:**
- Constructor y `make()`: Crear validador con datos, reglas y mensajes.
- `validate()`: Ejecuta validación y llena errores.
- Validación de campo/regla: `validateField`, `validateRule`, métodos específicos por regla (e.g. `validateRequired`, `validateEmail`, ...).
- Reglas incluidas: required, email, min/max, numeric, integer, alpha, alpha_num, url, date, confirmed, same, different, in, not_in, regex, size, between, boolean, array, phone, ip, json, unique, exists, file, image, mimes.
- Manejo de errores: mensajes personalizados y por defecto, resumen de errores, exportación JSON.
- Utilidades: `getDebugInfo`, `debug`, `toJson`, `getErrorSummary`.
- Escenarios predefinidos: `validateRegister`, `validatePasswordChange`.

**Seguridad:**
- Garantiza validación de todo input antes de procesar.
- Mensajes personalizables para evitar filtración de información sensible.

**Recomendaciones:**
- Usar `Validator::make()` para toda validación de formularios/datos.
- Personalizar mensajes para feedback amigable.
- Usar `toJson()` en respuestas de API.

---

## 6. Parsedown.php

**Propósito:**
Librería externa para parseo y conversión de Markdown a HTML seguro, utilizada para procesar documentación y contenido enriquecido.

**Notas:**
- No modificar este archivo salvo actualización oficial.
- Usar solo desde funciones controladas para evitar XSS.

---

> **Nota:** Todos los helpers están diseñados para ser reutilizables, seguros y fácilmente extensibles. Se recomienda revisar y actualizar esta documentación si se agregan nuevas funciones o se modifican comportamientos clave.
