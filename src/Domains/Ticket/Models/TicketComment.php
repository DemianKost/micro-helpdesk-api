<?php

namespace Src\Domains\Ticket\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domains\User\Models\User;

class TicketComment extends Model
{
    use HasUuids;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'body'
    ];

    /**
     * @return BelongsTo<Ticket, TicketComment>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            Ticket::class,
            'ticket_id'
        );
    }

    /**
     * @return BelongsTo<User, TicketComment>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}