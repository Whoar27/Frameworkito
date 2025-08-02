# Documentación Técnica: Carpeta Pública (`public/`)

La carpeta `public/` es el punto de entrada web de Frameworkito. Contiene todos los archivos y recursos que pueden ser accedidos directamente por el navegador, así como el bootstrap principal de la aplicación.

---

## Estructura General

```
public/
├── .htaccess      # Configuración de Apache para URLs amigables, CORS, compresión y cacheo
├── index.php      # Punto de entrada principal (bootstrap PHP)
├── assets/        # Recursos estáticos (CSS, JS, imágenes, fuentes, vendors)
├── uploads/       # Archivos subidos por usuarios (avatares, documentos, etc.)
```

---

## Archivos Clave

### 1. `.htaccess`
- **Propósito:**
  - Habilita URLs amigables redirigiendo todas las peticiones a `index.php` salvo archivos/directorios reales.
  - Configura CORS para la API (opcional), permitiendo peticiones desde otros orígenes.
  - Maneja peticiones OPTIONS (CORS preflight).
  - Sirve archivos y directorios estáticos directamente.
  - Configura cacheo de recursos estáticos mediante expiraciones.
  - Habilita compresión gzip para mejorar el rendimiento.
- **Recomendaciones:**
  - Revisar y adaptar reglas según necesidades del hosting.
  - No modificar si no se entiende el impacto en seguridad y rendimiento.

### 2. `index.php`
- **Propósito:**
  - Bootstrap principal del sistema.
  - Define rutas y constantes globales.
  - Crea directorios necesarios (logs, storage, uploads) si no existen.
  - Carga helpers y configuración.
  - Inicializa la aplicación vía `App\Core\Bootstrap`.
  - Maneja errores globales mostrando mensajes amigables y logueando detalles críticos.
  - Soporta autoloading manual si Composer no está presente.
  - Registra métricas de performance si el modo debug está activo.
- **Seguridad:**
  - Solo debe exponer lo necesario al público; todo el código sensible y lógica de negocio está fuera de `public/`.
  - Maneja errores de forma segura, mostrando detalles solo en modo debug.

### 3. `assets/`
- **Propósito:**
  - Almacena recursos estáticos como CSS, JS, imágenes, fuentes y librerías de terceros.
  - Organizar por tipo o proveedor para facilitar el mantenimiento.

### 4. `uploads/`
- **Propósito:**
  - Carpeta para archivos subidos por los usuarios (avatares, documentos, etc.).
  - Se crean subcarpetas automáticamente (`uploads/avatars`, `uploads/documents`, etc).
- **Seguridad:**
  - Restringir ejecución de scripts y acceso directo a archivos sensibles mediante reglas adicionales en `.htaccess`.

---

## Buenas Prácticas y Recomendaciones

- No almacenar código fuente sensible ni configuraciones privadas en `public/`.
- Proteger la carpeta `uploads/` para evitar ejecución de scripts maliciosos.
- Mantener la estructura de `assets/` organizada para facilitar el desarrollo frontend.
- Revisar y actualizar `.htaccess` según cambios en el servidor o requerimientos de seguridad.
- No modificar `index.php` salvo para tareas de bootstrap o mantenimiento del sistema base.

---

> **Resumen:**
> La carpeta `public/` expone solo los recursos necesarios para el funcionamiento web del sistema, asegurando que la lógica de negocio y configuraciones permanezcan protegidas fuera del alcance público.
