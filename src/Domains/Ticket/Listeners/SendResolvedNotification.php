<?php

namespace Src\Domains\Ticket\Listeners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Src\Domains\Ticket\Events\TicketResolved;
use Illuminate\Support\Facades\Log;

class SendResolvedNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public int $tries = 3;
    public int $timeout = 90;

    public function handle(TicketResolved $event): void
    {
        Log::info('testing stuff');
    }
}