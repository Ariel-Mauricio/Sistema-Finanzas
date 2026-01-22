@extends('layouts.master')

@section('title', 'Centro de Reportes')

@section('content')
<div class="page-header">
    <h1 class="page-title">Centro de Reportes</h1>
    <p class="page-subtitle">Accede a todos los reportes financieros del sistema</p>
</div>

<!-- Filtros Globales -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filtrosGlobales" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" 
                       value="{{ request('fecha_inicio', now()->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" 
                       value="{{ request('fecha_fin', now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sucursal</label>
                <select class="form-select" name="base">
                    <option value="">Todas</option>
                    <option value="norte">Norte</option>
                    <option value="sur">Sur</option>
                    <option value="sangolqui">Sangolquí</option>
                    <option value="latacunga">Latacunga</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i>Aplicar Filtros
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reportes Disponibles -->
<div class="row g-4">
    <!-- Estado de Resultados -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); width: 70px; height: 70px;">
                        <i class="fas fa-file-invoice-dollar text-white fa-lg"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Estado de Resultados</h5>
                <p class="text-muted small mb-4">Resumen de ingresos, gastos y utilidad neta del período seleccionado.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reportes.estado-resultados') }}" class="btn btn-outline-success">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.pdf', 'estado-resultados') }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flujo de Caja -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); width: 70px; height: 70px;">
                        <i class="fas fa-money-bill-wave text-white fa-lg"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Flujo de Caja</h5>
                <p class="text-muted small mb-4">Movimiento detallado del efectivo día a día con saldos acumulados.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reportes.flujo-caja') }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.pdf', 'flujo-caja') }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalle de Ingresos -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); width: 70px; height: 70px;">
                        <i class="fas fa-arrow-up text-white fa-lg"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Detalle de Ingresos</h5>
                <p class="text-muted small mb-4">Lista completa de todos los comprobantes de ingreso con totales.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reportes.ingresos-detalle') }}" class="btn btn-outline-secondary" style="border-color: #8B5CF6; color: #8B5CF6;">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.pdf', 'ingresos') }}" class="btn text-white" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalle de Egresos -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%); width: 70px; height: 70px;">
                        <i class="fas fa-arrow-down text-white fa-lg"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Detalle de Egresos</h5>
                <p class="text-muted small mb-4">Lista completa de todos los gastos y egresos registrados.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reportes.egresos-detalle') }}" class="btn btn-outline-danger">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.pdf', 'egresos') }}" class="btn btn-danger" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 70px; height: 70px;">
                        <i class="fas fa-chart-line text-white fa-lg"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Resumen Ejecutivo</h5>
                <p class="text-muted small mb-4">Vista anual con comparativos mensuales e indicadores clave.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('reportes.resumen-ejecutivo') }}" class="btn btn-outline-warning">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="{{ route('reportes.pdf', 'resumen') }}" class="btn btn-warning text-dark" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance General -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <div class="stat-icon mx-auto" style="background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%); width: 70px; height: 70px;">
                        <i class="fas fa-balance-scale text-white fa-lg"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Balance Comparativo</h5>
                <p class="text-muted small mb-4">Comparación de ingresos vs egresos por sucursal y período.</p>
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>Ver Reporte
                    </a>
                    <a href="#" class="btn btn-info text-white" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Exportación Masiva -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-download text-primary me-2"></i>
            Exportación de Datos
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('export-import.index') }}" class="btn btn-outline-success w-100 py-3">
                    <i class="fas fa-file-excel fa-2x mb-2 d-block"></i>
                    Exportar a Excel
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-outline-danger w-100 py-3">
                    <i class="fas fa-file-pdf fa-2x mb-2 d-block"></i>
                    Exportar Todo a PDF
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-outline-primary w-100 py-3">
                    <i class="fas fa-file-csv fa-2x mb-2 d-block"></i>
                    Exportar a CSV
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
