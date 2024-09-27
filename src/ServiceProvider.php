<?php

namespace Kyivstar\Api;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    private static string $packageName = 'kyivstar-api';

    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/blueprint.php' => config_path(self::$packageName.'.php'),
        ], self::$packageName);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', self::$packageName);

        $this->app->singleton(KyivstarApi::class, function (Application $app) {
            return new KyivstarApi($app['config'][self::$packageName]);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [KyivstarApi::class];
    }
}