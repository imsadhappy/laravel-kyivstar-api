<?php

namespace Kyivstar\Api\Tests;

abstract class VersionedTestCase extends TestCase
{
    abstract protected function runV1BetaTest();

    protected function buildApiEndpoint(string $endpoint = ''): string
    {
        $version = $this->getApiVersion();

        if ('v1beta' === $version) {
            return "https://api-gateway.kyivstar.ua/mock/rest/$version/$endpoint";
        } else {
            $this->markTestSkipped(get_called_class() . " for $version not found.");
        }
    }

    protected function runVersionTest()
    {
        $version = $this->getApiVersion();

        if ('v1beta' === $version) {
            $this->runV1BetaTest();
        } else {
            $this->markTestSkipped(get_called_class() . " for $version not found.");
        }
    }
}