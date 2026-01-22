@extends('layouts.master')

@section('title', 'Notificaciones')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-bell text-warning me-2"></i>
        Configuración de Notificaciones
    </h1>
    <p class="page-subtitle">Administra las alertas y notificaciones del sistema</p>
</div>

<div class="row g-4">
    <!-- Sidebar de navegación -->
    <div class="col-lg-3">
        @include('configuracion.partials.sidebar')
    </div>

    <!-- Contenido Principal -->
    <div class="col-lg-9">
        <form method="POST" action="{{ route('configuracion.notificaciones.guardar') }}">
            @csrf
            
            <!-- Notificaciones por Email -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        Notificaciones por Email
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="email_nuevo_comprobante" 
                                       name="email_nuevo_comprobante" {{ $notificaciones['email_nuevo_comprobante'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_nuevo_comprobante">
                                    <strong>Nuevo Comprobante</strong>
                                    <small class="d-block text-muted">Recibir email cuando se registre un nuevo ingreso</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="email_nuevo_egreso" 
                                       name="email_nuevo_egreso" {{ $notificaciones['email_nuevo_egreso'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_nuevo_egreso">
                                    <strong>Nuevo Egreso</strong>
                                    <small class="d-block text-muted">Recibir email cuando se registre un nuevo gasto</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="email_backup" 
                                       name="email_backup" {{ $notificaciones['email_backup'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_backup">
                                    <strong>Respaldo Completado</strong>
                                    <small class="d-block text-muted">Notificar cuando se complete un backup</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="email_login_fallido" 
                                       name="email_login_fallido" {{ $notificaciones['email_login_fallido'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_login_fallido">
                                    <strong>Intentos de Login Fallidos</strong>
                                    <small class="d-block text-muted">Alertar sobre múltiples intentos fallidos</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas del Sistema -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Alertas del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Límite de Alerta de Egresos ($)</label>
                            <input type="number" class="form-control" name="limite_alertas" 
                                   value="{{ $notificaciones['limite_alertas'] }}" min="100" step="100">
                            <small class="text-muted">Alertar cuando un egreso supere este monto</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de Notificaciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-info me-2"></i>
                        Reportes Automáticos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Próximamente:</strong> Configuración de reportes automáticos diarios, semanales y mensuales por email.
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('configuracion.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
