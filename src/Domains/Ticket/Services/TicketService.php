<?php

namespace Src\Domains\Ticket\Services;

use Src\Domains\Common\Support\ActionBus;
use Src\Domains\Ticket\Actions\AssignTicket;
use Src\Domains\Ticket\Actions\CreateTicket;
use Src\Domains\Ticket\Actions\DeleteTicket;
use Src\Domains\Ticket\Actions\UpdateTicket;
use Src\Domains\Ticket\Models\Ticket;

class TicketService
{
    public function __construct(
        private ActionBus $actions
    ) {}

    public function create(array $attributes)
    {
        return $this->actions->call(CreateTicket::class, $attributes);
    }

    public function update(array $attributes, Ticket $ticket)
    {
        return $this->actions->call(UpdateTicket::class, $attributes, $ticket);
    }

    public function assign(array $attributes, Ticket $ticket)
    {
        return $this->actions->call(AssignTicket::class, $attributes, $ticket);
    }

    public function delete(Ticket $ticket)
    {
        return $this->actions->call(DeleteTicket::class, $ticket);
    }
}