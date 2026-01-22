@extends('layouts.master')

@section('title', 'Comprobantes de Ingreso')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-file-invoice-dollar text-success me-2"></i>
            Comprobantes de Ingreso
        </h1>
        <p class="page-subtitle">Gestión de ingresos y cobros</p>
    </div>
    <a href="{{ route('comprobantes.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Nuevo Comprobante
    </a>
</div>

<!-- Estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Ingresos del Mes</h6>
                        <h3 class="card-title mb-0">${{ number_format($ingresosMes, 2) }}</h3>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Ingresos Hoy</h6>
                        <h3 class="card-title mb-0">${{ number_format($ingresosHoy, 2) }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Total General</h6>
                        <h3 class="card-title mb-0">${{ number_format($totalIngresos, 2) }}</h3>
                    </div>
                    <i class="fas fa-coins fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Buscar</label>
                <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}" 
                       placeholder="Cliente, número, descripción...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipo</label>
                <select class="form-select" name="tipo">
                    <option value="">Todos</option>
                    @foreach(\App\Models\Comprobante::$tipos as $key => $value)
                        <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="fecha_fin" value="{{ request('fecha_fin') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('comprobantes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nº Comprobante</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Método Pago</th>
                        <th class="text-end">Total</th>
                        <th class="text-center" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comprobantes as $comprobante)
                    <tr>
                        <td>
                            <a href="{{ route('comprobantes.show', $comprobante) }}" class="fw-semibold text-primary">
                                {{ $comprobante->numero_comprobante }}
                            </a>
                        </td>
                        <td>{{ $comprobante->fecha->format('d/m/Y') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $comprobante->cliente }}</div>
                            @if($comprobante->cedula_ruc)
                                <small class="text-muted">{{ $comprobante->cedula_ruc }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success">{{ $comprobante->tipo_nombre }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $comprobante->metodo_pago_nombre }}</span>
                        </td>
                        <td class="text-end fw-bold text-success">${{ number_format($comprobante->total, 2) }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('comprobantes.show', $comprobante) }}" class="btn btn-outline-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('comprobantes.edit', $comprobante) }}" class="btn btn-outline-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('comprobantes.pdf', $comprobante) }}" class="btn btn-outline-danger" title="PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('comprobantes.destroy', $comprobante) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este comprobante?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">No hay comprobantes registrados</p>
                            <a href="{{ route('comprobantes.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Crear Primer Comprobante
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($comprobantes->hasPages())
    <div class="card-footer">
        {{ $comprobantes->links() }}
    </div>
    @endif
</div>
@endsection
