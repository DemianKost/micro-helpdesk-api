<?php

namespace Src\Domains\Ticket\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Src\Domains\Ticket\Models\Ticket;

class TicketResolvedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket
    ) {}

    public function content(): Content
    {
        return new Content(
            view: 'ticket.resolved'
        );
    }
}