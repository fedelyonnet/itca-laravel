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
            subject: 'DEBUG: ADF Lead (XML/JSON)',
        );
    }

    public function content(): Content
    {
        if (is_array($this->data)) {
            $content = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            $content = $this->data;
        }

        return new Content(
            text: function () use ($content) {
                return $content;
            },
        );
    }
}