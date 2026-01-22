<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante #{{ $comprobante->numero_comprobante }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 11px;
            color: #666;
        }
        .document-title {
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .document-number {
            text-align: right;
            margin-bottom: 20px;
        }
        .document-number span {
            background: #f1f5f9;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            color: #2563eb;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #f8fafc;
            padding: 8px 12px;
            font-weight: bold;
            color: #1e40af;
            border-left: 4px solid #2563eb;
            margin-bottom: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 5px 10px;
            color: #666;
            font-weight: 500;
        }
        .info-value {
            display: table-cell;
            padding: 5px 10px;
            font-weight: 500;
        }
        .values-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .values-table th {
            background: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .values-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .values-table .total-row {
            background: #f0fdf4;
            font-weight: bold;
            font-size: 14px;
        }
        .values-table .total-row td {
            border-top: 2px solid #22c55e;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .signatures {
            display: table;
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            display: table-cell;
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        .print-date {
            margin-top: 30px;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">FinanzaPro</div>
            <div class="company-info">Sistema de Gestión Financiera</div>
        </div>

        <div class="document-title">
            COMPROBANTE DE INGRESO
        </div>

        <div class="document-number">
            <span>Nº {{ $comprobante->numero_comprobante }}</span>
        </div>

        <div class="info-section">
            <div class="section-title">Datos del Cliente</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Cliente:</div>
                    <div class="info-value">{{ $comprobante->cliente }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cédula/RUC:</div>
                    <div class="info-value">{{ $comprobante->cedula_ruc ?: 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Teléfono:</div>
                    <div class="info-value">{{ $comprobante->telefono ?: 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $comprobante->email ?: 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="section-title">Detalle del Comprobante</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Tipo:</div>
                    <div class="info-value">{{ $comprobante->tipo_nombre }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha:</div>
                    <div class="info-value">{{ $comprobante->fecha->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Método de Pago:</div>
                    <div class="info-value">{{ $comprobante->metodo_pago_nombre }}</div>
                </div>
                @if($comprobante->referencia_pago)
                <div class="info-row">
                    <div class="info-label">Nº Referencia:</div>
                    <div class="info-value">{{ $comprobante->referencia_pago }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="section-title">Descripción</div>
            <p style="padding: 10px; background: #f8fafc; border-radius: 5px;">
                {{ $comprobante->descripcion }}
            </p>
            @if($comprobante->observaciones)
            <p style="padding: 10px; margin-top: 10px; font-style: italic; color: #666;">
                <strong>Observaciones:</strong> {{ $comprobante->observaciones }}
            </p>
            @endif
        </div>

        <table class="values-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th class="text-right" style="width: 150px;">Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">$ {{ number_format($comprobante->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>IVA (15%)</td>
                    <td class="text-right">$ {{ number_format($comprobante->iva, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td class="text-right">$ {{ number_format($comprobante->total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    Recibido por
                </div>
            </div>
            <div class="signature-box" style="display: table-cell;"></div>
            <div class="signature-box">
                <div class="signature-line">
                    Cliente
                </div>
            </div>
        </div>

        <div class="print-date">
            Documento generado el {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
