@extends('layouts.master')

@section('title', 'Respaldos')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-database text-success me-2"></i>
        Gestión de Respaldos
    </h1>
    <p class="page-subtitle">Crea y administra copias de seguridad de la base de datos</p>
</div>

<div class="row g-4">
    <!-- Sidebar de navegación -->
    <div class="col-lg-3">
        @include('configuracion.partials.sidebar')
    </div>

    <!-- Contenido Principal -->
    <div class="col-lg-9">
        <!-- Crear Backup -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle text-primary me-2"></i>
                    Crear Nuevo Respaldo
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Genera una copia de seguridad completa de la base de datos. 
                    El archivo se guardará en el servidor y podrás descargarlo.
                </p>
                <form method="POST" action="{{ route('configuracion.respaldos.crear') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="this.disabled=true; this.innerHTML='<i class=\'fas fa-spinner fa-spin me-2\'></i>Creando...'; this.form.submit();">
                        <i class="fas fa-download me-2"></i>Crear Backup Ahora
                    </button>
                </form>
            </div>
        </div>

        <!-- Lista de Backups -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list text-info me-2"></i>
                    Respaldos Disponibles
                </h5>
            </div>
            <div class="card-body p-0">
                @if(count($backups) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Archivo</th>
                                <th>Tamaño</th>
                                <th>Fecha de Creación</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                            <tr>
                                <td>
                                    <i class="fas fa-file-archive text-warning me-2"></i>
                                    {{ $backup['nombre'] }}
                                </td>
                                <td>{{ $backup['tamanio'] }}</td>
                                <td>{{ $backup['fecha'] }}</td>
                                <td class="text-end">
                                    <a href="{{ route('configuracion.respaldos.descargar', $backup['nombre']) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form method="POST" action="{{ route('configuracion.respaldos.eliminar', $backup['nombre']) }}" 
                                          class="d-inline" onsubmit="return confirm('¿Eliminar este backup?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-database fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No hay respaldos disponibles</p>
                    <small class="text-muted">Crea tu primer backup usando el botón de arriba</small>
                </div>
                @endif
            </div>
        </div>

        <!-- Restaurar Backup -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-upload text-warning me-2"></i>
                    Restaurar desde Archivo
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>¡Precaución!</strong> Restaurar un backup reemplazará todos los datos actuales. 
                    Esta acción no se puede deshacer.
                </div>
                <form method="POST" action="{{ route('configuracion.respaldos.restaurar') }}" enctype="multipart/form-data"
                      onsubmit="return confirm('¿Estás seguro? Se perderán todos los datos actuales.');">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label">Archivo SQL de Backup</label>
                            <input type="file" class="form-control" name="archivo" accept=".sql,.txt" required>
                            <small class="text-muted">Máximo 50MB. Formatos: .sql, .txt</small>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-undo me-2"></i>Restaurar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
