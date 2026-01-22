<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ConfiguracionSistema;

class SistemaFinancieroSeeder extends Seeder
{
    public function run(): void
    {
        // Crear Super Administrador con credenciales seguras
        User::updateOrCreate(
            ['email' => 'admin@finanzapro.com'],
            [
                'name' => 'Administrador Principal',
                'password' => Hash::make('Admin@2026#Secure'),
                'role' => 'admin',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario contador
        User::updateOrCreate(
            ['email' => 'contador@finanzapro.com'],
            [
                'name' => 'Contador General',
                'password' => Hash::make('Contador@2026#'),
                'role' => 'contador',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Crear usuario auxiliar
        User::updateOrCreate(
            ['email' => 'auxiliar@finanzapro.com'],
            [
                'name' => 'Auxiliar Contable',
                'password' => Hash::make('Auxiliar@2026#'),
                'role' => 'auxiliar',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Configuraciones del sistema
        $configuraciones = [
            // Información de la empresa
            ['clave' => 'empresa_nombre', 'valor' => 'FinanzaPro', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Nombre de la empresa'],
            ['clave' => 'empresa_ruc', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'RUC de la empresa'],
            ['clave' => 'empresa_direccion', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Dirección de la empresa'],
            ['clave' => 'empresa_telefono', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Teléfono de la empresa'],
            ['clave' => 'empresa_email', 'valor' => 'info@finanzapro.com', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Email de la empresa'],
            
            // Configuraciones financieras
            ['clave' => 'moneda_simbolo', 'valor' => '$', 'tipo' => 'string', 'grupo' => 'finanzas', 'descripcion' => 'Símbolo de moneda'],
            ['clave' => 'moneda_codigo', 'valor' => 'USD', 'tipo' => 'string', 'grupo' => 'finanzas', 'descripcion' => 'Código de moneda ISO'],
            ['clave' => 'iva_porcentaje', 'valor' => '15', 'tipo' => 'decimal', 'grupo' => 'finanzas', 'descripcion' => 'Porcentaje de IVA'],
            ['clave' => 'decimales', 'valor' => '2', 'tipo' => 'integer', 'grupo' => 'finanzas', 'descripcion' => 'Decimales para montos'],
            
            // Configuraciones de comprobantes
            ['clave' => 'prefijo_comprobante', 'valor' => 'COMP-', 'tipo' => 'string', 'grupo' => 'comprobantes', 'descripcion' => 'Prefijo de comprobantes'],
            ['clave' => 'prefijo_egreso', 'valor' => 'EGR-', 'tipo' => 'string', 'grupo' => 'comprobantes', 'descripcion' => 'Prefijo de egresos'],
            ['clave' => 'secuencia_comprobante', 'valor' => '1', 'tipo' => 'integer', 'grupo' => 'comprobantes', 'descripcion' => 'Secuencia de comprobantes'],
            ['clave' => 'secuencia_egreso', 'valor' => '1', 'tipo' => 'integer', 'grupo' => 'comprobantes', 'descripcion' => 'Secuencia de egresos'],
            
            // Configuraciones del sistema
            ['clave' => 'tema', 'valor' => 'light', 'tipo' => 'string', 'grupo' => 'sistema', 'descripcion' => 'Tema del sistema'],
            ['clave' => 'notificaciones_email', 'valor' => 'true', 'tipo' => 'boolean', 'grupo' => 'sistema', 'descripcion' => 'Enviar notificaciones por email'],
            ['clave' => 'backup_automatico', 'valor' => 'true', 'tipo' => 'boolean', 'grupo' => 'sistema', 'descripcion' => 'Realizar backups automáticos'],
        ];

        foreach ($configuraciones as $config) {
            ConfiguracionSistema::updateOrCreate(
                ['clave' => $config['clave']],
                $config
            );
        }

        // Crear categorías por defecto
        $categorias = [
            // Ingresos
            ['nombre' => 'Ventas', 'tipo' => 'ingreso', 'color' => '#10B981', 'icono' => 'fa-shopping-cart'],
            ['nombre' => 'Servicios', 'tipo' => 'ingreso', 'color' => '#3B82F6', 'icono' => 'fa-briefcase'],
            ['nombre' => 'Intereses', 'tipo' => 'ingreso', 'color' => '#8B5CF6', 'icono' => 'fa-percent'],
            ['nombre' => 'Otros Ingresos', 'tipo' => 'ingreso', 'color' => '#6366F1', 'icono' => 'fa-plus-circle'],
            
            // Egresos
            ['nombre' => 'Nómina', 'tipo' => 'egreso', 'color' => '#EF4444', 'icono' => 'fa-users'],
            ['nombre' => 'Servicios Básicos', 'tipo' => 'egreso', 'color' => '#F59E0B', 'icono' => 'fa-bolt'],
            ['nombre' => 'Arriendo', 'tipo' => 'egreso', 'color' => '#EC4899', 'icono' => 'fa-home'],
            ['nombre' => 'Materiales', 'tipo' => 'egreso', 'color' => '#14B8A6', 'icono' => 'fa-box'],
            ['nombre' => 'Transporte', 'tipo' => 'egreso', 'color' => '#F97316', 'icono' => 'fa-truck'],
            ['nombre' => 'Impuestos', 'tipo' => 'egreso', 'color' => '#DC2626', 'icono' => 'fa-file-invoice-dollar'],
            ['nombre' => 'Otros Gastos', 'tipo' => 'egreso', 'color' => '#6B7280', 'icono' => 'fa-minus-circle'],
        ];

        foreach ($categorias as $categoria) {
            ConfiguracionSistema::updateOrCreate(
                ['nombre' => $categoria['nombre'], 'tipo' => $categoria['tipo']],
                $categoria
            );
        }

        $this->command->info('✅ Sistema Financiero inicializado correctamente');
        $this->command->info('');
        $this->command->info('📋 CREDENCIALES DE ACCESO:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('👤 ADMINISTRADOR');
        $this->command->info('   Email: admin@finanzapro.com');
        $this->command->info('   Contraseña: Admin@2026#Secure');
        $this->command->info('');
        $this->command->info('👤 CONTADOR');
        $this->command->info('   Email: contador@finanzapro.com');
        $this->command->info('   Contraseña: Contador@2026#');
        $this->command->info('');
        $this->command->info('👤 AUXILIAR');
        $this->command->info('   Email: auxiliar@finanzapro.com');
        $this->command->info('   Contraseña: Auxiliar@2026#');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
