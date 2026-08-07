<?php

namespace App\Mail;

use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CriticalErrorNotification extends Mailable
{
    public function __construct(
        public string $errorId,
        public string $context,
        public Exception $exception,
        public array $data,
        public string $timestamp,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🚨 CRITICAL ERROR [{$this->errorId}] - {$this->context}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.critical-error-notification',
            with: [
                'errorId' => $this->errorId,
                'context' => $this->context,
                'errorMessage' => $this->exception->getMessage(),
                'code' => $this->exception->getCode(),
                'file' => $this->exception->getFile(),
                'line' => $this->exception->getLine(),
                'timestamp' => $this->timestamp,
                'data' => $this->data,
            ],
        );
    }
}
