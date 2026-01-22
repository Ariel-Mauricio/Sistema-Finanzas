<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'clave',
        'valor',
        'tipo',
        'grupo',
        'descripcion'
    ];

    /**
     * Obtener valor de configuración
     */
    public static function obtener($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    /**
     * Guardar valor de configuración
     */
    public static function guardar($clave, $valor, $grupo = 'general', $tipo = 'string', $descripcion = null)
    {
        return self::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'grupo' => $grupo,
                'tipo' => $tipo,
                'descripcion' => $descripcion
            ]
        );
    }
}
