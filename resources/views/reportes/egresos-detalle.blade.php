@extends('layouts.master')

@section('title', 'Detalle de Egresos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-arrow-down text-danger me-2"></i>
            Detalle de Egresos
        </h1>
        <p class="page-subtitle">Análisis de todos los gastos registrados</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
        <a href="{{ route('reportes.pdf', ['tipo' => 'egresos']) }}?fecha_inicio={{ $fechaInicio }}&fecha_fin={{ $fechaFin }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Descargar PDF
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('reportes.egresos-detalle') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="categoria">
                    <option value="">Todas</option>
                    @foreach(\App\Models\Egreso::$categorias as $key => $value)
                        <option value="{{ $key }}" {{ $categoria == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                        <p class="stat-label text-muted mb-1">Total Egresos</p>
                        <h3 class="stat-value mb-0 text-danger">$ {{ number_format($egresos->sum('total'), 2) }}</h3>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
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
                        <h3 class="stat-value mb-0">{{ $egresos->count() }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-file-invoice-dollar"></i>
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
                        <h3 class="stat-value mb-0">$ {{ $egresos->count() > 0 ? number_format($egresos->sum('total') / $egresos->count(), 2) : '0.00' }}</h3>
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
    <!-- Por Categoría -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie text-danger me-2"></i>Por Categoría
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totalesPorCategoria as $item)
                            <tr>
                                <td>{{ \App\Models\Egreso::$categorias[$item->categoria] ?? $item->categoria }}</td>
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
                    <i class="fas fa-file-alt text-danger me-2"></i>Por Tipo Documento
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
                                <td>{{ \App\Models\Egreso::$tipos[$item->tipo] ?? $item->tipo }}</td>
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
                    <i class="fas fa-list text-danger me-2"></i>Listado de Egresos
                </h5>
                <span class="badge bg-danger">{{ $egresos->count() }} registros</span>
            </div>
            <div class="card-body p-0">
                @if($egresos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Número</th>
                                <th>Proveedor</th>
                                <th>Categoría</th>
                                <th>Método</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($egresos as $egreso)
                            <tr>
                                <td>{{ $egreso->fecha->format('d/m/Y') }}</td>
                                <td><span class="badge bg-danger">#{{ $egreso->numero_documento }}</span></td>
                                <td>{{ $egreso->proveedor }}</td>
                                <td>{{ $egreso->categoria_nombre }}</td>
                                <td>{{ $egreso->metodo_pago_nombre }}</td>
                                <td class="text-end fw-bold text-danger">$ {{ number_format($egreso->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">TOTAL:</th>
                                <th class="text-end text-danger">$ {{ number_format($egresos->sum('total'), 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay egresos en el período seleccionado</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
