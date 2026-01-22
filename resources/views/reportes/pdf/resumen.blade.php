<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Financiero - FinanzaPro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #8B5CF6; padding-bottom: 15px; }
        .header h1 { color: #8B5CF6; font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; }
        .info-box { background: #f5f3ff; border-radius: 5px; padding: 12px; margin-bottom: 20px; border-left: 4px solid #8B5CF6; }
        .info-box p { margin: 3px 0; }
        .summary-cards { margin-bottom: 20px; }
        .summary-card { display: inline-block; width: 48%; background: #f8fafc; border-radius: 8px; padding: 15px; margin-right: 2%; margin-bottom: 10px; text-align: center; vertical-align: top; }
        .summary-card h3 { font-size: 20px; margin-bottom: 5px; }
        .summary-card p { font-size: 10px; color: #666; }
        .summary-card.success { background: #dcfce7; }
        .summary-card.danger { background: #fee2e2; }
        .summary-card.primary { background: #dbeafe; }
        .summary-card.warning { background: #fef3c7; }
        .text-success { color: #10B981; }
        .text-danger { color: #EF4444; }
        .text-primary { color: #3B82F6; }
        .text-warning { color: #F59E0B; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #e5e5e5; }
        th { background: #8B5CF6; color: white; font-weight: 600; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .amount { font-family: 'DejaVu Sans Mono', monospace; }
        .footer { margin-top: 25px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e5e5e5; padding-top: 12px; }
        .positive { color: #10B981; }
        .negative { color: #EF4444; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>RESUMEN FINANCIERO ANUAL</h1>
            <p>Sistema de Gestión Financiera FinanzaPro</p>
        </div>

        <div class="info-box">
            <p><strong>Año Fiscal:</strong> {{ $anio ?? date('Y') }}</p>
            <p><strong>Generado:</strong> {{ $fechaGeneracion }}</p>
        </div>

        <!-- Resumen de Totales -->
        <div class="summary-cards">
            <div class="summary-card success">
                <h3 class="text-success">${{ number_format($totales['ingresos'] ?? 0, 2) }}</h3>
                <p>Total Ingresos</p>
            </div>
            <div class="summary-card danger">
                <h3 class="text-danger">${{ number_format($totales['egresos'] ?? 0, 2) }}</h3>
                <p>Total Egresos</p>
            </div>
            <div class="summary-card primary">
                <h3 class="text-primary">${{ number_format($totales['balance'] ?? 0, 2) }}</h3>
                <p>Balance Neto</p>
            </div>
            <div class="summary-card warning">
                @php
                    $margenAnual = ($totales['ingresos'] ?? 0) > 0 
                        ? round((($totales['balance'] ?? 0) / ($totales['ingresos'] ?? 1)) * 100, 1) 
                        : 0;
                @endphp
                <h3 class="text-warning">{{ $margenAnual }}%</h3>
                <p>Margen Anual</p>
            </div>
        </div>

        <!-- Detalle Mensual -->
        @if(isset($dataMensual) && count($dataMensual) > 0)
        <h4 style="margin-bottom: 10px; color: #8B5CF6;">Detalle Mensual</h4>
        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th class="text-right">Ingresos</th>
                    <th class="text-right">Egresos</th>
                    <th class="text-right">Balance</th>
                    <th class="text-center">Margen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataMensual as $mes)
                <tr>
                    <td>{{ $mes['mes'] }}</td>
                    <td class="text-right amount text-success">${{ number_format($mes['ingresos'], 2) }}</td>
                    <td class="text-right amount text-danger">${{ number_format($mes['egresos'], 2) }}</td>
                    <td class="text-right amount {{ $mes['balance'] >= 0 ? 'positive' : 'negative' }}">
                        ${{ number_format($mes['balance'], 2) }}
                    </td>
                    <td class="text-center {{ $mes['margen'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $mes['margen'] }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background: #f3f4f6;">
                    <td>TOTALES</td>
                    <td class="text-right amount text-success">${{ number_format($totales['ingresos'] ?? 0, 2) }}</td>
                    <td class="text-right amount text-danger">${{ number_format($totales['egresos'] ?? 0, 2) }}</td>
                    <td class="text-right amount {{ ($totales['balance'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                        ${{ number_format($totales['balance'] ?? 0, 2) }}
                    </td>
                    <td class="text-center {{ $margenAnual >= 0 ? 'positive' : 'negative' }}">{{ $margenAnual }}%</td>
                </tr>
            </tfoot>
        </table>
        @endif

        <div class="footer">
            <p>Documento generado automáticamente por FinanzaPro | {{ $fechaGeneracion }}</p>
        </div>
    </div>
</body>
</html>
