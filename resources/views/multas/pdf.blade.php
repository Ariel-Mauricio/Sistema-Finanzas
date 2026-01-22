<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multa #{{ $multa->numero_documento }}</title>
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
            border-bottom: 3px solid #f59e0b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #f59e0b;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 11px;
            color: #666;
        }
        .document-title {
            background: #f59e0b;
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
            background: #fffbeb;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            color: #f59e0b;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-pendiente {
            background: #fef3c7;
            color: #92400e;
        }
        .status-pagada {
            background: #d1fae5;
            color: #065f46;
        }
        .status-anulada {
            background: #e5e7eb;
            color: #374151;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #fffbeb;
            padding: 8px 12px;
            font-weight: bold;
            color: #b45309;
            border-left: 4px solid #f59e0b;
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
        .value-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .value-box h3 {
            font-size: 28px;
            color: #dc2626;
            margin: 0;
        }
        .value-box p {
            color: #666;
            margin: 5px 0 0;
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
        .motivo-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 15px 0;
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
            NOTIFICACIÓN DE MULTA
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <span class="status-badge status-{{ $multa->estado }}">
                    {{ strtoupper($multa->estado) }}
                </span>
            </div>
            <div class="document-number">
                <span>Nº {{ $multa->numero_documento }}</span>
            </div>
        </div>

        <div class="info-section">
            <div class="section-title">Datos de la Persona Multada</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nombre:</div>
                    <div class="info-value">{{ $multa->persona }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha de Multa:</div>
                    <div class="info-value">{{ $multa->fecha->format('d/m/Y') }}</div>
                </div>
                @if($multa->aplicado_por)
                <div class="info-row">
                    <div class="info-label">Aplicado por:</div>
                    <div class="info-value">{{ $multa->aplicado_por }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="info-section">
            <div class="section-title">Motivo de la Multa</div>
            <div class="motivo-box">
                {{ $multa->motivo }}
            </div>
        </div>

        <div class="value-box">
            <p>VALOR DE LA MULTA</p>
            <h3>$ {{ number_format($multa->valor, 2) }}</h3>
            <p>
                @if($multa->estado == 'pendiente')
                    Pendiente de pago
                @elseif($multa->estado == 'pagada')
                    PAGADO
                @else
                    ANULADO
                @endif
            </p>
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    Autoridad
                </div>
            </div>
            <div class="signature-box" style="display: table-cell;"></div>
            <div class="signature-box">
                <div class="signature-line">
                    Persona Notificada
                </div>
            </div>
        </div>

        <div class="print-date">
            Documento generado el {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
