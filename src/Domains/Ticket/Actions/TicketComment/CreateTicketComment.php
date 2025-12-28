<?php

namespace Src\Domains\Ticket\Actions\TicketComment;

use Src\Domains\Common\Support\TransactionManager;
use Src\Domains\Ticket\Enums\TicketAction;
use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\Ticket\Models\TicketAudit;
use Src\Domains\Ticket\Models\TicketComment;
use Src\Domains\Ticket\Services\TicketCommentValidator;
use function GuzzleHttp\json_encode;

class CreateTicketComment
{
    public function __construct(
        private TransactionManager $transactionManager,
        private TicketCommentValidator $validator
    ) {}

    public function execute(array $attributes, Ticket $ticket)
    {
        $this->validator->validateUpdate($attributes);

        return $this->transactionManager->run( function () use ($attributes, $ticket) {
            $ticketComment = TicketComment::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->user()->id,
                'body' => $attributes['body']
            ]);

            TicketAudit::create([
                'ticket_id'   => $ticket->id,
                'actor_id'    => $ticketComment->user_id,
                'action'      => TicketAction::COMMENTED->value,
                'meta'          => json_encode([])
            ]);

            return $ticketComment;
        });
    }
}