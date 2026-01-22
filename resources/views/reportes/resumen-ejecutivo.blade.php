@extends('layouts.master')

@section('title', 'Resumen Ejecutivo')

@section('styles')
<style>
    .executive-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }
    .executive-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
    }
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
    @media (max-width: 1200px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .kpi-grid { grid-template-columns: 1fr; }
    }
    .kpi-item {
        text-align: center;
        padding: 1.5rem;
        border-radius: 12px;
        background: var(--card-bg);
        box-shadow: var(--shadow);
    }
    .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .kpi-label {
        color: var(--text-muted);
        font-size: 0.875rem;
    }
    .kpi-change {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
    }
    .kpi-change.positive {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }
    .kpi-change.negative {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }
    .insight-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
    }
    .insight-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .print-only { display: none; }
    @media print {
        .no-print { display: none !important; }
        .print-only { display: block; }
        .kpi-grid { grid-template-columns: repeat(4, 1fr); }
    }
</style>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center no-print">
    <div>
        <h1 class="page-title">Resumen Ejecutivo</h1>
        <p class="page-subtitle">Vista consolidada del estado financiero</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="fas fa-print me-2"></i>Imprimir
        </button>
        <a href="{{ route('reportes.resumen-ejecutivo', ['exportar' => 'pdf']) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>PDF
        </a>
    </div>
</div>

<!-- Header del Reporte (para impresión) -->
<div class="print-only text-center mb-4">
    <h1>{{ config('app.name') }}</h1>
    <h2>Resumen Ejecutivo Financiero</h2>
    <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
</div>

