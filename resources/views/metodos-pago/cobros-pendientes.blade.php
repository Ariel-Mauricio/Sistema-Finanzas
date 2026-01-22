@extends('layouts.master')

@section('title', 'Cobros Pendientes')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">
            <i class="fas fa-clock text-warning me-2"></i>
            Cobros Pendientes
        </h1>
        <p class="page-subtitle">Gestión de pagos por cobrar</p>
    </div>
    <a href="{{ route('metodos-pago.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h4>¡Todo al día!</h4>
        <p class="text-muted">No hay cobros pendientes por gestionar.</p>
        <p class="text-muted small">Esta funcionalidad está reservada para futuras implementaciones donde se requiera seguimiento de pagos parciales o pendientes.</p>
    </div>
</div>
@endsection
