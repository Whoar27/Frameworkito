```
└── 📁1 Proyecto base MVC
    └── 📁app
        └── 📁config
            ├── app.php // Ya tiene codigo
            ├── auth.php // Ya tiene codigo
            ├── database.php // Ya tiene codigo
            ├── logging.php // Ya tiene codigo
            ├── mail.php // Ya tiene codigo
        └── 📁controllers
            ├── AuthController.php
            ├── BaseController.php // Ya tiene codigo
            ├── HomeController.php  // Ya tiene codigo
            ├── PublicController.php  // Ya tiene codigo
            ├── UserController.php
        └── 📁core
            ├── App.php // Ya tiene codigo
            ├── Bootstrap.php // Ya tiene codigo
            ├── Router.php // Ya tiene codigo
        └── 📁helpers
            ├── Auth.php // Ya tiene codigo
            ├── DatabaseLogger.php // Ya tiene codigo
            ├── FileLogger.php // Ya tiene codigo
            ├── functions.php // Ya tiene codigo
            ├── Redirect.php // Ya tiene codigo
            ├── Session.php // Ya tiene codigo
            ├── Utils.php // Ya tiene codigo
            ├── Validator.php // Ya tiene codigo
        └── 📁middlewares
            ├── AuthMiddleware.php
            ├── CSRFMiddleware.php
            ├── GuestMiddleware.php
            ├── MaintenanceMiddleware.php // Ya tiene codigo
            ├── RoleMiddleware.php
        └── 📁models
            ├── ActivityLog.php
            ├── BaseModel.php
            ├── User.php
        └── 📁routes
            ├── api.php
            ├── web.php // Ya tiene codigo
        └── 📁services
            ├── ActivityLogService.php
            ├── AuthService.php
            ├── EmailService.php
            ├── LogService.php
            ├── UserService.php
        └── 📁validators
            ├── LoginValidator.php
            ├── RegisterValidator.php
            ├── UserValidator.php
        └── 📁views
            └── 📁auth
                ├── forgot-password.php // Ya tiene codigo
                ├── login.php // Ya tiene codigo
                ├── register.php // Ya tiene codigo
                ├── reset-password.php // Ya tiene codigo
                ├── two-factor.php
                ├── verify-email.php // Ya tiene codigo
            └── 📁components
                ├── alerts.php
                ├── footer.php
                ├── header.php
                ├── navbar.php
            └── 📁errors
                ├── 403.php // Ya tiene codigo
                ├── 404.php // Ya tiene codigo
                ├── 500.php // Ya tiene codigo
                ├── debug.php // Ya tiene codigo
            └── 📁layouts
                ├── app.php
                ├── auth.php // Ya tiene codigo
                ├── guest.php // Ya tiene codigo
            └── 📁maintenance
                ├── index.php // Ya tiene codigo
            └── 📁public
                ├── about.php // Ya tiene codigo
                ├── contact.php // Ya tiene codigo
                ├── faq.php // Ya tiene codigo
                ├── landing.php // Ya tiene codigo
            └── 📁user
                ├── dashboard.php
                ├── profile.php
                ├── settings.php
    └── 📁database
        └── 📁migrations
            ├── 001_*.sql // Ya tienen codigo
        └── 📁schema
            ├── database_schema.md
        └── 📁seeds
            ├── admin_user.sql
            ├── default_roles.sql
    └── 📁documentation
        ├── api-reference.md
        ├── configuration.md
        ├── crear_auth_base.bat // Ya tiene codigo
        ├── deployment.md
        ├── examples.md
        ├── installation.md
        ├── usage.md
    └── 📁logs
    └── 📁public
        └── 📁assets
            └── 📁css
                ├── app.css
                ├── auth.css
                ├── components.css
                ├── guest.css // Ya tiene codigo
            └── 📁img
            └── 📁js
                ├── app.js
                ├── auth.js
                ├── components.js
        └── 📁uploads
            └── 📁avatars
            └── 📁documents
        ├── .htaccess // Ya tiene codigo
        ├── index.php // Ya tiene codigo
    └── 📁tests
        └── 📁integration
            ├── AuthControllerTest.php
            ├── UserControllerTest.php
        └── 📁unit
            ├── AuthServiceTest.php
            ├── UserServiceTest.php
    └── 📁vendor
    ├── .env // Ya tiene codigo
    ├── .env.example // Ya tiene codigo
    ├── .gitignore
    ├── .htaccess // Ya tiene codigo
    ├── composer.json // Ya tiene codigo
    ├── composer.lock // Ya tiene codigo
    ├── generate-key.php // Ya tiene codigo
    └── README.md // Ya tiene codigo
```