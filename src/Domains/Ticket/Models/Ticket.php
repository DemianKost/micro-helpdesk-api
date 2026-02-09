<?php

namespace Src\Domains\Ticket\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domains\Ticket\Enums\TicketPriority;
use Src\Domains\Ticket\Enums\TicketStatus;
use Src\Domains\User\Models\User;

class Ticket extends Model
{
    use HasUuids;

    /**
     * @var array
     */
    protected $fillable = [
        'requester_id',
        'assignee_id',
        'title',
        'description',
        'priority',
        'status',
        'resolved_at'
    ];

    /**
     * @return array{priority: string, status: string}
     */
    public function casts(): array
    {
        return [
            'priority' => TicketPriority::class,
            'status' => TicketStatus::class,
            'resolved_at' => 'datetime'
        ];
    }

    /**
     * @return HasMany<TicketComment, Ticket>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(
            related: TicketComment::class,
            foreignKey: 'ticket_id'
        );
    }

    /**
     * @return BelongsTo<User, Ticket>
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'requester_id'
        );
    }

    /**
     * @return BelongsTo<User, Ticket>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'assignee_id'
        );
    }

    /**
     * @return HasMany<TicketEvent, Ticket>
     */
    public function events(): HasMany
    {
        return $this->hasMany(
            related: TicketEvent::class,
            foreignKey: 'ticket_id'
        );
    }
}