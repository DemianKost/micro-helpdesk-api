<?php

namespace Src\Domains\Ticket\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Src\Domains\Ticket\Events\TicketCommented;
use Src\Domains\Ticket\Events\TicketResolved;
use Src\Domains\Ticket\Events\TicketStatusUpdated;
use Src\Domains\Ticket\Listeners\SendCommentNotification;
use Src\Domains\Ticket\Listeners\SendResolvedNotification;
use Src\Domains\Ticket\Listeners\StatusUpdated;

class TicketServicePrvovider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        Event::listen(TicketResolved::class, SendResolvedNotification::class);
        Event::listen(TicketStatusUpdated::class, StatusUpdated::class);
        Event::listen(TicketCommented::Class, SendCommentNotification::class);
    }
}