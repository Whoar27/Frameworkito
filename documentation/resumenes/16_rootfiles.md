# Documentación Técnica: Archivos de Configuración Raíz

Este documento describe los archivos principales de configuración y control ubicados en la raíz del sistema Frameworkito: `.env`, `.htaccess` y `composer.json`. Estos archivos son fundamentales para la operación, despliegue y personalización del sistema.

---

## 1. `.env`

**Propósito:**
- Centraliza la configuración sensible y de entorno del sistema (no debe subirse a repositorios públicos).
- Permite ajustar el comportamiento del sistema sin modificar el código fuente.

**Secciones principales:**
- **APP:** Entorno (`APP_ENV`), modo debug, nombre, URL, clave de aplicación, zona horaria, tipo de sistema.
- **Empresa:** Email y datos de contacto de soporte, nombre y dirección de la empresa.
- **Base de datos:** Tipo, host, puerto, nombre, usuario, contraseña, prefijo, charset y collation.
- **Correo:** Driver, host, puerto, usuario, contraseña, cifrado, dirección y nombre remitente.
- **Autenticación:** Parámetros de sesión, duración de tokens, intentos máximos, bloqueo, verificación de email, 2FA.
- **Logging:** Nivel, rotación, días máximos, tamaño máximo, registro en base de datos y archivos.
- **Seguridad:** Protección CSRF.

**Recomendaciones:**
- No exponer ni subir a repositorios públicos.
- Actualizar según el entorno (desarrollo, producción, testing).
- Cambiar credenciales y claves por valores seguros antes de producción.

---

## 2. `.htaccess` (raíz del proyecto)

**Propósito:**
- Redirige todas las peticiones al directorio `/public` para proteger el código fuente y archivos sensibles.
- Bloquea acceso directo a archivos de configuración, logs, scripts, migraciones y carpetas del sistema.
- Añade headers de seguridad HTTP básicos.

**Reglas principales:**
- Redirección 301 a `/public` salvo que ya esté en la URL.
- Bloqueo de acceso a archivos `.env`, `.config`, `.log`, `.sql`, `.md`, etc.
- Bloqueo de acceso a carpetas sensibles (`/app`, `/database`, `/logs`, `/tests`, `/vendor`).
- Headers: `X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`.

**Recomendaciones:**
- No modificar salvo que se comprenda el impacto en seguridad y despliegue.
- Revisar reglas tras cambios de estructura o migraciones.

---

## 3. `composer.json`

**Propósito:**
- Archivo de gestión de dependencias PHP mediante Composer.
- Define los paquetes requeridos, autoload, scripts y metadatos del proyecto.

**Secciones principales:**
- **require:** Versiones mínimas de PHP y extensiones (`pdo`, `mbstring`, `openssl`, `json`), librerías externas (`delight-im/auth`, `phpmailer/phpmailer`).
- **require-dev:** Herramientas de testing (`phpunit`), formateo de código (`php-cs-fixer`).
- **autoload:** PSR-4 para `App\` y carga de helpers globales.
- **scripts:** Comandos post-instalación, test, cobertura, CS fixer, setup, generación de claves.
- **archive.exclude:** Exclusión de carpetas y archivos no necesarios para distribución.

**Recomendaciones:**
- Mantener actualizado y versionado.
- Usar `composer install` tras clonar el proyecto para instalar dependencias.
- Revisar y ajustar scripts según necesidades del equipo.

---

> **Resumen:**
> Los archivos `.env`, `.htaccess` y `composer.json` son críticos para la seguridad, configuración y operación del sistema. Su correcta gestión es esencial para el despliegue seguro y eficiente de Frameworkito.
