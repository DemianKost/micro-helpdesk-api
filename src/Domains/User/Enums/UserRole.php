<?php

namespace Src\Domains\User\Enums;

enum UserRole: string
{
    case CUSTOMER = 'customer';
    case AGENT = 'agent';
    case ADMIN = 'admin';
}