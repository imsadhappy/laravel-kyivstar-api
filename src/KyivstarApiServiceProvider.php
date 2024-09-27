<?php

namespace Kyivstar\Api;

use Illuminate\Support\ServiceProvider;

class KyivstarApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {  
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('kyivstar-api.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'kyivstar-api');

        $this->app->singleton(KyivstarApi::class, fn () => new KyivstarApi(config('kyivstar-api')));
    }
}
