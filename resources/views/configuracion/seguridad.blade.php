@extends('layouts.master')

@section('title', 'Seguridad')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-shield-alt text-danger me-2"></i>
        Configuración de Seguridad
    </h1>
    <p class="page-subtitle">Gestiona la seguridad y control de acceso del sistema</p>
</div>

<div class="row g-4">
    <!-- Sidebar de navegación -->
    <div class="col-lg-3">
        @include('configuracion.partials.sidebar')
    </div>

    <!-- Contenido Principal -->
    <div class="col-lg-9">
        <form method="POST" action="{{ route('configuracion.seguridad.guardar') }}">
            @csrf
            
            <!-- Control de Acceso -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key text-primary me-2"></i>
                        Control de Acceso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Intentos de Login Permitidos</label>
                            <input type="number" class="form-control" name="intentos_login" 
                                   value="{{ $seguridad['intentos_login'] }}" min="3" max="10" required>
                            <small class="text-muted">Número de intentos antes del bloqueo (3-10)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiempo de Bloqueo (minutos)</label>
                            <input type="number" class="form-control" name="bloqueo_minutos" 
                                   value="{{ $seguridad['bloqueo_minutos'] }}" min="5" max="60" required>
                            <small class="text-muted">Duración del bloqueo después de fallar (5-60 min)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiempo de Sesión (minutos)</label>
                            <input type="number" class="form-control" name="session_timeout" 
                                   value="{{ $seguridad['session_timeout'] }}" min="30" max="480" required>
                            <small class="text-muted">Inactividad máxima antes de cerrar sesión (30-480 min)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contraseña Expira (días)</label>
                            <input type="number" class="form-control" name="password_expira" 
                                   value="{{ $seguridad['password_expira'] }}" min="30" max="365" required>
                            <small class="text-muted">Días hasta expiración de contraseña (30-365)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opciones de Seguridad -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs text-secondary me-2"></i>
                        Opciones de Seguridad
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="forzar_https" 
                                       name="forzar_https" {{ $seguridad['forzar_https'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="forzar_https">
                                    <strong>Forzar HTTPS</strong>
                                    <small class="d-block text-muted">Redirigir todo el tráfico a HTTPS</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="log_accesos" 
                                       name="log_accesos" {{ $seguridad['log_accesos'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="log_accesos">
                                    <strong>Registrar Accesos</strong>
                                    <small class="d-block text-muted">Guardar log de todos los inicios de sesión</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimos Accesos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history text-info me-2"></i>
                        Últimos Accesos al Sistema
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($ultimosAccesos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Último Acceso</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosAccesos as $acceso)
                                <tr>
                                    <td>
                                        <i class="fas fa-user-circle text-primary me-2"></i>
                                        {{ $acceso->name }}
                                    </td>
                                    <td>{{ $acceso->email }}</td>
                                    <td>
                                        @if($acceso->last_login)
                                            {{ $acceso->last_login->format('d/m/Y H:i') }}
                                            <small class="text-muted d-block">{{ $acceso->last_login->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">Nunca</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $acceso->last_login_ip ?? 'N/A' }}</code>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-clock fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay registros de acceso</p>
                    </div>
                    @endif
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
