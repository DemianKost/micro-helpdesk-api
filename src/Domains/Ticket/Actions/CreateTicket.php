<?php

namespace Src\Domains\Ticket\Actions;

use Src\Domains\Common\Support\TransactionManager;
use Src\Domains\Ticket\Enums\TicketAction;
use Src\Domains\Ticket\Enums\TicketStatus;
use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\Ticket\Models\TicketAudit;
use Src\Domains\Ticket\Services\TicketValidator;

class CreateTicket
{
    public function __construct(
        private TicketValidator $validator,
        private TransactionManager $transactionManager
    ) {}

    public function execute(array $attributes)
    {
        $this->validator->validateCreate($attributes);

        return $this->transactionManager->run(function () use($attributes) {
            $ticket = Ticket::create(
                array_merge( 
                    $attributes,
                    ['requester_id' => auth()->user()->id]
                )
            );
            
            TicketAudit::create([
                'ticket_id'   => $ticket->id,
                'actor_id'    => $ticket->requester_id,
                'action'      => TicketAction::CREATED->value,
            ]);

            return $ticket;
        });
    }
}