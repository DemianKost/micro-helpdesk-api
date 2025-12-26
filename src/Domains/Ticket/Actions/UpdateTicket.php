<?php

namespace Src\Domains\Ticket\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Src\Domains\Common\Support\TransactionManager;
use Src\Domains\Ticket\Enums\TicketAction;
use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\Ticket\Models\TicketAudit;
use Src\Domains\Ticket\Policies\TicketPolicy;
use Src\Domains\Ticket\Services\TicketValidator;

class UpdateTicket
{
    public function __construct(
        private TicketValidator $ticketValidator,
        private TransactionManager $transactionManager
    ) {}

    public function execute(array $attributes, Ticket $ticket)
    {
        if ( ! authorize(TicketPolicy::class, 'update') ) {
            throw new AuthorizationException('You are not allowed to update this ticket.');
        }

        $this->ticketValidator->validateUpdate($attributes);
        
        return $this->transactionManager->run( function() use($ticket, $attributes) {
            $ticket->update($attributes);
            $ticket->refresh();

            TicketAudit::create([
                'ticket_id'   => $ticket->id,
                'actor_id'    => $ticket->requester_id,
                'action'      => TicketAction::UPDATED->value,
                'meta'        => json_encode([
                    'old_title' => $ticket->title,
                    'new_title' => $attributes['title'] ?? '',
                    'old_description' => $ticket->description,
                    'new_description' => $attributes['description'] ?? ''
                ])
            ]);

            return $ticket;
        });
    }
}