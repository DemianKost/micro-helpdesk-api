<?php

namespace Src\Domains\Workspaces\Enums;

enum WorkspaceRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
    case MEMBER = 'member';
}