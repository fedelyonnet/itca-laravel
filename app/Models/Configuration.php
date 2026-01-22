<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    /**
     * Obtener el valor de una configuración por su clave.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    /**
     * Establecer el valor de una configuración.
     *
     * @param string $key
     * @param string $value
     * @param string|null $description
     * @return Configuration
     */
    public static function set(string $key, string $value, ?string $description = null): Configuration
    {
        $data = ['value' => $value];
        if ($description) {
            $data['description'] = $description;
        }

        return self::updateOrCreate(
            ['key' => $key],
            $data
        );
    }
}
