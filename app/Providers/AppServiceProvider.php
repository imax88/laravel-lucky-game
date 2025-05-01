<?php

namespace App\Providers;

use App\Providers\Contracts\GameServiceInterface;
use App\Providers\Contracts\WinningsCalculatorInterface;
use App\Providers\Services\GameService;
use App\Providers\Services\WinningsCalculator;
use Illuminate\Support\ServiceProvider;

/**
 * Application service provider for registering application services.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GameServiceInterface::class, function ($app) {
            return new GameService($app->make(WinningsCalculatorInterface::class));
        });

        $this->app->singleton(WinningsCalculatorInterface::class, function ($app) {
            return new WinningsCalculator();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
