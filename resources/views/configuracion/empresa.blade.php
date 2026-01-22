@extends('layouts.master')

@section('title', 'Datos de la Empresa')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-building text-primary me-2"></i>
        Datos de la Empresa
    </h1>
    <p class="page-subtitle">Configura la información de tu empresa</p>
</div>

<div class="row g-4">
    <!-- Sidebar de navegación -->
    <div class="col-lg-3">
        @include('configuracion.partials.sidebar')
    </div>

    <div class="col-lg-9">
        <div class="row">
            <!-- Formulario Principal -->
            <div class="col-lg-8">
                <form action="{{ route('configuracion.empresa.guardar') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Información General -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-building text-primary me-2"></i>
                                Información General
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre de la Empresa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('empresa_nombre') is-invalid @enderror" 
                                           name="empresa_nombre" 
                                           value="{{ old('empresa_nombre', $config['empresa_nombre'] ?? '') }}" 
                                           placeholder="Nombre comercial" required>
                                    @error('empresa_nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Razón Social <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('empresa_razon_social') is-invalid @enderror" 
                                           name="empresa_razon_social" 
                                           value="{{ old('empresa_razon_social', $config['empresa_razon_social'] ?? '') }}" 
                                           placeholder="Razón social legal" required>
                                    @error('empresa_razon_social')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">RUC / Identificación Fiscal <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('empresa_ruc') is-invalid @enderror" 
                                           name="empresa_ruc" 
                                           value="{{ old('empresa_ruc', $config['empresa_ruc'] ?? '') }}" 
                                           placeholder="1234567890001" required>
                                    @error('empresa_ruc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipo de Contribuyente</label>
                                    <select class="form-select" name="empresa_tipo_contribuyente">
                                        <option value="">Seleccione...</option>
                                        <option value="persona_natural" {{ ($config['empresa_tipo_contribuyente'] ?? '') === 'persona_natural' ? 'selected' : '' }}>Persona Natural</option>
                                        <option value="persona_juridica" {{ ($config['empresa_tipo_contribuyente'] ?? '') === 'persona_juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                                        <option value="rise" {{ ($config['empresa_tipo_contribuyente'] ?? '') === 'rise' ? 'selected' : '' }}>RISE</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Actividad Económica</label>
                                    <input type="text" class="form-control" 
                                           name="empresa_actividad" 
                                           value="{{ old('empresa_actividad', $config['empresa_actividad'] ?? '') }}" 
                                           placeholder="Descripción de la actividad">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fecha de Constitución</label>
                                    <input type="date" class="form-control" 
                                           name="empresa_fecha_constitucion" 
                                           value="{{ old('empresa_fecha_constitucion', $config['empresa_fecha_constitucion'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-address-card text-primary me-2"></i>
                                Información de Contacto
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Dirección</label>
                                    <textarea class="form-control" name="empresa_direccion" rows="2" 
                                              placeholder="Dirección completa">{{ old('empresa_direccion', $config['empresa_direccion'] ?? '') }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" name="empresa_ciudad" 
                                           value="{{ old('empresa_ciudad', $config['empresa_ciudad'] ?? '') }}" 
                                           placeholder="Ciudad">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Provincia / Estado</label>
                                    <input type="text" class="form-control" name="empresa_provincia" 
                                           value="{{ old('empresa_provincia', $config['empresa_provincia'] ?? '') }}" 
                                           placeholder="Provincia">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">País</label>
                                    <input type="text" class="form-control" name="empresa_pais" 
                                           value="{{ old('empresa_pais', $config['empresa_pais'] ?? 'Ecuador') }}" 
                                           placeholder="País">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono Principal</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" name="empresa_telefono" 
                                               value="{{ old('empresa_telefono', $config['empresa_telefono'] ?? '') }}" 
                                               placeholder="02 123 4567">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Celular</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                        <input type="tel" class="form-control" name="empresa_celular" 
                                               value="{{ old('empresa_celular', $config['empresa_celular'] ?? '') }}" 
                                               placeholder="099 123 4567">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" name="empresa_email" 
                                               value="{{ old('empresa_email', $config['empresa_email'] ?? '') }}" 
                                               placeholder="info@empresa.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sitio Web</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="url" class="form-control" name="empresa_web" 
                                               value="{{ old('empresa_web', $config['empresa_web'] ?? '') }}" 
                                               placeholder="https://www.empresa.com">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración Fiscal -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                                Configuración Fiscal
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tasa IVA (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" name="empresa_tasa_iva" 
                                               value="{{ old('empresa_tasa_iva', $config['empresa_tasa_iva'] ?? '15') }}" 
                                               min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Obligado a llevar contabilidad</label>
                                    <select class="form-select" name="empresa_obligado_contabilidad">
                                        <option value="si" {{ ($config['empresa_obligado_contabilidad'] ?? 'si') === 'si' ? 'selected' : '' }}>Sí</option>
                                        <option value="no" {{ ($config['empresa_obligado_contabilidad'] ?? 'si') === 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Moneda Principal</label>
                                    <select class="form-select" name="empresa_moneda">
                                        <option value="USD" {{ ($config['empresa_moneda'] ?? 'USD') === 'USD' ? 'selected' : '' }}>USD - Dólar Americano</option>
                                        <option value="EUR" {{ ($config['empresa_moneda'] ?? 'USD') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        <option value="COP" {{ ($config['empresa_moneda'] ?? 'USD') === 'COP' ? 'selected' : '' }}>COP - Peso Colombiano</option>
                                        <option value="PEN" {{ ($config['empresa_moneda'] ?? 'USD') === 'PEN' ? 'selected' : '' }}>PEN - Sol Peruano</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Representante Legal</label>
                                    <input type="text" class="form-control" name="empresa_representante" 
                                           value="{{ old('empresa_representante', $config['empresa_representante'] ?? '') }}" 
                                           placeholder="Nombre del representante legal">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Pie de página en documentos</label>
                                    <textarea class="form-control" name="empresa_pie_documento" rows="2" 
                                              placeholder="Texto que aparecerá al pie de facturas y reportes">{{ old('empresa_pie_documento', $config['empresa_pie_documento'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('configuracion.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Configuración
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-eye text-primary me-2"></i>
                            Vista Previa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center border rounded p-4 mb-3" style="background: #f8f9fa;">
                            @if(isset($config['empresa_logo']) && $config['empresa_logo'])
                                <img src="{{ asset('storage/' . $config['empresa_logo']) }}" alt="Logo" class="mb-3" style="max-height: 60px;">
                            @else
                                <div class="mb-3 py-3 bg-light border rounded">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                    <div class="small text-muted mt-1">Sin logo</div>
                                </div>
                            @endif
                            <h5 class="mb-1 preview-nombre">{{ $config['empresa_nombre'] ?? 'Nombre de la Empresa' }}</h5>
                            <p class="text-muted mb-0 small preview-razon">{{ $config['empresa_razon_social'] ?? 'Razón Social' }}</p>
                            <p class="text-muted mb-0 small">RUC: <span class="preview-ruc">{{ $config['empresa_ruc'] ?? '0000000000000' }}</span></p>
                        </div>

                        <div class="small">
                            <div class="d-flex mb-2">
                                <i class="fas fa-map-marker-alt text-muted me-2 mt-1"></i>
                                <span class="preview-direccion">{{ $config['empresa_direccion'] ?? 'Dirección no configurada' }}</span>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="fas fa-phone text-muted me-2 mt-1"></i>
                                <span class="preview-telefono">{{ $config['empresa_telefono'] ?? 'Teléfono no configurado' }}</span>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="fas fa-envelope text-muted me-2 mt-1"></i>
                                <span class="preview-email">{{ $config['empresa_email'] ?? 'Email no configurado' }}</span>
                            </div>
                        </div>

                        <div class="alert alert-info small mt-3 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Esta información aparecerá en facturas, reportes y otros documentos generados.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Preview en tiempo real
document.querySelector('input[name="empresa_nombre"]')?.addEventListener('input', function() {
    document.querySelector('.preview-nombre').textContent = this.value || 'Nombre de la Empresa';
});
document.querySelector('input[name="empresa_razon_social"]')?.addEventListener('input', function() {
    document.querySelector('.preview-razon').textContent = this.value || 'Razón Social';
});
document.querySelector('input[name="empresa_ruc"]')?.addEventListener('input', function() {
    document.querySelector('.preview-ruc').textContent = this.value || '0000000000000';
});
document.querySelector('textarea[name="empresa_direccion"]')?.addEventListener('input', function() {
    document.querySelector('.preview-direccion').textContent = this.value || 'Dirección no configurada';
});
document.querySelector('input[name="empresa_telefono"]')?.addEventListener('input', function() {
    document.querySelector('.preview-telefono').textContent = this.value || 'Teléfono no configurado';
});
document.querySelector('input[name="empresa_email"]')?.addEventListener('input', function() {
    document.querySelector('.preview-email').textContent = this.value || 'Email no configurado';
});
</script>
@endsection
