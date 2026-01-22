@extends('layouts.master')

@section('title', 'Detalle de Ingresos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-arrow-up text-success me-2"></i>
            Detalle de Ingresos
        </h1>
        <p class="page-subtitle">Análisis de todos los ingresos registrados</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
        <a href="{{ route('reportes.pdf', ['tipo' => 'ingresos']) }}?fecha_inicio={{ $fechaInicio }}&fecha_fin={{ $fechaFin }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Descargar PDF
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('reportes.ingresos-detalle') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo</label>
                <select class="form-select" name="tipo">
                    <option value="">Todos</option>
                    @foreach(\App\Models\Comprobante::$tipos as $key => $value)
                        <option value="{{ $key }}" {{ $tipo == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Resumen -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label text-muted mb-1">Total Ingresos</p>
                        <h3 class="stat-value mb-0 text-success">$ {{ number_format($comprobantes->sum('total'), 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label text-muted mb-1">Total Registros</p>
                        <h3 class="stat-value mb-0">{{ $comprobantes->count() }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label text-muted mb-1">Promedio</p>
                        <h3 class="stat-value mb-0">$ {{ $comprobantes->count() > 0 ? number_format($comprobantes->sum('total') / $comprobantes->count(), 2) : '0.00' }}</h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Por Tipo -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie text-primary me-2"></i>Por Tipo de Comprobante
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totalesPorTipo as $item)
                            <tr>
                                <td>{{ \App\Models\Comprobante::$tipos[$item->tipo] ?? $item->tipo }}</td>
                                <td class="text-center">{{ $item->cantidad }}</td>
                                <td class="text-end">$ {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card text-primary me-2"></i>Por Método de Pago
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Método</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totalesPorMetodo as $item)
                            <tr>
                                <td>{{ \App\Models\Comprobante::$metodosPago[$item->metodo_pago] ?? $item->metodo_pago }}</td>
                                <td class="text-center">{{ $item->cantidad }}</td>
                                <td class="text-end">$ {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Detalle -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list text-primary me-2"></i>Listado de Ingresos
                </h5>
                <span class="badge bg-success">{{ $comprobantes->count() }} registros</span>
            </div>
            <div class="card-body p-0">
                @if($comprobantes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Método</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comprobantes as $comprobante)
                            <tr>
                                <td>{{ $comprobante->fecha->format('d/m/Y') }}</td>
                                <td><span class="badge bg-success">#{{ $comprobante->numero_comprobante }}</span></td>
                                <td>{{ $comprobante->cliente }}</td>
                                <td>{{ $comprobante->tipo_nombre }}</td>
                                <td>{{ $comprobante->metodo_pago_nombre }}</td>
                                <td class="text-end fw-bold text-success">$ {{ number_format($comprobante->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">TOTAL:</th>
                                <th class="text-end text-success">$ {{ number_format($comprobantes->sum('total'), 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay ingresos en el período seleccionado</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
