<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Exceptions\ConfigException;

class ConfigValidatorTest extends TestCase
{
    private function getRandomConfigParam(?string $withValue = null): array
    {
        $config = config()->get($this->configKey);
        $key = array_keys($config)[rand(0, count($config)-1)];

        return [$key => is_null($withValue) ? $config[$key] : $withValue];
    }

    private function expectConfigException(int $code = 0): self
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode($code);

        return $this;
    }

    public function testConfigParamMissing()
    {
        $this->expectConfigException(ConfigException::MISSING_CONFIG)
                ->newApiInstance([], $this->getRandomConfigParam());
    }

    public function testConfigEmptyValue()
    {
        $this->expectConfigException(ConfigException::EMPTY_VALUE)
                ->newApiInstance($this->getRandomConfigParam(''));
    }

    public function testConfigInvalidVersion()
    {
        $this->expectConfigException(ConfigException::INVALID_VERSION)
                ->newApiInstance(['version' => 'invalid']);
    }

    public function testConfigInvalidServer()
    {
        $this->expectConfigException(ConfigException::INVALID_SERVER)
                ->newApiInstance(['server' => 'invalid']);
    }
}
