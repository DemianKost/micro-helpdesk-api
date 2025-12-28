<?php

namespace Src\Domains\Ticket\Actions\TicketComment;

use Illuminate\Auth\Access\AuthorizationException;
use Src\Domains\Common\Support\TransactionManager;
use Src\Domains\Ticket\Models\TicketComment;
use Src\Domains\Ticket\Policies\TicketCommentPolicy;
use Src\Domains\Ticket\Services\TicketCommentValidator;

class UpdateTicketComment
{
    public function __construct(
        private TransactionManager $transactionManager,
        private TicketCommentValidator $validator
    ) {}

    public function execute(array $attributes, TicketComment $ticketComment)
    {
        $this->validator->validateUpdate($attributes);

        if ( authorize(TicketCommentPolicy::class, 'update', $ticketComment->ticket()) ) {
            throw new AuthorizationException("You're not allowed to update this comment");
        }

        return $this->transactionManager->run( function () use ($attributes, $ticketComment) {
            $ticketComment->lockForUpdate()->update($attributes);

            return $ticketComment;
        });
    }
}