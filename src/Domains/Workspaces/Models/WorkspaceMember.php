<?php

namespace Src\Domains\Workspaces\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Domains\User\Models\User;
use Src\Domains\Workspaces\Enums\WorkspaceRole;

class WorkspaceMember extends Model
{
    use HasUuids;

    protected $fillable = [
        'workspace_id',
        'user_id',
        'role'
    ];

    /**
     * @return array{role: string}
     */
    protected function casts(): array
    {
        return [
            'role' => WorkspaceRole::class,
        ];
    }

    /**
     * @return BelongsTo<Workspace, WorkspaceMember>
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(
            related: Workspace::class,
            foreignKey: 'workspace_id'
        );
    }

    /**
     * @return BelongsTo<User, WorkspaceMember>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }
}