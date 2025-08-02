# Documentación Técnica: Servicios (`app/Services`)

Este documento describe los servicios principales del sistema Frameworkito, su propósito, estructura, funciones clave, consideraciones de seguridad y recomendaciones de uso. Está orientado a desarrolladores que deseen mantener o extender la capa de servicios del sistema.

---

## 1. EmailService.php

**Propósito:**
Gestiona el envío de correos electrónicos transaccionales (recuperación de contraseña, confirmaciones, notificaciones) utilizando PHPMailer y plantillas HTML.

**Funciones clave:**
- `__construct(array $config = [])`: Inicializa el servicio con la configuración de correo.
- `sendPasswordReset($toEmail, $toName, $resetLink, $companyName)`: Envía correo de recuperación de contraseña.
- `sendPasswordChanged($toEmail, $toName, $companyName)`: Envía confirmación de cambio de contraseña.
- `send($fromName, $toEmail, $toName, $subject, $body)`: Envía un correo genérico usando PHPMailer.
- `renderTemplate($filePath, array $vars = [])`: Renderiza una plantilla HTML reemplazando variables.

**Seguridad:**
- Validación estricta de configuración SMTP antes de enviar correos.
- Plantillas HTML con variables escapadas para prevenir XSS.
- Logging de intentos y errores de envío.

**Recomendaciones:**
- Configurar correctamente los parámetros SMTP en `app/Config/mail.php`.
- Personalizar las plantillas de email según la identidad del sistema.
- Revisar logs ante fallos de envío.

---

## 2. ActivityLogService.php *(sin implementar)*

**Propósito esperado:**
Servicio para registrar y consultar logs de actividad de usuarios y sistema.

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar métodos para auditar acciones relevantes y consultar logs.

---

## 3. AuthService.php *(sin implementar)*

**Propósito esperado:**
Servicio para encapsular la lógica de autenticación, registro, login, logout, recuperación y verificación de usuarios.

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda centralizar aquí toda la lógica de autenticación y validación de credenciales.

---

## 4. LogService.php *(sin implementar)*

**Propósito esperado:**
Servicio para registrar logs de sistema (errores, accesos, eventos) en archivos o base de datos.

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar métodos para registrar, consultar y limpiar logs, integrando con los helpers de logging.

---

## 5. UserService.php *(sin implementar)*

**Propósito esperado:**
Servicio para operaciones avanzadas sobre usuarios (gestión, actualización, borrado, roles, etc.), separando la lógica de negocio del modelo.

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar métodos para gestión avanzada de usuarios y lógica de negocio relacionada.

---

> **Nota:** Los servicios permiten separar la lógica de negocio y tareas complejas de los controladores y modelos, promoviendo un diseño limpio y mantenible. Se recomienda implementar y documentar los servicios faltantes para fortalecer la arquitectura del sistema.
