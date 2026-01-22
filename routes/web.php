<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportImportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\MetodoPagoController;

/*
|--------------------------------------------------------------------------
| FinanzaPro - Sistema de Gestión Financiera Profesional
|--------------------------------------------------------------------------
| Rutas protegidas con autenticación y control de roles
| Incluye validaciones de seguridad y rate limiting
|--------------------------------------------------------------------------
*/

// =============================================================================
// RUTAS PÚBLICAS (Solo autenticación)
// =============================================================================

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:5,1']); // Máximo 5 intentos por minuto

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout');

// =============================================================================
// RUTAS PROTEGIDAS (Requieren autenticación)
// =============================================================================

Route::middleware(['auth'])->group(function () {
    
    // Redirección raíz al dashboard
    Route::get('/', fn() => redirect()->route('dashboard'));
    
    // Dashboard principal (todos los usuarios autenticados)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // =========================================================================
    // MÓDULO FINANCIERO (Admin y Contador)
    // =========================================================================
    Route::middleware(['role:admin,contador'])->group(function () {
        
        // ----- COMPROBANTES (INGRESOS) -----
        Route::resource('comprobantes', ComprobanteController::class);
        Route::get('comprobantes/{comprobante}/pdf', [ComprobanteController::class, 'generatePDF'])
            ->name('comprobantes.pdf')
            ->whereNumber('comprobante');
        
        // ----- EGRESOS (GASTOS) -----
        Route::resource('egresos', EgresoController::class);
        Route::get('egresos/{egreso}/pdf', [EgresoController::class, 'generarPdf'])
            ->name('egresos.pdf')
            ->whereNumber('egreso');
        Route::get('egresos-reporte/pdf', [EgresoController::class, 'reportePdf'])
            ->name('egresos.reporte-pdf');
        
        // ----- MULTAS -----
        Route::resource('multas', MultaController::class);
        Route::get('multas/{multa}/pdf', [MultaController::class, 'generarPdf'])
            ->name('multas.pdf')
            ->whereNumber('multa');
        Route::get('multas-reporte/pdf', [MultaController::class, 'reportePdf'])
            ->name('multas.reporte-pdf');
        
        // ----- CENTRO DE REPORTES -----
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/', [ReporteController::class, 'index'])->name('index');
            Route::get('/estado-resultados', [ReporteController::class, 'estadoResultados'])->name('estado-resultados');
            Route::get('/flujo-caja', [ReporteController::class, 'flujoCaja'])->name('flujo-caja');
            Route::get('/ingresos-detalle', [ReporteController::class, 'ingresosDetalle'])->name('ingresos-detalle');
            Route::get('/egresos-detalle', [ReporteController::class, 'egresosDetalle'])->name('egresos-detalle');
            Route::get('/resumen-ejecutivo', [ReporteController::class, 'resumenEjecutivo'])->name('resumen-ejecutivo');
            Route::get('/pdf/{tipo}', [ReporteController::class, 'generarPDF'])
                ->name('pdf')
                ->whereAlpha('tipo');
        });
        
        // ----- MÉTODOS DE PAGO Y COBROS -----
        Route::prefix('metodos-pago')->name('metodos-pago.')->group(function () {
            Route::get('/', [MetodoPagoController::class, 'index'])->name('index');
            Route::get('/historial', [MetodoPagoController::class, 'historial'])->name('historial');
            Route::get('/cobros-pendientes', [MetodoPagoController::class, 'cobrosPendientes'])->name('cobros-pendientes');
            Route::get('/{metodo}', [MetodoPagoController::class, 'show'])
                ->name('show')
                ->where('metodo', '[a-z_]+');
        });
        
        // ----- EXPORTACIÓN/IMPORTACIÓN -----
        Route::get('/export-import', [ExportImportController::class, 'mostrarFormulario'])
            ->name('export-import.index');
        Route::get('/export-import/exportar', [ExportImportController::class, 'exportarDatos'])
            ->name('export-import.exportar');
        Route::post('/export-import/importar', [ExportImportController::class, 'importarDatos'])
            ->name('export-import.importar');
    });
    
    // =========================================================================
    // ADMINISTRACIÓN DEL SISTEMA (Solo Admin)
    // =========================================================================
    Route::middleware(['role:admin'])->group(function () {
        Route::prefix('configuracion')->name('configuracion.')->group(function () {
            // General
            Route::get('/', [ConfiguracionController::class, 'index'])->name('index');
            Route::post('/guardar', [ConfiguracionController::class, 'guardar'])->name('guardar');
            
            // Empresa
            Route::get('/empresa', [ConfiguracionController::class, 'empresa'])->name('empresa');
            Route::post('/empresa', [ConfiguracionController::class, 'guardarEmpresa'])->name('empresa.guardar');
            
            // Gestión de Usuarios
            Route::get('/usuarios', [ConfiguracionController::class, 'usuarios'])->name('usuarios');
            Route::post('/usuarios', [ConfiguracionController::class, 'crearUsuario'])->name('usuarios.crear');
            Route::put('/usuarios/{usuario}', [ConfiguracionController::class, 'actualizarUsuario'])
                ->name('usuarios.actualizar')
                ->whereNumber('usuario');
            Route::patch('/usuarios/{usuario}/toggle', [ConfiguracionController::class, 'toggleUsuario'])
                ->name('usuarios.toggle')
                ->whereNumber('usuario');
            Route::delete('/usuarios/{usuario}', [ConfiguracionController::class, 'eliminarUsuario'])
                ->name('usuarios.eliminar')
                ->whereNumber('usuario');
            
            // Notificaciones
            Route::get('/notificaciones', [ConfiguracionController::class, 'notificaciones'])->name('notificaciones');
            Route::post('/notificaciones', [ConfiguracionController::class, 'guardarNotificaciones'])->name('notificaciones.guardar');
            
            // Respaldos
            Route::get('/respaldos', [ConfiguracionController::class, 'respaldos'])->name('respaldos');
            Route::post('/respaldos/crear', [ConfiguracionController::class, 'crearBackup'])->name('respaldos.crear');
            Route::get('/respaldos/descargar/{filename}', [ConfiguracionController::class, 'descargarBackup'])
                ->name('respaldos.descargar')
                ->where('filename', '[a-zA-Z0-9_\-\.]+');
            Route::delete('/respaldos/{filename}', [ConfiguracionController::class, 'eliminarBackup'])
                ->name('respaldos.eliminar')
                ->where('filename', '[a-zA-Z0-9_\-\.]+');
            Route::post('/respaldos/restaurar', [ConfiguracionController::class, 'restaurarBackup'])->name('respaldos.restaurar');
            
            // Seguridad
            Route::get('/seguridad', [ConfiguracionController::class, 'seguridad'])->name('seguridad');
            Route::post('/seguridad', [ConfiguracionController::class, 'guardarSeguridad'])->name('seguridad.guardar');
        });
    });
});

