<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Comprobante;
use App\Models\Egreso;
use App\Models\Multa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExportImportController extends Controller
{
    /**
     * Exportar datos del día actual o rango de fechas
     */
    public function exportarDatos(Request $request)
    {
        try {
            // Validar fechas de entrada
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            
            // Sanitizar y validar fechas
            if (!$fechaInicio || !strtotime($fechaInicio)) {
                $fechaInicio = Carbon::today()->format('Y-m-d');
            }
            if (!$fechaFin || !strtotime($fechaFin)) {
                $fechaFin = Carbon::today()->format('Y-m-d');
            }
            
            // Asegurar que fechaInicio no sea mayor que fechaFin
            if (strtotime($fechaInicio) > strtotime($fechaFin)) {
                $temp = $fechaInicio;
                $fechaInicio = $fechaFin;
                $fechaFin = $temp;
            }
            
            // Obtener todos los datos del rango de fechas
            $comprobantes = Comprobante::whereBetween('created_at', [
                $fechaInicio . ' 00:00:00',
                $fechaFin . ' 23:59:59'
            ])->get();
            
            $egresos = Egreso::whereBetween('created_at', [
                $fechaInicio . ' 00:00:00',
                $fechaFin . ' 23:59:59'
            ])->get();
            
            $multas = Multa::whereBetween('created_at', [
                $fechaInicio . ' 00:00:00',
                $fechaFin . ' 23:59:59'
            ])->get();
            
            // Preparar datos para exportación
            $datosExportacion = [
                'fecha_exportacion' => Carbon::now()->format('Y-m-d H:i:s'),
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'origen' => config('app.sucursal', 'PRINCIPAL'),
                'version' => '2.0',
                'comprobantes' => $comprobantes->toArray(),
                'egresos' => $egresos->toArray(),
                'multas' => $multas->toArray(),
                'resumen' => [
                    'total_comprobantes' => $comprobantes->count(),
                    'total_egresos' => $egresos->count(),
                    'total_multas' => $multas->count(),
                    'suma_comprobantes' => $comprobantes->sum('valor_total'),
                    'suma_egresos' => $egresos->sum('total'),
                    'suma_multas' => $multas->sum('valor'),
                ]
            ];
            
            // Generar nombre del archivo seguro
            $nombreArchivo = sprintf(
                'finanzapro_%s_al_%s_%s.json',
                $fechaInicio,
                $fechaFin,
                date('His')
            );
            
            // Devolver archivo para descarga
            return response()->json($datosExportacion)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
                
        } catch (\Exception $e) {
            Log::error('Error al exportar datos: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al exportar datos',
                'mensaje' => 'Ha ocurrido un error durante la exportación. Por favor, inténtelo de nuevo.'
            ], 500);
        }
    }
    
    /**
     * Importar datos desde archivo JSON
     */
    public function importarDatos(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:json,txt|max:10240' // Máximo 10MB
        ], [
            'archivo.required' => 'Debe seleccionar un archivo para importar.',
            'archivo.file' => 'El archivo no es válido.',
            'archivo.mimes' => 'El archivo debe ser de tipo JSON.',
            'archivo.max' => 'El archivo no debe superar los 10MB.'
        ]);
        
        try {
            $archivo = $request->file('archivo');
            $contenido = file_get_contents($archivo->getPathname());
            $datos = json_decode($contenido, true);
            
            if (!$datos || json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'El archivo no tiene un formato JSON válido.');
            }
            
            DB::beginTransaction();
            
            $resultados = [
                'comprobantes_importados' => 0,
                'comprobantes_existentes' => 0,
                'egresos_importados' => 0,
                'egresos_existentes' => 0,
                'multas_importadas' => 0,
                'multas_existentes' => 0,
                'errores' => []
            ];
            
            // Importar comprobantes
            if (isset($datos['comprobantes']) && is_array($datos['comprobantes'])) {
                foreach ($datos['comprobantes'] as $comprobanteData) {
                    try {
                        // Verificar si ya existe por número_comprobante
                        if (!isset($comprobanteData['numero_comprobante'])) {
                            continue;
                        }
                        
                        $existe = Comprobante::where('numero_comprobante', $comprobanteData['numero_comprobante'])->first();
                        if ($existe) {
                            $resultados['comprobantes_existentes']++;
                            continue;
                        }
                        
                        // Remover campos que no se deben importar
                        unset($comprobanteData['id'], $comprobanteData['created_at'], $comprobanteData['updated_at']);
                        
                        Comprobante::create($comprobanteData);
                        $resultados['comprobantes_importados']++;
                        
                    } catch (\Exception $e) {
                        $resultados['errores'][] = 'Comprobante: ' . ($comprobanteData['numero_comprobante'] ?? 'desconocido');
                        Log::warning('Error importando comprobante: ' . $e->getMessage());
                    }
                }
            }
            
            // Importar egresos
            if (isset($datos['egresos']) && is_array($datos['egresos'])) {
                foreach ($datos['egresos'] as $egresoData) {
                    try {
                        if (!isset($egresoData['numero_documento'])) {
                            continue;
                        }
                        
                        $existe = Egreso::where('numero_documento', $egresoData['numero_documento'])->first();
                        if ($existe) {
                            $resultados['egresos_existentes']++;
                            continue;
                        }
                        
                        unset($egresoData['id'], $egresoData['created_at'], $egresoData['updated_at']);
                        
                        Egreso::create($egresoData);
                        $resultados['egresos_importados']++;
                        
                    } catch (\Exception $e) {
                        $resultados['errores'][] = 'Egreso: ' . ($egresoData['numero_documento'] ?? 'desconocido');
                        Log::warning('Error importando egreso: ' . $e->getMessage());
                    }
                }
            }
            
            // Importar multas
            if (isset($datos['multas']) && is_array($datos['multas'])) {
                foreach ($datos['multas'] as $multaData) {
                    try {
                        if (!isset($multaData['numero_documento'])) {
                            continue;
                        }
                        
                        $existe = Multa::where('numero_documento', $multaData['numero_documento'])->first();
                        if ($existe) {
                            $resultados['multas_existentes']++;
                            continue;
                        }
                        
                        unset($multaData['id'], $multaData['created_at'], $multaData['updated_at']);
                        
                        Multa::create($multaData);
                        $resultados['multas_importadas']++;
                        
                    } catch (\Exception $e) {
                        $resultados['errores'][] = 'Multa: ' . ($multaData['numero_documento'] ?? 'desconocido');
                        Log::warning('Error importando multa: ' . $e->getMessage());
                    }
                }
            }
            
            DB::commit();
            
            // Construir mensaje de resultado
            $mensaje = sprintf(
                'Importación completada: %d comprobantes, %d egresos, %d multas nuevos.',
                $resultados['comprobantes_importados'],
                $resultados['egresos_importados'],
                $resultados['multas_importadas']
            );
            
            $existentes = $resultados['comprobantes_existentes'] + $resultados['egresos_existentes'] + $resultados['multas_existentes'];
            if ($existentes > 0) {
                $mensaje .= sprintf(' (%d registros ya existían y fueron omitidos)', $existentes);
            }
            
            if (!empty($resultados['errores'])) {
                $mensaje .= ' Algunos errores: ' . implode(', ', array_slice($resultados['errores'], 0, 3));
                if (count($resultados['errores']) > 3) {
                    $mensaje .= '...';
                }
            }
            
            return back()->with('success', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al importar datos: ' . $e->getMessage());
            return back()->with('error', 'Error al importar datos. Por favor verifique el formato del archivo.');
        }
    }
    
    /**
     * Mostrar formulario de exportación/importación
     */
    public function mostrarFormulario()
    {
        // Obtener estadísticas para mostrar en el formulario
        $estadisticas = [
            'total_comprobantes' => Comprobante::count(),
            'total_egresos' => Egreso::count(),
            'total_multas' => Multa::count(),
            'ultima_sincronizacion' => null,
        ];
        
        return view('export-import.index', compact('estadisticas'));
    }
}
