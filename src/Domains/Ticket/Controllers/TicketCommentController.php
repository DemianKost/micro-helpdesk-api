<?php

namespace Src\Domains\Ticket\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Domains\Common\Support\ActionBus;
use Src\Domains\Ticket\Actions\TicketComment\CreateTicketComment;
use Src\Domains\Ticket\Actions\TicketComment\UpdateTicketComment;
use Src\Domains\Ticket\Models\TicketComment;

class TicketCommentController
{
    public function __construct(
        private ActionBus $actionBus
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $ticketComment = $this->actionBus->call(
            CreateTicketComment::class,
            $request->all()
        );

        return response()
            ->json([
                'success' => true,
                'message' => 'Comment added',
                'comment' => $ticketComment
            ]);
    }

    /**
     * @param Request $request
     * @param TicketComment $ticketComment
     * @return JsonResponse
     */
    public function update(Request $request, TicketComment $ticketComment): JsonResponse
    {
        $ticketComment = $this->actionBus->call(
            UpdateTicketComment::class,
            $request->all(),
            $ticketComment
        );

        return response()
            ->json([
                'success' => true,
                'message' => 'Comment updated',
                'comment' => $ticketComment
            ]);
    }
}