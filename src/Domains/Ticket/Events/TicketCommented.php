<?php

namespace Src\Domains\Ticket\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Domains\Ticket\Models\Ticket;

class TicketCommented implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels, InteractsWithSockets;

    public function __construct(
        public Ticket $ticket
    ) {}

    public function broadcastOn()
    {
        return new Channel('tickets');
    }

    public function broadcastAs()
    {
        return 'commented';
    }

    public function broadcastWith(): array
    {
        return [
            'ticket' => $this->ticket
        ];
    }
}