<?php

namespace Kyivstar\Api\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;

class AuthenticationService extends HttpService
{
    protected string $url = 'https://api-gateway.kyivstar.ua/idp/oauth2/token';
    /**
     * @param string $clientId
     * @param string $clientSecret
     */
    function __construct(string $clientId, string $clientSecret)
    {
        parent::__construct(
            fn() => base64_encode($clientId . ':' . $clientSecret),
            ['Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded']
        );
    }

    /**
     * @param bool $forceRefresh
     * @return string
     * @throws RequestException
     */
    public function __invoke(bool $forceRefresh = false): string
    {
        $cacheKey = 'kyivstar-api-access-token';

        if (!$forceRefresh) {

            $accessToken = Cache::get($cacheKey);

            if (!empty($accessToken)) {
                return $accessToken;
            }
        }

        $accessToken = $this->try('post', '', ['grant_type' => 'client_credentials'])
                            ->json('access_token');

        Cache::put($cacheKey, $accessToken, 60 * 60 * 7);

        return $accessToken;
    }
}