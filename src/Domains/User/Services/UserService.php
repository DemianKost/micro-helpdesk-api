<?php

namespace Src\Domains\User\Services;

use Src\Domains\Common\Support\TransactionManager;
use Src\Domains\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Src\Domains\Workspaces\Enums\WorkspaceRole;
use Src\Domains\Workspaces\Models\Workspace;
use Src\Domains\Workspaces\Models\WorkspaceMember;

class UserService
{
    public function __construct(
        private TransactionManager $transactionManager
    ) {}

    public function create(array $attributes)
    {
        return $this->transactionManager->run( function() use($attributes) {
            $user = User::create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => Hash::make($attributes['password']),
                'role' => $attributes['role']
            ]);

            $token = $user->createToken('default')->plainTextToken;

            $workspace = Workspace::create([
                'name' => 'Default workspace',
                'user_id' => auth()->id()
            ]);

            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'role' => WorkspaceRole::OWNER->value
            ]);

            return [
                'token' => $token,
                'user' => $user
            ];
        });
    }
}