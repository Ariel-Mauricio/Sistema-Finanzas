<div class="card">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            <a href="{{ route('configuracion.index') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('configuracion.index') ? 'active' : '' }}">
                <i class="fas fa-cog me-2"></i>General
            </a>
            <a href="{{ route('configuracion.empresa') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('configuracion.empresa') ? 'active' : '' }}">
                <i class="fas fa-building me-2"></i>Empresa
            </a>
            <a href="{{ route('configuracion.usuarios') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('configuracion.usuarios') ? 'active' : '' }}">
                <i class="fas fa-users-cog me-2"></i>Usuarios
            </a>
            <a href="{{ route('configuracion.notificaciones') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('configuracion.notificaciones') ? 'active' : '' }}">
                <i class="fas fa-bell me-2"></i>Notificaciones
            </a>
            <a href="{{ route('configuracion.respaldos') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('configuracion.respaldos') ? 'active' : '' }}">
                <i class="fas fa-database me-2"></i>Respaldos
            </a>
            <a href="{{ route('configuracion.seguridad') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('configuracion.seguridad') ? 'active' : '' }}">
                <i class="fas fa-shield-alt me-2"></i>Seguridad
            </a>
        </div>
    </div>
</div>

<!-- Info del Sistema -->
<div class="card mt-4">
    <div class="card-header">
        <h6 class="card-title mb-0">
            <i class="fas fa-info-circle me-2"></i>Info del Sistema
        </h6>
    </div>
    <div class="card-body">
        <small class="text-muted d-block mb-2">
            <strong>Versión:</strong> 2.0.0
        </small>
        <small class="text-muted d-block mb-2">
            <strong>Laravel:</strong> {{ app()->version() }}
        </small>
        <small class="text-muted d-block mb-2">
            <strong>PHP:</strong> {{ phpversion() }}
        </small>
        <small class="text-muted d-block">
            <strong>Base de Datos:</strong> MySQL
        </small>
    </div>
</div>
