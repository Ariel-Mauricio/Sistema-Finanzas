@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Bienvenido, {{ Auth::user()->name }}. Aquí está el resumen de hoy.</p>
    </div>
    <div>
        <span class="badge badge-status badge-info fs-6">
            <i class="fas fa-calendar-alt me-1"></i>
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6 fade-in" style="animation-delay: 0.1s">
        <div class="stat-card stat-card-success">
            <div class="stat-icon">
                <i class="fas fa-arrow-up"></i>
            </div>
            <div class="stat-value">${{ number_format($stats['ingresos_mes'] ?? 0, 2) }}</div>
            <div class="stat-label">Ingresos del Mes</div>
            @if(isset($kpis['crecimiento_ingresos']))
            <div class="stat-change">
                <i class="fas fa-{{ $kpis['crecimiento_ingresos'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($kpis['crecimiento_ingresos']) }}% vs mes anterior
            </div>
            @endif
        </div>
    </div>

    <div class="col-xl-3 col-md-6 fade-in" style="animation-delay: 0.2s">
        <div class="stat-card stat-card-danger">
            <div class="stat-icon">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="stat-value">${{ number_format($stats['egresos_mes'] ?? 0, 2) }}</div>
            <div class="stat-label">Egresos del Mes</div>
            @if(isset($kpis['crecimiento_egresos']))
            <div class="stat-change">
                <i class="fas fa-{{ $kpis['crecimiento_egresos'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                {{ abs($kpis['crecimiento_egresos']) }}% vs mes anterior
            </div>
            @endif
        </div>
    </div>

    <div class="col-xl-3 col-md-6 fade-in" style="animation-delay: 0.3s">
        <div class="stat-card {{ ($stats['balance_mes'] ?? 0) >= 0 ? 'stat-card-primary' : 'stat-card-warning' }}">
            <div class="stat-icon">
                <i class="fas fa-balance-scale"></i>
            </div>
            <div class="stat-value">${{ number_format($stats['balance_mes'] ?? 0, 2) }}</div>
            <div class="stat-label">Balance del Mes</div>
            @if(isset($kpis['margen_operativo']))
            <div class="stat-change">
                <i class="fas fa-percentage"></i>
                {{ $kpis['margen_operativo'] }}% margen operativo
            </div>
            @endif
        </div>
    </div>

    <div class="col-xl-3 col-md-6 fade-in" style="animation-delay: 0.4s">
        <div class="stat-card stat-card-info">
            <div class="stat-icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-value">{{ $stats['comprobantes_mes'] ?? 0 }}</div>
            <div class="stat-label">Comprobantes del Mes</div>
            <div class="stat-change">
                <i class="fas fa-clock"></i>
                {{ $stats['total_comprobantes'] ?? 0 }} total
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <h5 class="fw-bold mb-3">
            <i class="fas fa-bolt text-warning me-2"></i>
            Acciones Rápidas
        </h5>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="{{ route('comprobantes.create') }}" class="quick-action">
            <div class="quick-action-icon text-success">
                <i class="fas fa-plus-circle"></i>
            </div>
            <span class="quick-action-text">Nuevo Ingreso</span>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="{{ route('egresos.create') }}" class="quick-action">
            <div class="quick-action-icon text-danger">
                <i class="fas fa-minus-circle"></i>
            </div>
            <span class="quick-action-text">Nuevo Egreso</span>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="{{ route('reportes.index') }}" class="quick-action">
            <div class="quick-action-icon text-info">
                <i class="fas fa-chart-pie"></i>
            </div>
            <span class="quick-action-text">Ver Reportes</span>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="{{ route('metodos-pago.index') }}" class="quick-action">
            <div class="quick-action-icon text-primary">
                <i class="fas fa-credit-card"></i>
            </div>
            <span class="quick-action-text">Métodos de Pago</span>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="{{ route('export-import.index') }}" class="quick-action">
            <div class="quick-action-icon text-secondary">
                <i class="fas fa-sync"></i>
            </div>
            <span class="quick-action-text">Sincronizar</span>
        </a>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <a href="{{ route('configuracion.index') }}" class="quick-action">
            <div class="quick-action-icon text-dark">
                <i class="fas fa-cog"></i>
            </div>
            <span class="quick-action-text">Configuración</span>
        </a>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    <i class="fas fa-chart-area text-primary me-2"></i>
                    Flujo de Efectivo - Últimos 12 Meses
                </h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary active">Mensual</button>
                    <button class="btn btn-outline-primary">Semanal</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="cashFlowChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie text-success me-2"></i>
                    Distribución de Gastos
                </h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="expenseChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-4">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    <i class="fas fa-arrow-circle-up text-success me-2"></i>
                    Últimos Ingresos
                </h5>
                <a href="{{ route('comprobantes.index') }}" class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th class="text-end">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosComprobantes ?? [] as $comprobante)
                            <tr>
                                <td>
                                    <span class="badge badge-status badge-info">{{ $comprobante->numero_comprobante }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $comprobante->nombre }} {{ $comprobante->apellido }}</div>
                                    <small class="text-muted">{{ $comprobante->cedula }}</small>
                                </td>
                                <td>{{ $comprobante->fecha ? $comprobante->fecha->format('d/m/Y') : '-' }}</td>
                                <td class="text-end">
                                    <span class="fw-bold text-success">${{ number_format($comprobante->valor_total, 2) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No hay comprobantes recientes</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">
                    <i class="fas fa-arrow-circle-down text-danger me-2"></i>
                    Últimos Egresos
                </h5>
                <a href="{{ route('egresos.index') }}" class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Proveedor</th>
                                <th>Fecha</th>
                                <th class="text-end">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosEgresos ?? [] as $egreso)
                            <tr>
                                <td>
                                    <span class="badge badge-status badge-danger">{{ $egreso->numero_documento }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $egreso->proveedor }}</div>
                                    <small class="text-muted">{{ $egreso->tipo_documento }}</small>
                                </td>
                                <td>{{ $egreso->fecha ? $egreso->fecha->format('d/m/Y') : '-' }}</td>
                                <td class="text-end">
                                    <span class="fw-bold text-danger">${{ number_format($egreso->total, 2) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No hay egresos recientes</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Annual Summary -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-calendar-check text-primary me-2"></i>
                    Resumen Anual {{ date('Y') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="py-3">
                            <div class="text-success mb-2">
                                <i class="fas fa-arrow-up fa-2x"></i>
                            </div>
                            <h3 class="fw-bold">${{ number_format($stats['ingresos_anio'] ?? 0, 2) }}</h3>
                            <p class="text-muted mb-0">Ingresos Totales</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="py-3">
                            <div class="text-danger mb-2">
                                <i class="fas fa-arrow-down fa-2x"></i>
                            </div>
                            <h3 class="fw-bold">${{ number_format($stats['egresos_anio'] ?? 0, 2) }}</h3>
                            <p class="text-muted mb-0">Egresos Totales</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="py-3">
                            <div class="text-primary mb-2">
                                <i class="fas fa-balance-scale fa-2x"></i>
                            </div>
                            <h3 class="fw-bold {{ ($stats['balance_anio'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($stats['balance_anio'] ?? 0, 2) }}
                            </h3>
                            <p class="text-muted mb-0">Balance Neto</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="py-3">
                            <div class="text-info mb-2">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                            <h3 class="fw-bold">{{ $stats['total_comprobantes'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Documentos Procesados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<div id="chartData" data-chart='<?= json_encode($chartData ?? ["labels" => [], "ingresos" => [], "egresos" => []]) ?>' class="d-none"></div>
<div id="topEgresosData" data-items='<?= json_encode($topEgresos ?? []) ?>' class="d-none"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cash Flow Chart
    const chartDataEl = document.getElementById('chartData');
    const chartData = chartDataEl ? JSON.parse(chartDataEl.dataset.chart || '{"labels":[],"ingresos":[],"egresos":[]}') : {labels:[], ingresos:[], egresos:[]};
    
    const cashFlowCtx = document.getElementById('cashFlowChart');
    if (cashFlowCtx) {
        new Chart(cashFlowCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Ingresos',
                    data: chartData.ingresos,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }, {
                    label: 'Egresos',
                    data: chartData.egresos,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Expense Distribution Chart
    const expenseCtx = document.getElementById('expenseChart');
    if (expenseCtx) {
        const topEgresosEl = document.getElementById('topEgresosData');
        const topEgresos = topEgresosEl ? JSON.parse(topEgresosEl.dataset.items || '[]') : [];
        const labels = topEgresos.map(e => e.tipo_documento || 'Otros');
        const values = topEgresos.map(e => parseFloat(e.total) || 0);
        
        if (values.length > 0) {
            new Chart(expenseCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#4F46E5',
                            '#10B981',
                            '#F59E0B',
                            '#EF4444',
                            '#8B5CF6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }
});
</script>
@endpush
