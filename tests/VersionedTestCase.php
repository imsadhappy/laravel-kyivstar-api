<?php

namespace Kyivstar\Api\Tests;

use Illuminate\Support\Facades\Http;

abstract class VersionedTestCase extends TestCase
{
    abstract protected function runV1BetaTest(array $authentication);

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
            $this->runV1BetaTest(self::setupAuthenticationFacade($version));
        } else {
            $this->markTestSkipped(get_called_class() . " for $version not found.");
        }
    }

    /**
     * Facade used by other service test (version-agnostic)
     *
     * @param string $version
     * @return array
     */
    private static function setupAuthenticationFacade(string $version = 'v1beta'): array
    {
        if ('v1beta' === $version) {

            $payload = [
                'token_type' => fake()->word(),
                'access_token' => fake()->password()
            ];

            Http::fake([
                'https://api-gateway.kyivstar.ua/idp/oauth2/token' => Http::response($payload, 200)
            ]);

            return $payload;

        } else {

            throw new \Exception("AuthenticationFacade for $version not implemented.");
        }
    }
}