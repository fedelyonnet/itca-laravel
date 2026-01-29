<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>Retomá tu Inscripción</title>
    <!-- Importar Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: light dark; supported-color-schemes: light dark; }
        body { font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #ffffff; }
        .container { max-width: 800px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-image { width: 100%; display: block; height: auto; }
        .content { padding: 40px 30px; }
        .btn { display: block; padding: 14px 28px; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center; width: 100%; box-sizing: border-box; font-size: 16px; margin: 25px 0; }
        .btn-primary { background-color: #1a56db; }
        .btn-secondary { background-color: #4b5563; margin-top: 10px; font-size: 14px; padding: 10px 20px;}
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #6b7280; background-color: #f9fafb; border-top: 1px solid #e5e7eb; }
        
        /* Grid System for Email */
        .two-columns { width: 100%; border-collapse: separate; border-spacing: 0; }
        .two-columns td { vertical-align: top; padding: 0 5px; }
        
        /* Desktop Alignment */
        .box-container { min-height: 485px; }

        @media only screen and (max-width: 600px) {
            .content { padding: 25px 15px !important; }
            .mobile-stack { display: block !important; width: 100% !important; padding-left: 0 !important; padding-right: 0 !important; }
            .mobile-hide { display: none !important; }
            .box-container { min-height: 0 !important; margin-bottom: 20px !important; }
            

            /* Responsive Grid for Characteristics */
            .responsive-col { width: 50% !important; }
            
            /* Smaller fonts for Years table on mobile */
            .text-year { font-size: 16px !important; }
            .text-download { font-size: 11px !important; margin-top: 7px !important; display: inline-block !important; }
            
            /* Smaller Total Text on Mobile */
            .text-total-label { font-size: 14px !important; }
            .text-total-value { font-size: 14px !important; }
            
            /* Smaller Invoice Rows on Mobile */
            .text-invoice-row { font-size: 14px !important; }
        }
        
        /* Grid System */
        .responsive-col { width: 33.32%; display: inline-block; vertical-align: top; }

        .data-box { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background-color: #ffffff; min-height: 420px; }
        .data-header { background-color: #034EFF; color: #ffffff; padding: 12px 15px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Montserrat', sans-serif; text-align: center; }
        .data-content { padding: 15px; }
        
        .data-item { display: flex; align-items: flex-start; margin-bottom: 12px; }
        .data-item:last-child { margin-bottom: 0; }
        .item-icon { width: 24px; min-width: 24px; height: 24px; margin-right: 12px; padding-top: 2px; text-align: center; vertical-align: top; }
        .item-info { flex: 1; }
        .item-label { font-family: 'Montserrat', sans-serif; font-size: 11px; color: #010c42; text-transform: uppercase; font-weight: 500; line-height: 1.2; margin-bottom: 2px; }
        .item-value { font-family: 'Montserrat', sans-serif; font-size: 10px; color: #034eff; font-weight: 600; line-height: 1.4; }

        /* Dark Mode Support Overrides */
        @media (prefers-color-scheme: dark) {
            body, .container { background-color: #ffffff !important; }
            .force-light-bg { background-color: #ffffff !important; }
            .force-light-text { color: #010C42 !important; }
            .force-header-blue { background-color: #034EFF !important; color: #ffffff !important; }
        }
        /* Outlook Dark Mode Override */
        [data-ogsc] .force-light-bg { background-color: #ffffff !important; }
        [data-ogsc] .force-light-text { color: #010C42 !important; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Image (Dynamic) -->
        <div class="header-container" style="background-color: #1a56db;">
            @if(isset($mailTemplate) && $mailTemplate->header_image && file_exists(storage_path('app/public/' . $mailTemplate->header_image)))
                <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->header_image)) }}" alt="ITCA" class="header-image">
            @else
                <!-- Fallback Header if no custom image is set or file missing -->
                <div style="padding: 30px; text-align: center;">
                    <h1 style="color: #ffffff; margin: 0; font-size: 24px;">¡Tu futuro te espera en ITCA!</h1>
                </div>
            @endif
        </div>

        <div class="content">
            <div style="text-align: center; margin-bottom: 20px;">
                <span style="font-family: 'Montserrat', sans-serif; font-weight: 500; font-size: 17px; color: #010C42;">Hola </span>
                <span style="font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 17px; color: #034EFF;">{{ $lead->nombre }}:</span>
            </div>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 400; font-size: 12px; color: #010C42; margin-bottom: 0; text-align: center;">
                Queremos acompañarte en tu proceso de inscripción.<br>
                No dejes pasar esta oportunidad, ¡quedan pocos cupos disponibles!
            </p>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 400; font-size: 10px; color: #010C42; margin-top: 20px; margin-bottom: 30px; text-align: center;">
                Te presentamos <span style="color: #034EFF;">un resumen de tu elección</span> para que puedas continuar:
            </p>

            <!-- Two Columns Section -->
            <table class="two-columns" role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <!-- Left Column: Course Details -->
                    <td class="mobile-stack" style="vertical-align: top; width: 50%; padding-right: 5px;">
                        <table class="box-container" width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff; overflow: hidden; border-spacing: 0; height: 100%;">
                            <!-- Header -->
                            <tr>
                                <td height="30" style="background-color: #034EFF; color: #ffffff; padding: 12px 15px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Montserrat', sans-serif; text-align: center; vertical-align: middle;">
                                    DETALLE DE LA CARRERA
                                </td>
                            </tr>
                            <!-- Content -->
                            <tr>
                                <td valign="top" height="100%" style="padding: 20px 10px; height: 100%;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <!-- Carrera -->
                                        <tr>
                                            <td width="24" valign="top" style="padding-right: 12px; padding-bottom: 20px;">
                                                <div style="width: 20px; height: 20px; text-align: center; line-height: 20px;">
                                                    <img src="{{ $message->embed(public_path('images/lead_mails/wrench.png')) }}" alt="Carrera" style="max-width: 20px; max-height: 20px; width: auto; height: auto; display: inline-block; vertical-align: middle; border: 0;">
                                                </div>
                                            </td>
                                            <td valign="top" style="padding-bottom: 12px;">
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #010c42; font-weight: 600; line-height: 1.2; margin-bottom: 4px;">Carrera</div>
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 13px; color: #034eff; font-weight: 600; line-height: 1.4;">{{ $cursada->carrera }}</div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Sede -->
                                        <tr>
                                            <td width="24" valign="top" style="padding-right: 12px; padding-bottom: 12px;">
                                                <div style="width: 20px; height: 20px; text-align: center; line-height: 20px;">
                                                    <img src="{{ $message->embed(public_path('images/lead_mails/location.png')) }}" alt="Sede" style="max-width: 20px; max-height: 20px; width: auto; height: auto; display: inline-block; vertical-align: middle; border: 0;">
                                                </div>
                                            </td>
                                            <td valign="top" style="padding-bottom: 12px;">
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #010c42; font-weight: 600; line-height: 1.2; margin-bottom: 4px;">Sede</div>
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 13px; color: #034eff; font-weight: 600; line-height: 1.4;">{{ $cursada->sede }}</div>
                                            </td>
                                        </tr>

                                        <!-- Modalidad -->
                                        <tr>
                                            <td width="24" valign="top" style="padding-right: 12px; padding-bottom: 12px;">
                                                <div style="width: 20px; height: 20px; text-align: center; line-height: 20px;">
                                                    <img src="{{ $message->embed(public_path('images/lead_mails/gear.png')) }}" alt="Modalidad" style="max-width: 20px; max-height: 20px; width: auto; height: auto; display: inline-block; vertical-align: middle; border: 0;">
                                                </div>
                                            </td>
                                            <td valign="top" style="padding-bottom: 12px;">
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #010c42; font-weight: 600; line-height: 1.2; margin-bottom: 4px;">Modalidad</div>
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 13px; color: #034eff; font-weight: 600; line-height: 1.4;">{{ $cursada->xModalidad }}</div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Turno -->
                                        <tr>
                                            <td width="24" valign="top" style="padding-right: 12px; padding-bottom: 12px; min-width: 24px;">
                                                <div style="width: 20px; height: 20px; text-align: center; line-height: 20px;">
                                                    <img src="{{ $message->embed(public_path('images/lead_mails/clock.png')) }}" alt="Turno" style="max-width: 20px; max-height: 20px; width: auto; height: auto; display: inline-block; vertical-align: middle; border: 0;">
                                                </div>
                                            </td>
                                            <td valign="top" style="padding-bottom: 12px;">
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #010c42; font-weight: 600; line-height: 1.2; margin-bottom: 4px;">Turno</div>
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 13px; color: #034eff; font-weight: 600; line-height: 1.4;">{{ $cursada->xTurno }}</div>
                                            </td>
                                        </tr>

                                        <!-- Día -->
                                        <tr>
                                            <td width="24" valign="top" style="padding-right: 12px; padding-bottom: 12px;">
                                                <div style="width: 20px; height: 20px; text-align: center; line-height: 20px;">
                                                    <img src="{{ $message->embed(public_path('images/lead_mails/calendar.png')) }}" alt="Día" style="max-width: 20px; max-height: 20px; width: auto; height: auto; display: inline-block; vertical-align: middle; border: 0;">
                                                </div>
                                            </td>
                                            <td valign="top" style="padding-bottom: 12px;">
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #010c42; font-weight: 600; line-height: 1.2; margin-bottom: 4px;">Día de Cursada</div>
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 13px; color: #034eff; font-weight: 600; line-height: 1.4;">{{ $cursada->xDias }}</div>
                                            </td>
                                        </tr>

                                        <!-- Horario -->
                                        <tr>
                                            <td width="24" valign="top" style="padding-right: 12px;">
                                                <div style="width: 20px; height: 20px; text-align: center; line-height: 20px;">
                                                    <img src="{{ $message->embed(public_path('images/lead_mails/clock.png')) }}" alt="Horario" style="max-width: 20px; max-height: 20px; width: auto; height: auto; display: inline-block; vertical-align: middle; border: 0;">
                                                </div>
                                            </td>
                                            <td valign="top">
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #010c42; font-weight: 600; line-height: 1.2; margin-bottom: 4px;">Horario</div>
                                                <div style="font-family: 'Montserrat', sans-serif; font-size: 13px; color: #034eff; font-weight: 600; line-height: 1.4;">{{ $cursada->Horario }} hs</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- Button Row (Bottom Aligned) -->
                            <tr>
                                <td valign="bottom" style="padding: 15px; border-top: 1px solid #e2e8f0;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td align="center">
                                                <a href="{{ route('carreras') }}" style="display: block; width: 100%; text-decoration: none; background-color: #034EFF; color: #ffffff; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 12px; border: 1px solid #010C42; border-radius: 21px; padding: 12px 0; text-align: center; box-sizing: border-box; text-transform: uppercase;">
                                                    MODIFICAR MI ELECCIÓN
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    
                    <!-- Right Column: Budget Information -->
                    <td class="mobile-stack" style="vertical-align: top; width: 50%; padding-left: 5px; height: 100%;">
                        <table class="box-container" width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff; overflow: hidden; border-spacing: 0; height: 100%;">
                            <!-- Header -->
                            <tr>
                                <td height="30" style="background-color: #02115A; color: #ffffff; padding: 12px 15px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Montserrat', sans-serif; text-align: center; vertical-align: middle;">
                                    PRESUPUESTO
                                </td>
                            </tr>
                            <!-- Content: dynamic text -->
                            <tr>
                                <td valign="top" height="100%" style="padding: 0; height: 100%;">
                                    <div style="padding: 20px 15px;">
                                        @php
                                            $ctaWeb = floatval($cursada->Cta_Web ?? 0);
                                            $dtoCuota = abs(floatval($cursada->Dto_Cuota ?? 0));
                                            $cuotas = intval($cursada->cuotas ?? 12);
                                            $sinIvaCta = floatval($cursada->Sin_IVA_cta ?? 0);
                                            $matricBase = floatval($cursada->Matric_Base ?? 0);
                                            $sinIvaMat = floatval($cursada->Sin_iva_Mat ?? 0);
                                            
                                            // Calcular descuento de matrícula si existe
                                            $descuentoMatricula = $matricBase - $sinIvaMat;
                                        @endphp
                                        
                                        <div style="margin-bottom: 20px;">
                                            <p style="font-family: 'Montserrat', sans-serif; margin: 0 0 5px 0; font-size: 13px; color: #4b5563;">
                                                El valor de cuota por mes te saldrá: <span style="color: #034EFF; font-weight: 700;">${{ number_format($ctaWeb, 2, ',', '.') }}*</span>
                                            </p>
                                            @if($dtoCuota > 0)
                                                <p style="font-family: 'Montserrat', sans-serif; margin: 0 0 10px 0; font-size: 13px; color: #034EFF; font-weight: 700; font-style: italic;">
                                                    ¡Descuento del {{ round($dtoCuota) }}% Aplicado!
                                                </p>
                                            @endif
                                            <p style="font-family: 'Montserrat', sans-serif; margin: 0 0 15px 0; font-size: 13px; color: #010C42; font-weight: 600;">
                                                Cantidad de cuotas: {{ $cuotas }}
                                            </p>
                                            
                                            <p style="font-family: 'Montserrat', sans-serif; margin: 0; font-size: 11px; color: #64748b;">
                                                Precio total sin impuestos nacionales: ${{ number_format($sinIvaCta, 2, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Enrollment Fee Box (Full Width, Bottom Aligned) -->
                            <tr>
                                <td valign="bottom" style="padding: 0;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #020f3a;">
                                        <tr>
                                            <td style="padding: 25px 20px;">
                                                <p style="margin: 0 0 20px 0; font-size: 14px; color: #ffffff; line-height: 1.5; text-align: center;">
                                                    Ahora solo debés <span style="color: #2DFB89; font-weight: bold;">pagar la matrícula</span><br>para poder reservar tu vacante
                                                </p>
                                                
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="text-invoice-row" style="color: #ffffff; font-size: 14px; font-family: 'Montserrat', sans-serif; padding-bottom: 8px;">Valor de Matrícula:</td>
                                                        <td class="text-invoice-row" style="color: #ffffff; font-size: 16px; font-weight: 700; font-family: 'Montserrat', sans-serif; text-align: right; padding-bottom: 8px;">${{ number_format($matricBase, 2, ',', '.') }}</td>
                                                    </tr>
                                                    @if($descuentoMatricula > 0)
                                                    <tr>
                                                        <td class="text-invoice-row" style="color: #cbd5e1; font-size: 13px; font-style: italic; font-family: 'Montserrat', sans-serif; padding-bottom: 15px;">Descuento por promo:</td>
                                                        <td class="text-invoice-row" style="color: #ffffff; font-size: 14px; font-family: 'Montserrat', sans-serif; text-align: right; padding-bottom: 15px;">-${{ number_format($descuentoMatricula, 2, ',', '.') }}</td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                    <tr>
                                                        <td class="text-total-label" style="color: #2DFB89; font-size: 18px; font-weight: 600; font-family: 'Montserrat', sans-serif;">Total:</td>
                                                        <td class="text-total-value" style="color: #2DFB89; font-size: 24px; font-weight: 700; font-family: 'Montserrat', sans-serif; text-align: right;">${{ number_format($sinIvaMat, 2, ',', '.') }}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <!-- Button Row (Bottom Aligned) - Outside the dark box, in the white footer area -->
                            <tr>
                                <td valign="bottom" style="padding: 15px; border-top: 1px solid #e2e8f0; background-color: #ffffff;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td align="center">
                                                <a href="{{ route('abandoned.recover', $token) }}" style="display: block; width: 100%; text-decoration: none; background-color: #2DFB89; color: #000000; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 12px; border: 1px solid #000000; border-radius: 21px; padding: 12px 0; text-align: center; box-sizing: border-box; text-transform: uppercase;">
                                                    Ir a pagar matrícula
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- SECCIÓN DE DESCARGA DE PROGRAMAS -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 30px; border: 1px solid #02115A; border-radius: 8px; overflow: hidden; border-spacing: 0;">
                <!-- Cabecera Blanca -->
                <tr>
                    <td align="center" style="padding: 16px 10px; background-color: #ffffff; border-bottom: 2px solid #02115A;">
                        <p style="font-family: 'Montserrat', sans-serif; font-size: 14px; margin: 0; color: #010C42; font-weight: 500; line-height: 1.2;">
                            <span style="color: #034EFF; font-weight: 700;">Descargá los programas</span> y descubrí los contenidos de cada año:
                        </p>
                    </td>
                </tr>
                <!-- Fila de Años (Fondo Oscuro) -->
                <tr>
                    <td style="background-color: #020f3a; padding: 0;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <!-- 1° AÑO -->
                                <td width="33.3%" align="center" valign="top" style="padding: 22px 5px; border-right: 1px solid rgba(255,255,255,0.15);">
                                    <div style="height: 45px; vertical-align: middle;">
                                        <p class="text-year" style="font-family: 'Montserrat', sans-serif; font-size: 24px; font-weight: 800; color: #ffffff; margin: 0;">1° AÑO</p>
                                    </div>
                                    @if($mailTemplate && $mailTemplate->syllabus_year_1)
                                        <a href="{{ url('storage/' . $mailTemplate->syllabus_year_1) }}" class="text-download" style="display: inline-block; font-family: 'Montserrat', sans-serif; font-size: 13px; color: #2DFB89; font-weight: 700; text-decoration: underline; margin-top: 7px;">Descargar</a>
                                    @else
                                        <span class="text-download" style="display: inline-block; font-family: 'Montserrat', sans-serif; font-size: 11px; color: #94a3b8; font-style: italic; margin-top: 7px;">Próximamente</span>
                                    @endif
                                </td>
                                
                                <!-- 2° AÑO -->
                                <td width="33.3%" align="center" valign="top" style="padding: 22px 5px; border-right: 1px solid rgba(255,255,255,0.15);">
                                    <div style="height: 45px; vertical-align: middle;">
                                        <p class="text-year" style="font-family: 'Montserrat', sans-serif; font-size: 24px; font-weight: 800; color: #ffffff; margin: 0;">2° AÑO</p>
                                    </div>
                                    @if($mailTemplate && $mailTemplate->syllabus_year_2)
                                        <a href="{{ url('storage/' . $mailTemplate->syllabus_year_2) }}" class="text-download" style="display: inline-block; font-family: 'Montserrat', sans-serif; font-size: 13px; color: #2DFB89; font-weight: 700; text-decoration: underline; margin-top: 7px;">Descargar</a>
                                    @else
                                        <span class="text-download" style="display: inline-block; font-family: 'Montserrat', sans-serif; font-size: 11px; color: #94a3b8; font-style: italic; margin-top: 7px;">Próximamente</span>
                                    @endif
                                </td>
                                
                                <!-- 3° AÑO -->
                                <td width="33.3%" align="center" valign="top" style="padding: 22px 5px;">
                                    <div style="height: 45px; vertical-align: middle;">
                                        <p class="text-year" style="font-family: 'Montserrat', sans-serif; font-size: 24px; font-weight: 800; color: #ffffff; margin: 0;">3° AÑO</p>
                                        <p style="font-family: 'Montserrat', sans-serif; font-size: 9px; color: #ffffff; opacity: 0.8; margin: 0;">Especialización opcional</p>
                                    </div>
                                    @if($mailTemplate && $mailTemplate->syllabus_year_3)
                                        <a href="{{ url('storage/' . $mailTemplate->syllabus_year_3) }}" class="text-download" style="display: inline-block; font-family: 'Montserrat', sans-serif; font-size: 13px; color: #2DFB89; font-weight: 700; text-decoration: underline; margin-top: 7px;">Descargar</a>
                                    @else
                                        <span class="text-download" style="display: inline-block; font-family: 'Montserrat', sans-serif; font-size: 11px; color: #94a3b8; font-style: italic; margin-top: 7px;">Próximamente</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            @if($modalidadTipo)
            <!-- TÍTULO DE MODALIDAD -->
            <div style="margin-top: 35px; margin-bottom: 20px; text-align: center;">
                <p style="font-family: 'Montserrat', sans-serif; font-size: 15px; color: #010C42; font-weight: 500; margin: 0;">
                    Nuestra cursada <span style="color: #034EFF; font-weight: 700;">{{ $cursada->xModalidad }} ({{ $cursada->Régimen }})</span> presenta las siguientes características:
                </p>
            </div>

            @php
                $features = [
                    ['icon' => 'mod_clock.png', 'label' => 'Duración', 'value' => $modalidadTipo->duracion, 'color' => '#034EFF'],
                    ['icon' => 'mod_gear.png', 'label' => 'Dedicación', 'value' => $modalidadTipo->dedicacion, 'color' => '#034EFF'],
                    ['icon' => 'mod_student.png', 'label' => 'Clases', 'value' => $modalidadTipo->clases_semana, 'color' => '#034EFF'],
                    ['icon' => 'mod_wrench.png', 'label' => 'Teórica y Práctica', 'value' => '', 'is_tp' => true, 'color' => '#034EFF'],
                    ['icon' => 'mod_calendar.png', 'label' => 'Mes de inicio', 'value' => $modalidadTipo->mes_inicio . ' ' . $cursada->Fecha_Inicio->format('Y'), 'color' => '#034EFF'],
                    ['icon' => 'mod_book.png', 'label' => 'Incluye', 'value' => 'Herramientas, insumos y maquetas para prácticas', 'color' => '#034EFF']
                ];
                $count = count($features);
            @endphp


            <!-- CARACTERÍSTICAS DE LA MODALIDAD -->
            <div style="margin-top: 30px; margin-bottom: 30px; text-align: center; font-size: 0;">
                <!--[if mso]>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                <![endif]-->
                @foreach($features as $i => $f)
                <!--[if mso]>
                <td width="33%" valign="top" style="padding: 5px;">
                <![endif]-->
                <div class="responsive-col">
                    <div style="padding: 5px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border: 2px solid {{ $f['color'] ?? '#034EFF' }}; border-radius: 8px; overflow: hidden; height: 100%;">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="middle" bgcolor="{{ $f['color'] ?? '#034EFF' }}" style="padding: 12px 5px; height: 50px;" class="force-header-blue">
                                    <img src="{{ $message->embed(public_path('images/lead_mails/' . $f['icon'])) }}" alt="{{ $f['label'] }}" width="20" height="20" style="display: block; margin: 0 auto 6px auto; border: 0;">
                                    <span style="font-family: 'Montserrat', sans-serif; font-size: 9px; font-weight: 700; color: #ffffff; text-transform: uppercase; line-height: 1.2; display: block;">{{ $f['label'] }}</span>
                                </td>
                            </tr>
                            <!-- Body -->
                            <tr>
                                <td align="center" valign="middle" style="padding: 12px 5px; height: 80px;">
                                    <span style="font-family: 'Montserrat', sans-serif; font-size: 11px; color: #010C42; font-weight: 600; line-height: 1.3; display: block;">
                                        @if(isset($f['is_tp']))
                                            @php
                                                $tp = trim($modalidadTipo->teoria_practica ?? '');
                                                $ht = trim($modalidadTipo->horas_teoria ?? '');
                                                $hp = trim($modalidadTipo->horas_practica ?? '');
                                                $hv = trim($modalidadTipo->horas_virtuales ?? '');
                                                $labelTP = $tp ?: (($hp && ($ht ?: $hv)) ? $hp . "<br>" . ($ht ?: $hv) : ($hp ?: ($ht ?: $hv)));
                                            @endphp
                                            {!! $labelTP ?: 'Consultar' !!}
                                        @else
                                            {{ $f['value'] }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!--[if mso]>
                </td>
                <![endif]-->
                @if(($i + 1) % 3 == 0 && !$loop->last)
                <!--[if mso]>
                </tr><tr>
                <![endif]-->
                @endif
                @endforeach
                <!--[if mso]>
                </tr>
                </table>
                <![endif]-->
            </div>
            @endif

            <!-- Certificación Text -->
            <div style="margin-top: 35px; margin-bottom: 20px; text-align: center;">
                <p style="font-family: 'Montserrat', sans-serif; font-size: 15px; color: #010C42; font-weight: 500; margin: 0;">
                    Al finalizar la cursada, obtendrás tu <span style="color: #034EFF; font-weight: 700;">certificación</span>, que acreditará todo lo aprendido:
                </p>
            </div>

            <!-- Certification Images (Two Columns / Stacked on Mobile) -->
            @if(isset($mailTemplate) && ($mailTemplate->main_illustration || $mailTemplate->certificate_image))
            <table class="two-columns" role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 10px;">
                <tr>
                    <!-- Ilustración Principal -->
                    <td class="mobile-stack" align="center" valign="top" style="width: 50%; padding: 10px;">
                        @if($mailTemplate->main_illustration && file_exists(storage_path('app/public/' . $mailTemplate->main_illustration)))
                            <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->main_illustration)) }}" alt="Ilustración" style="max-width: 100%; height: auto; display: block; border-radius: 8px;">
                        @endif
                    </td>
                    <!-- Certificado -->
                    <td class="mobile-stack" align="center" valign="top" style="width: 50%; padding: 10px;">
                        @if($mailTemplate->certificate_image && file_exists(storage_path('app/public/' . $mailTemplate->certificate_image)))
                            <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->certificate_image)) }}" alt="Certificado" style="max-width: 100%; height: auto; display: block; border-radius: 8px; border: 2px solid #000000;">
                        @endif
                    </td>
                </tr>
            </table>
            @endif

            <!-- UTN Banner -->
            <div style="margin-top: 20px; text-align: center;">
                @if(isset($mailTemplate->utn_banner_image) && file_exists(storage_path('app/public/' . $mailTemplate->utn_banner_image)))
                    <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->utn_banner_image)) }}" alt="Certificación UTN" style="width: 100%; max-width: 100%; height: auto; display: block; border-radius: 8px;">
                @endif
            </div>

            <!-- BENEFICIOS EXCLUSIVOS -->
            @if(isset($mailTemplate))
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 30px; border: 1px solid #1a1a1a; border-radius: 8px; overflow: hidden; border-spacing: 0; margin-bottom: 20px;">
                <!-- Header -->
                <tr>
                    <td align="center" bgcolor="#4000ff" style="padding: 10px; background-color: #4000ff; color: #ffffff; font-family: 'Montserrat', sans-serif; font-weight: 700; text-transform: uppercase; font-size: 14px;">
                        BENEFICIOS EXCLUSIVOS
                    </td>
                </tr>
                <!-- Content -->
                <tr>
                    <td align="center" style="padding: 20px 10px; background-color: #ffffff;">
                        <p style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #010C42; margin: 0 0 20px 0; line-height: 1.4;">
                            Al inscribirte hoy, accedes a <span style="color: #4000ff; font-weight: 700;">múltiples ventajas</span>.<br>
                            Estas son solo algunas de ellas:
                        </p>
                        
                        <!-- Grid Container -->
                        <div style="text-align: center; font-size: 0;">
                            <!-- Beneficio 1 -->
                            <div style="display: inline-block; width: 23%; vertical-align: top; margin: 0 1% 10px 1%; min-width: 130px;">
                                <div style="border: 1px solid #1a1a1a; border-radius: 6px; overflow: hidden;">
                                    <table width="100%" height="40" cellpadding="0" cellspacing="0" border="0" bgcolor="#4000ff">
                                        <tr>
                                            <td align="center" valign="middle" style="color: #ffffff; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; padding: 0 2px; text-align: center;">
                                                Club ITCA
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="background-color: #ffffff; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if($mailTemplate->benefit_1_image && file_exists(storage_path('app/public/' . $mailTemplate->benefit_1_image)))
                                            <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->benefit_1_image)) }}" alt="Club ITCA" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Beneficio 2 -->
                            <div style="display: inline-block; width: 23%; vertical-align: top; margin: 0 1% 10px 1%; min-width: 130px;">
                                <div style="border: 1px solid #1a1a1a; border-radius: 6px; overflow: hidden;">
                                    <table width="100%" height="40" cellpadding="0" cellspacing="0" border="0" bgcolor="#4000ff">
                                        <tr>
                                            <td align="center" valign="middle" style="color: #ffffff; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; padding: 0 2px; text-align: center;">
                                                Productos y<br>herramientas
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="background-color: #ffffff; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if($mailTemplate->benefit_2_image && file_exists(storage_path('app/public/' . $mailTemplate->benefit_2_image)))
                                            <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->benefit_2_image)) }}" alt="Productos" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Beneficio 3 -->
                            <div style="display: inline-block; width: 23%; vertical-align: top; margin: 0 1% 10px 1%; min-width: 130px;">
                                <div style="border: 1px solid #1a1a1a; border-radius: 6px; overflow: hidden;">
                                    <table width="100%" height="40" cellpadding="0" cellspacing="0" border="0" bgcolor="#4000ff">
                                        <tr>
                                            <td align="center" valign="middle" style="color: #ffffff; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; padding: 0 2px; text-align: center;">
                                                Bolsa Laboral
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="background-color: #ffffff; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if($mailTemplate->benefit_3_image && file_exists(storage_path('app/public/' . $mailTemplate->benefit_3_image)))
                                            <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->benefit_3_image)) }}" alt="Bolsa Laboral" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Beneficio 4 -->
                            <div style="display: inline-block; width: 23%; vertical-align: top; margin: 0 1% 10px 1%; min-width: 130px;">
                                <div style="border: 1px solid #1a1a1a; border-radius: 6px; overflow: hidden;">
                                    <table width="100%" height="40" cellpadding="0" cellspacing="0" border="0" bgcolor="#4000ff">
                                        <tr>
                                            <td align="center" valign="middle" style="color: #ffffff; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; padding: 0 2px; text-align: center;">
                                                Charlas y<br>Visitas Técnicas
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="background-color: #ffffff; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if($mailTemplate->benefit_4_image && file_exists(storage_path('app/public/' . $mailTemplate->benefit_4_image)))
                                            <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->benefit_4_image)) }}" alt="Charlas" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            @endif

            <!-- Partners -->
            <div style="margin-top: 35px; margin-bottom: 20px; text-align: center;">
                <p style="font-family: 'Montserrat', sans-serif; font-size: 15px; color: #010C42; font-weight: 500; margin: 0;">
                    Nos acompañan en la <span style="color: #034EFF; font-weight: 700;">formación profesional</span>:
                </p>
            </div>

            <div style="margin-bottom: 30px; text-align: center;">
                @if(isset($mailTemplate->partners_image) && file_exists(storage_path('app/public/' . $mailTemplate->partners_image)))
                    <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->partners_image)) }}" alt="Partners" style="width: 100%; height: auto; display: block; border-radius: 8px;">
                @endif
            </div>

            <!-- VISITA INSTITUTO -->
            <div style="margin-top: 35px; margin-bottom: 20px; text-align: center;">
                <p style="font-family: 'Montserrat', sans-serif; font-size: 15px; color: #010C42; font-weight: 500; margin: 0; line-height: 1.4;">
                    Te invitamos a <span style="color: #034EFF; font-weight: 700;">visitar nuestro instituto</span>, recorrer los talleres, conocer la dinámica<br>
                    de trabajo de los alumnos y recibir asesoramiento personalizado.
                </p>
            </div>

            <!-- Grid Ilustraciones 2-5 -->
            <div style="text-align: center; font-size: 0; margin-bottom: 30px;">
                @foreach(['illustration_2', 'illustration_3', 'illustration_4', 'illustration_5'] as $imgField)
                    <div style="display: inline-block; width: 23%; vertical-align: top; margin: 0 1% 10px 1%; min-width: 130px;">
                        @if(isset($mailTemplate->$imgField) && file_exists(storage_path('app/public/' . $mailTemplate->$imgField)))
                            <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->$imgField)) }}" alt="Ilustración" style="width: 100%; height: auto; display: block; border-radius: 8px; border: 1px solid #010C42;">
                        @endif
                    </div>
                @endforeach
            </div>

            @php
                $whatsappData = \App\Models\DatoContacto::where('descripcion', 'WhatsApp')->first();
                $telData = \App\Models\DatoContacto::where('descripcion', 'Tel')->first();
                $mailData = \App\Models\DatoContacto::where('descripcion', 'Mail')->first();
                
                $waNumber = $whatsappData ? $whatsappData->contenido : '5491126875535';
                $waLink = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $waNumber);
                
                $telText = $telData ? $telData->contenido : '0810-220-4822';
                $mailText = $mailData ? $mailData->contenido : 'info@itca.com.ar';
            @endphp

            <!-- CONTACTO CTA -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 30px; border: 1px solid #010C42; border-radius: 8px; overflow: hidden; border-collapse: separate;">
                <tr>
                    <!-- DATOS -->
                    <td class="mobile-stack" width="33%" align="center" valign="middle" style="padding: 15px; border-right: 1px solid #010C42;">
                        <p style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #010C42; font-weight: 500; margin: 0; line-height: 1.4;">
                            <span style="font-style: italic;">{{ $mailText }}</span><br>
                            <span style="font-weight: 700;">{{ $telText }}</span>
                        </p>
                    </td>
                    <!-- WHATSAPP -->
                    <td class="mobile-stack" width="33%" align="center" valign="middle" style="padding: 15px; border-right: 1px solid #010C42;">
                        <a href="{{ $waLink }}" target="_blank" style="display: inline-block; padding: 10px 15px; background-color: #4000ff; color: #ffffff; text-decoration: none; border-radius: 20px; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; border: 1px solid #4000ff;">
                            @if(file_exists(public_path('images/lead_mails/wa.png')))
                                <img src="{{ $message->embed(public_path('images/lead_mails/wa.png')) }}" alt="WhatsApp" style="width: 18px; height: auto; vertical-align: middle; margin-right: 5px; border: 0; image-rendering: auto;">
                            @endif
                            <span style="vertical-align: middle;">Agendar Entrevista</span>
                        </a>
                    </td>
                    <!-- INSCRIBIRME -->
                    <td class="mobile-stack" width="33%" align="center" valign="middle" style="padding: 15px;">
                        <a href="{{ url('/carreras') }}" target="_blank" style="display: inline-block; padding: 10px 15px; background-color: #2DFB89; color: #010C42; text-decoration: none; border-radius: 20px; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; border: 1px solid #2DFB89;">
                            ¡Inscribirme ahora!
                        </a>
                    </td>
                </tr>
            </table>

            <!-- LEGALES -->
            <div style="margin-top: 20px; text-align: center; padding-top: 15px;">
                <p style="font-family: 'Montserrat', sans-serif; font-size: 10px; color: #64748b; margin: 0; line-height: 1.4; font-style: italic;">
                    *Valor de cuota actual, vigente hasta el fin del presente mes. Los valores de cuotas se ajustan y actualizan mes a mes según IPC publicado por el INDEC. Cuotas totales a abonar en el 1er año: 10. Consultar por promociones y descuentos aplicables sobre adelantamientos de cuotas.
                </p>
            </div>


        </div>
        @if(isset($mailTemplate->bottom_image) && file_exists(storage_path('app/public/' . $mailTemplate->bottom_image)))
            <div class="footer" style="padding: 0; background-color: transparent; border: none;">
                <img src="{{ $message->embed(storage_path('app/public/' . $mailTemplate->bottom_image)) }}" alt="ITCA" style="width: 100%; height: auto; display: block; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
            </div>
        @endif
    </div>
</body>
</html>
