<?php

namespace Src\Domains\Ticket\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Src\Domains\Common\Support\TransactionManager;
use Src\Domains\Ticket\Enums\TicketAction;
use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\Ticket\Models\TicketAudit;
use Src\Domains\Ticket\Policies\TicketPolicy;
use Src\Domains\Ticket\Services\TicketValidator;

class DeleteTicket
{
    public function __construct(
        private TicketValidator $ticketValidator,
        private TransactionManager $transactionManager
    ) {}

    public function execute(Ticket $ticket)
    {
        if ( ! authorize(TicketPolicy::class, 'delete') ) {
            throw new AuthorizationException('You are not allowed to delete this ticket.');
        }

        return $this->transactionManager->run(function () use ($ticket) {
            $ticket->delete();

            TicketAudit::create([
                'ticket_id'   => $ticket->id,
                'actor_id'    => $ticket->requester_id,
                'action'      => TicketAction::DELETED->value,
            ]);

            return true;
        });
    }
}