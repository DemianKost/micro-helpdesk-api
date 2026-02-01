<?php

namespace Src\Domains\Ticket\Actions;

use Src\Domains\Common\Support\RateLimiterManager;
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
        private TransactionManager $transactionManager,
        private RateLimiterManager $rateLimiterManager,
    ) {}

    public function execute(array $attributes)
    {
        $this->validator->validateCreate($attributes);

        $userId = auth()->id();
        $ip = request()->ip() ?? 'unknown';
        $workspaceId = (int) ($attributes['workspace_id'] ?? 0);

        $limits = [
            ["rl:tickets:create:ws:{$workspaceId}:user:{$userId}", 10, 60],
            ["rl:tickets:create:ws:{$workspaceId}:ip:" . str_replace(':', '_', $ip), 30, 600],
            ["rl:tickets:create:ws:{$workspaceId}", 200, 3600],
        ];

        return $this->rateLimiterManager->runMany($limits, function () use ($attributes, $userId) {
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
        });
    }
}