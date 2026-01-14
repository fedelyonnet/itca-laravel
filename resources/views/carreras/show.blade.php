<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="{{ $curso->nombre }} - ITCA">
    <title>{{ $curso->nombre }} - ITCA</title>
    <!-- Fonts (match home and carreras) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">
    @vite(['resources/css/public.css', 'resources/js/app.js'])
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body>
    <!-- Sticky Bar -->
    @if($stickyBar && $stickyBar->visible == true)
    <div class="sticky-bar" @style(['background-color: ' . $stickyBar->color . ' !important'])>
        <div class="container">
            <div class="sticky-bar-content">
                <div class="sticky-bar-text-container">
                    <span class="sticky-bar-text">{!! $stickyBar->getFormattedTextAttribute() !!}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <header class="header {{ $stickyBar && $stickyBar->visible == true ? 'header-with-sticky' : '' }}">
        <div class="container">
            <nav class="nav">
                <!-- Logo -->
                <a href="/" class="logo">ITCA</a>
                <!-- Desktop Navigation -->
                <ul class="nav-links">
                    <li>
                        <a href="{{ url('/') }}" class="nav-link">
                            Somos ITCA
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('carreras') }}" class="nav-link active">
                            Carreras
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#beneficios') }}" class="nav-link">
                            Beneficios
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#contacto') }}" class="nav-link">
                            Contacto
                        </a>
                    </li>
                </ul>
                <!-- Mobile Hamburger Button -->
                <button class="hamburger"><span></span><span></span><span></span></button>
            </nav>
        </div>
    </header>

    <main>
        <!-- Header lista-carreras con breadcrumb (sin título) -->
        <section class="lista-carreras lista-carreras--show carrera-show-header">
            <div class="lista-carreras-container carrera-show-container">
                <div class="lista-carreras-header carrera-show-header-inner">
                    <div class="lista-carreras-breadcrumb carrera-show-breadcrumb">
                        <a href="/" class="lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">Inicio</a>
                        <span class="lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>
                        <a href="/carreras" class="lista-carreras-breadcrumb-link carrera-show-breadcrumb-link">Carreras</a>
                        <span class="lista-carreras-breadcrumb-separator carrera-show-breadcrumb-separator">></span>
                        <span class="lista-carreras-breadcrumb-current carrera-show-breadcrumb-current">
                            @php
                                $nombre = $curso->nombre;
                                $palabras = explode(' ', $nombre);
                                $mitad = ceil(count($palabras) / 2);
                                $primeraParte = implode(' ', array_slice($palabras, 0, $mitad));
                                $segundaParte = implode(' ', array_slice($palabras, $mitad));
                            @endphp
                            <span class="carrera-show-breadcrumb-part1">{{ $primeraParte }}</span>
                            @if($segundaParte)
                                <span class="carrera-show-breadcrumb-part2">{{ $segundaParte }}</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido de la carrera (estilos propios del show) -->
        <section class="carrera-show-section">
            <div class="lista-carreras-container carrera-show-body-container">
                <div class="carrera-container carrera-show-container carrera-{{ Str::slug($curso->nombre) }}">
                    <div class="carrera-left carrera-show-left">
                        <div class="carrera-title carrera-show-title">
                            <h3>
                                <div class="carrera-title-line1 carrera-show-title-line1">Carrera de</div>
                                <div class="carrera-title-line2 carrera-show-title-line2">
                                    @php
                                        $palabras = explode(' ', $curso->nombre);
                                        $ultimaPalabra = array_pop($palabras);
                                        $resto = implode(' ', $palabras);
                                    @endphp
                                    {{ $resto }} <span class="carrera-title-highlight carrera-show-title-highlight">{{ $ultimaPalabra }}</span>
                                </div>
                            </h3>
                        </div>
                        <div class="carrera-text carrera-show-text">
                            <p>{{ $curso->descripcion }}</p>
                        </div>
                        <div class="carrera-buttons carrera-show-buttons">
                            <img src="/images/desktop/arrow.png" alt="Flecha" class="carrera-btn-arrow carrera-show-btn-arrow">
                            <a href="#contacto" class="carrera-show-cta">
                                <span class="carrera-show-cta-line1">Aprovechá esta oportunidad</span>
                                <span class="carrera-show-cta-line2">y asegurá tu lugar</span>
                            </a>
                            <a href="{{ route('inscripcion', $curso->id) }}" class="carrera-btn-inscribir carrera-show-btn-inscribir">¡Inscribirme ahora!</a>
                        </div>
                    </div>
                    <div class="carrera-right carrera-show-right">
                        <div class="carrera-image carrera-show-image">
                            @if($curso->imagen_show_desktop)
                                <img src="{{ asset('storage/' . $curso->imagen_show_desktop) }}" alt="{{ $curso->nombre }}" class="carreras-image-desktop carrera-show-image-desktop" />
                            @elseif($curso->ilustracion_desktop)
                                <img src="{{ asset('storage/' . $curso->ilustracion_desktop) }}" alt="{{ $curso->nombre }}" class="carreras-image-desktop carrera-show-image-desktop" />
                            @endif
                            @if($curso->imagen_show_mobile)
                                <img src="{{ asset('storage/' . $curso->imagen_show_mobile) }}" alt="{{ $curso->nombre }}" class="carreras-image-mobile carrera-show-image-mobile" />
                            @elseif($curso->ilustracion_mobile)
                                <img src="{{ asset('storage/' . $curso->ilustracion_mobile) }}" alt="{{ $curso->nombre }}" class="carreras-image-mobile carrera-show-image-mobile" />
                            @endif
                            <div class="carreras-modalidad-badge carreras-modalidad-badge-desktop carrera-show-modalidad-badge">
                                <span class="carreras-modalidad-text carrera-show-modalidad-text">Modalidad: <strong>
                                    @if($curso->modalidad_online && $curso->modalidad_presencial)
                                        Presencial / Online
                                    @elseif($curso->modalidad_presencial)
                                        Presencial
                                    @else
                                        Online
                                    @endif
                                </strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Requisitos Section (show-only) -->
        <section class="requisitos-section">
            <div class="requisitos-container">
                <div class="requisitos-row">
                    <div class="requisitos-left">
                        <div class="requisitos-title-wrapper">
                            <h3 class="requisitos-title"><span class="requisitos-title-text">Requisitos</span></h3>
                        </div>
                        <div class="requisitos-content">
                            <div class="requisitos-card">
                                <div class="requisito-item">
                                    <img src="/images/desktop/hat.png" alt="Secundario" class="requisito-icon" />
                                    <div class="requisito-text-block">
                                        <div class="requisito-line1">Secundario</div>
                                        <div class="requisito-line2">No se requiere</div>
                                    </div>
                                </div>
                                <div class="requisito-item">
                                    <img src="/images/desktop/dni.png" alt="DNI" class="requisito-icon" />
                                    <div class="requisito-text-block">
                                        <div class="requisito-line1">Presentar</div>
                                        <div class="requisito-line2">DNI</div>
                                    </div>
                                </div>
                                <div class="requisito-item">
                                    <img src="/images/desktop/cruz.png" alt="Edad mínima" class="requisito-icon" />
                                    <div class="requisito-text-block">
                                        <div class="requisito-line1">Mayor de</div>
                                        <div class="requisito-line2">16 años</div>
                                    </div>
                                </div>
                                <div class="requisito-item">
                                    <img src="/images/desktop/tick.png" alt="Conocimientos" class="requisito-icon" />
                                    <div class="requisito-text-block">
                                        <div class="requisito-line1">Conocimientos</div>
                                        <div class="requisito-line2">no se requieren</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="requisitos-right">
                        <div class="requisitos-subtitle-wrapper">
                            <h3 class="requisitos-subtitle"><span class="requisitos-subtitle-text">Podés cursar en zona</span></h3>
                        </div>
                        <div class="requisitos-cursar">
                            <div class="requisitos-cursar-card">
                                <div class="cursar-grid {{ isset($sedesPorZona) && $sedesPorZona->count() === 1 ? 'cursar-grid-single' : '' }}">
                                    @if(isset($sedesPorZona) && $sedesPorZona->count() > 0)
                                        @foreach($sedesPorZona as $zona => $sedesNombres)
                                            <div class="cursar-item">
                                                <span class="cursar-label">{{ strtoupper($zona) }}</span>
                                                <div class="cursar-hover {{ $sedesNombres->count() > 1 ? 'cursar-hover-multi' : '' }}">
                                                    @foreach($sedesNombres as $sedeNombre)
                                                        <span>{{ $sedeNombre }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="cursar-item">
                                            <span class="cursar-label">-</span>
                                            <div class="cursar-hover"><span>No disponible</span></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Modalidades -->
        <section class="modalidades-section">
            <div class="modalidades-single">
                <h3 class="modalidades-title"><span class="modalidades-title-text">Modalidades</span></h3>
                <!-- Desktop Version -->
                @if(isset($modalidades) && $modalidades->count() > 0)
                    @foreach($modalidades as $modalidad)
                        @php
                            $modalidadSlug = Str::slug(strtolower($modalidad->nombre));
                            $tipos = $modalidad->tipos;
                            $columnas = $modalidad->columnas_visibles ?? [];
                            $horarios = $modalidad->horarios_visibles ?? [];
                            $numColumnas = count($columnas);
                            // Determinar la clase CSS según el número de columnas
                            $claseGrid = $numColumnas === 6 ? 'semipresencial' : 'presencial';
                            // Determinar si es semipresencial basándose en el nombre o número de columnas
                            $esSemipresencial = $numColumnas === 6 || stripos($modalidad->nombre, 'semi') !== false;
                        @endphp
                        <div class="modalidades-dropdown modalidades-dropdown-{{ $modalidadSlug }} modalidades-dropdown-{{ $claseGrid }} modalidades-desktop">
                            <div class="modalidades-dropdown-grid">
                                <!-- Fila 1: Header -->
                                <div class="modalidad-header-row modalidad-header-row-{{ $claseGrid }}">
                                    <div class="modalidad-left">
                                        <div class="modalidad-label">
                                            @if($modalidad->nombre_linea2)
                                                <span class="modalidad-label-line">{{ strtoupper($modalidad->nombre_linea1) }}</span>
                                                <span class="modalidad-label-line">{{ strtoupper($modalidad->nombre_linea2) }}</span>
                                            @else
                                                {{ strtoupper($modalidad->nombre_linea1 ?? $modalidad->nombre) }}
                                            @endif
                                        </div>
                                    </div>
                                    @foreach($columnas as $columna)
                                        <div class="modalidad-icon-item">
                                            <img src="{{ $columna['icono'] }}" alt="{{ $columna['nombre'] }}" class="modalidad-icon">
                                            <span class="modalidad-icon-text">{{ $columna['nombre'] }}</span>
                                        </div>
                                    @endforeach
                                    <div class="modalidad-right">
                                        <img src="/images/desktop/chevron.png" alt="Chevron" class="modalidad-chevron">
                                    </div>
                                </div>
                                <!-- Filas de Tipos -->
                                @foreach($tipos as $tipo)
                                    <div class="modalidad-content-row modalidad-content-row-{{ $claseGrid }}">
                                        <div class="modalidad-label-cell">
                                            <span class="modalidad-content-text">{{ strtoupper($tipo->nombre) }}</span>
                                        </div>
                                        @foreach($columnas as $columna)
                                            @php
                                                $campoDato = $columna['campo_dato'] ?? $columna['campo'] ?? '';
                                                $valor = $tipo->$campoDato ?? '';
                                                // Solo dividir en 2 líneas máximo: antes y después de "cada"
                                                $esMultilinea = strpos($valor, 'cada') !== false;
                                            @endphp
                                            <div class="modalidad-data-cell {{ $esMultilinea ? 'modalidad-data-cell-multiline' : '' }}">
                                                @if($valor)
                                                    @if($esMultilinea)
                                                        @php
                                                            // Dividir solo en 2 partes: antes de "cada" y después
                                                            $partes = explode(' cada ', $valor);
                                                            if(count($partes) == 2) {
                                                                $lineas = [$partes[0], 'cada ' . $partes[1]];
                                                            } else {
                                                                // Si no tiene "cada " exacto, buscar "cada"
                                                                $posCada = strpos($valor, 'cada');
                                                                if($posCada !== false) {
                                                                    $lineas = [
                                                                        trim(substr($valor, 0, $posCada)),
                                                                        trim(substr($valor, $posCada))
                                                                    ];
                                                                } else {
                                                                    $lineas = [$valor];
                                                                }
                                                            }
                                                        @endphp
                                                        @foreach($lineas as $linea)
                                                            <span class="modalidad-left-text">{{ trim($linea) }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="modalidad-left-text">{{ $valor }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <!-- Fila 5: Información adicional - fuera del grid principal -->
                            <div class="modalidad-special-row">
                                @if($modalidad->texto_info)
                                    <div class="modalidad-special-cell modalidad-special-cell-left">
                                        @php
                                            $textoInfo = $modalidad->texto_info;
                                            // Si tiene HTML, mantenerlo
                                            if (strpos($textoInfo, '<') !== false) {
                                                echo $textoInfo;
                                            } else {
                                                // Dividir el texto en 2 líneas: antes y después del paréntesis
                                                $posParentesis = strpos($textoInfo, '(');
                                                if ($posParentesis !== false) {
                                                    $linea1 = trim(substr($textoInfo, 0, $posParentesis));
                                                    $linea2 = trim(substr($textoInfo, $posParentesis));
                                                    echo '<span class="modalidad-special-text">' . e($linea1) . '</span>';
                                                    echo '<span class="modalidad-special-text modalidad-special-text-italic">' . e($linea2) . '</span>';
                                                } else {
                                                    // Si no tiene paréntesis, usar nl2br
                                                    echo nl2br(e($textoInfo));
                                                }
                                            }
                                        @endphp
                                    </div>
                                @endif
                                @php
                                    // Filtrar solo horarios que tengan horas no vacías
                                    $horariosConHoras = array_filter($horarios ?? [], function($horario) {
                                        return isset($horario['horas']) && trim($horario['horas']) !== '';
                                    });
                                @endphp
                                @if(count($horariosConHoras) > 0)
                                    <div class="modalidad-special-right-wrapper">
                                        @foreach($horariosConHoras as $horario)
                                            <div class="modalidad-special-cell modalidad-special-cell-right">
                                                @if($horario['icono'] ?? null)
                                                    <img src="{{ $horario['icono'] }}" alt="{{ $horario['nombre'] }}" class="modalidad-special-icon">
                                                @endif
                                                <div class="modalidad-special-time">
                                                    <span class="modalidad-special-time-label">{{ $horario['nombre'] }}</span>
                                                    <span class="modalidad-special-time-hours">{{ $horario['horas'] }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
                
                <!-- Mobile Version -->
                @if(isset($modalidades) && $modalidades->count() > 0)
                    @foreach($modalidades as $modalidad)
                        @php
                            $modalidadSlug = Str::slug(strtolower($modalidad->nombre));
                            $tipos = $modalidad->tipos;
                            $primerTipo = $tipos->first();
                            $segundoTipo = $tipos->skip(1)->first();
                            $columnas = $modalidad->columnas_visibles ?? [];
                            $horarios = $modalidad->horarios_visibles ?? [];
                        @endphp
                        <div class="modalidades-mobile modalidades-mobile-{{ $modalidadSlug }}">
                            <div class="modalidad-mobile-header">
                                <span class="modalidad-mobile-header-text">{{ strtoupper(str_replace(['-', '/', ' / '], ' ', $modalidad->nombre)) }}</span>
                                <img src="/images/desktop/chevron.png" alt="Chevron" class="modalidad-mobile-chevron">
                            </div>
                            <div class="modalidad-mobile-panel">
                                @if($tipos->count() > 0)
                                    <div class="modalidad-mobile-columns">
                                        <!-- Columna Izquierda: Primer Tipo (Intensivo) -->
                                        <div class="modalidad-mobile-column modalidad-mobile-column-left active">
                                            <button class="modalidad-mobile-tab-btn active" data-tab="{{ Str::slug(strtolower($primerTipo->nombre)) }}">{{ $primerTipo->nombre }}</button>
                                            <div class="modalidad-mobile-column-content">
                                                @foreach($tipos as $tipo)
                                                    @php $tipoSlug = Str::slug(strtolower($tipo->nombre)); @endphp
                                                    <!-- Tab {{ $tipo->nombre }} - Iconos -->
                                                    <div class="modalidad-mobile-tab-content {{ $loop->first ? 'active' : '' }}" data-content="{{ $tipoSlug }}">
                                                        <div class="modalidad-mobile-icon-col">
                                                            @foreach($columnas as $columna)
                                                                <div class="modalidad-mobile-icon-item">
                                                                    <img src="{{ $columna['icono'] }}" alt="{{ $columna['nombre'] }}" class="modalidad-mobile-icon">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!-- Columna Derecha: Segundo Tipo (Regular) -->
                                        <div class="modalidad-mobile-column modalidad-mobile-column-right">
                                            @if($segundoTipo)
                                                <button class="modalidad-mobile-tab-btn" data-tab="{{ Str::slug(strtolower($segundoTipo->nombre)) }}">{{ $segundoTipo->nombre }}</button>
                                            @endif
                                            <div class="modalidad-mobile-column-content">
                                                @foreach($tipos as $tipo)
                                                    @php $tipoSlug = Str::slug(strtolower($tipo->nombre)); @endphp
                                                    <!-- Tab {{ $tipo->nombre }} - Datos -->
                                                    <div class="modalidad-mobile-tab-content {{ $loop->first ? 'active' : '' }}" data-content="{{ $tipoSlug }}">
                                                        <div class="modalidad-mobile-data-col">
                                                            @foreach($columnas as $columna)
                                                                @php
                                                                    $campoDato = $columna['campo_dato'] ?? $columna['campo'] ?? '';
                                                                    $valor = $tipo->$campoDato ?? '';
                                                                @endphp
                                                                <div class="modalidad-mobile-data-item">
                                                                    @if($valor)
                                                                        @php
                                                                            // Solo dividir en 2 líneas máximo: antes y después de "cada"
                                                                            $esMultilinea = strpos($valor, 'cada') !== false;
                                                                        @endphp
                                                                        @if($esMultilinea)
                                                                            @php
                                                                                // Dividir solo en 2 partes: antes de "cada" y después
                                                                                $partes = explode(' cada ', $valor);
                                                                                if(count($partes) == 2) {
                                                                                    $lineas = [$partes[0], 'cada ' . $partes[1]];
                                                                                } else {
                                                                                    // Si no tiene "cada " exacto, buscar "cada"
                                                                                    $posCada = strpos($valor, 'cada');
                                                                                    if($posCada !== false) {
                                                                                        $lineas = [
                                                                                            trim(substr($valor, 0, $posCada)),
                                                                                            trim(substr($valor, $posCada))
                                                                                        ];
                                                                                    } else {
                                                                                        $lineas = [$valor];
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            @foreach($lineas as $linea)
                                                                                <span class="modalidad-mobile-data-text">{{ trim($linea) }}</span>
                                                                            @endforeach
                                                                        @else
                                                                            <span class="modalidad-mobile-data-text">{{ $valor }}</span>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <!-- Sección especial mobile -->
                                <div class="modalidad-mobile-special-row">
                                    @if($modalidad->texto_info)
                                        <div class="modalidad-mobile-special-cell modalidad-mobile-special-cell-left">
                                            @php
                                                $textoInfo = $modalidad->texto_info;
                                                // Si tiene HTML, mantenerlo, sino convertir saltos de línea
                                                if (strpos($textoInfo, '<') !== false) {
                                                    echo $textoInfo;
                                                } else {
                                                    echo nl2br(e($textoInfo));
                                                }
                                            @endphp
                                        </div>
                                    @endif
                                    @php
                                        // Filtrar solo horarios que tengan horas no vacías (versión mobile)
                                        $horariosConHorasMobile = array_filter($horarios ?? [], function($horario) {
                                            return isset($horario['horas']) && trim($horario['horas']) !== '';
                                        });
                                    @endphp
                                    @if(count($horariosConHorasMobile) > 0)
                                        <div class="modalidad-mobile-special-icons">
                                            @foreach($horariosConHorasMobile as $horario)
                                                <div class="modalidad-mobile-special-cell modalidad-mobile-special-cell-right">
                                                    @if($horario['icono'] ?? null)
                                                        <img src="{{ $horario['icono'] }}" alt="{{ $horario['nombre'] }}" class="modalidad-mobile-special-icon">
                                                    @endif
                                                    <div class="modalidad-mobile-special-time">
                                                        <span class="modalidad-mobile-special-time-label">{{ $horario['nombre'] }}</span>
                                                        <span class="modalidad-mobile-special-time-hours">{{ $horario['horas'] }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Cursada -->
        @if(isset($anios) && $anios->count() > 0)
        <section class="cursada">
            <div class="cursada-container">
                <h2 class="cursada-title">
                    <span class="cursada-title-line-1">
                        <span class="cursada-title-text-1">Descubrí</span>
                        <span class="cursada-title-text-2">qué vas a cursar</span>
                    </span>
                    <span class="cursada-title-line-2">
                        <span class="cursada-title-text-1">en cada año</span>
                    </span>
                </h2>
                
                @foreach($anios as $index => $anio)
                    @php
                        $numeroAnio = $anio->año;
                        $claseAnio = '';
                        if ($numeroAnio == 1) $claseAnio = '';
                        elseif ($numeroAnio == 2) $claseAnio = 'segundo';
                        elseif ($numeroAnio == 3) $claseAnio = 'tercero';
                        $esUltimoAnio = $index === $anios->count() - 1;
                    @endphp
                    
                    <!-- Dropdown de Año Desktop -->
                    <div class="cursada-dropdown {{ $claseAnio }} cursada-desktop">
                        <div class="cursada-dropdown-grid">
                            <!-- Header -->
                            <div class="cursada-header-row">
                                <div class="cursada-left">
                                    <div class="cursada-label">{{ $numeroAnio }}° AÑO</div>
                                    @if($esUltimoAnio && $numeroAnio == 3)
                                        <div class="cursada-subtitle">
                                            <span class="cursada-subtitle-regular">Especialización</span>
                                            <span class="cursada-subtitle-bold">opcional</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="cursada-content">
                                    <div class="cursada-info-line">
                                        <span class="cursada-info-label">Título:</span>
                                        <span class="cursada-info-value">{{ $anio->titulo }}</span>
                                    </div>
                                    <div class="cursada-info-line">
                                        <span class="cursada-info-label">Nivel:</span>
                                        <span class="cursada-info-value">{{ $anio->nivel }}</span>
                                    </div>
                                </div>
                                <div class="cursada-right">
                                    <img src="/images/desktop/chevron.png" alt="Chevron" class="cursada-chevron">
                                </div>
                            </div>
                            
                            @foreach($anio->unidades as $unidad)
                                <!-- Fila: Unidad -->
                                <div class="cursada-content-row">
                                    <div class="cursada-label-cell">
                                        <span class="cursada-unidad-text">Unidad {{ $unidad->numero }}</span>
                                    </div>
                                    <div class="cursada-content-cell">
                                        <div class="cursada-unidad-line">
                                            <span class="cursada-unidad-top">{{ $unidad->titulo }}</span>
                                        </div>
                                        <div class="cursada-unidad-line">
                                            <span class="cursada-unidad-bottom">{{ $unidad->subtitulo }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Fila: Botones Inscribirme y Descargar -->
                            <div class="cursada-content-row">
                                <div class="cursada-label-cell cursada-label-cell-with-btn">
                                    @if($index === 0)
                                        <a href="{{ route('inscripcion', $curso->id) }}" class="cursada-inscribirme-btn">¡Inscribirme ahora!</a>
                                    @else
                                        <button class="cursada-inscribirme-btn">Chatear con SAE</button>
                                    @endif
                                </div>
                                <div class="cursada-content-cell-with-btn">
                                    <button class="cursada-descargar-btn">Descargar el programa del {{ $numeroAnio }}° Año Completo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Versión Mobile -->
                    <div class="cursada-mobile cursada-mobile-ano{{ $numeroAnio }}">
                        <div class="cursada-mobile-header">
                            <span class="cursada-mobile-header-text">{{ $numeroAnio }}° AÑO</span>
                            <img src="/images/desktop/chevron.png" alt="Chevron" class="cursada-mobile-chevron">
                        </div>
                        <div class="cursada-mobile-panel">
                            <div class="cursada-mobile-unidades">
                                @foreach($anio->unidades as $unidad)
                                    <div class="cursada-mobile-unidad">
                                        <div class="cursada-mobile-unidad-label">Unidad {{ $unidad->numero }}</div>
                                        <div class="cursada-mobile-unidad-content">
                                            <div class="cursada-mobile-unidad-top">{{ $unidad->titulo }}</div>
                                            <div class="cursada-mobile-unidad-bottom">{{ $unidad->subtitulo }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="cursada-mobile-buttons">
                                <button class="cursada-mobile-descargar-btn">Descargar el programa del {{ $numeroAnio }}° Año Completo</button>
                                <div class="cursada-mobile-inscribirme-wrapper">
                                    <img src="/images/desktop/arrow.png" alt="Flecha" class="cursada-mobile-btn-arrow">
                                    @if($index === 0)
                                        <a href="{{ route('inscripcion', $curso->id) }}" class="cursada-mobile-inscribirme-btn">¡Inscribirme ahora!</a>
                                    @else
                                        <button class="cursada-mobile-inscribirme-btn">Chatear con SAE</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Certificación -->
        <section class="certificacion">
            <!-- Versión Desktop -->
            <div class="certificacion-container certificacion-desktop">
                <div class="certificacion-titles-wrapper">
                    <h2 class="certificacion-title">
                        <span class="certificacion-title-text-1">¿Qué</span>
                        <span class="certificacion-title-text-2">vas a aprender</span>
                        <span class="certificacion-title-text-1">en esta carrera?</span>
                    </h2>
                    <h2 class="certificacion-card-main-title">Certificación Oficial ITCA y UTN</h2>
                </div>
                
                <div class="certificacion-content">
                    <div class="certificacion-card certificacion-card-left">
                        <div class="certificacion-text-item">
                            <img src="/images/desktop/star1.png" alt="Star" class="certificacion-star-icon">
                            <p class="certificacion-text-item-text">Comprender <strong>desde cero el funcionamiento</strong> mecánico y electrónico de un automóvil.</p>
                        </div>
                        <div class="certificacion-text-item">
                            <img src="/images/desktop/star2.png" alt="Star" class="certificacion-star-icon">
                            <p class="certificacion-text-item-text">Reconocer y analizar los distintos <strong>sistemas que componen un vehículo.</strong></p>
                        </div>
                        <div class="certificacion-text-item">
                            <img src="/images/desktop/star1.png" alt="Star" class="certificacion-star-icon">
                            <p class="certificacion-text-item-text"><strong>Detectar y diagnosticar fallas</strong> en cada uno de esos sistemas.</p>
                        </div>
                        <div class="certificacion-text-item">
                            <img src="/images/desktop/star2.png" alt="Star" class="certificacion-star-icon">
                            <p class="certificacion-text-item-text"><strong>Aplicar procedimientos</strong> para la resolución de problemas mecánicos y electrónicos.</p>
                        </div>
                    </div>
                    
                    <div class="certificacion-card certificacion-card-right">
                        <div class="certificacion-card-right-wrapper">
                            @if(isset($certificados) && $certificados && $certificados->certificado_1)
                                <div class="certificacion-cert-item">
                                    <img src="{{ asset('storage/' . $certificados->certificado_1) }}" alt="Certificado ITCA" class="certificacion-cert-img">
                                    <p class="certificacion-cert-text">Tu diploma será entregado en el <span class="certificacion-cert-highlight">Acto de Graduación</span>, donde celebraremos juntos tu logro.</p>
                                </div>
                            @else
                                <div class="certificacion-cert-item">
                                    <img src="/images/desktop/cert-itca.png" alt="Certificado ITCA" class="certificacion-cert-img">
                                    <p class="certificacion-cert-text">Tu diploma será entregado en el <span class="certificacion-cert-highlight">Acto de Graduación</span>, donde celebraremos juntos tu logro.</p>
                                </div>
                            @endif
                            @if(isset($certificados) && $certificados && $certificados->certificado_2)
                                <div class="certificacion-cert-item">
                                    <img src="{{ asset('storage/' . $certificados->certificado_2) }}" alt="Certificado UTN" class="certificacion-cert-img">
                                    <p class="certificacion-cert-text">Tu <span class="certificacion-cert-highlight">certificación UTN</span> será entregada por correo electrónico.</p>
                                </div>
                            @else
                                <div class="certificacion-cert-item">
                                    <img src="/images/desktop/cert-utn.png" alt="Certificado UTN" class="certificacion-cert-img">
                                    <p class="certificacion-cert-text">Tu <span class="certificacion-cert-highlight">certificación UTN</span> será entregada por correo electrónico.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="certificacion-card certificacion-card-full">
                        <div class="certificacion-card-col-1">
                            <div class="certificacion-list-content">
                                <div class="certificacion-list-row" data-item="objetivo-estudio">
                                    <div class="certificacion-list-text">Objetivo de Estudio</div>
                                    <div class="certificacion-list-plus">+</div>
                                </div>
                                <div class="certificacion-list-content-expanded" id="objetivo-estudio-content">
                                    <div class="certificacion-list-expanded-text">
                                        Formar técnicos especializados en el diagnóstico y reparación de sistemas automotrices, con conocimientos sólidos en mecánica, electrónica y nuevas tecnologías del sector. El egresado será capaz de identificar, analizar y resolver problemas técnicos en vehículos modernos.
                                    </div>
                                </div>
                                <div class="certificacion-list-row" data-item="dirigido-a">
                                    <div class="certificacion-list-text">Dirigido a</div>
                                    <div class="certificacion-list-plus">+</div>
                                </div>
                                <div class="certificacion-list-content-expanded" id="dirigido-a-content">
                                    <div class="certificacion-list-expanded-text">
                                        Personas interesadas en el sector automotriz, técnicos que buscan actualización, emprendedores del rubro y estudiantes que quieren desarrollar una carrera profesional en mecánica y electrónica automotriz. No se requieren conocimientos previos.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="certificacion-card-col-2">
                            <div class="certificacion-list-content">
                                <div class="certificacion-list-row" data-item="niveles">
                                    <div class="certificacion-list-text">Niveles</div>
                                    <div class="certificacion-list-plus">+</div>
                                </div>
                                <div class="certificacion-list-content-expanded" id="niveles-content">
                                    <div class="certificacion-list-expanded-text">
                                        La carrera se estructura en módulos progresivos que cubren desde fundamentos de mecánica hasta sistemas avanzados de diagnóstico electrónico. Incluye práctica en talleres equipados con tecnología moderna y herramientas especializadas.
                                    </div>
                                </div>
                                <div class="certificacion-list-row" data-item="salida-laboral">
                                    <div class="certificacion-list-text">Salida Laboral</div>
                                    <div class="certificacion-list-plus">+</div>
                                </div>
                                <div class="certificacion-list-content-expanded" id="salida-laboral-content">
                                    <div class="certificacion-list-expanded-text">
                                        Talleres mecánicos, concesionarias, servicios técnicos oficiales, centros de diagnóstico automotriz, empresas de flotas, emprendimientos propios y sector industrial. Alta demanda laboral en el mercado automotriz actual.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Versión Mobile -->
            <div class="certificacion-container certificacion-mobile">
                <!-- 1. Título "¿Qué vas a aprender en esta carrera?" -->
                <h2 class="certificacion-mobile-title">
                    <div class="certificacion-mobile-title-line-1">
                        <span class="certificacion-mobile-title-text-1">¿Qué</span>
                        <span class="certificacion-mobile-title-text-2">vas a aprender</span>
                    </div>
                    <div class="certificacion-mobile-title-line-2">
                        <span class="certificacion-mobile-title-text-1">en esta carrera?</span>
                    </div>
                </h2>

                <!-- 2. certificacion-card-left -->
                <div class="certificacion-mobile-card certificacion-mobile-card-left">
                    <div class="certificacion-mobile-text-item">
                        <img src="/images/desktop/star1.png" alt="Star" class="certificacion-mobile-star-icon">
                        <p class="certificacion-mobile-text-item-text">Comprender <strong>desde cero el funcionamiento</strong> mecánico y electrónico de un automóvil.</p>
                    </div>
                    <div class="certificacion-mobile-text-item">
                        <img src="/images/desktop/star2.png" alt="Star" class="certificacion-mobile-star-icon">
                        <p class="certificacion-mobile-text-item-text">Reconocer y analizar los distintos <strong>sistemas que componen un vehículo.</strong></p>
                    </div>
                    <div class="certificacion-mobile-text-item">
                        <img src="/images/desktop/star1.png" alt="Star" class="certificacion-mobile-star-icon">
                        <p class="certificacion-mobile-text-item-text"><strong>Detectar y diagnosticar fallas</strong> en cada uno de esos sistemas.</p>
                    </div>
                    <div class="certificacion-mobile-text-item">
                        <img src="/images/desktop/star2.png" alt="Star" class="certificacion-mobile-star-icon">
                        <p class="certificacion-mobile-text-item-text"><strong>Aplicar procedimientos</strong> para la resolución de problemas mecánicos y electrónicos.</p>
                    </div>
                </div>

                <!-- 3. Título "Certificación Oficial ITCA y UTN" -->
                <h2 class="certificacion-mobile-main-title">
                    <span class="certificacion-mobile-main-title-text-1">Certificación</span>
                    <span class="certificacion-mobile-main-title-text-2">Oficial ITCA y UTN</span>
                </h2>

                <!-- 4. certificacion-card-right -->
                <div class="certificacion-mobile-card certificacion-mobile-card-right">
                    @if(isset($certificados) && $certificados && $certificados->certificado_1)
                        <div class="certificacion-mobile-cert-item">
                            <img src="{{ asset('storage/' . $certificados->certificado_1) }}" alt="Certificado ITCA" class="certificacion-mobile-cert-img">
                            <p class="certificacion-mobile-cert-text">Tu diploma será entregado en el <span class="certificacion-mobile-cert-highlight">Acto de Graduación</span>, donde celebraremos juntos tu logro.</p>
                        </div>
                    @else
                        <div class="certificacion-mobile-cert-item">
                            <img src="/images/desktop/cert-itca.png" alt="Certificado ITCA" class="certificacion-mobile-cert-img">
                            <p class="certificacion-mobile-cert-text">Tu diploma será entregado en el <span class="certificacion-mobile-cert-highlight">Acto de Graduación</span>, donde celebraremos juntos tu logro.</p>
                        </div>
                    @endif
                    @if(isset($certificados) && $certificados && $certificados->certificado_2)
                        <div class="certificacion-mobile-cert-item">
                            <img src="{{ asset('storage/' . $certificados->certificado_2) }}" alt="Certificado UTN" class="certificacion-mobile-cert-img">
                            <p class="certificacion-mobile-cert-text">Tu <span class="certificacion-mobile-cert-highlight">certificación UTN</span> será entregada por correo electrónico.</p>
                        </div>
                    @else
                        <div class="certificacion-mobile-cert-item">
                            <img src="/images/desktop/cert-utn.png" alt="Certificado UTN" class="certificacion-mobile-cert-img">
                            <p class="certificacion-mobile-cert-text">Tu <span class="certificacion-mobile-cert-highlight">certificación UTN</span> será entregada por correo electrónico.</p>
                        </div>
                    @endif
                </div>

                <!-- 5. certificacion-card-full -->
                <div class="certificacion-mobile-card certificacion-mobile-card-full">
                    <div class="certificacion-mobile-list-content">
                        <div class="certificacion-mobile-list-row" data-item="objetivo-estudio-mobile">
                            <div class="certificacion-mobile-list-text">Objetivo de Estudio</div>
                            <div class="certificacion-mobile-list-plus">+</div>
                        </div>
                        <div class="certificacion-mobile-list-content-expanded" id="objetivo-estudio-mobile-content">
                            <div class="certificacion-mobile-list-expanded-text">
                                Formar técnicos especializados en el diagnóstico y reparación de sistemas automotrices, con conocimientos sólidos en mecánica, electrónica y nuevas tecnologías del sector. El egresado será capaz de identificar, analizar y resolver problemas técnicos en vehículos modernos.
                            </div>
                        </div>
                        <div class="certificacion-mobile-list-row" data-item="dirigido-a-mobile">
                            <div class="certificacion-mobile-list-text">Dirigido a</div>
                            <div class="certificacion-mobile-list-plus">+</div>
                        </div>
                        <div class="certificacion-mobile-list-content-expanded" id="dirigido-a-mobile-content">
                            <div class="certificacion-mobile-list-expanded-text">
                                Personas interesadas en el sector automotriz, técnicos que buscan actualización, emprendedores del rubro y estudiantes que quieren desarrollar una carrera profesional en mecánica y electrónica automotriz. No se requieren conocimientos previos.
                            </div>
                        </div>
                        <div class="certificacion-mobile-list-row" data-item="niveles-mobile">
                            <div class="certificacion-mobile-list-text">Niveles</div>
                            <div class="certificacion-mobile-list-plus">+</div>
                        </div>
                        <div class="certificacion-mobile-list-content-expanded" id="niveles-mobile-content">
                            <div class="certificacion-mobile-list-expanded-text">
                                La carrera se estructura en módulos progresivos que cubren desde fundamentos de mecánica hasta sistemas avanzados de diagnóstico electrónico. Incluye práctica en talleres equipados con tecnología moderna y herramientas especializadas.
                            </div>
                        </div>
                        <div class="certificacion-mobile-list-row" data-item="salida-laboral-mobile">
                            <div class="certificacion-mobile-list-text">Salida Laboral</div>
                            <div class="certificacion-mobile-list-plus">+</div>
                        </div>
                        <div class="certificacion-mobile-list-content-expanded" id="salida-laboral-mobile-content">
                            <div class="certificacion-mobile-list-expanded-text">
                                Talleres mecánicos, concesionarias, servicios técnicos oficiales, centros de diagnóstico automotriz, empresas de flotas, emprendimientos propios y sector industrial. Alta demanda laboral en el mercado automotriz actual.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fotos Section -->
        @if($fotos->count() > 0)
        <section class="fotos-section">
            <div class="fotos-container">
                <h2 class="fotos-title">
                    <span class="fotos-title-text-1">Algunas</span>
                    <span class="fotos-title-text-2">fotografías</span>
                    <span class="fotos-title-text-1">de nuestras clases</span>
                </h2>
                
                <div class="fotos-desktop">
                    <div class="fotos-carousel-section">
                        <div class="swiper fotos-swiper">
                            <div class="swiper-wrapper">
                                @forelse($fotos as $foto)
                                    <div class="swiper-slide fotos-carousel-slide">
                                        <img src="{{ asset('storage/' . $foto->imagen) }}" 
                                             alt="{{ $foto->descripcion ?: 'Foto clase ' . $loop->iteration }}" 
                                             class="fotos-slide-img" />
                                    </div>
                                @empty
                                    {{-- Si no hay fotos, no mostrar el carousel --}}
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Controles de navegación -->
                        <div class="fotos-carousel-controls">
                            <!-- Barra de progreso -->
                            <div class="fotos-progress-bar fotos-desktop-progress-bar">
                                <div class="fotos-progress-track"></div>
                                <div class="fotos-progress-indicator fotos-desktop-progress-indicator"></div>
                            </div>
                            <div class="fotos-controls-wrapper">
                                <button class="fotos-carousel-btn fotos-carousel-btn-prev">
                                    <img src="/images/desktop/arrow-b.svg" alt="Anterior" class="fotos-arrow-left" />
                                </button>
                                <button class="fotos-carousel-btn fotos-carousel-btn-next">
                                    <img src="/images/desktop/arrow-b.svg" alt="Siguiente" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Versión Mobile -->
                <div class="fotos-mobile">
                    <h2 class="fotos-mobile-title">
                        <div class="fotos-mobile-title-line-1">
                            <span class="fotos-mobile-title-text-1">Algunas</span>
                            <span class="fotos-mobile-title-text-2">fotografías</span>
                        </div>
                        <div class="fotos-mobile-title-line-2">
                            <span class="fotos-mobile-title-text-1">de nuestras clases</span>
                        </div>
                    </h2>
                    
                    <!-- Carrusel Nativo de Fotos (solo mobile) -->
                    <div class="fotos-mobile-carousel-section">
                        <div class="fotos-mobile-carousel">
                            <div class="fotos-carousel-track">
                                @forelse($fotos as $foto)
                                    <div class="fotos-carousel-slide">
                                        <img src="{{ asset('storage/' . $foto->imagen) }}" 
                                             alt="{{ $foto->descripcion ?: 'Foto clase ' . $loop->iteration }}" />
                                    </div>
                                @empty
                                    {{-- Si no hay fotos, no mostrar el carousel --}}
                                @endforelse
                            </div>
                        </div>
                        
                        <div class="fotos-carousel-controls">
                            <div class="fotos-progress-bar">
                                <div class="fotos-progress-track"></div>
                                <div class="fotos-progress-indicator"></div>
                            </div>
                            
                            <!-- Botones de navegación -->
                            <div class="fotos-controls-row">
                                <button class="fotos-carousel-btn fotos-arrow-left" onclick="scrollFotosCarousel('left')">
                                    <img src="/images/mobile/arrowicon.png" alt="Anterior" />
                                </button>
                                
                                <button class="fotos-carousel-btn fotos-arrow-right" onclick="scrollFotosCarousel('right')">
                                    <img src="/images/mobile/arrowicon.png" alt="Siguiente" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Testimonios Carrera Section -->
        <section class="testimonios-carrera-section">
            <div class="testimonios-carrera-container">
                <h2 class="testimonios-carrera-title">
                    <span class="testimonios-carrera-title-text-1"></span>
                    <span class="testimonios-carrera-title-text-2">Alumnos y Egresados</span>
                    <span class="testimonios-carrera-title-text-1">comparten su experiencia</span>
                </h2>
                
                <div class="testimonios-carrera-grid">
                    @php
                        $testimoniosArray = $testimonios->toArray();
                        $testimoniosCount = count($testimoniosArray);
                        $testimonioIndex = 0;
                    @endphp
                    
                    @for($gridIndex = 0; $gridIndex < 8; $gridIndex++)
                        @if($gridIndex == 2)
                            <!-- Columna 3: Video que ocupa 2 filas -->
                            <div class="testimonios-carrera-grid-item testimonios-carrera-grid-item-video">
                                <div class="testimonios-carrera-grid-video">
                                    <video class="testimonios-carrera-video" loop controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture>
                                        @if($videoTestimonios && !empty($videoTestimonios->video))
                                            <source src="{{ asset('storage/' . $videoTestimonios->video) }}" type="video/mp4">
                                        @else
                                            <source src="/images/mediacontent/ytmobile.mp4" type="video/mp4">
                                        @endif
                                        Tu navegador no soporta el elemento video.
                                    </video>
                                    <button class="testimonios-carrera-play-button">
                                        <img src="/images/desktop/play.png" alt="Play" class="testimonios-carrera-play-icon" />
                                    </button>
                                    <a href="{{ ($videoTestimonios && !empty($videoTestimonios->url)) ? $videoTestimonios->url : '#' }}" class="testimonios-carrera-ver-mas-btn">
                                        Ver más testimonios
                                    </a>
                                </div>
                            </div>
                        @elseif($gridIndex == 6)
                            <!-- Columna 3 fila 2: Ocupada por el video, saltamos este slot -->
                            @continue
                        @else
                            @if($testimonioIndex < $testimoniosCount)
                                @php $testimonio = $testimonios[$testimonioIndex]; $testimonioIndex++; @endphp
                                <div class="testimonios-carrera-grid-item">
                                    <div class="testimonios-carrera-grid-image">
                                        <!-- Header -->
                                        <div class="testimonios-carrera-card-header">
                                            <img src="{{ asset('storage/' . $testimonio->avatar) }}" 
                                                 alt="Avatar" class="testimonios-carrera-card-avatar" loading="lazy">
                                            <div class="testimonios-carrera-card-info">
                                                <p class="testimonios-carrera-card-sede">{{ $testimonio->sede }}</p>
                                                <div class="testimonios-carrera-card-wrapper">
                                                    <p class="testimonios-carrera-card-nombre">{{ $testimonio->nombre }}</p>
                                                    <p class="testimonios-carrera-card-tiempo">hace {{ $testimonio->tiempo_testimonio }} meses</p>
                                                </div>
                                                <p class="testimonios-carrera-card-carrera">{{ $testimonio->carrera }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Main -->
                                        <div class="testimonios-carrera-card-main">
                                            <p class="testimonios-carrera-card-texto">{{ $testimonio->texto }}</p>
                                        </div>
                                        
                                        <!-- Bottom -->
                                        <div class="testimonios-carrera-card-bottom">
                                            <img src="/images/desktop/comunidad/stars.png" alt="Stars" class="testimonios-carrera-card-stars" loading="lazy">
                                            <img src="{{ asset('storage/' . $testimonio->icono) }}" 
                                                 alt="Icono" class="testimonios-carrera-card-google" loading="lazy">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endfor
                </div>
            </div>
        </section>

        <!-- Testimonios Carrera Mobile Carousel Section -->
        <section class="testimonios-carrera-mobile-carousel-section">
            <div class="testimonios-carrera-mobile-container">
                <h2 class="testimonios-carrera-mobile-title">
                    <span class="testimonios-carrera-mobile-title-text-regular">Unite a nuestra </span>
                    <span class="testimonios-carrera-mobile-title-text-highlight">comunidad ITCA</span>
                </h2>
            </div>
            <div class="testimonios-carrera-mobile-carousel">
                <div class="testimonios-carrera-carousel-track">
                    @foreach($testimonios as $testimonio)
                    <div class="testimonios-carrera-carousel-slide">
                        <div class="testimonios-carrera-mobile-card">
                            <div class="testimonios-carrera-card-header">
                                <img src="{{ asset('storage/' . $testimonio->avatar) }}" alt="Avatar" class="testimonios-carrera-card-avatar" loading="lazy">
                                <div class="testimonios-carrera-card-info">
                                    <p class="testimonios-carrera-card-sede">{{ $testimonio->sede }}</p>
                                    <div class="testimonios-carrera-card-wrapper">
                                        <p class="testimonios-carrera-card-nombre">{{ $testimonio->nombre }}</p>
                                        <p class="testimonios-carrera-card-tiempo">hace {{ $testimonio->tiempo_testimonio }} meses</p>
                                    </div>
                                    <p class="testimonios-carrera-card-carrera">{{ $testimonio->carrera }}</p>
                                </div>
                            </div>
                            <div class="testimonios-carrera-card-main">
                                <p class="testimonios-carrera-card-texto">{{ $testimonio->texto }}</p>
                            </div>
                            <div class="testimonios-carrera-card-bottom">
                                <img src="/images/desktop/comunidad/stars.png" alt="Stars" class="testimonios-carrera-card-stars" loading="lazy">
                                <img src="{{ asset('storage/' . $testimonio->icono) }}" alt="Icono" class="testimonios-carrera-card-google" loading="lazy">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Controles del carrusel -->
            <div class="testimonios-carrera-carousel-controls">
                <div class="testimonios-carrera-progress-bar">
                    <div class="testimonios-carrera-progress-track"></div>
                    <div class="testimonios-carrera-progress-indicator"></div>
                </div>
                <div class="testimonios-carrera-controls-row">
                    <a href="#" class="testimonios-carrera-ver-todos-btn">Ver más opiniones</a>
                    <button class="testimonios-carrera-carousel-btn testimonios-carrera-btn-prev" onclick="scrollTestimoniosCarreraCarousel(-1)">
                        <img src="/images/desktop/arrow-b.svg" alt="Previous" class="testimonios-carrera-arrow-left">
                    </button>
                    <button class="testimonios-carrera-carousel-btn testimonios-carrera-btn-next" onclick="scrollTestimoniosCarreraCarousel(1)">
                        <img src="/images/desktop/arrow-b.svg" alt="Next">
                    </button>
                </div>
            </div>
        </section>

        <!-- En Acción Carrera Mobile Carousel Section -->
        <section class="en-accion-carrera-mobile-carousel-section">
            <div class="en-accion-carrera-mobile-carousel">
                <div class="en-accion-carrera-carousel-track">
                    @foreach($videosMobile as $video)
                    <div class="en-accion-carrera-carousel-slide">
                        <video class="en-accion-carrera-mobile-video" loop controlsList="nodownload nofullscreen noremoteplayback" disablePictureInPicture>
                            <source src="{{ asset('storage/' . $video->video) }}" type="video/mp4">
                        </video>
                        <button class="en-accion-carrera-mobile-play-button">
                            <img src="/images/desktop/play.png" alt="Play" class="en-accion-carrera-mobile-play-icon" />
                        </button>
                        <a href="{{ $video->url }}" target="_blank" class="en-accion-carrera-mobile-{{ $video->getPlatformClass() }}-btn">
                            Ir a {{ $video->getPlatformName() }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Controles del carrusel -->
            <div class="en-accion-carrera-mobile-carousel-controls">
                <!-- Barra de progreso arriba -->
                <div class="en-accion-carrera-mobile-progress-bar">
                    <div class="en-accion-carrera-mobile-progress-indicator"></div>
                </div>
                <!-- Botones de navegación abajo -->
                <div class="en-accion-carrera-mobile-controls-wrapper">
                    <button class="en-accion-carrera-mobile-prev-btn">
                        <img src="/images/desktop/arrow-b.svg" alt="Anterior" />
                    </button>
                    <button class="en-accion-carrera-mobile-next-btn">
                        <img src="/images/desktop/arrow-b.svg" alt="Siguiente" />
                    </button>
                </div>
            </div>
        </section>

        <!-- Dudas Section -->
        <section class="dudas-section">
            <div class="dudas-container">
                <h2 class="dudas-title">
                    <span class="dudas-title-text-1">¡Resolvé</span>
                    <span class="dudas-title-text-2">algunas dudas</span>
                    <span class="dudas-title-text-1">ya!</span>
                </h2>
                
                <!-- Título mobile -->
                <h2 class="dudas-mobile-title">
                    <span class="dudas-mobile-title-text-1">¡Resolvé</span>
                    <span class="dudas-mobile-title-text-2">algunas dudas</span>
                    <span class="dudas-mobile-title-text-1">ya!</span>
                </h2>
                
                @if($dudas->count() > 0)
                    @php
                        // Dividir las FAQs en dos columnas
                        $totalDudas = $dudas->count();
                        $mitad = ceil($totalDudas / 2);
                        $dudasCol1 = $dudas->take($mitad);
                        $dudasCol2 = $dudas->skip($mitad);
                    @endphp
                    <div class="faqs-card faqs-card-full">
                        <div class="faqs-card-col-1">
                            <div class="faqs-list-content">
                                @foreach($dudasCol1 as $duda)
                                    <div class="faqs-list-row" data-item="faqs-{{ $duda->id }}">
                                        <div class="faqs-list-text">{{ $duda->pregunta }}</div>
                                        <div class="faqs-list-plus">+</div>
                                    </div>
                                    <div class="faqs-list-content-expanded" id="faqs-{{ $duda->id }}-content">
                                        <div class="faqs-list-expanded-text">
                                            {{ $duda->respuesta }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="faqs-card-col-2">
                            <div class="faqs-list-content">
                                @foreach($dudasCol2 as $duda)
                                    <div class="faqs-list-row" data-item="faqs-{{ $duda->id }}">
                                        <div class="faqs-list-text">{{ $duda->pregunta }}</div>
                                        <div class="faqs-list-plus">+</div>
                                    </div>
                                    <div class="faqs-list-content-expanded" id="faqs-{{ $duda->id }}-content">
                                        <div class="faqs-list-expanded-text">
                                            {{ $duda->respuesta }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- Partners Section (reutilizada) -->
        <section class="partners-section">
            <div class="partners-content">
                <h2 class="partners-title">
                    <span class="partners-title-text-1">Nos acompañan en la</span>
                    <span class="partners-title-text-2">formación profesional</span>
                </h2>
                <div class="partners-carousel">
                    <div class="partners-slider">
                        @if($partners->count() > 0)
                            @foreach($partners as $partner)
                                <div>
                                    <a href="{{ $partner->url }}" target="_blank" class="partners-logo">
                                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->url }}" loading="lazy">
                                    </a>
                                </div>
                            @endforeach
                            @foreach($partners as $partner)
                                <div>
                                    <a href="{{ $partner->url }}" target="_blank" class="partners-logo">
                                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->url }}" loading="lazy">
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Contacto Section (reutilizada) -->
        <section class="contacto-section" id="contacto">
            <div class="contacto-content">
                <div class="contacto-tercio contacto-sedes">
                    <h3 class="contacto-tercio-title">Sedes</h3>
                    <div class="contacto-sedes-content">
                        @foreach($sedes->where('disponible', true)->whereNotIn('nombre', ['online', 'Online', 'ONLINE', 'próximamente', 'Proximamente', 'PROXIMAMENTE']) as $sede)
                            <div class="contacto-sede-row" data-sede="{{ Str::slug($sede->nombre) }}">
                                <div class="contacto-sede-text">{{ $sede->nombre }}</div>
                                <div class="contacto-sede-plus">+</div>
                            </div>
                            <div class="contacto-sede-content" id="{{ Str::slug($sede->nombre) }}-content">
                                <div class="contacto-sede-direccion">{{ $sede->direccion }}</div>
                                <div class="contacto-sede-contacto">Contacto: {{ $sede->telefono }}</div>
                                @if(!empty($sede->link_google_maps) && trim($sede->link_google_maps) !== '')
                                    <div class="contacto-sede-link">
                                        <a href="{{ $sede->link_google_maps }}" target="_blank" class="contacto-sede-link-maps">📍 Ver en Maps</a>
                                    </div>
                                @endif
                                @if(!empty($sede->link_whatsapp) && trim($sede->link_whatsapp) !== '')
                                    <div class="contacto-sede-link">
                                        <a href="{{ $sede->link_whatsapp }}" target="_blank" class="contacto-sede-link-whatsapp">💬 WhatsApp</a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="contacto-tercio contacto-contacto">
                    <div class="contacto-contacto-wrapper">
                        <h3 class="contacto-tercio-title">Contacto</h3>
                        <div class="contacto-info-content">
                            <div class="contacto-info-item"><span class="contacto-info-label">Tel: </span><span class="contacto-info-value">0810-220-4822</span></div>
                            <div class="contacto-info-item"><span class="contacto-info-label">WhatsApp: </span><span class="contacto-info-value">11-2267-4822</span></div>
                            <div class="contacto-info-item"><span class="contacto-info-label">Mail: </span><span class="contacto-info-value"><a href="mailto:inscripciones@itca.edu.ar">inscripciones@itca.edu.ar</a></span></div>
                        </div>
                        <!-- Redes Sociales (visible solo en desktop) -->
                        <h4 class="contacto-redes-title contacto-redes-desktop">Redes Sociales</h4>
                        <div class="contacto-redes-icons contacto-redes-desktop">
                            <a href="https://www.instagram.com/itca.oficial/?hl=en" target="_blank" class="contacto-redes-link"><img src="/images/social/ig.png" alt="Instagram" class="contacto-redes-icon"></a>
                            <a href="https://www.tiktok.com/@itca.oficial" target="_blank" class="contacto-redes-link"><img src="/images/social/tik.png" alt="TikTok" class="contacto-redes-icon"></a>
                            <a href="https://www.facebook.com/ITCAoficial/" target="_blank" class="contacto-redes-link"><img src="/images/social/fb.png" alt="Facebook" class="contacto-redes-icon"></a>
                            <a href="https://www.linkedin.com/school/itca-oficial/" target="_blank" class="contacto-redes-link"><img src="/images/social/lin.png" alt="LinkedIn" class="contacto-redes-icon"></a>
                            <a href="https://www.youtube.com/canalITCAoficial" target="_blank" class="contacto-redes-link"><img src="/images/social/yt.png" alt="YouTube" class="contacto-redes-icon"></a>
                        </div>
                    </div>
                </div>

                <div class="contacto-tercio contacto-info">
                    <div class="contacto-info-wrapper">
                        <h3 class="contacto-tercio-title">¿Querés más info?</h3>
                        <p class="contacto-info-text"><strong>Suscribite</strong> y mantente al día con las últimas noticias, ofertas exclusivas y recursos útiles.</p>
                        <form class="contacto-form">
                            <input type="text" placeholder="Tu nombre" class="contacto-input" required>
                            <input type="email" placeholder="Tu email" class="contacto-input" required>
                            <input type="tel" placeholder="Tu número de celular" class="contacto-input" required>
                            <button type="submit" class="contacto-submit-btn">Enviar datos</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        // Inicialización local por si app.js no encuentra jQuery a tiempo
        document.addEventListener('DOMContentLoaded', function() {
            if (window.jQuery) {
                var $ = window.jQuery;
                if ($('.partners-slider').length && typeof $('.partners-slider').slick === 'function') {
                    $('.partners-slider').not('.slick-initialized').slick({
                        slidesToShow: 6,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 0,
                        speed: 5000,
                        cssEase: 'linear',
                        arrows: false,
                        infinite: true,
                        pauseOnHover: false,
                        variableWidth: true
                    });
                }
            }

            // Toggle del dropdown de modalidades y sincronización - usando delegación de eventos para múltiples dropdowns
            const modalidadesContainer = document.querySelector('.modalidades-single');
            
            // Función para sincronizar anchos de columnas de un dropdown específico
            function syncColumnWidths(dropdown) {
                const headerRow = dropdown.querySelector('.modalidad-header-row');
                const contentRows = dropdown.querySelectorAll('.modalidad-content-row');
                
                if (headerRow && contentRows.length > 0) {
                    // Obtener todas las celdas del header
                    const headerCells = Array.from(headerRow.children);
                    const columnWidths = headerCells.map(cell => {
                        const rect = cell.getBoundingClientRect();
                        return rect.width + 'px';
                    });
                    
                    // Construir grid-template-columns con los anchos calculados
                    const gridColumns = columnWidths.join(' ');
                    
                    // Aplicar a todas las filas de contenido del mismo dropdown
                    contentRows.forEach(row => {
                        row.style.gridTemplateColumns = gridColumns;
                    });
                }
            }
            
            // Delegación de eventos para los headers (toda la fila es clickeable)
            if (modalidadesContainer) {
                modalidadesContainer.addEventListener('click', function(e) {
                    // Detectar click en el header row o en el chevron
                    const headerRow = e.target.closest('.modalidad-header-row');
                    const chevron = e.target.closest('.modalidad-chevron');
                    
                    if (headerRow || chevron) {
                        const modalidadDropdown = (headerRow || chevron).closest('.modalidades-dropdown');
                        if (modalidadDropdown) {
                            modalidadDropdown.classList.toggle('open');
                            
                            // Sincronizar cuando se abre
                            if (modalidadDropdown.classList.contains('open')) {
                                setTimeout(() => syncColumnWidths(modalidadDropdown), 150);
                            }
                        }
                    }
                });
                
                // Observer para cada dropdown
                const dropdowns = document.querySelectorAll('.modalidades-dropdown');
                dropdowns.forEach(dropdown => {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                if (dropdown.classList.contains('open')) {
                                    setTimeout(() => syncColumnWidths(dropdown), 150);
                                }
                            }
                        });
                    });
                    observer.observe(dropdown, { attributes: true });
                });
            }
            
            // Sincronizar al redimensionar
            window.addEventListener('resize', function() {
                const openDropdowns = document.querySelectorAll('.modalidades-dropdown.open');
                openDropdowns.forEach(dropdown => {
                    setTimeout(() => syncColumnWidths(dropdown), 100);
                });
            });
            
            // Función para sincronizar alturas de iconos y textos
            function syncModalidadRowHeights(modalidadMobile) {
                const activeContent = modalidadMobile.querySelector('.modalidad-mobile-tab-content.active');
                if (!activeContent) return;
                
                const iconCol = modalidadMobile.querySelector('.modalidad-mobile-column-left .modalidad-mobile-tab-content.active .modalidad-mobile-icon-col');
                const dataCol = modalidadMobile.querySelector('.modalidad-mobile-column-right .modalidad-mobile-tab-content.active .modalidad-mobile-data-col');
                
                if (iconCol && dataCol) {
                    const iconItems = iconCol.querySelectorAll('.modalidad-mobile-icon-item');
                    const dataItems = dataCol.querySelectorAll('.modalidad-mobile-data-item');
                    
                    const maxLength = Math.max(iconItems.length, dataItems.length);
                    for (let i = 0; i < maxLength; i++) {
                        if (iconItems[i] && dataItems[i]) {
                            const iconHeight = iconItems[i].offsetHeight;
                            const dataHeight = dataItems[i].offsetHeight;
                            const maxHeight = Math.max(iconHeight, dataHeight);
                            
                            iconItems[i].style.minHeight = maxHeight + 'px';
                            dataItems[i].style.minHeight = maxHeight + 'px';
                        }
                    }
                }
            }
            
            // Toggle del panel mobile de modalidades
            const modalidadesMobileHeaders = document.querySelectorAll('.modalidad-mobile-header');
            modalidadesMobileHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const modalidadMobile = this.closest('.modalidades-mobile');
                    if (modalidadMobile) {
                        modalidadMobile.classList.toggle('open');
                        // Sincronizar alturas cuando se abre el panel
                        if (modalidadMobile.classList.contains('open')) {
                            setTimeout(() => {
                                syncModalidadRowHeights(modalidadMobile);
                            }, 300);
                        }
                    }
                });
            });
            
            // Toggle del panel mobile de cursada
            const cursadaMobileHeaders = document.querySelectorAll('.cursada-mobile-header');
            cursadaMobileHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const cursadaMobile = this.closest('.cursada-mobile');
                    if (cursadaMobile) {
                        cursadaMobile.classList.toggle('open');
                    }
                });
            });
            
            // Cambio de tabs en mobile
            const modalidadesMobileTabs = document.querySelectorAll('.modalidad-mobile-tab-btn');
            modalidadesMobileTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');
                    const modalidadMobile = this.closest('.modalidades-mobile');
                    if (modalidadMobile) {
                        // Remover active de todos los tabs, contenidos y columnas
                        const allTabs = modalidadMobile.querySelectorAll('.modalidad-mobile-tab-btn');
                        const allContents = modalidadMobile.querySelectorAll('.modalidad-mobile-tab-content');
                        const allColumns = modalidadMobile.querySelectorAll('.modalidad-mobile-column');
                        
                        allTabs.forEach(t => t.classList.remove('active'));
                        allContents.forEach(c => c.classList.remove('active'));
                        allColumns.forEach(col => col.classList.remove('active'));
                        
                        // Activar el tab, su columna y todos los contenidos con el mismo data-content (en ambas columnas)
                        this.classList.add('active');
                        const column = this.closest('.modalidad-mobile-column');
                        if (column) {
                            column.classList.add('active');
                        }
                        const targetContents = modalidadMobile.querySelectorAll(`.modalidad-mobile-tab-content[data-content="${tabName}"]`);
                        targetContents.forEach(content => {
                            content.classList.add('active');
                        });
                        
                        // Sincronizar alturas de iconos y textos para alinearlos por fila
                        setTimeout(() => {
                            syncModalidadRowHeights(modalidadMobile);
                        }, 10);
                    }
                });
            });
            
            // Sincronizar alturas al cargar
            document.addEventListener('DOMContentLoaded', function() {
                const modalidadesMobile = document.querySelectorAll('.modalidades-mobile');
                modalidadesMobile.forEach(modalidad => {
                    if (modalidad.classList.contains('open')) {
                        syncModalidadRowHeights(modalidad);
                    }
                });
            });
            
            // Toggle del dropdown de cursada (toda la fila del header es clickeable)
            const cursadaContainer = document.querySelector('.cursada-container');
            if (cursadaContainer) {
                cursadaContainer.addEventListener('click', function(e) {
                    // Detectar click en el header row o en el chevron
                    const headerRow = e.target.closest('.cursada-header-row');
                    const chevron = e.target.closest('.cursada-chevron');
                    
                    if (headerRow || chevron) {
                        const cursadaDropdown = (headerRow || chevron).closest('.cursada-dropdown');
                        if (cursadaDropdown) {
                            cursadaDropdown.classList.toggle('open');
                        }
                    }
                });
            }
            
            // Contacto Sedes Expand/Collapse
            const sedeRows = document.querySelectorAll('.contacto-sede-row[data-sede]');
            
            sedeRows.forEach(row => {
                row.addEventListener('click', function() {
                    const sedeId = this.getAttribute('data-sede');
                    const content = document.getElementById(sedeId + '-content');
                    
                    // Cerrar todos los otros contenidos
                    document.querySelectorAll('.contacto-sede-content').forEach(otherContent => {
                        if (otherContent !== content) {
                            otherContent.classList.remove('active');
                        }
                    });
                    
                    // Toggle del contenido actual
                    if (content) {
                        content.classList.toggle('active');
                    }
                });
            });
            
        });
    </script>
</html>