<!-- Período de Análisis -->
<div class="card mb-4 executive-card">
    <div class="executive-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-1">Período de Análisis</h3>
                <p class="mb-0 opacity-75">
                    {{ $fechaInicio ?? now()->startOfMonth()->format('d/m/Y') }} - {{ $fechaFin ?? now()->format('d/m/Y') }}
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <form action="{{ route('reportes.resumen-ejecutivo') }}" method="GET" class="d-inline-flex gap-2 align-items-center no-print">
                    <input type="date" class="form-control form-control-sm bg-white bg-opacity-10 text-white border-white border-opacity-25" 
                           name="fecha_inicio" value="{{ request('fecha_inicio', now()->startOfMonth()->format('Y-m-d')) }}">
                    <span class="text-white">-</span>
                    <input type="date" class="form-control form-control-sm bg-white bg-opacity-10 text-white border-white border-opacity-25" 
                           name="fecha_fin" value="{{ request('fecha_fin', now()->format('Y-m-d')) }}">
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- KPIs Principales -->
<div class="kpi-grid mb-4">
    <div class="kpi-item">
        <div class="kpi-value text-success">${{ number_format($totalIngresos ?? 0, 2) }}</div>
        <div class="kpi-label">Ingresos Totales</div>
        @if(isset($cambioIngresos))
        <div class="kpi-change {{ $cambioIngresos >= 0 ? 'positive' : 'negative' }}">
            <i class="fas fa-arrow-{{ $cambioIngresos >= 0 ? 'up' : 'down' }}"></i>
            {{ abs($cambioIngresos) }}% vs período anterior
        </div>
        @endif
    </div>
    <div class="kpi-item">
        <div class="kpi-value text-danger">${{ number_format($totalEgresos ?? 0, 2) }}</div>
        <div class="kpi-label">Egresos Totales</div>
        @if(isset($cambioEgresos))
        <div class="kpi-change {{ $cambioEgresos <= 0 ? 'positive' : 'negative' }}">
            <i class="fas fa-arrow-{{ $cambioEgresos <= 0 ? 'down' : 'up' }}"></i>
            {{ abs($cambioEgresos) }}% vs período anterior
        </div>
        @endif
    </div>
    <div class="kpi-item">
        <div class="kpi-value {{ ($utilidadNeta ?? 0) >= 0 ? 'text-primary' : 'text-danger' }}">${{ number_format($utilidadNeta ?? 0, 2) }}</div>
        <div class="kpi-label">Utilidad Neta</div>
        @if(isset($margenUtilidad))
        <div class="kpi-change {{ $margenUtilidad >= 20 ? 'positive' : 'negative' }}">
            <i class="fas fa-percentage"></i>
            {{ number_format($margenUtilidad, 1) }}% margen
        </div>
        @endif
    </div>
    <div class="kpi-item">
        <div class="kpi-value text-info">${{ number_format($saldoCuentas ?? 0, 2) }}</div>
        <div class="kpi-label">Saldo en Cuentas</div>
        @if(isset($cuentasActivas))
        <div class="kpi-change positive">
            <i class="fas fa-university"></i>
            {{ $cuentasActivas }} cuentas activas
        </div>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- Gráfico Principal -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-area text-primary me-2"></i>
                    Evolución Financiera
                </h5>
            </div>
            <div class="card-body">
                <canvas id="evolucionChart" height="300"></canvas>
            </div>
        </div>

        <!-- Comparativa -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-balance-scale text-primary me-2"></i>
                    Análisis Comparativo
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th class="text-end">Período Actual</th>
                                <th class="text-end">Período Anterior</th>
                                <th class="text-end">Variación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-arrow-up text-success me-2"></i>Ingresos</td>
                                <td class="text-end fw-semibold">${{ number_format($totalIngresos ?? 0, 2) }}</td>
                                <td class="text-end">${{ number_format($ingresosPeriodoAnterior ?? 0, 2) }}</td>
                                <td class="text-end {{ ($variacionIngresos ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ ($variacionIngresos ?? 0) >= 0 ? '+' : '' }}{{ number_format($variacionIngresos ?? 0, 1) }}%
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-arrow-down text-danger me-2"></i>Egresos</td>
                                <td class="text-end fw-semibold">${{ number_format($totalEgresos ?? 0, 2) }}</td>
                                <td class="text-end">${{ number_format($egresosPeriodoAnterior ?? 0, 2) }}</td>
                                <td class="text-end {{ ($variacionEgresos ?? 0) <= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ ($variacionEgresos ?? 0) >= 0 ? '+' : '' }}{{ number_format($variacionEgresos ?? 0, 1) }}%
                                </td>
                            </tr>
                            <tr class="table-light">
                                <td class="fw-bold"><i class="fas fa-calculator text-primary me-2"></i>Utilidad</td>
                                <td class="text-end fw-bold">${{ number_format($utilidadNeta ?? 0, 2) }}</td>
                                <td class="text-end fw-semibold">${{ number_format($utilidadPeriodoAnterior ?? 0, 2) }}</td>
                                <td class="text-end fw-bold {{ ($variacionUtilidad ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ ($variacionUtilidad ?? 0) >= 0 ? '+' : '' }}{{ number_format($variacionUtilidad ?? 0, 1) }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Lateral -->
    <div class="col-lg-4">
        <!-- Distribución de Ingresos -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie text-success me-2"></i>
                    Distribución de Ingresos
                </h5>
            </div>
            <div class="card-body">
                <canvas id="ingresosChart" height="200"></canvas>
            </div>
        </div>

        <!-- Distribución de Egresos -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie text-danger me-2"></i>
                    Distribución de Egresos
                </h5>
            </div>
            <div class="card-body">
                <canvas id="egresosChart" height="200"></canvas>
            </div>
        </div>

        <!-- Insights -->
        <div class="insight-card mt-4">
            <div class="insight-icon">
                <i class="fas fa-lightbulb fa-lg"></i>
            </div>
            <h5 class="mb-3">Insights del Período</h5>
            <ul class="list-unstyled mb-0">
                @forelse($insights ?? [] as $insight)
                <li class="mb-2">
                    <i class="fas fa-check-circle me-2 opacity-75"></i>
                    {{ $insight }}
                </li>
                @empty
                <li>
                    <i class="fas fa-info-circle me-2 opacity-75"></i>
                    Analiza tus transacciones para obtener insights personalizados.
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Resumen de Cuentas Bancarias -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-university text-primary me-2"></i>
            Estado de Cuentas Bancarias
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>Banco</th>
                        <th>Tipo</th>
                        <th>Número</th>
                        <th class="text-end">Saldo Inicial</th>
                        <th class="text-end">Movimientos</th>
                        <th class="text-end">Saldo Actual</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuentasBancarias ?? [] as $cuenta)
                    <tr>
                        <td class="fw-semibold">{{ $cuenta->nombre_banco }}</td>
                        <td>{{ ucfirst($cuenta->tipo_cuenta) }}</td>
                        <td><code>{{ $cuenta->numero_cuenta }}</code></td>
                        <td class="text-end">${{ number_format($cuenta->saldo_inicial, 2) }}</td>
                        <td class="text-end {{ $cuenta->movimientos_netos >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $cuenta->movimientos_netos >= 0 ? '+' : '' }}${{ number_format($cuenta->movimientos_netos ?? 0, 2) }}
                        </td>
                        <td class="text-end fw-bold">${{ number_format($cuenta->saldo_actual, 2) }}</td>
                        <td class="text-center">
                            <span class="badge badge-status {{ $cuenta->activa ? 'badge-success' : 'badge-danger' }}">
                                {{ $cuenta->activa ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No hay cuentas bancarias registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($cuentasBancarias && count($cuentasBancarias) > 0)
                <tfoot class="table-light">
                    <tr>
                        <td colspan="5" class="fw-bold">TOTAL</td>
                        <td class="text-end fw-bold fs-5">${{ number_format($saldoCuentas ?? 0, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- Footer del Reporte -->
<div class="text-center text-muted mt-4 py-3 border-top print-only">
    <small>Este reporte fue generado automáticamente por {{ config('app.name') }}</small>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div id="evolucionLabels" data-labels='<?= json_encode($chartLabels ?? []) ?>' class="d-none"></div>
<div id="evolucionIngresos" data-values='<?= json_encode($chartIngresos ?? []) ?>' class="d-none"></div>
<div id="evolucionEgresos" data-values='<?= json_encode($chartEgresos ?? []) ?>' class="d-none"></div>
<div id="evolucionUtilidad" data-values='<?= json_encode($chartUtilidad ?? []) ?>' class="d-none"></div>
<script>
// Gráfico de Evolución
const evolucionCtx = document.getElementById('evolucionChart').getContext('2d');
const labelsEl = document.getElementById('evolucionLabels');
const ingresosEl = document.getElementById('evolucionIngresos');
const egresosEl = document.getElementById('evolucionEgresos');
const utilidadEl = document.getElementById('evolucionUtilidad');

new Chart(evolucionCtx, {
    type: 'line',
    data: {
        labels: labelsEl ? JSON.parse(labelsEl.dataset.labels || '[]') : [],
        datasets: [{
            label: 'Ingresos',
            data: ingresosEl ? JSON.parse(ingresosEl.dataset.values || '[]') : [],
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Egresos',
            data: egresosEl ? JSON.parse(egresosEl.dataset.values || '[]') : [],
            borderColor: '#EF4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            fill: true,
            tension: 0.4
        }, {
            label: 'Utilidad',
            data: utilidadEl ? JSON.parse(utilidadEl.dataset.values || '[]') : [],
            borderColor: '#6366F1',
            backgroundColor: 'transparent',
            borderWidth: 3,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: value => '$' + value.toLocaleString()
                }
            }
        }
    }
});

// Gráfico de Ingresos
const ingresosCtx = document.getElementById('ingresosChart').getContext('2d');
new Chart(ingresosCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($chartIngresosLabels ?? []) !!},
        datasets: [{
            data: {!! json_encode($chartIngresosData ?? []) !!},
            backgroundColor: ['#10B981', '#34D399', '#6EE7B7', '#A7F3D0', '#D1FAE5']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, font: { size: 11 } }
            }
        }
    }
});

// Gráfico de Egresos
const egresosCtx = document.getElementById('egresosChart').getContext('2d');
new Chart(egresosCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($chartEgresosLabels ?? []) !!},
        datasets: [{
            data: {!! json_encode($chartEgresosData ?? []) !!},
            backgroundColor: ['#EF4444', '#F87171', '#FCA5A5', '#FECACA', '#FEE2E2']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, font: { size: 11 } }
            }
        }
    }
});
</script>
@endsection
