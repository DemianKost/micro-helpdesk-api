<?php

namespace Src\Domains\Workspaces\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Domains\User\Models\User;

class Workspace extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'description',
        'archived',
        'user_id'
    ];

    /**
     * @return BelongsTo<User, Workspace>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    /**
     * @return HasMany<WorkspaceMember, Workspace>
     */
    public function members(): HasMany
    {
        return $this->hasMany(
            related: WorkspaceMember::class,
            foreignKey: 'workspace_id'
        );
    }
}