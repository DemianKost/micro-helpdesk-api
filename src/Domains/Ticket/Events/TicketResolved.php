<?php

namespace Src\Domains\Ticket\Events;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Domains\Ticket\Models\Ticket;

class TicketResolved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Ticket $ticket
    ) {}
}