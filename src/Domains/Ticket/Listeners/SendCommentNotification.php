<?php

namespace Src\Domains\Ticket\Listeners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Src\Domains\Ticket\Events\TicketCommented;
use Src\Domains\Ticket\Mail\Comments\TicketCommentedMail;

class SendCommentNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public int $tries = 3;
    public int $timeout = 90;

    public function handle(TicketCommented $event): void
    {
        Mail::to($event->ticket->assignee->email)
            ->queue(new TicketCommentedMail($event->ticket));
    }
}