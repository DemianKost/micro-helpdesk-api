<?php

namespace Src\Domains\Ticket\Policies;

use Src\Domains\Ticket\Enums\TicketStatus;
use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\User\Models\User;

class TicketPolicy
{
    public function before(User $user): bool
    {
        if ( $user->isAdmin() ) return true;

        return false;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isCustomer()) {
            return $ticket->requester_id === $user->id;
        }

        if ($user->isAgent()) {
            return $ticket->assignee_id === $user->id
                || $ticket->assignee_id === null;
        }

        return false;
    }

    public function update(User $user, Ticket $ticket): bool
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

    public function delete(User $user, Ticket $ticket): bool
    {
        if ( $user->isAdmin() ) return true;

        return false;
    }

    public function comment(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket)
            && $ticket->status !== TicketStatus::CLOSED;
    }

    public function assign(User $user, Ticket $ticket)
    {
        if ($user->isAgent()) {
            return $ticket->assignee_id === null
                && $ticket->status !== TicketStatus::CLOSED;
        }

        return false;
    }

    public function transition(User $user, Ticket $ticket)
    {
        if ($user->isCustomer()) {
            return $ticket->requester_id === $user->id;
        }

        if ($user->isAgent()) {
            return $ticket->assignee_id === $user->id;
        }

        return false;
    }
}