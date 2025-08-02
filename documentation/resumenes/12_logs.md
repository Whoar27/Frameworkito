# Documentación Técnica: Carpeta de Logs (`logs/`)

La carpeta `logs/` está destinada a almacenar los archivos de registro (logs) generados por el sistema Frameworkito. Estos logs permiten auditar el funcionamiento, detectar errores, monitorear accesos y facilitar el soporte y la depuración.

---

## Estructura General

```
logs/
├── [canales y archivos de log generados por el sistema]
```

> **Nota:** Se omite la carpeta `logs/apache/` ya que es específica del entorno local del desarrollador y no forma parte del sistema base.

---

## Principios y Recomendaciones

- **Canales de log configurables:**
  - El sistema puede generar distintos archivos de log según el canal (por ejemplo: `auth.log`, `error.log`, `activity.log`, `system.log`, etc.), permitiendo separar eventos y facilitar la revisión.
- **Rotación y limpieza:**
  - Se recomienda implementar rotación automática de logs para evitar archivos excesivamente grandes y conservar solo los registros recientes o relevantes.
- **Seguridad:**
  - Los archivos de log pueden contener información sensible (errores, IPs, identificadores de usuario, etc.), por lo que deben protegerse contra accesos no autorizados y no exponerse públicamente.
- **Auditoría y trazabilidad:**
  - Los logs son clave para la auditoría de acciones (login, cambios críticos, errores, accesos administrativos, etc.) y deben conservarse el tiempo necesario según la política del proyecto.
- **Integración con helpers y servicios:**
  - El sistema provee helpers y servicios para registrar eventos en los logs de manera centralizada y consistente.

---

## Buenas Prácticas

- No almacenar logs en repositorios públicos ni compartirlos sin anonimizar información sensible.
- Revisar periódicamente los logs para detectar patrones de error o intentos de acceso no autorizado.
- Configurar la ubicación y permisos de la carpeta `logs/` según el entorno (producción, desarrollo, testing).
- Automatizar la limpieza y rotación de logs para evitar consumo excesivo de disco.

---

> **Resumen:**
> La carpeta `logs/` centraliza los registros del sistema y es fundamental para la operación segura, el soporte y la auditoría. Su gestión debe ser cuidadosa y adaptada a las necesidades y políticas de cada proyecto.
