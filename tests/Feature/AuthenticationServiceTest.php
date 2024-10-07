<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Feature;

use Kyivstar\Api\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Kyivstar\Api\Services\AuthenticationService;

class AuthenticationServiceTest extends TestCase
{
    public function testAuthenticationService()
    {
        $version = $this->getApiVersion();

        if ('v1beta' === $version) {
            $this->runV1BetaTest();
        } else {
            $this->markTestSkipped(__CLASS__ . " for $version not found.");
        }
    }

    private function runV1BetaTest()
    {
        $cacheKey = 'kyivstar-api-access-token';
        $payload = array_values(self::setupAuthenticationFacade());

        /**
         * Check caching and value formatting
         */
        Cache::shouldReceive('get')->once()->with($cacheKey)->andReturnNull();
        Cache::shouldReceive('put')->once()->with($cacheKey,
                    join(' : ', $payload),
                    AuthenticationService::TTL);

        $request = new AuthenticationService('foo', 'bar');

        $this->assertEquals($payload, $request());
    }


    /**
     * Facade used by other service test (version-agnostic)
     *
     * @param string $version
     * @return array
     */
    public static function setupAuthenticationFacade(string $version = 'v1beta'): array
    {
        $url = 'https://api-gateway.kyivstar.ua/idp/oauth2/token';
        $payload = [
            'token_type' => fake()->word(),
            'access_token' => fake()->password()
        ];

        Http::fake([
            $url => Http::response($payload, 200)
        ]);

        return $payload;
    }
}