<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comprobante;
use App\Models\Egreso;
use App\Models\Multa;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    /**
     * Reporte de Estado de Resultados
     */
    public function estadoResultados(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Ingresos por tipo
        $ingresos = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('tipo', DB::raw('SUM(total) as total'))
            ->groupBy('tipo')
            ->get();

        $totalIngresos = $ingresos->sum('total');

        // Egresos por categoría
        $egresos = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('categoria', DB::raw('SUM(total) as total'))
            ->groupBy('categoria')
            ->get();

        $totalEgresos = $egresos->sum('total');

        // Utilidad/Pérdida
        $utilidad = $totalIngresos - $totalEgresos;

        return view('reportes.estado-resultados', compact(
            'ingresos',
            'egresos',
            'totalIngresos',
            'totalEgresos',
            'utilidad',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Reporte de Flujo de Caja
     */
    public function flujoCaja(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Movimientos diarios
        $movimientos = [];
        $fecha = Carbon::parse($fechaInicio);
        $saldoAcumulado = 0;

        while ($fecha <= Carbon::parse($fechaFin)) {
            $ingresosDia = Comprobante::whereDate('fecha', $fecha)->sum('total');
            $egresosDia = Egreso::whereDate('fecha', $fecha)->sum('total');
            $flujoDia = $ingresosDia - $egresosDia;
            $saldoAcumulado += $flujoDia;

            if ($ingresosDia > 0 || $egresosDia > 0) {
                $movimientos[] = [
                    'fecha' => $fecha->format('Y-m-d'),
                    'fecha_formato' => $fecha->format('d/m/Y'),
                    'ingresos' => $ingresosDia,
                    'egresos' => $egresosDia,
                    'flujo' => $flujoDia,
                    'saldo_acumulado' => $saldoAcumulado
                ];
            }

            $fecha->addDay();
        }

        return view('reportes.flujo-caja', compact(
            'movimientos',
            'fechaInicio',
            'fechaFin'
        ));
    }

    /**
     * Reporte de Ingresos Detallado
     */
    public function ingresosDetalle(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $tipo = $request->get('tipo');

        $query = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        $comprobantes = $query->orderBy('fecha', 'desc')->get();

        // Totales por tipo de comprobante
        $totalesPorTipo = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('tipo', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->groupBy('tipo')
            ->get();

        // Totales por método de pago
        $totalesPorMetodo = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('metodo_pago', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->groupBy('metodo_pago')
            ->get();

        return view('reportes.ingresos-detalle', compact(
            'comprobantes',
            'totalesPorTipo',
            'totalesPorMetodo',
            'fechaInicio',
            'fechaFin',
            'tipo'
        ));
    }

    /**
     * Reporte de Egresos Detallado
     */
    public function egresosDetalle(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $categoria = $request->get('categoria');

        $query = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        if ($categoria) {
            $query->where('categoria', $categoria);
        }

        $egresos = $query->orderBy('fecha', 'desc')->get();

        // Totales por categoría
        $totalesPorCategoria = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('categoria', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->groupBy('categoria')
            ->get();

        // Totales por tipo documento
        $totalesPorTipo = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->select('tipo', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->groupBy('tipo')
            ->get();

        return view('reportes.egresos-detalle', compact(
            'egresos',
            'totalesPorCategoria',
            'totalesPorTipo',
            'fechaInicio',
            'fechaFin',
            'categoria'
        ));
    }

    /**
     * Generar PDF de cualquier reporte
     */
    public function generarPDF(Request $request, $tipo)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $anio = $request->get('anio', Carbon::now()->year);

        $data = $this->getReporteData($tipo, $fechaInicio, $fechaFin, $anio);
        $data['tipo'] = $tipo;
        $data['fechaInicio'] = $fechaInicio;
        $data['fechaFin'] = $fechaFin;
        $data['anio'] = $anio;
        $data['fechaGeneracion'] = Carbon::now()->format('d/m/Y H:i');

        // Mapear tipo de reporte a vista PDF
        $vistaMap = [
            'estado-resultados' => 'estado-resultados',
            'ingresos' => 'ingresos',
            'egresos' => 'egresos',
            'resumen' => 'resumen',
            'flujo-caja' => 'resumen',
        ];

        $vista = $vistaMap[$tipo] ?? 'resumen';
        
        $pdf = Pdf::loadView('reportes.pdf.' . $vista, $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download("reporte_{$tipo}_{$fechaInicio}_{$fechaFin}.pdf");
    }

    private function getReporteData($tipo, $fechaInicio, $fechaFin, $anio = null)
    {
        switch ($tipo) {
            case 'estado-resultados':
                $ingresos = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])
                    ->select('tipo', DB::raw('SUM(total) as total'))
                    ->groupBy('tipo')
                    ->get();
                $egresos = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
                    ->select('categoria', DB::raw('SUM(total) as total'))
                    ->groupBy('categoria')
                    ->get();
                return [
                    'ingresos' => $ingresos,
                    'egresos' => $egresos,
                    'totalIngresos' => $ingresos->sum('total'),
                    'totalEgresos' => $egresos->sum('total'),
                ];
            
            case 'ingresos':
                return [
                    'comprobantes' => Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])
                        ->orderBy('fecha', 'desc')->get()
                ];
            
            case 'egresos':
                return [
                    'egresos' => Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
                        ->orderBy('fecha', 'desc')->get()
                ];
            
            case 'resumen':
            case 'flujo-caja':
                $anio = $anio ?? Carbon::now()->year;
                $dataMensual = [];
                $totalIngresos = 0;
                $totalEgresos = 0;
                
                for ($mes = 1; $mes <= 12; $mes++) {
                    $inicioMes = Carbon::createFromDate($anio, $mes, 1)->startOfMonth();
                    $finMes = Carbon::createFromDate($anio, $mes, 1)->endOfMonth();
                    
                    $ingresosMes = Comprobante::whereBetween('fecha', [$inicioMes, $finMes])->sum('total');
                    $egresosMes = Egreso::whereBetween('fecha', [$inicioMes, $finMes])->sum('total');
                    
                    $totalIngresos += $ingresosMes;
                    $totalEgresos += $egresosMes;
                    
                    $dataMensual[] = [
                        'mes' => $inicioMes->translatedFormat('F'),
                        'mes_num' => $mes,
                        'ingresos' => $ingresosMes,
                        'egresos' => $egresosMes,
                        'balance' => $ingresosMes - $egresosMes,
                        'margen' => $ingresosMes > 0 ? round((($ingresosMes - $egresosMes) / $ingresosMes) * 100, 2) : 0
                    ];
                }
                
                return [
                    'dataMensual' => $dataMensual,
                    'totales' => [
                        'ingresos' => $totalIngresos,
                        'egresos' => $totalEgresos,
                        'balance' => $totalIngresos - $totalEgresos,
                    ]
                ];
            
            default:
                return [];
        }
    }

    /**
     * Resumen Ejecutivo
     */
    public function resumenEjecutivo(Request $request)
    {
        $anio = $request->get('anio', Carbon::now()->year);

        $dataMensual = [];
        
        for ($mes = 1; $mes <= 12; $mes++) {
            $fechaInicio = Carbon::createFromDate($anio, $mes, 1)->startOfMonth();
            $fechaFin = Carbon::createFromDate($anio, $mes, 1)->endOfMonth();

            $ingresos = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])->sum('total');
            $egresos = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])->sum('total');

            $dataMensual[] = [
                'mes' => $fechaInicio->translatedFormat('F'),
                'mes_num' => $mes,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'balance' => $ingresos - $egresos,
                'margen' => $ingresos > 0 ? round((($ingresos - $egresos) / $ingresos) * 100, 2) : 0
            ];
        }

        $totales = [
            'ingresos' => array_sum(array_column($dataMensual, 'ingresos')),
            'egresos' => array_sum(array_column($dataMensual, 'egresos')),
        ];
        $totales['balance'] = $totales['ingresos'] - $totales['egresos'];

        return view('reportes.resumen-ejecutivo', compact('dataMensual', 'totales', 'anio'));
    }
}
