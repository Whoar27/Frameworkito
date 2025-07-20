# 📦 CHANGELOG

Todas las modificaciones importantes del sistema serán documentadas aquí.

---

## [1.0.6] - 2025-07-19

### 🔥 Agregado

- Sistema de documentación dinámico con soporte para archivos `.md` desde subdirectorios.
- Enrutamiento flexible en `web.php` para procesar cualquier slug de documentación con `docViewer()`.
- Controlador `docViewer()` implementado con renderizado Markdown mediante `Parsedown`.
- Nueva vista pública `/doc` con renderizado automático del índice de guías disponibles.
- Soporte para enlaces amigables y legibles (`nombre-del-archivo`) tanto en URLs como en nombres visuales.
- Captura y renderizado de errores `404` personalizados.
- Logging extendido para capturar errores 404 con información de IP, URL solicitada y referer.
- Comandos Git ejecutados por grupos funcionales para mayor trazabilidad de commits.

### 🛠️ Modificado

- `BaseController`: se agregó `show404()` mejorado para trabajar con datos personalizados.
- `.htaccess`: adaptado para permitir rutas limpias a través de `/doc/*`.
- `.gitignore` y `composer.json`: ajustes menores de mantenimiento.

### 🐛 Corregido

- Problemas con `htmlspecialchars()` cuando los datos eran `null` en vistas.
- Detección de archivos vinculados por `mklink` y resolución correcta en `docViewer`.
- Visualización duplicada de secciones o etiquetas como `Otros` en el índice.

---

## Estructura

- Este changelog sigue el formato [Keep a Changelog](https://keepachangelog.com/) y versiones semánticas [SemVer](https://semver.org/lang/es/).
- Cambios sin versión previa se encuentran agrupados bajo `[Unreleased]`.

---

