<?php

namespace Src\Domains\Ticket\Actions;

use DomainException;
use Src\Domains\Common\Support\TransactionManager;
use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\Ticket\Models\TicketAudit;
use Src\Domains\Ticket\Enums\TicketAction;
use Src\Domains\Ticket\Policies\TicketPolicy;
use Src\Domains\Ticket\Services\TicketValidator;

class AssignTicket
{
    public function __construct(
        private TicketValidator $validator,
        private TransactionManager $transactionManager
    ) {}

    public function execute(array $attributes, Ticket $ticket)
    {
        authorize(TicketPolicy::class, 'assign', $ticket);

        $this->validator->validateAssign($attributes);

        return $this->transactionManager->run(function () use ($attributes, $ticket) {
            $newAssigneeId = $attributes['assignee_id'];

            if ( $ticket->assignee_id === $newAssigneeId ) {
                throw new DomainException("You can't assign again the same person in the ticket");
            }

            $ticket->update([
                'assignee_id' => $newAssigneeId
            ]);
            $ticket->refresh();

            TicketAudit::create([
                'ticket_id'   => $ticket->id,
                'actor_id'    => $ticket->requester_id,
                'action'      => TicketAction::ASSIGNED->value,
                'meta' => json_encode([
                    'old_assignee_id' => $ticket->assignee_id,
                    'new_assignee_id' => $newAssigneeId
                ])
            ]);

            return $ticket;
        });
    }
}