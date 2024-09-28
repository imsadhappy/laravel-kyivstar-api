<?php

namespace Kyivstar\Api\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;

class AuthenticationService
{
    private ?PendingRequest $request;

    private ?Response $response;
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
     * @throws RequestException
     */
    public function __invoke(bool $forceRefresh = false): array
    {
        $cacheKey = 'kyivstar-api-access-token';
        $url = 'https://api-gateway.kyivstar.ua/idp/oauth2/token';
        $payload = ['grant_type' => 'client_credentials'];

        if (!$forceRefresh) {

            $accessToken = Cache::get($cacheKey);

            if (!empty($accessToken)) {
                return explode(':', $accessToken);
            }
        }

        $this->response = $this->request->post($url, $payload);

        if ($this->response->status() !== 200) {
            throw $this->response->toException();
        }
        
        $accessToken = [ucfirst($this->response->json('token_type')), $this->response->json('access_token')];

        Cache::put($cacheKey, join(':', $accessToken), 60 * 60 * 7);

        return $accessToken;
    }
}
