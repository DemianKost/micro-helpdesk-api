<?php

namespace Src\Domains\User\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Src\Domains\Ticket\Models\TicketComment;
use Src\Domains\User\Enums\UserRole;
use Src\Domains\Workspaces\Models\WorkspaceMember;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasUuids, HasFactory, HasApiTokens, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * @return HasMany<TicketComment, User>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(
            TicketComment::class,
            'ticket_id'
        );
    }

    /**
     * @return HasMany<User, User>
     */
    public function ownedWorkspaces(): HasMany
    {
        return $this->hasMany(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    /**
     * @return HasMany<WorkspaceMember, User>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(
            related: WorkspaceMember::class,
            foreignKey: 'user_id'
        );
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role == UserRole::ADMIN;
    }

    /**
     * @return bool
     */
    public function isAgent(): bool
    {
        return $this->role == UserRole::AGENT;
    }

    /**
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->role == UserRole::CUSTOMER;
    }

    /**
     * @return UserFactory
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
