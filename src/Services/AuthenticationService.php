<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Traits\HttpValidator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\PendingRequest;

final class AuthenticationService
{
    use HttpValidator;

    const TTL = 60 * 60 * 7;

    private ?PendingRequest $request;

    private string $url;

    private array $payload;

    private string $cacheKey;

    /**
     * Constructs new authentication request
     *
     * @param string $clientId
     * @param string $clientSecret
     */
    function __construct(string $clientId, string $clientSecret)
    {
        /**
         * Maybe in other versions (not v1beta)
         * other endpoint URL or different payload will be used.
         * So we can add `switch (config()->get("kyivstar-api.version")) {...}` here later.
         */
        $this->url = 'https://api-gateway.kyivstar.ua/idp/oauth2/token';
        $this->payload = ['grant_type' => 'client_credentials'];
        $this->cacheKey = 'kyivstar-api-access-token';
        $this->request = Http::withBasicAuth($clientId, $clientSecret)->asForm();
    }

    /**
     * Gets accessToken from cache or
     * performs constructed authentication request
     *
     * @param bool $forceRefresh
     * @return array
     */
    public function __invoke(bool $forceRefresh = false): array
    {
        if (!$forceRefresh) {

            $token = Cache::get($this->cacheKey);

            if (!empty($token)) {
                return explode(' : ', $token);
            }
        }
        
        return $this->is200($this->request->post($this->url, $this->payload), function ($response): array {

            $token = [$response->json('token_type'), $response->json('access_token')];

            Cache::put($this->cacheKey, join(' : ', $token), self::TTL);

            return $token;
        });
    }
}
