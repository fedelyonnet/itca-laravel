<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\Cursada;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbandonedCartMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckAbandonedCart implements ShouldQueue
{
    use Queueable;

    public $lead;
    public $cursada;

    /**
     * Create a new job instance.
     */
    public function __construct(Lead $lead, Cursada $cursada)
    {
        $this->lead = $lead;
        $this->cursada = $cursada;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Verificar si el usuario ya concretó la inscripción (pago aprobado)
        $inscripcionExitosa = Inscripcion::where('lead_id', $this->lead->id)
            ->where('cursada_id', $this->cursada->ID_Curso)
            ->whereIn('estado', ['approved', 'completado'])
            ->exists();

        if ($inscripcionExitosa) {
            // El usuario ya pagó, no hacemos nada
            return;
        }

        // Si llegó aquí, es un carrito abandonado. Enviamos el mail.
        // Verificamos si la cursada aún tiene vacantes antes de enviar (opcional, pero recomendado)
        // Por ahora enviamos siempre y la validación se hace al clickear el link según lo hablado.

        Mail::to($this->lead->correo)->send(new AbandonedCartMail($this->lead, $this->cursada));
    }
}
