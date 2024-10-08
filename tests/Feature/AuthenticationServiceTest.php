<?php declare(strict_types=1);

namespace Kyivstar\Api\Tests\Feature;

use Kyivstar\Api\Tests\VersionedTestCase;
use Kyivstar\Api\Services\AuthenticationService;
use Illuminate\Support\Facades\Cache;

class AuthenticationServiceTest extends VersionedTestCase
{
    public function testAuthenticationService()
    {
        $this->runVersionTest();
    }

    protected function runV1BetaTest(array $authentication)
    {
        $cacheKey = 'kyivstar-api-access-token';
        $payload = array_values($authentication);

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
}