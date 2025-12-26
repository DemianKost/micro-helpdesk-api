<?php

namespace Src\Domains\Ticket\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Domains\Common\Controllers\Controller;
use Src\Domains\Ticket\Models\Ticket;
use Src\Domains\Ticket\Queries\TicketQuery;
use Src\Domains\Ticket\Services\TicketService;

class TicketController extends Controller
{
    public function __construct(
        private TicketService $service
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $tickets = (new TicketQuery(
            user: auth()->user(),
            filters: $request->only(['status','priority','assignee_id','requester_id','mine','q','sort','created_from','created_to'])
        ))->paginate(20);

        return response()
            ->json([
                'success' => true,
                'tickets' => $tickets
            ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $ticket = $this->service->create($request->all());

        return response()
            ->json([
                'success' => true,
                'ticket' => $ticket
            ]);
    }

    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return JsonResponse
     */
    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $ticket = $this->service->update($request->all(), $ticket);

        return response()
            ->json([
                'success' => true,
                'ticket' => $ticket
            ]);
    }
    
    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $ticket = (new TicketQuery(
            auth()->user()
        ))->findOrFail($id);

        return response()
            ->json([
                'success' => true,
                'ticket' => $ticket
            ]);
    }

    /**
     * @param Request $request
     * @param Ticket $ticket
     * @return JsonResponse
     */
    public function assign(Request $request, Ticket $ticket): JsonResponse
    {
        $this->service->assign($request->all(), $ticket);

        return response()
            ->json([
                'success' => true
            ]);
    }

    /**
     * @param Ticket $ticket
     * @return JsonResponse
     */
    public function delete(Ticket $ticket): JsonResponse
    {
        $this->service->delete($ticket);

        return response()
            ->json([
                'success' => true
            ]);
    }
}