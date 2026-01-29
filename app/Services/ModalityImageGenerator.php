<?php

namespace App\Services;

class ModalityImageGenerator
{
    private $width = 1200;
    private $height = 240;
    private $primaryColor;
    private $textColor;
    private $bgColor;
    private $whiteColor;

    public function __construct()
    {
        // Verificar que GD esté disponible
        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension is not loaded');
        }
    }

    /**
     * Genera una imagen con las características de la modalidad
     * 
     * @param array $features Array con 6 elementos, cada uno con 'label' y 'value'
     * @return string Path relativo a la imagen generada
     */
    public function generate(array $features)
    {
        // Crear imagen
        $image = imagecreatetruecolor($this->width, $this->height);
        
        // Definir colores
        $this->bgColor = imagecolorallocate($image, 255, 255, 255); // Blanco
        $this->primaryColor = imagecolorallocate($image, 3, 78, 255); // #034EFF
        $this->textColor = imagecolorallocate($image, 1, 12, 66); // #010C42
        $this->whiteColor = imagecolorallocate($image, 255, 255, 255);
        
        // Fondo blanco
        imagefill($image, 0, 0, $this->bgColor);
        
        $columnWidth = $this->width / 6;
        $headerHeight = 80;
        $valueHeight = $this->height - $headerHeight;
        
        // Cargar fuentes
        $fontBold = public_path('fonts/Montserrat-Bold.ttf');
        $fontSemiBold = public_path('fonts/Montserrat-SemiBold.ttf');
        
        // Si no existen las fuentes, usar fuentes del sistema
        if (!file_exists($fontBold)) {
            $fontBold = 5; // GD built-in font
            $fontSemiBold = 5;
            $useTTF = false;
        } else {
            $useTTF = true;
        }
        
        // Dibujar cada columna
        foreach ($features as $index => $feature) {
            $x = $index * $columnWidth;
            
            // Header (fondo azul)
            imagefilledrectangle($image, $x, 0, $x + $columnWidth, $headerHeight, $this->primaryColor);
            
            // Borde blanco derecho del header (excepto última columna)
            if ($index < 5) {
                imagefilledrectangle($image, $x + $columnWidth - 2, 0, $x + $columnWidth, $headerHeight, $this->whiteColor);
            }
            
            // Label (texto blanco en header)
            $labelText = strtoupper($feature['label']);
            $labelX = intval($x + ($columnWidth / 2));
            $labelY = intval($headerHeight / 2);
            
            if ($useTTF) {
                $bbox = imagettfbbox(14, 0, $fontBold, $labelText);
                $textWidth = $bbox[2] - $bbox[0];
                $textHeight = $bbox[1] - $bbox[7];
                imagettftext($image, 14, 0, intval($labelX - ($textWidth / 2)), intval($labelY + ($textHeight / 2)), $this->whiteColor, $fontBold, $labelText);
            } else {
                $textWidth = imagefontwidth(5) * strlen($labelText);
                imagestring($image, 5, intval($labelX - ($textWidth / 2)), $labelY - 8, $labelText, $this->whiteColor);
            }
            
            // Value area (fondo blanco con borde azul)
            imagefilledrectangle($image, intval($x), $headerHeight, intval($x + $columnWidth), $this->height, $this->bgColor);
            
            // Bordes azules del value area
            imagerectangle($image, intval($x), $headerHeight, intval($x + $columnWidth - 1), $this->height - 1, $this->primaryColor);
            imagerectangle($image, intval($x + 1), $headerHeight + 1, intval($x + $columnWidth - 2), $this->height - 2, $this->primaryColor);
            
            // Value (texto azul oscuro)
            $valueText = $this->wrapText($feature['value'], $columnWidth - 20);
            $valueX = intval($x + ($columnWidth / 2));
            $valueY = intval($headerHeight + ($valueHeight / 2));
            
            if ($useTTF) {
                $bbox = imagettfbbox(16, 0, $fontSemiBold, $valueText);
                $textWidth = $bbox[2] - $bbox[0];
                $textHeight = $bbox[1] - $bbox[7];
                imagettftext($image, 16, 0, intval($valueX - ($textWidth / 2)), intval($valueY + ($textHeight / 2)), $this->textColor, $fontSemiBold, $valueText);
            } else {
                $textWidth = imagefontwidth(5) * strlen($valueText);
                imagestring($image, 5, intval($valueX - ($textWidth / 2)), $valueY - 8, $valueText, $this->textColor);
            }
        }
        
        // Guardar imagen
        $filename = 'modality_' . md5(json_encode($features)) . '.jpg';
        $path = storage_path('app/public/mail_images/' . $filename);
        
        // Crear directorio si no existe
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        imagejpeg($image, $path, 90);
        imagedestroy($image);
        
        return 'mail_images/' . $filename;
    }
    
    /**
     * Envuelve texto largo para que quepa en el ancho especificado
     */
    private function wrapText($text, $maxWidth)
    {
        // Para simplificar, solo reemplazamos " / " con salto de línea
        return str_replace(' / ', "\n", $text);
    }
}
