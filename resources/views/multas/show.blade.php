@extends('layouts.master')

@section('title', 'Multa #' . $multa->numero_documento)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-gavel text-warning me-2"></i>
            Multa #{{ $multa->numero_documento }}
        </h1>
        <p class="page-subtitle">Detalle completo de la multa</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('multas.pdf', $multa) }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Descargar PDF
        </a>
        <a href="{{ route('multas.edit', $multa) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        <a href="{{ route('multas.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Información de la Multa -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle text-warning me-2"></i>Información de la Multa
                </h5>
                @if($multa->estado == 'pendiente')
                    <span class="badge bg-warning text-dark fs-6">Pendiente</span>
                @elseif($multa->estado == 'pagada')
                    <span class="badge bg-success fs-6">Pagada</span>
                @else
                    <span class="badge bg-secondary fs-6">Anulada</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Número de Documento</label>
                        <p class="fw-bold mb-0">#{{ $multa->numero_documento }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Fecha</label>
                        <p class="fw-bold mb-0">{{ $multa->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Motivo</label>
                        <p class="mb-0">{{ $multa->motivo }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos de la Persona -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user text-warning me-2"></i>Datos de la Persona
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Persona Multada</label>
                        <p class="fw-bold mb-0">{{ $multa->persona }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Aplicado por</label>
                        <p class="mb-0">{{ $multa->aplicado_por ?: 'No registrado' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Valor de la Multa -->
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>Valor de la Multa
                </h5>
            </div>
            <div class="card-body text-center">
                <h2 class="mb-0 text-danger">$ {{ number_format($multa->valor, 2) }}</h2>
                <p class="text-muted mt-2 mb-0">
                    @if($multa->estado == 'pendiente')
                        Pendiente de pago
                    @elseif($multa->estado == 'pagada')
                        Multa cancelada
                    @else
                        Multa anulada
                    @endif
                </p>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        @if($multa->estado == 'pendiente')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt text-warning me-2"></i>Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('multas.update', $multa) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="persona" value="{{ $multa->persona }}">
                    <input type="hidden" name="aplicado_por" value="{{ $multa->aplicado_por }}">
                    <input type="hidden" name="motivo" value="{{ $multa->motivo }}">
                    <input type="hidden" name="valor" value="{{ $multa->valor }}">
                    <input type="hidden" name="fecha" value="{{ $multa->fecha->format('Y-m-d') }}">
                    <input type="hidden" name="estado" value="pagada">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check me-2"></i>Marcar como Pagada
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Información del Sistema -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog text-warning me-2"></i>Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="text-muted small">Registrado por</label>
                    <p class="mb-0">{{ $multa->user->name ?? 'Sistema' }}</p>
                </div>
                <div class="mb-2">
                    <label class="text-muted small">Fecha de Creación</label>
                    <p class="mb-0">{{ $multa->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($multa->updated_at != $multa->created_at)
                <div>
                    <label class="text-muted small">Última Modificación</label>
                    <p class="mb-0">{{ $multa->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
