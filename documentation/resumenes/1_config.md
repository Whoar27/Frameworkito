# 1. Configuración General (`app/Config`)

Este documento contiene un resumen técnico y detallado de cada archivo de configuración del proyecto **Frameworkito**. Incluye estructura, campos, advertencias, recomendaciones y mejores prácticas para cada uno.

---

## 1. `app.php` — Configuración General de la Aplicación
- **Carga variables de entorno** desde `.env` y las expone vía `$_ENV`.
- Define:
  - **Nombre, entorno** (`development`, `production`, etc.), **modo debug**, **modo mantenimiento**, **URL base**, **clave secreta**, **zona horaria**, **tipo de aplicación**.
  - **Configuración de mantenimiento:** IPs y rutas permitidas, notificaciones, mensaje y contacto de soporte.
  - **Sesiones:** duración, almacenamiento, seguridad.
  - **Seguridad:** HTTPS forzado, CSRF, headers de seguridad, rate limiting.
  - **Archivos:** rutas de uploads, tamaños máximos, tipos permitidos.
  - **API:** activación, prefijo, versión, rate limiting.
  - **Caché:** driver, TTL, path.
  - **Desarrollo:** errores, profiling.
  - **Rutas principales** (login, logout, home, register).
  - **Proveedores/autoload de servicios**.
- **Advertencias y recomendaciones:**
  - Personaliza cada sección según el entorno y necesidades del proyecto.
  - Mantén la clave secreta fuera del repositorio.
  - Revisa las rutas de uploads y permisos de archivos.
  - Asegúrate de configurar correctamente las IPs y rutas permitidas en mantenimiento.

---

## 2. `auth.php` — Configuración de Autenticación
- **Basado en Delight-im/Auth**.
- Define:
  - **Verificación de email, 2FA, duración de “recordarme”**.
  - **Rate limiting** para login, registro, recuperación de contraseña.
  - **Políticas de contraseñas:** longitud, complejidad, contraseñas prohibidas.
  - **Tokens:** expiración, longitud, expiración de email/contraseña.
  - **Roles y permisos predeterminados, jerarquía**.
  - **Sesiones:** múltiples sesiones, límite concurrente, info adicional.
  - **2FA:** métodos disponibles, configuración TOTP, backup codes.
  - **Logging:** eventos a registrar, contexto.
  - **OAuth:** Google, Facebook, etc.
  - **Validación de email:** dominios bloqueados, permitidos.
- **Advertencias y recomendaciones:**
  - Documenta cómo añadir roles/permisos y cómo activar 2FA.
  - Ajusta políticas de seguridad según el tipo de aplicación.
  - Usa logging para rastrear eventos críticos.

---

## 3. `database.php` — Configuración de Base de Datos
- Define:
  - **Conexión por defecto** (MySQL, SQLite, PostgreSQL).
  - **Configuración detallada** para cada tipo de conexión.
  - **Pool de conexiones**.
  - **Migraciones:** tabla, path, orden.
  - **Seeds:** path.
  - **Backups:** path, retención, compresión.
  - **Tablas específicas** para Delight-im/Auth.
  - Incluye helper `getPDO()` para obtener instancias PDO.
- **Advertencias y recomendaciones:**
  - Explica cómo cambiar entre motores.
  - Documenta rutas de migraciones/seeds y backups automáticos.
  - Mantén la seguridad de credenciales y backups.

---

## 4. `logging.php` — Configuración de Logging
- **Sistema dual:** logs en archivos y en base de datos.
- Define:
  - **Nivel mínimo de log**, activación de logs en archivos y BD.
  - **Archivos:** path, rotación, canales (info, error, debug, auth, warning, critical), formato, compresión.
  - **Base de datos:** tabla, campos, tipos de eventos, limpieza automática, índices.
  - **Alertas:** email para eventos críticos, niveles/eventos que disparan alertas, rate limiting.
  - **Formato y performance:** buffers, async, caché.
  - **Desarrollo:** logs adicionales, profiling, logs en pantalla.
- **Advertencias y recomendaciones:**
  - Documenta cómo consultar logs y activar alertas.
  - Personaliza rotación y limpieza según el entorno.
  - Usa logs para auditoría y diagnóstico.

---

## 5. `mail.php` — Configuración de Email
- **Basado en PHPMailer**.
- Define:
  - **Driver** (`smtp`, `mail`, `sendmail`), remitente por defecto.
  - **SMTP:** host, puerto, usuario, contraseña, cifrado, proveedores comunes (Gmail, Outlook, Yahoo, Sendgrid).
  - **Templates:** path, tipos de email (bienvenida, verificación, reset, 2FA, notificaciones), variables globales.
  - **Cola de emails:** activación, driver, path, batch size.
  - **Logging de emails:** registrar envíos y errores.
  - **Validación de emails:** sintaxis, dominio, MX.
  - **Rate limiting:** por usuario, por tipo de email.
  - **Seguridad:** DKIM, tipos sensibles, headers, encriptación.
  - **Testing:** modo prueba, path de emails de prueba, destinatario por defecto.
  - **Push notifications** (opcional).
  - **Backup de emails enviados**.
- **Advertencias y recomendaciones:**
  - Explica cómo configurar SMTP y personalizar templates.
  - Documenta activación de cola, backups y modo prueba.
  - Mantén segura la configuración de SMTP y destinatarios de prueba.

---

Cada archivo de configuración debe estar bien documentado y adaptado al entorno donde se despliega el sistema. Se recomienda mantener ejemplos y advertencias visibles para el equipo de desarrollo y operaciones.
