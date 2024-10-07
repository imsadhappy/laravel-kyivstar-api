<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Feature;

use Kyivstar\Api\Tests\VersionedTestCase;
use Kyivstar\Api\Services\AuthenticationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AuthenticationServiceTest extends VersionedTestCase
{
    public function testAuthenticationService()
    {
        $this->runVersionTest();
    }

    protected function runV1BetaTest()
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
        if ('v1beta' === $version) {

            $url = 'https://api-gateway.kyivstar.ua/idp/oauth2/token';
            $payload = [
                'token_type' => fake()->word(),
                'access_token' => fake()->password()
            ];

            Http::fake([
                $url => Http::response($payload, 200)
            ]);

            return $payload;

        } else {

            throw new \Exception("AuthenticationFacade for $version not implemented.");
        }
    }
}