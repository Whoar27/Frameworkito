# Frameworkito

![PHP](https://img.shields.io/badge/PHP-8.0+-777bb4?style=flat&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-f29111?style=flat&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-7952b3?style=flat&logo=bootstrap)
![FontAwesome](https://img.shields.io/badge/FontAwesome-6.x-228be6?style=flat&logo=fontawesome)
![VanillaJS](https://img.shields.io/badge/JS-Vanilla-f7df1e?style=flat&logo=javascript)
![Notiwhoar](https://img.shields.io/badge/Notiwhoar-1.0.5-00b894?style=flat)


¡Bienvenido a **Frameworkito**! 🚀

Frameworkito es un mini framework PHP MVC que te ofrece mucho más que una base de código:

- Incluye un **website completo** y un **panel de administración** listos para usar, modificar o adaptar a cualquier proyecto.
- Todo está construido con **PHP vanilla**, **JavaScript vanilla**, **Bootstrap 5** y **FontAwesome** para máxima compatibilidad y facilidad de personalización.
- Integra una **librería propia de notificaciones** (notiwhoar) con toasts, modales, confirmaciones, entradas de usuario, notificaciones de espera y personalización avanzada.

Ideal para aprender, prototipar, lanzar MVPs o como punto de partida profesional para sistemas reales.

---

## 📋 Tabla de Contenidos
- [¿Qué es Frameworkito?](#qué-es-frameworkito)
- [Requisitos Previos](#requisitos-previos)
- [Instalación Rápida](#instalación-rápida)
- [Configuración Inicial](#configuración-inicial)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Uso Básico](#uso-básico)
- [Despliegue y Buenas Prácticas](#despliegue-y-buenas-prácticas)
- [Pruebas](#pruebas)
- [Gestión de Logs y Almacenamiento](#gestión-de-logs-y-almacenamiento)
- [Notas para Desarrollo y Contribución](#notas-para-desarrollo-y-contribución)
- [Licencia y Créditos](#licencia-y-créditos)

---

## ¿Qué es Frameworkito?

**Frameworkito** es una base inicial (starter kit) para proyectos PHP que sigue el patrón MVC (Modelo-Vista-Controlador). Está diseñado para que puedas crear desde sitios web administrables (como blogs, portafolios o landings) hasta sistemas de gestión (inventarios, agendas, etc.), sin preocuparte por implementar desde cero la autenticación, recuperación de contraseñas, envío de correos y confirmación de cambios de contraseña.

Incluye autenticación lista para usar, helpers, middlewares, sistema de rutas flexible, pruebas automatizadas y una estructura clara para que puedas enfocarte en la lógica específica de tu aplicación.

- 100% PHP moderno (>=8.0)
- Fácil de entender, modificar y extender
- Ideal para proyectos pequeños, medianos, prototipos o aprendizaje
- ¡Sin dependencias innecesarias

---

## Requisitos Previos

- PHP >= 8.0
- Extensiones: PDO, mbstring, openssl, json
- Composer (para gestión de dependencias)
- Servidor web (Apache recomendado)
- Base de datos MySQL (o compatible)

---

## Instalación Rápida

1. **Clona el repositorio:**
   ```bash
   git clone https://github.com/tuusuario/frameworkito.git
   cd frameworkito
   ```
2. **Instala dependencias:**
   ```bash
   composer install
   ```
3. **Copia el archivo de entorno y configúralo:**
   ```bash
   cp .env.example .env
   # Edita .env con tus datos de base de datos, correo, etc.
   ```
4. **Configura permisos de carpetas:**
   - Asegúrate de que `storage/`, `logs/` y `public/uploads/` sean escribibles por el servidor web.
5. **Configura tu servidor web:**
   - El directorio raíz debe ser `public/`.
   - Si usas Apache, asegúrate de que `.htaccess` esté habilitado.

---

## Configuración Inicial

Toda la configuración sensible (entorno, base de datos, correo, seguridad, etc.) se gestiona desde el archivo `.env`. Algunos puntos clave:

- **APP_ENV, APP_DEBUG:** Define el entorno y si se muestran errores detallados.
- **DB_*:** Configura la conexión a la base de datos.
- **MAIL_*, LOG_*, CSRF_PROTECTION:** Ajusta correo, logging y seguridad.
- **No subas tu .env a repositorios públicos.**

---

## Comandos útiles (Composer Scripts)

Estos comandos te facilitan tareas comunes del proyecto desde la terminal:

- **Generar clave de aplicación (.env):**
  ```bash
  composer generate-key
  ```
  Genera una nueva APP_KEY segura y la agrega automáticamente a tu archivo `.env`.

- **Ejecutar migraciones de base de datos:**
  ```bash
  composer migrate
  ```
  Ejecuta todas las migraciones pendientes para crear o actualizar la estructura de tu base de datos.

  Opciones avanzadas:

  | Comando                              | Descripción                                      |
  |--------------------------------------|--------------------------------------------------|
  | `composer migrate-status`     | Muestra el estado de las migraciones              |
  | `composer migrate-rollback`   | Revierte el último batch de migraciones           |
  | `composer migrate-fresh`      | Elimina y recrea todas las tablas/migraciones     |
  | `composer migrate`            | Ejecuta todas las migraciones pendientes (default)|

- **Sembrar datos iniciales (seeds):**
  ```bash
  composer seed
  ```
  Inserta datos de ejemplo o usuarios iniciales en la base de datos.

Puedes combinar estos comandos para preparar tu entorno de desarrollo rápidamente.

---

## Estructura del Proyecto

```
├── app/           # Lógica de negocio: controladores, modelos, middlewares, helpers, servicios, etc.
├── public/        # Punto de entrada web, recursos estáticos y uploads
├── database/      # Migraciones, seeds, backups y documentación de base de datos
├── storage/       # Archivos temporales, caché y sesiones
├── logs/          # Archivos de log del sistema
├── tests/         # Pruebas unitarias y de integración
├── documentation/ # (Opcional) Documentación técnica modular
├── .env           # Configuración de entorno (no subir a repositorios)
├── composer.json  # Dependencias y autoload PHP
├── README.md      # Este archivo
```

---

## Uso Básico

- Accede a la aplicación en tu navegador: `http://localhost` (o la URL configurada)
- Rutas principales: login, registro, recuperación de contraseña, perfil, panel de usuario, administración, etc.
- Los controladores y vistas pueden personalizarse fácilmente para tu proyecto.

---

## Notificaciones y Modales: Notiwhoar

Frameworkito incluye **notiwhoar**, una librería propia para notificaciones y modales, lista para usar en cualquier parte del sistema (website y panel de administración). Puedes mostrar toasts, confirmaciones, entradas de usuario y modales personalizados de forma sencilla y elegante.

### Ejemplos rápidos

#### Toast de éxito
```js
exito('¡Operación realizada con éxito!');
```

#### Toast de error con opciones
```js
error('Ocurrió un error inesperado', { posicion: 'inferior-centro', permitirCerrar: true });
```

#### Confirmación antes de eliminar
```js
confirmacion({
  tipo: 'warning',
  titulo: '¿Eliminar registro?',
  contenido: 'Esta acción no se puede deshacer',
  textoBtnConfirmar: 'Sí, eliminar',
  textoBtnCancelar: 'Cancelar'
}).then(respuesta => {
  if (respuesta) {
    exito('Eliminado correctamente');
  }
});
```

#### Solicitar entrada de texto
```js
solicitarEntrada({
  titulo: 'Nuevo nombre',
  mensaje: '¿Cómo quieres llamar este elemento?',
  tipo: 'text',
  requerido: true
}).then(valor => {
  if (valor) {
    exito('Nombre actualizado: ' + valor);
  }
});
```

#### Modal personalizado
```js
modal({
  titulo: 'Información importante',
  contenido: '<p>Este es un modal <b>personalizado</b>.</p>',
  html: true
});
```

¿Quieres conocer todas las opciones, ejemplos y trucos de notiwhoar? Consulta la [Guía avanzada de notiwhoar](documentation/notiwhoar.md) 📚

---

## Despliegue y Buenas Prácticas

- **No expongas carpetas sensibles:** Solo `public/` debe ser accesible desde la web.
- **Protege uploads y logs:** Usa reglas en `.htaccess` para evitar ejecución de scripts.
- **Realiza backups periódicos** de la base de datos y archivos importantes.
- **Configura el entorno de producción:** Desactiva `APP_DEBUG` y usa credenciales seguras.

---

## Pruebas

- Las pruebas automatizadas están en la carpeta `tests/`.
- Puedes ejecutar los tests con:
  ```bash
  composer test
  ```
- Separa pruebas unitarias de integración para mantener la calidad del código.

---

## Gestión de Logs y Almacenamiento

- Los logs del sistema se guardan en la carpeta `logs/`.
- Los archivos temporales y sesiones están en `storage/`.
- Los archivos subidos por usuarios van a `public/uploads/`.
- Limpia periódicamente archivos antiguos y protege el acceso a estas carpetas.

---

## Notas para Desarrollo y Contribución

- Sigue la estructura de carpetas y convenciones del sistema.
- Los helpers, middlewares y servicios pueden extenderse según tus necesidades.
- Si encuentras un bug o quieres contribuir, ¡abre un issue o pull request!
- Mantén tus dependencias actualizadas con `composer update`.

---

## Licencia y Créditos

Frameworkito es open source bajo licencia MIT.

Desarrollado con cariño por whoar27 💙

---

¡Gracias por usar Frameworkito! Si tienes dudas o sugerencias, no dudes en contactarnos o contribuir al proyecto.
