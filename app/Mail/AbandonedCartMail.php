<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Lead;
use App\Models\Cursada;

class AbandonedCartMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $cursada;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct(Lead $lead, Cursada $cursada, string $token)
    {
        $this->lead = $lead;
        $this->cursada = $cursada;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡No pierdas tu vacante en ITCA!',
        );
    }

    /**
     * Get the message content definition.
     */
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Resolve curso, template and modalid tipo
        $curso = $this->cursada->resolveCurso();
        $mailTemplate = $curso ? \App\Models\CareerMailTemplate::where('curso_id', $curso->id)->first() : null;
        $modalidadTipo = $this->cursada->getModalidadTipo();

        return new Content(
            view: 'emails.abandoned_cart',
            with: [
                'mailTemplate' => $mailTemplate,
                'curso' => $curso,
                'modalidadTipo' => $modalidadTipo,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
