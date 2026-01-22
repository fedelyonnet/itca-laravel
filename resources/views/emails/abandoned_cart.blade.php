<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retomá tu Inscripción</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f3f4f6; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #1a56db; color: #ffffff; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .course-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 15px; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center; width: 100%; box-sizing: border-box; }
        .btn-primary { background-color: #1a56db; }
        .btn-secondary { background-color: #4b5563; margin-top: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #6b7280; background-color: #f9fafb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Hola {{ $lead->nombre }}!</h1>
        </div>
        <div class="content">
            <p>Notamos que comenzaste tu inscripción pero no la finalizaste.</p>
            <p>Tu vacante para el curso <strong>{{ $cursada->carrera }}</strong> te está esperando, pero los cupos son limitados.</p>
            
            <div class="course-card">
                <h3 style="margin-top: 0;">{{ $cursada->carrera }}</h3>
                <p style="margin: 5px 0;">Sede: {{ $cursada->sede }}</p>
                <p style="margin: 5px 0;">Inicio: {{ \Carbon\Carbon::parse($cursada->Fecha_Inicio)->format('d/m/Y') }}</p>
            </div>

            <p>Para asegurar tu lugar, hacé clic en el siguiente botón:</p>
            
            <a href="{{ url('/inscripcion') }}?retomar={{ $cursada->ID_Curso }}&lead={{ $lead->id }}" class="btn btn-primary" style="color: #ffffff;">
                FINALIZAR INSCRIPCIÓN
            </a>

            <div style="margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                <p style="font-size: 14px; margin-bottom: 10px;">¿Cambiaste de opinión? Podés elegir otra carrera o fecha:</p>
                <a href="{{ url('/carreras') }}" class="btn btn-secondary" style="color: #ffffff;">
                    VER OTRAS CARRERAS
                </a>
            </div>
        </div>
        <div class="footer">
            <p>Instituto Tecnológico de Capacitación Automotriz (ITCA)</p>
            <p>Si ya realizaste el pago, desestimá este correo.</p>
        </div>
    </div>
</body>
</html>
