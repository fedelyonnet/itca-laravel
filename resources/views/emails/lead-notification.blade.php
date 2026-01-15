<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Lead ITCA</title>
    <style>
        /* Reset básico */
        body { margin: 0; padding: 0; background-color: #f4f4f4; font-family: 'Arial', sans-serif; -webkit-font-smoothing: antialiased; }
        table { border-collapse: collapse; width: 100%; }
        
        /* Contenedor Principal */
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f4;
            padding-bottom: 40px;
        }
        
        .main-content {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        /* Header */
        .header {
            background-color: #02115A;
            padding: 30px 20px;
            text-align: center;
        }
        .header img {
            max-width: 150px;
            height: auto;
        }

        /* Body */
        .content-body {
            padding: 40px 30px;
            color: #333333;
        }
        
        h1 {
            color: #02115A;
            font-size: 24px;
            margin: 0 0 20px 0;
            text-align: center;
            font-weight: bold;
        }
        
        p {
            font-size: 16px;
            line-height: 1.5;
            margin: 0 0 20px 0;
            color: #555555;
            text-align: center;
        }

        /* Secciones de Datos */
        .data-section {
            background-color: #f8f9fa;
            border-left: 4px solid #F28C00; /* Naranja institucional */
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        
        .section-title {
            color: #02115A;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
        }

        .data-row {
            margin-bottom: 10px;
            font-size: 15px;
            display: flex;
            justify-content: space-between;
        }
        
        .label {
            color: #777;
            font-weight: bold;
            width: 40%;
        }
        
        .value {
            color: #333;
            width: 60%;
            font-weight: 500;
            text-align: right;
        }

        /* Footer */
        .footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            color: #999999;
            font-size: 12px;
        }
        
        .footer a {
            color: #F28C00;
            text-decoration: none;
        }

        /* Utilidad para móviles */
        @media screen and (max-width: 600px) {
            .content-body { padding: 20px; }
            .data-row { flex-direction: column; }
            .label, .value { width: 100%; text-align: left; }
            .value { margin-bottom: 10px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <center>
            <table class="main-content">
                <!-- Header Azul -->
                <tr>
                    <td class="header">
                        <!-- Logo con URL absoluta usando asset() -->
                        <img src="{{ asset('images/logo.png') }}" alt="ITCA Logo">
                    </td>
                </tr>

                <!-- Contenido -->
                <tr>
                    <td class="content-body">
                        <h1>¡Nuevo Lead Recibido!</h1>
                        <p>Un usuario ha completado el formulario de inscripción o consulta.</p>

                        <!-- Datos Personales -->
                        <div class="data-section">
                            <div class="section-title">Datos del Contacto</div>
                            
                            <div class="data-row">
                                <span class="label">Nombre:</span>
                                <span class="value">{{ $lead->nombre }} {{ $lead->apellido }}</span>
                            </div>
                            <div class="data-row">
                                <span class="label">DNI:</span>
                                <span class="value">{{ $lead->dni }}</span>
                            </div>
                            <div class="data-row">
                                <span class="label">Email:</span>
                                <span class="value"><a href="mailto:{{ $lead->correo }}" style="color:#02115A; text-decoration:none;">{{ $lead->correo }}</a></span>
                            </div>
                            <div class="data-row">
                                <span class="label">Teléfono:</span>
                                <span class="value">{{ $lead->telefono }}</span>
                            </div>
                        </div>

                        <!-- Datos del Curso -->
                        <div class="data-section" style="border-left-color: #02115A;">
                            <div class="section-title">Interés / Cursada</div>
                            
                            <div class="data-row">
                                <span class="label">Carrera:</span>
                                <span class="value">{{ $cursada->carrera ?? 'N/A' }}</span>
                            </div>
                            <div class="data-row">
                                <span class="label">ID Curso:</span>
                                <span class="value">{{ $cursada->ID_Curso ?? 'N/A' }}</span>
                            </div>
                            <div class="data-row">
                                <span class="label">Sede:</span>
                                <span class="value">{{ corregirNombreSede($cursada->sede ?? 'N/A') }}</span>
                            </div>
                            <div class="data-row">
                                <span class="label">Inicio:</span>
                                <span class="value">
                                    @if($cursada->Fecha_Inicio)
                                        {{ \Carbon\Carbon::parse($cursada->Fecha_Inicio)->format('d/m/Y') }}
                                    @else
                                        A confirmar
                                    @endif
                                </span>
                            </div>
                            <div class="data-row">
                                <span class="label">Días:</span>
                                <span class="value">{{ convertirDiasCompletos($cursada->xDias ?? '') ?: 'N/A' }}</span>
                            </div>
                            <div class="data-row">
                                <span class="label">Modalidad:</span>
                                <span class="value">{{ $cursada->xModalidad ?? 'N/A' }}</span>
                            </div>
                            <div class="data-row">
                                <span class="label">Régimen:</span>
                                <span class="value">{{ $cursada->Régimen ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <p style="font-size: 13px; color: #999; margin-top: 30px;">
                            Este lead ya ha sido guardado en la base de datos del sistema.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td class="footer">
                        &copy; {{ date('Y') }} ITCA - Instituto Tecnológico de Capacitación Automotriz.<br>
                        Sistema de Notificaciones Automáticas.
                    </td>
                </tr>
            </table>
        </center>
    </div>
</body>
</html>