<?php

namespace Kyivstar\Api\Tests\Feature;

use Kyivstar\Api\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Kyivstar\Api\Services\AuthenticationService;

class AuthenticationServiceTest extends TestCase
{
    /**
     * Check caching and value formatting
     */
    public function testAuthentication()
    {
        /**
         * Maybe in other versions (not v1beta)
         * other endpoint URL or different payload will be used.
         * So we can add switch ($this->getApiVersion()) here later.
         */
        $url = 'https://api-gateway.kyivstar.ua/idp/oauth2/token';
        $cacheKey = 'kyivstar-api-access-token';
        $payload = [
            'token_type' => fake()->word(),
            'access_token' => fake()->password()
        ];

        Http::fake([
            $url => Http::response($payload, 200)
        ]);
        Cache::shouldReceive('get')->once()->with($cacheKey)->andReturnNull();
        Cache::shouldReceive('put')->once()->with($cacheKey,
                                                join(' : ', array_values($payload)),
                                                60 * 60 * 7);

        $request = new AuthenticationService('foo', 'bar');

        $this->assertEquals(array_values($payload), $request());
    }
}