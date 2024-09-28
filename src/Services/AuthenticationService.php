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
     */
    public function __invoke(bool $forceRefresh = false): array
    {
        $ckey = 'kyivstar-api-access-token';

        if (!$forceRefresh) {
            $accessToken = Cache::get($ckey);
            if (!empty($accessToken)) {
                return explode(' : ', $accessToken);
            }
        }

        $response = $this->request->post('https://api-gateway.kyivstar.ua/idp/oauth2/token', 
                                         ['grant_type' => 'client_credentials']);
        
        return $this->is200($response, function($response) use ($ckey) {
            $token = [$response->json('token_type'), $response->json('access_token')];
            Cache::put($ckey, join(' : ', $token), 60 * 60 * 7);
            return $token;
        });
    }
}
