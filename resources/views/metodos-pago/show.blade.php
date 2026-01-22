@extends('layouts.master')

@section('title', $nombreMetodo)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-wallet text-primary me-2"></i>
            {{ $nombreMetodo }}
        </h1>
        <p class="page-subtitle">Detalle de transacciones por método de pago</p>
    </div>
    <a href="{{ route('metodos-pago.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-4">
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
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Ingresos</h6>
                        <h3 class="mb-0">${{ number_format($totalIngresos, 2) }}</h3>
                    </div>
                    <i class="fas fa-arrow-up fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Egresos</h6>
                        <h3 class="mb-0">${{ number_format($totalEgresos, 2) }}</h3>
                    </div>
                    <i class="fas fa-arrow-down fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card {{ ($totalIngresos - $totalEgresos) >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Balance</h6>
                        <h3 class="mb-0">${{ number_format($totalIngresos - $totalEgresos, 2) }}</h3>
                    </div>
                    <i class="fas fa-balance-scale fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Ingresos -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-up me-2"></i>
                    Ingresos ({{ $ingresos->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                @if($ingresos->isEmpty())
                    <p class="text-muted text-center py-4">No hay ingresos</p>
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
                            @foreach($ingresos as $ingreso)
                            <tr>
                                <td>{{ $ingreso->fecha->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('comprobantes.show', $ingreso) }}">
                                        {{ Str::limit($ingreso->cliente, 30) }}
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

    <!-- Egresos -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-down me-2"></i>
                    Egresos ({{ $egresos->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                @if($egresos->isEmpty())
                    <p class="text-muted text-center py-4">No hay egresos</p>
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
                            @foreach($egresos as $egreso)
                            <tr>
                                <td>{{ $egreso->fecha->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('egresos.show', $egreso) }}">
                                        {{ Str::limit($egreso->proveedor, 30) }}
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
