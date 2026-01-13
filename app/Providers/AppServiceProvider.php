<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS en producción
        if (config('app.env') === 'production' || request()->isSecure()) {
            URL::forceScheme('https');
        }

        // Log de consultas SQL lentas para depuración
        if (config('app.debug')) {
            \DB::listen(function ($query) {
                if ($query->time > 100) { // Loguear consultas que tarden más de 100ms
                    \Log::warning('Consulta lenta detectada: ' . $query->sql, [
                        'bindings' => $query->bindings,
                        'time' => $query->time
                    ]);
                }
            });
        }
    }
}
