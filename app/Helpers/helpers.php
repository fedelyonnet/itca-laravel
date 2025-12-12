<?php

if (!function_exists('corregirNombreCarrera')) {
    /**
     * Corrige los nombres de las carreras según las reglas establecidas
     * 
     * @param string $nombre
     * @return string
     */
    function corregirNombreCarrera($nombre)
    {
        $nombreOriginal = trim($nombre);
        $nombreUpper = strtoupper($nombreOriginal);
        
        // Mapeo de nombres de BD a nombres de visualización
        $mapeos = [
            'MECÁNICA Y TECNOLOGÍAS DEL AUTÓMOVIL 1' => 'Mecánica y Tecnologías del Automóvil',
            'ELECTRICIDAD Y ELECTRÓNICA DEL AUTOMÓVIL' => 'Electricidad y Electrónica del Automóvil',
            'MECÁNICA Y ELECTRÓNICA DE MOTOS 1' => 'Mecánica y Electrónica de la Motocicleta',
        ];
        
        // Buscar coincidencia exacta (case insensitive)
        foreach ($mapeos as $bd => $visualizacion) {
            if (strtoupper(trim($nombreOriginal)) === $bd) {
                return $visualizacion;
            }
        }
        
        // Buscar coincidencia parcial para casos similares
        if (stripos($nombreUpper, 'MECÁNICA Y TECNOLOGÍAS DEL AUTÓMOVIL') !== false) {
            return 'Mecánica y Tecnologías del Automóvil';
        }
        
        if (stripos($nombreUpper, 'ELECTRICIDAD Y ELECTRÓNICA DEL AUTOMÓVIL') !== false) {
            return 'Electricidad y Electrónica del Automóvil';
        }
        
        if (stripos($nombreUpper, 'MECÁNICA Y ELECTRÓNICA DE MOTOS') !== false) {
            return 'Mecánica y Electrónica de la Motocicleta';
        }
        
        // Si no hay coincidencia, devolver el nombre original
        return $nombreOriginal;
    }
}

if (!function_exists('corregirNombreSede')) {
    /**
     * Convierte los nombres de sedes a formato completo con dirección
     * 
     * @param string $nombre
     * @return string
     */
    function corregirNombreSede($nombre)
    {
        $nombreOriginal = trim($nombre);
        $nombreLower = mb_strtolower($nombreOriginal, 'UTF-8');
        
        // Mapeo de valores de BD (case-insensitive) a nombres completos
        $conversiones = [
            'constituyentes' => 'Villa Urquiza - Av. Constituyentes 4631',
            'urquiza constituyentes' => 'Villa Urquiza - Av. Constituyentes 4631',
            'congreso' => 'Villa Urquiza - Av. Congreso 5672',
            'urquiza congreso' => 'Villa Urquiza - Av. Congreso 5672',
            'moron' => 'Morón - E. Grant 301',
            'morón' => 'Morón - E. Grant 301',
            'banfield' => 'Banfield - Av. Hipólito Yrigoyen 7536',
            'san isidro' => 'San Isidro - Camino de la Ribera Nte. 150',
            'beiró' => 'Villa Devoto - Bermudez 3192',
            'beiro' => 'Villa Devoto - Bermudez 3192',
            'devoto beiro' => 'Villa Devoto - Bermudez 3192',
            'devoto' => 'Villa Devoto - Bermudez 3192',
        ];
        
        // Buscar coincidencia exacta (case-insensitive)
        if (isset($conversiones[$nombreLower])) {
            return $conversiones[$nombreLower];
        }
        
        // Buscar coincidencia parcial (sin importar mayúsculas/minúsculas ni tildes)
        foreach ($conversiones as $key => $value) {
            // Normalizar para comparar (remover tildes)
            $keyNormalizado = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], mb_strtolower($key, 'UTF-8'));
            $nombreNormalizado = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $nombreLower);
            
            if (stripos($nombreNormalizado, $keyNormalizado) !== false || stripos($keyNormalizado, $nombreNormalizado) !== false) {
                return $value;
            }
        }
        
        return $nombreOriginal;
    }
}

