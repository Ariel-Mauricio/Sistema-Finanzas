@extends('layouts.master')

@section('title', 'Comprobante #' . $comprobante->numero_comprobante)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-file-invoice text-primary me-2"></i>
            Comprobante #{{ $comprobante->numero_comprobante }}
        </h1>
        <p class="page-subtitle">Detalle completo del ingreso</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('comprobantes.pdf', $comprobante) }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Descargar PDF
        </a>
        <a href="{{ route('comprobantes.edit', $comprobante) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        <a href="{{ route('comprobantes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Información del Comprobante -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>Información del Comprobante
                </h5>
                <span class="badge bg-{{ $comprobante->tipo == 'factura' ? 'primary' : ($comprobante->tipo == 'recibo' ? 'success' : 'info') }} fs-6">
                    {{ $comprobante->tipo_nombre }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Número de Comprobante</label>
                        <p class="fw-bold mb-0">#{{ $comprobante->numero_comprobante }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Fecha</label>
                        <p class="fw-bold mb-0">{{ $comprobante->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Descripción</label>
                        <p class="mb-0">{{ $comprobante->descripcion }}</p>
                    </div>
                    @if($comprobante->observaciones)
                    <div class="col-12">
                        <label class="text-muted small">Observaciones</label>
                        <p class="mb-0">{{ $comprobante->observaciones }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Datos del Cliente -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user text-primary me-2"></i>Datos del Cliente
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Nombre</label>
                        <p class="fw-bold mb-0">{{ $comprobante->cliente }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Cédula/RUC</label>
                        <p class="mb-0">{{ $comprobante->cedula_ruc ?: 'No registrado' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Teléfono</label>
                        <p class="mb-0">{{ $comprobante->telefono ?: 'No registrado' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">{{ $comprobante->email ?: 'No registrado' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Resumen de Valores -->
        <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>Resumen de Valores
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span>$ {{ number_format($comprobante->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">IVA (15%):</span>
                    <span>$ {{ number_format($comprobante->iva, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold fs-5">TOTAL:</span>
                    <span class="fw-bold fs-5 text-success">$ {{ number_format($comprobante->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Método de Pago -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card text-primary me-2"></i>Método de Pago
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Forma de Pago</label>
                    <p class="fw-bold mb-0">
                        <i class="fas fa-{{ $comprobante->metodo_pago == 'efectivo' ? 'money-bill' : ($comprobante->metodo_pago == 'tarjeta' ? 'credit-card' : 'university') }} me-2"></i>
                        {{ $comprobante->metodo_pago_nombre }}
                    </p>
                </div>
                @if($comprobante->referencia_pago)
                <div>
                    <label class="text-muted small">Nº Referencia</label>
                    <p class="mb-0">{{ $comprobante->referencia_pago }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog text-primary me-2"></i>Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="text-muted small">Registrado por</label>
                    <p class="mb-0">{{ $comprobante->user->name ?? 'Sistema' }}</p>
                </div>
                <div class="mb-2">
                    <label class="text-muted small">Fecha de Creación</label>
                    <p class="mb-0">{{ $comprobante->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($comprobante->updated_at != $comprobante->created_at)
                <div>
                    <label class="text-muted small">Última Modificación</label>
                    <p class="mb-0">{{ $comprobante->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
