@extends('layouts.master')

@section('title', 'Editar Egreso')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit text-danger me-2"></i>
        Editar Egreso #{{ $egreso->numero_documento }}
    </h1>
    <p class="page-subtitle">Modifique los datos del gasto</p>
</div>

<form action="{{ route('egresos.update', $egreso) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <div class="col-lg-8">
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
                            <label class="form-label">Nombre del Proveedor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('proveedor') is-invalid @enderror" 
                                   name="proveedor" value="{{ old('proveedor', $egreso->proveedor) }}" required>
                            @error('proveedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RUC del Proveedor</label>
                            <input type="text" class="form-control" name="ruc_proveedor" 
                                   value="{{ old('ruc_proveedor', $egreso->ruc_proveedor) }}" maxlength="20">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle del Egreso -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt text-danger me-2"></i>Detalle del Egreso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                            <select class="form-select @error('tipo') is-invalid @enderror" name="tipo" required>
                                @foreach($tipos as $key => $value)
                                    <option value="{{ $key }}" {{ old('tipo', $egreso->tipo) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría <span class="text-danger">*</span></label>
                            <select class="form-select @error('categoria') is-invalid @enderror" name="categoria" required>
                                @foreach($categorias as $key => $value)
                                    <option value="{{ $key }}" {{ old('categoria', $egreso->categoria) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                   name="fecha" value="{{ old('fecha', $egreso->fecha->format('Y-m-d')) }}" required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      name="descripcion" rows="3" required>{{ old('descripcion', $egreso->descripcion) }}</textarea>
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
                        <i class="fas fa-dollar-sign text-danger me-2"></i>Valores
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Subtotal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       name="subtotal" id="subtotal" value="{{ old('subtotal', $egreso->subtotal) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IVA (15%)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       name="iva" id="iva" value="{{ old('iva', $egreso->iva) }}">
                                <button type="button" class="btn btn-outline-secondary" onclick="calcularIVA()">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control fw-bold text-danger" id="total" readonly 
                                       value="{{ number_format($egreso->total, 2) }}">
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
                        <i class="fas fa-credit-card text-danger me-2"></i>Método de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Forma de Pago <span class="text-danger">*</span></label>
                        <select class="form-select" name="metodo_pago" required>
                            @foreach($metodosPago as $key => $value)
                                <option value="{{ $key }}" {{ old('metodo_pago', $egreso->metodo_pago) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nº Referencia / Transferencia</label>
                        <input type="text" class="form-control" name="referencia_pago" 
                               value="{{ old('referencia_pago', $egreso->referencia_pago) }}">
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-comment-alt text-danger me-2"></i>Observaciones
                    </h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" name="observaciones" rows="4">{{ old('observaciones', $egreso->observaciones) }}</textarea>
                </div>
            </div>

            <!-- Acciones -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-danger btn-lg">
                    <i class="fas fa-save me-2"></i>Actualizar Egreso
                </button>
                <a href="{{ route('egresos.index') }}" class="btn btn-outline-secondary">
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
