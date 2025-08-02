# Documentación Técnica: Rutas (`app/Routes`)

Este documento describe la estructura y organización de las rutas principales del sistema Frameworkito, incluyendo rutas web, API y la integración con middlewares. Está orientado a desarrolladores que deseen mantener o extender el sistema de enrutamiento.

---

## 1. web.php

**Propósito:**
Define todas las rutas web accesibles desde el navegador, agrupadas por áreas funcionales y preparadas para la integración con middlewares.

**Estructura y ejemplos:**
- **Sintaxis:** `$get($uri, $handler)`, `$post($uri, $handler)`, `$middleware($middleware, $rutas)`
- **Áreas principales:**
  - **Rutas públicas:** landing, about, contacto, FAQ, privacidad, términos, readme, status API.
  - **Autenticación:** login, registro, logout, recuperación y reseteo de contraseña, confirmación y verificación de email.
  - **Debug/desarrollo:** test, phpinfo, info del sistema (solo en modo debug).
  - **Usuario:** home, perfil.
  - **Administración:** dashboard, usuarios, logs (preparadas para protección por middleware).
- **Middleware:**
  - Preparado para aplicar middlewares como `AuthMiddleware`, `RoleMiddleware` y `GuestMiddleware` a rutas específicas (comentado hasta tener los archivos implementados).

**Ejemplo de definición:**
```php
$get('/login', 'AuthController@showLogin');
$post('/login', 'AuthController@login');
$middleware('App\\Middlewares\\AuthMiddleware', ['/home', '/profile', '/admin']);
```

**Recomendaciones:**
- Mantener las rutas agrupadas y comentadas para claridad.
- Usar middlewares para proteger rutas sensibles.
- No exponer rutas de debug en producción.
- Evitar lógica de negocio en los archivos de rutas.

---

## 2. api.php *(sin implementar)*

**Propósito esperado:**
Definir rutas para la API REST del sistema (por ejemplo, endpoints para AJAX, clientes móviles, integraciones externas, etc.).

**Nota:**
- El archivo existe pero no contiene rutas. Se recomienda estructurarlo usando `$get`, `$post`, `$put`, `$delete`, etc., y aplicar middlewares de autenticación y rate limiting.

---

> **Nota:** La definición clara y modular de rutas facilita el mantenimiento, la escalabilidad y la seguridad del sistema. Se recomienda documentar y comentar cualquier cambio en la estructura de rutas o en el uso de middlewares.
