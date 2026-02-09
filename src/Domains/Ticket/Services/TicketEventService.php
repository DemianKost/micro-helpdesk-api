<?php

namespace Src\Domains\Ticket\Services;

use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\Ticket\Models\TicketEvent;

class TicketEventService
{
    public function create(Ticket $ticket)
    {
        $previousEvent = TicketEvent::where('ticket_id', $ticket->id)->first();
        $seq = ($previousEvent) ? $previousEvent->seq + 1 : 1;

        TicketEvent::create([
            'ticket_id' => $ticket->id,
            'seq' => $seq,
            'type' => '',
            'data' => $ticket->toArray(),
            'meta' => [
                'actor_id' => auth()->user()->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        ]);
    }
}