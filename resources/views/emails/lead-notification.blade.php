<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Lead</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #02115A;
            border-bottom: 3px solid #02115A;
            padding-bottom: 10px;
            margin-top: 0;
        }
        h2 {
            color: #010C42;
            margin-top: 30px;
            font-size: 18px;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #02115A;
        }
        .field {
            margin-bottom: 10px;
        }
        .field-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            min-width: 120px;
        }
        .field-value {
            color: #333;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nuevo Lead Recibido</h1>
        
        <div class="section">
            <h2>Datos del Interesado</h2>
            <div class="field">
                <span class="field-label">Nombre:</span>
                <span class="field-value">{{ $lead->nombre }} {{ $lead->apellido }}</span>
            </div>
            <div class="field">
                <span class="field-label">DNI:</span>
                <span class="field-value">{{ $lead->dni }}</span>
            </div>
            <div class="field">
                <span class="field-label">Correo:</span>
                <span class="field-value">{{ $lead->correo }}</span>
            </div>
            <div class="field">
                <span class="field-label">Teléfono:</span>
                <span class="field-value">{{ $lead->telefono }}</span>
            </div>
        </div>

        <div class="section">
            <h2>Datos de la Carrera/Cursada</h2>
            <div class="field">
                <span class="field-label">ID Curso:</span>
                <span class="field-value">{{ $cursada->ID_Curso ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Carrera:</span>
                <span class="field-value">{{ $cursada->carrera ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Fecha Inicio:</span>
                <span class="field-value">
                    @if($cursada->Fecha_Inicio)
                        {{ \Carbon\Carbon::parse($cursada->Fecha_Inicio)->format('d/m/Y') }}
                    @else
                        N/A
                    @endif
                </span>
            </div>
            <div class="field">
                <span class="field-label">Días:</span>
                <span class="field-value">{{ convertirDiasCompletos($cursada->xDias ?? '') ?: 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Modalidad:</span>
                <span class="field-value">{{ $cursada->xModalidad ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Régimen:</span>
                <span class="field-value">{{ $cursada->Régimen ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Sede:</span>
                <span class="field-value">{{ corregirNombreSede($cursada->sede ?? 'N/A') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Este email fue generado automáticamente por el sistema de leads de ITCA.</p>
            <p>Fecha: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
