<?php

namespace Src\Domains\Ticket\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domains\Ticket\Enums\TicketAction;
use Src\Domains\User\Models\User;

class TicketAudit extends Model
{
    use HasUuids;

    /**
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'actor_id',
        'action',
        'from_status',
        'to_status',
        'meta'
    ];

    /**
     * @return array{action: string}
     */
    protected function casts(): array
    {
        return [
            'action' => TicketAction::class
        ];
    }

    /**
     * @return BelongsTo<Ticket, TicketAudit>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            Ticket::class,
            'ticket_id'
        );
    }

    /**
     * @return BelongsTo<User, TicketAudit>
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'actor_id'
        );
    }
}