<?php

namespace Kyivstar\Api\Services;

use Kyivstar\Api\Traits\HttpValidator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\PendingRequest;

class AuthenticationService
{
    use HttpValidator;

    private ?PendingRequest $request;

    private string $endpoint = 'https://api-gateway.kyivstar.ua/idp/oauth2/token';

    private array $payload = ['grant_type' => 'client_credentials'];

    private string $cacheKey = 'kyivstar-api-access-token';

    /**
     * Constructs new authentication request
     *
     * @param string $clientId
     * @param string $clientSecret
     */
    function __construct(string $clientId, string $clientSecret)
    {
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
        
        return $this->is200($this->request->post($this->endpoint, $this->payload), function ($response): array {

            $token = [$response->json('token_type'), $response->json('access_token')];

            Cache::put($this->cacheKey, join(' : ', $token), 60 * 60 * 7);

            return $token;
        });
    }
}
