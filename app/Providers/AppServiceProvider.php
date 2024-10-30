<?php

namespace App\Providers;

use App\CustomSqlServerConnector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('db.connector.sqlsrv', CustomSqlServerConnector::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        if (str_contains(Config::get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