// =============================================================================
// API INTERNA (Protegida con autenticación y throttle)
// =============================================================================

Route::middleware(['auth', 'throttle:60,1'])->prefix('api')->group(function () {
    
    // API de estadísticas para dashboard
    Route::get('/estadisticas', [DashboardController::class, 'apiEstadisticas'])
        ->name('api.estadisticas');
    
    // Búsqueda global (con validación de entrada)
    Route::get('/buscar', function (\Illuminate\Http\Request $request) {
        $query = trim($request->get('q', ''));
        
        // Validación de seguridad
        if (strlen($query) < 3 || strlen($query) > 100) {
            return response()->json([]);
        }
        
        // Sanitizar entrada
        $query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
        
        $resultados = [];
        
        // Buscar en comprobantes (limitado)
        $comprobantes = \App\Models\Comprobante::where('nombre', 'like', "%{$query}%")
            ->orWhere('apellido', 'like', "%{$query}%")
            ->orWhere('cedula', 'like', "%{$query}%")
            ->orWhere('numero_comprobante', 'like', "%{$query}%")
            ->select(['id', 'numero_comprobante', 'nombre', 'apellido'])
            ->limit(5)
            ->get();
            
        foreach ($comprobantes as $c) {
            $resultados[] = [
                'tipo' => 'Comprobante',
                'titulo' => "#{$c->numero_comprobante} - " . e($c->nombre) . " " . e($c->apellido),
                'url' => route('comprobantes.show', $c->id),
                'icono' => 'fa-file-invoice-dollar',
                'color' => 'success'
            ];
        }
        
        // Buscar en egresos (limitado)
        $egresos = \App\Models\Egreso::where('proveedor', 'like', "%{$query}%")
            ->orWhere('descripcion', 'like', "%{$query}%")
            ->orWhere('numero_documento', 'like', "%{$query}%")
            ->select(['id', 'numero_documento', 'proveedor'])
            ->limit(5)
            ->get();
            
        foreach ($egresos as $e) {
            $resultados[] = [
                'tipo' => 'Egreso',
                'titulo' => "#{$e->numero_documento} - " . e($e->proveedor),
                'url' => route('egresos.show', $e->id),
                'icono' => 'fa-receipt',
                'color' => 'danger'
            ];
        }
        
        return response()->json($resultados);
    })->name('api.buscar');
});
