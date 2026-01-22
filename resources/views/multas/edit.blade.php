@extends('layouts.master')

@section('title', 'Editar Multa')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit text-warning me-2"></i>
        Editar Multa #{{ $multa->numero_documento }}
    </h1>
    <p class="page-subtitle">Modifique los datos de la multa</p>
</div>

<form action="{{ route('multas.update', $multa) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <div class="col-lg-8">
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
                            <label class="form-label">Nombre de la Persona <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('persona') is-invalid @enderror" 
                                   name="persona" value="{{ old('persona', $multa->persona) }}" required>
                            @error('persona')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Aplicado por</label>
                            <input type="text" class="form-control" name="aplicado_por" 
                                   value="{{ old('aplicado_por', $multa->aplicado_por) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle de la Multa -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-gavel text-warning me-2"></i>Detalle de la Multa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                   name="fecha" value="{{ old('fecha', $multa->fecha->format('Y-m-d')) }}" required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor de la Multa <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control @error('valor') is-invalid @enderror" 
                                       name="valor" value="{{ old('valor', $multa->valor) }}" required>
                            </div>
                            @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Motivo de la Multa <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('motivo') is-invalid @enderror" 
                                      name="motivo" rows="4" required>{{ old('motivo', $multa->motivo) }}</textarea>
                            @error('motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Estado -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle text-warning me-2"></i>Estado de la Multa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-select" name="estado" required>
                            @foreach($estados as $key => $value)
                                <option value="{{ $key }}" {{ old('estado', $multa->estado) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    @if($multa->estado == 'pendiente')
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta multa está pendiente de pago
                    </div>
                    @elseif($multa->estado == 'pagada')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Esta multa ya fue pagada
                    </div>
                    @else
                    <div class="alert alert-secondary">
                        <i class="fas fa-ban me-2"></i>
                        Esta multa fue anulada
                    </div>
                    @endif
                </div>
            </div>

            <!-- Acciones -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning btn-lg">
                    <i class="fas fa-save me-2"></i>Actualizar Multa
                </button>
                <a href="{{ route('multas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
