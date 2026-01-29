<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cursada extends Model
{
    protected $table = 'cursadas';
    
    protected $fillable = [
        'ID_Curso',
        'carrera',
        'Cod1',
        'Fecha_Inicio',
        'xDias',
        'xModalidad',
        'Régimen',
        'xTurno',
        'Horario',
        'Vacantes',
        'Matric_Base',
        'Sin_iva_Mat', // Nueva columna del Excel
        'Cta_Web',
        'Sin_IVA_cta', // Nueva columna del Excel
        'Dto_Cuota',
        'cuotas',
        'sede',
        'Promo_Mat_logo',
        'ver_curso', // Nueva columna del Excel
    ];
    
    protected $casts = [
        'Fecha_Inicio' => 'date',
        'Matric_Base' => 'decimal:2',
        'Sin_iva_Mat' => 'decimal:2',
        'Cta_Web' => 'decimal:2',
        'Sin_IVA_cta' => 'decimal:2',
        'Dto_Cuota' => 'decimal:2', // Porcentaje
        'Vacantes' => 'integer',
        'cuotas' => 'integer', // Asegurar que cuotas sea un entero
    ];
    
    /**
     * Intenta resolver el Curso asociado basándose en el código y nombre.
     */
    public function resolveCurso()
    {
        // Estrategia de búsqueda inteligente basada en código "Cod1" y nombre
        $keywords = [];
        $cod = strtoupper(trim($this->Cod1));

        if (\Illuminate\Support\Str::startsWith($cod, 'M1')) {
            $keywords = ['moto', 'motocicleta'];
        } elseif (\Illuminate\Support\Str::startsWith($cod, 'C1')) {
            $keywords = ['mecanica', 'tecnologia', 'automovil']; 
        } elseif (\Illuminate\Support\Str::startsWith($cod, 'EEA')) {
            $keywords = ['electricidad', 'electronica'];
        }

        $curso = null;
        
        if (!empty($keywords)) {
            $cursos = Curso::all();
            $bestMatch = null;
            $maxScore = 0;

            foreach ($cursos as $c) {
                $slug = \Illuminate\Support\Str::slug($c->nombre);
                $score = 0;
                
                foreach ($keywords as $kw) {
                    if (str_contains($slug, $kw)) {
                        $score++;
                    }
                }

                if (\Illuminate\Support\Str::startsWith($cod, 'C1') && str_contains($slug, 'moto')) {
                    $score = -1; 
                }

                if ($score > $maxScore) {
                    $maxScore = $score;
                    $bestMatch = $c;
                }
            }
            $curso = $bestMatch;
        }

        if (!$curso) {
             $slugCursada = \Illuminate\Support\Str::slug($this->carrera);
             $curso = Curso::all()->first(function($c) use ($slugCursada) {
                 return str_contains($slugCursada, \Illuminate\Support\Str::slug($c->nombre)) || 
                        str_contains(\Illuminate\Support\Str::slug($c->nombre), $slugCursada);
             });
        }
        return $curso;
    }

    /**
     * Resuelve el tipo de modalidad detallado (ModalidadTipo) basándose en los campos de la cursada.
     */
    public function getModalidadTipo()
    {
        $curso = $this->resolveCurso();
        if (!$curso) return null;

        // 1. Buscamos la modalidad (Presencial, Semipresencial, etc)
        // Normalizamos el nombre para búsqueda
        $modalidadNombre = trim($this->xModalidad);
        
        $modalidades = \App\Models\Modalidad::where('curso_id', $curso->id)->get();
        $modalidad = $modalidades->first(function($m) use ($modalidadNombre) {
            $normalize = function($s) {
                $s = strtolower($s);
                $s = str_replace([' ', '-'], '', $s);
                $s = str_replace('sempre', 'semi', $s);
                return $s;
            };
            $n1 = $normalize($m->nombre);
            $n2 = $normalize($modalidadNombre);
            return $n1 && $n2 && ($n1 === $n2);
        });

        if (!$modalidad) return null;

        // 2. Buscamos el tipo de modalidad dentro de esa modalidad (Regular, Intensivo, etc)
        $tipoNombre = trim($this->Régimen);
        
        return \App\Models\ModalidadTipo::where('modalidad_id', $modalidad->id)
            ->where('nombre', 'LIKE', '%' . $tipoNombre . '%')
            ->first();
    }
}
