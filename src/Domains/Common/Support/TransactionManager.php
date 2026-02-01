<?php

namespace Src\Domains\Common\Support;

use Closure;
use Illuminate\Support\Facades\DB;

final class TransactionManager
{
    public function run(
        Closure $callback,
        int $attempts = 1,
        ?string $connection = null
    ): mixed {
        $db = $this->getConnection($connection);
        
        return $db->transaction(function () use ($callback, $db) {
            return $callback($db);
        }, $attempts);
    }

    public function afterCommit(Closure $callback, ?string $connection = null): void
    {
        $db = $this->getConnection($connection);

        $db->afterCommit($callback);
    }

    private function getConnection(?string $connection = null)
    {
        return $connection
            ? DB::connection($connection)
            : DB::connection();
    }
}