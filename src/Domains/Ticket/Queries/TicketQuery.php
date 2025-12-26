<?php

namespace Src\Domains\Ticket\Queries;

use Illuminate\Database\Eloquent\Builder;
use Src\Domains\User\Models\User;
use Src\Domains\Ticket\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketQuery
{
    public function __construct(
        private readonly User $user,
        private readonly array $filters = []
    ) {}

    public function builder(): Builder
    {
        $query = Ticket::query()
            ->with([
                'requester:id,name,email',
                'assignee:id,name,email',
            ]);
        
        $this->applyVisibility($query);
        $this->applySorting($query);

        return $query;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->builder()->paginate($perPage)->withQueryString();
    }

    public function findOrFail(int $ticketId): Ticket
    {
        $query = Ticket::query()
            ->with($this->singleRelations());

        $this->applyVisibility($query);

        return $query->whereKey($ticketId)->firstOrFail();
    }

    private function applyVisibility(Builder $query): void
    {
        if ( $this->user->isAdmin() ) return;

        if ($this->user->isCustomer()) {
            $query->where('requester_id', $this->user->id);
            return;
        }

        if ($this->user->isAgent()) {
            $query->where(function (Builder $q) {
                $q->where('assignee_id', $this->user->id)
                  ->orWhereNull('assignee_id');
            });
        }
    }

    private function applySorting(Builder $query): void
    {
        $sort = (string)($this->filters['sort'] ?? 'latest');

        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'priority':
                $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'status':
                $query->orderBy('status', 'asc')->orderBy('created_at', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    }

    private function singleRelations(): array
    {
        return [
            'requester:id,name,email',
            'assignee:id,name,email',
            'comments.user:id,name,email'
        ];
    }
}