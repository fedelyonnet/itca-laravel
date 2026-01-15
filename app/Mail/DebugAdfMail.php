<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DebugAdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jsonData;

    public function __construct(array $jsonData)
    {
        $this->jsonData = $jsonData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'DEBUG: JSON ADF para Tecnom',
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: '<pre>' . json_encode($this->jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>'
        );
    }
}