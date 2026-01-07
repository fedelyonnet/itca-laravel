<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\Cursada;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $cursada;

    /**
     * Create a new message instance.
     */
    public function __construct(Lead $lead, Cursada $cursada)
    {
        $this->lead = $lead;
        $this->cursada = $cursada;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $carreraNombre = $this->cursada->carrera ?? 'Carrera';
        return new Envelope(
            subject: "Nuevo Lead - {$carreraNombre}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-notification',
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
