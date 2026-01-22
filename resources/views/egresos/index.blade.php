@extends('layouts.master')

@section('title', 'Gestión de Egresos')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">
            <i class="fas fa-arrow-down text-danger me-2"></i>
            Gestión de Egresos
        </h1>
        <p class="page-subtitle">Administra los gastos y salidas de dinero</p>
    </div>
    <a href="{{ route('egresos.create') }}" class="btn btn-danger btn-lg">
        <i class="fas fa-plus me-2"></i>Nuevo Egreso
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Egresos del Mes</h6>
                        <h3 class="card-title mb-0">${{ number_format($egresosMes, 2) }}</h3>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Egresos Hoy</h6>
                        <h3 class="card-title mb-0">${{ number_format($egresosHoy, 2) }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Total General</h6>
                        <h3 class="card-title mb-0">${{ number_format($totalEgresos, 2) }}</h3>
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
        <form action="{{ route('egresos.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="desde" value="{{ request('desde') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="categoria">
                    <option value="">Todas</option>
                    @foreach(\App\Models\Egreso::$categorias as $key => $value)
                        <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Buscar</label>
                <input type="text" class="form-control" name="buscar" placeholder="Proveedor..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-2"></i>Filtrar
                </button>
                <a href="{{ route('egresos.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Egresos -->
<div class="card">
    <div class="card-body p-0">
        @if($egresos->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nº Documento</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th class="text-end">Total</th>
                        <th class="text-center" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($egresos as $egreso)
                    <tr>
                        <td>
                            <span class="badge bg-danger">#{{ $egreso->numero_documento }}</span>
                        </td>
                        <td>{{ $egreso->fecha->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $egreso->proveedor }}</strong>
                            @if($egreso->ruc_proveedor)
                            <br><small class="text-muted">RUC: {{ $egreso->ruc_proveedor }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $egreso->categoria_nombre }}</span>
                        </td>
                        <td>{{ Str::limit($egreso->descripcion, 40) }}</td>
                        <td class="text-end fw-bold text-danger">$ {{ number_format($egreso->total, 2) }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('egresos.show', $egreso) }}" class="btn btn-outline-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('egresos.edit', $egreso) }}" class="btn btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('egresos.pdf', $egreso) }}" class="btn btn-outline-danger" title="PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('egresos.destroy', $egreso) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('¿Está seguro de eliminar este egreso?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No hay egresos registrados</h5>
            <p class="text-muted">Comienza creando tu primer egreso</p>
            <a href="{{ route('egresos.create') }}" class="btn btn-danger">
                <i class="fas fa-plus me-2"></i>Nuevo Egreso
            </a>
        </div>
        @endif
    </div>
    @if($egresos->hasPages())
    <div class="card-footer">
        {{ $egresos->links() }}
    </div>
    @endif
</div>
@endsection
