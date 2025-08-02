# Documentación Técnica: Modelos (`app/Models`)

Este documento describe los modelos principales del sistema Frameworkito, su propósito, estructura, funciones clave, consideraciones de seguridad y recomendaciones de uso. Está orientado a desarrolladores que deseen mantener o extender la capa de acceso a datos del sistema.

---

## 1. User.php

**Propósito:**
Modelo para operaciones básicas sobre usuarios en la base de datos. Permite buscar usuarios por email o por selector de recuperación.

**Funciones clave:**
- `__construct()`: Inicializa la conexión usando el helper `Database`.
- `findByEmail($email)`: Busca un usuario por email y retorna id, email y username.
- `findBySelector($selector)`: Busca un usuario a partir del selector de la tabla `users_resets` (usado en recuperación de contraseña), retorna id, email y username.

**Seguridad:**
- Todas las consultas usan sentencias preparadas para prevenir inyección SQL.
- No expone información sensible del usuario.

**Recomendaciones:**
- Extender este modelo para agregar métodos de gestión de usuarios (crear, actualizar, eliminar, listar, etc.).
- Usar siempre métodos del modelo para acceder a la base de datos, nunca consultas directas desde controladores.

---

## 2. BaseModel.php *(sin implementar)*

**Propósito esperado:**
Modelo base para herencia y reutilización de lógica común entre modelos (por ejemplo, conexión, helpers, validaciones, etc.).

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda crear una clase base abstracta para centralizar lógica común de modelos.

---

## 3. ActivityLog.php *(sin implementar)*

**Propósito esperado:**
Modelo para registrar y consultar logs de actividad de usuarios y sistema.

**Nota:**
- El archivo existe pero no contiene implementación. Se recomienda implementar métodos para registrar eventos, consultar logs y filtrar por usuario, fecha, tipo, etc.

---

> **Nota:** Los modelos deben ser el único punto de acceso a la base de datos desde el sistema, encapsulando la lógica de persistencia y protegiendo contra errores y vulnerabilidades. Se recomienda implementar y documentar los modelos faltantes para mantener la coherencia y seguridad del sistema.
