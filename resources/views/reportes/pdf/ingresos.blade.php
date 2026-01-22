<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Ingresos - FinanzaPro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #333; line-height: 1.3; }
        .container { padding: 15px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #10B981; padding-bottom: 12px; }
        .header h1 { color: #10B981; font-size: 20px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 11px; }
        .info-box { background: #f0fdf4; border-radius: 5px; padding: 10px; margin-bottom: 15px; border-left: 4px solid #10B981; }
        .info-box p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 9px; }
        th, td { padding: 6px 5px; text-align: left; border-bottom: 1px solid #e5e5e5; }
        th { background: #10B981; color: white; font-weight: 600; font-size: 9px; }
        tr:nth-child(even) { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background: #dcfce7 !important; font-weight: bold; }
        .amount { font-family: 'DejaVu Sans Mono', monospace; }
        .summary-cards { margin-bottom: 15px; }
        .summary-card { display: inline-block; width: 23%; background: #f8fafc; border-radius: 5px; padding: 10px; margin-right: 2%; text-align: center; vertical-align: top; }
        .summary-card:last-child { margin-right: 0; }
        .summary-card h4 { font-size: 16px; color: #10B981; margin-bottom: 3px; }
        .summary-card p { font-size: 9px; color: #666; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e5e5e5; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DETALLE DE INGRESOS</h1>
            <p>Sistema de Gestión Financiera FinanzaPro</p>
        </div>

        <div class="info-box">
            <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
            <p><strong>Generado:</strong> {{ $fechaGeneracion }}</p>
            <p><strong>Total de registros:</strong> {{ $comprobantes->count() }}</p>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <h4>{{ $comprobantes->count() }}</h4>
                <p>Comprobantes</p>
            </div>
            <div class="summary-card">
                <h4>${{ number_format($comprobantes->sum('total'), 2) }}</h4>
                <p>Total Ingresos</p>
            </div>
            <div class="summary-card">
                <h4>${{ number_format($comprobantes->avg('total') ?? 0, 2) }}</h4>
                <p>Promedio</p>
            </div>
            <div class="summary-card">
                <h4>${{ number_format($comprobantes->max('total') ?? 0, 2) }}</h4>
                <p>Mayor Ingreso</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>N° Comprobante</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Método Pago</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comprobantes as $comp)
                <tr>
                    <td>{{ $comp->numero_comprobante }}</td>
                    <td>{{ $comp->fecha->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($comp->cliente, 25) }}</td>
                    <td>{{ Str::limit($comp->descripcion, 25) }}</td>
                    <td>{{ $comp->tipo_nombre }}</td>
                    <td>{{ $comp->metodo_pago_nombre }}</td>
                    <td class="text-right amount">${{ number_format($comp->total, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No hay comprobantes en este período</td>
                </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="6"><strong>TOTAL GENERAL</strong></td>
                    <td class="text-right amount"><strong>${{ number_format($comprobantes->sum('total'), 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Documento generado automáticamente por FinanzaPro | {{ $fechaGeneracion }}</p>
        </div>
    </div>
</body>
</html>
