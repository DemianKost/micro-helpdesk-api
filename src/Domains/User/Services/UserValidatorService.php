<?php

namespace Src\Domains\User\Services;

use Src\Domains\Common\Support\BaseValidator;

class UserValidatorService extends BaseValidator
{
    public function validateLogin(array $attributes): array
    {
        return $this->validate($attributes, [
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);
    }

    public function validateCreate(array $attributes): array
    {
        return $this->validate($attributes, [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
            'role' => ['required', 'string']
        ]);
    }
}