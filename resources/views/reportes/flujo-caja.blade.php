@extends('layouts.master')

@section('title', 'Flujo de Caja')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-chart-line text-primary me-2"></i>
            Flujo de Caja
        </h1>
        <p class="page-subtitle">Análisis del movimiento de efectivo</p>
    </div>
    <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('reportes.flujo-caja') }}" method="GET" class="row g-3 align-items-end">
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

@php
    $totalEntradas = collect($movimientos)->sum('ingresos');
    $totalSalidas = collect($movimientos)->sum('egresos');
    $flujoNeto = $totalEntradas - $totalSalidas;
@endphp

<!-- Resumen -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Entradas</h6>
                        <h2 class="mb-0">${{ number_format($totalEntradas, 2) }}</h2>
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
                        <h6 class="text-white-50 mb-2">Salidas</h6>
                        <h2 class="mb-0">${{ number_format($totalSalidas, 2) }}</h2>
                    </div>
                    <i class="fas fa-arrow-down fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card {{ $flujoNeto >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">Flujo Neto</h6>
                        <h2 class="mb-0">${{ number_format($flujoNeto, 2) }}</h2>
                    </div>
                    <i class="fas fa-balance-scale fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Movimientos -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list text-primary me-2"></i>
            Movimientos Diarios
        </h5>
    </div>
    <div class="card-body p-0">
        @if(count($movimientos) > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th class="text-end">Entradas</th>
                        <th class="text-end">Salidas</th>
                        <th class="text-end">Flujo</th>
                        <th class="text-end">Saldo Acumulado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimientos as $mov)
                    <tr>
                        <td>{{ $mov['fecha_formato'] }}</td>
                        <td class="text-end text-success">
                            @if($mov['ingresos'] > 0)
                                +${{ number_format($mov['ingresos'], 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end text-danger">
                            @if($mov['egresos'] > 0)
                                -${{ number_format($mov['egresos'], 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end fw-bold {{ $mov['flujo'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $mov['flujo'] >= 0 ? '+' : '' }}${{ number_format($mov['flujo'], 2) }}
                        </td>
                        <td class="text-end fw-bold {{ $mov['saldo_acumulado'] >= 0 ? 'text-primary' : 'text-warning' }}">
                            ${{ number_format($mov['saldo_acumulado'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td>TOTALES</td>
                        <td class="text-end text-success">+${{ number_format($totalEntradas, 2) }}</td>
                        <td class="text-end text-danger">-${{ number_format($totalSalidas, 2) }}</td>
                        <td class="text-end {{ $flujoNeto >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $flujoNeto >= 0 ? '+' : '' }}${{ number_format($flujoNeto, 2) }}
                        </td>
                        <td class="text-end">-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No hay movimientos en este período</h5>
            <p class="text-muted">Seleccione otro rango de fechas</p>
        </div>
        @endif
    </div>
</div>
@endsection
