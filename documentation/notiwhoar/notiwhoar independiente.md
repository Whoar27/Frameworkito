# 🎉 NotiWhoar

Librería moderna y ligera para notificaciones, confirmaciones y modales en JavaScript.

## ✨ Características

- 🚀 **Ligera**: Menos de 15KB minificado
- 🎨 **Personalizable**: Múltiples temas y opciones
- 📱 **Responsive**: Funciona en todos los dispositivos
- 🔧 **API Simple**: Fácil de usar y configurar
- 🎭 **Animaciones**: Transiciones suaves y elegantes
- 🌙 **Temas**: Claro, oscuro, minimalista y más
- ♿ **Accesible**: Soporte para lectores de pantalla

## 📦 Instalación

### CDN
```html
<script src="https://cdn.jsdelivr.net/npm/notiwhoar@1.0.0/dist/notiwhoar.min.js"></script>
```

### Descarga directa
1. Descarga `notiwhoar.min.js` desde la carpeta `dist/`
2. Incluye el archivo en tu HTML:
```html
<script src="path/to/notiwhoar.min.js"></script>
```

## 🚀 Uso Básico

### Notificaciones Toast
```javascript
// Notificaciones simples
NotiWhoar.success('¡Operación exitosa!');
NotiWhoar.error('Algo salió mal');
NotiWhoar.warning('Ten cuidado');
NotiWhoar.info('Información importante');

// Con opciones personalizadas
NotiWhoar.notify('success', 'Mensaje personalizado', {
  title: 'Título',
  duration: 5000,
  position: 'top-center',
  closable: true
});
```

### Confirmaciones
```javascript
const result = await NotiWhoar.confirm(
  '¿Estás seguro?',
  'Esta acción no se puede deshacer'
);

if (result) {
  console.log('Usuario confirmó');
} else {
  console.log('Usuario canceló');
}
```

### Inputs
```javascript
const name = await NotiWhoar.prompt('Tu nombre', 'text', {
  placeholder: 'Escribe tu nombre...',
  required: true
});

const email = await NotiWhoar.prompt('Tu email', 'email', {
  placeholder: 'ejemplo@correo.com',
  validation: (value) => {
    if (!value.includes('@')) {
      return 'Email inválido';
    }
    return null;
  }
});
```

### Modales Personalizados
```javascript
const modal = NotiWhoar.modal({
  title: 'Mi Modal',
  content: '<p>Contenido personalizado</p>',
  size: 'medium',
  closable: true
});

modal.show();
```

## 🎨 Temas

```javascript
// Cambiar tema
NotiWhoar.setTheme('dark');     // Tema oscuro
NotiWhoar.setTheme('minimal');  // Tema minimalista
NotiWhoar.setTheme('colorful'); // Tema colorido
NotiWhoar.setTheme('compact');  // Tema compacto
NotiWhoar.setTheme('auto');     // Automático (según preferencias del sistema)
```

## ⚙️ Configuración

```javascript
NotiWhoar.configure({
  toast: {
    duration: 4000,
    position: 'top-right',
    closable: true
  },
  modal: {
    closable: true,
    backdrop: true
  }
});
```

## 🔧 Desarrollo

```bash
# Instalar dependencias
npm install

# Desarrollo con watch
npm run dev

# Build para producción
npm run build

# Servidor de desarrollo
npm run serve
```

## 📄 Licencia

MIT License