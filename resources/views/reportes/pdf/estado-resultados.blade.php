<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Resultados - FinanzaPro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #3B82F6; padding-bottom: 15px; }
        .header h1 { color: #3B82F6; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; }
        .info-box { background: #eff6ff; border-radius: 5px; padding: 12px; margin-bottom: 20px; border-left: 4px solid #3B82F6; }
        .info-box p { margin: 3px 0; }
        .section { margin-bottom: 20px; }
        .section-title { background: #f1f5f9; padding: 8px 12px; font-weight: bold; color: #1e40af; border-radius: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #e5e5e5; }
        th { background: #f8fafc; font-weight: 600; }
        .text-right { text-align: right; }
        .text-success { color: #10B981; }
        .text-danger { color: #EF4444; }
        .text-primary { color: #3B82F6; }
        .total-row { background: #f1f5f9; font-weight: bold; }
        .grand-total { background: #3B82F6; color: white; font-size: 14px; }
        .amount { font-family: 'DejaVu Sans Mono', monospace; }
        .summary-box { display: table; width: 100%; margin-bottom: 20px; }
        .summary-item { display: table-cell; width: 33%; text-align: center; padding: 15px; }
        .summary-item.success { background: #dcfce7; border-radius: 8px; }
        .summary-item.danger { background: #fee2e2; border-radius: 8px; }
        .summary-item.primary { background: #dbeafe; border-radius: 8px; }
        .summary-item h3 { font-size: 20px; margin-bottom: 5px; }
        .summary-item p { font-size: 10px; color: #666; }
        .footer { margin-top: 25px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e5e5e5; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ESTADO DE RESULTADOS</h1>
            <p>Sistema de Gestión Financiera FinanzaPro</p>
        </div>

        <div class="info-box">
            <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
            <p><strong>Generado:</strong> {{ $fechaGeneracion }}</p>
        </div>

        <!-- Resumen Visual -->
        <div class="summary-box">
            <div class="summary-item success">
                <h3 class="text-success">${{ number_format($totalIngresos, 2) }}</h3>
                <p>Total Ingresos</p>
            </div>
            <div class="summary-item danger">
                <h3 class="text-danger">${{ number_format($totalEgresos, 2) }}</h3>
                <p>Total Egresos</p>
            </div>
            <div class="summary-item primary">
                <h3 class="text-primary">${{ number_format($utilidad, 2) }}</h3>
                <p>{{ $utilidad >= 0 ? 'Utilidad Neta' : 'Pérdida Neta' }}</p>
            </div>
        </div>

        <!-- Ingresos -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-arrow-up"></i> INGRESOS
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Tipo de Comprobante</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ingresos as $ingreso)
                    <tr>
                        <td>{{ \App\Models\Comprobante::$tipos[$ingreso->tipo] ?? $ingreso->tipo }}</td>
                        <td class="text-right amount text-success">${{ number_format($ingreso->total, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td><strong>TOTAL INGRESOS</strong></td>
                        <td class="text-right amount text-success"><strong>${{ number_format($totalIngresos, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Egresos -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-arrow-down"></i> EGRESOS / GASTOS
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($egresos as $egreso)
                    <tr>
                        <td>{{ \App\Models\Egreso::$categorias[$egreso->categoria] ?? $egreso->categoria }}</td>
                        <td class="text-right amount text-danger">${{ number_format($egreso->total, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td><strong>TOTAL EGRESOS</strong></td>
                        <td class="text-right amount text-danger"><strong>${{ number_format($totalEgresos, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Resultado Final -->
        <table>
            <tr class="grand-total">
                <td><strong>{{ $utilidad >= 0 ? 'UTILIDAD NETA' : 'PÉRDIDA NETA' }}</strong></td>
                <td class="text-right amount"><strong>${{ number_format(abs($utilidad), 2) }}</strong></td>
            </tr>
        </table>

        <div class="footer">
            <p>Documento generado automáticamente por FinanzaPro | {{ $fechaGeneracion }}</p>
            <p>Este documento es un resumen financiero y no constituye un documento contable oficial.</p>
        </div>
    </div>
</body>
</html>
