# Documentación Técnica: Base de Datos (`database/`)

Este documento describe la estructura y organización de los archivos relacionados con la base de datos en el sistema Frameworkito. Incluye la finalidad de cada subcarpeta y recomendaciones para su uso y mantenimiento.

---

## Estructura General

```
database/
├── backups/     # Copias de seguridad de la base de datos
│   └── proyecto_base.sql
├── migrations/  # Scripts de migración para crear y actualizar la base de datos inicial
│   ├── 000_create_migrations_table.sql
│   ├── 001_create_users_table.sql
│   ├── 002_create_users_confirmations_table.sql
│   ├── 003_create_users_remembered_table.sql
│   ├── 004_create_users_resets_table.sql
│   ├── 005_create_users_throttling_table.sql
│   ├── 006_create_roles_table.sql
│   ├── 007_create_users_roles_table.sql
│   ├── 008_create_activity_logs_table.sql
│   └── 009_create_system_settings.sql
├── schema/      # (Vacío) Espacio para diagramas, documentación o snapshots de esquema
├── seeds/       # (Vacío) Scripts para poblar la base de datos con datos iniciales o de prueba
```

---

## Descripción de carpetas y archivos

- **backups/**
  - Almacena copias de seguridad completas de la base de datos. Ejemplo: `proyecto_base.sql`.
  - Recomendación: Realizar backups periódicos y almacenarlos de forma segura.

- **migrations/**
  - Contiene scripts SQL para crear y actualizar tablas y relaciones del sistema.
  - Cada archivo sigue un orden numérico y nombre descriptivo para facilitar el control de versiones.
  - Ejemplo de archivos: creación de usuarios, roles, logs, settings, etc.
  - Recomendación: Ejecutar los scripts en orden y versionar cambios en el esquema.

- **schema/**
  - (Actualmente vacío) Espacio sugerido para guardar diagramas ER, documentación visual del modelo de datos, o snapshots del esquema.
  - Recomendación: Utilizar para mantener documentación visual del modelo de datos.

- **seeds/**
  - (Actualmente vacío) Carpeta destinada a scripts para poblar la base de datos con datos de ejemplo, usuarios de prueba, roles, etc.
  - Recomendación: Agregar seeds para facilitar pruebas y desarrollo.

---

## Buenas Prácticas y Recomendaciones

- Mantener los scripts de migración bajo control de versiones.
- Documentar cualquier cambio en el esquema de la base de datos.
- Realizar y almacenar backups periódicos antes de aplicar migraciones.
- Usar seeds para poblar entornos de desarrollo y testing.
- Mantener la carpeta `schema/` actualizada con diagramas o documentación relevante.

---

> **Resumen:**
> La estructura de `database/` permite gestionar de forma ordenada las migraciones, backups y documentación del modelo de datos, facilitando el mantenimiento y la evolución segura del sistema.
