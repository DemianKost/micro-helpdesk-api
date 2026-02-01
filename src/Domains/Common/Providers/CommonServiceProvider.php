<?php

namespace Src\Domains\Common\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domains\Common\Support\ActionBus;
use Src\Domains\Common\Support\RateLimiterManager;
use Src\Domains\Common\Support\TransactionManager;

class CommonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ActionBus::class);
        $this->app->singleton(TransactionManager::class);
        $this->app->singleton(RateLimiterManager::class);

        require_once base_path() . '/src/Domains/Common/Utils/GlobalFunctions.php';
    }

    public function boot(): void
    {
        
    }
}