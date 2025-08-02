# Documentación Técnica: Pruebas (`tests/`)

La carpeta `tests/` contiene las pruebas automatizadas del sistema Frameworkito, organizadas para facilitar el mantenimiento y la calidad del software. Permite asegurar el correcto funcionamiento de los componentes principales mediante pruebas unitarias e integrales.

---

## Estructura General

```
tests/
├── integration/    # Pruebas de integración (interacción entre componentes)
│   ├── AuthControllerTest.php
│   └── UserControllerTest.php
├── unit/           # Pruebas unitarias (cada clase/servicio de forma aislada)
│   ├── AuthServiceTest.php
│   └── UserServiceTest.php
```

---

## Descripción de carpetas y archivos

- **integration/**
  - Contiene pruebas de integración, que validan el funcionamiento conjunto de varios componentes (por ejemplo, controladores y servicios conectados a la base de datos o a otros módulos).
  - Ejemplo: `AuthControllerTest.php`, `UserControllerTest.php`.

- **unit/**
  - Contiene pruebas unitarias, que validan el comportamiento de clases o funciones individuales de forma aislada.
  - Ejemplo: `AuthServiceTest.php`, `UserServiceTest.php`.

---

## Buenas Prácticas y Recomendaciones

- Mantener una clara separación entre pruebas unitarias y de integración.
- Nombrar los archivos de prueba siguiendo el patrón `<Clase>Test.php` para facilitar su identificación.
- Automatizar la ejecución de pruebas en cada despliegue o antes de publicar cambios importantes.
- Agregar nuevas pruebas al incorporar funcionalidades o corregir errores.
- Documentar casos de prueba especiales o dependencias externas necesarias para ejecutar los tests.

---

> **Resumen:**
> La carpeta `tests/` es clave para la calidad y mantenibilidad del sistema, permitiendo detectar errores de forma temprana y asegurar el correcto funcionamiento de los componentes principales.
