<?php

namespace App\Providers;

use App\Domains\Account\BalanceStrategyFactory;
use App\Domains\Account\Services\LedgerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('ledger', function ($app) {
            return new LedgerService($app->make(BalanceStrategyFactory::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $domainPath = $this->app->basePath('app/Domains/Account');
        $this->loadMigrationsFrom([
            $domainPath.'/Database/Migrations',
        ]);

    }
}
