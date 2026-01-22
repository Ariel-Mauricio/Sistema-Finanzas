<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comprobante;
use App\Models\Egreso;
use App\Models\Multa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Período actual
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        $inicioAnio = Carbon::now()->startOfYear();
        $finAnio = Carbon::now()->endOfYear();

        // ESTADÍSTICAS GENERALES
        $stats = [
            // Ingresos
            'ingresos_hoy' => Comprobante::whereDate('fecha', today())->sum('total'),
            'ingresos_mes' => Comprobante::whereBetween('fecha', [$inicioMes, $finMes])->sum('total'),
            'ingresos_anio' => Comprobante::whereBetween('fecha', [$inicioAnio, $finAnio])->sum('total'),
            'total_comprobantes' => Comprobante::count(),
            'comprobantes_mes' => Comprobante::whereBetween('created_at', [$inicioMes, $finMes])->count(),
            
            // Egresos
            'egresos_hoy' => Egreso::whereDate('fecha', today())->sum('total'),
            'egresos_mes' => Egreso::whereBetween('fecha', [$inicioMes, $finMes])->sum('total'),
            'egresos_anio' => Egreso::whereBetween('fecha', [$inicioAnio, $finAnio])->sum('total'),
            'total_egresos' => Egreso::count(),
            'egresos_count_mes' => Egreso::whereBetween('created_at', [$inicioMes, $finMes])->count(),
            
            // Multas
            'multas_mes' => Schema::hasTable('multas') ? Multa::whereBetween('fecha', [$inicioMes, $finMes])->sum('valor') : 0,
            'total_multas' => Schema::hasTable('multas') ? Multa::count() : 0,
            
            // Balance
            'balance_mes' => 0,
            'balance_anio' => 0,
        ];

        $stats['balance_mes'] = $stats['ingresos_mes'] - $stats['egresos_mes'];
        $stats['balance_anio'] = $stats['ingresos_anio'] - $stats['egresos_anio'];

        // DATOS PARA GRÁFICOS
        $chartData = $this->getChartDataMensual();
        
        // Últimas transacciones
        $ultimosComprobantes = Comprobante::orderBy('created_at', 'desc')->take(5)->get();
        $ultimosEgresos = Egreso::orderBy('created_at', 'desc')->take(5)->get();
        
        // Top categorías de egresos
        $topEgresos = Egreso::select('categoria', DB::raw('SUM(total) as total'))
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->groupBy('categoria')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Indicadores de rendimiento
        $kpis = [
            'crecimiento_ingresos' => $this->calcularCrecimiento('ingresos'),
            'crecimiento_egresos' => $this->calcularCrecimiento('egresos'),
            'margen_operativo' => $stats['ingresos_mes'] > 0 
                ? round((($stats['ingresos_mes'] - $stats['egresos_mes']) / $stats['ingresos_mes']) * 100, 2) 
                : 0,
        ];

        return view('dashboard_pro', compact(
            'stats',
            'chartData',
            'ultimosComprobantes',
            'ultimosEgresos',
            'topEgresos',
            'kpis'
        ));
    }

    private function getChartDataMensual()
    {
        $meses = [];
        $ingresos = [];
        $egresos = [];

        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $meses[] = $fecha->translatedFormat('M Y');
            
            $ingresos[] = Comprobante::whereMonth('fecha', $fecha->month)
                ->whereYear('fecha', $fecha->year)
                ->sum('total');
                
            $egresos[] = Egreso::whereMonth('fecha', $fecha->month)
                ->whereYear('fecha', $fecha->year)
                ->sum('total');
        }

        return [
            'labels' => $meses,
            'ingresos' => $ingresos,
            'egresos' => $egresos
        ];
    }

    private function calcularCrecimiento($tipo)
    {
        $mesActual = Carbon::now();
        $mesAnterior = Carbon::now()->subMonth();

        if ($tipo === 'ingresos') {
            $actual = Comprobante::whereMonth('fecha', $mesActual->month)
                ->whereYear('fecha', $mesActual->year)
                ->sum('total');
            $anterior = Comprobante::whereMonth('fecha', $mesAnterior->month)
                ->whereYear('fecha', $mesAnterior->year)
                ->sum('total');
        } else {
            $actual = Egreso::whereMonth('fecha', $mesActual->month)
                ->whereYear('fecha', $mesActual->year)
                ->sum('total');
            $anterior = Egreso::whereMonth('fecha', $mesAnterior->month)
                ->whereYear('fecha', $mesAnterior->year)
                ->sum('total');
        }

        if ($anterior == 0) {
            return $actual > 0 ? 100 : 0;
        }

        return round((($actual - $anterior) / $anterior) * 100, 2);
    }

    public function reporteFinanciero(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $ingresos = Comprobante::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();

        $egresos = Egreso::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();

        $totalIngresos = $ingresos->sum('valor_total');
        $totalEgresos = $egresos->sum('total');
        $balance = $totalIngresos - $totalEgresos;

        return view('reportes.financiero', compact(
            'ingresos',
            'egresos',
            'totalIngresos',
            'totalEgresos',
            'balance',
            'fechaInicio',
            'fechaFin'
        ));
    }

    public function apiEstadisticas()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        return response()->json([
            'ingresos_mes' => Comprobante::whereBetween('fecha', [$inicioMes, $finMes])->sum('valor_total'),
            'egresos_mes' => Egreso::whereBetween('fecha', [$inicioMes, $finMes])->sum('total'),
            'comprobantes_hoy' => Comprobante::whereDate('created_at', today())->count(),
            'egresos_hoy' => Egreso::whereDate('created_at', today())->count(),
        ]);
    }
}
