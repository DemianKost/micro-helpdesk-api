<?php

namespace Src\Domains\Common\Exceptions;

use RuntimeException;

final class TooManyAttemptsException extends RuntimeException
{
    public function __construct(public readonly int $retryAfterSeconds)
    {
        parent::__construct('Too many attempts.');
    }
}