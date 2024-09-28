<?php

namespace Kyivstar\Api\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Kyivstar\Api\Exceptions\AuthenticationException;

class AuthenticationService
{
    private ?PendingRequest $request;

    /**
     * @param string $clientId
     * @param string $clientSecret
     */
    function __construct(string $clientId, string $clientSecret)
    {
        $this->request = Http::withBasicAuth($clientId, $clientSecret)->asForm();
    }

    /**
     * @param bool $forceRefresh
     * @return array
     * @throws AuthenticationException|RequestException
     */
    public function __invoke(bool $forceRefresh = false): array
    {
        $sep = ' : ';
        $url = 'https://api-gateway.kyivstar.ua/idp/oauth2/token';
        $payload = ['grant_type' => 'client_credentials'];
        $cacheKey = 'kyivstar-api-access-token';

        if (!$forceRefresh) {

            $accessToken = Cache::get($cacheKey);

            if (!empty($accessToken)) {
                return explode($sep, $accessToken);
            }
        }

        $response = $this->request->post($url, $payload);

        if ($response->status() === 401) {
            throw new AuthenticationException($response->json('error_verbose'));
        }

        if ($response->status() !== 200) {
            throw $response->toException();
        }
        
        $accessToken = [ucfirst($response->json('token_type')), $response->json('access_token')];

        Cache::put($cacheKey, join($sep, $accessToken), 60 * 60 * 7);

        return $accessToken;
    }
}
