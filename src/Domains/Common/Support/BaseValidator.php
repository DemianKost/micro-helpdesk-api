<?php

namespace Src\Domains\Common\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BaseValidator
{
    protected function validate(array $data, array $rules, array $messages = []): array
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            Log::warning('Validation failed', [
                'errors'   => $validator->errors()->toArray(),
                'data'     => $this->safeData($data),
                'rules'    => $rules,
                'messages' => $messages,
            ]);
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    protected function safeData(array $data): array
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'token'];

        return collect($data)->map(function ($value, $key) use ($sensitiveKeys) {
            return in_array($key, $sensitiveKeys, true) ? '***' : $value;
        })->toArray();
    }
}