<?php

namespace Src\Domains\Common\Support;

use Illuminate\Support\Facades\RateLimiter;
use Src\Domains\Common\Exceptions\TooManyAttemptsException;

final class RateLimiterManager
{
    /**
     * @param string $key
     * @param int $maxAttempts
     * @param int $decaySeconds
     * @param callable $task
     * @throws TooManyAttemptsException
     */
    public function run(
        string $key,
        int $maxAttempts,
        int $decaySeconds,
        callable $task
    ) {
        $result = RateLimiter::attempt(
            $key,
            $maxAttempts,
            fn () => $task(),
            $decaySeconds
        );

        if ($result === false) {
            $retryAfter = RateLimiter::availableIn($key);
            throw new TooManyAttemptsException($retryAfter);
        }

        return $result;
    }

    /**
     * @param array $limits
     * @param callable $task
     * @throws TooManyAttemptsException
     */
    public function runMany(array $limits, callable $task)
    {
        foreach ($limits as $limit) {
            [$key, $maxAttempts, $decaySeconds] = $limit;

            $allowed = RateLimiter::attempt(
                $key,
                $maxAttempts,
                fn () => true,
                $decaySeconds
            );

            if ($allowed === false) {
                $retryAfter = RateLimiter::availableIn($key);
                throw new TooManyAttemptsException($retryAfter);
            }
        }

        return $task();
    }

    /**
     * @param string $key
     * @param int $maxAttempts
     * @return bool
     */
    public function check(string $key, int $maxAttempts): bool
    {
        return ! RateLimiter::tooManyAttempts($key, $maxAttempts);
    }

    /**
     * @param string $key
     * @return void
     */
    public function clear(string $key): void
    {
        RateLimiter::clear($key);
    }
}