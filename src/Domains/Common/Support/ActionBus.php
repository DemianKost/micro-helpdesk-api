<?php

namespace Src\Domains\Common\Support;

use Illuminate\Container\Container;
use InvalidArgumentException;

final class ActionBus
{
    public function __construct(
        private Container $container
    ) {}

    public function call(string $actionClass, mixed ...$args): mixed
    {
        $action = $this->container->make($actionClass);

        if ( method_exists($action, 'handle') ) {
            return $action->handle(...$args);
        }

        if ( method_exists($action, 'execute') ) {
            return $action->execute(...$args);
        }

        throw new InvalidArgumentException("Action [$actionClass] must have handle() or execute().");
    }

    public function run(string $actionClass, mixed ...$args): mixed
    {
        $transactionManager = $this->container->make(TransactionManager::class);

        return $transactionManager->run(function () use ($actionClass, $args) {
            return $this->call($actionClass, ...$args);
        });
    }
}