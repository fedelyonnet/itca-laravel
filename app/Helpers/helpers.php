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
        $correcciones = [
            'Electricidad y Eletrónica Automotriz' => 'Electricidad y Electrónica del Automóvil',
            'Mecánica y Electrónica de Motos' => 'Mecánica y Electrónica de la Motocicleta',
        ];
        
        // Buscar coincidencia exacta o parcial
        foreach ($correcciones as $incorrecto => $correcto) {
            if (trim($nombre) === $incorrecto || stripos($nombre, $incorrecto) !== false) {
                return $correcto;
            }
        }
        
        return $nombre;
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
        $conversiones = [
            'constituyentes' => 'Villa Urquiza - Av. Constituyentes 4631',
            'congreso' => 'Villa Urquiza - Av. Congreso 5672',
            'moron' => 'Morón - E. Grant 301',
            'morón' => 'Morón - E. Grant 301',
            'banfield' => 'Banfield - Av. Hipólito Yrigoyen 7536',
            'san isidro' => 'San Isidro - Camino de la Ribera Nte. 150',
            'beiró' => 'Villa Devoto - Bermudez 3192',
            'beiro' => 'Villa Devoto - Bermudez 3192',
        ];
        
        $nombreLower = strtolower(trim($nombre));
        
        // Buscar coincidencia exacta (case-insensitive)
        if (isset($conversiones[$nombreLower])) {
            return $conversiones[$nombreLower];
        }
        
        // Buscar coincidencia parcial
        foreach ($conversiones as $incorrecto => $correcto) {
            if (stripos($nombreLower, $incorrecto) !== false) {
                return $correcto;
            }
        }
        
        return $nombre;
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

