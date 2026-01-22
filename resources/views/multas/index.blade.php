@extends('layouts.master')

@section('title', 'Gestión de Multas')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1 class="page-title">
            <i class="fas fa-gavel text-warning me-2"></i>
            Gestión de Multas
        </h1>
        <p class="page-subtitle">Administra multas y sanciones</p>
    </div>
    <a href="{{ route('multas.create') }}" class="btn btn-warning btn-lg">
        <i class="fas fa-plus me-2"></i>Nueva Multa
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Multas Pendientes</h6>
                        <h3 class="card-title mb-0">${{ number_format($pendientes, 2) }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Multas Pagadas</h6>
                        <h3 class="card-title mb-0">${{ number_format($pagadas, 2) }}</h3>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
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
                        <h3 class="card-title mb-0">${{ number_format($totalMultas, 2) }}</h3>
                    </div>
                    <i class="fas fa-gavel fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('multas.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" class="form-control" name="desde" value="{{ request('desde') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="pagada" {{ request('estado') == 'pagada' ? 'selected' : '' }}>Pagada</option>
                    <option value="anulada" {{ request('estado') == 'anulada' ? 'selected' : '' }}>Anulada</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Buscar</label>
                <input type="text" class="form-control" name="buscar" placeholder="Persona..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Multas -->
<div class="card">
    <div class="card-body p-0">
        @if($multas->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nº Documento</th>
                        <th>Fecha</th>
                        <th>Persona</th>
                        <th>Motivo</th>
                        <th class="text-end">Valor</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($multas as $multa)
                    <tr>
                        <td>
                            <span class="badge bg-warning text-dark">#{{ $multa->numero_documento }}</span>
                        </td>
                        <td>{{ $multa->fecha->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $multa->persona }}</strong>
                            @if($multa->aplicado_por)
                            <br><small class="text-muted">Aplicado por: {{ $multa->aplicado_por }}</small>
                            @endif
                        </td>
                        <td>{{ Str::limit($multa->motivo, 40) }}</td>
                        <td class="text-end fw-bold text-danger">$ {{ number_format($multa->valor, 2) }}</td>
                        <td class="text-center">
                            @if($multa->estado == 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($multa->estado == 'pagada')
                                <span class="badge bg-success">Pagada</span>
                            @else
                                <span class="badge bg-secondary">Anulada</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('multas.show', $multa) }}" class="btn btn-outline-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('multas.edit', $multa) }}" class="btn btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('multas.pdf', $multa) }}" class="btn btn-outline-danger" title="PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <form action="{{ route('multas.destroy', $multa) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('¿Está seguro de eliminar esta multa?')">
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
            <h5 class="text-muted">No hay multas registradas</h5>
            <p class="text-muted">Comienza creando tu primera multa</p>
            <a href="{{ route('multas.create') }}" class="btn btn-warning">
                <i class="fas fa-plus me-2"></i>Nueva Multa
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
