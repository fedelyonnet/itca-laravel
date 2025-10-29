<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoticiaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'fecha_publicacion' => 'required|date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'visible' => 'nullable|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convertir formato de fecha de dd/mm/yyyy a yyyy-mm-dd
        if ($this->fecha_publicacion) {
            $fecha = \DateTime::createFromFormat('d/m/Y', $this->fecha_publicacion);
            if ($fecha) {
                $this->merge([
                    'fecha_publicacion' => $fecha->format('Y-m-d')
                ]);
            }
        }
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'contenido.required' => 'El contenido es obligatorio.',
            'fecha_publicacion.required' => 'La fecha de publicación es obligatoria.',
            'fecha_publicacion.date' => 'La fecha de publicación debe ser una fecha válida en formato dd/mm/aaaa.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'imagen.max' => 'La imagen no puede ser mayor a 2MB.',
        ];
    }
}

