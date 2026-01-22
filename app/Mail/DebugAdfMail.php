<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class DebugAdfMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo Lead Ingresado desde Web ITCA',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.adf',
            with: ['content' => 'ADF XML attached'],
        );
    }

    public function attachments(): array
    {
        if (is_array($this->data)) {
            $content = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            $content = $this->data;
        }

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $content, 'lead.adf')
                ->withMime('text/plain'),
        ];
    }
}