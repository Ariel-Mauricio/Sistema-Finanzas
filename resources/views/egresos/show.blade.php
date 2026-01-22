@extends('layouts.master')

@section('title', 'Egreso #' . $egreso->numero_documento)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-file-invoice-dollar text-danger me-2"></i>
            Egreso #{{ $egreso->numero_documento }}
        </h1>
        <p class="page-subtitle">Detalle completo del gasto</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('egresos.pdf', $egreso) }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Descargar PDF
        </a>
        <a href="{{ route('egresos.edit', $egreso) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        <a href="{{ route('egresos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Información del Egreso -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle text-danger me-2"></i>Información del Egreso
                </h5>
                <span class="badge bg-{{ $egreso->tipo == 'factura' ? 'primary' : ($egreso->tipo == 'recibo' ? 'success' : 'info') }} fs-6">
                    {{ $egreso->tipo_nombre }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Número de Documento</label>
                        <p class="fw-bold mb-0">#{{ $egreso->numero_documento }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Fecha</label>
                        <p class="fw-bold mb-0">{{ $egreso->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Categoría</label>
                        <p class="mb-0"><span class="badge bg-secondary">{{ $egreso->categoria_nombre }}</span></p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Descripción</label>
                        <p class="mb-0">{{ $egreso->descripcion }}</p>
                    </div>
                    @if($egreso->observaciones)
                    <div class="col-12">
                        <label class="text-muted small">Observaciones</label>
                        <p class="mb-0">{{ $egreso->observaciones }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Datos del Proveedor -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building text-danger me-2"></i>Datos del Proveedor
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Proveedor</label>
                        <p class="fw-bold mb-0">{{ $egreso->proveedor }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">RUC</label>
                        <p class="mb-0">{{ $egreso->ruc_proveedor ?: 'No registrado' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Resumen de Valores -->
        <div class="card mb-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>Resumen de Valores
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span>$ {{ number_format($egreso->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">IVA (15%):</span>
                    <span>$ {{ number_format($egreso->iva, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold fs-5">TOTAL:</span>
                    <span class="fw-bold fs-5 text-danger">$ {{ number_format($egreso->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Método de Pago -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card text-danger me-2"></i>Método de Pago
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Forma de Pago</label>
                    <p class="fw-bold mb-0">
                        <i class="fas fa-{{ $egreso->metodo_pago == 'efectivo' ? 'money-bill' : ($egreso->metodo_pago == 'tarjeta' ? 'credit-card' : 'university') }} me-2"></i>
                        {{ $egreso->metodo_pago_nombre }}
                    </p>
                </div>
                @if($egreso->referencia_pago)
                <div>
                    <label class="text-muted small">Nº Referencia</label>
                    <p class="mb-0">{{ $egreso->referencia_pago }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog text-danger me-2"></i>Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <label class="text-muted small">Registrado por</label>
                    <p class="mb-0">{{ $egreso->user->name ?? 'Sistema' }}</p>
                </div>
                <div class="mb-2">
                    <label class="text-muted small">Fecha de Creación</label>
                    <p class="mb-0">{{ $egreso->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($egreso->updated_at != $egreso->created_at)
                <div>
                    <label class="text-muted small">Última Modificación</label>
                    <p class="mb-0">{{ $egreso->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
