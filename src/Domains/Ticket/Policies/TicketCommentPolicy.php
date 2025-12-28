<?php

namespace Src\Domains\Ticket\Policies;

use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\User\Models\User;
use Src\Domains\Ticket\Enums\TicketStatus;

class TicketCommentPolicy
{
    public function before(User $user)
    {
        if ( $user->isAdmin() ) return true;

        return false;
    }

    public function update(User $user, Ticket $ticket)
    {
        if ($user->isCustomer()) {
            return $ticket->requester_id === $user->id
                && $ticket->status !== TicketStatus::CLOSED;
        }

        if ($user->isAgent()) {
            return $ticket->assignee_id === $user->id
                && $ticket->status !== TicketStatus::CLOSED;
        }

        return false;
    }
}