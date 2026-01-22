<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_documento',
        'persona',
        'aplicado_por',
        'motivo',
        'valor',
        'fecha',
        'estado',
        'user_id'
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor' => 'decimal:2',
    ];

    public static $estados = [
        'pendiente' => 'Pendiente',
        'pagada' => 'Pagada',
        'anulada' => 'Anulada'
    ];

    /**
     * Generar número de documento único
     */
    public static function generarNumero(): string
    {
        $año = date('Y');
        $ultimo = self::whereYear('created_at', $año)->max('id') ?? 0;
        return 'MUL-' . $año . '-' . str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener estado formateado
     */
    public function getEstadoNombreAttribute(): string
    {
        return self::$estados[$this->estado] ?? $this->estado;
    }

    /**
     * Formatear valor
     */
    public function getValorFormateadoAttribute(): string
    {
        return '$' . number_format($this->valor, 2);
    }
}
