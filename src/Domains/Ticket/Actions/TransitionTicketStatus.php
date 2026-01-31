<?php

namespace Src\Domains\Ticket\Actions;

use DomainException;
use Illuminate\Auth\Access\AuthorizationException;
use Src\Domains\Common\Support\TransactionManager;
use Src\Domains\Ticket\Enums\TicketStatus;
use Src\Domains\Ticket\Events\TicketResolved;
use Src\Domains\Ticket\Events\TicketStatusUpdated;
use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\Ticket\Models\TicketAudit;
use Src\Domains\Ticket\Policies\TicketPolicy;
use Src\Domains\User\Models\User;

class TransitionTicketStatus
{
    public function __construct(
        private TransactionManager $transactionManager
    ) {}

    public function execute(
        User $user,
        Ticket $ticket,
        TicketStatus $ticketStatus
    ) {
        if ( ! authorize(TicketPolicy::class, 'transition') ) {
            throw new AuthorizationException('You are not allowed to transition this ticket.');
        }

        if ( $this->isValidTransition($ticket, $ticketStatus) ) {
            throw new DomainException("Invalid transition to {$ticketStatus->value}");
        }

        return $this->transactionManager->run(function() use($user, $ticket, $ticketStatus) {
            $from = $ticket->status;

            $ticket->update([
                'status' => $ticketStatus->value,
                'resolved_at' => $ticketStatus === TicketStatus::RESOLVED
                    ? now()
                    : ($ticketStatus === TicketStatus::IN_PROGRESS ? null : $ticket->resolved_at),
            ]);

            if ( $ticketStatus === TicketStatus::RESOLVED ) event(new TicketResolved($ticket));

            TicketAudit::create([
                'ticket_id'   => $ticket->id,
                'actor_id'    => $user->id,
                'action'      => 'status_changed',
                'from_status' => $from,
                'to_status'   => $ticketStatus->value,
                'meta'        => null,
            ]);

            event( new TicketStatusUpdated($ticket) );

            return $ticket->refresh();
        });
    }

    private function isValidTransition(Ticket $ticket, TicketStatus $ticketStatus): bool
    {
        $from = $ticket->status;

        return match ($from) {
            TicketStatus::OPEN =>
                $ticketStatus === TicketStatus::IN_PROGRESS
                && $ticket->assignee_id !== null,
            TicketStatus::IN_PROGRESS =>
                $ticketStatus === TicketStatus::RESOLVED,
            TicketStatus::RESOLVED =>
                in_array($ticketStatus, [
                    TicketStatus::CLOSED,
                    TicketStatus::IN_PROGRESS,
                ], true),
            TicketStatus::CLOSED =>
                false,
            default => false,
        };
    }
}