@extends('layouts.master')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-cog text-primary me-2"></i>
        Configuración del Sistema
    </h1>
    <p class="page-subtitle">Administra la configuración general del sistema</p>
</div>

<div class="row g-4">
    <!-- Sidebar de navegación -->
    <div class="col-lg-3">
        @include('configuracion.partials.sidebar')
    </div>

    <!-- Contenido Principal -->
    <div class="col-lg-9">
        <!-- Estadísticas Rápidas -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-file-invoice-dollar fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ number_format($estadisticas['total_comprobantes'] ?? 0) }}</h3>
                        <small>Comprobantes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-receipt fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ number_format($estadisticas['total_egresos'] ?? 0) }}</h3>
                        <small>Egresos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $estadisticas['total_usuarios'] ?? 0 }}</h3>
                        <small>Usuarios</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-user-check fa-2x mb-2"></i>
                        <h3 class="mb-0">{{ $estadisticas['usuarios_activos'] ?? 0 }}</h3>
                        <small>Activos</small>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($configuraciones) && $configuraciones->count() > 0)
        <form method="POST" action="{{ route('configuracion.guardar') }}">
            @csrf
            
            @foreach($configuraciones as $grupo => $configs)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        @switch($grupo)
                            @case('empresa')
                                <i class="fas fa-building text-primary me-2"></i>Información de la Empresa
                                @break
                            @case('finanzas')
                                <i class="fas fa-dollar-sign text-success me-2"></i>Configuración Financiera
                                @break
                            @case('comprobantes')
                                <i class="fas fa-file-invoice text-info me-2"></i>Comprobantes y Secuencias
                                @break
                            @case('sistema')
                                <i class="fas fa-cog text-secondary me-2"></i>Sistema
                                @break
                            @default
                                <i class="fas fa-sliders-h text-primary me-2"></i>{{ ucfirst($grupo) }}
                        @endswitch
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($configs as $config)
                        <div class="col-md-6">
                            <label class="form-label">{{ $config->descripcion ?: ucwords(str_replace('_', ' ', $config->clave)) }}</label>
                            @if($config->tipo === 'boolean')
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           name="configuraciones[{{ $config->clave }}]" 
                                           value="true"
                                           {{ $config->valor === 'true' || $config->valor === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label">Activado</label>
                                </div>
                            @else
                                <input type="{{ $config->tipo === 'integer' || $config->tipo === 'decimal' ? 'number' : 'text' }}" 
                                       class="form-control" 
                                       name="configuraciones[{{ $config->clave }}]" 
                                       value="{{ $config->valor }}"
                                       {{ $config->tipo === 'decimal' ? 'step=0.01' : '' }}>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-2"></i>Restablecer
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </form>
        @else
        <!-- Accesos Rápidos cuando no hay configuraciones -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-rocket text-primary me-2"></i>
                    Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('configuracion.empresa') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-building fa-2x d-block mb-2"></i>
                            Configurar Empresa
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('configuracion.usuarios') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-users fa-2x d-block mb-2"></i>
                            Gestionar Usuarios
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('configuracion.respaldos') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-database fa-2x d-block mb-2"></i>
                            Crear Respaldo
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Nota:</strong> Las configuraciones avanzadas se pueden agregar desde la base de datos o ejecutando los seeders de configuración.
        </div>
        @endif
    </div>
</div>
@endsection
