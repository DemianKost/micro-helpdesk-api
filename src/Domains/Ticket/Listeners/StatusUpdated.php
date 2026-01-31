<?php

namespace Src\Domains\Ticket\Listeners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Src\Domains\Ticket\Mail\StatusUpdatedMail;
use Src\Domains\Ticket\Events\TicketStatusUpdated;

class StatusUpdated implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public int $tries = 3;
    public int $timeout = 90;

    public function handle(TicketStatusUpdated $event): void
    {
        Log::info("Update ticket status event");
        Log::info(json_encode( $event ));

        Mail::to($event->ticket->assignee->email)
            ->queue(new StatusUpdatedMail($event->ticket));
    }
}