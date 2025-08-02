# Documentación Técnica: Validadores (`app/Validators`)

Este documento describe los validadores principales del sistema Frameworkito, su propósito esperado y recomendaciones para su implementación. Está orientado a desarrolladores que deseen mantener o extender la validación de datos en el sistema.

---

## 1. LoginValidator.php *(sin implementar)*

**Propósito esperado:**
Validar los datos enviados en el formulario de inicio de sesión (login), asegurando que los campos requeridos estén presentes y sean válidos (email, contraseña, formato, etc.).

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar reglas de validación para login y mensajes de error personalizados.

---

## 2. RegisterValidator.php *(sin implementar)*

**Propósito esperado:**
Validar los datos enviados en el formulario de registro de usuario, comprobando email, contraseña, confirmación, unicidad y formato de los campos.

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar validaciones robustas para registro, incluyendo comprobación de email único y reglas de fortaleza de contraseña.

---

## 3. UserValidator.php *(sin implementar)*

**Propósito esperado:**
Validar datos de usuario en operaciones de actualización de perfil, cambio de contraseña, actualización de email, etc.

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda centralizar aquí todas las reglas de validación relacionadas con el usuario y su perfil.

---

> **Nota:** Centralizar la validación en clases separadas mejora la mantenibilidad, la reutilización y la seguridad del sistema. Se recomienda implementar y documentar los validadores faltantes para fortalecer la robustez de la aplicación.
