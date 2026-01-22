@extends('layouts.master')

@section('title', 'Historial de Transacciones')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-history text-primary me-2"></i>
            Historial de Transacciones
        </h1>
        <p class="page-subtitle">Registro completo de movimientos financieros</p>
    </div>
    <a href="{{ route('metodos-pago.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('metodos-pago.historial') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo</label>
                <select class="form-select" name="tipo">
                    <option value="todos" {{ $tipo == 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="ingresos" {{ $tipo == 'ingresos' ? 'selected' : '' }}>Solo Ingresos</option>
                    <option value="egresos" {{ $tipo == 'egresos' ? 'selected' : '' }}>Solo Egresos</option>
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
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Ingresos</h6>
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
                        <h6 class="text-white-50 mb-1">Total Egresos</h6>
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

<!-- Tabla de Transacciones -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list text-primary me-2"></i>
            Transacciones ({{ $transacciones->count() }})
        </h5>
    </div>
    <div class="card-body p-0">
        @if($transacciones->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No hay transacciones en el período seleccionado</h5>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Número</th>
                        <th>Concepto</th>
                        <th>Método Pago</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transacciones as $trans)
                    <tr>
                        <td>{{ $trans['fecha']->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $trans['clase'] }}">{{ $trans['tipo'] }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $trans['clase'] }}-subtle text-{{ $trans['clase'] }}">
                                #{{ $trans['numero'] }}
                            </span>
                        </td>
                        <td>{{ Str::limit($trans['concepto'], 50) }}</td>
                        <td>{{ $trans['metodo_pago'] }}</td>
                        <td class="text-end fw-bold text-{{ $trans['clase'] }}">
                            {{ $trans['tipo'] == 'Egreso' ? '-' : '' }}${{ number_format($trans['valor'], 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
