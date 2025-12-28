<?php

namespace Src\Domains\Ticket\Services;

use Src\Domains\Common\Support\BaseValidator;

class TicketCommentValidator extends BaseValidator
{
    public function validateCreate(array $attributes): array
    {
        return $this->validate($attributes, [
            'ticket_id' => ['required', 'exists:tickets,id'],
            'body' => ['required', 'string', 'max:2048']
        ]);
    }

    public function validateUpdate(array $attributes): array
    {
        return $this->validate($attributes, [
            'body' => ['required', 'string', 'max:2048']
        ]);
    }
}