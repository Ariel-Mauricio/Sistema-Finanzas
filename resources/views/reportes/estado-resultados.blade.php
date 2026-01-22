@extends('layouts.master')

@section('title', 'Estado de Resultados')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-chart-bar text-primary me-2"></i>
            Estado de Resultados
        </h1>
        <p class="page-subtitle">Resumen de ingresos vs egresos</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
        <a href="{{ route('reportes.pdf', ['tipo' => 'estado-resultados']) }}?fecha_inicio={{ $fechaInicio }}&fecha_fin={{ $fechaFin }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Descargar PDF
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('reportes.estado-resultados') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i>Generar Reporte
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Resumen Principal -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card border-0 border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label text-muted mb-1">Total Ingresos</p>
                        <h3 class="stat-value mb-0 text-success">$ {{ number_format($totalIngresos, 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0 border-start border-danger border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label text-muted mb-1">Total Egresos</p>
                        <h3 class="stat-value mb-0 text-danger">$ {{ number_format($totalEgresos, 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0 border-start border-{{ $utilidad >= 0 ? 'primary' : 'warning' }} border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label text-muted mb-1">{{ $utilidad >= 0 ? 'Utilidad' : 'Pérdida' }}</p>
                        <h3 class="stat-value mb-0 text-{{ $utilidad >= 0 ? 'primary' : 'warning' }}">$ {{ number_format(abs($utilidad), 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-{{ $utilidad >= 0 ? 'primary' : 'warning' }} bg-opacity-10 text-{{ $utilidad >= 0 ? 'primary' : 'warning' }}">
                        <i class="fas fa-{{ $utilidad >= 0 ? 'trophy' : 'exclamation-triangle' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Ingresos por Tipo -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-up me-2"></i>Ingresos por Tipo
                </h5>
            </div>
            <div class="card-body">
                @if($ingresos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ingresos as $ingreso)
                            <tr>
                                <td>{{ \App\Models\Comprobante::$tipos[$ingreso->tipo] ?? $ingreso->tipo }}</td>
                                <td class="text-end">$ {{ number_format($ingreso->total, 2) }}</td>
                                <td class="text-end">{{ $totalIngresos > 0 ? number_format(($ingreso->total / $totalIngresos) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>TOTAL INGRESOS</th>
                                <th class="text-end text-success">$ {{ number_format($totalIngresos, 2) }}</th>
                                <th class="text-end">100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">No hay ingresos en este período</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Egresos por Categoría -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-down me-2"></i>Egresos por Categoría
                </h5>
            </div>
            <div class="card-body">
                @if($egresos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($egresos as $egreso)
                            <tr>
                                <td>{{ \App\Models\Egreso::$categorias[$egreso->categoria] ?? $egreso->categoria }}</td>
                                <td class="text-end">$ {{ number_format($egreso->total, 2) }}</td>
                                <td class="text-end">{{ $totalEgresos > 0 ? number_format(($egreso->total / $totalEgresos) * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>TOTAL EGRESOS</th>
                                <th class="text-end text-danger">$ {{ number_format($totalEgresos, 2) }}</th>
                                <th class="text-end">100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">No hay egresos en este período</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Resultado Final -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-calculator text-primary me-2"></i>Resultado del Período
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <td class="fw-bold">Total Ingresos</td>
                        <td class="text-end fs-5 text-success">$ {{ number_format($totalIngresos, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">(-) Total Egresos</td>
                        <td class="text-end fs-5 text-danger">$ {{ number_format($totalEgresos, 2) }}</td>
                    </tr>
                    <tr class="table-{{ $utilidad >= 0 ? 'success' : 'warning' }}">
                        <td class="fw-bold fs-5">(=) {{ $utilidad >= 0 ? 'UTILIDAD NETA' : 'PÉRDIDA NETA' }}</td>
                        <td class="text-end fs-4 fw-bold">$ {{ number_format(abs($utilidad), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        @if($totalIngresos > 0)
        <div class="mt-3">
            <p class="text-muted mb-1">Margen de utilidad:</p>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-{{ $utilidad >= 0 ? 'success' : 'warning' }}" 
                     role="progressbar" 
                     style="width: {{ abs($utilidad / $totalIngresos) * 100 }}%">
                    {{ number_format(($utilidad / $totalIngresos) * 100, 1) }}%
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
