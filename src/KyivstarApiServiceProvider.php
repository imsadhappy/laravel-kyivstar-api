<?php

namespace Kyivstar\Api;

use Illuminate\Support\ServiceProvider;

class KyivstarApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {  
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::configFile() => config_path('kyivstar-api.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(self::configFile(), 'kyivstar-api');
        $this->app->singleton(KyivstarApi::class, fn () => new KyivstarApi(config('kyivstar-api')));
    }

    private static function configFile()
    {
        return __DIR__.'/../config/kyivstar-api.php';
    }
}
