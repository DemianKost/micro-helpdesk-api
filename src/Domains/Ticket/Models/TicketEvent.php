<?php

namespace Src\Domains\Ticket\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domains\Ticket\Models\Ticket;

class TicketEvent extends Model
{
    use HasUuids;

    protected $fillable = [
        'ticket_id',
        'seq',
        'type',
        'data',
        'meta'
    ];

    /**
     * @return BelongsTo<Ticket, TicketEvent>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            related: Ticket::class,
            foreignKey: 'ticket_id'
        );
    }
}