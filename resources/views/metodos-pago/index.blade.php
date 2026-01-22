@extends('layouts.master')

@section('title', 'Métodos de Pago')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-credit-card text-primary me-2"></i>
            Métodos de Pago
        </h1>
        <p class="page-subtitle">Resumen de transacciones financieras</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('metodos-pago.historial') }}" class="btn btn-outline-primary">
            <i class="fas fa-history me-2"></i>Historial
        </a>
    </div>
</div>

<!-- Filtros de Fecha -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('metodos-pago.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Resumen General -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Total Ingresos</h6>
                        <h2 class="mb-0">${{ number_format($totalIngresos, 2) }}</h2>
                    </div>
                    <i class="fas fa-arrow-up fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Total Egresos</h6>
                        <h2 class="mb-0">${{ number_format($totalEgresos, 2) }}</h2>
                    </div>
                    <i class="fas fa-arrow-down fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card {{ $balance >= 0 ? 'bg-primary' : 'bg-warning' }} text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Balance</h6>
                        <h2 class="mb-0">${{ number_format($balance, 2) }}</h2>
                    </div>
                    <i class="fas fa-balance-scale fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Ingresos por Tipo -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie text-success me-2"></i>
                    Ingresos por Tipo de Comprobante
                </h5>
            </div>
            <div class="card-body">
                @if($ingresosPorTipo->isEmpty())
                    <p class="text-muted text-center py-4">No hay ingresos en el período</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingresosPorTipo as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-success-subtle text-success">
                                            {{ \App\Models\Comprobante::$tipos[$item->tipo] ?? ucfirst($item->tipo) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $item->cantidad }}</td>
                                    <td class="text-end text-success fw-bold">${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ingresos por Método de Pago -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-wallet text-info me-2"></i>
                    Ingresos por Método de Pago
                </h5>
            </div>
            <div class="card-body">
                @if($ingresosPorMetodo->isEmpty())
                    <p class="text-muted text-center py-4">No hay ingresos en el período</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Método</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingresosPorMetodo as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('metodos-pago.show', $item->metodo_pago) }}">
                                            {{ \App\Models\Comprobante::$metodosPago[$item->metodo_pago] ?? ucfirst($item->metodo_pago) }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $item->cantidad }}</td>
                                    <td class="text-end text-success fw-bold">${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Egresos por Categoría -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tags text-danger me-2"></i>
                    Egresos por Categoría
                </h5>
            </div>
            <div class="card-body">
                @if($egresosPorCategoria->isEmpty())
                    <p class="text-muted text-center py-4">No hay egresos en el período</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($egresosPorCategoria as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger">
                                            {{ \App\Models\Egreso::$categorias[$item->categoria] ?? ucfirst($item->categoria) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $item->cantidad }}</td>
                                    <td class="text-end text-danger fw-bold">${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Egresos por Método de Pago -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-money-bill-wave text-warning me-2"></i>
                    Egresos por Método de Pago
                </h5>
            </div>
            <div class="card-body">
                @if($egresosPorMetodo->isEmpty())
                    <p class="text-muted text-center py-4">No hay egresos en el período</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Método</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($egresosPorMetodo as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('metodos-pago.show', $item->metodo_pago) }}">
                                            {{ \App\Models\Egreso::$metodosPago[$item->metodo_pago] ?? ucfirst($item->metodo_pago) }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $item->cantidad }}</td>
                                    <td class="text-end text-danger fw-bold">${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Últimas Transacciones -->
<div class="row g-4 mt-2">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-up text-success me-2"></i>
                    Últimos Ingresos
                </h5>
            </div>
            <div class="card-body p-0">
                @if($ultimosIngresos->isEmpty())
                    <p class="text-muted text-center py-4">No hay ingresos recientes</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosIngresos as $ingreso)
                            <tr>
                                <td>{{ $ingreso->fecha->format('d/m') }}</td>
                                <td>
                                    <a href="{{ route('comprobantes.show', $ingreso) }}">
                                        {{ Str::limit($ingreso->cliente, 25) }}
                                    </a>
                                </td>
                                <td class="text-end text-success fw-bold">${{ number_format($ingreso->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-down text-danger me-2"></i>
                    Últimos Egresos
                </h5>
            </div>
            <div class="card-body p-0">
                @if($ultimosEgresos->isEmpty())
                    <p class="text-muted text-center py-4">No hay egresos recientes</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosEgresos as $egreso)
                            <tr>
                                <td>{{ $egreso->fecha->format('d/m') }}</td>
                                <td>
                                    <a href="{{ route('egresos.show', $egreso) }}">
                                        {{ Str::limit($egreso->proveedor, 25) }}
                                    </a>
                                </td>
                                <td class="text-end text-danger fw-bold">${{ number_format($egreso->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
