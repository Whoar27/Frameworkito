@echo off
echo ===============================================
echo    CREANDO ESTRUCTURA AUTHMANAGER BASE
echo ===============================================
echo.

REM Crear directorio principal
@REM mkdir auth-base
@REM cd auth-base

echo [1/10] Creando archivos de configuracion raiz...
REM Archivos de configuración raíz
echo. > .htaccess
echo. > .env.example
echo. > .gitignore
echo. > composer.json
echo. > composer.lock
echo. > README.md

echo [2/10] Creando estructura /app...
REM Crear estructura /app
mkdir app
cd app

REM Subdirectorios de /app
mkdir config
mkdir controllers
mkdir models
mkdir views
mkdir helpers
mkdir middlewares
mkdir services
mkdir validators
mkdir routes

echo [3/10] Creando archivos de configuracion...
REM Archivos de configuración
cd config
echo. > app.php
echo. > auth.php
echo. > database.php
echo. > mail.php
echo. > logging.php
cd..

echo [4/10] Creando controladores...
REM Archivos de controladores
cd controllers
echo. > BaseController.php
echo. > AuthController.php
echo. > UserController.php
echo. > HomeController.php
cd..

echo [5/10] Creando modelos...
REM Archivos de modelos
cd models
echo. > BaseModel.php
echo. > User.php
echo. > ActivityLog.php
cd..

echo [6/10] Creando vistas...
REM Estructura de vistas
cd views
mkdir layouts
mkdir auth
mkdir user
mkdir components
mkdir errors

REM Archivos de layouts
cd layouts
echo. > app.php
echo. > auth.php
echo. > guest.php
cd..

REM Archivos de autenticación
cd auth
echo. > login.php
echo. > register.php
echo. > forgot-password.php
echo. > reset-password.php
echo. > verify-email.php
echo. > two-factor.php
cd..

REM Archivos de usuario
cd user
echo. > dashboard.php
echo. > profile.php
echo. > settings.php
cd..

REM Componentes
cd components
echo. > header.php
echo. > footer.php
echo. > navbar.php
echo. > alerts.php
cd..

REM Páginas de error
cd errors
echo. > 404.php
echo. > 403.php
echo. > 500.php
cd..

cd..

echo [7/10] Creando helpers y servicios...
REM Archivos de helpers
cd helpers
echo. > functions.php
echo. > Auth.php
echo. > Session.php
echo. > Validator.php
echo. > Redirect.php
echo. > Utils.php
echo. > FileLogger.php
echo. > DatabaseLogger.php
cd..

REM Archivos de middlewares
cd middlewares
echo. > AuthMiddleware.php
echo. > GuestMiddleware.php
echo. > RoleMiddleware.php
echo. > CSRFMiddleware.php
cd..

REM Archivos de servicios
cd services
echo. > AuthService.php
echo. > UserService.php
echo. > EmailService.php
echo. > LogService.php
echo. > ActivityLogService.php
cd..

REM Archivos de validadores
cd validators
echo. > LoginValidator.php
echo. > RegisterValidator.php
echo. > UserValidator.php
cd..

REM Archivos de rutas
cd routes
echo. > web.php
echo. > api.php
cd..

cd..

echo [8/10] Creando estructura /public...
REM Crear estructura /public
mkdir public
cd public

echo. > .htaccess
echo. > index.php

REM Subdirectorios de assets
mkdir assets
cd assets
mkdir css
mkdir js
mkdir img

REM Archivos CSS
cd css
echo. > app.css
echo. > auth.css
echo. > components.css
cd..

REM Archivos JavaScript
cd js
echo. > app.js
echo. > auth.js
echo. > components.js
cd..

REM Archivos de imágenes
cd img
echo. > logo.png
echo. > favicon.ico
mkdir icons
cd..

cd..

REM Directorio de uploads
mkdir uploads
cd uploads
mkdir avatars
mkdir documents
cd..

cd..

echo [9/10] Creando estructura de base de datos y logs...
REM Crear estructura /database
mkdir database
cd database

mkdir migrations
mkdir seeds
mkdir schema

REM Archivos de migraciones
cd migrations
echo. > 001_create_users_table.sql
echo. > 002_create_roles_table.sql
echo. > 003_create_permissions_table.sql
echo. > 004_create_activity_logs_table.sql
echo. > 005_create_user_roles_table.sql
cd..

REM Archivos de seeds
cd seeds
echo. > default_roles.sql
echo. > admin_user.sql
cd..

REM Documentación de esquema
cd schema
echo. > database_schema.md
cd..

cd..

REM Crear estructura /logs
mkdir logs
cd logs

mkdir daily
echo. > auth.log
echo. > error.log
echo. > access.log
echo. > debug.log

REM Archivos de logs diarios de ejemplo
cd daily
echo. > auth-2025-07-13.log
echo. > error-2025-07-13.log
echo. > info-2025-07-13.log
cd..

cd..

echo [10/10] Creando documentacion y tests...
REM Crear estructura /tests
mkdir tests
cd tests
mkdir unit
mkdir integration

REM Archivos de pruebas unitarias
cd unit
echo. > AuthServiceTest.php
echo. > UserServiceTest.php
cd..

REM Archivos de pruebas de integración
cd integration
echo. > AuthControllerTest.php
echo. > UserControllerTest.php
cd..

cd..

REM Crear estructura /documentation
mkdir documentation
cd documentation
echo. > installation.md
echo. > configuration.md
echo. > usage.md
echo. > api-reference.md
echo. > examples.md
echo. > deployment.md
cd..

REM Crear directorio vendor (se llenará con composer)
mkdir vendor

echo.
echo ===============================================
echo        ESTRUCTURA CREADA EXITOSAMENTE
echo ===============================================
echo.
echo Directorios creados:
echo - /app (con todos los subdirectorios MVC)
echo - /public (punto de entrada y assets)
echo - /database (migraciones y seeds)
echo - /logs (sistema de logging)
echo - /tests (pruebas unitarias e integracion)
echo - /documentation (guias y documentacion)
echo - /vendor (dependencias de composer)
echo.
echo Siguiente paso:
echo 1. Ejecutar: composer install
echo 2. Configurar archivos en /app/config/
echo 3. Ejecutar migraciones de BD
echo.
echo ¡Listo para empezar a desarrollar!
echo.
pause