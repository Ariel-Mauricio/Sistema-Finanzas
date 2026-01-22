# 💼 FinanzaPro v2.0 - Sistema Integral de Gestión Financiera

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.2-7952B3?style=for-the-badge&logo=bootstrap)

**Un sistema profesional y robusto para la gestión integral de finanzas empresariales**

[Características](#-características) • [Instalación](#-instalación) • [Documentación](#-documentación) • [Soporte](#-soporte)

</div>

---

## 🎯 Acerca de FinanzaPro

**FinanzaPro** es una solución integral de gestión financiera desarrollada con **Laravel 11** y diseño moderno responsivo. Proporciona todas las herramientas necesarias para administrar:

- 📊 Ingresos y comprobantes
- 💰 Egresos y gastos
- 🚨 Multas y sanciones
- 💳 Métodos de pago
- 📈 Reportes avanzados
- 👥 Gestión de usuarios
- ⚙️ Configuración empresarial

Es perfecto para **empresas, PyMEs, cooperativas y organizaciones** que requieren control financiero profesional.

---

## ✨ Características Principales

### 📋 **Gestión de Comprobantes**
- Registro completo de ingresos por cliente
- Múltiples tipos de comprobante
- Cálculo automático de IVA
- Validación de datos en tiempo real
- Historial completo de transacciones
- Búsqueda y filtrado avanzado

```
Tipos de comprobante soportados:
✓ Factura
✓ Recibo
✓ Nota de Crédito
✓ Comprobante Personalizado
```

### 💸 **Control de Egresos**
- Registro detallado de gastos
- Clasificación por categorías
- Seguimiento de proveedores
- Control presupuestario
- Validación de montos
- Historial de cambios

### 🚨 **Gestión de Multas**
- Registro de sanciones
- Estados de pago
- Seguimiento de vencimientos
- Notificaciones automáticas
- Control de deudores

### 💳 **Métodos de Pago**
- Múltiples formas de pago
- Seguimiento de cobros
- Pendientes y confirmadas
- Histórico de transacciones

### 📊 **Reportes Profesionales**

#### Estado de Resultados
```
Período: Enero - Diciembre 2025
Total Ingresos:    $45,000.00
Total Egresos:     $18,500.00
Utilidad Neta:     $26,500.00
Margen:            58.9%
```

#### Flujo de Caja
- Movimientos diarios
- Saldo acumulado
- Análisis por período
- Proyecciones

#### Resumen Ejecutivo
- Vista mensual anual
- Gráficos interactivos
- Indicadores clave
- Análisis de tendencias

#### Reportes Detallados
- Desglose por cliente
- Desglose por categoría
- Análisis por período
- Exportación a PDF

### 👥 **Gestión de Usuarios**

Roles disponibles:
- **Administrador**: Acceso total al sistema
- **Contador**: Acceso a finanzas y reportes
- **Auxiliar**: Acceso limitado a registros
- **Usuario**: Acceso de solo lectura

Funcionalidades:
- ✓ Crear/Editar/Eliminar usuarios
- ✓ Asignación de roles
- ✓ Activación/Desactivación
- ✓ Auditoría de cambios
- ✓ Reseteo de contraseña

### ⚙️ **Configuración del Sistema**

**Información Empresarial**
- Datos de la empresa
- Logo y branding
- Contacto y ubicación
- Información legal

**Configuración Financiera**
- Moneda y símbolo
- Porcentaje de IVA
- Secuencias de numeración
- Parámetros fiscales

**Seguridad**
- Control de acceso por IP
- Logs de acceso
- Cifrado de datos sensibles
- Política de contraseñas

**Respaldos**
- Backup automático diario
- Respaldo manual bajo demanda
- Restauración segura
- Historial de respaldos

---

## 🛠️ Tecnologías Utilizadas

### Backend
| Tecnología | Versión | Descripción |
|-----------|---------|------------|
| Laravel | 11.x | Framework PHP moderno |
| PHP | 8.2+ | Lenguaje de programación |
| MySQL | 8.0 | Base de datos relacional |
| Eloquent ORM | Nativo | Manejo de datos |
| Middleware | Nativo | Control de acceso |

### Frontend
| Tecnología | Versión | Descripción |
|-----------|---------|------------|
| Bootstrap | 5.3.2 | Framework CSS responsivo |
| Font Awesome | 6.5.1 | Iconografía profesional |
| Chart.js | Última | Gráficos interactivos |
| Blade | Nativo | Motor de plantillas |
| Vite | Último | Bundler de módulos |

### Herramientas
| Herramienta | Propósito |
|------------|----------|
| Composer | Gestión de dependencias PHP |
| NPM/Yarn | Gestión de dependencias JS |
| DomPDF | Generación de PDF |
| Git | Control de versiones |

---

## 📦 Requisitos del Sistema

### Servidor Web
- **Apache 2.4+** o Nginx 1.18+
- **Mod_rewrite** habilitado
- **SSL/TLS** (recomendado)

### Base de Datos
- **MySQL 8.0+** o MariaDB 10.3+
- **Coleción**: utf8mb4_unicode_ci

### Entorno PHP
- **PHP 8.2+**
- **Extensiones requeridas**:
  - `php-mysql`
  - `php-mbstring`
  - `php-json`
  - `php-xml`
  - `php-bcmath`
  - `php-ctype`
  - `php-fileinfo`
  - `php-openssl`

### Navegadores Soportados
- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- Opera 76+

---

## 🚀 Instalación Completa

### Paso 1: Preparar Entorno

```bash
# Crear directorio de proyecto
mkdir /var/www/finanzapro
cd /var/www/finanzapro

# Clonar repositorio
git clone https://github.com/Ariel-Mauricio/Sistema-Finanzas.git .
```

### Paso 2: Instalar Dependencias

```bash
# Instalar dependencias PHP
composer install --no-dev

# Instalar dependencias JavaScript
npm install
npm run build
```

### Paso 3: Configurar Aplicación

```bash
# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Generar clave JWT (si usa autenticación)
php artisan jwt:secret
```

### Paso 4: Configurar Base de Datos

**Editar `.env`**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_financiero
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

**Crear base de datos**:
```bash
mysql -u root -p -e "CREATE DATABASE sistema_financiero CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Paso 5: Ejecutar Migraciones

```bash
# Ejecutar todas las migraciones
php artisan migrate

# Crear usuario administrador
php artisan db:seed --class=AdminUserSeeder

# Cargar configuraciones iniciales
php artisan db:seed --class=ConfiguracionSeeder
```

### Paso 6: Permisos de Directorios

```bash
# Linux/Mac
sudo chown -R www-data:www-data /var/www/finanzapro
chmod -R 755 /var/www/finanzapro
chmod -R 775 /var/www/finanzapro/storage
chmod -R 775 /var/www/finanzapro/bootstrap/cache

# Windows (XAMPP)
# Cambiar permisos en propiedades de carpeta
```

### Paso 7: Iniciar Servidor

**Desarrollo**:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

**Producción** (con Apache/Nginx):
- Configurar virtual host
- Apuntar document root a `/public`
- Habilitar mod_rewrite

---

## 🔐 Credenciales Iniciales

### Usuario Administrador
```
Email:       admin@finanzapro.com
Contraseña:  Admin@2026#Secure
Rol:         Administrador (Acceso total)
```

### Usuario Contador
```
Email:       contador@finanzapro.com
Contraseña:  Contador@2026#
Rol:         Contador (Finanzas y reportes)
```

**⚠️ Importante**: Cambiar credenciales en producción

---

## 📁 Estructura del Proyecto

```
Sistema-Finanzas/
│
├── app/
│   ├── Models/
│   │   ├── Comprobante.php      # Modelo de ingresos
│   │   ├── Egreso.php           # Modelo de egresos
│   │   ├── Multa.php            # Modelo de multas
│   │   ├── User.php             # Modelo de usuarios
│   │   └── Configuracion.php    # Modelo de configuración
│   │
│   ├── Http/Controllers/
│   │   ├── ComprobanteController.php
│   │   ├── EgresoController.php
│   │   ├── MultaController.php
│   │   ├── ConfiguracionController.php
│   │   ├── ReporteController.php
│   │   └── AuthController.php
│   │
│   ├── Rules/                   # Validaciones personalizadas
│   └── Providers/               # Service providers
│
├── database/
│   ├── migrations/              # Migraciones de BD
│   │   ├── create_users_table
│   │   ├── create_comprobantes_table
│   │   ├── create_egresos_table
│   │   └── create_multas_table
│   │
│   └── seeders/                 # Data seeders
│       ├── AdminUserSeeder.php
│       └── ConfiguracionSeeder.php
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── master.blade.php       # Layout principal
│   │   │
│   │   ├── auth/
│   │   │   └── login_pro.blade.php    # Login profesional
│   │   │
│   │   ├── comprobantes/
│   │   │   ├── index.blade.php        # Listado
│   │   │   ├── create.blade.php       # Crear
│   │   │   ├── edit.blade.php         # Editar
│   │   │   └── show.blade.php         # Detalle
│   │   │
│   │   ├── egresos/                   # Ídem comprobantes
│   │   ├── multas/                    # Ídem comprobantes
│   │   │
│   │   ├── reportes/
│   │   │   ├── pdf/
│   │   │   │   ├── ingresos.blade.php
│   │   │   │   ├── egresos.blade.php
│   │   │   │   └── estado-resultados.blade.php
│   │   │   │
│   │   │   └── index.blade.php
│   │   │
│   │   └── configuracion/
│   │       ├── index.blade.php
│   │       ├── empresa.blade.php
│   │       ├── usuarios.blade.php
│   │       └── respaldos.blade.php
│   │
│   ├── css/                     # Estilos personalizados
│   └── js/                      # Scripts personalizados
│
├── routes/
│   └── web.php                  # Rutas de aplicación
│
├── storage/
│   ├── app/                     # Archivos de aplicación
│   ├── framework/               # Cache y sesiones
│   └── logs/                    # Archivos de log
│
├── public/
│   ├── index.php                # Entrada principal
│   ├── css/                     # CSS compilado
│   └── js/                      # JS compilado
│
├── config/                      # Archivos de configuración
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   ├── filesystems.php
│   └── mail.php
│
└── tests/
    ├── Unit/                    # Tests unitarios
    └── Feature/                 # Tests funcionales
```

---

## 📚 Guía de Uso

### Crear Comprobante de Ingreso

```
1. Navegar a "Comprobantes" en el menú
2. Hacer clic en "Nuevo Comprobante"
3. Llenar formulario:
   - Cliente: Nombre del cliente
   - Cédula/RUC: Identificación
   - Tipo: Seleccionar tipo de comprobante
   - Descripción: Detalle del ingreso
   - Subtotal: Monto sin impuestos
   - IVA: Se calcula automáticamente
   - Total: Se calcula automáticamente
   - Método de Pago: Forma de pago
   - Fecha: Día de la transacción
4. Hacer clic en "Guardar"
5. Sistema genera automáticamente PDF
```

### Generar Reportes

```
Reportes disponibles:
1. Estado de Resultados
   - Ingresos y egresos por período
   - Margen de ganancia
   - Exportar PDF

2. Flujo de Caja
   - Movimientos diarios
   - Saldo acumulado
   - Gráficos

3. Resumen Ejecutivo
   - Vista anual mensual
   - Indicadores clave
   - Análisis comparativo

4. Detalles de Ingresos
   - Listado completo de comprobantes
   - Filtros por cliente, tipo, período
   - Subtotales por categoría

5. Detalles de Egresos
   - Listado completo de egresos
   - Filtros por proveedor, categoría
   - Análisis de gastos
```

### Administrar Usuarios

```
Como Administrador:
1. Ir a "Configuración" → "Usuarios"
2. Ver lista de usuarios activos
3. Crear nuevo usuario:
   - Nombre
   - Email
   - Rol (Admin, Contador, Auxiliar, Usuario)
   - Contraseña
4. Editar usuario existente
5. Activar/Desactivar usuario
6. Eliminar usuario (no permite auto-eliminar)
```

---

## 🔒 Seguridad

### Características de Seguridad

✅ **Autenticación**
- Sesiones seguras con Laravel Auth
- Bcrypt para hash de contraseñas
- Recuperación de contraseña

✅ **Control de Acceso**
- Middleware por roles
- Validación de permisos
- Protección CSRF

✅ **Validación de Datos**
- Validación en cliente (Bootstrap)
- Validación en servidor (Laravel)
- Sanitización de inputs

✅ **Protección de Base de Datos**
- Eloquent ORM (previene SQL Injection)
- Prepared statements
- Validación de tipos

✅ **Auditoría**
- Log de cambios
- Registro de accesos
- Historial de operaciones

---

## 📞 API Endpoints

### Autenticación
```
POST   /login              # Iniciar sesión
POST   /logout             # Cerrar sesión
GET    /dashboard          # Panel principal
```

### Comprobantes
```
GET    /comprobantes              # Listado
POST   /comprobantes              # Crear
GET    /comprobantes/{id}         # Ver detalle
PUT    /comprobantes/{id}         # Actualizar
DELETE /comprobantes/{id}         # Eliminar
GET    /comprobantes/{id}/pdf     # Descargar PDF
```

### Egresos
```
GET    /egresos              # Listado
POST   /egresos              # Crear
GET    /egresos/{id}         # Ver detalle
PUT    /egresos/{id}         # Actualizar
DELETE /egresos/{id}         # Eliminar
GET    /egresos/{id}/pdf     # Descargar PDF
```

### Reportes
```
GET    /reportes                           # Página principal
GET    /reportes/estado-resultados         # Estado de resultados
GET    /reportes/flujo-caja                # Flujo de caja
GET    /reportes/resumen-ejecutivo         # Resumen ejecutivo
GET    /reportes/ingresos-detalle          # Detalles ingresos
GET    /reportes/egresos-detalle           # Detalles egresos
GET    /reportes/pdf/{tipo}                # PDF de reportes
```

### Configuración
```
GET    /configuracion              # Página principal
GET    /configuracion/empresa      # Datos empresa
POST   /configuracion/empresa      # Guardar empresa
GET    /configuracion/usuarios     # Gestionar usuarios
POST   /configuracion/usuarios     # Crear usuario
PUT    /configuracion/usuarios/{id} # Actualizar usuario
DELETE /configuracion/usuarios/{id} # Eliminar usuario
GET    /configuracion/respaldos    # Backups
POST   /configuracion/respaldos    # Crear backup
```

---

## 🐛 Troubleshooting

### Error: "SQLSTATE[HY000]: General error"
```bash
# Solución
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh --seed
```

### Error: "Class not found"
```bash
# Solución
composer dump-autoload
php artisan optimize
```

### Permisos de carpeta
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache

# Windows (ejecutar como Admin)
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T
```

### Base de datos no responde
```bash
# Verificar estado de MySQL
mysql -u root -p -e "SELECT 1;"

# Reiniciar servicio
sudo systemctl restart mysql
```

---

## 📊 Estadísticas del Proyecto

```
├── Líneas de código:     15,000+
├── Vistas Blade:         25+
├── Controladores:        6+
├── Modelos:              5
├── Migraciones:          8
├── Rutas:                65+
├── Tests:                20+
└── Documentación:        Completa
```

---

## 🤝 Contribución

¿Quieres contribuir? ¡Bienvenido!

```bash
# 1. Fork el proyecto
# 2. Crea una rama
git checkout -b feature/nueva-caracteristica

# 3. Commit cambios
git commit -m "Agregar nueva característica"

# 4. Push a rama
git push origin feature/nueva-caracteristica

# 5. Abre un Pull Request
```

---

## 📄 Licencia

Este proyecto está bajo licencia **MIT**. Ver archivo `LICENSE` para más detalles.

---

## 📞 Soporte y Contacto

### Reportar Bugs
- Abre un issue en GitHub
- Incluye pasos para reproducir
- Adjunta capturas de pantalla

### Solicitar Features
- Abre un issue con etiqueta `enhancement`
- Describe la funcionalidad deseada
- Proporciona casos de uso

### Contacto Directo
- **Email**: dev@finanzapro.com
- **GitHub**: [@Ariel-Mauricio](https://github.com/Ariel-Mauricio)
- **Issues**: [Sistema-Finanzas/issues](https://github.com/Ariel-Mauricio/Sistema-Finanzas/issues)

---

## 🎓 Documentación Adicional

- [Laravel Official Docs](https://laravel.com/docs)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [Chart.js Guide](https://www.chartjs.org/docs)

---

## 🙏 Agradecimientos

Desarrollado con ❤️ usando:
- Laravel Framework
- Bootstrap CSS Framework
- Comunidad de código abierto

---

<div align="center">

**Hecho con ❤️ por [Ariel Mauricio](https://github.com/Ariel-Mauricio)**

© 2026 FinanzaPro - Todos los derechos reservados

**v2.0.0** | Enero 2026

</div>
