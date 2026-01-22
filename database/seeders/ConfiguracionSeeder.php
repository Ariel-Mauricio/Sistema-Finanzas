<?php

namespace Database\Seeders;

use App\Models\ConfiguracionSistema;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $configuraciones = [
            // Empresa
            ['clave' => 'empresa_nombre', 'valor' => 'FinanzaPro', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Nombre de la empresa'],
            ['clave' => 'empresa_ruc', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'RUC/CI de la empresa'],
            ['clave' => 'empresa_direccion', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Dirección'],
            ['clave' => 'empresa_telefono', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Teléfono'],
            ['clave' => 'empresa_email', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Email de contacto'],
            ['clave' => 'empresa_ciudad', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Ciudad'],
            ['clave' => 'empresa_provincia', 'valor' => '', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Provincia'],
            ['clave' => 'empresa_slogan', 'valor' => 'Sistema de Gestión Financiera', 'tipo' => 'string', 'grupo' => 'empresa', 'descripcion' => 'Slogan'],

            // Finanzas
            ['clave' => 'moneda_codigo', 'valor' => 'USD', 'tipo' => 'string', 'grupo' => 'finanzas', 'descripcion' => 'Código de moneda'],
            ['clave' => 'moneda_simbolo', 'valor' => '$', 'tipo' => 'string', 'grupo' => 'finanzas', 'descripcion' => 'Símbolo de moneda'],
            ['clave' => 'iva_porcentaje', 'valor' => '15', 'tipo' => 'number', 'grupo' => 'finanzas', 'descripcion' => 'Porcentaje IVA'],
            ['clave' => 'iva_activo', 'valor' => 'true', 'tipo' => 'boolean', 'grupo' => 'finanzas', 'descripcion' => 'Calcular IVA automáticamente'],

            // Comprobantes
            ['clave' => 'comprobante_prefijo', 'valor' => 'COMP', 'tipo' => 'string', 'grupo' => 'comprobantes', 'descripcion' => 'Prefijo comprobantes'],
            ['clave' => 'comprobante_siguiente', 'valor' => '1', 'tipo' => 'number', 'grupo' => 'comprobantes', 'descripcion' => 'Siguiente número'],
            ['clave' => 'egreso_prefijo', 'valor' => 'EGR', 'tipo' => 'string', 'grupo' => 'comprobantes', 'descripcion' => 'Prefijo egresos'],
            ['clave' => 'egreso_siguiente', 'valor' => '1', 'tipo' => 'number', 'grupo' => 'comprobantes', 'descripcion' => 'Siguiente número egreso'],

            // Sistema
            ['clave' => 'zona_horaria', 'valor' => 'America/Guayaquil', 'tipo' => 'string', 'grupo' => 'sistema', 'descripcion' => 'Zona horaria'],
            ['clave' => 'formato_fecha', 'valor' => 'd/m/Y', 'tipo' => 'string', 'grupo' => 'sistema', 'descripcion' => 'Formato de fecha'],
            ['clave' => 'paginacion', 'valor' => '15', 'tipo' => 'number', 'grupo' => 'sistema', 'descripcion' => 'Registros por página'],
            ['clave' => 'backup_automatico', 'valor' => 'false', 'tipo' => 'boolean', 'grupo' => 'sistema', 'descripcion' => 'Respaldo automático diario'],
        ];

        foreach ($configuraciones as $config) {
            ConfiguracionSistema::updateOrCreate(
                ['clave' => $config['clave']],
                $config
            );
        }
    }
}
