<?php

namespace Kyivstar\Api\Tests;

use Kyivstar\Api\KyivstarApi;
use Kyivstar\Api\KyivstarApiServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected string $configKey = 'kyivstar-api';

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set($this->configKey, [
            'version' => 'v1beta',
            'server' => 'mock',
            'alpha_name' => 'messagedesk',
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            KyivstarApiServiceProvider::class,
        ];
    }

    /**
     * @param array|null $mergeConfig
     * @param array|null $diffConfig
     */
    protected function newApiInstance(?array $mergeConfig = [],
                                      ?array $diffConfig = []): KyivstarApi
    {
        $config = config()->get($this->configKey);

        if (!empty($mergeConfig)) {
            $config = array_merge($config, $mergeConfig);
        }

        if (!empty($diffConfig)) {
            $config = array_diff($config, $diffConfig);
        }

        return new KyivstarApi($config);
    }
}