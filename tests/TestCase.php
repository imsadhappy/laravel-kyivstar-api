<?php

namespace Kyivstar\Api\Tests;

use Kyivstar\Api\KyivstarApi;
use Kyivstar\Api\KyivstarApiServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    private array $config;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            KyivstarApiServiceProvider::class,
        ];
    }

    protected function getApiVersion(): string
    {
        return $this->config['version'];
    }

    protected function getEnvironmentSetUp($app)
    {
        try {
            $config = $app['config']->get('kyivstar-api');
        } catch (\Exception $e) {
            $config = [
                'version' => 'v1beta', //very first version
                'alpha_name' => fake()->word()
            ];
        }

        $this->config = array_merge($config, [
            'server' => 'mock', //always use mock
            'client_id' => 'foo', //not important
            'client_secret' => 'bar', //as long as not empty
        ]);

        $app['config']->set('kyivstar-api', $this->config);
    }

    /**
     * @param array|null $mergeConfig
     * @param array|null $diffConfig
     */
    protected function newApiInstance(?array $mergeConfig = [],
                                      ?array $diffConfig = []): KyivstarApi
    {
        $config = $this->config;

        if (!empty($mergeConfig)) {
            $config = array_merge($config, $mergeConfig);
        }

        if (!empty($diffConfig)) {
            $config = array_filter($config, fn ($key) => !in_array($key, $diffConfig), ARRAY_FILTER_USE_KEY);
        }

        return new KyivstarApi($config);
    }
}