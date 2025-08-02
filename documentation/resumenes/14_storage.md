# Documentación Técnica: Carpeta de Almacenamiento (`storage/`)

La carpeta `storage/` es utilizada por el sistema Frameworkito para almacenar archivos temporales, cachés y sesiones de usuario. Su contenido es dinámico y depende del uso y configuración del sistema.

---

## Estructura General

```
storage/
├── cache/         # Archivos de caché generados por la aplicación (vacío por defecto)
├── sessions/      # Archivos de sesión PHP (1 archivo de ejemplo: sess_*)
```

---

## Descripción de carpetas

- **cache/**
  - Almacena archivos de caché generados por la aplicación (por ejemplo, vistas compiladas, resultados de consultas, fragmentos temporales, etc.).
  - Actualmente vacía, se pobla automáticamente según la lógica implementada.
  - Recomendación: Limpiar periódicamente para evitar acumulación innecesaria.

- **sessions/**
  - Almacena archivos de sesión PHP, cada uno correspondiente a un usuario/logueo activo (ejemplo: `sess_mbv8mt14fflc7d4h54ibfb4bib`).
  - Se generan automáticamente por PHP si la configuración de sesiones está dirigida a esta carpeta.
  - Recomendación: Proteger el acceso a esta carpeta para evitar la exposición de datos sensibles y limpiar sesiones expiradas regularmente.

---

## Buenas Prácticas y Recomendaciones

- Nunca almacenar información sensible o persistente en `storage/` sin protección adecuada.
- Configurar permisos restrictivos para evitar accesos no autorizados.
- Automatizar la limpieza de archivos temporales y sesiones expiradas.
- Excluir esta carpeta de los repositorios públicos, pero mantener su estructura para despliegues.

---

> **Resumen:**
> La carpeta `storage/` centraliza archivos temporales y de sesión, facilitando la gestión y limpieza de recursos efímeros del sistema.
