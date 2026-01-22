<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comprobante;
use App\Models\Egreso;
use Illuminate\Support\Facades\DB;

class MetodoPagoController extends Controller
{
    /**
     * Mostrar resumen de métodos de pago
     */
    public function index(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->get('fecha_fin', now()->toDateString());

        // Estadísticas de ingresos por tipo
        $ingresosPorTipo = Comprobante::select('tipo', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->groupBy('tipo')
            ->get();

        // Estadísticas de ingresos por método de pago
        $ingresosPorMetodo = Comprobante::select('metodo_pago', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->groupBy('metodo_pago')
            ->get();

        // Estadísticas de egresos por categoría
        $egresosPorCategoria = Egreso::select('categoria', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->groupBy('categoria')
            ->get();

        // Estadísticas de egresos por método de pago
        $egresosPorMetodo = Egreso::select('metodo_pago', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->groupBy('metodo_pago')
            ->get();

        // Totales generales
        $totalIngresos = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])->sum('total');
        $totalEgresos = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])->sum('total');
        $balance = $totalIngresos - $totalEgresos;

        // Últimas transacciones
        $ultimosIngresos = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $ultimosEgresos = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('metodos-pago.index', compact(
            'ingresosPorTipo',
            'ingresosPorMetodo',
            'egresosPorCategoria',
            'egresosPorMetodo',
            'totalIngresos',
            'totalEgresos',
            'balance',
            'ultimosIngresos',
            'ultimosEgresos',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Ver historial de transacciones
     */
    public function historial(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->get('fecha_fin', now()->toDateString());
        $tipo = $request->get('tipo', 'todos');

        $ingresos = collect([]);
        $egresos = collect([]);

        if ($tipo === 'todos' || $tipo === 'ingresos') {
            $ingresos = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'fecha' => $item->fecha,
                        'tipo' => 'Ingreso',
                        'numero' => $item->numero_comprobante,
                        'concepto' => $item->cliente . ' - ' . $item->descripcion,
                        'metodo_pago' => $item->metodo_pago_nombre,
                        'valor' => $item->total,
                        'clase' => 'success'
                    ];
                });
        }

        if ($tipo === 'todos' || $tipo === 'egresos') {
            $egresos = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->orderBy('fecha', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'fecha' => $item->fecha,
                        'tipo' => 'Egreso',
                        'numero' => $item->numero_documento,
                        'concepto' => $item->proveedor . ' - ' . $item->descripcion,
                        'metodo_pago' => $item->metodo_pago_nombre,
                        'valor' => $item->total,
                        'clase' => 'danger'
                    ];
                });
        }

        // Combinar y ordenar
        $transacciones = $ingresos->concat($egresos)->sortByDesc('fecha');

        // Totales
        $totalIngresos = $ingresos->sum('valor');
        $totalEgresos = $egresos->sum('valor');

        return view('metodos-pago.historial', compact(
            'transacciones',
            'totalIngresos',
            'totalEgresos',
            'fechaInicio',
            'fechaFin',
            'tipo'
        ));
    }

    /**
     * Ver cobros pendientes (para futuras implementaciones)
     */
    public function cobrosPendientes()
    {
        return view('metodos-pago.cobros-pendientes');
    }

    /**
     * Ver detalle de un método de pago
     */
    public function show($metodo)
    {
        $metodosPago = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia Bancaria',
            'tarjeta' => 'Tarjeta de Crédito/Débito',
            'cheque' => 'Cheque',
            'otros' => 'Otros'
        ];

        if (!isset($metodosPago[$metodo])) {
            return redirect()->route('metodos-pago.index')
                ->with('error', 'Método de pago no válido.');
        }

        $fechaInicio = request('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = request('fecha_fin', now()->toDateString());

        $ingresos = Comprobante::where('metodo_pago', $metodo)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();

        $egresos = Egreso::where('metodo_pago', $metodo)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();

        $totalIngresos = $ingresos->sum('total');
        $totalEgresos = $egresos->sum('total');
        $nombreMetodo = $metodosPago[$metodo];

        return view('metodos-pago.show', compact(
            'metodo',
            'nombreMetodo',
            'ingresos',
            'egresos',
            'totalIngresos',
            'totalEgresos',
            'fechaInicio',
            'fechaFin'
        ));
    }
}
