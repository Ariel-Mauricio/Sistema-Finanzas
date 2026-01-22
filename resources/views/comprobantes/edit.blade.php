@extends('layouts.master')

@section('title', 'Editar Comprobante')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit text-primary me-2"></i>
        Editar Comprobante #{{ $comprobante->numero_comprobante }}
    </h1>
    <p class="page-subtitle">Modifique los datos del ingreso</p>
</div>

<form action="{{ route('comprobantes.update', $comprobante) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <div class="col-lg-8">
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
                            <label class="form-label">Nombre del Cliente <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('cliente') is-invalid @enderror" 
                                   name="cliente" value="{{ old('cliente', $comprobante->cliente) }}" required>
                            @error('cliente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cédula/RUC</label>
                            <input type="text" class="form-control" name="cedula_ruc" 
                                   value="{{ old('cedula_ruc', $comprobante->cedula_ruc) }}" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" 
                                   value="{{ old('telefono', $comprobante->telefono) }}" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" 
                                   value="{{ old('email', $comprobante->email) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle del Ingreso -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt text-primary me-2"></i>Detalle del Ingreso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Comprobante <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipo') is-invalid @enderror" name="tipo" required>
                                @foreach($tipos as $key => $value)
                                    <option value="{{ $key }}" {{ old('tipo', $comprobante->tipo) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                   name="fecha" value="{{ old('fecha', $comprobante->fecha->format('Y-m-d')) }}" required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      name="descripcion" rows="3" required>{{ old('descripcion', $comprobante->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valores -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-dollar-sign text-primary me-2"></i>Valores
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Subtotal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       name="subtotal" id="subtotal" value="{{ old('subtotal', $comprobante->subtotal) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IVA (15%)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       name="iva" id="iva" value="{{ old('iva', $comprobante->iva) }}">
                                <button type="button" class="btn btn-outline-secondary" onclick="calcularIVA()">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control fw-bold text-success" id="total" readonly 
                                       value="{{ number_format($comprobante->total, 2) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Método de Pago -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card text-primary me-2"></i>Método de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Forma de Pago <span class="text-danger">*</span></label>
                        <select class="form-select" name="metodo_pago" required>
                            @foreach($metodosPago as $key => $value)
                                <option value="{{ $key }}" {{ old('metodo_pago', $comprobante->metodo_pago) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nº Referencia / Transferencia</label>
                        <input type="text" class="form-control" name="referencia_pago" 
                               value="{{ old('referencia_pago', $comprobante->referencia_pago) }}">
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-comment-alt text-primary me-2"></i>Observaciones
                    </h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" name="observaciones" rows="4">{{ old('observaciones', $comprobante->observaciones) }}</textarea>
                </div>
            </div>

            <!-- Acciones -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Actualizar Comprobante
                </button>
                <a href="{{ route('comprobantes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
function calcularIVA() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const iva = subtotal * 0.15;
    document.getElementById('iva').value = iva.toFixed(2);
    calcularTotal();
}

function calcularTotal() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const iva = parseFloat(document.getElementById('iva').value) || 0;
    const total = subtotal + iva;
    document.getElementById('total').value = total.toFixed(2);
}

document.getElementById('subtotal').addEventListener('input', calcularTotal);
document.getElementById('iva').addEventListener('input', calcularTotal);
</script>
@endsection
