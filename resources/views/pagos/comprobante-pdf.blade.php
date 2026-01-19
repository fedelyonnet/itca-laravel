<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago - ITCA</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 1.5cm;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 1rem;
            border-bottom: 2px solid #010C42;
            padding-bottom: 0.5rem;
        }
        .logo {
            width: 140px;
            margin-bottom: 0.5rem;
        }
        .title {
            color: #010C42;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 1rem;
        }
        .meta-info td {
            vertical-align: top;
        }
        .box {
            border: 1px solid #D4D4D4;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .box-title {
            color: #010C42;
            font-size: 18px;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 1rem;
            border-bottom: 1px solid #D4D4D4;
            padding-bottom: 0.5rem;
        }
        .info-grid {
            width: 100%;
        }
        .info-grid td {
            padding: 5px 0;
        }
        .label {
            font-weight: bold;
            color: #666;
            width: 140px;
        }
        .value {
            color: #010C42;
        }
        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .price-table td {
            padding: 10px;
            border-top: 1px solid #eee;
        }
        .price-row-total {
            background-color: #f9f9f9;
            font-size: 20px;
            font-weight: bold;
        }
        .price-label {
            text-align: right;
            padding-right: 20px;
        }
        .price-amount {
            text-align: right;
            width: 150px;
        }
        .footer {
            margin-top: 3rem;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }
        .status-badge {
            display: inline-block;
            background-color: #65E09C;
            color: #010C42;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }
        .savings {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="ITCA Logo" class="logo">
        <h1 class="title">Comprobante de Inscripción</h1>
        <div class="status-badge">PAGO RESERVA APROBADO</div>
    </div>

    <table class="meta-info">
        <tr>
            <td>
                <strong>Emitido para:</strong><br>
                {{ $inscripcion->lead->nombre }} {{ $inscripcion->lead->apellido }}<br>
                DNI: {{ $inscripcion->lead->dni }}<br>
                Email: {{ $inscripcion->lead->correo }}
            </td>
            <td style="text-align: right;">
                <strong>Detalles del Pago:</strong><br>
                Nro. Operación: #{{ $inscripcion->collection_id ?? 'n/d' }}<br>
                Fecha: {{ $fecha }}<br>
                Hora: {{ $hora }} hs
            </td>
        </tr>
    </table>

    <div class="box">
        <div class="box-title">Detalles de la Cursada</div>
        <table class="info-grid">
            <tr>
                <td class="label">Carrera:</td>
                <td class="value">{{ $cursada->carrera ?? 'No especificada' }}</td>
            </tr>
            <tr>
                <td class="label">Sede:</td>
                <td class="value">{{ $cursada->sede ?? 'No especificada' }}</td>
            </tr>
            <tr>
                <td class="label">Modalidad:</td>
                <td class="value">{{ $cursada->xModalidad ?? 'No especificada' }}</td>
            </tr>
            <tr>
                <td class="label">Fecha Inicio:</td>
                <td class="value">
                    @if(isset($cursada->Fecha_Inicio))
                        {{ \Carbon\Carbon::parse($cursada->Fecha_Inicio)->translatedFormat('F Y') }}
                    @else
                        No disponible
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Turno:</td>
                <td class="value">{{ $cursada->xTurno ?? 'No especificado' }}</td>
            </tr>
        </table>
    </div>

    <div class="box">
        <div class="box-title">Resumen de Pago</div>
        <table class="price-table">
            <tr>
                <td class="price-label">Subtotal Matrícula:</td>
                <td class="price-amount">${{ number_format(($inscripcion->monto_matricula ?? 0) + ($inscripcion->monto_descuento ?? 0), 2, ',', '.') }}</td>
            </tr>
            @if($inscripcion->monto_descuento > 0)
            <tr>
                <td class="price-label">Descuento ({{ $inscripcion->codigo_descuento }}):</td>
                <td class="price-amount savings">-${{ number_format($inscripcion->monto_descuento, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="price-row-total">
                <td class="price-label">TOTAL ABONADO:</td>
                <td class="price-amount">${{ number_format($inscripcion->monto_matricula ?? 0, 2, ',', '.') }}</td>
            </tr>
        </table>
        <p style="font-size: 11px; color: #777; margin-top: 15px; font-style: italic;">
            * Precio total sin impuestos nacionales: ARS $ {{ number_format($inscripcion->monto_sin_iva ?? 0, 2, ',', '.') }}
        </p>
    </div>

    <div class="footer">
        <strong>Instituto Tecnológico de Capacitación Automotriz (ITCA)</strong><br>
        Este es un comprobante oficial de pago generado automáticamente.<br>
        Para cualquier consulta, por favor contactanos a través de nuestros canales oficiales.
    </div>
</body>
</html>
