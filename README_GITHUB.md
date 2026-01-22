# FinanzaPro v2.0 - Sistema de Gestión Financiera Profesional

## 📋 Descripción

**FinanzaPro** es un sistema integral de gestión financiera desarrollado con Laravel 11 y diseño profesional moderno. Proporciona todas las herramientas necesarias para administrar ingresos, egresos, métodos de pago y generar reportes financieros avanzados.

## ✨ Características Principales

### 📊 Gestión Financiera Completa
- **Comprobantes de Ingreso**: Registro y seguimiento de ingresos con múltiples tipos
- **Egresos**: Gestión de gastos por categoría
- **Multas**: Control de multas y sanciones
- **Métodos de Pago**: Seguimiento de múltiples formas de pago

### 📈 Reportes Avanzados
- Estado de Resultados
- Flujo de Caja
- Resumen Ejecutivo Anual
- Reportes Detallados (Ingresos/Egresos)
- Exportación a PDF con DomPDF

### 👥 Gestión de Usuarios
- Control de acceso basado en roles
- Roles: Administrador, Contador, Auxiliar, Usuario
- Auditoría de cambios
- Gestión de permisos

### ⚙️ Administración del Sistema
- Configuración empresarial
- Respaldo y restauración de base de datos
- Gestión de usuarios
- Notificaciones del sistema
- Configuración de seguridad

### 🎨 Interfaz Profesional
- Diseño responsivo (mobile, tablet, desktop)
- Interfaz moderna con gradientes y animaciones
- Tema corporativo personalizable
- Navegación intuitiva

## 🛠️ Stack Tecnológico

### Backend
- **Laravel 11**: Framework PHP moderno
- **MySQL 8.0**: Base de datos relacional
- **PHP 8.2+**: Lenguaje de programación

### Frontend
- **Bootstrap 5.3.2**: Framework CSS responsivo
- **Font Awesome 6.5.1**: Iconografía
- **Chart.js**: Gráficos y visualizaciones
- **Blade**: Motor de plantillas

### Herramientas
- **Composer**: Gestor de dependencias PHP
- **NPM/Yarn**: Gestor de dependencias JavaScript
- **Vite**: Bundler de módulos
- **DomPDF**: Generación de reportes en PDF

## 📦 Instalación

### Requisitos Previos
- PHP 8.2 o superior
- MySQL 8.0
- Composer
- Node.js 16+ (opcional, para desarrollo)
- XAMPP o similar (para desarrollo local)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/Sistema-Finanzas.git
cd Sistema-Finanzas
```

2. **Instalar dependencias PHP**
```bash
composer install
```

3. **Configurar archivo .env**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos en .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_financiero
DB_USERNAME=root
DB_PASSWORD=
```

5. **Ejecutar migraciones**
```bash
php artisan migrate
```

6. **Crear usuario administrador (opcional)**
```bash
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=ConfiguracionSeeder
```

7. **Instalar dependencias JavaScript** (si aplica)
```bash
npm install
npm run build
```

8. **Iniciar servidor local**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Acceder a: **http://localhost:8000**

## 🔐 Credenciales Iniciales

### Administrador
- **Email**: admin@finanzapro.com
- **Contraseña**: Admin@2026#Secure

### Contador
- **Email**: contador@finanzapro.com
- **Contraseña**: Contador@2026#

## 📁 Estructura del Proyecto

```
Sistema-Finanzas/
├── app/
│   ├── Models/              # Modelos Eloquent
│   ├── Http/Controllers/    # Controladores
│   ├── Rules/               # Reglas de validación
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/             # Seeders de datos
├── resources/
│   ├── views/               # Plantillas Blade
│   │   ├── layouts/         # Layouts base
│   │   ├── comprobantes/    # Vistas de comprobantes
│   │   ├── egresos/         # Vistas de egresos
│   │   ├── multas/          # Vistas de multas
│   │   ├── reportes/        # Vistas de reportes
│   │   └── configuracion/   # Vistas de configuración
│   └── css/
├── routes/
│   └── web.php              # Rutas de aplicación
├── storage/
│   ├── app/                 # Archivos de aplicación
│   ├── framework/           # Archivos del framework
│   └── logs/                # Archivos de log
├── public/
│   ├── css/                 # Estilos compilados
│   ├── js/                  # Scripts compilados
│   └── images/              # Imágenes públicas
└── config/                  # Archivos de configuración
```

## 🚀 Módulos Principales

### 1. **Comprobantes de Ingreso**
- Registro de ingresos por cliente
- Múltiples tipos de comprobante
- Tracking de métodos de pago
- Cálculo automático de IVA

### 2. **Gestión de Egresos**
- Registro de gastos por categoría
- Seguimiento de proveedores
- Múltiples categorías de gasto
- Control presupuestario

### 3. **Multas**
- Registro de sanciones
- Seguimiento de pagos
- Estados de multa

### 4. **Reportes Financieros**
- Estado de Resultados
- Flujo de Caja
- Resumen Ejecutivo
- Reportes detallados PDF

### 5. **Configuración**
- Datos empresariales
- Configuración de seguridad
- Gestión de usuarios
- Respaldos de BD

## 📊 Base de Datos

### Principales Tablas
- `comprobantes`: Ingresos financieros
- `egresos`: Gastos y egresos
- `multas`: Registro de multas
- `users`: Usuarios del sistema
- `configuracion`: Parámetros del sistema
- `metodos_pago`: Métodos de pago registrados

## 🔒 Seguridad

- **Autenticación**: Basada en sesiones con Laravel Auth
- **Validación**: Validaciones en cliente y servidor
- **CSRF Protection**: Token CSRF en todos los formularios
- **Hash de Contraseñas**: Bcrypt
- **Control de Acceso**: Middleware de roles
- **SQL Injection**: Protección con Eloquent ORM

## 📝 Requisitos Funcionales Cumplidos

✅ Migración de base de datos a `sistema_financiero`  
✅ Modelos simplificados y actualizados  
✅ Controladores CRUD completos  
✅ Vistas responsivas y profesionales  
✅ Generación de reportes PDF  
✅ Gestión de usuarios y roles  
✅ Configuración del sistema  
✅ Respaldo y restauración de BD  
✅ Validación de datos  
✅ Auditoría de cambios  

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:
1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo licencia MIT. Ver `LICENSE` para más detalles.

## 👨‍💻 Autor

Desarrollado por **FinanzaPro Dev Team**

## 📞 Soporte

Para reportar bugs o solicitar features, por favor abre un issue en GitHub.

---

**Última actualización**: Enero 22, 2026  
**Versión**: 2.0.0  
**Estado**: ✅ Producción
