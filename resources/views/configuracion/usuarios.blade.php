@extends('layouts.master')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-users-cog text-primary me-2"></i>
            Gestión de Usuarios
        </h1>
        <p class="page-subtitle">Administra los usuarios del sistema y sus permisos</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
    </button>
</div>

<div class="row g-4">
    <!-- Sidebar de navegación -->
    <div class="col-lg-3">
        @include('configuracion.partials.sidebar')
    </div>

    <div class="col-lg-9">
        <div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
                        <th>Creado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                    {{ strtoupper(substr($usuario->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $usuario->name }}</div>
                                    <small class="text-muted">{{ $usuario->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @switch($usuario->role)
                                @case('admin')
                                    <span class="badge badge-status" style="background: #FEE2E2; color: #991B1B;">
                                        <i class="fas fa-crown me-1"></i>Administrador
                                    </span>
                                    @break
                                @case('contador')
                                    <span class="badge badge-status" style="background: #DBEAFE; color: #1E40AF;">
                                        <i class="fas fa-calculator me-1"></i>Contador
                                    </span>
                                    @break
                                @case('auxiliar')
                                    <span class="badge badge-status" style="background: #D1FAE5; color: #065F46;">
                                        <i class="fas fa-user-tie me-1"></i>Auxiliar
                                    </span>
                                    @break
                                @default
                                    <span class="badge badge-status" style="background: #F3F4F6; color: #374151;">
                                        <i class="fas fa-user me-1"></i>Usuario
                                    </span>
                            @endswitch
                        </td>
                        <td>
                            @if($usuario->active)
                                <span class="badge badge-status badge-success">
                                    <i class="fas fa-check-circle me-1"></i>Activo
                                </span>
                            @else
                                <span class="badge badge-status badge-danger">
                                    <i class="fas fa-times-circle me-1"></i>Inactivo
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($usuario->last_login)
                                {{ $usuario->last_login->diffForHumans() }}
                            @else
                                <span class="text-muted">Nunca</span>
                            @endif
                        </td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditar{{ $usuario->id }}"
                                        title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                @if($usuario->id !== auth()->id())
                                <form action="{{ route('configuracion.usuarios.toggle', $usuario) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-{{ $usuario->active ? 'warning' : 'success' }}" 
                                            title="{{ $usuario->active ? 'Desactivar' : 'Activar' }}">
                                        <i class="fas fa-{{ $usuario->active ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('configuracion.usuarios.eliminar', $usuario) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Editar Usuario -->
                    <div class="modal fade" id="modalEditar{{ $usuario->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('configuracion.usuarios.actualizar', $usuario) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Usuario</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" class="form-control" name="name" value="{{ $usuario->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="{{ $usuario->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rol</label>
                                            <select class="form-select" name="role" required>
                                                <option value="admin" {{ $usuario->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                                                <option value="contador" {{ $usuario->role === 'contador' ? 'selected' : '' }}>Contador</option>
                                                <option value="auxiliar" {{ $usuario->role === 'auxiliar' ? 'selected' : '' }}>Auxiliar</option>
                                                <option value="usuario" {{ $usuario->role === 'usuario' ? 'selected' : '' }}>Usuario</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nueva Contraseña <small class="text-muted">(dejar vacío para mantener)</small></label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirmar Contraseña</label>
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No hay usuarios registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('configuracion.usuarios.crear') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol</label>
                        <select class="form-select" name="role" required>
                            <option value="">Seleccionar rol...</option>
                            <option value="admin">Administrador</option>
                            <option value="contador">Contador</option>
                            <option value="auxiliar">Auxiliar</option>
                            <option value="usuario">Usuario</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" required minlength="8">
                        <small class="text-muted">Mínimo 8 caracteres</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
</div>
@endsection
