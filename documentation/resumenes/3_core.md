# 3. Núcleo de la Aplicación (`app/Core`)

Este documento contiene un resumen técnico y detallado de los componentes principales del núcleo de **Frameworkito**. Incluye propósito, estructura, funciones clave, advertencias y recomendaciones para cada archivo.

---

## 1. `App.php` — Clase Principal de la Aplicación
**Propósito:**
Clase central que coordina el ciclo de vida de la aplicación, la inicialización, la gestión de servicios y la ejecución de cada request.

**Estructura y funciones clave:**
- `__construct($config, $services)`: Recibe la configuración y los servicios inicializados (DB, mail, etc.).
- `run()`: Ejecuta el ciclo principal: inicia sesión, procesa la request, registra logs y maneja excepciones.
- `startSession()`: Inicializa la sesión, valida IP y realiza regeneración periódica del ID de sesión.
- `getClientIp()`: Obtiene la IP real del cliente considerando proxies y headers comunes.
- `processRequest()`: Procesa la request HTTP y delega al router.
- `getRequestUri()`, `handleCorsOptions()`, `sendHttpError()`: Utilidades para manejo de request y errores HTTP.
- `isAjaxRequest()`: Detecta si la petición es AJAX.
- `logRequest()`: Registra información de cada request (método, URI, tiempos, uso de memoria, código de respuesta).
- `handleException($e)`: Manejo centralizado de excepciones con logging y respuesta HTTP adecuada.
- `getConfig($key, $default)`: Permite obtener configuraciones usando notación de puntos.
- `getService($name)`, `getPDO()`: Acceso a servicios globales y la conexión PDO.
- Métodos para detección de entorno: `isDebug()`, `getEnvironment()`, `isProduction()`, `isDevelopment()`, `isTesting()`.
- `getSystemInfo()`: Devuelve información técnica del sistema y entorno para debugging.

**Advertencias y recomendaciones:**
- Centraliza el manejo de errores y logs para facilitar el diagnóstico.
- Usa `getConfig()` para acceder a configuraciones anidadas de forma segura.
- Personaliza la inicialización de sesión y seguridad según las necesidades del proyecto.

---

## 2. `Bootstrap.php` — Inicializador de la Aplicación
**Propósito:**
Encargado de la carga y validación de configuraciones, inicialización de servicios, configuración del entorno PHP y creación de la instancia principal de la aplicación.

**Estructura y funciones clave:**
- `createApplication()`: Orquesta todo el proceso de arranque: carga configs, verifica modo mantenimiento, configura entorno y errores, inicializa servicios y sesiones, aplica seguridad y retorna la instancia de `App`.
- `checkMaintenanceMode()`: Aplica el middleware de mantenimiento según configuración, permitiendo IPs y rutas específicas.
- `loadConfiguration()`: Carga todos los archivos críticos de configuración y valida su existencia.
- `validateConfiguration()`: Valida la presencia de claves críticas como `APP_KEY` y otros parámetros obligatorios.
- `configureEnvironment()`: Ajusta valores de PHP (timezone, encoding, límites de memoria, etc.) según configuración.
- `configureErrorHandling()`, `handleUncaughtException()`: Configura el manejo global de errores y excepciones.
- `initializeServices()`, `initializeDatabase()`: Inicializa servicios básicos y la conexión PDO con opciones seguras.
- `configureSessions()`: Configura los parámetros de sesión (cookies, path, seguridad, almacenamiento).
- `applySecurity()`: Aplica headers de seguridad, fuerza HTTPS y ajusta directivas de PHP para seguridad.
- `isHttps()`: Detecta si la conexión es segura.

**Advertencias y recomendaciones:**
- Mantén los archivos de configuración actualizados y seguros.
- Valida siempre la existencia de las claves críticas antes de arrancar la app.
- Usa los métodos de inicialización para adaptar el entorno a producción o desarrollo.

---

## 3. `Router.php` — Sistema de Ruteo
**Propósito:**
Gestiona el registro, resolución y ejecución de rutas HTTP, así como la integración y ejecución de middlewares globales y por ruta.

**Estructura y funciones clave:**
- `__construct($config, $services)`: Inicializa el router, carga rutas y registra middlewares globales.
- `loadRoutes()`, `loadRoutesFromFile($file, $prefix)`: Carga rutas desde archivos (`web.php`, `api.php`).
- Helpers para definir rutas: `$get`, `$post`, `$put`, `$delete`, `$patch`, `$middleware`.
- `addRoute($method, $uri, $handler, $middlewares)`: Registra rutas con parámetros y middlewares.
- `normalizeUri()`, `convertToRegex()`, `extractParameterNames()`: Utilidades para manejo de URIs y parámetros dinámicos.
- `dispatch($method, $uri)`: Despacha la request ejecutando middlewares y el handler correspondiente.
- `findRoute()`, `registerGlobalMiddlewares()`, `executeGlobalMiddlewares()`, `executeRouteMiddlewares()`, `executeMiddleware()`: Gestión y ejecución de middlewares.
- `executeHandler()`, `executeControllerMethod()`, `executeFunction()`: Ejecuta el handler de la ruta (controlador, función o closure).
- `handleNotFound()`: Maneja rutas no encontradas con respuesta 404 personalizada.
- `validateCsrfToken()`: Protección CSRF configurable.
- `checkRateLimit()`: Rate limiting básico por IP configurable.
- `registerMiddleware($name, $routes)`: Permite asignar middlewares a múltiples rutas.
- `getRoutes()`, `url()`, `hasRoute()`: Utilidades para inspección y generación de rutas.

**Advertencias y recomendaciones:**
- Usa middlewares para seguridad, autenticación y lógica reutilizable.
- Personaliza las respuestas de error y el manejo de rutas no encontradas.
- Configura CSRF y rate limiting según el tipo de aplicación y entorno.

---

Cada componente del núcleo debe estar bien documentado y adaptado para facilitar el mantenimiento, la extensión y la seguridad del sistema.
