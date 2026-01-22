<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egreso #{{ $egreso->numero_documento }}</title>
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
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 11px;
            color: #666;
        }
        .document-title {
            background: #dc2626;
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
            background: #fef2f2;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            color: #dc2626;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #fef2f2;
            padding: 8px 12px;
            font-weight: bold;
            color: #b91c1c;
            border-left: 4px solid #dc2626;
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
            background: #dc2626;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .values-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .values-table .total-row {
            background: #fef2f2;
            font-weight: bold;
            font-size: 14px;
        }
        .values-table .total-row td {
            border-top: 2px solid #dc2626;
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
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #f3f4f6;
            border-radius: 3px;
            font-size: 11px;
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
            COMPROBANTE DE EGRESO
        </div>

        <div class="document-number">
            <span>Nº {{ $egreso->numero_documento }}</span>
        </div>

        <div class="info-section">
            <div class="section-title">Datos del Proveedor</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Proveedor:</div>
                    <div class="info-value">{{ $egreso->proveedor }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">RUC:</div>
                    <div class="info-value">{{ $egreso->ruc_proveedor ?: 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="info-section">
            <div class="section-title">Detalle del Egreso</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Tipo:</div>
                    <div class="info-value">{{ $egreso->tipo_nombre }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Categoría:</div>
                    <div class="info-value"><span class="badge">{{ $egreso->categoria_nombre }}</span></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha:</div>
                    <div class="info-value">{{ $egreso->fecha->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Método de Pago:</div>
                    <div class="info-value">{{ $egreso->metodo_pago_nombre }}</div>
                </div>
                @if($egreso->referencia_pago)
                <div class="info-row">
                    <div class="info-label">Nº Referencia:</div>
                    <div class="info-value">{{ $egreso->referencia_pago }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="section-title">Descripción</div>
            <p style="padding: 10px; background: #fef2f2; border-radius: 5px;">
                {{ $egreso->descripcion }}
            </p>
            @if($egreso->observaciones)
            <p style="padding: 10px; margin-top: 10px; font-style: italic; color: #666;">
                <strong>Observaciones:</strong> {{ $egreso->observaciones }}
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
                    <td class="text-right">$ {{ number_format($egreso->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>IVA (15%)</td>
                    <td class="text-right">$ {{ number_format($egreso->iva, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td class="text-right">$ {{ number_format($egreso->total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    Autorizado por
                </div>
            </div>
            <div class="signature-box" style="display: table-cell;"></div>
            <div class="signature-box">
                <div class="signature-line">
                    Recibido por
                </div>
            </div>
        </div>

        <div class="print-date">
            Documento generado el {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
