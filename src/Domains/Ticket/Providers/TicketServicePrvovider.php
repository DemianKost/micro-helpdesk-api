<?php

namespace Src\Domains\Ticket\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Src\Domains\Ticket\Events\TicketResolved;
use Src\Domains\Ticket\Listeners\SendResolvedNotification;

class TicketServicePrvovider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        Event::listen(
            TicketResolved::class,
            SendResolvedNotification::class
        );
    }
}