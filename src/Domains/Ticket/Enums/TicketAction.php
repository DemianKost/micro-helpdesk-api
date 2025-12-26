<?php

namespace Src\Domains\Ticket\Enums;

enum TicketAction: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case ASSIGNED = 'assigned';
    case STATUS_CHANGED = 'status_changed';
    case COMMENTED = 'commented';
    case PRIORITY_CHANGED = 'priority_changed';
}