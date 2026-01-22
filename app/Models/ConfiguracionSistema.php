<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ConfiguracionSistema extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'clave',
        'valor',
        'tipo',
        'grupo',
        'descripcion'
    ];

    public $timestamps = true;

    /**
     * Obtener valor de configuración con caché
     */
    public static function obtener($clave, $default = null)
    {
        return Cache::remember("config_{$clave}", 3600, function() use ($clave, $default) {
            $config = self::where('clave', $clave)->first();
            return $config ? $config->valor : $default;
        });
    }

    /**
     * Establecer valor de configuración
     */
    public static function establecer($clave, $valor, $tipo = 'string', $grupo = 'general', $descripcion = null)
    {
        Cache::forget("config_{$clave}");
        
        return self::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'tipo' => $tipo,
                'grupo' => $grupo,
                'descripcion' => $descripcion
            ]
        );
    }

    /**
     * Obtener todas las configuraciones de un grupo
     */
    public static function porGrupo($grupo)
    {
        return self::where('grupo', $grupo)->get();
    }
}
