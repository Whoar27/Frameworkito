# Guía completa para trabajar con Git y GitHub en tu proyecto

Esta guía está diseñada especialmente para **personas que están empezando** con Git y GitHub. Te explicaremos paso a paso todo lo que necesitas saber para gestionar el código de tu proyecto.

---

## 🤔 ¿Qué es Git y GitHub?

**Git** es un sistema de control de versiones que te permite:
- Guardar un historial de todos los cambios en tu código
- Volver a versiones anteriores si algo se rompe
- Trabajar en equipo sin conflictos

**GitHub** es una plataforma web donde puedes:
- Almacenar tu código en la nube (como un Google Drive para programadores)
- Compartir tu proyecto con otros
- Colaborar con más desarrolladores

---

## 🚀 Subir el proyecto por primera vez a GitHub

### Paso 1: Crear un repositorio en GitHub

Un **repositorio** (o "repo") es como una carpeta en GitHub donde vivirá tu proyecto.

1. **Abre tu navegador** y ve a https://github.com/new
2. **Inicia sesión** con tu cuenta de GitHub (si no tienes una, créala en https://github.com)
3. **Completa el formulario:**
   - **Repository name:** Escribe el nombre de tu proyecto (ejemplo: `mi-proyecto-web`)
   - **Description:** Una breve descripción de lo que hace tu proyecto (opcional)
   - **Public/Private:** Deja en "Public" si quieres que cualquiera pueda ver tu código
   - ⚠️ **IMPORTANTE:** NO marques las casillas de "Add a README file", "Add .gitignore" o "Choose a license"
4. **Haz clic en "Create repository"**

### Paso 2: Preparar tu proyecto local

Antes de subir tu código, necesitas preparar tu computadora. Abre la **terminal** (Command Prompt en Windows, Terminal en Mac/Linux) y navega hasta la carpeta de tu proyecto.

```bash
# Navegar a la carpeta del proyecto (ejemplo)
cd ruta/a/tu/proyecto
```

### Paso 3: Inicializar Git en tu proyecto

Git necesita "activarse" en tu proyecto. Esto se hace solo una vez:

```bash
git init
```

**¿Qué hace este comando?**
- Crea una carpeta oculta llamada `.git` donde Git guardará toda la información del historial
- Tu carpeta ahora es oficialmente un "repositorio de Git"

### Paso 4: Agregar todos los archivos

Ahora le decimos a Git que queremos incluir todos los archivos de nuestro proyecto:

```bash
git add .
```

**¿Qué significa el punto (.)?**
- El punto significa "todos los archivos y carpetas del directorio actual"
- Git ahora está "vigilando" todos tus archivos para detectar cambios

### Paso 5: Hacer tu primer commit

Un **commit** es como tomar una "fotografía" del estado actual de tu proyecto:

```bash
git commit -m "Carga inicial: primer versión del proyecto"
```

**¿Qué hace esto?**
- Guarda todos los cambios con un mensaje descriptivo
- El texto entre comillas es el mensaje que te ayudará a recordar qué cambiaste
- Siempre usa mensajes claros como "Agregué página de contacto" o "Corregí error en login"

### Paso 6: Conectar con GitHub

Ahora necesitas decirle a Git dónde está tu repositorio en GitHub:

```bash
git remote add origin https://github.com/TU-USUARIO/nombre-de-tu-repo.git
```

**¡IMPORTANTE!** Cambia:
- `TU-USUARIO` por tu nombre de usuario real de GitHub
- `nombre-de-tu-repo` por el nombre exacto que pusiste en el paso 1

**¿Qué hace este comando?**
- `remote` significa "repositorio remoto" (el que está en GitHub)
- `origin` es un nombre que se usa por convención para el repositorio principal
- La URL es la dirección de tu repositorio en GitHub

### Paso 7: Subir tu código a GitHub

Finalmente, enviamos todo a GitHub:

```bash
git branch -M main
git push -u origin main
```

**¿Qué hace cada comando?**
- `git branch -M main`: Asegura que estés en la rama principal llamada "main"
- `git push -u origin main`: Sube todo tu código al repositorio de GitHub

### Paso 8: Verificar que todo funcionó

1. **Ve a tu repositorio en GitHub** (refresca la página)
2. **Deberías ver todos tus archivos** listados en la página
3. **Si ves tu código ahí, ¡felicitaciones! 🎉**

---

## 💡 Subir cambios nuevos o actualizaciones

Cada vez que modifiques tu proyecto (agregues nuevas páginas, corrijas bugs, etc.), necesitas "actualizar" tu repositorio en GitHub.

### Paso 1: Ver qué has cambiado

Antes de subir cambios, siempre revisa qué archivos has modificado:

```bash
git status
```

**¿Qué verás?**
- **Archivos en rojo:** Han sido modificados pero Git aún no los está "vigilando"
- **Archivos en verde:** Ya están listos para el próximo commit
- **Archivos "untracked":** Son nuevos y Git nunca los ha visto

### Paso 2: Agregar los cambios

Tienes dos opciones:

**Opción A: Agregar todo (recomendado para principiantes)**
```bash
git add .
```

**Opción B: Agregar archivos específicos**
```bash
git add archivo1.php
git add carpeta/archivo2.css
```

### Paso 3: Hacer un commit con tus cambios

```bash
git commit -m "Describe qué cambiaste"
```

**Ejemplos de buenos mensajes:**
- `"Agregué página de contacto"`
- `"Corregí error en el formulario de login"`
- `"Actualicé estilos de la página principal"`
- `"Agregué validación de email"`

**Ejemplos de malos mensajes:**
- `"cambios"` (muy vago)
- `"fix"` (no dice qué se arregló)
- `"asdfgh"` (sin sentido)

### Paso 4: Subir los cambios a GitHub

```bash
git push
```

**¿Qué hace esto?**
- Envía todos tus nuevos commits a GitHub
- Tu código en GitHub se actualiza automáticamente
- Otras personas pueden ver tus cambios

---

## 🛡️ Buenas prácticas y consejos importantes

### ❌ Archivos que NUNCA debes subir

**Archivo `.env`**
- Contiene contraseñas y datos sensibles
- Ya está excluido en `.gitignore`, pero verifica que no aparezca en `git status`

**Carpeta `vendor/`**
- Contiene librerías de PHP instaladas con Composer
- Se regenera automáticamente, no necesitas subirla

**Carpeta `node_modules/`**
- Si usas Node.js, esta carpeta es muy pesada
- También se regenera automáticamente

**Archivos temporales**
- Logs (`.log`)
- Archivos de respaldo (`.backup`, `.bak`)
- Archivos del sistema (`.DS_Store` en Mac, `Thumbs.db` en Windows)

### ✅ Archivos que SÍ debes subir

**`composer.json` y `composer.lock`**
- Necesarios para que otros puedan instalar las mismas librerías

**`package.json` y `package-lock.json`**
- Si usas Node.js, estos archivos son importantes

**`README.md`**
- Documenta qué hace tu proyecto y cómo usarlo

**`LICENSE`**
- Define cómo otros pueden usar tu código

### 💡 Consejos útiles

1. **Haz commits frecuentes:** Es mejor hacer muchos commits pequeños que uno gigante
2. **Mensajes descriptivos:** Siempre explica qué cambiaste y por qué
3. **Revisa antes de subir:** Usa `git status` para ver qué vas a subir
4. **No tengas miedo:** Git guarda todo el historial, siempre puedes volver atrás

---

## 🛠️ Comandos útiles y qué hace cada uno

### Comandos básicos

**`git status`**
- **Qué hace:** Muestra el estado actual de tu repositorio
- **Cuándo usarlo:** Antes de hacer commits para ver qué archivos han cambiado
- **Ejemplo de salida:** Te dice qué archivos están modificados, cuáles están listos para commit, etc.

**`git add .`**
- **Qué hace:** Prepara todos los archivos modificados para el próximo commit
- **Cuándo usarlo:** Después de hacer cambios y antes de hacer commit
- **Alternativa:** `git add archivo.php` para agregar solo un archivo específico

**`git commit -m "mensaje"`**
- **Qué hace:** Guarda una "fotografía" de tus cambios con un mensaje descriptivo
- **Cuándo usarlo:** Después de `git add` y cuando quieras guardar tus cambios
- **Tip:** El mensaje debe explicar qué cambiaste

**`git push`**
- **Qué hace:** Envía tus commits locales a GitHub
- **Cuándo usarlo:** Después de hacer uno o varios commits
- **Resultado:** Tu código en GitHub se actualiza

**`git pull`**
- **Qué hace:** Descarga los cambios más recientes de GitHub a tu computadora
- **Cuándo usarlo:** Si trabajas desde varias computadoras o con más personas
- **Importante:** Hazlo antes de empezar a trabajar cada día

### Comandos para ver información

**`git log`**
- **Qué hace:** Muestra el historial completo de commits
- **Cuándo usarlo:** Para ver qué cambios se han hecho y cuándo
- **Tip:** Presiona `q` para salir de la vista

**`git log --oneline`**
- **Qué hace:** Muestra el historial de commits en una sola línea cada uno
- **Cuándo usarlo:** Para tener una vista rápida del historial
- **Más fácil de leer** que `git log` normal

**`git diff`**
- **Qué hace:** Muestra exactamente qué líneas han cambiado en tus archivos
- **Cuándo usarlo:** Para revisar tus cambios antes de hacer commit
- **Útil para:** Verificar que no agregaste código por error

### Comandos para deshacer cosas

**`git checkout -- archivo.php`**
- **Qué hace:** Restaura un archivo a su estado del último commit
- **Cuándo usarlo:** Si rompiste algo y quieres volver a la versión anterior
- **⚠️ CUIDADO:** Perderás todos los cambios no guardados en ese archivo

**`git reset HEAD archivo.php`**
- **Qué hace:** Quita un archivo del "área de preparación" (después de `git add`)
- **Cuándo usarlo:** Si agregaste un archivo por error con `git add`
- **No borra cambios:** Solo "des-agrega" el archivo

**`git reset --soft HEAD~1`**
- **Qué hace:** Deshace el último commit pero mantiene los cambios
- **Cuándo usarlo:** Si te equivocaste en el mensaje del commit
- **Para principiantes:** Úsalo solo si acabas de hacer el commit

### Comandos de configuración

**`git config --global user.name "Tu Nombre"`**
- **Qué hace:** Configura tu nombre para todos los repositorios
- **Cuándo usarlo:** La primera vez que usas Git
- **Aparecerá:** En todos tus commits

**`git config --global user.email "tu@email.com"`**
- **Qué hace:** Configura tu email para todos los repositorios
- **Cuándo usarlo:** La primera vez que usas Git
- **Importante:** Usa el mismo email de tu cuenta de GitHub

**`git remote -v`**
- **Qué hace:** Muestra qué repositorios remotos tienes configurados
- **Cuándo usarlo:** Para verificar que estás conectado al repositorio correcto
- **Deberías ver:** La URL de tu repositorio de GitHub

### Comandos para situaciones especiales

**`git clone https://github.com/usuario/repo.git`**
- **Qué hace:** Descarga un repositorio completo de GitHub a tu computadora
- **Cuándo usarlo:** Para trabajar en un proyecto existente o desde otra computadora
- **Crea:** Una nueva carpeta con todo el código

**`git stash`**
- **Qué hace:** Guarda temporalmente tus cambios sin hacer commit
- **Cuándo usarlo:** Si necesitas cambiar de rama pero no quieres hacer commit aún
- **Para recuperar:** Usa `git stash pop`

**`git branch`**
- **Qué hace:** Muestra todas las ramas de tu repositorio
- **Cuándo usarlo:** Para ver en qué rama estás trabajando
- **La rama actual:** Aparece con un asterisco (*)

### Comandos para versiones y CHANGELOG

**`git tag v1.0.0`**
- **Qué hace:** Crea una etiqueta (tag) para marcar una versión específica de tu proyecto
- **Cuándo usarlo:** Cuando lances una nueva versión estable de tu proyecto
- **Ejemplo:** `git tag v1.0.0`, `git tag v1.1.0`, `git tag v2.0.0`

**`git tag -a v1.0.0 -m "Primera versión estable"`**
- **Qué hace:** Crea una etiqueta anotada con un mensaje descriptivo
- **Cuándo usarlo:** Para versiones importantes donde quieres agregar más información
- **Mejor práctica:** Siempre usar etiquetas anotadas para releases oficiales

**`git push origin --tags`**
- **Qué hace:** Sube todas las etiquetas (tags) a GitHub
- **Cuándo usarlo:** Después de crear tags localmente
- **Resultado:** Las versiones aparecerán en la sección "Releases" de GitHub

**`git tag -l`**
- **Qué hace:** Lista todas las etiquetas existentes en tu repositorio
- **Cuándo usarlo:** Para ver qué versiones has creado anteriormente
- **Útil para:** Decidir qué número de versión usar para el siguiente release

**`git show v1.0.0`**
- **Qué hace:** Muestra información detallada sobre una etiqueta específica
- **Cuándo usarlo:** Para ver qué cambios incluye una versión particular
- **Incluye:** El commit asociado, fecha, autor y cambios

---

## 📝 Gestión de versiones y CHANGELOG

### ¿Qué es un CHANGELOG?

Un **CHANGELOG.md** es un archivo que documenta todos los cambios importantes en cada versión de tu proyecto. Es como un "historial de mejoras" que ayuda a los usuarios a entender qué ha cambiado entre versiones.

### Estructura recomendada para CHANGELOG.md

```markdown
# Changelog

All notable changes to this project will be documented in this file.

## [v1.2.0] - 2024-03-15

### Added
- Nueva página de contacto
- Sistema de validación de formularios
- Integración con API de correos

### Changed
- Mejorado el diseño de la página principal
- Actualizada la librería de Bootstrap a v5.3

### Fixed
- Corregido error en el login de usuarios
- Solucionado problema de responsive en móviles

## [v1.1.0] - 2024-02-28

### Added
- Panel de administración básico
- Sistema de logs

### Fixed
- Corregidos errores menores de CSS

## [v1.0.0] - 2024-02-01

### Added
- Versión inicial del proyecto
- Sistema de autenticación
- Páginas públicas básicas
```

### Flujo completo: Código → Versión → CHANGELOG

**Paso 1: Terminar tus cambios**
```bash
git add .
git commit -m "Agregada página de contacto y validaciones"
git push
```

**Paso 2: Actualizar el CHANGELOG.md**
- Abre el archivo `CHANGELOG.md` (créalo si no existe)
- Agrega una nueva sección con la versión que vas a lanzar
- Documenta todos los cambios bajo las categorías: Added, Changed, Fixed, Removed

**Paso 3: Hacer commit del CHANGELOG**
```bash
git add CHANGELOG.md
git commit -m "Actualizado CHANGELOG para v1.2.0"
```

**Paso 4: Crear el tag de versión**
```bash
git tag -a v1.2.0 -m "Versión 1.2.0: Agregada página de contacto y mejoras de UX"
```

**Paso 5: Subir todo a GitHub**
```bash
git push
git push origin --tags
```

### Convenciones para números de versión

Usa **Versionado Semántico** (SemVer):

**`MAYOR.MENOR.PARCHE` (ejemplo: v2.1.3)**

- **MAYOR** (v2.0.0): Cambios que rompen compatibilidad
- **MENOR** (v1.1.0): Nuevas funcionalidades sin romper compatibilidad
- **PARCHE** (v1.0.1): Solo corrección de errores

**Ejemplos prácticos:**
- `v1.0.0` → `v1.0.1`: Corregiste un bug
- `v1.0.1` → `v1.1.0`: Agregaste una nueva página
- `v1.5.2` → `v2.0.0`: Cambiaste completamente la estructura

### Categorías para el CHANGELOG

**Added** 🆕
- Nuevas funcionalidades
- Nuevas páginas
- Nuevas integraciones

**Changed** 🔄
- Modificaciones a funcionalidades existentes
- Mejoras de rendimiento
- Cambios de diseño

**Fixed** 🐛
- Corrección de errores
- Solución de problemas de seguridad
- Arreglos de bugs

**Removed** ❌
- Funcionalidades eliminadas
- Archivos eliminados
- APIs deprecadas

### Ventajas de usar versiones y CHANGELOG

1. **Para ti:**
   - Llevas un registro claro de los cambios
   - Puedes volver a versiones anteriores fácilmente
   - Organizas mejor tu trabajo

2. **Para otros desarrolladores:**
   - Entienden rápidamente qué ha cambiado
   - Pueden decidir si actualizar o no
   - Facilita la colaboración

3. **Para el proyecto:**
   - Se ve más profesional
   - Facilita el mantenimiento
   - Ayuda en la planificación de futuras versiones

---

- **[Documentación oficial de Git](https://git-scm.com/doc)** - Guía completa oficial
- **[GitHub Docs](https://docs.github.com/es)** - Documentación de GitHub en español
- **[Git Cheat Sheet](https://education.github.com/git-cheat-sheet-education.pdf)** - Hoja de referencia rápida
- **[Atlassian Git Tutorial](https://www.atlassian.com/es/git/tutorials)** - Tutorial interactivo muy bueno

---

## 🎯 Siguiente paso: ¡Practicar!

La mejor manera de aprender Git es **usándolo todos los días**. Cada vez que hagas un cambio en tu proyecto:

1. `git status` (para ver qué cambió)
2. `git add .` (para preparar los cambios)
3. `git commit -m "descripción del cambio"` (para guardar)
4. `git push` (para subir a GitHub)

**¡No tengas miedo de experimentar!** Git está diseñado para que puedas deshacer casi cualquier cosa. Con el tiempo, estos comandos se volverán automáticos.

¡Tu proyecto está listo para crecer y evolucionar de manera profesional! 🚀