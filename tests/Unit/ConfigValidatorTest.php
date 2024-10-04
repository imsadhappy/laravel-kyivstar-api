<?php

namespace Kyivstar\Api\Tests\Unit;

use Kyivstar\Api\Tests\TestCase;
use Kyivstar\Api\Exceptions\ConfigException;

class ConfigValidatorTest extends TestCase
{
    private array $configArgs = ['version', 'server', 'alpha_name', 'client_id', 'client_secret'];

    private function expectConfigException(int $code = 0): self
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionCode($code);

        return $this;
    }

    public function testConfigParamMissing()
    {
        $this->expectConfigException(ConfigException::MISSING_CONFIG)
                ->newApiInstance([], [fake()->randomElement($this->configArgs)]);
    }

    public function testConfigEmptyValue()
    {
        $this->expectConfigException(ConfigException::EMPTY_VALUE)
                ->newApiInstance([fake()->randomElement($this->configArgs) => '']);
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