if (!function_exists('simplificarNombreSede')) {
    /**
     * Simplifica el nombre de la sede para mostrar en los items filtrados
     * 
     * @param string $nombre
     * @return string
     */
    function simplificarNombreSede($nombre)
    {
        $nombreLower = strtolower(trim($nombre));
        
        // Mapeo de nombres completos o parciales a textos simplificados
        if (stripos($nombreLower, 'congreso') !== false && stripos($nombreLower, 'urquiza') !== false) {
            return 'Urquiza Congreso';
        }
        
        if (stripos($nombreLower, 'constituyentes') !== false && stripos($nombreLower, 'urquiza') !== false) {
            return 'Urquiza Constituyentes';
        }
        
        if (stripos($nombreLower, 'banfield') !== false) {
            return 'Banfield';
        }
        
        if (stripos($nombreLower, 'devoto') !== false || stripos($nombreLower, 'beiró') !== false || stripos($nombreLower, 'beiro') !== false || stripos($nombreLower, 'bermudez') !== false) {
            return 'Devoto';
        }
        
        if (stripos($nombreLower, 'moron') !== false || stripos($nombreLower, 'morón') !== false) {
            return 'Morón';
        }
        
        if (stripos($nombreLower, 'san isidro') !== false) {
            return 'San Isidro';
        }
        
        return $nombre;
    }
}

if (!function_exists('convertirDiasCompletos')) {
    /**
     * Convierte abreviaciones de días a nombres completos
     * Ejemplo: "mie vie" -> "Miércoles y Viernes"
     * 
     * @param string $dias
     * @return string
     */
    function convertirDiasCompletos($dias)
    {
        if (empty($dias)) {
            return '';
        }
        
        $diasOriginal = trim($dias);
        $diasLower = mb_strtolower($diasOriginal, 'UTF-8');
        
        // Mapeo de abreviaciones a nombres completos
        $mapeoDias = [
            'lun' => 'Lunes',
            'lunes' => 'Lunes',
            'mar' => 'Martes',
            'martes' => 'Martes',
            'mie' => 'Miércoles',
            'mié' => 'Miércoles',
            'miercoles' => 'Miércoles',
            'miércoles' => 'Miércoles',
            'jue' => 'Jueves',
            'jueves' => 'Jueves',
            'vie' => 'Viernes',
            'viernes' => 'Viernes',
            'sab' => 'Sábado',
            'sáb' => 'Sábado',
            'sabado' => 'Sábado',
            'sábado' => 'Sábado',
            'dom' => 'Domingo',
            'domingo' => 'Domingo',
        ];
        
        // Separar por espacios, guiones, o cualquier separador
        $separadores = preg_split('/([\s\-]+)/', $diasLower, -1, PREG_SPLIT_DELIM_CAPTURE);
        $diasArray = [];
        $separador = ' y ';
        
        foreach ($separadores as $parte) {
            $parte = trim($parte);
            if (empty($parte)) {
                continue;
            }
            
            // Si es un separador (guión o espacio), usar "y" como separador
            if (preg_match('/^[\s\-]+$/', $parte)) {
                continue;
            }
            
            // Buscar la abreviación en el mapeo
            $diaCompleto = null;
            foreach ($mapeoDias as $abrev => $completo) {
                if (stripos($parte, $abrev) === 0 || $parte === $abrev) {
                    $diaCompleto = $completo;
                    break;
                }
            }
            
            if ($diaCompleto) {
                $diasArray[] = $diaCompleto;
            } else {
                // Si no se encuentra, mantener el original capitalizado
                $diasArray[] = ucfirst($parte);
            }
        }
        
        // Si no se encontró ningún día, devolver el original capitalizado
        if (empty($diasArray)) {
            return ucfirst($diasOriginal);
        }
        
        return implode($separador, $diasArray);
    }
}

