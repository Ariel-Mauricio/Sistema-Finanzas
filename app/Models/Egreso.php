<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_documento',
        'tipo',
        'proveedor',
        'ruc_proveedor',
        'descripcion',
        'categoria',
        'subtotal',
        'iva',
        'total',
        'metodo_pago',
        'referencia_pago',
        'fecha',
        'observaciones',
        'user_id'
    ];

    protected $casts = [
        'fecha' => 'date',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Tipos de documento
    public static $tipos = [
        'factura' => 'Factura',
        'recibo' => 'Recibo',
        'nota_venta' => 'Nota de Venta',
        'ticket' => 'Ticket',
        'nomina' => 'Nómina',
        'servicios' => 'Servicios',
        'otros' => 'Otros'
    ];

    // Categorías de gasto
    public static $categorias = [
        'servicios_basicos' => 'Servicios Básicos',
        'alquiler' => 'Alquiler/Arriendo',
        'sueldos' => 'Sueldos y Salarios',
        'materiales' => 'Materiales/Insumos',
        'transporte' => 'Transporte',
        'publicidad' => 'Publicidad/Marketing',
        'mantenimiento' => 'Mantenimiento',
        'impuestos' => 'Impuestos',
        'seguros' => 'Seguros',
        'otros' => 'Otros Gastos'
    ];

    // Métodos de pago
    public static $metodosPago = [
        'efectivo' => 'Efectivo',
        'transferencia' => 'Transferencia Bancaria',
        'tarjeta' => 'Tarjeta de Crédito/Débito',
        'cheque' => 'Cheque',
        'otros' => 'Otros'
    ];

    /**
     * Generar número de documento único
     */
    public static function generarNumero(): string
    {
        $año = date('Y');
        $ultimo = self::whereYear('created_at', $año)->max('id') ?? 0;
        return 'EGR-' . $año . '-' . str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener tipo formateado
     */
    public function getTipoNombreAttribute(): string
    {
        return self::$tipos[$this->tipo] ?? $this->tipo;
    }

    /**
     * Obtener categoría formateada
     */
    public function getCategoriaNombreAttribute(): string
    {
        return self::$categorias[$this->categoria] ?? $this->categoria;
    }

    /**
     * Obtener método de pago formateado
     */
    public function getMetodoPagoNombreAttribute(): string
    {
        return self::$metodosPago[$this->metodo_pago] ?? $this->metodo_pago;
    }

    /**
     * Formatear total
     */
    public function getTotalFormateadoAttribute(): string
    {
        return '$' . number_format($this->total, 2);
    }
}
