# Documentación Técnica: Middlewares (`app/Middlewares`)

Este documento describe los middlewares principales del sistema Frameworkito, su propósito, estructura, funciones clave, consideraciones de seguridad y recomendaciones de uso. Está orientado a desarrolladores que deseen mantener o extender la capa de middleware del sistema.

---

## 1. AuthMiddleware.php

**Propósito:**
Protege rutas que requieren autenticación. Verifica que la sesión esté activa y que exista un usuario autenticado (`user_id` en sesión). Si no, redirige automáticamente a la página de login.

**Método principal:**
- `handle($request, $next)`: Inicia la sesión si es necesario, verifica autenticación y redirige a `/login` si el usuario no está autenticado. Si está autenticado, continúa con el request.

**Seguridad:**
- Impide acceso no autorizado a rutas protegidas.
- Redirección inmediata y segura.

**Recomendaciones:**
- Usar en todas las rutas que requieran usuario autenticado.

---

## 2. MaintenanceMiddleware.php

**Propósito:**
Gestiona el modo mantenimiento del sistema. Si el modo está activo, bloquea el acceso a usuarios no autorizados y muestra una página de mantenimiento o una respuesta JSON para AJAX.

**Funciones clave:**
- `__construct($config)`: Permite configurar IPs y rutas permitidas.
- `handle()`: Verifica si el modo mantenimiento está activo, si la IP/ruta está permitida, si es AJAX, y responde en consecuencia.
- `isMaintenanceModeEnabled()`: Detecta si el modo mantenimiento está habilitado (por configuración/env).
- `isAllowedIp()`, `isAllowedRoute()`: Permiten excepciones para ciertas IPs/rutas.
- `isAjaxRequest()`: Detecta peticiones AJAX.
- `showMaintenancePage()`, `showBasicMaintenancePage()`: Renderizan la página de mantenimiento.
- `logMaintenanceAccess()`: Registra accesos durante mantenimiento.

**Seguridad:**
- Bloquea acceso general durante mantenimiento salvo excepciones explícitas.
- Permite control granular por IP/ruta.
- Logging de accesos y excepciones.

**Recomendaciones:**
- Configurar IPs y rutas permitidas para administradores.
- Personalizar la página de mantenimiento según necesidades.

---

## 3. CSRFMiddleware.php *(sin implementar)*

**Propósito esperado:**
Proteger contra ataques CSRF validando tokens en cada request sensible.

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar validación de token CSRF usando los helpers de sesión.

---

## 4. GuestMiddleware.php *(sin implementar)*

**Propósito esperado:**
Restringir acceso a rutas solo para usuarios no autenticados (por ejemplo, login/registro).

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar lógica para redirigir usuarios autenticados fuera de rutas de invitado.

---

## 5. RoleMiddleware.php *(sin implementar)*

**Propósito esperado:**
Restringir acceso a rutas según el rol del usuario autenticado (admin, editor, etc.).

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar verificación de roles usando el helper de autenticación.

---

> **Nota:** Los middlewares permiten separar lógica de seguridad, acceso y mantenimiento del flujo principal de la aplicación. Se recomienda implementar y documentar los middlewares pendientes para fortalecer la seguridad y flexibilidad del sistema.
