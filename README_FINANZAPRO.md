# FinanzaPro - Sistema de Gestión Financiera

![FinanzaPro](https://img.shields.io/badge/FinanzaPro-v2.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-11-red)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)

## 📋 Descripción

**FinanzaPro** es un sistema profesional de gestión financiera desarrollado en Laravel, diseñado especialmente para **negocios, academias, escuelas de manejo, centros de capacitación y empresas** que necesitan llevar un control completo de sus finanzas: ventas de productos, cursos, servicios, matrículas, pensiones y más. Incluye un dashboard interactivo con KPIs, gráficos y métricas en tiempo real.

## ✨ Características Principales

### 📊 Dashboard Profesional
- Estadísticas en tiempo real
- Gráficos interactivos con Chart.js
- KPIs de rendimiento financiero
- Indicadores de tendencias

### 💰 Gestión de Ingresos (Comprobantes)
- Registro de comprobantes de pago
- Filtros avanzados por fecha, tipo, sucursal
- Exportación a PDF y Excel
- Impresión de comprobantes

### 💸 Gestión de Egresos
- Control de gastos por categorías
- Gestión de proveedores
- Seguimiento de facturas
- Reportes de gastos

### 💳 Métodos de Pago y Cobros
- Registro de pagos en efectivo, transferencia, tarjeta
- Control de cobros pendientes
- Seguimiento de abonos y pagos parciales
- Historial de transacciones por cliente

### 📈 Reportes Financieros
- Estado de Resultados
- Flujo de Caja
- Detalle de Ingresos
- Detalle de Egresos
- Resumen Ejecutivo
- Exportación a PDF/Excel

### ⚙️ Configuración del Sistema
- Datos de la empresa
- Gestión de usuarios y roles
- Configuración fiscal
- Respaldo de datos

## 🚀 Instalación

### Requisitos
- PHP >= 8.2
- MySQL >= 5.7
- Composer
- Node.js (opcional, para assets)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone [url-del-repositorio]
cd comprobantes
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar el entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar la base de datos** en el archivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=finanzapro_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Ejecutar migraciones y seeders**
```bash
php artisan migrate
php artisan db:seed --class=SistemaFinancieroSeeder
```

6. **Iniciar el servidor**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## 🔐 Credenciales de Acceso

### Administrador
- **Email:** admin@finanzapro.com
- **Contraseña:** Admin@2026#Secure

### Contador
- **Email:** contador@finanzapro.com
- **Contraseña:** Contador@2026#

### Auxiliar
- **Email:** auxiliar@finanzapro.com
- **Contraseña:** Auxiliar@2026#

## 📁 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ComprobanteController.php
│   │   ├── ConfiguracionController.php
│   │   ├── DashboardController.php
│   │   ├── EgresoController.php
│   │   ├── MetodoPagoController.php
│   │   └── ReporteController.php
│   └── Middleware/
├── Models/
│   ├── Comprobante.php
│   ├── Egreso.php
│   ├── Estudiante.php
│   ├── Multa.php
│   └── User.php
resources/
├── views/
│   ├── layouts/
│   │   └── master.blade.php
│   ├── auth/
│   ├── comprobantes/
│   ├── configuracion/
│   ├── metodos-pago/
│   ├── egresos/
│   └── reportes/
```

## 🛠️ Tecnologías Utilizadas

- **Backend:** Laravel 11
- **Frontend:** Bootstrap 5.3, Blade Templates
- **Base de Datos:** MySQL
- **Gráficos:** Chart.js
- **Iconos:** Font Awesome 6.5
- **PDF:** DomPDF
- **Autenticación:** Laravel Built-in Auth

## 📝 Roles de Usuario

| Rol | Permisos |
|-----|----------|
| Admin | Acceso completo al sistema |
| Contador | Gestión financiera completa |
| Auxiliar | Registro de transacciones |

## 🔧 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Ver rutas
php artisan route:list

# Crear nuevo usuario admin
php artisan db:seed --class=AdminUserSeeder
```

## 📄 Licencia

Este proyecto es de uso privado.

## 👥 Soporte

Para soporte técnico, contactar a través del panel de administración del sistema.

---

**FinanzaPro** - Sistema de Gestión Financiera Profesional © 2026
