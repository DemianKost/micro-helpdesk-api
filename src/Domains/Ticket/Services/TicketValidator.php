<?php

namespace Src\Domains\Ticket\Services;

use Src\Domains\Common\Support\BaseValidator;

final class TicketValidator extends BaseValidator
{
    public function validateCreate(array $attributes): array
    {
        return $this->validate($attributes, [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority'    => ['required', 'in:low,medium,high'],
            'status'      => ['sometimes', 'in:open,pending,closed'],
        ]);
    }

    public function validateUpdate(array $attributes): array
    {
        return $this->validate($attributes, [
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'priority'    => ['sometimes', 'in:low,medium,high'],
            'status'      => ['sometimes', 'in:open,pending,closed'],
        ]);
    }

    public function validateAssign(array $attributes): array
    {
        return $this->validate($attributes, [
            'assignee_id' => ['required', 'string', 'exists:users,id']
        ]);
    }
}